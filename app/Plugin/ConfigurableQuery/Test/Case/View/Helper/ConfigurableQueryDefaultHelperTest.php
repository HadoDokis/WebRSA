<?php
	/**
	 * Code source de la classe ConfigurableQueryDefaultHelperTest.
	 *
	 * PHP 5.3
	 *
	 * @package app.Test.Case.View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	require_once( dirname( __FILE__ ).DS.'..'.DS.'..'.DS.'bootstrap.php' );
	App::uses( 'Controller', 'Controller' );
	App::uses( 'PaginatorComponent', 'Controller/Component' );
	App::uses( 'View', 'View' );
	App::uses( 'AppHelper', 'View/Helper' );
	App::uses( 'ConfigurableQueryDefaultHelper', 'ConfigurableQuery.View/Helper' );
	App::uses( 'ConfigurableQueryCsvHelper', 'ConfigurableQuery.View/Helper' );
	App::uses( 'CsvHelper', 'View/Helper' );
	App::uses( 'ConfigurableQueryAbstractTestCase', 'ConfigurableQuery.Test/Case' );

	class CsvTestHelper extends CsvHelper
	{
		/**
		 * Surcharge de la méthode CsvHelper::renderHeaders() afin de ne pas
		 * envoyer le fichier en attachement.
		 *
		 * @param string $filename
		 */
		public function renderHeaders($filename = null) {
			if (is_string($filename)) {
				$this->setFilename($filename);
			}

			if ($this->filename === null) {
				$this->filename = 'Data.csv';
			}
		}
	}

	/**
	 * La classe ConfigurableQueryDefaultHelperTest ...
	 *
	 * @package app.Test.Case.View.Helper
	 */
	class ConfigurableQueryDefaultHelperTest extends ConfigurableQueryAbstractTestCase
	{
		/**
		 * Fixtures utilisées pour les tests.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'plugin.ConfigurableQuery.ConfigurableQueryGroup',
			'plugin.ConfigurableQuery.ConfigurableQueryUser'
		);

		/**
		 * Le contrôleur utilisé pour les tests.
		 *
		 * @var Controller
		 */
		public $Controller = null;

		/**
		 * Le contrôleur utilisé pour les tests.
		 *
		 * @var View
		 */
		public $View = null;

		/**
		 * Le helper à tester.
		 *
		 * @var ConfigurableQueryDefault
		 */
		public $Default = null;

		/**
		 * Le modèle User.
		 *
		 * @var AppModel
		 */
		public $User = null;

		/**
		 * Préparation du test.
		 */
		public function setUp() {
			parent::setUp();

			$requestParams = array(
				'plugin' => null,
				'controller' => 'users',
				'action' => 'index'
			);

			Router::reload();
			$request = new CakeRequest();

			// TODO: en paramètre
			$requestParams += array(
				'paging' => array(
					'User' => array(
						'page' => 1,
						'current' => 1,
						'count' => 1,
						'prevPage' => false,
						'nextPage' => false,
						'pageCount' => 1,
						'order' => null,
						'limit' => 20,
						'options' => array(
							'page' => 1,
							'conditions' => array( )
						),
						'paramType' => 'named'
					)
				),
				'controller' => 'users',
				'action' => 'index',
			);

			$request->addParams( $requestParams );

			Router::setRequestInfo( $request );

			App::build( array( 'locales' => CakePlugin::path( 'ConfigurableQuery' ).'Test'.DS.'Locale'.DS ) );
			Configure::write( 'Config.language', 'fre' );
			$_SESSION['Config']['language'] = 'fre';

			$this->Controller = new Controller( $request );
			$this->Controller->User = ClassRegistry::init( array( 'class' => 'ConfigurableQuery.ConfigurableQueryUser', 'alias' => 'User' ) );
			$this->Controller->User->Group = ClassRegistry::init( array( 'class' => 'ConfigurableQuery.ConfigurableQueryGroup', 'alias' => 'Group' ) );

			$this->View = new View( $this->Controller );
			$this->Default = new ConfigurableQueryDefaultHelper( $this->View );
			$this->Default->DefaultCsv = new ConfigurableQueryCsvHelper( $this->View );
			$this->Default->DefaultCsv->Csv = new CsvTestHelper( $this->View );
		}

		/**
		 * Nettoyage postérieur au test.
		 */
		public function tearDown() {
			parent::tearDown();
			unset( $this->Controller, $this->View, $this->Default );
		}

		/**
		 * Test de la méthode ConfigurableQueryDefaultHelper::configuredParams()
		 */
		public function testConfiguredParams() {
			$result = $this->Default->configuredParams();
			$expected = array(
				'key' => 'Users.index',
			);
			$this->assertEquals( $result, $expected );
		}

		/**
		 * Test de la méthode ConfigurableQueryDefaultHelper::configuredFields()
		 */
		public function testConfiguredFields() {
			Configure::write(
				'Users.index.fields',
				array(
					'User.username',
					'User.created',
					'Group.name'
				)
			);
			$result = $this->Default->configuredFields( array( 'key' => 'Users.index.fields' ) );
			$expected = array(
				'User.username' => array(
					'type' => 'string',
					'label' => 'Identifiant'
				),
				'User.created' => array(
					'type' => 'datetime',
					'label' => 'Créé le',
				),
				'Group.name' => array(
					'type' => 'string',
					'label' => 'Groupe'
				)
			);
			$this->assertEquals( $result, $expected );
		}

		/**
		 * Test de la méthode ConfigurableQueryDefaultHelper::configuredIndex()
		 */
		public function testConfiguredIndex() {
			Configure::write(
				'Users.index',
				array(
					'fields' => array(
						'User.username',
						'User.created',
						'Group.name'
					),
					'header' => array(
						array( 'Utilisateur' => array( 'colspan' => 2 ) ),
						array( 'Groupe' => null )
					)
				)
			);
			// TODO: on devrait pouvoir s'en passer
			$this->Controller->User->Behaviors->attach( 'DatabaseTable' );
			$query = array(
				'fields' => array(
					'User.username',
					'User.created',
					'Group.name'
				),
				'recursive' => -1,
				'joins' => array(
					$this->Controller->User->join( 'Group', array( 'type' => 'INNER' ) )
				)
			);
			$records = $this->Controller->User->find( 'all', $query );
			$result = $this->Default->configuredIndex( $records );

			$base = Router::url( '/' );
			$expected = '<div class="pagination">
					<p class="counter">Résultats 1 - 1 sur au moins 1 résultats.</p>
				</div>
				<table id="TableUsersIndex" class="users index">
					<thead>
						<tr>
							<th colspan="2">Utilisateur</th>
							<th>Groupe</th>
						</tr>
						<tr>
							<th id="TableUsersIndexColumnUserUsername">
								<a href="'.$base.'users/index/page:1/sort:User.username/direction:asc">Identifiant</a>
							</th>
							<th id="TableUsersIndexColumnUserCreated">
								<a href="'.$base.'users/index/page:1/sort:User.created/direction:asc">Créé le</a>
							</th>
							<th id="TableUsersIndexColumnGroupName">
								<a href="'.$base.'users/index/page:1/sort:Group.name/direction:asc">Groupe</a>
							</th>
						</tr>
					</thead>
					<tbody>
						<tr class="odd">
							<td class="data string ">admin</td>
							<td class="data datetime ">29/06/2015 à 00:28:35</td>
							<td class="data string ">Admin</td>
						</tr>
					</tbody>
				</table>
				<div class="pagination"><p class="counter">Résultats 1 - 1 sur au moins 1 résultats.</p>
			</div>';
			$this->assertEqualsXhtml( $result, $expected );
		}

		/**
		 * Test de la méthode ConfigurableQueryDefaultHelper::configuredIndex()
		 * avec une innerTable.
		 */
		public function testConfiguredIndexInnerTable() {
			Configure::write(
				'Users.index',
				array(
					'fields' => array(
						'User.username',
						'User.created',
						'Group.name'
					),
					'innerTable' => array(
						'User.id',
						'Group.id'
					),
					'header' => array(
						array( 'Utilisateur' => array( 'colspan' => 2 ) ),
						array( 'Groupe' => null ),
						array( ' ' => array( 'style' => 'display: none' ) ),
					)
				)
			);
			// TODO: on devrait pouvoir s'en passer
			$this->Controller->User->Behaviors->attach( 'DatabaseTable' );
			$query = array(
				'fields' => array(
					'User.id',
					'User.username',
					'User.created',
					'Group.id',
					'Group.name'
				),
				'recursive' => -1,
				'joins' => array(
					$this->Controller->User->join( 'Group', array( 'type' => 'INNER' ) )
				)
			);
			$records = $this->Controller->User->find( 'all', $query );
			$result = $this->Default->configuredIndex( $records );

			$base = Router::url( '/' );
			$expected = '<div class="pagination">
				<p class="counter">Résultats 1 - 1 sur au moins 1 résultats.</p>
			</div>
			<table id="TableUsersIndex" class="users index tooltips">
				<thead>
					<tr>
						<th colspan="2">Utilisateur</th>
						<th>Groupe</th>
						<th style="display: none"> </th>
						<th class="innerTableHeader noprint">Informations complémentaires</th>
					</tr>
					<tr>
						<th id="TableUsersIndexColumnUserUsername">
							<a href="'.$base.'users/index/page:1/sort:User.username/direction:asc">Identifiant</a>
						</th>
						<th id="TableUsersIndexColumnUserCreated">
							<a href="'.$base.'users/index/page:1/sort:User.created/direction:asc">Créé le</a>
						</th>
						<th id="TableUsersIndexColumnGroupName">
							<a href="'.$base.'users/index/page:1/sort:Group.name/direction:asc">Groupe</a>
						</th>
						<th class="innerTableHeader noprint">Informations complémentaires</th>
					</tr>
				</thead>
				<tbody>
					<tr class="odd">
						<td class="data string ">admin</td>
						<td class="data datetime ">29/06/2015 à 00:28:35</td>
						<td class="data string ">Admin</td>
						<td class="innerTableCell noprint">
							<table id="innerTableTableUsersIndex0" class="users index innerTable">
								<tbody>
									<tr class="odd">
										<th>Id de l\'utilisateur</th>
										<td class="data integer positive">1</td>
									</tr>
									<tr class="even">
										<th>Id du groupe</th>
										<td class="data integer positive">1</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
			<div class="pagination">
				<p class="counter">Résultats 1 - 1 sur au moins 1 résultats.</p>
			</div>';
			$this->assertEqualsXhtml( $result, $expected );
		}

		/**
		 * Test de la méthode ConfigurableQueryDefaultHelper::configuredCsv()
		 */
		public function testConfiguredCsv() {
			$query = array(
				'fields' => array(
					'User.id',
					'User.username',
					'User.created',
					'User.modified'
				)
			);
			$records = $this->Controller->User->find( 'all', $query );

			Configure::write(
				'Users.index',
				array(
					'User.id',
					'User.username',
					'User.created',
					'User.modified' => array(
						'format' => '%A %e %B %Y %H:%M'
					),
				)
			);
			$result = $this->Default->configuredCsv( $records );

			$expected = '"Id de l\'utilisateur",Identifiant,"Créé le",User.modified
1,admin,"29/06/2015 à 00:28:35","lundi 29 juin 2015 00:28"
';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}
	}
?>