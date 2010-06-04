<?php
	require_once( dirname( __FILE__ ).'/../cake_app_helper_test_case.php' );

	App::import( 'Helper', array( 'Form', 'Html', 'Xform' ));

	class XformTestCase extends CakeAppHelperTestCase
	{

		public function testCreate() {
			$this->assertEqual(
				$this->Xform->create(),
				'<form method="post" action="'.Router::url( null, true ).'"><fieldset style="display:none;"><input type="hidden" name="_method" value="POST" /></fieldset>'
			);
		}

		public function testSubmit() {
			$this->assertEqual(
				$this->Xform->submit( 'Save' ),
				'<div class="submit"><input type="submit" value="'.__( 'Save', true ).'" /></div>'
			);
		}

		public function testRequired() {
			$this->assertEqual(
				$this->Xform->required( 'Foo' ),
				'Foo <abbr class="required" title="Champ obligatoire">*</abbr>'
			);
		}

		public function testInput() {
			$this->assertEqual(
				$this->Xform->input( 'Item.name_a' ),
				'<div class="input text"><label for="ItemNameA">Item.name_a</label><input name="data[Item][name_a]" type="text" value="" id="ItemNameA" /></div>'
			);
		}

		public function testMultiple() {
			$this->assertEqual(
				$this->Xform->multiple( 'User.group_id', array( 'options' => array( 1 => 'Administrators' ) ) ),
				"<fieldset class=\"multiple\"><legend>User.group_id</legend><div class=\"input select\"><label for=\"UserGroupId\">User.group_id</label><select name=\"data[User][group_id]\" id=\"UserGroupId\">\n<option value=\"1\">Administrators</option>\n</select></div></fieldset>"
			);
		}
	}
?>
