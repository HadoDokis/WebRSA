<?php
	require_once( dirname( __FILE__ ).'/../../../cake/console/libs/shell.php' );

	if (!defined('DISABLE_AUTO_DISPATCH')) {
		define('DISABLE_AUTO_DISPATCH', true);
	}
	
	if (!class_exists('ShellDispatcher')) {
		ob_start();
		$argv = false;
		require CAKE . 'console' .  DS . 'cake.php';
		ob_end_clean();
	}
	
	Mock::generatePartial('ShellDispatcher', 'TestShellMockShellDispatcher', array(
		'getInput', 'stdout', 'stderr', '_stop', '_initEnvironment'
	));

	class CakeAppShellTestCase extends CakeTestCase
	{

		function startTest() {
			$this->Dispatcher =& new TestShellMockShellDispatcher();
			$shellName = preg_replace( '/Test$/', '', get_class( $this ) );
			$this->{$shellName} =& new $shellName($this->Dispatcher);
		}
		
		function endTest() {
			unset($this->Detectionparcours);
			ClassRegistry::flush();
		}
	}
?>
