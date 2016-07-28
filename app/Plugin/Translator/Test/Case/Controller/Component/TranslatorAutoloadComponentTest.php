<?php
	/**
	 * TranslatorAutoloadComponentTest file
	 *
	 * PHP 5.3
	 *
	 * @package Translator
	 * @subpackage Test.Case.Utility.Translator
	 */

	App::uses('TranslatorAutoloadComponent', 'Translator.Controller/Component');
	App::uses('Controller', 'Controller');
	App::uses('Translator', 'Translator.Utility');
	
	/**
	 * InsertionsAllocatairesTestController class
	 *
	 * @package app.Test.Case.Controller.Component
	 */
	class Domain1Controller extends Controller
	{
		public $components = array(
			'Translator.TranslatorAutoload'
		);
		
		public function index() {}
	}

	/**
	 * TranslatorAutoloadComponentTest class
	 *
	 * @package Translator
	 * @subpackage Test.Case.Utility.Translator
	 */
	class TranslatorAutoloadComponentTest extends ControllerTestCase
	{
		/**
		 * setUp method
		 *
		 * @return void
		 */
		public function setUp() {
			parent::setUp();
			call_user_func(array(Translator::getInstance(), 'reset'));
			App::build(array('locales' => CakePlugin::path('Translator').'Test'.DS.'Locale'.DS));
			$request = new CakeRequest('domain1/index', false);
			$request->addParams(array('controller' => 'domain1', 'action' => 'index'));
			$this->Controller = new Domain1Controller($request);
			$this->Controller->Components->init($this->Controller);
			Cache::delete($this->Controller->TranslatorAutoload->cacheKey());
			$this->Controller->TranslatorAutoload->initialize($this->Controller);
			$this->testAction('/domain1/index', array('method' => 'GET'));
		}
		
		/**
		 * Test de la méthode TranslatorAutoloadComponent::domains();
		 */
		public function testDomains() {
			$results = $this->Controller->TranslatorAutoload->domains();
			$expected = array(
				(int) 0 => 'domain1_index',
				(int) 1 => 'domain1',
				(int) 2 => 'default'
			);
			$this->assertEqual($results, $expected, "Retourne la liste de domaines");
			
			$results = Translator::domains();
			$expected = $this->Controller->TranslatorAutoload->domains();
			$this->assertEqual($results, $expected, "Domaines de l'utilitaire identique à ceux du component");
		}
		
		/**
		 * Test de la méthode TranslatorAutoloadComponent::save();
		 */
		public function testSave() {
			Configure::write('Cache.disable', false);
			Translator::translate('test1'); // effectue une traduction pour contrôle de la sauvegarde
			
			$this->Controller->TranslatorAutoload->save(); // force la sauvegarde
			
			$beforeReset = Translator::export();
			Translator::reset();
			$afterReset = Translator::export();
			
			$this->Controller->TranslatorAutoload->load();
			$afterLoad = Translator::export();
			
			$expected = array(
				'fre' => array(
					'["domain1_index","domain1","default"]' => array(
						'{"plural":null,"category":6,"count":null,"language":null}' => array(
							'test1' => 'traduction domain1/test1'
						)
					)
				)
			);
			$this->assertEqual($beforeReset, $expected, "Avant reset");
			$this->assertEqual($afterReset, array(), "Après reset");
			$this->assertEqual($afterLoad, $expected, "Après load");
		}
		
		/**
		 * Test of the TranslatorAutoloadComponent::load().
		 *
		 * @covers TranslatorAutoloadComponent::load
		 * @covers TranslatorAutoloadComponent::_translator
		 */
		public function testLoad()
		{
			$translatorClass = $this->Controller->TranslatorAutoload->settings['translatorClass'];
			$Instance = $translatorClass::getInstance();
			$this->Controller->TranslatorAutoload->load();
			$this->assertEquals(array(), $Instance->export());
		}
		
		/**
		 * @expectedException        RuntimeException
		 * @expectedExceptionMessage Missing utility class falseClassName
		 * @covers TranslatorAutoloadComponent::_translator
		 */
		public function testLoadMissingUtilityClassException() {
			$Collection = new ComponentCollection($this->Controller);
			$this->Controller->TranslatorAutoload = new TranslatorAutoloadComponent($Collection);
			$this->Controller->TranslatorAutoload->settings['translatorClass'] = 'falseClassName';
			$this->Controller->TranslatorAutoload->load();
		}
		
		/**
		 * @expectedException        RuntimeException
		 * @expectedExceptionMessage Utility class TranslatorHash does not implement TranslatorInterface
		 * @covers TranslatorAutoloadComponent::_translator
		 */
		public function testLoadNotImplementsUtilityClassException() {
			$Collection = new ComponentCollection($this->Controller);
			$this->Controller->TranslatorAutoload = new TranslatorAutoloadComponent($Collection);
			$this->Controller->TranslatorAutoload->settings['translatorClass'] = 'TranslatorHash';
			$this->Controller->TranslatorAutoload->load();
		}
		
		/**
		 * @expectedException        RuntimeException
		 * @expectedExceptionMessage Method "fakeMethod" cannot be called. Use one of "load", "save"
		 * @covers TranslatorAutoloadComponent::dispatchEvent
		 */
		public function testDispatchEventException() {
			$this->Controller->TranslatorAutoload->settings['events']['fakeEvent'] = 'fakeMethod';
			$this->Controller->TranslatorAutoload->dispatchEvent('fakeEvent');
		}
		
		/**
		 * Passe dans toutes les methodes de callback
		 */
		public function testDispatchEvents() {
			foreach (array_keys($this->Controller->TranslatorAutoload->settings['events']) as $method) {
				$this->Controller->TranslatorAutoload->$method($this->Controller, null);
			}
		}
	}