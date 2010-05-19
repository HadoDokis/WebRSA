<?php
	require_once( dirname( __FILE__ ).'/../cake_app_helper_test_case.php' );

	App::import(
		'Helper',
		array(
			'Html',
			'Locale' ,
			'Xform' ,
			'Type' ,
			'Time' ,
		)
	);

	class TypeTestCase extends CakeAppHelperTestCase
	{

		public function testFormat() {
			$item = array(
				'Item' => array(
					'firstname' => 'Christian',
					'date_a' => '1979-01-24',
					'tel' => '0102030405',
				)
			);

			$result = $this->Type->format( $item, 'Item.tel' );
			//$this->assertEqual( $result, '01 02 03 04 05' ); // FIXME: voir avec TypeBehavior
			$this->assertEqual( $result, '0102030405' );

			$result = $this->Type->format( $item, 'Item.date_a', array( 'tag' => 'p' ) );
			$this->assertEqual( $result, '<p class="date">24/01/1979</p>' );

			$result = $this->Type->format( $item, 'Item.tel', array( 'tag' => 'p' ) );
			//$this->assertEqual( $result, '<p class="phone">01&nbsp;02&nbsp;03&nbsp;04&nbsp;05</p>' ); // FIXME: voir avec TypeBehavior
			$this->assertEqual( $result, '<p class="string">0102030405</p>' );
		}

		public function testInput() {
			$result = $this->Type->input( 'Item.tel' );
			//$expected = '<div class="input text"><label for="ItemTel">Téléphone</label><input name="data[Item][tel]" type="text" maxlength="14" value="" id="ItemTel" /></div>'; // FIXME: voir avec TypeBehavior AutovalidateBehavior
			$expected = '<div class="input text"><label for="ItemTel">Item.tel</label><input name="data[Item][tel]" type="text" value="" id="ItemTel" /></div>';
			$this->assertEqual( $result, $expected );
		}
	}
?>