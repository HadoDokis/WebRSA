<?php
	/**
	 * Code source de la classe Cohortesreferents93Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Cohortesreferents93Controller permet d'assigner un référent du parcours aux allocataires qui
	 * n'en possèdent pas.
	 *
	 * @package app.Controller
	 */
	class Cohortesreferents93Controller extends AppController
	{
		/**
		 * Nom du contrôleur
		 *
		 * @var string
		 */
		public $name = 'Cohortesreferents93';

		/**
		 * Components utilisés par ce contrôleur.
		 *
		 * @var array
		 */
		public $components = array(
			'Cohortes'=> array(
				'affecter'
			),
			'Search.Filtresdefaut' => array(
				'affectes',
				'affecter'
			),
			'Gestionzonesgeos',
			'Search.Prg' => array(
				'actions' => array(
					'affectes',
					'affecter' => array(
						'filter' => 'Search'
					),
				)
			),
			'Workflowscers93'
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
		public $uses = array( 'Cohortereferent93', 'PersonneReferent', 'Option' );

		/**
		 * Cohorte d'affectation des référents au sein d'une structure référente (PDV).
		 * La date de début d'affectation est la date du jour.
		 * Si l'utilisateur n'est pas attaché à une structure référente, alors on envoit une erreur.
		 *
		 * @return void
		 */
		public function affecter() {
			$this->Workflowscers93->assertUserCpdv();
			$structurereferente_id = $this->Workflowscers93->getUserStructurereferenteId();

			if( !empty( $this->request->data ) ) {
				// Traitement du formulaire
				if( isset( $this->request->data['PersonneReferent'] ) ) {
					$dossiers_ids = array_unique( Set::extract( '/PersonneReferent/dossier_id', $this->request->data ) );
					$this->Cohortes->get( $dossiers_ids );

					// On change les règles de validation du modèle PersonneReferent avec celles qui sont spécfiques à la cohorte
					$personneReferentValidate = $this->PersonneReferent->validate;
					$this->PersonneReferent->validate = $this->Cohortereferent93->validatePersonneReferent;

					$data = Set::extract( '/PersonneReferent[action]', $this->request->data );

					if( $this->PersonneReferent->saveAll( $data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
						$this->PersonneReferent->begin();

						$data = Set::extract( '/PersonneReferent[action=Valider]', $this->request->data );

						if( !empty( $data ) && $this->PersonneReferent->saveAll( $data, array( 'validate' => 'first', 'atomic' => false ) ) ) {
							$this->PersonneReferent->commit();
							$this->Cohortes->release( $dossiers_ids );
							$this->Session->setFlash( 'Enregistrement effectué.', 'flash/success' );
							unset( $this->request->data['PersonneReferent'] );
						}
						else {
							$this->PersonneReferent->rollback();

							if( empty( $data ) ) {
								$this->Session->setFlash( 'Aucun élément à enregistrer', 'flash/notice' );
							}
							else {
								$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
							}
						}
					}
					else {
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}

					$this->PersonneReferent->validate = $personneReferentValidate;
				}

				// INFO: sinon on ne peut pas trier comme on veut
				$this->PersonneReferent->Personne->virtualFields['order'] = $this->Cohortereferent93->vfPersonneOrder;

				// Traitement du formulaire de recherche
				$querydata = $this->Cohortereferent93->search(
					$structurereferente_id,
					(array)$this->Session->read( 'Auth.Zonegeographique' ),
					$this->Session->read( 'Auth.User.filtre_zone_geo' ),
					$this->request->data['Search'],
					( ( $this->action == 'affecter' ) ? $this->Cohortes->sqLocked( 'Dossier' ) : null )
				);

				$this->paginate = $querydata;
				$personnes_referents = $this->paginate(
					$this->PersonneReferent->Personne,
					array(),
					array(),
					!Set::classicExtract( $this->request->data, 'Search.Pagination.nombre_total' )
				);
				$this->set( 'personnes_referents', $personnes_referents );

				$this->Cohortes->get( array_unique( Set::extract( '/Dossier/id', $personnes_referents ) ) );

				// Par défaut, tout est mis en attente
				if( !isset( $this->request->data['PersonneReferent'] ) ) {
					$this->request->data['PersonneReferent'] = array();
					foreach( array_keys( $personnes_referents ) as $index ) {
						$this->request->data['PersonneReferent'][$index] = array( 'action' => 'En attente' );
					}
				}
			}

			$this->set( 'structurereferente_id', $structurereferente_id );

			// Options
			$options = array(
				'actions' => array( 'Valider' => 'Valider', 'En attente' => 'En attente' ),
				'cantons' => $this->Gestionzonesgeos->listeCantons(),
				'etatdosrsa' => $this->Option->etatdosrsa(),
				'typevoie' => $this->Option->typevoie(),
				'rolepers' => $this->Option->rolepers(),
				'exists' => array( '1' => 'Oui', '0' => 'Non' ),
				'mesCodesInsee' => $this->Gestionzonesgeos->listeCodesInsee(),
				'referents' => $this->PersonneReferent->Referent->referentsListe( $structurereferente_id ),
				'toppersdrodevorsa' => $this->Option->toppersdrodevorsa( true ),
				'Referent' => array(
					'designe' => array( '0' => 'Référent non désigné', '1' => 'Référent désigné' )
				)
			);
			$options = Set::merge( $options, $this->PersonneReferent->Personne->Contratinsertion->Cer93->enums() );
			$this->set( compact( 'options' ) );
		}

		/**
		 * Export CSV des résultats de la cohorte d'affectation des référents au sein d'une structure référente (PDV).
		 * Si l'utilisateur n'est pas attaché à une structure référente, alors on envoit une erreur.
		 *
		 * @return void
		 */
		public function exportcsv() {
			$this->Workflowscers93->assertUserCpdv();
			$structurereferente_id = $this->Workflowscers93->getUserStructurereferenteId();

			$data = Xset::bump( $this->request->params['named'], '__' );

			// INFO: sinon on ne peut pas trier comme on veut
			$this->PersonneReferent->Personne->virtualFields['order'] = $this->Cohortereferent93->vfPersonneOrder;

			$querydata = $this->Cohortereferent93->search(
				$structurereferente_id,
				(array)$this->Session->read( 'Auth.Zonegeographique' ),
				$this->Session->read( 'Auth.User.filtre_zone_geo' ),
				$data['Search'],
				null
			);

			unset( $querydata['limit'] );

			$personnes_referents = $this->PersonneReferent->Personne->find( 'all', $querydata );

			$this->layout = '';
			$this->set( 'personnes_referents', $personnes_referents );

			// Options
			$options = array(
				'etatdosrsa' => $this->Option->etatdosrsa(),
				'typevoie' => $this->Option->typevoie(),
				'rolepers' => $this->Option->rolepers(),
				'referents' => $this->PersonneReferent->Referent->referentsListe( $structurereferente_id ),
			);
			$options = Set::merge( $options, $this->PersonneReferent->Personne->Contratinsertion->Cer93->enums() );
			$this->set( compact( 'options' ) );
		}
	}
?>
