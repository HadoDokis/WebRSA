<?php
	/**
	 * Code source de la classe SearchPrototypeHelper.
	 *
	 * PHP 5.3
	 *
	 * @package Search
	 * @subpackage View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe SearchPrototypeHelper encapsule des fonctions javascript pour
	 * la librairie Prototypejs.
	 *
	 * @package Search
	 * @subpackage View.Helper
	 */
	class SearchPrototypeHelper extends AppHelper
	{
		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array( 'Html' );

		/**
		 * Fournit le code javascript permettant de désactiver les boutons de
		 * soumission d'un formumlaire lors de son envoi afin de ne pas renvoyer
		 * celui-ci plusieurs fois avant que le reqête n'ait abouti.
		 *
		 * @param string $form L'id du formulaire au sens Prototype
		 * @param string $message Le message (optionnel) qui apparaîtra en haut du formulaire
		 * @return string
		 */
		public function observeDisableFormOnSubmit( $form, $message = null ) {
			if( empty( $message ) ) {
				$script = "observeDisableFormOnSubmit( '{$form}' );";
			}
			else {
				$message = str_replace( "'", "\\'", $message );
				$script = "observeDisableFormOnSubmit( '{$form}', '{$message}' );";
			}
			$script = "document.observe( 'dom:loaded', function() { {$script} } );";

			return $this->Html->scriptBlock( $script );
		}

		/**
		 * Permet de désactiver et éventuellement de masquer un fieldset suivant
		 * qu'une case à cocher est cochée ou non.
		 *
		 * @param string $master Le chemin CakePHP de la case à cocher
		 * @param string $slave L'id HTML du fieldset
		 * @param boolean $condition true pour désactiver lorsque la case est cochée, false sinon
		 * @param boolean $hide true pour en plus cacher le fieldset lorsqu'il est désactivé
		 * @return array
		 */
		public function observeDisableFieldsetOnCheckbox( $master, $slave, $condition = false, $hide = false ) {
			$master = $this->domId( $master );
			$condition = ( $condition ? 'true' : 'false' );
			$hide = ( $hide ? 'true' : 'false' );

			$script = "observeDisableFieldsetOnCheckbox( '{$master}', '{$slave}', {$condition}, {$hide} );";
			$script = "document.observe( 'dom:loaded', function() { {$script} } );";

			return $this->Html->scriptBlock( $script );
		}

		/**
		 * Retourne le code javascript permettant de faire dépendre des input
		 * select non-multiples entre eux, suivant le principe suivant: on prend
		 * le suffixe de la valeur du maître et elle doit correspondre au préfixe
		 * de la valeur de l'esclave.
		 *
		 * @param array $fields En clé le champ maître au sens CakePHP, en valeur le champ esclave au sens CakePHP.
		 * @return string
		 */
		public function observeDependantSelect( array $fields ) {
			$script = '';

			foreach( $fields as $masterField => $slaveField ) {
				$masterField = $this->domId( $masterField );
				$slaveField = $this->domId( $slaveField );
				$script .= "dependantSelect( '{$slaveField}', '{$masterField}' );\n";
			}

			$script = "document.observe( \"dom:loaded\", function() { {$script} } );";

			return $this->Html->scriptBlock( $script );
		}

		/**
		 * Permet de désactiver et éventuellement de masquer un ensemble de champs
		 * suivant la valeur d'un champ maître.
		 *
		 * @param string $master Le chemin CakePHP du champ maître
		 * @param string|array $slaves Les chemins CakePHP des champs à désactiver
		 * @param mixed $values Les valeurs à prendre en compte pour le champ maître
		 * @param boolean $condition true pour désactiver lorsque le champ maître a une des valeurs, false sinon
		 * @param boolean $hide true pour en plus cacher les champs esclaves lorsqu'ils sont désactivés
		 * @return string
		 */
		public function observeDisableFieldsOnValue( $master, $slaves, $values, $condition, $hide = false ) {
			$master = $this->domId( $master );

			$slaves = (array)$slaves;
			foreach( $slaves as $i => $slave ) {
				$slaves[$i] = $this->domId( $slave );
			}
			$slaves = "[ '".implode( "', '", $slaves )."' ]";

			$values = (array)$values;
			foreach( $values as $i => $value ) {
				if( $value === null ) {
					$value = 'undefined';
				}
				else {
					$value = "'{$value}'";
				}
				$values[$i] = $value;
			}
			$values = "[ ".implode( ", ", $values )." ]";

			$condition = ( $condition ? 'true' : 'false' );

			$hide = ( $hide ? 'true' : 'false' );

			$script = "observeDisableFieldsOnValue( '{$master}', {$slaves}, {$values}, {$condition}, {$hide} );\n";
			$script = "document.observe( \"dom:loaded\", function() { {$script} } );";

			return $this->Html->scriptBlock( $script );
		}
	}
?>