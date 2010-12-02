<?php
	/**
	* Gestion des bilans de parcours pour le conseil général du département 66.
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.controllers
	*/

	class Bilansparcours66Controller extends AppController
	{
		public $helpers = array( 'Default', 'Default2' );

		public $uses = array( 'Bilanparcours66', 'Option' );

		/// FIXME: evite les droits
		public function beforeFilter() {
		}

		/**
		*
		*/

		protected function _setOptions() {
			$options = $this->Bilanparcours66->enums();
			$options['Saisineepbilanparcours66']['typeorient_id'] = $this->Bilanparcours66->Typeorient->listOptions();
			$options['Saisineepbilanparcours66']['structurereferente_id'] = $this->Bilanparcours66->Structurereferente->list1Options( array( 'orientation' => 'O' ) );
			$options['Bilanparcours66']['duree_engag'] = $this->Option->duree_engag_cg66();
            $options['Bilanparcours66']['structurereferente_id'] = $this->{$this->modelClass}->Structurereferente->listOptions();
            $options['Bilanparcours66']['referent_id'] = $this->{$this->modelClass}->Referent->listOptions();

			$this->set( compact( 'options' ) );
		}

		/**
		*
		*/

		public function index( $personne_id = null ) {
			if( !empty( $personne_id ) ) {
				$conditions = array( 'Orientstruct.personne_id' => $personne_id );
			}
			else {
				$conditions = array();
			}

			$this->paginate = array(
				'fields' => array(
					'Bilanparcours66.referent_id',
					'Bilanparcours66.orientstruct_id',
					'Bilanparcours66.contratinsertion_id',
					'Bilanparcours66.presenceallocataire',
					'Bilanparcours66.situationallocataire',
					'Bilanparcours66.saisineepparcours',
					'Bilanparcours66.maintienorientation',
					'Bilanparcours66.changereferent',
					'Bilanparcours66.created',
					'Saisineepbilanparcours66.id'
				),
				'contain' => array(
					'Orientstruct' => array(
						'Typeorient',
						'Structurereferente',
					),
					'Contratinsertion' => array(
						'Personne' => array(
							'fields' => array( 'qual', 'nom', 'prenom' ),
							'Foyer' => array(
								'Adressefoyer' => array(
									'conditions' => array(
										'Adressefoyer.rgadr' => '01',
										'Adressefoyer.typeadr' => 'D'
									),
									'Adresse'
								)
							)
						),
						'Structurereferente' => array(
							'Typeorient',
						),
					),
					'Saisineepbilanparcours66' => array(
						'Dossierep' => array(
							'fields' => array(
								'etapedossierep'
							)
						)
					),
					'Referent'
				),
				'conditions' => $conditions,
				'limit' => 10
			);

			$this->set( 'options', $this->Bilanparcours66->Saisineepbilanparcours66->Dossierep->enums() );
			$bilansparcours66 = $this->paginate( $this->Bilanparcours66 );

			// INFO: containable ne permet pas de passer dans les virtualFields maison
			foreach( $bilansparcours66 as $key => $bilanparcours66 ) {
				$bilansparcours66[$key]['Referent']['nom_complet'] = implode(
					' ',
					array(
						$bilansparcours66[$key]['Referent']['qual'],
						$bilansparcours66[$key]['Referent']['nom'],
						$bilansparcours66[$key]['Referent']['prenom']
					)
				);
				
				$bilansparcours66[$key]['Personne']['nom_complet'] = implode(
					' ',
					array(
						$bilansparcours66[$key]['Contratinsertion']['Personne']['qual'],
						$bilansparcours66[$key]['Contratinsertion']['Personne']['nom'],
						$bilansparcours66[$key]['Contratinsertion']['Personne']['prenom']
					)
				);
			}

			$this->set( compact( 'bilansparcours66' )  );
		}

		/**
		*
		*/

		public function add() {
			$args = func_get_args();
			call_user_func_array( array( $this, '_add_edit' ), $args );
		}

		/**
		* TODO: que modifie-t'on ? Dans quel cas peut-on supprimer ?
		*/

// 		public function edit() {
// 			$args = func_get_args();
// 			call_user_func_array( array( $this, '_add_edit' ), $args );
// 		}

		/**
		* Ajout ou modification du bilan de parcours d'un allocataire.
		*
		* Le bilan de parcours entraîne:
		*	- pour le thème réorientation/saisinesepsbilansparcours66
		*		* soit un maintien de l'orientation, avec reconduction du CER, sans passage en EP
		*		* soit une saisine de l'EP locale, commission parcours
		*
		* FIXME: modification du bilan
		*
		* @param integer $id Pour un ajout, l'id technique de la personne; pour une
		*	modification, l'id technique du bilan de parcours.
		* @return void
		* @precondition L'allocataire existe et possède une orientation
		* @access protected
		*/

		protected function _add_edit( $id = null ) {
			if( $this->action == 'add' ) {
				$personne_id = $id;
			}
			// TODO
			else if( $this->action == 'edit' ) {
				/*$bilanparcours66 = $this->Bilanparcours66->find(
					'first',
					array(
						'contain' => false,
						'conditions' => array( 'Bilanparcours66.id' => $id )
					)
				);
				$this->assert( !empty( $bilanparcours66 ), 'error404' );

				$this->Bilanparcours66->Orientstruct->id = $bilanparcours66['Bilanparcours66']['orientstruct_id'];
				$personne_id = $this->Bilanparcours66->Orientstruct->field( 'personne_id' );*/
			}

			// INFO: pour passer de 74 à 29 modèles utilisés lors du find count
 			$this->Bilanparcours66->Orientstruct->Personne->unbindModelAll();

			// On s'assure que la personne existe bien
			$nPersonnes = $this->Bilanparcours66->Orientstruct->Personne->find(
				'count',
				array(
					'contain' => false,
					'conditions' => array( 'Personne.id' => $personne_id )
				)
			);
			$this->assert( ( $nPersonnes == 1 ), 'error404' );

			// Si le formulaire a été renvoyé
			if( !empty( $this->data ) ) {
				$this->Bilanparcours66->begin();

				$success = $this->Bilanparcours66->sauvegardeBilan( $this->data );

				$this->_setFlashResult( 'Save', $success );
				if( $success ) {
 					$this->Bilanparcours66->commit();
					$this->redirect( array( 'controller' => 'contratsinsertion', 'action' => 'index', $personne_id ) );
				}
				else {
					$this->Bilanparcours66->rollback();
				}
			}
			// Premier accès à la page
			else {
				if( $this->action == 'add' ) {
					$orientstruct = $this->Bilanparcours66->Orientstruct->find(
						'first',
						array(
							'fields' => array(
								'Orientstruct.id',
								'Orientstruct.personne_id',
							),
							'conditions' => array(
								'Orientstruct.personne_id' => $id,
								'Orientstruct.date_valid IS NOT NULL'
							),
							'contain' => false,
							'order' => array( 'Orientstruct.date_valid DESC' )
						)
					);

					$this->assert( !empty( $orientstruct ), 'error500' );

					$this->data = array(
						'Bilanparcours66' => array(
							'orientstruct_id' => $orientstruct['Orientstruct']['id']
						)
					);
					
					$contratinsertion = $this->Bilanparcours66->Orientstruct->Personne->Contratinsertion->find(
						'first',
						array(
							'conditions'=>array(
								'Contratinsertion.personne_id' => $id
							),
							'order'=>array(
								'Contratinsertion.rg_ci DESC'
							),
							'contain'=>array(
								'Structurereferente',
								'Referent'
							)
						)
					);
					
					$this->data['Bilanparcours66']['structurereferente_id'] = $contratinsertion['Structurereferente']['id'];
					$this->data['Bilanparcours66']['referent_id'] = $contratinsertion['Structurereferente']['id'].'_'.$contratinsertion['Referent']['id'];
				}
				else if( $this->action == 'edit' ) {
					$this->data = $bilanparcours66;
				}
			}

			// INFO: si on utilise fields pour un modèle (le modèle principal ?), on n'a pas la relation belongsTo (genre Foyer belongsTo Dossier)
			// INFO: http://stackoverflow.com/questions/3865349/containable-fails-to-join-in-belongsto-relationships-when-fields-are-used-in-ca
			// http://cakephp.lighthouseapp.com/projects/42648/tickets/1174-containable-fails-to-join-in-belongsto-relationships-when-fields-are-used
			// Recherche des informations de la personne
			$personne = $this->Bilanparcours66->Orientstruct->Personne->find(
				'first',
				array(
					'conditions' => array( 'Personne.id' => $personne_id ),
					'contain' => array(
						'Foyer' => array(
							'fields' => array(
								'id'
							),
							'Adressefoyer' => array(
								'conditions' => array(
									'Adressefoyer.rgadr' => '01'
								),
								'Adresse' => array(
									'fields' => array(
										'numvoie',
										'typevoie',
										'nomvoie',
										'codepos',
										'locaadr'
									)
								)
							),
							'Dossier' => array(
								'fields' => array(
									'numdemrsa',
									'matricule',
								)
							),
							'Modecontact' => array(
								'fields' => array(
									'autorutitel',
									'numtel',
									'autorutiadrelec',
									'adrelec'
								)
							)
						),
						'Prestation' => array(
							'fields' => array(
								'rolepers'
							)
						)
					)
				)
			);

			$this->set( 'typevoie', $this->Option->typevoie() );
			$this->set( 'rolepers', $this->Option->rolepers() );
			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'nationalite', $this->Option->nationalite() );

			$this->set( compact( 'personne' ) );
			$this->_setOptions();
			$this->render( null, null, 'add_edit' );
		}

		/**
		* TODO: que supprime-t'on ? Dans quel cas peut-on supprimer ?
		*/

		/*public function delete( $id ) {
			$success = $this->Bilanparcours66->delete( $id );
			$this->_setFlashResult( 'Delete', $success );
			$this->redirect( array( 'action' => 'index' ) );
		}*/
	}
?>
