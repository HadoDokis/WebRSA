<?php
	require_once( TESTS.'cases/cake_app_behavior_test_case.php' );
	require_once( TESTS.'cases/cake_app_test_case.php' );

	define( 'TESTS_DATASOURCE', 'test_suite' );
	ClassRegistry::config( array( 'ds' => TESTS_DATASOURCE ) );
	App::import( 'Core', array( 'Model' ) );
//
 	require_once( APP.'app_model.php' );

	/**
	*
	*/

	class SimpleUserModel extends AppModel
	{
		public $name = 'User';

		public $alias = 'User';

		public $useDbConfig = TESTS_DATASOURCE;

// 		public $belongsTo = array(
// 			'Group' => array(
// 				'className' => 'Group',
// 				'foreignKey' => 'group_id',
// 				'conditions' => '',
// 				'fields' => '',
// 				'order' => ''
// 			),
// 		);
//
// 		public $hasAndBelongsToMany = array(
// 			'Zonegeographique' => array(
// 				'className' => 'Zonegeographique',
// 				'joinTable' => 'users_zonesgeographiques',
// 				'foreignKey' => 'user_id',
// 				'associationForeignKey' => 'zonegeographique_id',
// 				'unique' => true,
// 				'conditions' => '',
// 				'fields' => '',
// 				'order' => '',
// 				'limit' => '',
// 				'offset' => '',
// 				'finderQuery' => '',
// 				'deleteQuery' => '',
// 				'insertQuery' => '',
// 				'with' => 'UserZonegeographique'
// 			)
// 		);
	}

	/**
	* SubqueryBehaviorTest class
	*
	* @package       cake
	* @subpackage    cake.tests.cases.libs.model.behaviors
	*/

	class SubqueryBehaviorTest extends CakeAppTestCase
	{
		/**
		*
		*/

		public $dbo = null;

		/**
		* Remplace un ensemble de caractères blancs par un espace
		* dans une requête SQL.
		* FIXME: quand ce ne sont pas des valeurs
		* FIXME: mettre dans une classe parente
		*/

		protected function _formatSql( $sql ) {
			$sql = trim( $sql );
			$sql = preg_replace( '/\s\s+/', ' ', $sql );
			$sql = preg_replace( '/\(\s+/', '(', $sql );
			$sql = preg_replace( '/\s+\)/', ')', $sql );
			return $sql;
		}

		/**
		* S'assure que deux fragments SQL sont les mêmes, exception
		* faite des caractères blancs de formattage.
		* FIXME: mettre dans une classe parente
		*/

		protected function _assertSql( $sql1, $sql2 ) {
			$this->assertEqual(
				$this->_formatSql( $sql1 ),
				$this->_formatSql( $sql2 )
			);
		}

		/**
		* Run before any test function
		*/

		public function startTest() {
			$this->modelClass = 'User';
			$this->{$this->modelClass} =& ClassRegistry::init( $this->modelClass );

			// Detach all behaviors
			$behaviors = array_values( $this->{$this->modelClass}->Behaviors->attached() );
			foreach( $behaviors as $behavior ) {
				$this->{$this->modelClass}->Behaviors->detach( $behavior );
			}

			// Attach only the one we're testing
			$settings = array(
			);
			$this->{$this->modelClass}->validate = array();
			$behaviorName = preg_replace( '/BehaviorTest$/', '', get_class( $this ) );
			$this->{$this->modelClass}->Behaviors->attach( $behaviorName, $settings );

			if( !$this->dbo =& ConnectionManager::getDataSource( $this->{$this->modelClass}->useDbConfig ) ) {
				trigger_error(
					sprintf( __( "Impossible d'obtenir l'objet dbo du modèle %s", true ), $this->modelClass ),
					E_USER_WARNING
				);
			}

			// FIXME: seulement mysql et postgresql
			if( !in_array( $this->dbo->config['driver'], array( 'mysql', 'mysqli', 'postgres' ) ) ) {
				trigger_error(
					sprintf( __( "Le driver '%s' n'est pas supporté", true ), $this->modelClass ),
					E_USER_WARNING
				);
			}

			$this->dbo->intQuote = ( ( $this->dbo->config['driver'] == 'mysql' ) ? '' : "'" );
		}

		/**
		* Exécuté après chaque test.
		*/

		function tearDown() {
			ClassRegistry::flush();

			if( isset( $this->modelClass ) && isset( $this->{$this->modelClass} ) ) {
				unset( $this->{$this->modelClass} );
			}
		}

		/**
		*
		*/

		public function testSimpleSq() {
			$q = $this->dbo->startQuote;
			$iq = $this->dbo->intQuote;
			$prefix = $this->dbo->config['prefix'];

			$result = $this->{$this->modelClass}->sq(
				array(
					'fields' => 'User.group_id',
					'conditions' => array(
						'User.id' => array( 1, 2 )
					)
				)
			);

			$expected = "SELECT
							{$q}User{$q}.{$q}group_id{$q}
							FROM {$q}{$prefix}users{$q} AS {$q}User{$q}
							WHERE {$q}User{$q}.{$q}id{$q} IN ({$iq}1{$iq}, {$iq}2{$iq})";

			$this->_assertSql( $result, $expected );
		}


		/**
		*
		*/

		public function testSimpleSqWithAlias() {
			$q = $this->dbo->startQuote;
			$iq = $this->dbo->intQuote;
			$prefix = $this->dbo->config['prefix'];

			$result = $this->{$this->modelClass}->sq(
				array(
					'alias' => 'u',
					'fields' => 'u.id',
					'conditions' => array(
						'u.id' => array( 1, 2 ),
						'u.group_id = Group.id',
						'u.username IS NOT NULL',
					),
					'order' => array( 'u.id DESC' ),
					'limit' => 1
				)
			);

			$expected = "SELECT
							{$q}u{$q}.{$q}id{$q}
							FROM {$q}{$prefix}users{$q} AS {$q}u{$q}
							WHERE
								{$q}u{$q}.{$q}id{$q} IN ({$iq}1{$iq}, {$iq}2{$iq})
								AND {$q}u{$q}.{$q}group_id{$q} = {$q}Group{$q}.{$q}id{$q}
								AND {$q}u{$q}.{$q}username{$q} IS NOT NULL
							ORDER BY {$q}u{$q}.{$q}id{$q} DESC
							LIMIT 1";

			$this->_assertSql( $result, $expected );
		}

		/**
		*
		*/

		public function testSimpleSqEmptyFields() {
			$q = $this->dbo->startQuote;
			$iq = $this->dbo->intQuote;
			$prefix = $this->dbo->config['prefix'];

			$result = $this->{$this->modelClass}->sq(
				array(
					'conditions' => array(
						'User.id' => array( 1, 2 )
					)
				)
			);

			$expected = "SELECT
							FROM {$q}{$prefix}users{$q} AS {$q}User{$q}
							WHERE {$q}User{$q}.{$q}id{$q} IN ({$iq}1{$iq}, {$iq}2{$iq})";

			$this->_assertSql( $result, $expected );
		}

		/**
		*
		*/

		public function testJoinedSq() {
			$q = $this->dbo->startQuote;
			$iq = $this->dbo->intQuote;
			$prefix = $this->dbo->config['prefix'];

			$this->{$this->modelClass}->UserZonegeographique->Behaviors->attach( 'Subquery' );
			$result = $this->{$this->modelClass}->UserZonegeographique->sq(
				array(
					'fields' => 'UserZonegeographique.user_id',
					'joins' => array(
						array(
							'alias' => 'Zonegeographique',
							'table' => "{$prefix}zonesgeographiques",
							'type' => 'LEFT',
							'foreignKey' => '',
							'conditions' => array(
								'UserZonegeographique.zonegeographque_id = Zonegeographique.id',
							),
						),
					),
					'conditions' => array(
						'Zonegeographique.libelle LIKE' => '%C%'
					)
				)
			);

			$expected = "SELECT
								{$q}UserZonegeographique{$q}.{$q}user_id{$q}
							FROM {$q}{$prefix}users_zonesgeographiques{$q} AS {$q}UserZonegeographique{$q}
								LEFT JOIN {$prefix}zonesgeographiques AS {$q}Zonegeographique{$q} ON (
									{$q}UserZonegeographique{$q}.{$q}zonegeographque_id{$q} = {$q}Zonegeographique{$q}.{$q}id{$q}
								)
							WHERE {$q}Zonegeographique{$q}.{$q}libelle{$q} LIKE '%C%'";

			$this->_assertSql( $result, $expected );

		}

		/**
		*
		*/

		/*public function testJoinedSq2() {
			$q = $this->dbo->startQuote;
			$iq = $this->dbo->intQuote;
			$prefix = $this->dbo->config['prefix'];

			$result = $this->{$this->modelClass}->sq(
				array(
					'fields' => 'PostTag.post_id',
					'joins' => array(
						array(
							'alias' => 'PostTag',
							'table' => "{$prefix}posts_tags",
							'type' => 'INNER',
							'foreignKey' => '',
							'conditions' => array(
								'Post.id = PostTag.post_id',
							),
						),
						array(
							'alias' => 'Tag',
							'table' => "{$prefix}tags",
							'type' => 'LEFT',
							'foreignKey' => '',
							'conditions' => array(
								'Tag.id = PostTag.tag_id',
							),
						),
						array(
							'alias' => 'Comment',
							'table' => "{$prefix}comments",
							'type' => 'LEFT',
							'foreignKey' => '',
							'conditions' => array(
								'Post.id = Comment.post_id',
							),
						),
						array(
							'alias' => 'User',
							'table' => "{$prefix}users",
							'type' => 'LEFT',
							'foreignKey' => '',
							'conditions' => array(
								'Post.user_id = User.id',
							),
						),
					),
					'conditions' => array(
						'Tag.name LIKE' => '%C%',
						'Comment.id' => '1',
					),
					'recursive' => '-1',
				)
			);
debug( $result );
			$expected = "SELECT
					{$q}PostTag{$q}.{$q}post_id{$q}
					FROM {$q}{$prefix}posts{$q} AS {$q}Post{$q}
						INNER JOIN {$prefix}posts_tags AS {$q}PostTag{$q} ON (
							{$q}Post{$q}.{$q}id{$q} = {$q}PostTag{$q}.{$q}post_id{$q}
						)
						LEFT JOIN {$prefix}tags AS {$q}Tag{$q} ON (
							{$q}Tag{$q}.{$q}id{$q} = {$q}PostTag{$q}.{$q}tag_id{$q}
						)
						LEFT JOIN {$prefix}comments AS {$q}Comment{$q} ON (
							{$q}Post{$q}.{$q}id{$q} = {$q}Comment{$q}.{$q}post_id{$q}
						)
						LEFT JOIN {$prefix}users AS {$q}User{$q} ON (
							{$q}Post{$q}.{$q}user_id{$q} = {$q}User{$q}.{$q}id{$q}
						)
					WHERE {$q}Tag{$q}.{$q}name{$q} LIKE '%C%'
						AND {$q}Comment{$q}.{$q}id{$q} = {$iq}1{$iq}";

// 			$this->_assertSql( $result, $expected );
		}*/

		/**
		*
		*/

		public function testAlias() {
			$q = $this->dbo->startQuote;
			$iq = $this->dbo->intQuote;
			$p = $this->dbo->config['prefix'];

			$queryData = array(
				'fields' => array( 'Foo.group_id' ),
				'alias' => 'Foo',
				'conditions' => array(
					'Foo.group_id' => 1,
				),
				'recursive' => '-1',
				'contain' => false,
			);

			$result = $this->{$this->modelClass}->sq( $queryData );
			$expected = "SELECT
								{$q}Foo{$q}.{$q}group_id{$q}
							FROM {$q}{$p}users{$q} AS {$q}Foo{$q}
							WHERE {$q}Foo{$q}.{$q}group_id{$q} = {$iq}1{$iq}";

			$this->_assertSql( $result, $expected );

			//

			$q = $this->dbo->startQuote;
			$iq = $this->dbo->intQuote;
			$p = $this->dbo->config['prefix'];

			$queryData = array(
				'fields' => array( 'FooBar.zonegeographique_id' ),
				'alias' => 'Foo',
				'joins' => array(
					array(
						'alias' => 'FooBar',
						'table' => "{$p}users_zonesgeographiques",
						'type' => 'INNER',
						'foreignKey' => '',
						'conditions' => array( 'Foo.id = FooBar.user_id', ),
					),
				),
				'conditions' => array(
					'Foo.group_id' => 1,
				),
				'recursive' => '-1',
			);

			$result = $this->{$this->modelClass}->sq( $queryData );
			$expected = "SELECT
								{$q}FooBar{$q}.{$q}zonegeographique_id{$q}
							FROM {$q}{$p}users{$q} AS {$q}Foo{$q}
							INNER JOIN {$p}users_zonesgeographiques AS {$q}FooBar{$q} ON (
								{$q}Foo{$q}.{$q}id{$q} = {$q}FooBar{$q}.{$q}user_id{$q}
							)
							WHERE {$q}Foo{$q}.{$q}group_id{$q} = {$iq}1{$iq}";

			$this->_assertSql( $result, $expected );
		}
	}
?>