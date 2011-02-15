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
		*
		*/

		protected function _initModelCleanSubquery( $modelName ) {
			if( isset( $this->modelClass ) && isset( $this->{$this->modelClass} ) ) {
				unset( $this->{$this->modelClass} );
			}

			$this->modelClass = $modelName;
			$this->{$this->modelClass} =& ClassRegistry::init( $this->modelClass );

			// Detach all behaviors
			$behaviors = array_values( $this->{$this->modelClass}->Behaviors->attached() );
			foreach( $behaviors as $behavior ) {
				$this->{$this->modelClass}->Behaviors->detach( $behavior );
			}

			// Attach only the one we're testing
			$behaviorName = preg_replace( '/BehaviorTest$/', '', get_class( $this ) );
			$this->{$this->modelClass}->Behaviors->attach( $behaviorName, array() );

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
		* Run before any test function
		*/

		public function startTest() {
			$this->_initModelCleanSubquery( 'User' );
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
			$p = $this->dbo->config['prefix'];

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
							FROM {$q}{$p}users{$q} AS {$q}User{$q}
							WHERE {$q}User{$q}.{$q}id{$q} IN ({$iq}1{$iq}, {$iq}2{$iq})";

			$this->_assertSql( $result, $expected );
		}


		/**
		*
		*/

		public function testSimpleSqWithAlias() {
			$q = $this->dbo->startQuote;
			$iq = $this->dbo->intQuote;
			$p = $this->dbo->config['prefix'];

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
							FROM {$q}{$p}users{$q} AS {$q}u{$q}
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
			$p = $this->dbo->config['prefix'];

			$result = $this->{$this->modelClass}->sq(
				array(
					'conditions' => array(
						'User.id' => array( 1, 2 )
					)
				)
			);

			$expected = "SELECT
							FROM {$q}{$p}users{$q} AS {$q}User{$q}
							WHERE {$q}User{$q}.{$q}id{$q} IN ({$iq}1{$iq}, {$iq}2{$iq})";

			$this->_assertSql( $result, $expected );
		}

		/**
		*
		*/

		public function testJoinedSq() {
			$q = $this->dbo->startQuote;
			$iq = $this->dbo->intQuote;
			$p = $this->dbo->config['prefix'];

			$this->{$this->modelClass}->UserZonegeographique->Behaviors->attach( 'Subquery' );
			$result = $this->{$this->modelClass}->UserZonegeographique->sq(
				array(
					'fields' => 'UserZonegeographique.user_id',
					'joins' => array(
						array(
							'alias' => 'Zonegeographique',
							'table' => "{$p}zonesgeographiques",
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
							FROM {$q}{$p}users_zonesgeographiques{$q} AS {$q}UserZonegeographique{$q}
								LEFT JOIN {$p}zonesgeographiques AS {$q}Zonegeographique{$q} ON (
									{$q}UserZonegeographique{$q}.{$q}zonegeographque_id{$q} = {$q}Zonegeographique{$q}.{$q}id{$q}
								)
							WHERE {$q}Zonegeographique{$q}.{$q}libelle{$q} LIKE '%C%'";

			$this->_assertSql( $result, $expected );

		}

		/**
		*
		*/

		public function testJoinedSq2() {
			$q = $this->dbo->startQuote;
			$iq = $this->dbo->intQuote;
			$p = $this->dbo->config['prefix'];

			$result = $this->{$this->modelClass}->sq(
				array(
					'fields' => 'UserZonegeographique.zonegeographique_id',
					'joins' => array(
						array(
							'alias' => 'UserZonegeographique',
							'table' => "{$p}users_zonesgeographiques",
							'type' => 'INNER',
							'foreignKey' => '',
							'conditions' => array(
								'User.id = UserZonegeographique.user_id',
							),
						),
						array(
							'alias' => 'Zonegeographique',
							'table' => "{$p}zonesgeographiques",
							'type' => 'LEFT',
							'foreignKey' => '',
							'conditions' => array(
								'Zonegeographique.id = UserZonegeographique.zonegeographique_id',
							),
						),
						array(
							'alias' => 'Serviceinstructeur',
							'table' => "{$p}servicesinstructeurs",
							'type' => 'LEFT',
							'foreignKey' => '',
							'conditions' => array(
								'User.serviceinstructeur_id = Serviceinstructeur.id',
							),
						),
						array(
							'alias' => 'Group',
							'table' => "{$p}groups",
							'type' => 'LEFT',
							'foreignKey' => '',
							'conditions' => array(
								'User.group_id = Group.id',
							),
						),
					),
					'conditions' => array(
						'Zonegeographique.name LIKE' => '%C%',
						'Comment.id' => '1',
					),
					'recursive' => '-1',
				)
			);

			$expected = "SELECT
								{$q}UserZonegeographique{$q}.{$q}zonegeographique_id{$q}
							FROM {$q}{$p}users{$q} AS {$q}User{$q}
								INNER JOIN {$p}users_zonesgeographiques AS {$q}UserZonegeographique{$q} ON (
									{$q}User{$q}.{$q}id{$q} = {$q}UserZonegeographique{$q}.{$q}user_id{$q}
								)
								LEFT JOIN {$p}zonesgeographiques AS {$q}Zonegeographique{$q} ON (
									{$q}Zonegeographique{$q}.{$q}id{$q} = {$q}UserZonegeographique{$q}.{$q}zonegeographique_id{$q}
								)
								LEFT JOIN {$p}servicesinstructeurs AS {$q}Serviceinstructeur{$q} ON (
									{$q}User{$q}.{$q}serviceinstructeur_id{$q} = {$q}Serviceinstructeur{$q}.{$q}id{$q}
								)
								LEFT JOIN {$p}groups AS {$q}Group{$q} ON (
									{$q}User{$q}.{$q}group_id{$q} = {$q}Group{$q}.{$q}id{$q}
								)
								WHERE {$q}Zonegeographique{$q}.{$q}name{$q} LIKE '%C%'
									AND {$q}Comment{$q}.{$q}id{$q} = '1'";


			$this->_assertSql( $result, $expected );
		}

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

		/**
		* cf. la fonction search du modèle Relancenonrespectsanctionep93
		*/

		public function testSqContratinsertion() {
			$this->_initModelCleanSubquery( 'Contratinsertion' );

			$q = $this->dbo->startQuote;
			$iq = $this->dbo->intQuote;
			$p = $this->dbo->config['prefix'];

			$queryData = array(
				'fields' => array( 'contratsinsertion.personne_id' ),
				'alias' => 'contratsinsertion',
				'conditions' => array(
					'contratsinsertion.personne_id = Orientstruct.personne_id',
					'DATE_TRUNC( \'day\', contratsinsertion.datevalidation_ci ) >= Orientstruct.date_valid'
				)
			);

			$result = $this->{$this->modelClass}->sq( $queryData );

			$expected = "SELECT
								{$q}contratsinsertion{$q}.{$q}personne_id{$q}
							FROM {$q}contratsinsertion{$q} AS {$q}{$p}contratsinsertion{$q}
							WHERE
								{$q}contratsinsertion{$q}.{$q}personne_id{$q} = {$q}Orientstruct{$q}.{$q}personne_id{$q}
								AND DATE_TRUNC( 'day', {$q}contratsinsertion{$q}.{$q}datevalidation_ci{$q} ) >= {$q}Orientstruct{$q}.{$q}date_valid{$q}";

			$this->_assertSql( $result, $expected );
		}

		/**
		* cf. la fonction search du modèle Relancenonrespectsanctionep93
		*/

		public function testSqNonrespectsanctionep93() {
			$this->_initModelCleanSubquery( 'Nonrespectsanctionep93' );

			$q = $this->dbo->startQuote;
			$iq = $this->dbo->intQuote;
			$p = $this->dbo->config['prefix'];

			$queryData = array(
				'fields' => array( 'nonrespectssanctionseps93.personne_id' ),
				'alias' => 'nonrespectssanctionseps93',
				'conditions' => array(
					'nonrespectssanctionseps93.active' => 1,
					'nonrespectssanctionseps93.dossierep_id IS NULL',
					'nonrespectssanctionseps93.orientstruct_id = Orientstruct.id'
				)
			);

			$result = $this->{$this->modelClass}->sq( $queryData );

			$expected = "SELECT
								{$q}nonrespectssanctionseps93{$q}.{$q}personne_id{$q}
							FROM {$q}{$p}nonrespectssanctionseps93{$q} AS {$q}nonrespectssanctionseps93{$q}
							WHERE
								{$q}nonrespectssanctionseps93{$q}.{$q}active{$q} = '1'
								AND {$q}nonrespectssanctionseps93{$q}.{$q}dossierep_id{$q} IS NULL
								AND {$q}nonrespectssanctionseps93{$q}.{$q}orientstruct_id{$q} = {$q}Orientstruct{$q}.{$q}id{$q}";

			$this->_assertSql( $result, $expected );
		}

/*
	SELECT nonrespectssanctionseps93.orientstruct_id
		FROM nonrespectssanctionseps93
		WHERE
			nonrespectssanctionseps93.active = \'1\'
			AND nonrespectssanctionseps93.dossierep_id IS NULL
			AND nonrespectssanctionseps93.orientstruct_id = Orientstruct.id
			AND (
				SELECT
						relancesnonrespectssanctionseps93.numrelance
						FROM relancesnonrespectssanctionseps93
						WHERE
							relancesnonrespectssanctionseps93.nonrespectsanctionep93_id = nonrespectssanctionseps93.id
							AND ( DATE( NOW() ) - relancesnonrespectssanctionseps93.daterelance ) >= '.Configure::read( "Nonrespectsanctionep93.relanceOrientstructCer{$search['Relance.numrelance']}" ).'
						ORDER BY relancesnonrespectssanctionseps93.numrelance DESC
						LIMIT 1
			) = '.( $search['Relance.numrelance'] - 1 ).'
*/
		/**
		* cf. la fonction search du modèle Relancenonrespectsanctionep93
		*/

		public function testSqNonrespectsanctionep93Relancenonrespectsanctionep93() {
			$this->_initModelCleanSubquery( 'Nonrespectsanctionep93' );
			$this->{$this->modelClass}->Relancenonrespectsanctionep93->Behaviors->attach( 'Subquery' );

			$q = $this->dbo->startQuote;
			$iq = $this->dbo->intQuote;
			$p = $this->dbo->config['prefix'];

			$queryData = array(
				'fields' => array( 'nonrespectssanctionseps93.orientstruct_id' ),
				'alias' => 'nonrespectssanctionseps93',
				'conditions' => array(
					'nonrespectssanctionseps93.active' => 1,
					'nonrespectssanctionseps93.dossierep_id IS NULL',
					'nonrespectssanctionseps93.orientstruct_id = Orientstruct.id',
					'('.$this->{$this->modelClass}->Relancenonrespectsanctionep93->sq(
						array(
							'fields' => array( 'relancesnonrespectssanctionseps93.numrelance' ),
							'alias' => 'relancesnonrespectssanctionseps93',
							'conditions' => array(
								'relancesnonrespectssanctionseps93.nonrespectsanctionep93_id = nonrespectssanctionseps93.id',
								'( DATE( NOW() ) - relancesnonrespectssanctionseps93.daterelance ) >=' => 60,
							),
							'order' => array( 'relancesnonrespectssanctionseps93.numrelance DESC' ),
							'limit' => 1
						)
					).')' => 1
				)
			);

			$result = $this->{$this->modelClass}->sq( $queryData );

			$expected = "SELECT
								{$q}nonrespectssanctionseps93{$q}.{$q}orientstruct_id{$q}
							FROM {$q}{$p}nonrespectssanctionseps93{$q} AS {$q}nonrespectssanctionseps93{$q}
							WHERE
								{$q}nonrespectssanctionseps93{$q}.{$q}active{$q} = '1'
								AND {$q}nonrespectssanctionseps93{$q}.{$q}dossierep_id{$q} IS NULL
								AND {$q}nonrespectssanctionseps93{$q}.{$q}orientstruct_id{$q} = {$q}Orientstruct{$q}.{$q}id{$q}
								AND (
									SELECT
											{$q}relancesnonrespectssanctionseps93{$q}.{$q}numrelance{$q}
										FROM {$p}relancesnonrespectssanctionseps93 AS relancesnonrespectssanctionseps93
										WHERE
											{$q}relancesnonrespectssanctionseps93{$q}.{$q}nonrespectsanctionep93_id{$q} = {$q}nonrespectssanctionseps93{$q}.{$q}id{$q}
											AND ( DATE( NOW() ) - {$q}relancesnonrespectssanctionseps93{$q}.{$q}daterelance{$q} ) >= '60'
										ORDER BY {$q}relancesnonrespectssanctionseps93{$q}.{$q}numrelance{$q} DESC
										LIMIT 1
								) = '1'";

			$this->_assertSql( $result, $expected );
		}
	}
?>