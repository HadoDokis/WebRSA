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
			'InsertionsAllocataires',
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
		 * @return array
		 */
		public function addAllConditions( array $query ) {
			$query = $this->Gestionzonesgeos->qdConditions( $query );
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
		public function completeSearchQuery( array $query, $limit = true ) {
			$query = $this->addAllConditions( $query );

			// Champ supplémentaire pour un moteur de recherche simple
			$query['fields'][] = $this->Jetons2->sqLocked( 'Dossier', 'locked' );

			if( $limit ) {
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
		 * @param array $query
		 * @return array
		 */
		public function paginate( array $query, $className = 'Personne' ) {
			$Controller = $this->_Collection->getController();

			$Controller->paginate = array( $className => $query );

			$results = $Controller->paginate(
				$className,
				array(),
				$query['fields'],
				!Hash::get( $Controller->request->data, 'Search.Pagination.nombre_total' )
			);

			return $results;
		}

		/**
		 * Retourne les options de base pour les formulaires de recherche liés
		 * à un allocataire.
		 *
		 * @return array
		 */
		public function options() {
			$options = ClassRegistry::init( 'Allocataire' )->options();

			$options['Adresse']['numcomptt'] = $this->Gestionzonesgeos->listeCodesInsee();
			$options['Canton']['canton'] = $this->Gestionzonesgeos->listeCantons();
			$options['Sitecov58']['id'] = $this->Gestionzonesgeos->listeSitescovs58(); // FIXME: à mettre dans le modèle Allocataire ?

			$options['PersonneReferent']['structurereferente_id'] = $this->InsertionsAllocataires->structuresreferentes( array( 'optgroup' => true ) );
			$options['PersonneReferent']['referent_id'] = $this->InsertionsAllocataires->referents( array( 'prefix' => true ) );

			return $options;
		}
	}
?>