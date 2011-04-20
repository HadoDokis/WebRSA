<?php

    class DspRev extends AppModel
    {
        var $name = 'DspRev';

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
			'DetailconfortRev'/*,
            'Fichiermodule' => array(
                'className' => 'Fichiermodule',
                'foreignKey' => false,
                'dependent' => false,
                'conditions' => array(
                    'Fichiermodule.modele = \'DspRev\'',
                    'Fichiermodule.fk_value = {$__cakeID__$}'
                ),
                'fields' => '',
                'order' => '',
                'limit' => '',
                'offset' => '',
                'exclusive' => '',
                'finderQuery' => '',
                'counterQuery' => ''
            )*/
		);
    }
?>
