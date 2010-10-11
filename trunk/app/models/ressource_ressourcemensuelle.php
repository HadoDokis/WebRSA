<?php
class RessourceRessourcemensuelle extends AppModel {
	var $name = 'RessourceRessourcemensuelle';
	var $validate = array(
		'ressourcemensuelle_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'ressource_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Ressourcemensuelle' => array(
			'className' => 'Ressourcemensuelle',
			'foreignKey' => 'ressourcemensuelle_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Ressource' => array(
			'className' => 'Ressource',
			'foreignKey' => 'ressource_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
?>