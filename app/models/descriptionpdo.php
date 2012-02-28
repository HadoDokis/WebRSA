<?php
	class Descriptionpdo extends AppModel
	{
		public $name = 'Descriptionpdo';

		public $actsAs = array(
			'Autovalidate',
			'Formattable',
			'Enumerable' => array(
				'fields' => array(
					'sensibilite',
					'dateactive',
					'decisionpcg',
					'nbmoisecheance',
// 					'declencheep'
				)
			),
			'ValidateTranslate'
		);

		public $validate = array(
			'name' => array(
				array(
					'rule' => array('notEmpty'),
				),
				array(
					'rule' => array('isUnique'),
				),
			),
			'sensibilite' => array(
				array(
					'rule' => array('notEmpty'),
				),
			),
		);

		public $hasMany = array(
			'Traitementpdo' => array(
				'className' => 'Traitementpdo',
				'foreignKey' => 'descriptionpdo_id',
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
			'Traitementpcg66' => array(
				'className' => 'Traitementpcg66',
				'foreignKey' => 'descriptionpdo_id',
				'dependent' => true,
				'conditions' => '',
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
		 * Retourne la liste des modèles odt paramétrés pour le impressions de
		 * cette classe.
		 *
		 * @return array
		 */
		public function modelesOdt() {
			$prefix = 'Descriptionpdo'.DS;

			$items = $this->find(
				'all',
				array(
					'fields' => array(
						'( \''.$prefix.'\' || "'.$this->alias.'"."modelenotification" || \'.odt\' ) AS "'.$this->alias.'__modele"',
					),
					'conditions' => array( ''.$this->alias.'.modelenotification IS NOT NULL' ),
					'recursive' => -1
				)
			);
			return Set::extract( $items, '/'.$this->alias.'/modele' );
		}
	}
?>