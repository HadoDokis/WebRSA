<?php
	require_once( dirname( __FILE__ ).'/../cake_app_helper_test_case.php' );

	App::import( 'Helper', 'Paginator' );
	App::import( 'Helper', 'Html' );
	App::import( 'Helper', 'Xpaginator' );

	class XpaginatorTestCase extends CakeAppHelperTestCase
	{
		/**
		* FIXME
		*/

		/*public function testSort() {
			$result = $this->Xpaginator->sort( 'Item.name_a', null, array( 'model' => 'Item' ) );
			$expected = '<a href="/index/page:1/sort:Item.name_a/direction:asc">Item.name A</a>';
			debug( htmlentities( $result ) );
		}*/
	}
?>