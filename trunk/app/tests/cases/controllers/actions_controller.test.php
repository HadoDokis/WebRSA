<?php

	require_once( dirname( __FILE__ ).'/../cake_app_controller_test_case.php' );

	App::import('Controller', 'Actions');

	class TestActionsController extends ActionsController {

		var $autoRender = false;
		var $redirectUrl;
		var $redirectStatus;
		var $renderedAction;
		var $renderedLayout;
		var $renderedFile;
		var $stopped;
		var $name='Actions';

		public function redirect($url, $status = null, $exit = true) {
			$this->redirectUrl = $url;
			$this->redirectStatus = $status;
		}

		public function render($action = null, $layout = null, $file = null) {
			$this->renderedAction = $action;
			$this->renderedLayout = (is_null($layout) ? $this->layout : $layout);
			$this->renderedFile = $file;
		}

		public function _stop($status = 0) {
			$this->stopped = $status;
		}

		public function assert( $condition, $error = 'error500', $parameters = array() ) {
			$this->condition = $condition;
			$this->error = $error;
			$this->parameters = $parameters;
		}

	}

	class ActionsControllerTest extends CakeAppControllerTestCase {

		function testBeforeFilter() {
			$this->ActionsController->beforeFilter();
			$expected = array(
				'1' => 'Facilités offertes',
				'2' => 'Autonomie sociale',
				'3' => 'Logement',
				'4' => 'Insertion professionnelle',
				'5' => 'Emploi',
			);
			$this->assertEqual($expected, $this->ActionsController->viewVars['libtypaction']);
		}

		function testIndexCancel() {
			$this->ActionsController->params['form']['Cancel'] = '1337';
			$this->ActionsController->index();
			$this->assertEqual(array('controller' => 'parametrages', 'action' => 'index'), $this->ActionsController->redirectUrl);
		}

		function testIndexNormal() {
			$this->ActionsController->index();
			$records = array (
					'0' => array(
						'Action' => array (
							'id' => '1',
							'typeaction_id' => '1',
							'code' => '04',
							'libelle' => 'Aide pour la garde des enfants',
						),
						'Typeaction' => array(
							'id' => '1',
                                    			'libelle' => 'Facilités offertes',
						),
					),
					'1' => array(
						'Action' => array (
							'id' => '2',
							'typeaction_id' => '2',
							'code' => '21',
							'libelle' => 'Démarche liée à la santé',
						),
						'Typeaction' => array(
							'id' => '2',
                                    			'libelle' => 'Autonomie sociale',
						),
					),
					'2' => array(
						'Action' => array (
							'id' => '3',
							'typeaction_id' => '3',
							'code' => '31',
							'libelle' => 'Recherche d\'un logement',
						),
						'Typeaction' => array(
							'id' => '3',
                                    			'libelle' => 'Logement',
						),
					),
					'3' => array(
						'Action' => array (
							'id' => '4',
							'typeaction_id' => '4',
							'code' => '44',
							'libelle' => 'Stage de conduite automobile (véhicules légers)',
						),
						'Typeaction' => array(
							'id' => '4',
                                    			'libelle' => 'Insertion professionnelle',
						),
					),
					'4' => array(
						'Action' => array (
							'id' => '5',
							'typeaction_id' => '5',
							'code' => '51',
							'libelle' => 'Aide ou suivi pour une recherche d\'emploi',
						),
						'Typeaction' => array(
							'id' => '5',
                                    			'libelle' => 'Emploi',
						),
					),
			);
			$this->assertEqual($records, $this->ActionsController->viewVars['actions']);
		}

		function testAdd() {
			$this->ActionsController->data = array(
					'0' => array(
						'id' => '1',
						'typeaction_id' => '1',
						'code' => null,
						'libelle' => 'libellé',
					),
					'1' => array(
						'id' => '2',
						'typeaction_id' => '1',
						'code' => null,
						'libelle' => 'libellé',
					),
			);
			$this->ActionsController->add();
			$this->assertEqual('default', $this->ActionsController->renderedLayout);
			$this->assertEqual('add_edit', $this->ActionsController->renderedFile);
		}


		function testEditWithoutData() {
			$action_id = '1';
			$this->ActionsController->edit($action_id);
			$records = array (
				'Action' => array (
					'id' => '1',
					'typeaction_id' => '1',
					'code' => '04',
					'libelle' => 'Aide pour la garde des enfants',
				),
				'Typeaction' => array(
					'id' => '1',
                        		'libelle' => 'Facilités offertes',
					'Action' => array(
						'0' => array(
							'id' => '1',
							'typeaction_id' => '1',
							'code' => '04',
							'libelle' => 'Aide pour la garde des enfants',
						),
					),
				),
			);
			$this->assertEqual($records, $this->ActionsController->data);
			$action_id = '2';
			$this->ActionsController->edit($action_id);
			$records = array (
				'Action' => array (
					'id' => '1',
					'typeaction_id' => '1',
					'code' => '04',
					'libelle' => 'Aide pour la garde des enfants',
				),
				'Typeaction' => array(
					'id' => '1',
                        		'libelle' => 'Facilités offertes',
					'Action' => array(
						'0' => array(
							'id' => '1',
							'typeaction_id' => '1',
							'code' => '04',
							'libelle' => 'Aide pour la garde des enfants',
						),
					),
				),
			);
			$this->assertEqual($records, $this->ActionsController->data);
		}

		function testDelete() {
			$action_id = '1';
			$this->ActionsController->delete($action_id);
			$this->assertEqual(array('controller'=> 'actions','action'=>'index'), $this->ActionsController->redirectUrl);
		}

	}

?>
