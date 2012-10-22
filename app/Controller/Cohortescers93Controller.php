<?php
	/**
	 * Code source de la classe Cohortescers93Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.controllers
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Cohortescers93Controller permet d'assigner un référent du parcours aux allocataires qui
	 * n'en possèdent pas.
	 *
	 * @package app.controllers
	 */
	class Cohortescers93Controller extends AppController
	{
		/**
		 * Nom du contrôleur
		 *
		 * @var string
		 */
		public $name = 'Cohortescers93';

		/**
		 * Components utilisés par ce contrôleur.
		 *
		 * @var array
		 */
		public $components = array(
			'Cohortes' => array(
				'avalidercpdv'
			),
			'Search.Filtresdefaut' => array(
				'saisie',
				'avalidercpdv'
			),
			'Gestionzonesgeos',
			'Search.Prg' => array(
				'actions' => array(
					'saisie' => array(
						'filter' => 'Search'
					),
					'avalidercpdv' => array(
						'filter' => 'Search'
					),
				)
			)
		);

		/**
		 * Helpers utilisés par ce contrôleur.
		 *
		 * @var array
		 */
		public $helpers = array( 'Csv', 'Default2', 'Search', 'Xform', 'Xhtml' );

		/**
		 * Modèles utilisés par ce contrôleur.
		 *
		 * @var array
		 */
		public $uses = array( 'Cohortecer93', 'Contratinsertion', 'Option' );

		/**
		 * Cohorte d'affectation des référents au sein d'une structure référente (PDV).
		 * La date de début d'affectation est la date du jour.
		 * Si l'utilisateur n'est pas attaché à une structure référente, alors on envoit une erreur.
		 *
		 * @return void
		 */
		public function saisie() {
			$structurereferente_id = $this->Session->read( 'Auth.User.structurereferente_id' );
			if( empty( $structurereferente_id ) ) {
				$this->Session->setFlash( 'L\'utilisateur doit etre rattaché à une structure référente.', 'flash/error' );
				$this->cakeError( 'error403' );
			}

			$this->_index( $structurereferente_id );
		}

		/**
		 * ....
		 * Si l'utilisateur n'est pas attaché à une structure référente, alors on envoit une erreur.
		 *
		 * @return void
		 */
		public function avalidercpdv() {
			$structurereferente_id = $this->Session->read( 'Auth.User.structurereferente_id' );
			if( empty( $structurereferente_id ) ) {
				$referent_id = $this->Session->read( 'Auth.User.referent_id' );
				if( !empty( $referent_id ) ) {
					$this->User->Referent->id = $referent_id;
					$structurereferente_id = $this->User->Referent->field( 'structurereferente_id' );
				}
			}

			if( empty( $structurereferente_id ) ) {
				$this->Session->setFlash( 'L\'utilisateur doit etre rattaché à une structure référente.', 'flash/error' );
				$this->cakeError( 'error403' );
			}

			$this->_index( $structurereferente_id );
		}

		/**
		 * Méthode de recherche générique.
		 *
		 * @param integer $structurereferente_id L'id de la structure référente à laquelle l'utilisateur est lié.
		 */
		protected function _index( $structurereferente_id ) {
			if( !empty( $this->request->data ) ) {
			
				// Traitement du formulaire de recherche
				$querydata = $this->Cohortecer93->search(
					$this->action,
					(array)$this->Session->read( 'Auth.Zonegeographique' ),
					$this->Session->read( 'Auth.User.filtre_zone_geo' ),
					$this->request->data['Search'],
					( ( $this->action == 'avalidercpdv' ) ? $this->Cohortes->sqLocked( 'Dossier' ) : null )
				);

				$this->paginate = $querydata;
				$cers93 = $this->paginate(
					$this->Contratinsertion->Personne,
					array(),
					array(),
					!Set::classicExtract( $this->request->data, 'Search.Pagination.nombre_total' )
				);
				$this->set( 'cers93', $cers93 );
			}

			$this->set( 'structurereferente_id', $structurereferente_id );

			// Options
			$options = array(
				'cantons' => $this->Gestionzonesgeos->listeCantons(),
				'etatdosrsa' => $this->Option->etatdosrsa(),
				'exists' => array( '1' => 'Oui', '0' => 'Non' ),
				'mesCodesInsee' => $this->Gestionzonesgeos->listeCodesInsee(),
				'referents' => $this->Contratinsertion->Personne->PersonneReferent->Referent->referentsListe( $structurereferente_id ),
				'toppersdrodevorsa' => $this->Option->toppersdrodevorsa( true ),
				'rolepers' => $this->Option->rolepers()
			);
			$options = Set::merge( $options, $this->Contratinsertion->Cer93->enums() );
			$this->set( compact( 'options' ) );

			if( $this->action == 'saisie' ) {
				$this->render( 'saisie' );
			}
			else {
				$this->render( 'visualisation' );
			}
		}
		
		
		/**
		 * @return void
		 */
		public function exportcsv() {
			$structurereferente_id = $this->Session->read( 'Auth.User.structurereferente_id' );
			if( empty( $structurereferente_id ) ) {
				$this->Session->setFlash( 'L\'utilisateur doit etre rattaché à une structure référente.', 'flash/error' );
				$this->cakeError( 'error403' );
			}

			$data = Xset::bump( $this->request->params['named'], '__' );
			$querydata = $this->Cohortecer93->search(
				'saisie',
				(array)$this->Session->read( 'Auth.Zonegeographique' ),
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				$data['Search'],
				null
			);
			unset( $querydata['limit'] );

			$cers93 = $this->Contratinsertion->Personne->find( 'all', $querydata );

			$this->layout = '';
			$this->set( 'cers93', $cers93 );
		}
	}
?>
