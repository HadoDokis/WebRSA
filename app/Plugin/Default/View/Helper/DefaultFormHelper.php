<?php
	/**
	 * Code source de la classe DefaultFormHelper.
	 *
	 * PHP 5.4
	 *
	 * @package Default
	 * @subpackage View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'FormHelper', 'View/Helper' );

	/**
	 * La classe DefaultFormHelper étend la classe FormHelper de CakePHP
	 * dans le cadre de son utilisation dans le plugin Default.
	 *
	 * @package Default
	 * @subpackage View.Helper
	 */
	class DefaultFormHelper extends FormHelper
	{
		/**
		 * Retourne une liste de boutons de formulaire, dans le div submit, à
		 * la mode CakePHP.
		 *
		 * @param array $buttons
		 * @return string
		 */
		public function buttons( array $buttons ) {
			$return = null;

			if( !empty( $buttons ) ) {
				$submit = '';

				foreach( Hash::normalize( $buttons ) as  $buttonName => $buttonParams ) {
					$buttonLabel = ( isset( $buttonParams['label'] ) && !empty( $buttonParams['label'] ) ? $buttonParams['label'] : __( $buttonName ) );
					$buttonType = ( isset( $buttonParams['type'] ) && !empty( $buttonParams['type'] ) ? $buttonParams['type'] : 'submit' );
					$submit .= $this->submit( $buttonLabel, array( 'div' => false, 'name' => $buttonName, 'type' => $buttonType ) );
				}

				$return = $this->Html->tag( 'div', $submit, array( 'class' => 'submit' ) );
			}

			return $return;
		}

		/**
		 * Retourne un élément de formulaire contenant la valeur sous forme de
		 * texte à partir des données dans $this->request->data.
		 *
		 * Les clés suivantes sont prises en compte dans les options:
		 *	- label(string): spécifie le label si on ne veut pas de la traduction automatique
		 *	- options(array): permet de traduire la valeur
		 *	- nl2br(boolean): applique la fonction nl2br sur la valeur
		 *	- hidden(boolean): ajoute un champ caché en plus de l'affichage
		 *	- type(string): spécifie la classe du div
		 *
		 * @param string $fieldName
		 * @param array $options
		 * @return string
		 */
		public function fieldValue( $fieldName, array $options = array() ) {
			// Label
			if( isset( $options['label'] ) ) {
				$label = $options['label'];
			}
			else {
				$label = $this->label( $fieldName, null, $options );
				$label = preg_replace( '/^.*>([^<]*)<.*$/', '\1', $label );
			}
			$label = $this->Html->tag( 'span', $label, array( 'class' => 'label' ) );

			// Valeur
			$value = Hash::get( $this->request->data, $fieldName );
			if( isset( $options['options'][$value] ) ) {
				$value = $options['options'][$value];
			}
			if( isset( $options['nl2br'] ) && $options['nl2br'] ) {
				$value = nl2br( $value );
			}
			$value = $this->Html->tag( 'span', $value, array( 'class' => 'input' ) );

			// Options
			$hidden = Hash::get( $options, 'hidden' );
			$options = $this->addClass( $options, 'input value' );
			if( isset( $options['type'] ) ) {
				$options = $this->addClass( $options, $options['type'] );
				unset( $options['type'] );
			}
			unset( $options['options'], $options['label'], $options['hidden'], $options['nl2br'] );

			// Ajout d'un champ caché ?
			if( $hidden ) {
				$hidden = $this->input( $fieldName, array( 'type' => 'hidden' ) );
			}
			else {
				$hidden = '';
			}

			return $this->Html->tag( 'div', $hidden.$label.$value, $options );
		}

		/**
		 * Retourn un champ de type input (@see FormHelper) ou une valeur
		 * (@see fieldValue) si la clé 'view' est à true dans les options.
		 *
		 * @param string $fieldName
		 * @param array $options
		 * @return string
		 */
		public function input( $fieldName, $options = array( ) ) {
			if( isset( $options['view'] ) && $options['view'] ) {
				unset( $options['view'] );
				return $this->fieldValue( $fieldName, $options );
			}

			if( ( Hash::get( $options, 'type' ) == 'hidden' ) && Hash::check( $options, 'options' ) ) {
				unset( $options['options'] );
			}

			return parent::input( $fieldName, $options );
		}

		/**
		 * Permet d'ajouter l'astérisque dans une abbr au libellé, lorsqu'un champ
		 * est obligatoire.
		 *
		 * @param string $label
		 * @param array $options
		 * @return string
		 */
		protected function _required( $label, array $options = array() ) {
			if( isset( $options['required'] ) && $options['required'] ) {
				$abbr = $this->Html->tag( 'abbr', '*', array( 'class' => 'required', 'title' => __( 'Validate::notEmpty' ) ) );
				$label = h( $label )." {$abbr}";
			}

			return $label;
		}

		/**
		 * Ajoute une étoile lorsqu'un champ est obligatoire (clé required à true
		 * dans les options), en plus de la fonctionnalité de base de
		 * FormHelper::_inputLabel().
		 *
		 * @see DefaultFormHelper::_required()
		 *
		 * @param string $fieldName
		 * @param string $label
		 * @param array $options Options for the label element.
		 * @return string Generated label element
		 */
		protected function _inputLabel( $fieldName, $label, $options ) {
			$label = $this->_required( $label, $options );
			unset( $options['required'] );
			return parent::_inputLabel( $fieldName, $label, $options );
		}

		/**
		 * Ajoute une étoile lorsqu'un champ est obligatoire (clé required à true
		 * dans les options), en plus de la fonctionnalité de base de
		 * FormHelper::label().
		 *
		 * @see DefaultFormHelper::_required()
		 *
		 * @param string $fieldName This should be "Modelname.fieldname"
		 * @param string $text Text that will appear in the label field.  If
		 *   $text is left undefined the text will be inflected from the
		 *   fieldName.
		 * @param array|string $options An array of HTML attributes, or a string, to be used as a class name.
		 * @return string The formatted LABEL element
		 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/form.html#FormHelper::label
		 */
		public function label( $fieldName = null, $text = null, $options = array( ) ) {
			$text = $this->_required( $text, $options );
			unset( $options['required'] );
			return parent::label( $fieldName, $text, $options );
		}
	}
?>