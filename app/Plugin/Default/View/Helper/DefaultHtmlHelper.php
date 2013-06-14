<?php
	/**
	 * Code source de la classe DefaultHtmlHelper.
	 *
	 * PHP 5.4
	 *
	 * @package Default
	 * @subpackage View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'HtmlHelper', 'View/Helper' );

	/**
	 * La classe DefaultHtmlHelper étend la classe HtmlHelper de CakePHP dans le
	 * cadre de son utilisation dans le plugin Default.
	 *
	 * @package Default
	 * @subpackage View.Helper
	 */
	class DefaultHtmlHelper extends HtmlHelper
	{

		/**
		 * Surcharge de la méthode link de HtmlHelper afin d'ajouter des classes
		 * concernant le plugin éventuel, le prefix éventuel, le contrôleur et
		 * l'action dans le paramètre $options lorsque le paramètre $url est un
		 * array.
		 *
		 * @param string $title The content to be wrapped by <a> tags.
		 * @param string|array $url Cake-relative URL or array of URL parameters, or external URL (starts with http://)
		 * @param array $options Array of HTML attributes.
		 * @param string $confirmMessage JavaScript confirmation message.
		 * @return string An `<a />` element.
		 */
		public function link( $title, $url = null, $options = array( ), $confirmMessage = false ) {
			if( is_array( $url ) ) {
				$tmp = $url;
				$classes = array( );

				foreach( array( 'plugin', 'controller', 'action' ) as $key ) {
					if( !isset( $tmp[$key] ) ) {
						$tmp[$key] = $this->request->params[$key];
					}
				}

				// Action prefix ?
				if( isset( $tmp['prefix'] ) && !empty( $tmp['prefix'] ) && isset( $tmp[$tmp['prefix']] ) && $tmp[$tmp['prefix']] ) {
					$tmp['action'] = "{$tmp['prefix']}_{$tmp['action']}";
				}

				$classes = Hash::filter(
					array(
						$tmp['plugin'],
						$tmp['controller'],
						$tmp['action']
					)
				);

				$options = $this->addClass( $options, implode( ' ', $classes ) );
			}

			$disabled = ( isset( $options['disabled'] ) ? $options['disabled'] : false );
			unset( $options['disabled'] );

			if( !$disabled ) {
				return parent::link( $title, $url, $options, $confirmMessage );
			}
			else {
				$options = $this->addClass( $options, 'link disabled' );
				return $this->tag( 'span', $title, $options );
			}
		}
	}
?>