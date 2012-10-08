<?php
	class Courrierpdo extends AppModel
	{
		public $name = 'Courrierpdo';

		public $actsAs = array(
			'Validation.Autovalidate',
			'ValidateTranslate',
			'Formattable'
		);

		public $hasMany = array(
			'Textareacourrierpdo' => array(
				'className' => 'Textareacourrierpdo',
				'foreignKey' => 'courrierpdo_id',
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

		public $hasAndBelongsToMany = array(
			'Traitementpdo' => array(
				'className' => 'Traitementpdo',
				'joinTable' => 'courrierspdos_traitementspdos',
				'foreignKey' => 'courrierpdo_id',
				'associationForeignKey' => 'traitementpdo_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'CourrierpdoTraitementpdo'
			),
			'Traitementpcg66' => array(
				'className' => 'Traitementpcg66',
				'joinTable' => 'courrierspdos_traitementspcgs66',
				'foreignKey' => 'courrierpdo_id',
				'associationForeignKey' => 'traitementpcg66_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'CourrierpdoTraitementpcg66'
			)
		);

		/**
		 * Retourne la liste des modèles odt paramétrés pour le impressions de
		 * cette classe.
		 *
		 * @return array
		 */
		public function modelesOdt() {
			$prefix = 'PDO'.DS.'Courrierpdo'.DS;

			$items = $this->find(
				'all',
				array(
					'fields' => array(
						'( \''.$prefix.'\' || "'.$this->alias.'"."modeleodt" || \'.odt\' ) AS "'.$this->alias.'__modele"',
					),
					'recursive' => -1
				)
			);
			return Set::extract( $items, '/'.$this->alias.'/modele' );
		}
	}
?>