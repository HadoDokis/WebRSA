<?php
	/**
	 * Fichier source de la classe Jetons2Component.
	 *
	 * PHP 5.3
	 *
	 * @package       app.Controller.Component
	 */

	/**
	 * La classe Jetons2Component permet de mettre des jetons (des locks fonctionnels) sur des
	 * enregistrements de la table dossiers pour un utilisateur particulier.
	 *
	 * @package       app.Controller.Component
	 */
	class Jetons2Component extends Component
	{
		/**
		 * Controller using this component.
		 *
		 * @var Controller
		 */
		public $controller = null;

		/**
		 * On a besoin d'un esession.
		 *
		 * @var array
		 */
		public $components = array( 'Session' );

		/**
		 * On initialise le modèle Jeton si Configure::write( 'Jetons2.disabled' ) n'est pas à true.
		 *
		 * @param Controller $controller Controller with components to initialize
		 * @return void
		 */
		public function initialize( &$controller, $settings = array() ) {
			$this->controller = &$controller;

			if( Configure::read( 'Jetons2.disabled' ) ) {
				return;
			}

			$this->Jeton = ClassRegistry::init( 'Jeton' );
		}

		/**
		 * On essaie d'acquérir un (ensemble de) jeton(s) pour l'utilisateur connecté au sein d'une transaction.
		 *
		 * Si un jeton est locké par un autre utilisateur, on annule la transaction et on en informe l'utilisateur
		 * via ue page d'erreur.
		 *
		 * @param mixed $dossiers Un id de dossier ou un array d'ids de dossiers.
		 * @return boolean
		 */
		public function get( $dossiers ) {
			if( Configure::read( 'Jetons2.disabled' ) ) {
				return true;
			}

			$dossiers = (array) $dossiers;

			$this->Jeton->begin();

			$sq = $this->Jeton->sq(
				array(
					'alias' => 'jetons',
					'fields' => array(
						'jetons.id',
						'jetons.dossier_id',
						'jetons.php_sid',
						'jetons.user_id',
						'jetons.modified',
					),
					'conditions' => array(
						'dossier_id'  => $dossiers,
					),
					'recursive' => -1
				)
			);

			$sq = "{$sq} FOR UPDATE";

			$results =@$this->Jeton->query( $sq );
			if( $results === false ) {
				$this->Jeton->rollback();
				$this->controller->cakeError( 'error500' );
				return;
			}

			$jetonsObtenus = Set::combine( $results, '{n}.jetons.dossier_id', '{n}.jetons' );

			foreach( $dossiers as $dossier ) {
				$jetonObtenu = ( isset( $jetonsObtenus[$dossier] ) ? $jetonsObtenus[$dossier] : null );

				$dossierNonVerrouille = (
					is_null( $jetonObtenu )
					|| empty( $jetonObtenu['php_sid'] )
					|| trim( $jetonObtenu['php_sid'] ) ==  $this->Session->id() // FIXME: VARCHAR
					|| ( strtotime( $jetonObtenu['modified'] ) < strtotime( '-'.readTimeout().' seconds' ) )
				);

				if( $dossierNonVerrouille ) {
					$jeton = array(
						'Jeton' => array(
							'dossier_id' => $dossier,
							'php_sid' => $this->Session->id(),
							'user_id' => $this->Session->read( 'Auth.User.id' ),
						)
					);

					if( !empty( $jetonObtenu['id'] ) ) {
						$jeton['Jeton']['id'] = $jetonObtenu['id'];
					}

					$this->Jeton->create( $jeton );
					if( !$this->Jeton->save() ) {
						$this->Jeton->rollback();
						$this->controller->cakeError( 'error500' );
						// return
					}
				}
				else {
					$this->Jeton->rollback();

					$lockingUser = $this->Jeton->User->find(
						'first',
						array(
							'conditions' => array(
								'User.id' => $jetonObtenu['user_id']
							),
							'recursive' => -1
						)
					);

					$this->controller->cakeError(
						'lockedDossier',
						array(
							'time' => ( strtotime( $jetonObtenu['modified'] ) + readTimeout() ),
							'user' => $lockingUser['User']['username']
						)
					);
					return;
				}
			}

			$this->Jeton->commit();

			return true;
		}

		/**
		 * On relache un (ensemble de) jeton(s).
		 *
		 * @param mixed $dossiers Un id de dossier ou un array d'ids de dossiers.
		 * @return boolean
		 */
		public function release( $dossiers ) {
			if( Configure::read( 'Jetons2.disabled' ) ) {
				return true;
			}

			$dossiers = (array) $dossiers;

			$this->Jeton->begin();

			$conditions = array( 'dossier_id'  => $dossiers );

			$sq = $this->Jeton->sq(
				array(
					'alias' => 'jetons',
					'fields' => array(
						'jetons.id',
						'jetons.dossier_id',
						'jetons.php_sid',
						'jetons.user_id',
						'jetons.modified',
					),
					// INFO: si on get et que l'on release dans la même page, il ne fera pas le second
					// SELECT même si cacheQueries est à false.
					'conditions' => $conditions + array( '1 = 1' ),
					'recursive' => -1
				)
			);

			$sq = "{$sq} FOR UPDATE";

			$results =@$this->Jeton->query( $sq );
			if( $results === false ) {
				$this->Jeton->rollback();
				die( 'Erreur étrange' );
				return false;
			}

			if( $this->Jeton->deleteAll( $conditions, false, false ) == false ) {
				$this->Jeton->rollback();
				die( 'Erreur étrange' );
				return false;
			}

			$this->Jeton->commit();

			return true;
		}

		/**
		 * Retourne une condition concernant l'instant pivot en-dessous duquel les connections sont
		 * considérées comme étant expirées.
		 *
		 * @return string
		 */
		protected function _conditionsValid() {
			return array( 'modified >=' => strftime( '%Y-%m-%d %H:%M:%S', strtotime( '-'.readTimeout().' seconds' ) ) );
		}

		/**
		 * Retourne une sous-reqûete permettant de savoir si le Dossier est locké.
		 *
		 * @param string $modelAlias Alias du modèle Dossier
		 * @param string $fieldName Si non null, alors la sous-reqête est aliasée
		 *	pour utiliser dans l'attribut 'fields' d'un querydata.
		 * @return string
		 */
		public function sqLocked( $modelAlias = 'Dossier', $fieldName = null ) {
			if( Configure::read( 'Jetons2.disabled' ) ) {
				$sq = "( 0 = 1 )";
			}
			else {
				$sq = $this->Jeton->sq(
					array(
						'alias' => 'jetons',
						'fields' => array(
							'jetons.dossier_id',
						),
						'conditions' => array(
							'NOT' => array(
								array(
									'jetons.php_sid' => $this->Session->id(),
									'jetons.user_id' => $this->Session->read( 'Auth.User.id' )
								),
								'NOT' => $this->_conditionsValid()
							),
							'jetons.dossier_id = Dossier.id'
						),
						'recursive' => -1
					)
				);

				$sq = "( \"{$modelAlias}\".\"id\" IN ( {$sq} ) )";
			}

			if( !empty( $fieldName ) ) {
				$sq = "{$sq} AS \"Dossier__locked\"";
			}

			return $sq;
		}

		// TODO
		/*public function clean() {

		}*/

		/**
		 * Called before Controller::redirect().  Allows you to replace the url that will
		 * be redirected to with a new url. The return of this method can either be an array or a string.
		 *
		 * If the return is an array and contains a 'url' key.  You may also supply the following:
		 *
		 * - `status` The status code for the redirect
		 * - `exit` Whether or not the redirect should exit.
		 *
		 * If your response is a string or an array that does not contain a 'url' key it will
		 * be used as the new url to redirect to.
		 *
		 * @param Controller $controller Controller with components to beforeRedirect
		 * @param string|array $url Either the string or url array that is being redirected to.
		 * @param integer $status The status code of the redirect
		 * @param boolean $exit Will the script exit.
		 * @return array|null Either an array or null.
		 * @link @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::beforeRedirect
		 */
		public function beforeRedirect( &$controller, $url, $status = null, $exit = true ) {
			return array( 'url' => $url, 'status' => $status, 'exit' => $exit );
		}
	}
?>