<?php
	/**
	 * Code source de la classe AllocatairesHelper.
	 *
	 * @package app.View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe AllocatairesHelper fournit des blocs de champs pour construire
	 * des moteurs de recherche concernant les allocataires du RSA.
	 *
	 * Chaque méthode accepte une clé 'skip' dans les paramètres, permettant de
	 * ne pas obtenir un ou plusieurs champs du bloc.
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
		 * Les paramètres par défaut utilisés dans chacune des méthodes.
		 *
		 * @var array
		 */
		public $default = array(
			'prefix' => 'Search',
			'domain' => 'search_plugin',
			'options' => array(),
			'fieldset' => true,
			'skip' => array(),
		);

		/**
		 * Surcharge du constructeur avec possibilité de choisir les paramètres
		 * par défaut.
		 *
		 * @param View $View
		 * @param array $settings
		 */
		public function __construct( View $View, $settings = array( ) ) {
			parent::__construct( $View, $settings );
			$this->default = $settings + $this->default;
		}

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
		 * Retourne le contenu, éventuellement entouré d'un fieldset (suivant la
		 * valeur de la clé fieldset dans les paramètres) dont la légende
		 * sera traduite dans le domaine spécifié par la clé domaine des paramètres.
		 *
		 * @param string $legend L texte de la légende du fieldset
		 * @param string $content Le contenu
		 * @param array $params Les paramètres
		 * @return string
		 */
		protected function _fieldset( $legend, $content, array $params = array() ) {
			if( !$params['fieldset'] ) {
				return $content;
			}
			else {
				return $this->Xhtml->tag(
					'fieldset',
					$this->Xhtml->tag( 'legend', __d( $params['domain'], $legend ) )
					.$content
				);
			}

			return $content;
		}

		/**
		 * Retourne le résultat de Xform::input() si le champ ne se trouve pas
		 * dans la clé skip des paramètres.
		 *
		 * @param string $path Le chemin préfixé du champ
		 * @param array $params Les paramètres de l'appel à la méthode blocXXX()
		 * @param array $inputParams Les paramètres spécifiques à l'input
		 * @return string
		 */
		protected function _input( $path, array $params, array $inputParams = array() ) {
			if( !$this->_isSkipped( $path, $params ) ) {
				if( !isset( $inputParams['domain'] ) ) {
					$inputParams['domain'] = $params['domain'];
				}

				if( !isset( $inputParams['label'] ) ) {
					$inputParams['label'] = __d( $params['domain'], $path );
				}

				return $this->Xform->input( $path, $inputParams );
			}

			return null;
		}

		/**
		 * Retourne le résultat de SearchForm::dependantCheckboxes() si le champ
		 * ne se trouve pas dans la clé skip des paramètres.
		 *
		 * @param string $path Le chemin préfixé du champ
		 * @param array $params La clé skip est utilisée
		 * @param array $inputParams Les clés domain et options sont utilisées
		 * @return string
		 */
		protected function _dependantCheckboxes( $path, array $params, array $inputParams = array() ) {
			if( !$this->_isSkipped( $path, $params ) ) {
				if( !isset( $inputParams['domain'] ) ) {
					$inputParams['domain'] = $params['domain'];
				}

				return $this->SearchForm->dependantCheckboxes( $path, $inputParams );
			}

			return null;
		}

		/**
		 * Retourne le résultat de SearchForm::dateRange() si le champ
		 * ne se trouve pas dans la clé skip des paramètres.
		 *
		 * @param string $path Le chemin préfixé du champ
		 * @param array $params La clé skip est utilisée, domain est copiée
		 * @param array $inputParams Les clés domain et options sont utilisées
		 * @return string
		 */
		protected function _dateRange( $path, array $params, array $inputParams = array() ) {
			if( !$this->_isSkipped( $path, $params ) ) {
				if( !isset( $inputParams['domain'] ) ) {
					$inputParams['domain'] = $params['domain'];
				}

				return $this->SearchForm->dateRange( $path, $inputParams );
			}

			return null;
		}

		/**
		 * Retourne une groupe de filtres par dossier contenant les champs:
		 *	- Dossier.numdemrsa
		 *	- Dossier.matricule
		 *	- Dossier.dtdemrsa
		 *	- Situationdossierrsa.etatdosrsa
		 *	- Dossier.dernier
		 *	- Dossier.anciennete_dispositif
		 *	- Serviceinstructeur.id
		 *	- Dossier.fonorg
		 *	- Foyer.sitfam
		 *
		 * @param array $params
		 * @return string
		 */
		public function blocDossier( array $params = array() ) {
			$params = $params + $this->default;
			$params['prefix'] = ( !empty( $params['prefix'] ) ? "{$params['prefix']}." : null );

			$content = $this->_input( "{$params['prefix']}Dossier.numdemrsa", $params );
			$content .= $this->_input( "{$params['prefix']}Dossier.matricule", $params );
			$content .= $this->_dateRange( "{$params['prefix']}Dossier.dtdemrsa", $params );

			if( Hash::check( $params, 'options.Situationdossierrsa.etatdosrsa' ) ) {
				$content .= $this->_dependantCheckboxes( "{$params['prefix']}Situationdossierrsa.etatdosrsa", $params, array( 'options' => (array)Hash::get( $params, 'options.Situationdossierrsa.etatdosrsa' ) ) );
			}

			if( Hash::check( $params, 'options.Detailcalculdroitrsa.natpf' ) ) {
				$content .= $this->_dependantCheckboxes( "{$params['prefix']}Detailcalculdroitrsa.natpf", $params, array( 'options' => (array)Hash::get( $params, 'options.Detailcalculdroitrsa.natpf' ) ) );
			}

			$content .= $this->_input( "{$params['prefix']}Dossier.dernier", $params, array( 'type' => 'checkbox' ) );

			$paths = array(
				'Dossier.anciennete_dispositif',
				'Serviceinstructeur.id',
				'Dossier.fonorg',
				'Foyer.sitfam'
			);
			foreach( $paths as $path ) {
				if( Hash::check( $params, "options.{$path}" ) ) {
					$content .= $this->_input( "{$params['prefix']}{$path}", $params, array( 'type' => 'select', 'options' => (array)Hash::get( $params, "options.{$path}" ), 'empty' => true ) );
				}
			}

			return $this->_fieldset( 'Search.Dossier', $content, $params );
		}

		/**
		 * Retourne une groupe de filtres par adresse contenant les champs:
		 *	- Adresse.nomvoie
		 *	- Adresse.nomcom
		 *	- Adresse.numcom
		 *	- Canton.canton
		 *	- Sitecov58.name
		 *
		 * @param array $params
		 * @return string
		 */
		public function blocAdresse( array $params = array() ) {
			$params = $params + $this->default;
			$params['prefix'] = ( !empty( $params['prefix'] ) ? "{$params['prefix']}." : null );

			$content = $this->_input( "{$params['prefix']}Adresse.nomvoie", $params, array( 'type' => 'text' ) );
			$content .= $this->_input( "{$params['prefix']}Adresse.nomcom", $params, array( 'type' => 'text' ) );
			$content .= $this->_input( "{$params['prefix']}Adresse.numcom", $params, array( 'type' => 'select', 'options' => (array)Hash::get( $params, 'options.Adresse.numcom' ), 'empty' => true ) );

			if( Configure::read( 'CG.cantons' ) ) {
				$content .= $this->_input( "{$params['prefix']}Canton.canton", $params, array( 'type' => 'select', 'options' => (array)Hash::get( $params, 'options.Canton.canton' ), 'empty' => true ) );
			}

			if( Configure::read( 'Cg.departement' ) == 58 ) {
				$content .= $this->_input( "{$params['prefix']}Sitecov58.id", $params, array( 'type' => 'select', 'options' => (array)Hash::get( $params, 'options.Sitecov58.id' ), 'empty' => true ) );
			}

			return $this->_fieldset( 'Search.Adresse', $content, $params );
		}

		/**
		 * Retourne une groupe de filtres par allocataire contenant les champs:
		 *	- Personne.dtnai
		 *	- Personne.nom
		 *	- Personne.nomnai
		 *	- Personne.prenom
		 *	- Personne.nir
		 *	- Personne.sexe
		 *	- Personne.trancheage (si les options sont renseignées)
		 *	- Calculdroitrsa.toppersdrodevorsa
		 *
		 * @param array $params
		 * @return string
		 */
		public function blocAllocataire( array $params = array() ) {
			$params = $params + $this->default;
			$params['prefix'] = ( !empty( $params['prefix'] ) ? "{$params['prefix']}." : null );

			$content = $this->_input( "{$params['prefix']}Personne.dtnai", $params, array( 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'empty' => true ) );
			$content .= $this->_input( "{$params['prefix']}Personne.nom", $params );
			$content .= $this->_input( "{$params['prefix']}Personne.nomnai", $params );
			$content .= $this->_input( "{$params['prefix']}Personne.prenom", $params );
			$content .= $this->_input( "{$params['prefix']}Personne.nir", $params, array( 'maxlength' => 15 ) );
			$content .= $this->_input( "{$params['prefix']}Personne.sexe", $params, array( 'options' => (array)Hash::get( $params, 'options.Personne.sexe' ), 'empty' => true ) );
			if( Hash::check( $params, 'options.Personne.trancheage' ) ) {
				$content .= $this->_input( "{$params['prefix']}Personne.trancheage", $params, array( 'options' => (array)Hash::get( $params, 'options.Personne.trancheage' ), 'empty' => true ) );
			}
			$content .= $this->_input( "{$params['prefix']}Calculdroitrsa.toppersdrodevorsa", $params, array( 'options' => (array)Hash::get( $params, 'options.Calculdroitrsa.toppersdrodevorsa' ), 'empty' => true ) );

			return $this->_fieldset( 'Search.Personne', $content, $params );
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
			$params = $params + $this->default;
			$params['prefix'] = ( !empty( $params['prefix'] ) ? "{$params['prefix']}." : null );

			$script = "document.observe( 'dom:loaded', function() {
				dependantSelect( '".$this->domId( "{$params['prefix']}PersonneReferent.referent_id" )."', '".$this->domId( "{$params['prefix']}PersonneReferent.structurereferente_id" )."' );
			} );";

			$domain_search_plugin = ( Configure::read( 'Cg.departement' ) == 93 ) ? 'search_plugin_93' : 'search_plugin';

			$content = $this->Xform->input( "{$params['prefix']}PersonneReferent.structurereferente_id", array( 'label' => __d( $domain_search_plugin, 'Structurereferenteparcours.lib_struc' ), 'type' => 'select', 'options' => (array)Hash::get( $params, 'options.PersonneReferent.structurereferente_id' ), 'empty' => true ) );
			$content .= $this->Xform->input( "{$params['prefix']}PersonneReferent.referent_id", array( 'label' => __d( $domain_search_plugin, 'Referentparcours.nom_complet' ), 'type' => 'select', 'options' => (array)Hash::get( $params, 'options.PersonneReferent.referent_id' ), 'empty' => true ) );
			$content .= $this->Xhtml->scriptBlock( $script, array( 'inline' => true, 'safe' => false ) );

			return $this->_fieldset( 'Search.Referentparcours', $content, $params );
		}

		/**
		 * Retourne une groupe de contrôles de la pagination contenant le champ:
		 *	- Pagination.nombre_total
		 *
		 * @param array $params
		 * @return string
		 */
		public function blocPagination( array $params = array() ) {
			$params = $params + $this->default;
			$params['prefix'] = ( !empty( $params['prefix'] ) ? "{$params['prefix']}." : null );

			$content = $this->Xform->input( "{$params['prefix']}Pagination.nombre_total", array( 'label' =>  __d( $params['domain'], 'Search.Pagination.nombre_total' ), 'type' => 'checkbox' ) );

			return $this->_fieldset( 'Search.Pagination', $content, $params );
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
				'prefix' => $this->default['prefix'],
				'domain' => $this->default['domain'],
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