<?php
	/**
	 * Code source de la classe PrototypeAjaxHelper.
	 *
	 * PHP 5.3
	 *
	 * @package Prototype
	 * @subpackage View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe PrototypeAjaxHelper fournit des méthodes Ajax de haut niveau au
	 * moyen de la librairie javascript prototypejs.
	 *
	 * @package Prototype
	 * @subpackage View.Helper
	 */
	class PrototypeAjaxHelper extends AppHelper
	{
		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array( 'Html' );

		/**
		 * Le contenu du buffer.
		 *
		 * @var string
		 */
		public $script = '';

		/**
		 * Le nom du bloc qui contiendra le buffer.
		 *
		 * @var string
		 */
		public $block = 'scriptBottom';

		/**
		 * Permet de spécifier si on utilise le buffer ou si on retourne le
		 * bout de code javascript directement.
		 *
		 * @var boolean
		 */
		public $useBuffer = true;

		/**
		 * Surcharge du constructeur avec possibilité de choisir les paramètres
		 * block et useBuffer.
		 *
		 * @param View $View
		 * @param array $settings
		 */
		public function __construct( View $View, $settings = array( ) ) {
			parent::__construct( $View, $settings );
			$settings = $settings + array(
				'block' => 'scriptBottom',
				'useBuffer' => true
			);
			$this->block = $settings['block'];
			$this->useBuffer = $settings['useBuffer'];
		}

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
					if( $( json ).length == 1 && ( $( json ).first().name === null ) ) {
						var result = $( json ).first();
						for( field in result.values ) {
							$( field ).value = '';
							$( field ).simulate( 'change' );
						}
					}
					else {
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
		}
	);
} );";

			return $this->Html->script( array( 'prototype.event.simulate.js' ), array( 'inline' => false ) )
					.$this->render( $script );
		}

		/**
		 * Met à jour via ajax une div au chargement de la page, ainsi que lors
		 * d'une mise à jour de l'un des champs.
		 *
		 * @param string $update
		 * @param string|array $url
		 * @param string|array $fields
		 * @return string
		 */
		public function updateDivOnFieldsChange( $update, $url, $fields ) {
			$function = __FUNCTION__.$this->domId( Inflector::camelize( $update ) );
			$url = Router::url( $url );

			$fields = (array)$fields;

			$parameters = array();
			$observers = array(
				"document.observe( 'dom:loaded', function() { {$function}(); } );"
			);

			foreach( $fields as $field ) {
				$key = 'data['.str_replace( '.', '][', $field ).']';
				$domId = $this->domId( $field );

				$parameters[] = "'{$key}': \$F( '{$domId}' )";

				$observers[] = "Event.observe( \$( '{$domId}' ), 'change', function() { {$function}(); } );";
			}

			$script = "function {$function}() {
		new Ajax.Updater(
			'{$update}',
			'{$url}',
			{
				asynchronous: true,
				evalScripts: true,
				parameters: { ".implode( ',', $parameters )." }
			}
		);
	}
	".implode( "\n", $observers );

			return $this->render( $script );
		}

		/**
		 * Permet d'observer la modification de l'un des champs et le chargement
		 * de la page.
		 *
		 * @param string|array $fields
		 * @param array $params
		 * @return string
		 */
		public function observeFields( $fields, array $params = array() ) {
			$default = array(
				'prefix' => null,
				'url' => Router::url(),
				'event' => 'change',
				'onload' => true,
			);
			$params += $default;
			$fields = (array)$fields;
			$script = '';

			$ajaxParams = array( "'data[prefix]': '{$params['prefix']}'" );
			foreach( $fields as $field ) {
				$dataPath = 'data['.str_replace( '.', '][', $field ).']';
				$domId = $this->domId( $field );
				$ajaxParams[] = "'{$dataPath}': \$F( '{$domId}' )";
			}

			$url = Router::url( $params['url'] );
			foreach( $fields as $field ) {
				$domId = $this->domId( $field );
				$parameters = $ajaxParams;
				array_unshift( $parameters, "'data[Field][changed]': '{$field}'" );
				$parameters = '{ '.implode( ', ', $parameters ).' }';
				$script .= "Event.observe( \$( '{$domId}' ), '{$params['event']}', function(event) { ajaxObserveField( event, '{$url}', {$parameters} ) } );\n";
			}

			// Partie dom::loaded
			if( $params['onload'] ) {
				$domLoadedParameters = array( "'data[prefix]': '{$params['prefix']}'" );
				foreach( $fields as $field ) {
					$dataPath = 'data['.str_replace( '.', '][', $field ).']';
					$value = "'".str_replace( "'", "\\'", Hash::get( $this->request->data, $field ) )."'";
					$domLoadedParameters[] = "'{$dataPath}': {$value}";
				}
				$domLoadedParameters = '{ '.implode( ', ', $domLoadedParameters ).' }';
				$script .= "document.observe( 'dom:loaded', function(event) { ajaxObserveField( event, '{$url}', {$domLoadedParameters} ); } );\n";
			}

			return $this->render( $script );
		}

		/**
		 * Retourne le code javascript permettant de transformer un champ de type
		 * input text en champ de type complétion automatique, avec une liste
		 * déroulante "ul".
		 *
		 * @param string|array $fields
		 * @param array $params
		 * @return string
		 */
		public function autocomplete2( $fields, array $params = array() ) {
			$default = array(
				'prefix' => null,
				'url' => Router::url(),
				'event' => 'keyup',
			);
			$params += $default;
			$fields = (array)$fields;
			$script = '';

			$url = Router::url( $params['url'] );
			foreach( $fields as $field ) {
				$domId = $this->domId( $field );
				$dataPath = 'data['.str_replace( '.', '][', $field ).']';
				$parameters = array(
					"'data[prefix]': '{$params['prefix']}'",
					"'data[Field][changed]': '{$field}'",
					"'{$dataPath}': \$F( '{$domId}' )"
				);
				$parameters = '{ '.implode( ', ', $parameters ).' }';
				$script .= "\$( '{$domId}' ).writeAttribute( 'autocomplete', 'off' );\n";
				$script .= "Event.observe( \$( '{$domId}' ), '{$params['event']}', function(event) { ajaxObserveField( event, '{$url}', {$parameters} ) } );\n";
			}

			return $this->render( $script );
		}

		/**
		 * Ajoute le contenu dans le buffer si useBuffer est à true, sinon retourne
		 * le script dans une fonction déclenchée au chargement de la page.
		 *
		 * @param string $script Le code javascript à ajouter.
		 */
		public function render( $script ) {
			if( $this->useBuffer ) {
				$this->script = "{$this->script}\n{$script}";
			}
			else {
				return $this->Html->scriptBlock( $script );
			}
		}

		/**
		 * Ajoute le contenu du buffer dans une fonction déclenchée au chargement
		 * de la page, dans le block scriptBottom (par défaut), si useBuffer est
		 * à true;
		 *
		 * @param string $layoutFile The layout about to be rendered.
		 */
		public function beforeLayout( $layoutFile ) {
			parent::beforeLayout( $layoutFile );

			if( $this->useBuffer ) {
				$this->Html->scriptBlock( $this->script, array( 'block' => $this->block ) );
			}
		}

	}
?>