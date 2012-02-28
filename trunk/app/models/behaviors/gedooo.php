<?php
	// Inclusion des fichiers nécessaires à GEDOOo
	require_once( PHPGEDOOO_DIR.'GDO_Utility.class' );
	require_once( PHPGEDOOO_DIR.'GDO_FieldType.class' );
	require_once( PHPGEDOOO_DIR.'GDO_ContentType.class' );
	require_once( PHPGEDOOO_DIR.'GDO_IterationType.class' );
	require_once( PHPGEDOOO_DIR.'GDO_PartType.class' );
	require_once( PHPGEDOOO_DIR.'GDO_FusionType.class' );
	require_once( PHPGEDOOO_DIR.'GDO_MatrixType.class' );
	require_once( PHPGEDOOO_DIR.'GDO_MatrixRowType.class' );
	require_once( PHPGEDOOO_DIR.'GDO_AxisTitleType.class' );

	class GedoooBehavior extends ModelBehavior
	{
		/**
		* INFO: GDO_FieldType: text, string, number, date
		*/

		protected function _addPartValue( $oPart, $key, $value, $options ) {
			$type = 'text';
			if( preg_match( '/[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}/', $value ) ) {
				$type = 'date';
			}
            else if( preg_match( '/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/', $value, $matches ) ) {
                $type = 'date';
                $value = "{$matches[3]}/{$matches[2]}/{$matches[1]}";
            }
			else if( preg_match( '/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}:[0-9]{2}:[0-9]{2})$/', $value, $matches ) ) {
				$type = 'date';
				$value = "{$matches[3]}/{$matches[2]}/{$matches[1]}";
				$oPart->addElement( new GDO_FieldType( strtolower( $key ).'_time', preg_replace( '/([0-9]{2}:[0-9]{2}):[0-9]{2}/', '\1', $matches[4] ), 'text' ) );
			}

			// Traduction des enums
			if( preg_match( '/([^_]+)_([0-9]+_){0,1}([^_]+)$/', $key, $matches ) ) {
				if( isset( $options[$matches[1]][$matches[3]] ) ) {
					$value = Set::enum( $value, $options[$matches[1]][$matches[3]] );
				}
			}

			$oPart->addElement( new GDO_FieldType( strtolower( $key ), $value, $type ) );

			return $oPart;
		}

		/**
		* Crée une table de correspondance entre les noms des champs utilisables
		* dans les modèles et leur traduction à partir des données envoyées.
		*
		* @param array $keys
		* @return array
		*/
		protected function _flatKeys( $keys ) {
			$flatKeys = array();

			if( !empty( $keys ) ) {
				foreach( $keys as $key ) {
					if( strpos( $key, '.' ) !== false ) {
						$modelField = model_field( $key );
						$domain = Inflector::underscore( $modelField[0] );
						$msgstr = __d( $domain, implode( '.', $modelField ), true );
						$flatKeys[strtolower(str_replace( '.', '_', $key ))] = $msgstr;
					}
					else {
						$flatKeys[strtolower($key)] = $key;
					}
				}
			}

			return $flatKeys;
		}

		/**
		* Exporte la liste des champs disponibles dans un fichier CSV situé dans
		* app/tmp/logs, nommé suivant le nom du modèle de document utilisé.
		*
		* @param GDO_PartType &$oMainPart
		* @param array $mainData
		* @param string $document
		* @return void
		*/
		protected function _exportChampsDisponibles( &$oMainPart, $mainData, $cohorteData, $document ) {
			//
			$flatKeys = $this->_flatKeys( array_keys( Set::flatten( $mainData ) ) );
			//
			if( !empty( $cohorteData ) ) {
				foreach( $cohorteData as $prefix => $foo ) {
					if( isset( $foo[0] ) ) {
						$flatKeys = array_merge(
							$flatKeys,
							$this->_flatKeys( array_keys( Set::flatten( array( $prefix => $foo[0] ) ) ) )
						);
					}
				}
			}

			$mainFields = array();
			$sectionFields = array();

			if( !empty( $oMainPart->field ) ) {
				foreach( $oMainPart->field as $field ) {
					//$mainFields[$field->target] = $field->dataType;
					$msgstr = null;
					if( isset( $flatKeys[$field->target] ) ) {
						$msgstr = $flatKeys[$field->target];
					}
					$mainFields["contenu_{$field->target}"] = "\"contenu\";\"\";\"{$field->target}\";\"{$field->dataType}\";\"{$msgstr}\"";
				}
			}

			if( !empty( $oMainPart->iteration ) ) {
				foreach( $oMainPart->iteration as $iteration ) {
					if( isset( $iteration->part[0] ) ) {
						foreach( $iteration->part[0]->field as $field ) {
							//$sectionFields[$iteration->name][$field->target] = $field->dataType;
							$msgstr = null;
							$key = strtolower( "{$iteration->name}_{$field->target}" );
							if( isset( $flatKeys[$key] ) ) {
								$msgstr = $flatKeys[$key];
							}
							$sectionFields["sections_{$iteration->name}_{$field->target}"] = "\"sections\";\"{$iteration->name}\";\"{$field->target}\";\"{$field->dataType}\";\"{$msgstr}\"";
						}
					}
				}
			}

			$outputFile = TMP.DS.'logs'.DS.__CLASS__.'__'.str_replace( '/', '__', str_replace( '.', '_', $document ) ).'.csv';

			$oldMask = umask( 0 );

			file_put_contents(
				$outputFile,
				"\"Partie\";\"Nom de la section\";\"Champ\";\"Type\";\"Traduction\"\n"
				.implode( "\n", $mainFields )."\n".implode( "\n", $sectionFields )
			);

			umask( $oldMask );
		}

		/**
		* Fonction de génération de documents générique
		* @param $datas peut prendre la forme suivante:
		*     - array( ... ) si $section == false
		*     - array( 0 => array( ... ), 'section1' => array( ... ), 'section2' => array( ... ) ) si $section == true
		*/

		public function ged( &$model, $datas, $document, $section = false, $options = array() ) {
			// Définition des variables & maccros
			$sMimeType  = "application/pdf";
			$path_model = MODELESODT_DIR.$document;

			// Quel type de données a-t-on reçu ?
			if( !$section ) {
				$mainData = $datas;
				$cohorteData = array();
			}
			else {
				$mainData = ( isset( $datas[0] ) ? $datas[0] : array() );
				$cohorteData = $datas;
				unset( $cohorteData[0] );
			}

			//
			// Organisation des données
			//
			$u = new GDO_Utility();
			$oMainPart = new GDO_PartType();

			$bTemplate = $u->ReadFile($path_model);
			if( empty( $bTemplate ) ) {
				$this->log( sprintf( "Le modèle de document %s n'existe pas ou n'est pas lisible.", $path_model ), LOG_ERROR );
				return false;
			}

			if( !empty( $mainData ) ) {
				foreach( Set::flatten( $mainData, '_' ) as $key => $value ) {
					$oMainPart = $this->_addPartValue( $oMainPart, $key, $value, $options );
				}
			}

			// Ajout d'une variable contenant le chemin vers le fichier et vérification
			// que le modèle connaisse bien le fichier odt lorsqu'on est en debug
			if( Configure::read( 'debug' ) > 0 ) {
				// Attention, c'est le modèle qui doit avoir le comportement Gedooo
				$modelesOdt = str_replace( '%s', $model->alias, $model->modelesOdt );

				// Récupération des valeurs de la méthode modelesOdt lorsqu'elle est présente
				if( in_array( 'modelesOdt', get_class_methods( $model->name ) ) ) {
					$modelesOdt = Set::merge( $modelesOdt, $model->modelesOdt() );
				}

				if( !in_array( $document, $modelesOdt ) ) {
					$this->log( sprintf( "Le modèle de document %s n'est pas connu du modèle %s.", $document, $model->alias ), LOG_DEBUG );
				}

				$oMainPart->addElement( new GDO_FieldType( 'modeleodt_path', str_replace( MODELESODT_DIR, '', $path_model ), 'text' ) );
			}

			if( !empty( $cohorteData ) ) {
				foreach( $cohorteData as $cohorteName => $sectionDatas ) {
					// Traitement d'une section
					$sectionFields = array();

					$oIteration = new GDO_IterationType( $cohorteName );
					foreach( $sectionDatas as $sectionData ) {
						$oDevPart = new GDO_PartType();

						$sectionData = Set::flatten( $sectionData, '_' );
						foreach( $sectionData as $key => $value ) {
							$oDevPart = $this->_addPartValue( $oDevPart, $key, $value, $options );
						}
						$oIteration->addPart( $oDevPart );

					}
					$oMainPart->addElement($oIteration);
				}
			}

			if( Configure::read( 'debug' ) > 0 ) {
				$this->_exportChampsDisponibles( $oMainPart, $mainData, $cohorteData, $document );
			}

			$oTemplate = new GDO_ContentType(
				"",
				"modele.ott",
				$u->getMimeType($path_model),
				"binary",
				$bTemplate
			);

			$oFusion = new GDO_FusionType( $oTemplate, $sMimeType, $oMainPart );
			$oFusion->process();
			$success = ( $oFusion->getCode() == 'OK' );

			if( $success ) {
				$content = $oFusion->getContent();
				return $content->binary;
			}
			else {
				$this->log( sprintf( "Erreur lors de la génération du document (%s num. %s: %s).", $oFusion->getCode(), $oFusion->errNum, $oFusion->getMessage() ), LOG_ERROR );
			}

			return $success;
		}
	}
?>