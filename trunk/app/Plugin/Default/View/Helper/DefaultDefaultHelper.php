<?php
	/**
	 * Code source de la classe DefaultDefaultHelper.
	 *
	 * PHP 5.4
	 *
	 * @package Default
	 * @subpackage View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'DefaultUrl', 'Default.Utility' );
	App::uses( 'DefaultUtility', 'Default.Utility' );

	/**
	 * La classe DefaultDefaultHelper ...
	 *
	 * @package Default
	 * @subpackage View.Helper
	 */
	class DefaultDefaultHelper extends AppHelper
	{
		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Default.DefaultAction',
			'Default.DefaultCsv',
			'Default.DefaultForm',
			'Default.DefaultHtml',
			'Default.DefaultPaginator',
			'Default.DefaultTable',
		);

		/**
		 * Retourne une action de la liste d'actions générées par la méthode actions().
		 *
		 * @param string $url
		 * @param array $attributes
		 */
		protected function _action( $url, $attributes ) {
			list( $text, $url, $attributes ) = DefaultUtility::linkParams(
				$url,
				(array)$attributes
			);

			if( !isset( $attributes['title'] ) ) {
				$attributes += array(
					'domain' => Inflector::underscore( $this->request->params['controller'] ),
					'msgid' => DefaultUtility::msgid( $url ).'/:title'
				);

				$attributes['title'] = __d( $attributes['domain'], $attributes['msgid'] );
			}
			unset( $attributes['domain'], $attributes['msgid'] );

			if( isset( $attributes['text'] ) ) {
				$text = $attributes['text'];
				unset( $attributes['text'] );
			}

			$enabled = ( isset( $attributes['enabled'] ) ? $attributes['enabled'] : true );
			unset( $attributes['enabled'] );

			if( $enabled ) {
				$content = $this->DefaultHtml->link( $text, $url, $attributes );
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

				$content = $this->DefaultHtml->tag( 'span', $text, $attributes );
			}

			return $this->DefaultHtml->tag( 'li', $content, array( 'class' => 'action' ) );
		}

		/**
		 * Retourne une liste non ordonnée de liens.
		 *
		 * On peut spécifier un array en paramètre de chaque action, dont les
		 * clés peuvent être:
		 *	- domain: string
		 *	- title: boolean|string
		 *	- text: boolean|string
		 *
		 * Exemple:
		 * <pre>
		 * $actions = array(
		 *	'/Users/admin_add' => array( 'title' => false, 'text' => 'Ajouter' ),
		 *	'/Users/admin_permissions',
		 * );
		 *
		 * renverra
		 *
		 * <ul class="actions">
		 *	<li class="action">
		 *		<a href="/admin/users/add" class="users admin_add">
		 *			Ajouter
		 *		</a>
		 *	</li>
		 *	<li class="action">
		 *		<a href="/admin/users/permissions" title="/Users/admin_permissions/:title" class="users admin_permissions">
		 *			/Users/admin_permissions
		 *		</a>
		 *	</li>
		 * </ul>
		 * </pre>
		 *
		 * @see DefaultUtility::linkParams()
		 *
		 * @param array $actions
		 * @return string
		 */
		public function actions( array $actions ) {
			if( empty( $actions ) ) {
				return null;
			}

			$lis = array();
			foreach( Hash::normalize( $actions ) as $url => $attributes ) {
				$lis[] = $this->_action( $url, $attributes );
			}

			return $this->DefaultHtml->tag( 'ul', implode( $lis ), array( 'class' => 'actions' ) );
		}

		/**
		 * Retourne un tableau entouré de liens de pagination si des données sont
		 * présentes, un message d'avertissement sinon.
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

			$paginate = ( isset( $params['paginate'] ) ? $params['paginate'] : true );

			if( $paginate ) {
				$paginateParams = $params;
				unset( $paginateParams['options'], $paginateParams['header'] );
				$pagination = $this->pagination( $paginateParams );
			}
			else {
				$pagination = null;
				$params['sort'] = false;
			}

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
			unset( $params['format'], $params['key'], $params['innerTable'] );

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
		 * Retourne un tableau (vertical) de visualisation.
		 *
		 * @param array $data
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
		 * Retourne un formulaire complet, avec bouton 'Save' et 'Cancel' par
		 * défaut.
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

			$return = $this->DefaultForm->create( $model, array( 'novalidate' => 'novalidate' ) );
			$return .= $this->subform( $fields, $params );
			if( !empty( $buttons ) ) {
				$return .= $this->DefaultForm->buttons( (array)$buttons );
			}
			$return .= $this->DefaultForm->end();

			return $return;
		}

		/**
		 * Retourne un sous-formulaire.
		 *
		 * @param array $fields
		 * @param array $params
		 * @return string
		 */
		public function subform( array $fields, array $params = array() ) {
			$params += array(
				'domain' => Inflector::underscore( $this->request->params['controller'] ),
				'legend' => false,
				'fieldset' => false,
				'options' => array()
			);

			$inputs = array();
			foreach( Hash::normalize( $fields ) as $field => $fieldParams ) {
				if( !isset( $fieldParams['label'] ) || ( empty( $fieldParams['label'] ) && ( $fieldParams['label'] !== false ) ) ) {
					$fieldParams['label'] = __d( $params['domain'], $field );
				}

				if( !isset( $fieldParams['options'] ) && Hash::check( $params['options'], $field ) ) {
					$fieldParams['options'] = Hash::get( $params['options'], $field );
				}

				$inputs[$field] = (array)$fieldParams;
			}
			$inputs['legend'] = $params['legend'];
			$inputs['fieldset'] = $params['fieldset'];

			return $this->DefaultForm->inputs( $inputs );
		}

		/**
		 * Retourne un pseudo-formulaire de view.
		 *
		 * @param array $fields
		 * @param array $params
		 * @return string
		 */
		public function subformView( array $fields, array $params = array() ) {
			$params += array(
				'view' => true
			);

			$fields = Hash::normalize( $fields );
			foreach( $fields as $field => $fieldParams ) {
				if( !isset( $fieldParams['view'] ) ) {
					$fields[$field]['view'] = $params['view'];
				}
			}

			return $this->subform($fields, $params);
		}

		/**
		 * Effectue l'export CSV des données.
		 *
		 * @param array $data
		 * @param array $fields
		 * @param array $params
		 * @return string
		 */
		public function csv( array $data, array $fields, array $params = array() ) {
			return $this->DefaultCsv->render( $data, $fields, $params );
		}
	}
?>