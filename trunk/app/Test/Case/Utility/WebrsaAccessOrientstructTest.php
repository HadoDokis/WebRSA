<?php
	/**
	 * Code source de la classe WebrsaAccessOrientstructTest.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Case.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'WebrsaAccessOrientstruct', 'Utility' );

	/**
	 * La classe WebrsaAccessOrientstructTest réalise les tests unitaires de la classe WebrsaAccessOrientstruct.
	 *
	 * @package app.Test.Case.Model
	 */
	class WebrsaAccessOrientstructTest extends CakeTestCase
	{
		/**
		 * Fixtures utilisés.
		 *
		 * @var array
		 */
		public $fixtures = array();

		/**
		 * Test de la méthode WebrsaAccessOrientstruct::access()
		 *
		 * @covers WebrsaAccessOrientstruct::access
		 */
		public function testAccess() {
			Configure::write( 'Cg.departement', 58 );
			$record = array(
				'Orientstruct' => array(
					'rgorient' => 1,
					'printable' => true,
					'linked_records' => true,
					'dernier' => true,
					'dernier_oriente' => true
				)
			);
			$params = array(
				'ajout_possible' => true,
				'reorientationseps' => false
			);
			$result = WebrsaAccessOrientstruct::access( $record, $params );
			$expected = array(
				'Orientstruct' => array(
					'rgorient' => 1,
					'printable' => true,
					'linked_records' => true,
					'dernier' => true,
					'dernier_oriente' => true,
					'edit' => true,
					'impression' => true,
					'delete' => false,
				)
			);
			$this->assertEqual( $result, $expected, 'Failed in '.__FUNCTION__.' : '.__LINE__ );
		}

		/**
		 * @covers WebrsaAccessOrientstruct::check
		 */
		public function testCheck() {
			Configure::write( 'Cg.departement', 58 );
			$record = array(
				'Orientstruct' => array(
					'rgorient' => 1,
					'printable' => true,
					'linked_records' => true,
				)
			);
			$params = array(
				'ajout_possible' => true,
				'reorientationseps' => false
			);

			// Défini
			$result = WebrsaAccessOrientstruct::check( 'impression', $record, $params );
			$this->assertTrue( $result, 'Failed in '.__FUNCTION__.' : '.__LINE__ );

			// Non défini
			$result = WebrsaAccessOrientstruct::check( 'foo', $record, $params );
			$this->assertFalse( $result, 'Failed in '.__FUNCTION__.' : '.__LINE__ );
		}

		/**
		 * @covers WebrsaAccessOrientstruct::_edit
		 */
		public function test_edit() {
			Configure::write( 'Cg.departement', 58 );
			$record = array(
				'Orientstruct' => array(
					'rgorient' => 1,
					'printable' => true,
					'linked_records' => true,
				)
			);
			$params = array(
				'ajout_possible' => true,
				'reorientationseps' => false
			);

			$result = WebrsaAccessOrientstruct::check( 'edit', $record, $params );
			$this->assertTrue( $result, 'Failed in '.__FUNCTION__.' : '.__LINE__ );
		}
	}
?>
