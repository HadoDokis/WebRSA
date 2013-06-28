<?php
	/**
	 * Code source de la classe DefaultDefaultHelper.
	 *
	 * PHP 5.4
	 *
	 * @package app.View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'DefaultUtility', 'Default.Utility' );

	/**
	 * La classe DefaultDefaultHelper ...
	 *
	 * @package       app.View.Helper
	 */
	class DefaultDefaultHelper extends AppHelper
	{
		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Default.DefaultForm',
			'Default.DefaultHtml',
			'Default.DefaultPaginator',
			'Default.DefaultTable',
		);

		/**
		 * TODO:
		 *	- scinder
		 *		* compléter l'URL
		 *		* msgid(+suffix/type:title)
		 *	- bien formater plugin
		 *	- remplacer dans les Helpers où c'est nécessaire
		 *
		 * @param array $url
		 * @return string
		 */
		protected function _urlMsgid( array $url ) {
			foreach( array( 'plugin', 'controller', 'action' ) as $key ) {
				if( !isset( $url[$key] ) ) {
					$url[$key] = $this->request->params[$key];
				}
			}

			// Action prefix ?
			if( isset( $url['prefix'] ) && !empty( $url['prefix'] ) && isset( $url[$url['prefix']] ) && $url[$url['prefix']] ) {
				$url['action'] = "{$url['prefix']}_{$url['action']}";
			}

			return '/'.Inflector::camelize( $url['controller'] ).'/'.$url['action'].'/';
		}

		/**
		 *
		 * @see DefaultUtility::linkParams()
		 *
		 * @param array $menu
		 * @return null
		 */
		public function actions( array $actions ) {
			if( empty( $actions ) ) {
				return null;
			}

			$lis = array();
			foreach( Hash::normalize( $actions ) as $url => $attributes ) {
				list( $text, $url, $attributes ) = DefaultUtility::linkParams( // TODO: une méthode action()
					$url,
					(array)$attributes
				);

				if( isset( $attributes['text'] ) ) {
					$text = $attributes['text'];
				}

				if( !isset( $attributes['title'] ) ) {
					$domain = ( isset( $attributes['domain'] ) ? $attributes['domain'] : Inflector::underscore( $this->request->params['controller'] ) );
					$msgid = ( isset( $attributes['msgid'] ) ? $attributes['msgid'] : $this->_urlMsgid( $url ).':title' );
					$attributes['title'] = __d( $domain, $msgid );
				}

				$enabled = ( isset( $attributes['enabled'] ) ? $attributes['enabled'] : true );

				unset( $attributes['domain'], $attributes['msgid'], $attributes['enabled'] );

				if( $enabled ) {
					$lis[] = $this->DefaultHtml->tag(
						'li',
						$this->DefaultHtml->link(
							$text,
							$url,
							$attributes
						),
						array( 'class' => 'action' )
					);
				}
				else {
					$classes = Hash::filter(
						array(
							$url['plugin'],
							$url['controller'],
							$url['action'],
							'disabled'
						)
					);

					$attributes = $this->addClass( $attributes, implode( ' ', $classes ) );

					$lis[] = $this->DefaultHtml->tag(
						'li',
						$this->DefaultHtml->tag(
							'span',
							$text,
							$attributes
						),
						array( 'class' => 'action' )
					);
				}
			}

			return $this->DefaultHtml->tag( 'ul', implode( $lis ), array( 'class' => 'actions' ) );
		}

		/**
		 * Retourne un tableau entouré de liens de pagination si des données sont
		 * présentes, un message d'avertissement sinon.
		 *
		 * @todo un méthode pour que array_keys( Hash::normalize( $fields ) )
		 *	donne le nombre de cellules de chaque type, le nombre de cellules
		 *	d'action à la fin du tableau (@see DefaultTableHelper::thead)
		 *	et si des cellules de formulaires sont présentes, ajouter un formulaire
		 *	autour de ce qui est retourné.
		 * @todo ajouter le tableau tooltip
		 *
		 * @param array $datas
		 * @param array $fields
		 * @param array $params
		 * @return string
		 */
		public function index( array $datas, array $fields, array $params = array() ) {
			if( empty( $datas ) ) {
				return $this->DefaultHtml->tag( 'p', 'Aucun enregistrement', array( 'class' => 'notice' ) );
			}

			$pagination = $this->pagination( Hash::remove( $params, 'options' ) );

			return $pagination
				.$this->DefaultTable->index(
					$datas,
					$fields,
					$params
				)
				.$pagination;
		}

		/**
		 * Retourne un bloc de pagination.
		 *
		 * @param array $params
		 * @return string
		 */
		public function pagination( array $params = array() ) {
			$params = $params  + array( 'format' => __( 'Page {:page} of {:pages}, from {:start} to {:end}' ) );
			$pagination = $this->DefaultHtml->tag( 'p', $this->DefaultPaginator->counter( $params ), array( 'class' => 'counter' ) );
			unset( $params['format'] );

			$numbers = $this->DefaultPaginator->numbers( $params );
			if( !empty( $numbers ) ) {
				$first = $this->DefaultPaginator->first( __( '<< first' ), $params );
				$prev = $this->DefaultPaginator->prev( __( '< prev' ), $params );

				$next = $this->DefaultPaginator->next( __( 'next >' ), $params );
				$last = $this->DefaultPaginator->last( __( 'last >>' ), $params );

				if( empty( $first ) ) {
					$first = h( __( '<< first' ) );
					$first = $this->DefaultHtml->tag( 'span', $first, array( 'class' => 'first' ) );
				}

				if( empty( $last ) ) {
					$last = h( __( 'last >>' ) );
					$last = $this->DefaultHtml->tag( 'span', $last, array( 'class' => 'last' ) );
				}

				$pagination .= $this->DefaultHtml->tag(
					'p',
					"{$first} {$prev} {$numbers} {$next} {$last}",
					array( 'class' => 'numbers' )
				);
			}

			return $this->DefaultHtml->tag( 'div', $pagination, array( 'class' => 'pagination' ) );
		}

		/**
		 * Set le titre de la page dans le layout et retourne un tag h1 contenant
		 * ce text. La traduction se fait dans le domaine du contrôleur.
		 *
		 * @param array $data
		 * @param array $params
		 * @return string
		 */
		public function titleForLayout( array $data = array(), array $params = array() ) {
			$tag = ( isset( $params['tag'] ) ? $params['tag'] : 'h1' );
			$domain = ( isset( $params['domain'] ) ? $params['domain'] : Inflector::underscore( $this->request->params['controller'] ) );
			$msgid = ( isset( $params['msgid'] ) ? $params['msgid'] : '/'.Inflector::camelize( $this->request->params['controller'] ).'/'.$this->request->params['action'].'/:heading' );
			unset( $params['tag'], $params['domain'], $params['msgid'] );

			$title_for_layout = DefaultUtility::evaluate( $data, __d( $domain, $msgid ) );
			$this->_View->set( compact( 'title_for_layout' ) );
			return $this->DefaultHtml->tag( $tag, $title_for_layout, $params );
		}

		/**
		 *
		 * @param array $datas
		 * @param array $fields
		 * @param array $params
		 * @return string
		 */
		public function view( array $data, array $fields, array $params = array() ) {
			if( empty( $data ) ) {
				return null;
			}

			return $this->DefaultTable->details(
					$data,
					$fields,
					$params
				);
		}

		/**
		 * TODO: une fonction subform
		 *
		 * @param array $fields
		 * @param array $params
		 * @return string
		 */
		public function form( array $fields, array $params = array() ) {
			$model = ( isset( $params['model'] ) && !empty( $params['model'] ) ? $params['model'] : null );
			unset( $params['model'] );

			$buttons = ( isset( $params['buttons'] ) ? $params['buttons'] : array( 'Save', 'Cancel' ) );
			unset( $params['buttons'] );

			$domain = ( isset( $params['domain'] ) ? $params['domain'] : Inflector::underscore( $this->request->params['controller'] ) );
			unset( $params['domain'] );

			$legend = ( isset( $params['legend'] ) ? $params['legend'] : false );
			unset( $params['legend'] );

			$fieldset = ( isset( $params['fieldset'] ) ? $params['fieldset'] : false );
			unset( $params['fieldset'] );

			$options = ( isset( $params['options'] ) ? $params['options'] : array() );
			unset( $params['options'] );

			$inputs = array();
			foreach( Hash::normalize( $fields ) as $field => $fieldParams ) {
				if( !isset( $fieldParams['label'] ) || !empty( $fieldParams['label'] ) ) {
					$fieldParams['label'] = __d( $domain, $field );
				}

				if( !isset( $fieldParams['options'] ) && Hash::check( $options, $field ) ) {
					$fieldParams['options'] = Hash::get( $options, $field );
				}

				$inputs[$field] = (array)$fieldParams;
			}
			$inputs['legend'] = $legend;
			$inputs['fieldset'] = $fieldset;

			// TODO: subform false (true)
			$return = '';
			$return .= $this->DefaultForm->create( $model, array( 'novalidate' => 'novalidate' ) ); // TODO: 2nd paramètre
			$return .= $this->DefaultForm->inputs( $inputs );
			if( !empty( $buttons ) ) {
				$return .= $this->DefaultForm->buttons( (array)$buttons );
			}
			$return .= $this->DefaultForm->end();

			return $return;
		}
	}
?>