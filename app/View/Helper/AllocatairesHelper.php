<?php
	/**
	 * Code source de la classe AllocatairesHelper.
	 *
	 * @package app.View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe AllocatairesHelper ...
	 *
	 * @todo Attention aux traductions (domaine allocataires ?)
	 *
	 * @package app.View.Helper
	 */
	class AllocatairesHelper extends AppHelper
	{
		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Search.SearchForm',
			'Xform',
			'Xhtml',
		);

		/**
		 * Permet de savoir si un champ doit être affiché ou non, suivant les
		 * champs présents dans l'attribut 'skip' des paramètres.
		 *
		 * Utilisé dans les méthodes blocDossier(), blocAdresse() et blocAllocataire().
		 *
		 * @param string $path
		 * @param array $params
		 * @return boolean
		 */
		protected function _isSkipped( $path, array $params = array() ) {
			if( isset( $params['skip'] ) && in_array( $path, (array)$params['skip'] ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Retourne une groupe de filtres par dossier contenant les champs:
		 *	- Dossier.numdemrsa
		 *	- Dossier.matricule
		 *	- Dossier.dtdemrsa
		 *	- Situationdossierrsa.etatdosrsa
		 *	- Dossier.dernier
		 *
		 * @param array $params
		 * @return string
		 */
		public function blocDossier( array $params = array() ) {
			$default = array(
				'prefix' => 'Search',
				'domain' => 'search_plugin',
				'options' => array(),
				'fieldset' => true,
				// TODO: documenter, tests
				'skip' => array(),
			);

			$params = $params + $default;
			$options = $params['options'];

			$prefix = ( !empty( $params['prefix'] ) ? "{$params['prefix']}." : null );

			$content = '';

			if( !$this->_isSkipped( "{$prefix}Dossier.numdemrsa", $params ) ) {
				$content .= $this->Xform->input( "{$prefix}Dossier.numdemrsa", array( 'label' => 'Numéro de dossier RSA' ) );
			}

			if( !$this->_isSkipped( "{$prefix}Dossier.matricule", $params ) ) {
				$content .= $this->Xform->input( "{$prefix}Dossier.matricule", array( 'label' => 'Numéro CAF' ) );
			}

			if( !$this->_isSkipped( "{$prefix}Dossier.dtdemrsa", $params ) ) {
				$content .= $this->SearchForm->dateRange( "{$prefix}Dossier.dtdemrsa" );
			}

			if( !$this->_isSkipped( "{$prefix}Situationdossierrsa.etatdosrsa", $params ) && Hash::check( $options, 'Situationdossierrsa.etatdosrsa' ) ) {
				$dependantCheckboxesParams = array(
					'options' => (array)Hash::get( $options, 'Situationdossierrsa.etatdosrsa' ),
					'domain' => 'search_plugin',
				);
				$content .= $this->SearchForm->dependantCheckboxes( "{$prefix}Situationdossierrsa.etatdosrsa", $dependantCheckboxesParams );
			}

			if( !$this->_isSkipped( "{$prefix}Situationdossierrsa.etatdosrsa", $params ) ) {
				$content .= $this->Xform->input( "{$prefix}Dossier.dernier", array( 'label' => 'Uniquement la dernière demande RSA pour un même allocataire', 'type' => 'checkbox' ) );
			}

			if( !$params['fieldset'] ) {
				return $content;
			}
			else {
				return $this->Xhtml->tag(
					'fieldset',
					$this->Xhtml->tag( 'legend', __d( $params['domain'], 'Search.Dossier' ) )
					.$content
				);
			}
		}

		/**
		 * Retourne une groupe de filtres par adresse contenant les champs:
		 *	- Adresse.nomvoie
		 *	- Adresse.locaadr
		 *	- Adresse.numcomptt
		 *	- Canton.canton
		 *
		 * @param array $params
		 * @return string
		 */
		public function blocAdresse( array $params = array() ) {
			$default = array(
				'prefix' => 'Search',
				'domain' => 'search_plugin',
				'options' => array(),
				'fieldset' => true
			);

			$params = $params + $default;
			$options = $params['options'];

			$prefix = ( !empty( $params['prefix'] ) ? "{$params['prefix']}." : null );

			$content = '';

			if( !$this->_isSkipped( "{$prefix}Adresse.nomvoie", $params ) ) {
				$content .= $this->Xform->input( "{$prefix}Adresse.nomvoie", array( 'label' => 'Nom de voie de l\'allocataire ', 'type' => 'text' ) );
			}

			if( !$this->_isSkipped( "{$prefix}Adresse.locaadr", $params ) ) {
				$content .= $this->Xform->input( "{$prefix}Adresse.locaadr", array( 'label' => 'Commune de l\'allocataire ', 'type' => 'text' ) );
			}

			if( !$this->_isSkipped( "{$prefix}Adresse.numcomptt", $params ) ) {
				$content .= $this->Xform->input( "{$prefix}Adresse.numcomptt", array( 'label' => 'Numéro de commune au sens INSEE', 'type' => 'select', 'options' => (array)Hash::get( $options, 'Adresse.numcomptt' ), 'empty' => true ) );
			}

			if( Configure::read( 'CG.cantons' ) && Hash::check( $options, 'Canton.canton' ) && !$this->_isSkipped( "{$prefix}Canton.canton", $params ) ) {
				$content .= $this->Xform->input( "{$prefix}Canton.canton", array( 'label' => 'Canton', 'type' => 'select', 'options' => (array)Hash::get( $options, 'Canton.canton' ), 'empty' => true ) );
			}

			if( !$params['fieldset'] ) {
				return $content;
			}
			else {
				return $this->Xhtml->tag(
					'fieldset',
					$this->Xhtml->tag( 'legend', __d( $params['domain'], 'Search.Adresse' ) )
					.$content
				);
			}
		}

		/**
		 * Retourne une groupe de filtres par allocataire contenant les champs:
		 *	- Personne.dtnai
		 *	- Personne.nom
		 *	- Personne.nomnai
		 *	- Personne.prenom
		 *	- Personne.nir
		 *	- Personne.sexe
		 *	- Personne.trancheage
		 *	- Calculdroitrsa.toppersdrodevorsa
		 *
		 * @param array $params
		 * @return string
		 */
		public function blocAllocataire( array $params = array() ) {
			$default = array(
				'prefix' => 'Search',
				'domain' => 'search_plugin',
				'options' => array(),
				'fieldset' => true
			);

			$params = $params + $default;
			$options = $params['options'];

			$prefix = ( !empty( $params['prefix'] ) ? "{$params['prefix']}." : null );

			$content = '';

			if( !$this->_isSkipped( "{$prefix}Personne.dtnai", $params ) ) {
				$content .= $this->Xform->input( "{$prefix}Personne.dtnai", array( 'label' => 'Date de naissance', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'empty' => true ) );
			}

			if( !$this->_isSkipped( "{$prefix}Personne.nom", $params ) ) {
				$content .= $this->Xform->input( "{$prefix}Personne.nom", array( 'label' => 'Nom' ) );
			}

			if( !$this->_isSkipped( "{$prefix}Personne.nomnai", $params ) ) {
				$content .= $this->Xform->input( "{$prefix}Personne.nomnai", array( 'label' => 'Nom de jeune fille' ) );
			}

			if( !$this->_isSkipped( "{$prefix}Personne.prenom", $params ) ) {
				$content .= $this->Xform->input( "{$prefix}Personne.prenom", array( 'label' => 'Prénom' ) );
			}

			if( !$this->_isSkipped( "{$prefix}Personne.nir", $params ) ) {
				$content .= $this->Xform->input( "{$prefix}Personne.nir", array( 'label' => 'NIR', 'maxlength' => 15 ) );
			}

			if( !$this->_isSkipped( "{$prefix}Personne.sexe", $params ) && Hash::check( $options, 'Personne.sexe' ) ) {
				$content .= $this->Xform->input( "{$prefix}Personne.sexe", array( 'label' => 'Sexe', 'options' => (array)Hash::get( $options, 'Personne.sexe' ), 'empty' => true ) );
			}

			if( !$this->_isSkipped( "{$prefix}Personne.trancheage", $params ) && Hash::check( $options, 'Personne.trancheage' ) ) {
				$content .= $this->Xform->input( "{$prefix}Personne.trancheage", array( 'label' => 'Tranche d\'âge', 'options' => (array)Hash::get( $options, 'Personne.trancheage' ), 'empty' => true ) );
			}

			if( !$this->_isSkipped( "{$prefix}Calculdroitrsa.toppersdrodevorsa", $params ) ) {
				$content .= $this->Xform->input( "{$prefix}Calculdroitrsa.toppersdrodevorsa", array( 'label' => 'Personne soumise à droits et devoirs ?', 'options' => (array)Hash::get( $options, 'Calculdroitrsa.toppersdrodevorsa' ), 'empty' => true ) );
			}

			if( !$params['fieldset'] ) {
				return $content;
			}
			else {
				return $this->Xhtml->tag(
					'fieldset',
					$this->Xhtml->tag( 'legend', __d( $params['domain'], 'Search.Personne' ) )
					.$content
				);
			}
		}

		/**
		 * Retourne une groupe de filtres par référent du parcours contenant les champs:
		 *	- PersonneReferent.structurereferente_id
		 *	- PersonneReferent.referent_id
		 *
		 * @param array $params
		 * @return string
		 */
		public function blocReferentparcours( array $params = array() ) {
			$default = array(
				'prefix' => 'Search',
				'domain' => 'search_plugin',
				'options' => array(),
				'fieldset' => true
			);

			$params = $params + $default;
			$options = $params['options'];

			$prefix = ( !empty( $params['prefix'] ) ? "{$params['prefix']}." : null );

			$script = "document.observe( 'dom:loaded', function() {
				dependantSelect( '".$this->domId( "{$prefix}PersonneReferent.referent_id" )."', '".$this->domId( "{$prefix}PersonneReferent.structurereferente_id" )."' );
			} );";

			$content = $this->Xform->input( "{$prefix}PersonneReferent.structurereferente_id", array( 'label' => __d( 'search_plugin', 'Structurereferenteparcours.lib_struc' ), 'type' => 'select', 'options' => (array)Hash::get( $options, 'PersonneReferent.structurereferente_id' ), 'empty' => true ) );
			$content .= $this->Xform->input( "{$prefix}PersonneReferent.referent_id", array( 'label' => __d( 'search_plugin', 'Referentparcours.nom_complet' ), 'type' => 'select', 'options' => (array)Hash::get( $options, 'PersonneReferent.referent_id' ), 'empty' => true ) );
			$content .= $this->Xhtml->scriptBlock( $script, array( 'inline' => true, 'safe' => false ) );

			if( !$params['fieldset'] ) {
				return $content;
			}
			else {
				return $this->Xhtml->tag(
					'fieldset',
					$this->Xhtml->tag( 'legend', __d( $params['domain'], 'Search.Referentparcours' ) )
					.$content
				);
			}
		}

		/**
		 * Retourne une groupe de contrôles de la pagination contenant le champ:
		 *	- Pagination.nombre_total
		 *
		 * @param array $params
		 * @return string
		 */
		public function blocPagination( array $params = array() ) {
			$default = array(
				'prefix' => 'Search',
				'domain' => 'search_plugin',
				'options' => array(),
				'fieldset' => true
			);

			$params = $params + $default;
			$options = $params['options'];

			$prefix = ( !empty( $params['prefix'] ) ? "{$params['prefix']}." : null );

			$content = $this->Xform->input( "{$prefix}Pagination.nombre_total", array( 'label' =>  __d( $params['domain'], 'Search.Pagination.nombre_total' ), 'type' => 'checkbox' ) );

			if( !$params['fieldset'] ) {
				return $content;
			}
			else {
				return $this->Xhtml->tag(
					'fieldset',
					$this->Xhtml->tag( 'legend', __d( $params['domain'], 'Search.Pagination' ) )
					.$content
				);
			}
		}

		/**
		 * Retourne un ensemble de scripts pour un formulaire permettant:
		 *	- de cacher le formulaire de recherche
		 *	- de désactiver le bouton de soumission à l'envoi du formulaire
		 *
		 * @param array $params
		 * @return string
		 */
		public function blocScript( array $params = array() ) {
			$default = array(
				'id' => Inflector::camelize( "{$this->request->params['controller']}_{$this->request->params['action']}_form" ),
				'prefix' => 'Search',
				'domain' => 'search_plugin',
			);

			$params = $params + $default;

			$content = '';

			if( ( isset( $this->request->data[$params['prefix']] ) && !empty( $this->request->params['named'] ) ) ) {
				$out = "document.observe( 'dom:loaded', function() { \$('{$params['id']}').hide(); } );";
				$content .= $this->Xhtml->scriptBlock( $out );
			}

			$content .= $this->SearchForm->observeDisableFormOnSubmit( $params['id'] );

			return $content;
		}
	}
?>