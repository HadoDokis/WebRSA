<?php
	class Parcoursdetecte extends AppModel
	{
		var $name = 'Parcoursdetecte';

		var $validate = array(
			'orientstruct_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
		);

		var $belongsTo = array(
			'Orientstruct' => array(
				'className' => 'Orientstruct',
				'foreignKey' => 'orientstruct_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Ep' => array(
				'className' => 'Ep',
				'foreignKey' => 'ep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Osnv' => array(
				'className' => 'Osnv',
				'foreignKey' => 'osnv_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		var $hasMany = array(
			'Decisionparcours' => array(
				'className' => 'Decisionparcours',
				'foreignKey' => 'parcoursdetecte_id',
				'dependent' => false,
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

	}
?>