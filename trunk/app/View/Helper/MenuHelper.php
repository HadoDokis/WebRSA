<?php
	/**
	 * Code source de la classe MenuHelper.
	 *
	 * PHP 5.3
	 *
	 * @package app.View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe MenuHelper fournit des méthodes facilitant la construction de
	 * menus sous forme de liste non ordonnées (ul) imbriquées tout en vérifiant
	 * les permissions des différentes URLs.
	 *
	 * @package app.View.Helper
	 */
	class MenuHelper extends AppHelper
	{
		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array( 'Html', 'Permissions' );

		/**
		 * Permet de construire un menu à plusieurs niveaux en tenant compte des
		 * permissions vérifiées grâce au PermissionsHelper.
		 *
		 * <pre>
		 * $items = array(
		 *	'Composition du foyer' => array(
		 *		'url' => array( 'controller' => 'personnes', 'action' => 'index', 1 ),
		 *		'M. BUFFIN Christian' => array(
		 *			'url' => array( 'controller' => 'personnes', 'action' => 'view', 2 ),
		 *			'Mémos' => array(
		 *				'url' => array( 'controller' => 'memos', 'action' => 'index', 2 )
		 *			)
		 *		)
		 *	)
		 * );
		 *
		 * $this->Menu->make( $items );
		 *
		 * <ul>
		 * 	<li class="branch">
		 * 		<a href="/personnes/index/1">Composition du foyer</a>
		 * 		<ul>
		 * 			<li class="branch">
		 * 				<span>M. BUFFIN Christian</span>
		 * 				<ul>
		 * 					<li class="leaf"><a href="/memos/index/2">Mémos</a></li>
		 * 				</ul>
		 * 			</li>
		 * 		</ul>
		 * 	</li>
		 * </ul>
		 * </pre>
		 *
		 * @param array $items
		 * @return string
		 */
		public function make( $items ) {
			$return = '';
			foreach( $items as $key => $item ) {
				$sub = $item;
				unset( $sub['url'] );

				$sub = $this->make( $sub );

				$content = '';
				if( isset( $item['url'] ) && $this->Permissions->check( $item['url']['controller'], $item['url']['action'] ) ) {
					$content .= $this->Html->link( $key, $item['url'] ).$sub;
				}
				else if( !empty( $sub ) ) {
					$content .= $this->Html->tag( 'span', $key ).$sub;
				}

				$return .= empty( $content ) ? '' : $this->Html->tag( 'li', $content, array( 'class' => ( empty( $sub ) ? 'leaf' : 'branch' ) ) );
			}
			return empty( $return ) ? '' : $this->Html->tag( 'ul', $return );
		}

		/**
		 *
		 * @param array $items
		 * @param string $disabledTag
		 * @return string
		 */
		public function make2( $items, $disabledTag = 'span' ) {
			$return = '';
			foreach( $items as $key => $item ) {
				if( !isset( $item['disabled'] ) || !$item['disabled'] ) {
					$sub = $item;
					unset( $sub['url'], $sub['disabled'] );

					$sub = $this->make2( $sub, $disabledTag );

					$content = '';
					if( isset( $item['url'] ) && $this->Permissions->check( $item['url']['controller'], $item['url']['action'] ) ) {
						$content .= $this->Html->link( $key, $item['url'] ).$sub;
					}
					else if( !empty( $sub ) ) {
						$options = array();
						if( $disabledTag == 'a' ) {
							$options['href'] = '#';
						}
						$content .= $this->Html->tag( $disabledTag, $key, $options ).$sub;
					}

					$return .= empty( $content ) ? '' : $this->Html->tag( 'li', $content, array( 'class' => ( empty( $sub ) ? 'leaf' : 'branch' ) ) );
				}
			}
			return empty( $return ) ? '' : $this->Html->tag( 'ul', $return );
		}
	}
?>