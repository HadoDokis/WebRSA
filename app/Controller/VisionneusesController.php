<?php
	// Fait par le CG93
	// Auteur : Harry ZARKA <hzarka@cg93.fr>, 2010.

	class VisionneusesController extends AppController {

		public $name = 'Visionneuses';
		public $uses = array( 'Visionneuse','RejetHistorique');

		/**
		 * Components utilisés par ce contrôleur.
		 *
		 * @var array
		 */
		public $components = array( 'Default' );

		public $paginate = array('limit'=>10,'order'=>'Visionneuse.dtdeb DESC');

		public function index() {
			$this->Visionneuse->recursive = 0;
			$this->set('visionneuses', $this->paginate());

			$this->Default->search(
			array('Visionneuse.dtint' => 'BETWEEN','Visionneuse.flux' => 'BETWEEN')
			);
		}
	}
?>