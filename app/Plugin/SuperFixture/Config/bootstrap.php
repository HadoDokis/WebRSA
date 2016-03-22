<?php
	/**
	 * Chemin vers les super fixtures
	 * 
	 * @package SuperFixture
	 * @subpackage Config
	 */
	App::build(
		array(
			'Fixture' => array(TESTS.'Fixture'.DS),
			'SuperFixture' => array(TESTS.'SuperFixture'.DS),
		), App::REGISTER
	);