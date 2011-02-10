<?php
	class Informationpe extends AppModel
	{
		public $name = 'Informationpe';

        public $recursive = -1;

        // FIXME: validation
		// FIXME ?
		/*public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => false,
				'conditions' => array(
					'OR' => array(
						array(
							'Personne.nir IS NOT NULL',
							'Informationpe.nir IS NOT NULL',
							'Personne.nir = Informationpe.nir',
						),
						array(
							'Personne.nom = Informationpe.nom',
							'Personne.prenom = Informationpe.prenom',
							'Personne.dtnai = Informationpe.dtnai',
						),
					)
				),
				'fields' => '',
				'order' => ''
			)
		);*/

		public $hasMany = array(
			'Historiqueetatpe' => array(
				'className' => 'Historiqueetatpe',
				'foreignKey' => 'informationpe_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
		);
	}
?>