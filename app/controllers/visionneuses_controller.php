<?php

// Fait par le CG93
// Auteur : Harry ZARKA <hzarka@cg93.fr>, 2010.

class VisionneusesController extends AppController {

	var $name = 'Visionneuses';
	var $uses = array( 'Visionneuse','RejetHistorique');
	
		
	public $paginate = array('limit'=>10,'order'=>'Visionneuse.dtdeb DESC');

	
	function index() {
		$this->Visionneuse->recursive = 0;
		$this->set('visionneuses', $this->paginate());
	
		$this->Default->search(
		array('Visionneuse.dtint' => 'BETWEEN','Visionneuse.flux' => 'BETWEEN')
		);
	}
		
}
	
?>