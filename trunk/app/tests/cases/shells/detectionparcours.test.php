<?php
	require_once( dirname( __FILE__ ).'/../../../../cake/console/libs/shell.php' );
	require_once( dirname( __FILE__ ).'/../../../vendors/shells/detectionparcours.php' );

	if (!defined('DISABLE_AUTO_DISPATCH')) {
		define('DISABLE_AUTO_DISPATCH', true);
	}
	
	if (!class_exists('ShellDispatcher')) {
		ob_start();
		$argv = false;
		require CAKE . 'console' .  DS . 'cake.php';
		ob_end_clean();
	}
	
	Mock::generatePartial('ShellDispatcher', 'DetectionparcoursShellMockShellDispatcher', array(
		'getInput', 'stdout', 'stderr', '_stop', '_initEnvironment'
	));

	class DetectionparcoursTest extends CakeTestCase
	{

		function startTest() {
			$this->Dispatcher =& new DetectionparcoursShellMockShellDispatcher();
			$this->Detectionparcours =& new DetectionparcoursShell($this->Dispatcher);
		}
		
		function endTest() {
			unset($this->Detectionparcours);
			ClassRegistry::flush();
		}

		function testErr() {
			$this->Detectionparcours->outfile='fichier de sortie';
			$this->Detectionparcours->err('test');
			$this->assertEqual("Erreur: test\n",	$this->Detectionparcours->output);
		}
	}
?>