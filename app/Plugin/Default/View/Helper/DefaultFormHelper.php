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
					$submit .= $this->submit( $buttonLabel, array( 'div' => false, 'name' => $buttonName ) );
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

			return parent::input( $fieldName, $options );
		}
	}
?>