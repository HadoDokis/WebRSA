<?php	
	/**
	 * Code source de la classe Cohortesfichescandidature66Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Cohortesfichescandidature66Controller ...
	 *
	 * @package app.Controller
	 */
	class Cohortesfichescandidature66Controller extends AppController
	{
		public $name = 'Cohortesfichescandidature66';

		public $uses = array(
			'Cohortefichecandidature66',
			'ActioncandidatPersonne',
			'Actioncandidat',
			'Zonegeographique',
			'Dossier',
			'Canton'
		);

		public $helpers = array( 'Default2' );

		public $components = array(
            'Search.Prg' => array(
                'actions' => array(
                    'fichesenattente' => array( 'filter' => 'Search' ),
                    'fichesencours' => array( 'filter' => 'Search' )
                )
            ),
            'Gestionzonesgeos',
            'Cohortes' => array(
                'fichesenattente',
                'fichesencours'
            )
        );

		/**
		*
		*/
		public function _setOptions() {
			$motifssortie = $this->ActioncandidatPersonne->Motifsortie->find( 'list' );
			$options = Set::merge(
				$this->ActioncandidatPersonne->allEnumLists(),
				$this->ActioncandidatPersonne->Actioncandidat->allEnumLists()
			);
			$options['actions'] = $this->ActioncandidatPersonne->Actioncandidat->find( 'list', array( 'fields' => array( 'name' ),'order' => array( 'Actioncandidat.name ASC' )   ) );
			$options['partenaires'] = $this->ActioncandidatPersonne->Actioncandidat->Contactpartenaire->Partenaire->find( 'list', array( 'fields' => array( 'libstruc' ), 'order' => array( 'Partenaire.libstruc ASC' ) ) );
			$options['referents'] = $this->ActioncandidatPersonne->Referent->find( 'list', array( 'recursive' => -1 ) );

			$listeactions = $this->ActioncandidatPersonne->Actioncandidat->listActionParPartenaire();
			$this->set( compact( 'options', 'motifssortie', 'listeactions' ) );
		}


		/**
		*
		*/

		public function fichesenattente() {
			$this->_index( 'Suivifiche::fichesenattente' );
		}

		/**
		*
		*/

		public function fichesencours() {
			$this->_index( 'Suivifiche::fichesencours' );
		}

		/**
		*
		*/

		protected function _index( $statutFiche = null ) {
			$this->assert( !empty( $statutFiche ), 'invalidParameter' );

			$this->set( 'cantons', $this->Gestionzonesgeos->listeCantons() );
			$this->set( 'mesCodesInsee', $this->Gestionzonesgeos->listeCodesInsee() );


			if( !empty( $this->request->data ) ) {

				/**
				* Sauvegarde
				*/
				// On a renvoyé  le formulaire de la cohorte
				if( !empty( $this->request->data['ActioncandidatPersonne'] ) ) {
                    $this->Cohortes->get( array_unique( Set::extract( $this->request->data, 'ActioncandidatPersonne.{n}.dossier_id' ) ) );

					$valid = $this->ActioncandidatPersonne->saveAll( $this->request->data['ActioncandidatPersonne'], array( 'validate' => 'only', 'atomic' => false ) );

					if( $valid ) {
						$this->ActioncandidatPersonne->begin();
						$saved = $this->ActioncandidatPersonne->saveAll( $this->request->data['ActioncandidatPersonne'], array( 'validate' => 'first', 'atomic' => false ) );

						if( $saved ) {
							$this->ActioncandidatPersonne->commit();
                            $this->Session->setFlash( 'Enregistrement effectué.', 'flash/success' );
                            $this->Cohortes->release( array_unique( Set::extract( $this->request->data, 'ActioncandidatPersonne.{n}.dossier_id' ) ) );
							unset( $this->request->data['ActioncandidatPersonne'] );
						}
						else {
							$this->ActioncandidatPersonne->rollback();
                            $this->Session->setFlash( 'Erreur lors de l\'enregistrement.', 'flash/error' );
						}
					}
                    else {
                        $this->Session->setFlash( 'Erreur lors de l\'enregistrement.', 'flash/error' );
                    }
				}

				/**
				* Filtrage
				*/

				if( ( $statutFiche == 'Suivifiche::fichesenattente' ) || ( ( $statutFiche == 'Suivifiche::fichesencours' ) && !empty( $this->request->data ) ) ) {

                    if( !empty( $this->request->data['Search']['Actioncandidat']['id'] )) {
						$actioncandidatId = suffix( $this->request->data['Search']['Actioncandidat']['id'] );
						$this->request->data['Search']['Actioncandidat']['id'] = $actioncandidatId;
					}

					$paginate = $this->Cohortefichecandidature66->search(
                        $statutFiche,
                        (array)$this->Session->read( 'Auth.Zonegeographique' ),
                        $this->Session->read( 'Auth.User.filtre_zone_geo' ),
                        $this->request->data['Search'],
                        $this->Cohortes->sqLocked( 'Dossier' )
                    );

					$paginate['limit'] = 10;

					$this->paginate = $paginate;
					$cohortefichecandidature66 = $this->paginate( 'ActioncandidatPersonne' );

					foreach( $cohortefichecandidature66 as $key => $value ) {
						if( empty( $value['ActioncandidatPersonne']['sortiele'] ) ) {
							$cohortefichecandidature66[$key]['ActioncandidatPersonne']['proposition_sortiele'] = date( 'Y-m-d' );
						}
						else{
							$cohortefichecandidature66[$key]['ActioncandidatPersonne']['proposition_sortiele'] = $value['ActioncandidatPersonne']['sortiele'];
						}
					}

                    $this->Cohortes->get( array_unique( Set::extract( $cohortefichecandidature66, '{n}.Dossier.id' ) ) );
					$this->set( 'cohortefichecandidature66', $cohortefichecandidature66 );
				}
			}

			$this->_setOptions();

			switch( $statutFiche ) {
				case 'Suivifiche::fichesenattente':
					$this->set( 'pageTitle', 'Fiches de candidature en attente' );
					$this->render( 'formulaireenattente' );
					break;
				case 'Suivifiche::fichesencours':
					$this->set( 'pageTitle', 'Fiches de candidature en cours' );
					$this->render( 'formulaireencours' );
					break;
			}
		}
	}
?>