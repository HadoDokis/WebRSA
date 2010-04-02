<?php
	/**
	*	Extended paginator helper (based on PaginatorHelp from CakePHP 1.2.5).
	*
	*		* Adds a sort class and a direction class (asc, desc) on the sorting links
	*
	*/

	App::import( 'Helper', 'Paginator' );

	class XPaginatorHelper extends PaginatorHelper
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

		function sort( $title, $key = null, $options = array() ) {
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

			if (is_array($title) && array_key_exists($dir, $title)) {
				$title = $title[$dir];
			}

			// Add a sort class and a direction class (asc, desc) on the sorting link
			if( !empty( $sortKey ) && ( $key == $sortKey ) ) {
				$class = explode( ' ', Set::extract( $options, 'class' ) );
				$options['class'] = implode( ' ', Set::merge( $class, array( 'sort', $dir ) ) );
			}

			// Keep named params in url
			$params = Set::merge( Set::extract( $this->params, 'pass' ), Set::extract( $this->params, 'named' ) );
			$params = ( empty( $params ) ? array() : $params );
			foreach( array( 'page', 'sort', 'direction' ) as $unwanted ) {
				unset( $params[$unwanted] );
			}

			$url = array_merge( array( 'sort' => $key, 'direction' => $dir), $url, array('order' => null), $params );
			return $this->link($title, $url, $options);
		}

		/**
		* FIXME: dans theme
		* Generates a default pagination block
		*
		* FIXME: nom de fonction
		* FIXME: docs
		*/

		function paginationBlock( $classname, $urlOptions, $format = 'Results %start% - %end% out of %count%.' ) {
			$this->options( array( 'url' => $urlOptions ) );
			$pagination = null;
			if( Set::classicExtract( $this->params, "paging.{$classname}.pageCount" ) >= 1 ) {
				$pagination = $this->Html->tag ( 'p', $this->counter( array( 'format' => __( $format, true ) ) ), array( 'class' => 'pagination counter' ) );
				$links = implode(
					' ',
					array(
						$this->first( __( '<<', true ) ),
						$this->prev( __( '<', true ) ),
						$this->numbers(),
						$this->next( __( '>', true ) ),
						$this->last( __( '>>', true ) )
					)
				);
				$pagination .= $this->Html->tag( 'p', $links, array( 'class' => 'pagination links' ) );
			}

			return $pagination;
		}
	}
?>
