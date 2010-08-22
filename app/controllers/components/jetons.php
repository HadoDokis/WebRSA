<?php
    class JetonsComponent extends Component
    {
        var $components = array( 'Session' );
        var $_userId;

        /**
        *	The initialize method is called before the controller's beforeFilter method.
        */

		public function initialize( &$controller, $settings = array() ) {
			$this->controller = &$controller;
			// FIXME
			if( $this->_userId = $this->Session->read( 'Auth.User.id' ) ) {
				$this->controller->assert( valid_int( $this->_userId ), 'invalidParamForToken' ); // FIXME
			}

			$this->User = ClassRegistry::init( 'User' );
			$this->Dossier = ClassRegistry::init( 'Dossier' );
			$this->Jeton = ClassRegistry::init( 'Jeton' );
		}

		/**
		* Retourn l'id d'un dossier à partir des paramètres ($params = dossier_id, 'Personne.id', 'Dossier.id')
		*/

		protected function _dossierId( $params = array() ) {
			if( !is_array( $params ) ) {
				return $params;
			}
			else {
				if( array_key_exists( 'Personne.id', $params ) ) {
					$this->Personne = ClassRegistry::init( 'Personne' );
					$personne = $this->Personne->find(
						'first',
						array(
							'conditions' => array(
								'Personne.id' => $params['Personne.id']
							),
							'recursive' => 2
						)
					);

					$this->controller->assert( !empty( $personne ), 'invalidParamForToken' );
					return $personne['Foyer']['dossier_rsa_id'];
				}
				else if( array_key_exists( 'Dossier.id', $params ) ) {
					return $params['Dossier.id'];
				}
			}
		}

		/**
		* Retourne vrai si un dossier possédant l'id passé en paramètre existe
		*/

		protected function _dossierExists( $dossier_id ) {
			$count = $this->Dossier->find(
				'count',
				array(
					'conditions' => array( 'Dossier.id' => $dossier_id ),
					'recursive' => -1
				)
			);

			return ( !empty( $count ) );
		}

		/**
		* Retourne l'instant pivot en-dessous duquel les connections sont
		* considérées comme étant expirées.
		*/

		protected function _timeoutThreshold() {
			return strftime( '%Y-%m-%d %H:%M:%S', strtotime( '-'.readTimeout().' seconds' ) );
		}

		/**
		*
		*/

		protected function _clean() {
			$count = $this->Jeton->find(
				'count',
				array(
					'conditions' => array( '"Jeton"."modified" <' => $this->_timeoutThreshold() )
				)
			);

			if( $count > 0 ) {
				$this->_lock();
				return $this->Jeton->deleteAll(
					array(
						'"Jeton"."modified" <' => $this->_timeoutThreshold()
					)
				);
			}

			return false;
		}

		/**
		* FIXME: en faire une sous-requête
		*/

		public function ids() {
			$jetons = $this->Jeton->find(
				'list',
				array(
					'fields' => array(
						'Jeton.dossier_id',
						'Jeton.dossier_id'
					),
					'conditions' => array(
						'NOT' => array(
							'"Jeton"."php_sid"'     => session_id(), // FIXME: ou pas -> config
							'"Jeton"."user_id"'     => $this->_userId
						)
					)
				)
			);

			return $jetons;
		}

		/**
		*
		*/

		public function check( $params ) {
			$dossier_id = $this->_dossierId( $params );

			$this->controller->assert( $this->_dossierExists( $dossier_id ), 'invalidParamForToken' );
			$this->_clean();

			$jeton = $this->Jeton->find(
				'first',
				array(
					'conditions' => array(
						'"Jeton"."dossier_id"'  => $dossier_id,
						'and NOT' => array(
							'"Jeton"."php_sid"'     => session_id(), // FIXME: ou pas -> config
							'"Jeton"."user_id"'     => $this->_userId
						)
					)
				)
			);

			if( !empty( $jeton ) ) {
				$lockingUser = $this->User->find(
					'first',
					array(
						'conditions' => array(
							'User.id' => $jeton['Jeton']['user_id']
						),
						'recursive' => -1
					)
				);
				$this->controller->assert( !empty( $lockingUser ), 'invalidParamForToken' );
				$this->controller->cakeError(
					'lockedDossier',
					array(
						'time' => ( strtotime( $jeton['Jeton']['modified'] ) + readTimeout() ),
						'user' => $lockingUser['User']['username']
					)
				); // FIXME: paramètres ?
			}

			return empty( $jeton );
		}

		/**
		* Retourne vrai si le dossier est locké
		*/

		public function locked( $params ) {
			$dossier_id = $this->_dossierId( $params );
			$this->controller->assert( $this->_dossierExists( $dossier_id ), 'invalidParamForToken' );

			$count = $this->Jeton->find(
				'count',
				array(
					'conditions' => array(
						'"Jeton"."dossier_id"'  => $dossier_id,
						'and NOT' => array(
							'"Jeton"."php_sid"'     => session_id(), // FIXME: ou pas -> config
							'"Jeton"."user_id"'     => $this->_userId
						),
						'"Jeton"."modified" >=' => $this->_timeoutThreshold()
					)
				)
			);

			return !empty( $count );
		}

		/**
		* Retourne vrai si les dossiers sont lockés
		*/

		public function lockedList( $dossiers_ids ) {
			$list = $this->Jeton->find(
				'list',
				array(
					'fields' => array( 'Jeton.id', 'Jeton.dossier_id' ),
					'conditions' => array(
						'"Jeton"."dossier_id"'  => $dossiers_ids,
						'and NOT' => array(
							'"Jeton"."php_sid"'     => session_id(), // FIXME: ou pas -> config
							'"Jeton"."user_id"'     => $this->_userId
						),
						'"Jeton"."modified" >=' => $this->_timeoutThreshold()
					)
				)
			);

			return $list;
		}


		/**
		* Obtient un jeton sur un dossier
		*/

		public function get( $params ) {
			$this->_lock();
			$dossier_id = $this->_dossierId( $params );

			$this->controller->assert( $this->_dossierExists( $dossier_id ), 'invalidParamForToken' );

			if( $this->check( $params ) ) {
				$jeton = array(
					'Jeton' => array(
						'dossier_id'    => $dossier_id,
						'php_sid'       => session_id(), // FIXME: ou pas -> config
						'user_id'       => $this->_userId
					)
				);

				$vieuxJeton = $this->Jeton->find(
					'first',
					array(
						'conditions' => array(
							'"Jeton"."dossier_id"'  => $dossier_id,
							'"Jeton"."php_sid"'     => session_id(), // FIXME: ou pas -> config
							'"Jeton"."user_id"'     => $this->_userId
						)
					)
				);

				if( !empty( $vieuxJeton ) ) {
					$jeton['Jeton']['id'] = $vieuxJeton['Jeton']['id'];
					$jeton['Jeton']['created'] = $vieuxJeton['Jeton']['created'];
				}

				$this->Jeton->create( $jeton );
				return ( $this->Jeton->save() !== false );
			}
			else {
				return false;
			}
		}

		/**
		* Supprime le jeton sur un dossier
		*/

		public function release( $params ) {
			$dossier_id = $this->_dossierId( $params );
			$this->controller->assert( $this->_dossierExists( $dossier_id ), 'invalidParamForToken' );

			$this->_lock();

			return $this->Jeton->deleteAll(
				array(
					'Jeton.dossier_id'    => $dossier_id,
					'Jeton.php_sid'       => session_id(), // FIXME: ou pas -> config
					'Jeton.user_id'       => $this->_userId
				)
			);
		}

		/**
		* Crée un verrou sur la table des jetons
		*
		* MODE						-> 										-> modif personne	-> cohorte
		**************************************************************************************************
		* ACCESS SHARE				-> transactions entremélées				-> 4 form			-> X
		* ROW SHARE					-> transactions entremélées				->
		* ROW EXCLUSIVE			    -> transactions entremélées				-> 4 form			-> X
		* SHARE UPDATE EXCLUSIVE	-> 1 transaction puis l'autre			->
		* SHARE						-> transactions entremélées, deadlock	->
		* SHARE ROW EXCLUSIVE		-> 1 transaction puis l'autre			-> 1 form, 3 401	-> 1 form, 3 401
		* EXCLUSIVE					-> 1 transaction puis l'autre			->
		* ACCESS EXCLUSIVE			-> 1 transaction puis l'autre			->
		*/

		function _lock() {
			$sql = 'LOCK TABLE "jetons" IN SHARE ROW EXCLUSIVE MODE;';
			$this->Jeton->query( $sql );
		}

		/** *******************************************************************
			The beforeRedirect method is invoked when the controller's redirect method
			is called but before any further action. If this method returns false the
			controller will not continue on to redirect the request.
			The $url, $status and $exit variables have same meaning as for the controller's method.
		******************************************************************** */
		function beforeRedirect( &$controller, $url, $status = null, $exit = true ) {
			parent::beforeRedirect( $controller, $url, $status , $exit );
			return $url;
		}
	}
?>