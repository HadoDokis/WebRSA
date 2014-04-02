<?php
	/**
	 * Code source de la classe AjaxFichesprescriptions93Component.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller.Component
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AjaxComponent', 'Controller/Component' );

	/**
	 * La classe AjaxFichesprescriptions93Component ...
	 *
	 * @package app.Controller.Component
	 */
	class AjaxFichesprescriptions93Component extends AjaxComponent
	{
		/**
		 * Contrôleur utilisant ce component.
		 *
		 * @var Controller
		 */
		public $Controller = null;

		/**
		 * Paramètres de ce component
		 *
		 * @var array
		 */
		public $settings = array( );

		/**
		 * Components utilisés par ce component.
		 *
		 * @var array
		 */
		public $components = array( );

		public $fields = array(
			'Ficheprescription93.typethematiquefp93_id' => 'Thematiquefp93.type',
			'Ficheprescription93.thematiquefp93_id' => 'Thematiquefp93.id',
			'Ficheprescription93.categoriefp93_id' => 'Categoriefp93.id',
			'Ficheprescription93.filierefp93_id' => 'Filierefp93.id',
			'Ficheprescription93.prestatairefp93_id' => 'Prestatairefp93.id',
			'Ficheprescription93.actionfp93_id' => 'Actionfp93.id',
			'Ficheprescription93.numconvention' => 'Ficheprescription93.numconvention',
		);

		/**
		 * Traite l'événement Ajax d'un champ de formulaire ayant changé.
		 *
		 * @param array $data
		 * @return array
		 */
		public function ajaxOnChange( array $data ) {
			$data = $this->unprefixAjaxRequest( $data );
			$data['Target']['path'] = str_replace( '][', '.', preg_replace( '/^data\[(.*)\]$/', '\1', $data['Target']['name'] ) );
//$this->debug( $data['Target']['path'] );
			$value = Hash::get( $data, $data['Target']['path'] );

			// Suivant le niveau, on supprime les clés précédentes pour ne pas remettre à zéro
			$paths = array_keys( $this->fields );
			$current = array_search( $data['Target']['path'], $paths );
			$invertedPaths = array_flip( $paths );

			$fields = array();
			for( $i = $current + 1 ; $i < count($paths) ; $i++ ) {
				$fields[$paths[$i]] = array(
					'id' => Inflector::camelize( str_replace( '.', '_', $paths[$i] ) ),
					'value' => Hash::get( $data, $paths[$i] ),
					'type' => 'select',
					'options' => array()
				);
			}

			$fields['Ficheprescription93.numconvention']['type'] = 'text';
			if( 'Ficheprescription93.numconvention' !== $data['Target']['path'] ) {
				$fields['Ficheprescription93.numconvention']['value'] = null;
			}

			if( !empty( $value ) ) {
				// On sélectionne le type
				if( $current == $invertedPaths['Ficheprescription93.typethematiquefp93_id'] ) {
					$query = array(
						'conditions' => array(
							'Thematiquefp93.type' => Hash::get( $data, 'Ficheprescription93.typethematiquefp93_id' )
						)
					);
					$fields['Ficheprescription93.thematiquefp93_id']['options'] = $this->ajaxOptions( 'Thematiquefp93', $query );
				}

				// On sélectionne la thématique
				if( $current == $invertedPaths['Ficheprescription93.thematiquefp93_id'] ) {
					$query = array(
						'conditions' => array(
							'Categoriefp93.thematiquefp93_id' => Hash::get( $data, 'Ficheprescription93.thematiquefp93_id' )
						)
					);
					$fields['Ficheprescription93.categoriefp93_id']['options'] = $this->ajaxOptions( 'Categoriefp93', $query );
				}
				// On sélectionne la catégorie
				if( $current == $invertedPaths['Ficheprescription93.categoriefp93_id'] ) {
					$query = array(
						'conditions' => array(
							'Filierefp93.categoriefp93_id' => Hash::get( $data, 'Ficheprescription93.categoriefp93_id' )
						)
					);
					$fields['Ficheprescription93.filierefp93_id']['options'] = $this->ajaxOptions( 'Filierefp93', $query );
				}
				// On sélectionne la filière
				if( $current == $invertedPaths['Ficheprescription93.filierefp93_id'] ) {
					$query = array(
						'conditions' => array(
							'Actionfp93.filierefp93_id' => Hash::get( $data, 'Ficheprescription93.filierefp93_id' )
						)
					);
					$fields['Ficheprescription93.actionfp93_id']['options'] = $this->ajaxOptions( 'Actionfp93', $query );

					$query = array(
						'joins' => array(
							ClassRegistry::init( 'Prestatairefp93' )->join(
								'Actionfp93',
								array( 'type' => 'INNER' )
							)
						),
						'conditions' => array(
							'Actionfp93.filierefp93_id' => Hash::get( $data, 'Ficheprescription93.filierefp93_id' )
						)
					);
					$fields['Ficheprescription93.prestatairefp93_id']['options'] = $this->ajaxOptions( 'Prestatairefp93', $query );
				}
				// On sélectionne le prestataire
				// TODO
				if( $current == $invertedPaths['Ficheprescription93.prestatairefp93_id'] ) {
					$query = array(
						'conditions' => array(
							'Actionfp93.filierefp93_id' => Hash::get( $data, 'Ficheprescription93.filierefp93_id' ),
							'Actionfp93.prestatairefp93_id' => Hash::get( $data, 'Ficheprescription93.prestatairefp93_id' ),
						)
					);
					$fields['Ficheprescription93.actionfp93_id']['options'] = $this->ajaxOptions( 'Actionfp93', $query );
				}
				// On sélectionne l'action
				if( $current == $invertedPaths['Ficheprescription93.actionfp93_id'] ) {
					$result = ClassRegistry::init( 'Actionfp93' )->find(
						'first',
						array(
							'field' => array(
								'Actionfp93.prestatairefp93_id',
								'Actionfp93.numconvention',
							),
							'conditions' => array(
								'Actionfp93.id' => Hash::get( $data, 'Ficheprescription93.actionfp93_id' )
							)
						)
					);

					$fields['Ficheprescription93.numconvention']['value'] = Hash::get( $result, 'Actionfp93.numconvention' );
					$fields['Ficheprescription93.prestatairefp93_id']['value'] = Hash::get( $result, 'Actionfp93.prestatairefp93_id' );
				}
			}

			// Si on a un préfixe, on l'ajoute à ce que l'on retourne
			$fields = $this->prefixAjaxResult( $data['prefix'], $fields );

			return array( 'success' => true, 'fields' => $fields );
		}

		/**
		 * Traite l'événement Ajax du chargement de la page et de pré-remplissage
		 * de formulaire.
		 *
		 * @param array $data
		 * @return array
		 * @throws LogicException
		 */
		public function ajaxOnLoad( array $data ) {
			$return = array();
			$data = $this->unprefixAjaxRequest( $data );

			$fieldKeys = array_keys( $this->fields );
			foreach( $this->fields as $path => $field ) {
				$pathOffset = array_search( $path, $fieldKeys );

				if( $pathOffset === false ) {
					throw new LogicException();
				}

				$value = Hash::get( $data, $path );
				$elmt = array(
					'id' => Inflector::camelize( str_replace( '.', '_', $path ) ),
					'value' => $value,
					'type' => 'select',
					'options' => array()
				);

				if( $pathOffset === 0 ) {
					$types = ClassRegistry::init( 'Thematiquefp93' )->enum( 'type' );
					$options = array();
					foreach( $types as $id => $name ) {
						$options[] = compact( 'id', 'name' );
					}
					$elmt['options'] = $options;
				}
				else if( $path == 'Ficheprescription93.prestatairefp93_id' ) {
					$query = array(
						'joins' => array(
							ClassRegistry::init( 'Prestatairefp93' )->join( 'Actionfp93', array( 'type' => 'INNER' ) )
						),
						'conditions' => array(
							'Actionfp93.filierefp93_id' => Hash::get( $data, 'Ficheprescription93.filierefp93_id' )
						)
					);

					$elmt['options'] = $this->ajaxOptions( 'Prestatairefp93', $query );
				}
				else if( $path != 'Ficheprescription93.numconvention' ) { // TODO: la convention n'est pas gérée
					$parentPath = $fieldKeys[$pathOffset-1];
					$parentField = $this->fields[$parentPath];

					list( $modelName, $fieldName ) = model_field( $field );
					list( $parentModelName, $parentFieldName ) = model_field( $parentField );
					$query = array(
						'conditions' => array(
							$parentField => Hash::get( $data, $parentPath )
						)
					);

					if( $parentModelName !== $modelName ) {
						$query['joins'] = array(
							ClassRegistry::init( $modelName )->join( $parentModelName, array( 'type' => 'INNER' ) )
						);
					}

					$elmt['options'] = $this->ajaxOptions( $modelName, $query );
				}

				$return[$path] = $elmt;
			}

			$return = $this->prefixAjaxResult( $data['prefix'], $return );

			return array( 'success' => true, 'fields' => $return );
		}

		/**
		 *
		 * @param array $data
		 * @return array
		 */
		public function ajaxOnKeyup( array $data ) {
			$data = $this->unprefixAjaxRequest( $data );
			$data['Target']['path'] = str_replace( '][', '.', preg_replace( '/^data\[(.*)\]$/', '\1', $data['Target']['name'] ) );
//$this->debug( $data['Target']['path'] );
			$value = Hash::get( $data, $data['Target']['path'] );

			$Actionfp93 = ClassRegistry::init( 'Actionfp93' );

			$query = array(
				'fields' => array(
					'Actionfp93.id',
					'( UPPER( "Actionfp93"."numconvention" ) || \': \' || "Actionfp93"."name" ) AS "Actionfp93__name"',
				),
				'conditions' => array(
					'OR' => array(
						'UPPER( "Actionfp93"."numconvention" ) LIKE' => '%'.strtoupper( $value ).'%',
						'UPPER( "Actionfp93"."name" ) LIKE' => '%'.strtoupper( $value ).'%',
					)
				),
				'order' => array(
					'Actionfp93.numconvention ASC'
				)
			);

			if( trim( $value ) == '' ) {
				$query['conditions'] = '1 = 2';
			}

			$results = $Actionfp93->find( 'all', $query );

			$fields = array();

			if( trim( $value ) == '' ) {
				foreach( array_keys( $this->fields ) as $field ) {
					$fields[$field] = array(
						'id' => Inflector::camelize( str_replace( '.', '_', "{$data['prefix']}{$field}" ) ),
						'value' => null,
						'type' => 'select',
						'prefix' => $data['prefix'],
						'options' => array()
					);
				}

				// Cas particulier du premier élément de la liste
				$types = ClassRegistry::init( 'Thematiquefp93' )->enum( 'type' );
				$options = array();
				foreach( $types as $id => $name ) {
					$options[] = compact( 'id', 'name' );
				}
				$fields['Ficheprescription93.typethematiquefp93_id']['options'] = $options;
			}

			$fields['Ficheprescription93.numconvention'] = array(
				'id' => "{$data['prefix']}Ficheprescription93Numconvention",
				'value' => $value,
				'type' => 'ajax_select',
				'prefix' => $data['prefix'],
				'options' => Hash::extract( $results, '{n}.Actionfp93' )
			);

			return array( 'success' => true, 'fields' => $fields );
		}

		/**
		 * Retourne le json permettant de remplir les champs de la fiche de
		 * prescription.
		 *
		 * @param array $data
		 * @return array
		 */
		public function ajaxOnClick( array $data ) {
			$data = $this->unprefixAjaxRequest( $data );

			$Actionfp93 = ClassRegistry::init( 'Actionfp93' );

			$query = array(
				'fields' => array(
					'"Actionfp93"."numconvention" AS "Ficheprescription93__numconvention"',
					'"Actionfp93"."id" AS "Ficheprescription93__actionfp93_id"',
					'"Filierefp93"."id" AS "Ficheprescription93__filierefp93_id"',
					'"Prestatairefp93"."id" AS "Ficheprescription93__prestatairefp93_id"',
					'"Categoriefp93"."id" AS "Ficheprescription93__categoriefp93_id"',
					'"Thematiquefp93"."id" AS "Ficheprescription93__thematiquefp93_id"',
					'"Thematiquefp93"."type" AS "Ficheprescription93__typethematiquefp93_id"',
				),
				'joins' => array(
					$Actionfp93->join( 'Filierefp93', array( 'type' => 'INNER' ) ),
					$Actionfp93->join( 'Prestatairefp93', array( 'type' => 'INNER' ) ),
					$Actionfp93->Filierefp93->join( 'Categoriefp93', array( 'type' => 'INNER' ) ),
					$Actionfp93->Filierefp93->Categoriefp93->join( 'Thematiquefp93', array( 'type' => 'INNER' ) ),
				),
				'conditions' => array(
					'Actionfp93.id' => $data['value']
				)
			);

			$result = $Actionfp93->find( 'first', $query );
			// TODO: if empty...

			$prefix = Hash::get( $data, 'prefix' );
			if( !empty( $prefix ) ) {
				$result = array( $prefix => $result );
			}
			$result['prefix'] = $prefix;

			return $this->ajaxOnLoad( $result );
		}
	}
?>