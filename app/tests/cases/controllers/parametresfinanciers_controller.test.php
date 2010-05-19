<?php

	require_once( dirname( __FILE__ ).'/../cake_app_controller_test_case.php' );

	App::import('Controller','Parametresfinanciers');

	class TestParametresfinanciersController extends ParametresfinanciersController {
		var $autoRender = false;
		var $redirectUrl;
		var $redirectStatus;
		var $renderedAction;
		var $renderedLayout;
		var $renderedFile;
		var $stopped;
		var $name='Parametresfinanciers';
	
		function redirect($url, $status = null, $exit = true) {
			$this->redirectUrl = $url;
			$this->redirectStatus = $status;
		}
	
		function render($action = null, $layout = null, $file = null) {
			$this->renderedAction = $action;
			$this->renderedLayout = (is_null($layout) ? $this->layout : $layout);
			$this->renderedFile = $file;
		}
	
		function _stop($status = 0) {
			$this->stopped = $status;
		}
	}

	class ParametresfinanciersControllerTest extends CakeAppControllerTestCase
	{

		function testIndexCancel() {
			$this->ParametresfinanciersController->params['form']['Cancel'] = '12345';
			$this->ParametresfinanciersController->index();
			$this->assertEqual(array('controller' =>'apres','action' =>'indexparams'),$this->ParametresfinanciersController->redirectUrl);
		}

		function testIndexNormal() {
			$this->ParametresfinanciersController->index();
			$records = array (
				"Parametrefinancier" => array (
					"id" => 1,
					"entitefi" => "ef",
					"engagement" => "ef",
					"tiers" => "ef",
					"codecdr" => "ef",
					"libellecdr" => "ef",
					"natureanalytique" => "ef",
					"programme" => "ef",
					"lib_programme" => "ef",
					"apreforfait" => "ef",
					"aprecomplem" => "ef",
					"natureimput" => "ef",
					"lib_natureanalytique" => null

				)
			);

			$this->assertEqual($records,$this->ParametresfinanciersController->viewVars['parametrefinancier']);
		}

		function testEditWithoutData() {
			$this->ParametresfinanciersController->edit();
			$records = array (
				"Parametrefinancier" => array (
					"id" => 1,
					"entitefi" => "ef",
					"engagement" => "ef",
					"tiers" => "ef",
					"codecdr" => "ef",
					"libellecdr" => "ef",
					"natureanalytique" => "ef",
					"programme" => "ef",
					"lib_programme" => "ef",
					"apreforfait" => "ef",
					"aprecomplem" => "ef",
					"natureimput" => "ef",
					"lib_natureanalytique" => null
				)
			);
			$this->assertEqual($records,$this->ParametresfinanciersController->data);
		}

		function testEditWithData() {
			$this->ParametresfinanciersController->data=array(
					"id" => 2,
					"entitefi" => "rg",
					"engagement" => "rg",
					"tiers" => "rg",
					"codecdr" => "rg",
					"libellecdr" => "rg",
					"natureanalytique" => "rg",
					"programme" => "rg",
					"lib_programme" => "rg",
					"apreforfait" => "rg",
					"aprecomplem" => "rg",
					"natureimput" => "rg"
			);
			$this->ParametresfinanciersController->edit();
			$this->assertEqual(array('controller'=>'parametresfinanciers','action'=>'index'), $this->ParametresfinanciersController->redirectUrl);
		}

	}
?>
