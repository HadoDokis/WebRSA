<?php

	require_once( dirname( __FILE__ ).'/../cake_app_component_test_case.php' );

	App::import('Component','Default');

	class TestDefaultComponent extends DefaultComponent {
		public $name='Default';
	}

	class DefaultTest extends CakeAppComponentTestCase {

		/**
		*
		*/
		public function testIndex() {
			$this->Items->params = array( 'url' => array( 'url' => 'items/' ), 'controller' => 'items', 'action' => 'index' );
			$this->Items->Default->index( array( 'Item' => array( 'conditions' => array( 'Item.id <' => 2 ) ) ) );
			$expected = array(
				"0" => array(
					"Item" => array(
							"id" => 1,
							"firstname" => "Firstname n°1",
							"lastname" => "Lastname n°1",
							"name_a" => "name_a",
							"name_b" => "name_b",
							"version_a" => 1,
							"version_n" => 1,
							"description_a" => "description_a",
							"description_b" => "description_b",
							"modifiable_a" => 1,
							"modifiable_b" => 1,
							"date_a" => "2010-03-17",
							"date_b" => "2010-03-17",
							"tel" => "0101010101",
							"fax" => "0101010101",
							"category_id" => 12,
							"foo" => "f",
							"bar" => "",
							"montant" => 666.66 
					)
				)
			);
			$this->assertEqual($expected, $this->Items->viewVars['items']);
		}

		/**
		*
		*/
		public function testView() {
			$this->Items->params=array('url'=>array('url'=>'items/view/1'),'controller'=>'items','action'=>'view','pass'=>array(1));
			$this->Items->Default->view( 1 );
			$expected = array(
				"Item" => array(
					"id" => 1,
					"firstname" => "Firstname n°1",
					"lastname" => "Lastname n°1",
					"name_a" => "name_a",
					"name_b" => "name_b",
					"version_a" => 1,
					"version_n" => 1,
					"description_a" => "description_a",
					"description_b" => "description_b",
					"modifiable_a" => 1,
					"modifiable_b" => 1,
					"date_a" => "2010-03-17",
					"date_b" => "2010-03-17",
					"tel" => "0101010101",
					"fax" => "0101010101",
					"category_id" => 12,
					"foo" => "f",
					"bar" => "",
					"montant" => 666.66
				)
			);
			$this->assertEqual($expected, $this->Items->viewVars['item']);
		}

		/**
		*
		*/
		function testAddEdit() {
			// test pour la fonction add le paramètre cancel a été passé
			$this->Items->params['form']['cancel'] = '12345';
			$this->Items->Default->add(12);
			$this->assertEqual(array("action"=>"index"),$this->Items->redirectUrl);

			//-------------------------------------------------------------------

			// test pour la fonction edit le paramètre cacel a été passé
			$this->Items->params['form']['cancel'] = '12345';
			$this->Items->Default->edit(1);
			$this->assertEqual(array("action"=>"index"),$this->Items->redirectUrl);

			//-------------------------------------------------------------------

			// test avec un item inexistant et doit donc retourner une erreur et rediriger vers l'index
			$this->Items->params=array('url'=>array('url'=>'items/edit/666'), 'controller'=>'items', 'action'=>'edit', 'pass'=>array(666));
			$this->Items->action="edit";
			$this->Items->Default->edit(666);
			$this->assertEqual(array("action"=>"index"),$this->Items->redirectUrl);
			$this->assertEqual("invalidParameter",$this->Items->error);

			//-------------------------------------------------------------------

			// 
			$this->Items->data = array(
				"Item" => array(
					"id" => 123,
					"firstname" => "Firstname n°123",
					"lastname" => "Lastname n°123",
					"name_a" => "new name_a",
					"name_b" => "new name_b",
					"version_a" => 4,
					"version_n" => 7,
					"description_a" => "zrg description_a",
					"description_b" => "jtdjd tdescription_b",
					"modifiable_a" => 1,
					"modifiable_b" => 1,
					"date_a" => "2010-03-18",
					"date_b" => "2010-04-23",
					"tel" => "0123456789",
					"fax" => "0123456780",
					"category_id" => 2,
					"foo" => "o",
					"bar" => "r",
					"montant" => 12345
				)
			);
			$this->Items->params=array('url'=>array('url'=>'items/add/123'), 'controller'=>'items', 'operation'=>'saveAll', 'action'=>'add', 'pass'=>array(123));
			$this->Items->action="add";
			$this->Items->Default->add(123);
			$result=$this->Items->Item->find('all',array('conditions'=>array('Item.id ='=>'123'),'recursive'=>-1));
			$expected=array(
				0 => array(
					'Item' => array(
						'id' => 123,
						'firstname' => 'Firstname n°123',
						'lastname' => 'Lastname n°123',
						'name_a' => 'new name_a',
						'name_b' => 'new name_b',
						'version_a' => 4,
						'version_n' => 7,
						'description_a' => 'zrg description_a',
						'description_b' => 'jtdjd tdescription_b',
						'modifiable_a' => 1,
						'modifiable_b' => 1,
						'date_a' => '2010-03-18',
						'date_b' => '2010-04-23',
						'tel' => '0123456789',
						'fax' => '0123456780',
						'category_id' => 2,
						'foo' => 'o',
						'bar' => 'r',
						'montant' => 12345
					)
				)
			);
			$this->assertEqual($result,$expected);
		}

		/**
		*
		*/
		function testDelete() {
			// test avec un item inexistant et doit donc retourner une erreur
			$this->Items->Default->delete(666);
			$this->assertEqual("invalidParameter",$this->Items->error);
			$result=Set::classicExtract($this->Items->Session->read(),"Message");
			$expected=array(
				'flash' => array(
					'message' => 'Erreur lors de la suppression',
					'layout' => 'flash/error',
					'params' => array()
				)
			);
			$this->assertEqual($expected,$result);

			//-------------------------------------------------------------------

			// test en supprimant l'item 3
			$this->Items->Default->delete(3);
			$result=Set::classicExtract($this->Items->Session->read(),"Message");
			$expected=array(
				'flash' => array(
					'message' => 'Suppression effectuée',
					'layout' => 'flash/success',
					'params' => array()
				)
			);
			$this->assertEqual($expected,$result);
			$expected = array(
				"0" => array(
					"Item" => array(
						"id" => 1,
						"firstname" => "Firstname n°1",
						"lastname" => "Lastname n°1",
						"name_a" => "name_a",
						"name_b" => "name_b",
						"version_a" => 1,
						"version_n" => 1,
						"description_a" => "description_a",
						"description_b" => "description_b",
						"modifiable_a" => 1,
						"modifiable_b" => 1,
						"date_a" => "2010-03-17",
						"date_b" => "2010-03-17",
						"tel" => "0101010101",
						"fax" => "0101010101",
						"category_id" => 12,
						"foo" => "f",
						"bar" => "",
						"montant" => 666.66 
					)
				),
				"1" => array(
					"Item" => array(
						'id' => 2,
						'firstname' => 'Firstname n°2',
						'lastname' => 'Lastname n°2',
						'name_a' => 'name_c',
						'name_b' => 'name_d',
						'version_a' => 2,
						'version_n' => 2,
						'description_a' => 'description_c',
						'description_b' => 'description_d',
						'modifiable_a' => 1,
						'modifiable_b' => '',
						'date_a' => '2010-03-23',
						'date_b' => '2010-03-23',
						'tel' => '0202020202',
						'fax' => '0202020202',
						'category_id' => 45,
						'foo' => 'o',
						'bar' => '',
						'montant' => 123,
					)
				)
			);
			$result=$this->Items->Item->find('all',array('recursive'=>-1));
			$this->assertEqual($expected,$result);
		}

		/**
		*
		*/
		function testBeforeRender() {
			// test avec l'action delete
			$this->Items->action='delete';
			$this->Items->Default->beforeRender($this->Items);
			$this->assertEqual('Items::delete',$this->Items->pageTitle);

			//-------------------------------------------------------------------

			// test avec aucune action envoyée
			$this->Items->action='';
			$this->Items->Default->beforeRender($this->Items);
			$this->assertEqual('Items::',$this->Items->pageTitle);

			//-------------------------------------------------------------------

			// test avec l'action edit
			$this->Items->action='edit';
			$this->Items->Default->beforeRender($this->Items);
			$this->assertEqual('Items::edit',$this->Items->pageTitle);

			//-------------------------------------------------------------------

			// test avec l'action search
			$this->Items->action='search';
			$this->Items->Default->beforeRender($this->Items);
			$this->assertEqual('Items::search',$this->Items->pageTitle);
		}

		/**
		*
		*/
		function testSearch() {
			/*$this->Items->data=array(
				"Item" => array(
					"id" => 1,
					"firstname" => "Firstname n°1",
					"lastname" => "Lastname n°1",
					"name_a" => "name_a",
					"name_b" => "name_b",
					"version_a" => 1,
					"version_n" => 1,
					"description_a" => "description_a",
					"description_b" => "description_b",
					"modifiable_a" => 1,
					"modifiable_b" => 1,
					"date_a" => "2010-03-17",
					"date_b" => "2010-03-17",
					"tel" => "0101010101",
					"fax" => "0101010101",
					"category_id" => 12,
					"foo" => "f",
					"bar" => "",
					"montant" => 666.66 
				)
			);
			$items = $this->Items->Default->search( array( 'Item.firstname' => 'LIKE', 'Item.lastname' => 'LIKE' ), $this->data );
			debug($items);*/
		}
	}
?>
