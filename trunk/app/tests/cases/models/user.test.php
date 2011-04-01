<?php

	require_once( dirname( __FILE__ ).'/../cake_app_model_test_case.php' );

	App::import('Model', 'User');
	App::import('Core', 'Security');

	class UserTestCase extends CakeAppModelTestCase {

		function testBeforeSave() {
			$this->User->data = $this->User->find('first', array(
					'conditions' => array(
						'id' => '4'
					)
				)		
			);
			$result = $this->User->beforeSave();
			$this->assertTrue($result);
		}
/*
		function testBeforeDelete() {
			$this->assertFalse($this->User->beforeDelete());
		}
*/
		function testValidatesPassword() {
			$data = array(
				'User' => array(
					'id' => '1',
					'passwd' => 'c41d80854d210d5f7512ab216b53b2f2b8e742dc',
					'newpasswd' => 'c41d80854d210d5f7512ab216b53b2f2b8e742dd',
					'confnewpasswd' => 'c41d80854d210d5f7512ab216b53b2f2b8e742de',
				),
			);
			$result = $this->User->validatesPassword($data);
			$this->assertFalse($result);
		}

		function testValidOldPassword() {
			$data = array(
				'User' => array(
					'id' => '1',
					'passwd' => 'c41d80854d210d5f7512ab216b53b2f2b8e742dc',
					'newpasswd' => 'c41d80854d210d5f7512ab216b53b2f2b8e742de',
					'confnewpasswd' => 'c41d80854d210d5f7512ab216b53b2f2b8e742df',
				),
			);
			$result = $this->User->validOldPassword($data);
			$this->assertFalse($result);
		}
	}

?>
