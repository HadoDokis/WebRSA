<?php
	/**
	 * Code source de la classe PrototypeAjaxHelperTest.
	 *
	 * PHP 5.3
	 *
	 * @package Prototype
	 * @subpackage Test.Case.View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Controller', 'Controller' );
	App::uses( 'View', 'View' );
	App::uses( 'AppHelper', 'View/Helper' );
	App::uses( 'PrototypeAjaxHelper', 'Prototype.View/Helper' );

	/**
	 * La classe PrototypeAjaxHelperTest ...
	 *
	 * @package Prototype
	 * @subpackage Test.Case.View.Helper
	 */
	class PrototypeAjaxHelperTest extends CakeTestCase
	{
		/**
		 * Fixtures utilisées pour les tests.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'core.Apple',
		);

		/**
		 * Le contrôleur utilisé pour les tests.
		 *
		 * @var Controller
		 */
		public $Controller = null;

		/**
		 * Le contrôleur utilisé pour les tests.
		 *
		 * @var View
		 */
		public $View = null;

		/**
		 * Le helper à tester.
		 *
		 * @var PrototypeAjax
		 */
		public $Observer = null;

		/**
		 * Préparation du test.
		 */
		public function setUp() {
			parent::setUp();
			$Request = new CakeRequest();
			$this->Controller = new Controller( $Request );
			$this->View = new View( $this->Controller );
			$this->Ajax = new PrototypeAjaxHelper( $this->View );
			$this->Ajax->useBuffer = false;
		}

		/**
		 * Nettoyage postérieur au test.
		 */
		public function tearDown() {
			parent::tearDown();
			unset( $this->Controller, $this->View, $this->Ajax );
		}

		/**
		 * Test de la méthode PrototypeAjaxHelper::autocomplete()
		 */
		public function testAutocomplete() {
			$result = $this->Ajax->autocomplete(
				'Ficheprescription93.numconvention',
				array(
					'url' => array( 'action' => 'ajax_ficheprescription93_numconvention' )
				)
			);
			$expected = '<script type="text/javascript">
//<![CDATA[
$( \'Ficheprescription93Numconvention\' ).writeAttribute( \'autocomplete\', \'off\' );
Event.observe( $( \'Ficheprescription93Numconvention\' ), \'keyup\', function() {
	new Ajax.Request(
		\'/ajax_ficheprescription93_numconvention\',
		{
			method: \'post\',
			parameters: {
				\'data[path]\': \'Ficheprescription93.numconvention\',
				\'data[prefix]\': \'\',
				\'data[Ficheprescription93][numconvention]\': $F( \'Ficheprescription93Numconvention\' )
			},
			onSuccess: function( response ) {
				var oldAjaxSelect = $( \'ajaxSelect\' );
				if( oldAjaxSelect ) {
					$( oldAjaxSelect ).remove();
				}

				var json = response.responseText.evalJSON();

				if( $(json).length > 0 ) {
					var ajaxSelect = new Element( \'ul\' );

					$( json ).each( function ( result ) {
						var a = new Element( \'a\', { href: \'#\', onclick: \'return false;\' } ).update( result[\'name\'] );

						$( a ).observe( \'click\', function( event ) {
							for( field in result.values ) {
								$( field ).value = result[\'values\'][field];
								$( field ).simulate( \'change\' );
							}

							$( \'Ficheprescription93Numconvention\' ).value = result[\'Ficheprescription93Numconvention\'];

							$( \'ajaxSelect\' ).remove();

							return false;
						} );

						$( ajaxSelect ).insert( { bottom: $( a ).wrap( \'li\' ) } );
					} );

					$( \'Ficheprescription93Numconvention\' ).up( \'div\' ).insert(  { after: $( ajaxSelect ).wrap( \'div\', { id: \'ajaxSelect\', class: \'ajax select\' } ) }  );
				}
			}
		}
	);
} );
//]]>
</script>';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode PrototypeAjaxHelper::updateDivOnFieldsChange()
		 */
		public function testUpdateDivOnFieldsChange() {
			$result = $this->Ajax->updateDivOnFieldsChange(
				'CoordonneesPrescripteur',
				array( 'action' => 'ajax_prescripteur' ),
				array(
					'Ficheprescription93.structurereferente_id',
					'Ficheprescription93.referent_id',
				)
			);
			$expected = '<script type="text/javascript">
//<![CDATA[
function updateDivOnFieldsChangeCoordonneesPrescripteur() {
		new Ajax.Updater(
			\'CoordonneesPrescripteur\',
			\'/ajax_prescripteur\',
			{
				asynchronous: true,
				evalScripts: true,
				parameters: { \'data[Ficheprescription93][structurereferente_id]\': $F( \'Ficheprescription93StructurereferenteId\' ),\'data[Ficheprescription93][referent_id]\': $F( \'Ficheprescription93ReferentId\' ) }
			}
		);
	}
	document.observe( \'dom:loaded\', function() { updateDivOnFieldsChangeCoordonneesPrescripteur(); } );
Event.observe( $( \'Ficheprescription93StructurereferenteId\' ), \'change\', function() { updateDivOnFieldsChangeCoordonneesPrescripteur(); } );
Event.observe( $( \'Ficheprescription93ReferentId\' ), \'change\', function() { updateDivOnFieldsChangeCoordonneesPrescripteur(); } );
//]]>
</script>';
			$this->assertEqual( $result, $expected, var_export( $result, true ) );
		}
	}
?>