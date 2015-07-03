<?php
	/**
	 * Code source de la classe CriteresentretiensController.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe CriteresentretiensController ...
	 *
	 * @package app.Controller
	 */
	class CriteresentretiensController extends AppController
	{
		public $name = 'Criteresentretiens';

		public $uses = array( 'Critereentretien', 'Entretien' );

		public $helpers = array(
			'Csv',
			'Default3' => array(
				'className' => 'ConfigurableQuery.ConfigurableQueryDefault'
			),
			'Search'
		);

		public $components = array(
			'Allocataires',
			'Search.SearchPrg' => array( 'actions' => array( 'index' ) )
		);

		// FIXME: public/droits, etc///
		public function options() {
			$options = Hash::merge(
				$this->Allocataires->options(),
				$this->Entretien->enums()
			);

			return $options;
		}

		/**
		 *
		 */
		public function index() {
			// TODO: à présent, on peut factoriser
			if( !empty( $this->request->data ) ) {
				// FIXME: ajouter le prefix Search dans la vue
				//$query = $this->Critereentretien->search( (array)Hash::get( $this->request->data, 'Search' ) );
				$query = $this->Critereentretien->search( $this->request->data );
				$query = $this->Allocataires->completeSearchQuery( $query, array( 'structurereferente_id' => 'Entretien.structurereferente_id' ) );

				$key = "{$this->name}.{$this->request->params['action']}";
				$query = ConfigurableQueryFields::getFieldsByKeys( array( "{$key}.fields", "{$key}.innerTable" ), $query );

				$this->Entretien->forceVirtualFields = true;
				$results = $this->Allocataires->paginate( $query, 'Entretien' );

				$this->set( compact( 'results' ) );
			}
			else {
				$filtresdefaut = Configure::read( "Filtresdefaut.{$this->name}_{$this->action}" );
				$this->request->data = Hash::merge( $this->request->data, array( 'Search' => $filtresdefaut ) );
			}

			$options = $this->options();
			$this->set( compact( 'options' ) );
		}

		/**
		 * Export du tableau en CSV
		 */
		public function exportcsv() {
			$query = $this->Critereentretien->search( Hash::expand( $this->request->params['named'], '__' ) );

			unset( $query['limit'] );
			$query = $this->Allocataires->completeSearchQuery( $query, array( 'structurereferente_id' => 'Entretien.structurereferente_id' ) );


			$this->Entretien->forceVirtualFields = true;
			$results = $this->Entretien->find( 'all', $query );

			$options = $this->options();

			$this->layout = '';
			$this->set( compact( 'results', 'options' ) );
		}
	}
?>