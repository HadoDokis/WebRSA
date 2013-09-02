<?php
	/**
	 * Short description for file.
	 *
	 * PHP version 5.3
	 *
	 * @package Postgres
	 * @subpackage Test.Fixture
	 */
	require_once( dirname( __FILE__ ).DS.'postgres_autovalidate_fixture.php' );

	/**
	 * Short description for class.
	 *
	 * @package Postgres
	 * @subpackage Test.Fixture
	 */
	class PostgresSiteFixture extends PostgresAutovalidateFixture
	{

		/**
		 * name property
		 *
		 * @var string 'PostgresSite'
		 * @access public
		 */
		public $name = 'PostgresSite';

		/**
		 * fields property
		 *
		 * @var array
		 * @access public
		 */
		public $fields = array(
			'id' => array( 'type' => 'integer', 'key' => 'primary' ),
			'name' => array( 'type' => 'string', 'length' => 255, 'null' => false ),
			'status' => array( 'type' => 'string', 'length' => 10, 'null' => true ),
			'price' => array( 'type' => 'float', 'null' => true ),
			/*'user_id' => array( 'type' => 'integer', 'null' => false ),
			'price' => array( 'type' => 'float', 'null' => true ),
			'published' => array( 'type' => 'boolean', 'null' => false ),
			'document' => array( 'type' => 'binary', 'null' => true ),
			'description' => 'text',
			'birthday' => 'date',
			'birthtime' => 'time',
			'created' => 'datetime',
			'updated' => 'datetime',*/
			'indexes' => array(
				'sites_name_idx' => array(
					'column' => array( 'name' ),
					'unique' => 1
				)
			)
		);

		/**
		 * Liste des noms de contraintes CHECK pour la table, avec la mise en
		 * contrainte en base de données.
		 *
		 * Les fonctions retournent un boolean:
		 *	- cakephp_validate_in_list (text, text[])
		 *	- cakephp_validate_in_list (integer, integer[])
		 *	- cakephp_validate_inclusive_range (double precision, double precision, double precision)
		 *	- cakephp_validate_range (double precision, double precision, double precision)
		 *
		 * @var array
		 */
		public $constraints = array(
			'sites_status_in_list_chk' => "( cakephp_validate_in_list( status, ARRAY['spam', 'ham'] ) )",
			'sites_price_inclusive_range_chk' => "( cakephp_validate_inclusive_range( price, 0, 999 ) )",
			'sites_price_range_chk' => "( cakephp_validate_range( price, -1, 1000 ) )",
		);
	}
?>