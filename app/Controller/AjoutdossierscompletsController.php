<?php
	/**
	 * Code source de la classe AjoutdossierscompletsController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe AjoutdossierscompletsController ...
	 *
	 * @package app.Controller
	 */
	class AjoutdossierscompletsController extends AppController
	{
		public $components = array( 'Default' );
		public $uses = array( 'Dossier', 'Foyer', 'Personne', 'Adresse', 'Adressefoyer', 'Detaildroitrsa', 'Option', 'Ajoutdossiercomplet' );

		public $helpers = array( 'Default2' );

		protected function  _setOptions() {
			$options = array();
            $services = ClassRegistry::init( 'Serviceinstructeur' )->find( 'list' );
			$options = array(
				'qual' => ClassRegistry::init( 'Option' )->qual(),
				'typevoie' => ClassRegistry::init( 'Option' )->typevoie()
			);
			$this->set( compact( 'options', 'services' ) );
		}


       /**
        *
        */

        /**
        *
        */

        public function add(){

// 			$this->Ajoutdossiercomplet->begin();

			// Validation
			$this->Personne->set( $this->request->data['Personne'] );
			unset( $this->Personne->validate['dtnai'] );
			$valid = $this->Personne->validates();

			$this->Adresse->set( $this->request->data['Adresse'] );
			$this->Adressefoyer->set( $this->request->data['Adressefoyer'] );
			unset( $this->Adresse->validate['compladr'] );
			unset( $this->Adresse->validate['complideadr'] );
			$valid = $this->Adresse->validates() && $valid;
			$valid = $this->Adressefoyer->validates() && $valid;

			$this->Dossier->set( $this->request->data['Dossier'] );
			$valid = $this->Dossier->validates() && $valid;

            // Si validation -> sauvegarde
            if( !empty( $this->request->data ) && $valid ) {
				$data = $this->request->data;
				// Début de la transaction
				$this->Dossier->begin();

				if( !empty( $data['Dossier']['numdemrsatemp'] ) ) {
					$data['Dossier']['numdemrsa'] = $this->Dossier->generationNumdemrsaTemporaire();
				}

				// Tentatives de sauvegarde
				$saved = $this->Dossier->save( $data['Dossier'] );

				if( $saved ){
					// Détails du droit
					$data['Detaildroitrsa']['dossier_id'] = $this->Dossier->id;
					$saved = $this->Detaildroitrsa->save( $data['Detaildroitrsa'] ) && $saved;

					// Situation dossier RSA
					$situationdossierrsa = array( 'Situationdossierrsa' => array( 'dossier_id' => $this->Dossier->id, 'etatdosrsa' => 'Z' ) );
					$this->Dossier->Situationdossierrsa->validate = array();
					$saved = $this->Dossier->Situationdossierrsa->save( $situationdossierrsa ) && $saved;

					// Foyer
					$saved = $this->Foyer->save( array( 'dossier_id' => $this->Dossier->id ) ) && $saved;

// debug($data);
// debug( !empty( $data['Adresse'] ) );
// debug( isset( $data['Adresse'] ) );
// die();


					if( $data['Adresse']['presence'] == 1 ) {
						// Adresse
						$saved = $this->Adresse->save( $data['Adresse'] ) && $saved;

						// Adresse foyer
						$data['Adressefoyer']['foyer_id'] = $this->Foyer->id;
						$data['Adressefoyer']['adresse_id'] = $this->Adresse->id;
						$saved = $this->Adressefoyer->save( $data['Adressefoyer'] ) && $saved;
					}

					// Demandeur
					$this->Personne->create();
					$data['Personne']['foyer_id'] = $this->Foyer->id;
					$this->Personne->set( $data );
					$saved = $this->Personne->save( $data ) && $saved;
					$demandeur_id = $this->Personne->id;

					// Prestation
					$this->Personne->Prestation->create();
					$data['Prestation']['personne_id'] = $demandeur_id;
					$this->Personne->Prestation->set( $data );
					$saved = $this->Personne->Prestation->save( $data ) && $saved;
				}

				// Utilisateur
				$user = $this->User->find(
					'first',
					array(
						'conditions' => array(
							'User.id' => $this->Session->read( 'Auth.User.id' )
						),
						'recursive' => -1
					)
				);
				$this->assert( !empty( $user ), 'error500' );

                if( !empty( $data['Serviceinstructeur']['id'] ) ) {
                    // Service instructeur
                    $service = ClassRegistry::init( 'Serviceinstructeur' )->find(
                        'first',
                        array(
                            'conditions' => array(
                                'Serviceinstructeur.id' => $data['Serviceinstructeur']['id']
                            ),
                            'recursive' => -1
                        )
                    );
                    $this->assert( !empty( $service ), 'error500' );


                    $suiviinstruction = array(
                        'Suiviinstruction' => array(
                            'dossier_id'           => $this->Dossier->id,
                            'suiirsa'                  => '01',
                            'date_etat_instruction'    => strftime( '%Y-%m-%d' ),
                            'nomins'                   => $user['User']['nom'],
                            'prenomins'                => $user['User']['prenom'],
                            'numdepins'                => $service['Serviceinstructeur']['numdepins'],
                            'typeserins'               => $service['Serviceinstructeur']['typeserins'],
                            'numcomins'                => $service['Serviceinstructeur']['numcomins'],
                            'numagrins'                => $service['Serviceinstructeur']['numagrins']
                        )
                    );
                    $this->Dossier->Suiviinstruction->set( $suiviinstruction );

                    $validate = $this->Dossier->Suiviinstruction->validates();
    //debug($validate);
    //die();
                    if( $validate ) {
                        $saved = $this->Dossier->Suiviinstruction->save( $suiviinstruction ) && $saved;
                    }
                }

				// Fin de la transaction
				if( $saved ) {
					$this->Dossier->commit();
					$this->redirect( array('controller'=>'dossiers', 'action'=>'view', $this->Dossier->id ) );
				}
				// Annulation de la transaction
				else {
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
				}
			}
			$this->_setOptions();
        }
	}
?>