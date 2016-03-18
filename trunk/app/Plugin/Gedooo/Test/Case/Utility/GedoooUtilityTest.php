<?php
	/**
	 * Code source de la classe GedoooUtilityTest.
	 *
	 * PHP 5.3
	 *
	 * @package Gedooo.Test.Case.Utility
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'GedoooUtility', 'Gedooo.Utility' );

	/**
	 * La classe GedoooUtilityTest ...
	 *
	 * @package Gedooo.Test.Case.Utility
	 */
	class GedoooUtilityTest extends CakeTestCase
	{
		/**
		 * Test de la méthode GedoooUtility::key()
		 */
		public function testKey() {
			$result = GedoooUtility::key( 'Foo' );
			$this->assertEqual( $result, 'foo', $result );

			$result = GedoooUtility::key( 'FooBar' );
			$this->assertEqual( $result, 'foobar', $result );

			$result = GedoooUtility::key( 'Foo.bar' );
			$this->assertEqual( $result, 'foo_bar', $result );
		}

		/**
		 * Test de la méthode GedoooUtility::msgstr()
		 */
		public function testMsgstr() {
			// FIXME: ajouter un fichier de traduction et des tests propres au plugin
			$result = GedoooUtility::msgstr( 'Orientstruct.typeorient_id' );
			$this->assertEqual( $result, 'Type d\'orientation', $result );
		}

	}
?>