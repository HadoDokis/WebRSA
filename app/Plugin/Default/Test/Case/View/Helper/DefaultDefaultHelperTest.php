<?php
	/**
	 * Code source de la classe DefaultDefaultHelperTest.
	 *
	 * PHP 5.3
	 *
	 * @package Default
	 * @subpackage Test.Case.View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'View', 'View' );
	App::uses( 'AppHelper', 'View/Helper' );
	App::uses( 'DefaultDefaultHelper', 'Default.View/Helper' );
	App::uses( 'DefaultAbstractTestCase', 'Default.Test/Case' );

	/**
	 * La classe DefaultDefaultHelperTest ...
	 *
	 * @package Default
	 * @subpackage Test.Case.View.Helper
	 */
	class DefaultDefaultHelperTest extends DefaultAbstractTestCase
	{
		/**
		 * Fixtures utilisés par ces tests unitaires.
		 *
		 * @var array
		 */
		public $fixtures = array(
			'core.Apple'
		);

		/**
		 * Données à utiliser dans les cas de test.
		 *
		 * @var array
		 */
		public $datas = array(
			array(
				'Apple' => array(
					'id' => 7
				)
			)
		);

		public $requestsParams = array(
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
		 * @param array $requestParams
		 */
		protected function _setRequest( $requestParams ) {
			$Request = new CakeRequest( null, false );
			$Request->addParams( $requestParams );

			$this->DefaultDefault->request = $Request;
			$this->DefaultDefault->DefaultTable->request = $Request;
			$this->DefaultDefault->DefaultPaginator->request = $Request;
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
			$this->DefaultDefault = new DefaultDefaultHelper( $this->View );

			$this->_setRequest( $this->requestsParams['page_2_of_7'] );
		}

		/**
		 * Nettoyage postérieur au test.
		 *
		 * @return void
		 */
		public function tearDown() {
			parent::tearDown();
			unset( $this->View, $this->DefaultDefault );
		}

		/**
		 * Test de la méthode DefaultDefaultHelper::actions(()
		 *
		 * @return void
		 */
		public function testActions() {
			$result = $this->DefaultDefault->actions( array() );
			$expected = null;
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			$result = $this->DefaultDefault->actions( array( '/Users/admin_add' ) );
			$expected = '<ul class="actions">
							<li class="action">
								<a href="/admin/users/add" title="/Users/admin_add/:title" class="users admin_add">'.__d( 'users', '/Users/admin_add' ).'</a>
							</li>
						</ul>';
			$this->assertEqualsXhtml( $result, $expected );

			$result = $this->DefaultDefault->actions( array( '/Users/admin_add' => array( 'text' => 'Aut Caesar, aut nihil' ) ) );
			$expected = '<ul class="actions">
							<li class="action">
								<a href="/admin/users/add" title="/Users/admin_add/:title" class="users admin_add">Aut Caesar, aut nihil</a>
							</li>
						</ul>';
			$this->assertEqualsXhtml( $result, $expected );

			$result = $this->DefaultDefault->actions( array( '/Users/admin_add' => array( 'enabled' => false ) ) );
			$expected = '<ul class="actions">
							<li class="action">
								<span title="/Users/admin_add/:title" class="users add disabled">'.__d( 'users', '/Users/admin_add' ).'</span>
							</li>
						</ul>';
			$this->assertEqualsXhtml( $result, $expected );
		}

		/**
		 * Test de la méthode DefaultDefaultHelper::index(()
		 *
		 * @return void
		 */
		public function testIndex() {
			$fields = array(
				'Apple.id'
			);
			$params = array();

			$result = $this->DefaultDefault->index( array(), $fields, $params );
			$expected = '<p class="notice">Aucun enregistrement</p>';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			$result = $this->DefaultDefault->index( $this->datas, $fields, $params );
			$expectedCounter = sprintf( preg_replace( '/\{[^\}]+\}/', '%d', __( 'Page {:page} of {:pages}, from {:start} to {:end}' ) ), 2, 7, 21, 40 );
			$expected = '<div class="pagination">
							<p class="counter">'.$expectedCounter.'</p>
							<p class="numbers">
								<span><a href="/index/page:1" rel="first">'.h( __( '<< first' ) ).'</a></span>
								<span class="prev">'.h( __( '< prev' ) ).'</span>
								<span><a href="/index/page:1">1</a></span> | <span class="current">2</span> | <span><a href="/index/page:3">3</a></span> | <span><a href="/index/page:4">4</a></span> | <span><a href="/index/page:5">5</a></span> | <span><a href="/index/page:6">6</a></span> | <span><a href="/index/page:7">7</a></span>
								<span class="next"><a href="/index/page:3" rel="next">'.h( __( 'next >' ) ).'</a></span>
								<span><a href="/index/page:7" rel="last">'.h( __( 'last >>' ) ).'</a></span>
							</p>
						</div>
						<table id="TableApplesIndex" class="apples index">
							<thead>
								<tr>
									<th id="TableApplesIndexColumnAppleId"><a href="/index/page:1/sort:Apple.id/direction:asc">Apple.id</a></th>
								</tr>
							</thead>
							<tbody>
								<tr class="odd">
									<td class="data integer positive">7</td>
								</tr>
							</tbody>
						</table>
						<div class="pagination">
							<p class="counter">'.$expectedCounter.'</p>
							<p class="numbers">
								<span><a href="/index/page:1" rel="first">'.h( __( '<< first' ) ).'</a></span>
								<span class="prev">'.h( __( '< prev' ) ).'</span>
								<span><a href="/index/page:1">1</a></span> | <span class="current">2</span> | <span><a href="/index/page:3">3</a></span> | <span><a href="/index/page:4">4</a></span> | <span><a href="/index/page:5">5</a></span> | <span><a href="/index/page:6">6</a></span> | <span><a href="/index/page:7">7</a></span>
								<span class="next"><a href="/index/page:3" rel="next">'.h( __( 'next >' ) ).'</a></span>
								<span><a href="/index/page:7" rel="last">'.h( __( 'last >>' ) ).'</a></span>
							</p>
						</div>';
			$this->assertEqualsXhtml( $result, $expected );

			$this->_setRequest( $this->requestsParams['page_1_of_1'] );
			$expectedCounter = sprintf( preg_replace( '/\{[^\}]+\}/', '%d', __( 'Page {:page} of {:pages}, from {:start} to {:end}' ) ), 1, 1, 1, 19 );
			$result = $this->DefaultDefault->index( $this->datas, $fields, $params );
			$expected = '<div class="pagination">
							<p class="counter">'.$expectedCounter.'</p>
						</div>
						<table id="TableApplesIndex" class="apples index">
							<thead>
								<tr>
									<th id="TableApplesIndexColumnAppleId">
										<a href="/index/page:1/sort:Apple.id/direction:asc">Apple.id</a>
									</th>
								</tr>
							</thead>
							<tbody>
								<tr class="odd">
									<td class="data integer positive">7</td>
								</tr>
							</tbody>
						</table>
						<div class="pagination">
							<p class="counter">'.$expectedCounter.'</p>
						</div>';
			$this->assertEqualsXhtml( $result, $expected );

			$this->_setRequest( $this->requestsParams['page_1_of_2'] );
			$expectedCounter = sprintf( preg_replace( '/\{[^\}]+\}/', '%d', __( 'Page {:page} of {:pages}, from {:start} to {:end}' ) ), 1, 2, 1, 20 );
			$result = $this->DefaultDefault->index( $this->datas, $fields, $params );
			$expected = '<div class="pagination">
							<p class="counter">'.$expectedCounter.'</p>
							<p class="numbers">
								<span class="first">'.h( __( '<< first' ) ).'</span>
								<span class="prev">'.h( __( '< prev' ) ).'</span>
								<span class="current">1</span> | <span><a href="/index/page:2">2</a></span>
								<span class="next"><a href="/index/page:2" rel="next">'.h( __( 'next >' ) ).'</a></span>
								<span><a href="/index/page:2" rel="last">'.h( __( 'last >>' ) ).'</a></span>
							</p>
						</div>
						<table id="TableApplesIndex" class="apples index">
							<thead>
								<tr>
									<th id="TableApplesIndexColumnAppleId">
										<a href="/index/page:1/sort:Apple.id/direction:asc">Apple.id</a>
									</th>
								</tr>
							</thead>
							<tbody>
								<tr class="odd">
									<td class="data integer positive">7</td>
								</tr>
							</tbody>
						</table>
						<div class="pagination">
							<p class="counter">'.$expectedCounter.'</p>
							<p class="numbers">
								<span class="first">'.h( __( '<< first' ) ).'</span>
								<span class="prev">'.h( __( '< prev' ) ).'</span>
								<span class="current">1</span> | <span><a href="/index/page:2">2</a></span>
								<span class="next"><a href="/index/page:2" rel="next">'.h( __( 'next >' ) ).'</a></span>
								<span><a href="/index/page:2" rel="last">'.h( __( 'last >>' ) ).'</a></span>
							</p>
						</div>';

			$this->assertEqualsXhtml( $result, $expected );

			$this->_setRequest( $this->requestsParams['page_2_of_2'] );
			$expectedCounter = sprintf( preg_replace( '/\{[^\}]+\}/', '%d', __( 'Page {:page} of {:pages}, from {:start} to {:end}' ) ), 2, 2, 21, 21 );
			$result = $this->DefaultDefault->index( $this->datas, $fields, $params );
			$expected = '<div class="pagination">
							<p class="counter">'.$expectedCounter.'</p>
							<p class="numbers">
								<span><a href="/index/page:1" rel="first">'.h( __( '<< first' ) ).'</a></span>
								<span class="prev"><a href="/index/page:1" rel="prev">'.h( __( '< prev' ) ).'</a></span>
								<span><a href="/index/page:1">1</a></span> | <span class="current">2</span>
								<span class="next">'.h( __( 'next >' ) ).'</span>
								<span class="last">'.h( __( 'last >>' ) ).'</span>
							</p>
						</div>
						<table id="TableApplesIndex" class="apples index">
							<thead>
								<tr>
									<th id="TableApplesIndexColumnAppleId"><a href="/index/page:2/sort:Apple.id/direction:asc">Apple.id</a></th>
								</tr>
							</thead>
							<tbody>
								<tr class="odd">
									<td class="data integer positive">7</td>
								</tr>
							</tbody>
						</table>
						<div class="pagination">
							<p class="counter">'.$expectedCounter.'</p>
							<p class="numbers">
								<span><a href="/index/page:1" rel="first">'.h( __( '<< first' ) ).'</a></span>
								<span class="prev"><a href="/index/page:1" rel="prev">'.h( __( '< prev' ) ).'</a></span>
								<span><a href="/index/page:1">1</a></span> | <span class="current">2</span>
								<span class="next">'.h( __( 'next >' ) ).'</span>
								<span class="last">'.h( __( 'last >>' ) ).'</span>
							</p>
						</div>';
			$this->assertEqualsXhtml( $result, $expected );
		}

		/**
		 * Test de la méthode DefaultDefaultHelper::titleForLayout(()
		 *
		 * @return void
		 */
		public function testTitleForLayout() {
			$result = $this->DefaultDefault->titleForLayout( $this->datas );
			$expected = '<h1>/Apples/index/:heading</h1>';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );
		}

		/**
		 * Test de la méthode DefaultDefaultHelper::view(()
		 *
		 * @return void
		 */
		public function testView() {
			$fields = array(
				'Apple.id'
			);

			$result = $this->DefaultDefault->view( $this->datas[0], $fields );
			$expected = '<table id="TableApplesIndex" class="apples index"><tbody><tr class="odd"><td>Apple.id</td> <td class="data integer positive">7</td></tr></tbody></table>';
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

			$result = $this->DefaultDefault->view( array(), $fields );
			$expected = null;
			$this->assertEquals( $result, $expected, var_export( $result, true ) );

		}

		/**
		 * Test de la méthode DefaultDefaultHelper::form(()
		 *
		 * @return void
		 */
		public function testForm() {
			$fields = array(
				'Apple.id',
				'Apple.color',
			);

			$params = array( 'options' => array( 'Apple' => array( 'color' => array( 'red' => 'Red' ) ) ) );

			$result = $this->DefaultDefault->form( $fields, $params );
			$expected = '<form action="/" novalidate="novalidate" id="Form" method="post" accept-charset="utf-8">
							<div style="display:none;">
								<input type="hidden" name="_method" value="POST"/>
							</div>
							<div class="input text">
								<label for="AppleId">Apple.id</label>
								<input name="data[Apple][id]" type="text" id="AppleId"/>
							</div>
							<div class="input select">
								<label for="AppleColor">Apple.color</label>
								<select name="data[Apple][color]" id="AppleColor">
									<option value="red">Red</option>
								</select>
							</div>
							<div class="submit">
								<input  name="Save" type="submit" value="Enregistrer"/>
								<input  name="Cancel" type="submit" value="Annuler"/>
							</div>
						</form>';

			$this->assertEqualsXhtml( $result, $expected );
		}

		/**
		 * Test de la méthode DefaultDefaultHelper::subform(()
		 *
		 * @return void
		 */
		public function testSubform() {
			$fields = array(
				'Apple.id',
				'Apple.color',
			);

			$params = array( 'options' => array( 'Apple' => array( 'color' => array( 'red' => 'Red' ) ) ) );

			$result = $this->DefaultDefault->subform( $fields, $params );
			$expected = '<div class="input text">
							<label for="AppleId">Apple.id</label>
							<input name="data[Apple][id]" type="text" id="AppleId"/>
						</div>
						<div class="input select">
							<label for="AppleColor">Apple.color</label>
							<select name="data[Apple][color]" id="AppleColor">
								<option value="red">Red</option>
							</select>
						</div>';

			$this->assertEqualsXhtml( $result, $expected );
		}

		/**
		 * Test de la méthode DefaultDefaultHelper::urlMsgid(()
		 *
		 * @return void
		 */
		public function testUrlMsgid() {
			$url = array(
				'plugin' => null,
				'controller' => 'users',
				'action' => 'login',
			);
			$result = $this->DefaultDefault->urlMsgid( $url );
			$expected = '/Users/login/';
			$this->assertEquals( $result, $expected );

			$url = array(
				'plugin' => 'acl_extras',
				'controller' => 'users',
				'action' => 'index',
				'prefix' => 'admin',
				'admin' => 'true',
				0 => 'foo',
				'Search__active' => 1,
				'#' => 'content'
			);
			$result = $this->DefaultDefault->urlMsgid( $url );
			$expected = '/AclExtras.Users/admin_index/';
			$this->assertEquals( $result, $expected );
		}
	}
?>