<?php
    class Dsp extends AppModel
    {
        var $name = 'Dsp';

        var $actsAs = array( 'Enumerable' );

        var $belongsTo = array( 'Personne' );
        var $hasMany = array(
			'Detaildifsoc',
			'Detailaccosocfam',
			'Detailaccosocindi',
			'Detaildifdisp',
			'Detailnatmob',
			'Detaildiflog'
		);

        var $enumFields = array(
            'sitpersdemrsa',
            'topisogroouenf' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
            'topdrorsarmiant' => array( 'type' => 'no', 'domain' => 'default' ),
            'drorsarmianta2' => array( 'type' => 'nos', 'domain' => 'default' ),
            'topcouvsoc' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
            'accosocfam' => array( 'type' => 'nov', 'domain' => 'default' ),
            'accosocindi' => array( 'type' => 'nov', 'domain' => 'default' ),
            'soutdemarsoc' => array( 'type' => 'nov', 'domain' => 'default' ),
            'nivetu',
            'nivdipmaxobt',
            'topqualipro' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
            'topcompeextrapro' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
            'topengdemarechemploi' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
            'hispro',
            'cessderact',
            'topdomideract' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
            'duractdomi',
            'inscdememploi',
            'topisogrorechemploi' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
            'accoemploi',
            'topprojpro' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
            'topcreareprientre' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
            'concoformqualiemploi' => array( 'type' => 'nos', 'domain' => 'default' ),
            'topmoyloco' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
            'toppermicondub' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
            'topautrpermicondu' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
            'natlog',
            'demarlog'
        );
    }
?>