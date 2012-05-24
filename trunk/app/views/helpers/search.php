<?php
	/**
	 * Cette classe comprend des combinaisons de champs de formulaire de recherche.
	 */
	class SearchHelper extends AppHelper
	{
		public $helpers = array( 'Html', 'Xform' );

		/**
		 * Retourne le code javascript permettant d'activer ou de désactiver le fieldset contentant les cases à
		 * cocher (états du dossier, natures de la prestation, ...) suivant la valeur de la case à cocher
		 * "parente" ("choice").
		 *
		 * @param string $observeId
		 * @param string $updateId
		 * @param boolean $up
		 * @return string
		 */
		protected function _constuctObserve( $observeId, $updateId, $up = true ) {
			$stringUp = '';
			if( $up ) {
				$stringUp = ".up( 'fieldset' )";
			}
			$out = "document.observe('dom:loaded', function() {
				observeDisableFieldsetOnCheckbox( '{$observeId}', $( '{$updateId}' ){$stringUp}, false );
			});";
			return "<script type='text/javascript'>{$out}</script>";
		}

		/**
		 * Filtre sur les états du dossier RSA
		 *
		 * @param string $etatdosrsa
		 * @param string $path
		 * @return string
		 */
		public function etatdosrsa( $etatdosrsa, $path = 'Situationdossierrsa.etatdosrsa' ) {
			$fieldsetId = $this->domId( $path );

			$script = $this->_constuctObserve( $this->domId( $path.'_choice' ), $fieldsetId, false );

			$input = $this->Xform->input( $path.'_choice', array( 'label' => 'Filtrer par état du dossier', 'type' => 'checkbox' ) );

			$etatsCoches = Set::extract( $this->data, $path );
			if( empty( $etatsCoches ) ) {
				$etatsCoches = array_keys( $etatdosrsa );
			}

			$input.= $this->Html->tag(
				'fieldset',
				$this->Html->tag( 'legend', 'État du dossier RSA' ).
				$this->Xform->input( $path, array( 'label' => false, 'type' => 'select', 'multiple' => 'checkbox', 'options' => $etatdosrsa, 'value' => $etatsCoches, 'fieldset' => false ) ),
				array( 'id' => $fieldsetId )
			);

			return $script.$input;
		}

		/**
		 * Filtre sur les natures de prestation
		 *
		 * @param string $natpf
		 * @param string $path
		 * @return string
		 */
		public function natpf( $natpf, $path = 'Detailcalculdroitrsa.natpf' ) {
			$fieldsetId = $this->domId( $path );

			$script = $this->_constuctObserve( $this->domId( $path.'_choice' ), $fieldsetId, false );

			$input = $this->Xform->input( $path.'_choice', array( 'label' => 'Filtrer par nature de prestation (RSA Socle)', 'type' => 'checkbox' ) );

			$natpfsCoches = Set::extract( $this->data, $path );
			if( empty( $natpfsCoches ) ) {
				$natpfsCoches = array_keys( $natpf );
			}

			$input.= $this->Html->tag(
				'fieldset',
				$this->Html->tag( 'legend', 'Nature de la prestation' ).
				$this->Xform->input( $path, array( 'label' => false, 'type' => 'select', 'multiple' => 'checkbox', 'options' => $natpf, 'value' => $natpfsCoches, 'fieldset' => false ) ),
				array( 'id' => $fieldsetId )
			);

			return $script.$input;
		}

		/**
		 * Filtre sur les états du dossierpcg66
		 *
		 * @param string $etatdossierpcg
		 * @param string $path
		 * @return string
		 */
		public function etatDossierPCG66( $etatdossierpcg, $path = 'Dossierpcg66.etatdossierpcg' ) {
			$fieldsetId = $this->domId( $path );

			$script = $this->_constuctObserve( $this->domId( $path.'_choice' ), $fieldsetId, false );

			$input = $this->Xform->input( $path.'_choice', array( 'label' => 'Filtrer par état du dossier PCG', 'type' => 'checkbox' ) );

			$etatsDossiersPCGCoches = Set::extract( $this->data, $path );
			if( empty( $etatsDossiersPCGCoches ) ) {
				$etatsDossiersPCGCoches = array_keys( $etatdossierpcg );
			}

			$input.= $this->Html->tag(
				'fieldset',
				$this->Html->tag( 'legend', 'État du dossier PCG' ).
				$this->Xform->input( $path, array( 'label' => false, 'type' => 'select', 'multiple' => 'checkbox', 'options' => $etatdossierpcg, 'value' => $etatsDossiersPCGCoches, 'fieldset' => false ) ),
				array( 'id' => $fieldsetId )
			);

			return $script.$input;
		}
	}
?>