<?php
	/**
	 * Code source de la classe Ajax2Helper.
	 *
	 * PHP 5.3
	 *
	 * @package app.View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Ajax2Helper fournit des méthodes Ajax de haut niveau au moyen
	 * de la librairie javascript prototypejs.
	 *
	 * @package app.View.Helper
	 */
	class Ajax2Helper extends AppHelper
	{
		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array( 'Html' );

		/**
		 * Retourne le code javascript permettant de transformer un champ de type
		 * input text en champ de type complétion automatique, avec une liste
		 * déroulante "ul".
		 *
		 * Pour chaque résultat envoyé en json, on regardera:
		 *	- le champ 'name' pour remplir le texte de la liste déroulante
		 *	- le champ correspondant au domId de $path pour y mettre la valeur en cas de sélection
		 *	- les valeurs contenues dans l'array 'values' (domId => valeur) pour remplir le formulaire
		 *
		 * @param string $path
		 * @param array $params
		 * @return string
		 */
		public function autocomplete( $path, array $params = array() ) {
			$params += array(
				'prefix' => null,
				'url' => null,
				'domIdSelect' => 'ajaxSelect'
			);
			$params['url'] = Router::url( $params['url'] );

			$dataNameMaster = 'data['.implode( '][', explode( '.', $path ) ).']';
			$domIdMaster = $this->Html->domId( $path );

			$script = "$( '{$domIdMaster}' ).writeAttribute( 'autocomplete', 'off' );
Event.observe( $( '{$domIdMaster}' ), 'keyup', function() {
	new Ajax.Request(
		'{$params['url']}',
		{
			method: 'post',
			parameters: {
				'data[path]': '{$path}',
				'data[prefix]': '{$params['prefix']}',
				'{$dataNameMaster}': \$F( '{$domIdMaster}' )
			},
			onSuccess: function( response ) {
				var oldAjaxSelect = $( '{$params['domIdSelect']}' );
				if( oldAjaxSelect ) {
					$( oldAjaxSelect ).remove();
				}

				var json = response.responseText.evalJSON();

				if( $(json).length > 0 ) {
					var ajaxSelect = new Element( 'ul' );

					$( json ).each( function ( result ) {
						var a = new Element( 'a', { href: '#', onclick: 'return false;' } ).update( result['name'] );

						$( a ).observe( 'click', function( event ) {
							for( field in result.values ) {
								$( field ).value = result['values'][field];
								$( field ).simulate( 'change' );
							}

							$( '{$domIdMaster}' ).value = result['{$domIdMaster}'];

							$( '{$params['domIdSelect']}' ).remove();

							return false;
						} );

						$( ajaxSelect ).insert( { bottom: $( a ).wrap( 'li' ) } );
					} );

					$( '{$domIdMaster}' ).up( 'div' ).insert(  { after: $( ajaxSelect ).wrap( 'div', { id: '{$params['domIdSelect']}', class: 'ajax select' } ) }  );
				}
			}
		}
	);
} );";
			return $this->Html->script( array( 'prototype.event.simulate.js' ), array( 'inline' => false ) )
				.$this->Html->scriptBlock( $script, array( 'inline' => true, 'safe' => true ) );
		}
	}
?>