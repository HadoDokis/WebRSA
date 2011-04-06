<?php
	/**
	*	Extended paginator helper (based on PaginatorHelp from CakePHP 1.2.5).
	*
	*		* Adds a sort class and a direction class (asc, desc) on the sorting links
	*
	*/

	App::import( 'Helper', 'Paginator' );

	class XPaginator2Helper extends PaginatorHelper
	{
		public $helpers = array( 'Html' );

		/**
		* Generates a sorting link
		*
		* @param  string $title Title for the link.
		* @param  string $key The name of the key that the recordset should be sorted.
		* @param  array $options Options for sorting link. See #options for list of keys.
		* @return string A link sorting default by 'asc'. If the resultset is sorted 'asc' by the specified
		*  key the returned link will sort by 'desc'.
		*/

		public function sort( $title, $key = null, $options = array() ) {
			$options = array_merge( array('url' => array(), 'model' => null), $options );
			$url = $options['url'];
			unset($options['url']);

			if (empty($key)) {
				$key = $title;
				$title = __(Inflector::humanize(preg_replace('/_id$/', '', $title)), true);
			}
			$dir = 'asc';
			$sortKey = $this->sortKey($options['model']);
			$isSorted = ($sortKey === $key || $sortKey === $this->defaultModel() . '.' . $key);

			if ($isSorted && $this->sortDir($options['model']) === 'asc') {
				$dir = 'desc';
			}
// debug(
// 	array(
// 		func_get_args(),
// 		$dir,
// 		$sortKey,
// 		$isSorted
// 	)
// );
			if (is_array($title) && array_key_exists($dir, $title)) {
				$title = $title[$dir];
			}

			// Add a sort class and a direction class (asc, desc) on the sorting link
			if( $isSorted ) {
				$options = $this->addClass( $options, "sort {$dir}" );
			}

			// Keep named params in url
			$params = Set::merge( Set::extract( $this->params, 'pass' ), Set::extract( $this->params, 'named' ) );
			foreach( array( 'page', 'sort', 'direction' ) as $unwanted ) {
				unset( $params[$unwanted] );
			}

			$url = array_merge(
				array( 'sort' => $key, 'direction' => $dir),
				$url,
				array( 'order' => null ),
				$params
			);

			return $this->link( $title, $url, $options );
		}

		/**
		* TODO: dans theme
		* Generates a default pagination block
		*
		* TODO: nom de fonction
		* TODO: docs
		*/

		public function paginationBlock( $classname, $urlOptions, $format = 'Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%' ) {
			$page = Set::classicExtract( $this->params, "paging.{$classname}.page" );
			$count = Set::classicExtract( $this->params, "paging.{$classname}.count" );
			$limit = Set::classicExtract( $this->params, "paging.{$classname}.options.limit" );

			if( ( $count > ( $limit * $page ) ) && ( $format == 'Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%' ) && Configure::read( 'Optimisations.progressivePaginate' ) ) {
				$format = 'Résultats %start% - %end% sur au moins %count% résultats.';
			}
// 			else {
// 				$format = 'Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%'
// 			}

			$this->options( array( 'url' => $urlOptions ) );
			$pagination = null;
			$pageCount = Set::classicExtract( $this->params, "paging.{$classname}.pageCount" );

			if( $pageCount >= 1 ) {
				$pagination = $this->Html->tag ( 'p', $this->counter( array( 'format' => __( $format, true ) ) ), array( 'class' => 'pagination counter' ) );

				if( $pageCount > 1 ) {
					$links = array(
						$this->first( __( '<<', true ) ),
						$this->prev( __( '<', true ) ),
						$this->numbers(),
						$this->next( __( '>', true ) )
					);

					if( !Configure::read( 'Optimisations.progressivePaginate' ) ) {
						$links[] = $this->last( __( '>>', true ) );
					}

					$links = implode( ' ', $links );
					$pagination .= $this->Html->tag( 'p', $links, array( 'class' => 'pagination links' ) );
				}
			}

			return $pagination;
		}
	}
?>
