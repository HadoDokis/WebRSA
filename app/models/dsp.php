<?php
    class Dsp extends AppModel
    {
        var $name = 'Dsp';

//         var $actsAs = array( 'Enumerable' );

        var $belongsTo = array( 'Personne' );

        var $hasMany = array(
			'Detaildifsoc',
			'Detailaccosocfam',
// 			'Detailaccosocindi', // FIXME: ajouter les autres modèles depuis branches/trunk.bak
// 			'Detaildifdisp',
// 			'Detailnatmob',
// 			'Detaildiflog'
		);

        var $actsAs = array(
            'Enumerable' => array(
                'fields' => array(
                    'sitpersdemrsa' => array(
						'values' => array( '0101', '0102', '0103', '0104', '0105', '0106', '0107', '0108', '0109' )
					),
					'nivetu' => array(
						array( '1201', '1202', '1203', '1204', '1205', '1206', '1207' )
					),
					'nivdipmaxobt' => array(
						'values' => array( '2601', '2602', '2603', '2604', '2605', '2606' )
					),
					'hispro' => array(
						'values' => array( '1901', '1902', '1903', '1904' )
					),
					'cessderact' => array(
						'values' => array( '2701', '2702' )
					),
					'duractdomi' => array(
						'values' => array( '2104', '2105', '2106', '2107' )
					),
					'inscdememploi' => array(
						'values' => array( '4301', '4302', '4303', '4304'  )
					),
					'accoemploi' => array(
						'values' => array( '1801', '1802', '1803'  )
					),
					'natlog' => array(
						'values' => array( '0901', '0902', '0903', '0904', '0905', '0906', '0907', '0908', '0909', '0910', '0911', '0912', '0913' )
					),
					'demarlog' => array(
						'values' => array( '1101', '1102', '1103' )
					),
					'topisogroouenf' => array( 'type' => 'booleannumber', 'domain' => 'default', ),
					'topdrorsarmiant' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'topcouvsoc' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'topqualipro' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'topcompeextrapro' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'topengdemarechemploi' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'topdomideract' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'topisogrorechemploi' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'topprojpro' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'topcreareprientre' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'topmoyloco' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'toppermicondub' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'topautrpermicondu' => array( 'type' => 'booleannumber', 'domain' => 'default' ),
					'accosocfam' => array( 'type' => 'nov', 'domain' => 'default' ),
					'accosocindi' => array( 'type' => 'nov', 'domain' => 'default' ),
					'soutdemarsoc' => array( 'type' => 'nov', 'domain' => 'default' ),
					'concoformqualiemploi' => array( 'type' => 'nos', 'domain' => 'default' ),
					'drorsarmianta2' => array( 'type' => 'nos', 'domain' => 'default' ),
                )
            )
        );
    }
?>