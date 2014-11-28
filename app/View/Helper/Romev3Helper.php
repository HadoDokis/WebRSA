<?php
	/**
	 * Code source de la classe Romev3Helper.
	 *
	 * PHP 5.3
	 *
	 * @package app.View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Romev3Helper ...
	 *
	 * @package app.View.Helper
	 */
	class Romev3Helper extends AppHelper
	{
		/**
		 * Helpers utilisés.
		 *
		 * @var array
		 */
		public $helpers = array(
			'Ajax2' => array(
				'className' => 'Prototype.PrototypeAjax',
				'useBuffer' => false
			),
			'Default3' => array(
				'className' => 'Default.DefaultDefault',
				'useBuffer' => false
			),
			'Observer' => array(
				'className' => 'Prototype.PrototypeObserver',
				'useBuffer' => false
			)
		);

		/**
		 * Retourne les fieldset pour un alias du modèle Entreeromev3, utilisables
		 * dans le moteur de recherche par Dsp ainsi que dans le formulaire d'ajout
		 * ou de modification, si la variable de configuration Romev3.enabled est
		 * à true.
		 *
		 * Le fieldset contient un champ permettant de faire une recherche partielle
		 * (en Ajax, voir le paramètre URL) sur l'appellation et de remplir
		 * automatiquement les champs de type liste déroulante.
		 * Il contient un champ de type liste déroulante (liée) pour chacun des
		 * niveaux (fameille, domaine, métier, appellation).
		 *
		 * @param string $modelName L'alias du modèle Entreeromev3
		 * @param array $params Voir les clés domain, options, id, fieldset, url et required
		 * @return string
		 */
		public function fieldset( $modelName, array $params = array() ) {
			$params += array(
				'domain' => $this->request->params['controller'],
				'options' => array(),
				'id' => null,
				'fieldset' => true,
				'url' => array( 'controller' => 'cataloguesromesv3', 'action' => 'ajax_appellation' ),
				'required' => false
			);
			$return = '';

			if( Configure::read( 'Romev3.enabled' ) ) {
				$fieldsetPath = implode( '.', Hash::filter( array( $modelName ) ) );
				$ajaxFieldPath = "{$modelName}.romev3";

				if( $params['id'] === null ) {
					$params['id'] = $this->domId( $fieldsetPath );
				}

				$return .= $this->Default3->subform(
					array(
						$ajaxFieldPath => array( 'type' => 'text', 'required' => false ),
						"{$modelName}.id" => array( 'type' => 'hidden' ),
						"{$modelName}.familleromev3_id" => array( "options" => $params['options'][$modelName]["familleromev3_id"], 'empty' => true, 'required' => $params['required'] ),
						"{$modelName}.domaineromev3_id" => array( "options" => $params['options'][$modelName]["domaineromev3_id"], 'empty' => true, 'required' => $params['required'] ),
						"{$modelName}.metierromev3_id" => array( "options" => $params['options'][$modelName]["metierromev3_id"], 'empty' => true, 'required' => $params['required'] ),
						"{$modelName}.appellationromev3_id" => array( "options" => $params['options'][$modelName]["appellationromev3_id"], 'empty' => true, 'required' => $params['required'] )
					),
					array(
						"options" => $params['options']
					)
				);

				$return .= $this->Observer->dependantSelect(
					array(
						"{$modelName}.familleromev3_id" => "{$modelName}.domaineromev3_id",
						"{$modelName}.domaineromev3_id" => "{$modelName}.metierromev3_id",
						"{$modelName}.metierromev3_id" => "{$modelName}.appellationromev3_id",
					)
				);

				//
				$return .= $this->Ajax2->observe(
					array(
						$ajaxFieldPath => array( 'event' => 'keyup' )
					),
					array(
						'url' => $params['url'],
						'onload' => false
					)
				);

				if( Hash::get( $params, 'fieldset' ) ) {
					$return = $this->Default3->DefaultHtml->tag(
						'fieldset',
						$this->Default3->DefaultHtml->tag( 'legend', __d( $params['domain'], $fieldsetPath ) )
						.$return
					);
				}
			}

			return $return;
		}

		/**
		 * Retourne la liste des champs de type name pour une utilisation avec
		 * Default2Helper ou DefaultDefaultHelper (Default3), si la variable de
		 * configuration Romev3.enabled est à true.
		 *
		 * Utilisé dans la visualisation d'une Dsp.
		 *
		 * @param string $modelName
		 * @param array $params
		 * @return array
		 */
		public function fields( $modelName, array $params = array() ) {
			$params += array(
				'domain' => $this->request->params['controller']
			);
			$fields = array();

			if( Configure::read( 'Romev3.enabled' ) ) {
				foreach( array( 'famille', 'domaine', 'metier', 'appellation' ) as $suffix ) {
					$path = preg_replace( '/romev3$/', '', $modelName )."{$suffix}romev3.name";
					$label = __d( $params['domain'], preg_replace( '/romev3$/', '', $modelName )."{$suffix}romev3Rev.name" );
					$fields[$path] = array( 'label' => $label, 'type' => 'text' );
				}
			}

			return $fields;
		}
	}
?>