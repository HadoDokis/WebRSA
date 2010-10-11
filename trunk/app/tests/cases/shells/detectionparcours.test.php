<?php
	require_once( dirname( __FILE__ ).'/../cake_app_shell_test_case.php' );
	require_once( dirname( __FILE__ ).'/../../../vendors/shells/detectionparcours.php' );

	class DetectionparcoursTest extends CakeAppShellTestCase
	{
		function testErr() {
			$this->Detectionparcours->outfile='fichier de sortie';
			$this->Detectionparcours->err('test');
			$this->assertEqual("Erreur: test\n", $this->Detectionparcours->output);
		}
	}
?>
