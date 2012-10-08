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
		public $helpers = array( 'Xhtml', 'Html' );

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
				$title = __( Inflector::humanize(preg_replace('/_id$/', '', $title)) );
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
			$params = Set::merge( Set::extract( $this->request->params, 'pass' ), Set::extract( $this->request->params, 'named' ) );
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

		public function paginationBlock( $classname, $urlOptions, $format = 'Results %start% - %end% out of %count%.' ) {
			$page = Set::classicExtract( $this->request->params, "paging.{$classname}.page" );
			$count = Set::classicExtract( $this->request->params, "paging.{$classname}.count" );
			$limit = Set::classicExtract( $this->request->params, "paging.{$classname}.options.limit" );

			$controllerName = Inflector::camelize( $this->request->params['controller'] );

			// Pagination progressive pour ce contrôleur et cette action ?
			$progressivePaginate = Configure::read( "Optimisations.{$controllerName}_{$this->request->params['action']}.progressivePaginate" );

			// Pagination progressive pour ce contrôleur ?
			if( is_null( $progressivePaginate ) ) {
				$progressivePaginate = Configure::read( "Optimisations.{$controllerName}.progressivePaginate" );
			}

			// Pagination progressive en général ?
			if( is_null( $progressivePaginate ) ) {
				$progressivePaginate = Configure::read( 'Optimisations.progressivePaginate' );
			}

			if( ( $count > ( $limit * $page ) ) && ( $format == 'Results %start% - %end% out of %count%.' ) && $progressivePaginate ) {
				$format = 'Résultats %start% - %end% sur au moins %count% résultats.';
			}

			$this->options( array( 'url' => $urlOptions ) );
			$pagination = null;

			if( Set::classicExtract( $this->request->params, "paging.{$classname}.pageCount" ) >= 1 ) {
				$pagination = $this->Xhtml->tag ( 'p', $this->counter( array( 'format' => __( $format ) ) ), array( 'class' => 'pagination counter' ) );

				$links = array(
					$this->first( __( '<<' ) ),
					$this->prev( __( '<' ) ),
					$this->numbers(),
					$this->next( __( '>' ) )
				);

				if( !Configure::read( 'Optimisations.progressivePaginate' ) ) {
					$links[] = $this->last( __( '>>' ) );
				}

				$links = implode( ' ', $links );
				$pagination .= $this->Xhtml->tag( 'p', $links, array( 'class' => 'pagination links' ) );
			}

			return $pagination;
		}
	}
?>