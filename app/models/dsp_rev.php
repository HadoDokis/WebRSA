<?php

    class DspRev extends AppModel
    {
        var $name = 'DspRev';

        public $actsAs = array(
            'Enumerable' => array(
                'fields' => array(
                    'haspiecejointe'
                )
            )
        );

        var $hasMany = array(
			'DetaildifsocRev',
			'DetailaccosocfamRev',
			'DetailaccosocindiRev',
			'DetaildifdispRev',
			'DetailnatmobRev',
			'DetaildiflogRev',
			'DetailmoytransRev',
			'DetaildifsocproRev',
			'DetailprojproRev',
			'DetailfreinformRev',
			'DetailconfortRev',
            'Fichiermodule' => array(
                'className' => 'Fichiermodule',
                'foreignKey' => false,
                'dependent' => false,
                'conditions' => array(
                    'Fichiermodule.modele = \'Dsp\'',
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
    }
?>
