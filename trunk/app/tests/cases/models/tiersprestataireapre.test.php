<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'Tiersprestataireapre');

	class TiersprestataireapreTestCase extends CakeAppModelTestCase {

		function testAdminList() {
			$result = $this->Tiersprestataireapre->adminList();
			$expected = array(
				'0' => array(
					'Tiersprestataireapre' => array(
						'id' => '1',
						'nomtiers' => null,
						'siret' => null,
						'numvoie' => null,
						'typevoie' => null,
						'nomvoie' => null,
						'compladr' => null,
						'codepos' => null,
						'ville' => null,
						'canton' => null,
						'numtel' => null,
						'adrelec' => null,
						'nomtiturib' => null,
						'etaban' => null,
						'guiban' => null,
						'numcomptban' => null,
						'clerib' => null,
						'aidesliees' => null,
						'nometaban' => null,
						'deletable' => false,
					),
				),
			);
			$this->assertEqual($expected, $result);
		}

		//check_rib($cbanque = null, $cguichet = null, $nocompte = null, $clerib = null)
		function testCheck_rib() {
			$tierpresta_id = '1';
			$this->Tiersprestataireapre->data = $this->Tiersprestataireapre->find('first', array(
					'conditions' => array(
						'id' => $tierpresta_id
					)
				)
			);
			$result = $this->Tiersprestataireapre->check_rib(null, null, null, null);
			$this->assertFalse($result);

			$tierpresta_id = '1';
			$this->Tiersprestataireapre->data = $this->Tiersprestataireapre->find('first', array(
					'conditions' => array(
						'id' => $tierpresta_id
					)
				)
			);
			$this->Tiersprestataireapre->data['Tiersprestataireapre']['numcomptban'] = '12345678910';
			$result = $this->Tiersprestataireapre->check_rib('970 000 000 000 000 000 000 ', null, null, null);
			$this->assertTrue($result);

		}

	}

?>
