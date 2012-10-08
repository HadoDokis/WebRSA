<?php
	/**
	 * Cette classe comprend des combinaisons de champs de formulaire de recherche.
	 */
	class SearchHelper extends AppHelper
	{
		public $helpers = array( 'Html', 'Xform', 'Form' );

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
				$this->Xform->input( $path, array( 'label' => false, 'type' => 'select', 'multiple' => 'checkbox', 'options' => $etatdossierpcg, 'fieldset' => false ) ),
				array( 'id' => $fieldsetId )
			);

			return $script.$input;
		}

		/**
		 * Retourne un fieldset de recherche par adresse contenant les champs suivants: Adresse.locaadr, Adresse.numcomptt
		 * et Canton.canton (si on utilise les cantons).
		 *
		 * @param array $mesCodesInsee La liste des codes INSEE pour remplir le menu déroulant
		 * @param array $cantons La liste des cantons pour remplir le menu déroulant
		 * @param string $prefix Le préfixe éventuel (ex.: Search)
		 * @return string
		 */
		public function blocAdresse( $mesCodesInsee, $cantons, $prefix = null ) {
			$prefix = ( !empty( $prefix ) ? "{$prefix}." : null );
			$content = $this->Xform->input( "{$prefix}Adresse.locaadr", array( 'label' => 'Commune de l\'allocataire ', 'type' => 'text' ) );
			$content .= $this->Xform->input( "{$prefix}Adresse.numcomptt", array( 'label' => 'Numéro de commune au sens INSEE', 'type' => 'select', 'options' => $mesCodesInsee, 'empty' => true ) );
			if( Configure::read( 'CG.cantons' ) ) {
				$content .= $this->Xform->input( "{$prefix}Canton.canton", array( 'label' => 'Canton', 'type' => 'select', 'options' => $cantons, 'empty' => true ) );
			}

			return $this->Html->tag(
				'fieldset',
				$this->Html->tag( 'legend', 'Recherche par Adresse' )
				.$content
			);
		}

		/**
		 * Retourne une fieldset de recherche par allocataire contenant les champs suivants: Personne.dtnai, Personne.nom,
		 * Personne.nomnai, Personne.prenom, Personne.nir, Personne.trancheage.
		 *
		 * @param array $trancheage La liste des tranches d'âge à utiliser dans le menu déroulant
		 * @param string $prefix Le préfixe éventuel (ex.: Search)
		 * @return string
		 */
		public function blocAllocataire( $trancheage = array(), $prefix = null ) {
			$prefix = ( !empty( $prefix ) ? "{$prefix}." : null );

			$content = $this->Xform->input( "{$prefix}Personne.dtnai", array( 'label' => 'Date de naissance', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'empty' => true ) );
			$content .= $this->Xform->input( "{$prefix}Personne.nom", array( 'label' => 'Nom' ) );
			$content .= $this->Xform->input( "{$prefix}Personne.nomnai", array( 'label' => 'Nom de jeune fille' ) );
			$content .= $this->Xform->input( "{$prefix}Personne.prenom", array( 'label' => 'Prénom' ) );
			$content .= $this->Xform->input( "{$prefix}Personne.nir", array( 'label' => 'NIR', 'maxlength' => 15 ) );

			if( !empty( $trancheage ) ) {
				$content .= $this->Xform->input( "{$prefix}Personne.trancheage", array( 'label' => 'Tranche d\'âge', 'options' => $trancheage, 'empty' => true ) );
			}

			return $this->Html->tag(
				'fieldset',
				$this->Html->tag( 'legend', 'Recherche par allocataire' )
				.$content
			);
		}

		/**
		 * Filtre par plage de dates.
		 *
		 * @see app/views/criteres/index.ctp
		 * @see app/views/cohortes/filtre.ctp
		 * @todo Dossier.dtdemrsa, ...
		 * @todo $options from.minYear, ....
		 *
		 * @param string $path
		 * @return string
		 */
		public function date( $path ) {
			$fieldsetId = $this->domId( $path ).'_from_to';

			$script = $this->_constuctObserve( $this->domId( $path ), $fieldsetId, false );

			list( $model, $field ) = model_field( $path);
			$domain = Inflector::underscore( $model );
			$fieldLabel = __d( $domain, "{$model}.{$field}", true );

			$input = $this->Xform->input( $path, array( 'label' => 'Filtrer par '.lcfirst( $fieldLabel ), 'type' => 'checkbox' ) );

			$input .= $this->Html->tag(
				'fieldset',
				$this->Html->tag( 'legend', $fieldLabel )
				.$this->Xform->input( $path.'_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120 ) )
				.$this->Xform->input( $path.'_to', array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 5, 'minYear' => date( 'Y' ) - 120 ) ),
				array( 'id' => $fieldsetId )
			);

			return $script.$input;
		}

		/**
		 * Fieldset contenant un checkbox permettant de préciser si l'on veut obtenir le nombre total de
		 * résultats.
		 *
		 * @param string $path
		 * @return string
		 */
		public function paginationNombretotal( $path = 'Pagination.nombre_total' ) {
			return $this->Html->tag(
				'fieldset',
				$this->Html->tag( 'legend', 'Comptage des résultats' )
				.$this->Xform->input( $path, array( 'label' =>  'Obtenir le nombre total de résultats (plus lent)', 'type' => 'checkbox' ) )
			);
		}

		/**
		 * Retourne une liste déroulante permettant de choisir si l'allocataire est soumis à droits et devoirs.
		 *
		 * @param array $toppersdrodevorsa
		 * @param string $path
		 * @return string
		 */
		public function toppersdrodevorsa( $toppersdrodevorsa, $path = 'Calculdroitrsa.toppersdrodevorsa' ) {
			list( $model, $field ) = model_field( $path );
			$domain = Inflector::underscore( $model );
			$fieldLabel = __d( $domain, "{$model}.{$field}", true );

			// INFO: ne pas passer par XformHelper, sinon 'Non défini' devient 'Non'
			return $this->Form->input(
				$path,
				array(
					'label' => $fieldLabel,
					'type' => 'select',
					'options' => $toppersdrodevorsa,
					'empty' => true,
				)
			);
		}
	}
?>