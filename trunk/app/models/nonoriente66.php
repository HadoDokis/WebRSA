<?php
	class Nonoriente66 extends AppModel
	{
		public $name = 'Nonoriente66';

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'reponseallocataire' => array( 'type' => 'no' ),
					'haspiecejointe'
				)
			)
		);
		
		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Orientstruct' => array(
				'className' => 'Orientstruct',
				'foreignKey' => 'orientstruct_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

		
		public $hasMany = array(
			'Fichiermodule' => array(
				'className' => 'Fichiermodule',
				'foreignKey' => false,
				'dependent' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'Nonoriente66\'',
					'Fichiermodule.fk_value = {$__cakeID__$}'
				),
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			)
		);
		
		
		/**
		 * Retourne un champ virtuel permettant de connaître le nombre de fichiers modules liés à la non orientation
		 *
		 * @param type $nonorientation66Id
		 * @return type
		 */
		public function vfNbFichiersmodule( $fileModelName = 'Nonoriente66', $nonoriente66Id = 'Nonoriente66.id' ){
			return $this->Fichiermodule->sq(
				array(
					'fields' => array(
						'COUNT(fichiersmodules.id)'
					),
					'alias' => 'fichiersmodules',
					'contain' => false,
					'conditions' => array(
						"fichiersmodules.fk_value = {$nonoriente66Id}",
						"fichiersmodules.modele" => $fileModelName
					),
				)
			);
		}
		
	}
?>