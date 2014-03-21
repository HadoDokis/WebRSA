<?php
	/**
	 * Code source de la classe Cataloguepdifp93.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AbstractSearch', 'Model/Abstractclass' );

	/**
	 * La classe Cataloguepdifp93 ...
	 *
	 * @package app.Model
	 */
	class Cataloguepdifp93 extends AbstractSearch
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Cataloguepdifp93';

		/**
		 * Ce modèle n'est pas lié à une table.
		 *
		 * @var boolean
		 */
		public $useTable = false;

		/**
		 * Liste des modèles disponibles dans le paramétrage.
		 *
		 * @var array
		 */
		public $modelesParametrages = array(
			'Thematiquefp93',
			'Categoriefp93',
			'Filierefp93',
			'Actionfp93',
			'Prestatairefp93',
			'Modtransmfp93',
			'Adresseprestatairefp93',
			'Motifnonreceptionfp93',
			'Motifnonretenuefp93',
			'Motifnonsouhaitfp93',
			'Motifnonintegrationfp93',
			'Documentbeneffp93'
		);

		/**
		 *
		 * @param array $types Le nom du modèle => le type de jointure
		 * @return array
		 */
		public function searchQuery( array $types = array() ) {
			$Thematiquefp93 = ClassRegistry::init( 'Thematiquefp93' );

			$types += array(
				'Categoriefp93' => 'LEFT OUTER',
				'Filierefp93' => 'LEFT OUTER',
				'Actionfp93' => 'LEFT OUTER',
				'Prestatairefp93' => 'LEFT OUTER',
			);

			$cacheKey = Inflector::underscore( $Thematiquefp93->useDbConfig ).'_'.Inflector::underscore( $this->alias ).'_'.Inflector::underscore( __FUNCTION__ ).'_'.sha1( serialize( $types ) );
			$query = Cache::read( $cacheKey );

			if( $query === false ) {
				$query = array(
					'fields' => array(
						'Thematiquefp93.type',
						'Thematiquefp93.name',
						'Filierefp93.name',
						'Prestatairefp93.name',
						'Actionfp93.id',
						'Actionfp93.annee',
						'Actionfp93.name',
						'Actionfp93.actif',
					),
					'joins' => array(
						$Thematiquefp93->join( 'Categoriefp93', array( 'type' => $types['Categoriefp93'] ) ),
						$Thematiquefp93->Categoriefp93->join( 'Filierefp93', array( 'type' => $types['Filierefp93'] ) ),
						$Thematiquefp93->Categoriefp93->Filierefp93->join( 'Actionfp93', array( 'type' => $types['Actionfp93'] ) ),
						$Thematiquefp93->Categoriefp93->Filierefp93->Actionfp93->join( 'Prestatairefp93', array( 'type' => $types['Prestatairefp93'] ) ),
					),
					'conditions' => array(),
					'order' => array(
						'Thematiquefp93.type',
						'Thematiquefp93.name',
						'Filierefp93.name',
						'Prestatairefp93.name',
						'Actionfp93.annee',
						'Actionfp93.name',
						'Actionfp93.actif',
					)
				);

				// Enregistrement dans le cache
				Cache::write( $cacheKey, $query );
			}

			return $query;
		}

		/**
		 * Complète les conditions du querydata avec le contenu des filtres de
		 * recherche.
		 *
		 * @param array $query
		 * @param array $search
		 * @return array
		 */
		public function searchConditions( array $query, array $search ) {
			// 1. Valeurs exactes
			$paths = array(
				'Thematiquefp93.type',
				'Actionfp93.annee',
				'Actionfp93.actif',
			);
			foreach( $paths as $path ) {
				$value = trim( Hash::get( $search, $path ) );
				if( $value != '' ) {
					$query['conditions'][$path] = $value;
				}
			}

			// 2. Valeurs approchantes
			$paths = array(
				'Thematiquefp93.name',
				'Filierefp93.name',
				'Prestatairefp93.name',
				'Actionfp93.name',
				'Actionfp93.numconvention',
			);
			foreach( $paths as $path ) {
				$value = trim( Hash::get( $search, $path ) );
				if( $value != '' ) {
					$query['conditions']["{$path} ILIKE"] = "%{$value}%";
				}
			}

			return $query;
		}

		/**
		 * Retourne les options nécessaires au formulaire de recherche, aux
		 * impressions, ...
		 *
		 * @param array $params
		 * @return array
		 */
		public function options( array $params = array() ) {
			$Thematiquefp93 = ClassRegistry::init( 'Thematiquefp93' );

			$options = Hash::merge(
				$Thematiquefp93->enums(),
				$Thematiquefp93->Categoriefp93->enums(),
				$Thematiquefp93->Categoriefp93->Filierefp93->enums(),
				$Thematiquefp93->Categoriefp93->Filierefp93->Actionfp93->enums(),
				$Thematiquefp93->Categoriefp93->Filierefp93->Actionfp93->Prestatairefp93->enums()
			);

			return $options;
		}

		public function jsonDependantSelect( array $data ) {
			$Ficheprescription93 = ClassRegistry::init( 'Ficheprescription93' );

			// Un default où on va piocher
			$emptyFields = array(
				'Ficheprescription93.numconvention' => array(
					'id' => 'Ficheprescription93Numconvention',
					'value' => null
				),
				'Thematiquefp93.type' => array(
					'id' => 'Thematiquefp93Type',
					'value' => null,
					'type' => 'select',
					'options' => array()
				),
				'Categoriefp93.thematiquefp93_id' => array(
					'id' => 'Categoriefp93Thematiquefp93Id',
					'value' => null,
					'type' => 'select',
					'options' => array()
				),
				'Filierefp93.categoriefp93_id' => array(
					'id' => 'Filierefp93Categoriefp93Id',
					'value' => null,
					'type' => 'select',
					'options' => array()
				),
				'Actionfp93.filierefp93_id' => array(
					'id' => 'Actionfp93Filierefp93Id',
					'value' => null,
					'type' => 'select',
					'options' => array()
				),
				'Actionfp93.prestatairefp93_id' => array(
					'id' => 'Actionfp93Prestatairefp93Id',
					'value' => null,
					'type' => 'select',
					'options' => array()
				),
				'Ficheprescription93.actionfp93_id' => array(
					'id' => 'Ficheprescription93Actionfp93Id',
					'value' => null,
					'type' => 'select',
					'options' => array()
				),
			);

			// Comment structurer le json de retour
			$return = array(
				'success' => true,
				'fields' => $emptyFields
			);

			// $path -> Thematiquefp93.type
			$path = Hash::get( $data, 'Field.changed' );

			// FIXME: pas des else if, il faudra combiner
			// On sélectionne le type
			if( $path == 'Thematiquefp93.type' ) {
				unset( $return['fields']['Thematiquefp93.type'] );

				$value = Hash::get( $data, $path );
				if( !empty( $value ) ) {
					$list = $Ficheprescription93->Actionfp93->Filierefp93->Categoriefp93->Thematiquefp93->find(
						'list',
						array(
							'conditions' => array(
								$path => $value
							)
						)
					);
					$return['fields']['Categoriefp93.thematiquefp93_id']['options'] = $list;
				}
			}
			// On sélectionne la thématique
			else if( $path == 'Categoriefp93.thematiquefp93_id' ) {
				unset( $return['fields']['Thematiquefp93.type'], $return['fields']['Categoriefp93.thematiquefp93_id'] );

				$value = Hash::get( $data, $path );
				if( !empty( $value ) ) {
					$list = $Ficheprescription93->Actionfp93->Filierefp93->Categoriefp93->find(
						'list',
						array(
							'conditions' => array(
								$path => $value
							)
						)
					);
					$return['fields']['Filierefp93.categoriefp93_id']['options'] = $list;
				}
			}
			// On sélectionne la catégorie
			else if( $path == 'Filierefp93.categoriefp93_id' ) {
				unset( $return['fields']['Thematiquefp93.type'], $return['fields']['Categoriefp93.thematiquefp93_id'], $return['fields']['Filierefp93.categoriefp93_id'] );

				$value = Hash::get( $data, $path );
				if( !empty( $value ) ) {
					$list = $Ficheprescription93->Actionfp93->Filierefp93->find(
						'list',
						array(
							'conditions' => array(
								$path => $value
							)
						)
					);
					$return['fields']['Actionfp93.filierefp93_id']['options'] = $list;
				}
			}
			// On sélectionne la filière
			else if( $path == 'Actionfp93.filierefp93_id' ) {
				unset( $return['fields']['Thematiquefp93.type'], $return['fields']['Categoriefp93.thematiquefp93_id'], $return['fields']['Filierefp93.categoriefp93_id'], $return['fields']['Actionfp93.filierefp93_id'] );

				$value = Hash::get( $data, $path );
				if( !empty( $value ) ) {
					$list = $Ficheprescription93->Actionfp93->Prestatairefp93->find(
						'list',
						array(
							'joins' => array(
								$Ficheprescription93->Actionfp93->Prestatairefp93->join( 'Actionfp93', array( 'type' => 'INNER' ) )
							),
							'conditions' => array(
								$path => $value
							)
						)
					);
					$return['fields']['Actionfp93.prestatairefp93_id']['options'] = $list;

					$list = $Ficheprescription93->Actionfp93->find(
						'list',
						array(
							'conditions' => array(
								$path => $value
							)
						)
					);
					$return['fields']['Ficheprescription93.actionfp93_id']['options'] = $list;
				}
			}
			// On sélectionne le prestataire
			else if( $path == 'Actionfp93.prestatairefp93_id' ) {
				unset( $return['fields']['Thematiquefp93.type'], $return['fields']['Categoriefp93.thematiquefp93_id'], $return['fields']['Filierefp93.categoriefp93_id'], $return['fields']['Actionfp93.filierefp93_id'], $return['fields']['Actionfp93.prestatairefp93_id'] );

				$value = Hash::get( $data, $path );
				if( !empty( $value ) ) {
					$list = $Ficheprescription93->Actionfp93->find(
						'list',
						array(
							'joins' => array(
								$Ficheprescription93->Actionfp93->join( 'Prestatairefp93', array( 'type' => 'INNER' ) )
							),
							'conditions' => array(
								$path => $value
							)
						)
					);
					$return['fields']['Ficheprescription93.actionfp93_id']['options'] = $list;
				}
			}
			// On sélectionne l'action
			else if( $path == 'Ficheprescription93.actionfp93_id' ) {
				unset( $return['fields']['Thematiquefp93.type'], $return['fields']['Categoriefp93.thematiquefp93_id'], $return['fields']['Filierefp93.categoriefp93_id'], $return['fields']['Actionfp93.filierefp93_id'], $return['fields']['Ficheprescription93.actionfp93_id'], $return['fields']['Actionfp93.prestatairefp93_id'] );

				$value = Hash::get( $data, $path );
				if( !empty( $value ) ) {
					$result = $Ficheprescription93->Actionfp93->find(
						'first',
						array(
							'field' => array(
								'Actionfp93.numconvention'
							),
							'conditions' => array(
								'Actionfp93.id' => $value
							)
						)
					);
					$return['fields']['Ficheprescription93.numconvention']['value'] = Hash::get( $result, 'Actionfp93.numconvention' );
				}
			}

			// TODO: si on avait des valeurs sélectionnées dans le $this->request->data

			return $return;
		}
	}
?>