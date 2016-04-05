<?php
	/**
	 * Code source de la classe AllocatairesComponent.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaPermissions', 'Utility' );

	/**
	 * La classe AllocatairesComponent ...
	 *
	 * @package app.Controller.Component
	 */
	class AllocatairesComponent extends Component
	{
		/**
		 * Components utilisés par ce component.
		 *
		 * @var array
		 */
		public $components = array(
			'Gestionzonesgeos',
			'InsertionsBeneficiaires',
			'Jetons2',
			'Session',
		);

		/**
		 * On initialise le component Jeton2.
		 *
		 * @param Controller $controller Controller with components to initialize
		 */
		public function initialize( Controller $controller ) {
			parent::initialize( $controller );
			$this->Jetons2->initialize( $controller );
		}

		/**
		 * Permet de rajouter des conditions aux conditions de recherches suivant
		 * le paramétrage des service référent dont dépend l'utilisateur connecté.
		 *
		 * Nécessite la mise à true du paramètre 'Recherche.qdFilters.Serviceinstructeur'
		 * ainsi que l'ajout de conditions au service instructeur de l'utilisateur
		 * connecté.
		 *
		 * Utilisé pour l'injection de conditions pour la confidentialité au CG 58.
		 *
		 * Permet de déprécier AppController::_qdAddFilters()
		 *
		 * @param array $query Les querydata dans lesquelles rajouter les conditionss
		 * @return array
		 */
		public function addQdFilters( array $query ) {
			if( Configure::read( 'Recherche.qdFilters.Serviceinstructeur' ) ) {
				$sqrecherche = $this->Session->read( 'Auth.Serviceinstructeur.sqrecherche' );
				if( !empty( $sqrecherche ) ) {
					$query['conditions'][] = $sqrecherche;
				}
			}

			return $query;
		}

		/**
		 * Ajout de conditions supplémentaires liées à l'utilisateur connecté.
		 *
		 * @param array $query
		 * @param array $params
		 * @return array
		 */
		public function addAllConditions( array $query, array $params = array() ) {
			$params += array( 'structurereferente_id' => false );
			$query = $this->Gestionzonesgeos->completeQuery( $query, $params['structurereferente_id'] );
			$query['conditions'][] = WebrsaPermissions::conditionsDossier();
			$query = $this->addQdFilters( $query );

			return $query;
		}

		/**
		 * Complète une requête de recherche avec les condtions, le champ
		 * Dossier.locked et une limite éventuelle.
		 *
		 * @param array $query
		 * @param integer $limit
		 * @return array
		 */
		public function completeSearchQuery( array $query, array $params = array() ) {
			$params += array( 'limit' => true, 'structurereferente_id' => false );
			$query = $this->addAllConditions( $query, $params );

			// Champ supplémentaire pour un moteur de recherche simple
			$query['fields']['Dossier.locked'] = $this->Jetons2->sqLocked( 'Dossier', 'locked' );

			if( Hash::get( $params, 'limit' ) ) {
				$query = Hash::merge(
					array( 'limit' => 10 ),
					$query
				);
			}

			return $query;
		}

		/**
		 * Effectue la pagination sur le modèle Personne, progressive ou non,
		 * ajoute les restrictions liées à l'utilisateur connecté.
		 *
		 * @param array $query Le querydata à paginer
		 * @param string $className Le nom de la classe sur laquelle paginer
		 * @return array
		 */
		public function paginate( array $query, $className = 'Personne' ) {
			$Controller = $this->_Collection->getController();

			// Permet de surcharger les clefs paginate dans le controller directement
			if (isset($Controller->paginate)) {
				if( isset( $Controller->paginate[$className] ) ) {
					$Controller->paginate[$className] += $query;
				}
				else {
					$Controller->paginate += $query;
				}
			}
			else{
				$Controller->paginate = array( $className => $query );
			}


			$results = $Controller->paginate(
				$className,
				array(),
				$query['fields'],
				!Hash::get( $Controller->request->data, 'Search.Pagination.nombre_total' )
			);

			return $results;
		}


		/**
		 * Retourne les options de type "enum", c'est à dire liées aux schémas des
		 * tables de la base de données.
		 *
		 * @return array
		 */
		public function optionsEnums() {
			return ClassRegistry::init( 'Allocataire' )->options();
		}

		/**
		 * Retourne les options stockées en session, liées à l'utilisateur connecté.
		 *
		 * @return array
		 */
		public function optionsSession() {
			return array(
				'Adresse' => array(
					'numcom' => $this->Gestionzonesgeos->listeCodesInsee()
				),
				'Canton' => array(
					'canton' => $this->Gestionzonesgeos->listeCantons()
				),
				'Sitecov58' => array(
					'id' => $this->Gestionzonesgeos->listeSitescovs58()
				),
				'PersonneReferent' => array(
					'structurereferente_id' => $this->InsertionsBeneficiaires->structuresreferentes( array( 'type' => 'optgroup', 'prefix' => false ) ),
					'referent_id' => $this->InsertionsBeneficiaires->referents( array( 'prefix' => true ) )
				)
			);
		}

		/**
		 * Retourne les options stockées liées à des enregistrements en base de
		 * données, ne dépendant pas de l'utilisateur connecté.
		 *
		 * @return array
		 */
		public function optionsRecords() {
			return array(
				'Serviceinstructeur' => array(
					'id' => ClassRegistry::init( 'Serviceinstructeur' )->listOptions()
				)
			);
		}

		/**
		 * Retourne les options de base pour les formulaires de recherche liés
		 * à un allocataire.
		 *
		 * @return array
		 */
		public function options() {
			return Hash::merge(
				$this->optionsEnums(),
				$this->optionsRecords(),
				$this->optionsSession()
			);
		}
	}
?>