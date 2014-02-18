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
	 * La classe SearchFormHelper ...
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
		 * Retourne le code javascript permettant d'activer ou de désactiver le fieldset contentant les cases à
		 * cocher (états du dossier, natures de la prestation, ...) suivant la valeur de la case à cocher
		 * "parente" ("choice").
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
		 * @param $path string Le chemin au sens formulaire CakePHP
		 * @param $options array Les différentes valeurs pour les cases à cocher.
		 * @return string
		 */
		public function dependantCheckboxes( $path, $options ) {
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
	}
?>