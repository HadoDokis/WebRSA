<?php
	/**
	 * Code source de la classe SearchFormHelper.
	 *
	 * PHP 5.3
	 *
	 * @package Search
	 * @subpackage View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe SearchFormHelper fournit des méthodes génériques pour des éléments
	 * de formulaires. Utilise la librairire javascript prototype.js.
	 *
	 * @package Search
	 * @subpackage View.Helper
	 */
	class SearchFormHelper extends AppHelper
	{
		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array( 'Form', 'Html' );

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
				$out = "document.observe( 'dom:loaded', function() {
					observeDisableFormOnSubmit( '{$form}' );
				} );";
			}
			else {
				$message = str_replace( "'", "\\'", $message );

				$out = "document.observe( 'dom:loaded', function() {
					observeDisableFormOnSubmit( '{$form}', '{$message}' );
				} );";
			}

			return "<script type='text/javascript'>{$out}</script>";
		}

		/**
		 * Retourne le code javascript permettant d'activer ou de désactiver le
		 * fieldset contentant les cases à cocher (états du dossier, natures de
		 * la prestation, ...) suivant la valeur de la case à cocher "parente"
		 * ("choice").
		 *
		 * @param string $observeId
		 * @param string $updateId
		 * @param boolean $goUp
		 * @return string
		 */
		protected function _constuctObserve( $observeId, $updateId, $goUp = true ) {
			$goUp = $goUp ? ".up( 'fieldset' )" : '';
			$out = "document.observe( 'dom:loaded', function() { observeDisableFieldsetOnCheckbox( '{$observeId}', $( '{$updateId}' ){$goUp}, false ); } );";

			return $this->Html->scriptBlock( $out );
		}

		/**
		 * Retourne le code javascript permettant de faire dépendre des input
		 * select non-multiples entre eux, suivant le principe suivant: on prend
		 * le suffixe de la valeur du maître et elle doit correspondre au préfixe
		 * de la valeur de l'esclave.
		 *
		 * @param array $fields En clé le champ maître, en valeur le champ esclave
		 * @return string
		 */
		public function jsObserveDependantSelect( array $fields ) {
			$script = '';

			foreach( $fields as $masterField => $slaveField ) {
				$masterField = $this->domId( $masterField );
				$slaveField = $this->domId( $slaveField );
				$script .= "dependantSelect( '{$slaveField}', '{$masterField}' );\n";
			}

			$script = "document.observe( \"dom:loaded\", function() {\n{$script}} );";

			return $this->Html->scriptBlock( $script );
		}

		/**
		 * Méthode générique permettant de retourner un ensemble de cases à cocher au sein d'un
		 * fieldset, activées ou désactivées par une autre case à cocher située au-dessus du fieldset.
		 *
		 * Les traductions - "{$path}_choice" pour la case à cocher d'activation/désactivation et
		 * $path pour le fieldset sont faites dans le fichier de traduction correspondant au nom
		 * du contrôleur.
		 *
		 * Remplacements possibles:
		 * //			echo $this->Search->etatdosrsa($etatdosrsa);
		 * echo $this->SearchForm->dependantCheckboxes( 'Situationdossierrsa.etatdosrsa', $etatdosrsa );
		 * @see SearchHelper
		 *
		 * @param string $path
		 * @param array $options
		 * @return string
		 */
		public function dependantCheckboxes( $path, array $options = array() ) {
			$domain = Inflector::underscore( $this->request->params['controller'] );
			$fieldsetId = $this->domId( "{$path}_fieldset" );
			$choicePath = "{$path}_choice";

			$input = $this->Form->input(
				$choicePath,
				array(
					'label' => __d( $domain, $choicePath ),
					'type' => 'checkbox'
				)
			);

			$input .= $this->Html->tag(
				'fieldset',
				$this->Html->tag( 'legend', __d( $domain, $path ) )
				.$this->Form->input(
					$path,
					array(
						'label' => false,
						'type' => 'select',
						'multiple' => 'checkbox',
						'options' => $options,
						'fieldset' => false
					)
				),
				array( 'id' => $fieldsetId )
			);

			$script = $this->_constuctObserve( $this->domId( $choicePath ), $fieldsetId, false );

			return $input.$script;
		}

		/**
		 * Méthode générique permettant de filtrer sur une plage de dates.
		 *
		 * @todo Options: dateFormat, maxYear, minYear, ...
		 *
		 * @param string $path
		 * @param string $fieldLabel
		 * @return string
		 */
		public function dateRange( $path, $fieldLabel = null ) {
			$fieldsetId = $this->domId( $path ).'_from_to';

			$script = $this->_constuctObserve( $this->domId( $path ), $fieldsetId, false );

			list( $model, $field ) = model_field( $path);
			$domain = Inflector::underscore( $model );
			if( empty( $fieldLabel ) ) {
				$fieldLabel = __d( $domain, "{$model}.{$field}" );
			}

			$input = $this->Form->input( $path, array( 'label' => 'Filtrer par '.lcfirst( $fieldLabel ), 'type' => 'checkbox' ) );

			$input .= $this->Html->tag(
				'fieldset',
				$this->Html->tag( 'legend', $fieldLabel )
				.$this->Form->input( $path.'_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'default' => strtotime( '-1 week' ) ) )
				.$this->Form->input( $path.'_to', array( 'label' => 'Au (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 5, 'minYear' => date( 'Y' ) - 120 ) ),
				array( 'id' => $fieldsetId )
			);

			return $script.$input;
		}
	}
?>