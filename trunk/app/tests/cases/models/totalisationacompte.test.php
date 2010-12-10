<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Totalisationacompte');

	class TotalisationacompteTestCase extends CakeAppModelTestCase {

		//function search( $criteres )
		function testSearch() {
			$criteres = null;
			$result = $this->Totalisationacompte->search($criteres);
			$expected = array(
				'fields' => array(
					'0' => '"Totalisationacompte"."type_totalisation"',
					'1' => 'SUM("Totalisationacompte"."mttotsoclrsa") AS "Totalisationacompte__mttotsoclrsa"',
					'2' => 'SUM("Totalisationacompte"."mttotsoclmajorsa") AS "Totalisationacompte__mttotsoclmajorsa"',
					'3' => 'SUM("Totalisationacompte"."mttotlocalrsa") AS "Totalisationacompte__mttotlocalrsa"',
					'4' => 'SUM("Totalisationacompte"."mttotrsa") AS "Totalisationacompte__mttotrsa"',
				),
				'recursive' => '-1',
				'joins' => array(
					'0' => array(
						'table'=> 'identificationsflux',
						'alias'=> 'Identificationflux',
						'type'=> 'INNER',
						'foreignKey'=> false,
						'conditions'=> array(
							'0'=> 'Totalisationacompte.identificationflux_id = Identificationflux.id'
							),
						),
					),
				'group' => array(
					'0' => 'Totalisationacompte.type_totalisation',
					'1' => 'Totalisationacompte.id'
						),
				'order' => array(
					'0' => '"Totalisationacompte"."id" ASC'
						),
				'conditions' => array(),
				);
			$this->assertEqual($expected, $result);

//			$criteres = null;
//			$result = $this->Totalisationacompte->search($criteres);
//			var_dump($result);
		}
	}

?>
