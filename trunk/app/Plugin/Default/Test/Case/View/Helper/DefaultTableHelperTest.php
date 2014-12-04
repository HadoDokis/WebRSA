<?php
	/**
	 * Code source de la classe DefaultTableHelperTest.
	 *
	 * PHP 5.3
	 *
	 * @package Default
	 * @subpackage Test.Case.View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'View', 'View' );
	App::uses( 'AppHelper', 'View/Helper' );
	App::uses( 'DefaultTableHelper', 'Default.View/Helper' );
	App::uses( 'DefaultAbstractTestCase', 'Default.Test/Case' );

	/**
	 * La classe DefaultTableHelperTest ...
	 *
	 * @package Default
	 * @subpackage Test.Case.View.Helper
	 */
	class DefaultTableHelperTest extends DefaultAbstractTestCase
	{
		/**
		 * Fixtures utilisés par ces tests unitaires.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'core.Apple' // TODO: détacher tous les behaviors si possible, ce qui permettra d'éviter required="required"
		);

		public static $requestsParams = array(
			'page_1_of_1' => array(
				'paging' => array(
					'Apple' => array(
						'page' => 1,
						'current' => 9,
						'count' => 19,
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
				'controller' => 'apples',
				'action' => 'index',
			),
			'page_1_of_2' => array(
				'paging' => array(
					'Apple' => array(
						'page' => 1,
						'current' => 9,
						'count' => 21,
						'prevPage' => false,
						'nextPage' => true,
						'pageCount' => 2,
						'order' => null,
						'limit' => 20,
						'options' => array(
							'page' => 1,
							'conditions' => array( )
						),
						'paramType' => 'named'
					)
				),
				'controller' => 'apples',
				'action' => 'index',
			),
			'page_2_of_2' => array(
				'paging' => array(
					'Apple' => array(
						'page' => 2,
						'current' => 21,
						'count' => 21,
						'prevPage' => true,
						'nextPage' => false,
						'pageCount' => 2,
						'order' => null,
						'limit' => 20,
						'options' => array(
							'page' => 2,
							'conditions' => array( )
						),
						'paramType' => 'named'
					)
				),
				'controller' => 'apples',
				'action' => 'index',
			),
			'page_2_of_7' => array(
				'paging' => array(
					'Apple' => array(
						'page' => 2,
						'current' => 9,
						'count' => 62,
						'prevPage' => false,
						'nextPage' => true,
						'pageCount' => 7,
						'order' => null,
						'limit' => 20,
						'options' => array(
							'page' => 1,
							'conditions' => array( )
						),
						'paramType' => 'named'
					)
				),
				'controller' => 'apples',
				'action' => 'index',
			),
		);

		/**
		 *
		 * @var array
		 */
		public $fields = array(
			'Apple.id',
			'data[Apple][color]',
			'/Apples/view/#Apple.id#'
		);

		/**
		 *
		 * @var array
		 */
		public $data = array(
			array(
				'Apple' => array(
					'id' => 6,
					'color' => 'red'
				)
			)
		);

		/**
		 *
		 * @param array $requestParams
		 */
		protected function _setRequest( $requestParams ) {
			$Request = new CakeRequest( null, false );
			$Request->addParams( $requestParams );

			$this->DefaultTable->request = $Request;
			$this->DefaultTable->DefaultPaginator->request = $Request;
		}

		/**
		 * Préparation du test.
		 *
		 * @return void
		 */
		public function setUp() {
			parent::setUp();
			$controller = null;
			$this->View = new View( $controller );
			$this->DefaultTable = new DefaultTableHelper( $this->View );

			$this->_setRequest( self::$requestsParams['page_2_of_7'] );
		}

		/**
		 * Nettoyage postérieur au test.
		 *
		 * @return void
		 */
		public function tearDown() {
			parent::tearDown();
			unset(
				/*$this->DefaultTable->DefaultTableCell,
				$this->DefaultTable->DefaultHtml,
				$this->DefaultTable->DefaultPaginator,*/
				$this->View,
				$this->DefaultTable
			);
		}

		/**
		 * Test de la méthode DefaultTableHelper::thead()
		 *
		 * @return void
		 */
		public function testThead() {
			$params = array();

			// Sans donnée
			$result = $this->DefaultTable->thead( array(), $params );
			$expected = null;
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			// Avec le tri
			$result = $this->DefaultTable->thead( $this->fields, $params );
			$expected = '<thead>
							<tr>
								<th id="ColumnAppleId"><a href="/index/page:1/sort:Apple.id/direction:asc">Apple.id</a></th>
								<th id="ColumnInputdata[Apple][color]">data[Apple][color]</th>
								<th colspan="1" class="actions" id="ColumnActions">Actions</th>
							</tr>
						</thead>';
			$this->assertEqualsXhtml( $result, $expected );

			// Sans le tri
			$result = $this->DefaultTable->thead( $this->fields, $params + array( 'sort' => false ) );
			$expected = '<thead>
							<tr>
								<th id="ColumnAppleId">Apple.id</th>
								<th id="ColumnInputdata[Apple][color]">data[Apple][color]</th>
								<th colspan="1" class="actions" id="ColumnActions">Actions</th>
							</tr>
						</thead>';
			$this->assertEqualsXhtml( $result, $expected );

			// Avec un nom de colonne qui surcharge la traduction
			$fields = Hash::normalize( $this->fields );
			$fields['Apple.id'] = array( 'label' => 'Test Apple.id' );

			$result = $this->DefaultTable->thead( $fields, $params + array( 'sort' => false ) );
			$expected = '<thead>
							<tr>
								<th id="ColumnAppleId">Test Apple.id</th>
								<th id="ColumnInputdata[Apple][color]">data[Apple][color]</th>
								<th colspan="1" class="actions" id="ColumnActions">Actions</th>
							</tr>
						</thead>';
			$this->assertEqualsXhtml( $result, $expected );
		}

		/**
		 * Test de la méthode DefaultTableHelper::tbody()
		 *
		 * @return void
		 */
		public function testBody() {
			$params = array();

			$result = $this->DefaultTable->tbody( array(), $this->fields, $params );
			$expected = null;
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			$result = $this->DefaultTable->tbody( $this->data, $this->fields, $params );
			$expected = '<tbody>
							<tr class="odd">
								<td class="data integer positive">6</td>
								<td class="input string">
									<div class="input text">
										<label for="AppleColor">Color</label>
										<input name="data[Apple][color]" maxlength="40" type="text" id="AppleColor"/>
									</div>
								</td>
								<td class="action">
									<a href="/apples/view/6" title="/Apples/view/6" class="apples view">/Apples/view</a>
								</td>
							</tr>
						</tbody>';
			$this->assertEqualsXhtml( $result, $expected );

			$fields = array(
				'data[Apple][][id]',
				'Apple.color',
			);
			$result = $this->DefaultTable->tbody( $this->data, $fields, $params + array( 'options' => array( 'Apple' => array( 'color' => array( 'red' => 'Red' ) ) ) ) );
			$expected = '<tbody>
							<tr class="odd">
								<td class="input integer">
									<input type="hidden" name="data[Apple][0][id]" id="Apple0Id"/>
								</td>
								<td class="data string ">Red</td>
							</tr>
						</tbody>';
			$this->assertEqualsXhtml( $result, $expected );
		}

		/**
		 * Test de la méthode DefaultTable::tableParams()
		 */
		public function testTableParams() {
			// 1. Valeurs par défaut
			$result = $this->DefaultTable->tableParams();
			$expected = array(
				'id' => 'TableApplesIndex',
				'class' => 'apples index',
				'domain' => 'apples',
				'sort' => true
			);
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			// 2. Surcharge des valeurs
			$params = array(
				'id' => 'TableMyApplesIndex',
				'class' => 'my_apples',
				'domain' => 'my_apples',
				'sort' => false
			);
			$result = $this->DefaultTable->tableParams( $params );
			$expected = array(
				'id' => 'TableMyApplesIndex',
				'class' => 'apples index my_apples',
				'domain' => 'my_apples',
				'sort' => false
			);
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DefaultTableHelper::index()
		 *
		 * @return void
		 */
		public function testIndex() {
			$params = array();

			$result = $this->DefaultTable->index( array(), $this->fields, $params );
			$expected = null;
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			$result = $this->DefaultTable->index( $this->data, $this->fields, $params );
			$expected = '<table id="TableApplesIndex" class="apples index">
							<thead>
								<tr>
									<th id="TableApplesIndexColumnAppleId"><a href="/index/page:1/sort:Apple.id/direction:asc">Apple.id</a></th>
									<th id="TableApplesIndexColumnInputdata[Apple][color]">data[Apple][color]</th>
									<th colspan="1" class="actions" id="TableApplesIndexColumnActions">Actions</th>
								</tr>
							</thead>
							<tbody>
								<tr class="odd">
									<td class="data integer positive">6</td>
									<td class="input string">
										<div class="input text">
											<label for="AppleColor">Color</label>
											<input name="data[Apple][color]" maxlength="40" type="text" id="AppleColor"/>
										</div>
									</td>
									<td class="action">
										<a href="/apples/view/6" title="/Apples/view/6" class="apples view">/Apples/view</a>
									</td>
								</tr>
							</tbody>
						</table>';
			$this->assertEqualsXhtml( $result, $expected );

			// En ajoutant explicitement l'id de la table
			$result = $this->DefaultTable->index( $this->data, $this->fields, $params + array( 'id' => 'TableTestApplesIndex' ) );
			$expected = '<table id="TableTestApplesIndex" class="apples index">
							<thead>
								<tr>
									<th id="TableTestApplesIndexColumnAppleId"><a href="/index/page:1/sort:Apple.id/direction:asc">Apple.id</a></th>
									<th id="TableTestApplesIndexColumnInputdata[Apple][color]">data[Apple][color]</th>
									<th colspan="1" class="actions" id="TableTestApplesIndexColumnActions">Actions</th>
								</tr>
							</thead>
							<tbody>
								<tr class="odd">
									<td class="data integer positive">6</td>
									<td class="input string">
										<div class="input text">
											<label for="AppleColor">Color</label>
											<input name="data[Apple][color]" maxlength="40" type="text" id="AppleColor"/>
										</div>
									</td>
									<td class="action">
										<a href="/apples/view/6" title="/Apples/view/6" class="apples view">/Apples/view</a>
									</td>
								</tr>
							</tbody>
						</table>';
			$this->assertEqualsXhtml( $result, $expected );
		}

		/**
		 * Test de la méthode DefaultTableHelper::details()
		 *
		 * @return void
		 */
		public function testDetails() {
			$fields = array(
				'Apple.id',
				'Apple.color',
			);
			$params = array();

			$result = $this->DefaultTable->details( array(), $this->fields, $params );
			$expected = null;
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			$result = $this->DefaultTable->details( $this->data[0], $fields, $params );
			$expected = '<table id="TableApplesIndex" class="apples index">
							<tbody>
								<tr class="odd">
									<td>Apple.id</td>
									<td class="data integer positive">6</td>
								</tr>
								<tr class="even">
									<td>Apple.color</td>
									<td class="data string ">red</td>
								</tr>
							</tbody>
						</table>';
			$this->assertEqualsXhtml( $result, $expected );
		}

		/**
		 * Test de la méthode DefaultTableHelper::detailsTbody()
		 *
		 * @return void
		 */
		public function testDetailsTbody() {
			$fields = array(
				'Apple.id',
				'Apple.color',
			);
			$params = array();

			$result = $this->DefaultTable->detailsTbody( array(), $this->fields, $params );
			$expected = null;
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			$params['options'] = array( 'Apple' => array( 'color' => array( 'red' => 'Foo' ) ) );
			$result = $this->DefaultTable->detailsTbody( $this->data[0], $fields, $params );
			$expected = '<tbody>
							<tr class="odd">
								<td>Apple.id</td>
								<td class="data integer positive">6</td>
							</tr>
							<tr class="even">
								<td>Apple.color</td>
								<td class="data string ">Foo</td>
							</tr>
						</tbody>';
			$this->assertEqualsXhtml( $result, $expected );
		}

		/**
		 * Test de la méthode DefaultTableHelper::detailsTbody() avec des th
		 *
		 * @return void
		 */
		public function testDetailsTbodyTh() {
			$fields = array(
				'Apple.id',
				'Apple.color',
			);
			$params = array( 'th' => true );

			$result = $this->DefaultTable->detailsTbody( array(), $this->fields, $params );
			$expected = null;
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			$params['options'] = array( 'Apple' => array( 'color' => array( 'red' => 'Foo' ) ) );
			$result = $this->DefaultTable->detailsTbody( $this->data[0], $fields, $params );
			$expected = '<tbody>
							<tr class="odd">
								<th>Apple.id</th>
								<td class="data integer positive">6</td>
							</tr>
							<tr class="even">
								<th>Apple.color</th>
								<td class="data string ">Foo</td>
							</tr>
						</tbody>';
			$this->assertEqualsXhtml( $result, $expected );
		}
	}
?>