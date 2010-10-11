<?php
class PropopdoStatutpdo extends AppModel {
	var $name = 'PropopdoStatutpdo';

    var $actsAs = array (
        'Nullable',
        'ValidateTranslate'
    );
    
    var $validate = array(
        'propopdo_id' => array(
            array( 'rule' => 'notEmpty' )
        ),
        'statutpdo_id' => array(
            array( 'rule' => 'notEmpty' )
        )
    );
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Propopdo' => array(
			'className' => 'Propopdo',
			'foreignKey' => 'propopdo_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Statutpdo' => array(
			'className' => 'Statutpdo',
			'foreignKey' => 'statutpdo_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
?>
