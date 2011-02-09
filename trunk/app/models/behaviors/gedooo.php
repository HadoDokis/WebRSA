<?php
	class GedoooBehavior extends ModelBehavior
	{
		public function getPdf( &$model, $datas, $modeleodt ) {
			// Définition des variables & maccros
			// FIXME: chemins
			$phpGedooDir = dirname( __FILE__ ).'/../../vendors/phpgedooo';
			$sMimeType  = "application/pdf";
			$path_model = $phpGedooDir.'/../modelesodt/'.$modeleodt;

			// Inclusion des fichiers nécessaires à GEDOOo
			// FIXME
			$phpGedooDir = dirname( __FILE__ ).'/../../vendors/phpgedooo';
			require_once( $phpGedooDir.DS.'GDO_Utility.class' );
			require_once( $phpGedooDir.DS.'GDO_FieldType.class' );
			require_once( $phpGedooDir.DS.'GDO_ContentType.class' );
			require_once( $phpGedooDir.DS.'GDO_IterationType.class' );
			require_once( $phpGedooDir.DS.'GDO_PartType.class' );
			require_once( $phpGedooDir.DS.'GDO_FusionType.class' );
			require_once( $phpGedooDir.DS.'GDO_MatrixType.class' );
			require_once( $phpGedooDir.DS.'GDO_MatrixRowType.class' );
			require_once( $phpGedooDir.DS.'GDO_AxisTitleType.class' );

			//initialisation des objets
			$util      = new GDO_Utility();
			$oTemplate = new GDO_ContentType(
				'',
				basename( $path_model ),
				$util->getMimeType( $path_model ),
				'binary',
				$util->ReadFile( $path_model )
			);
			$oMainPart = new GDO_PartType();

			$fieldList = array();
			foreach( Set::flatten( $datas, '_' )  as $key => $value ) {
				$type = 'text';
				if( preg_match( '/[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}/', $value ) ) {
					$type = 'date';
				}

				$oMainPart->addElement(
					new GDO_FieldType(
						strtolower( $key ),
						$value,
						$type
					)
				);

				$fieldList[] = strtolower( $key );
			}

			// fusion des documents
			$oFusion = new GDO_FusionType($oTemplate, $sMimeType, $oMainPart);
			$oFusion->process();

			$success = ( $oFusion->getCode() == 'OK' );

			if( $success ) {
				$content = $oFusion->getContent();
				return $content->binary;
			}

			return $success;
		}

		/**
		* FIXME: harmoniser les noms de fonctions
		*/

		public function generateCohorte( &$model, $sectionName, $sectionDatas, $model, $datas = null ) {
			// Définition des variables & maccros
			// FIXME: chemins
			$phpGedooDir = dirname( __FILE__ ).'/../../vendors/phpgedooo';
			$sMimeType  = "application/pdf";
			$path_model = $phpGedooDir.'/../modelesodt/'.$model;

			// Inclusion des fichiers nécessaires à GEDOOo
			// FIXME
			$phpGedooDir = dirname( __FILE__ ).'/../../vendors/phpgedooo';
			require_once( $phpGedooDir.DS.'GDO_Utility.class' );
			require_once( $phpGedooDir.DS.'GDO_FieldType.class' );
			require_once( $phpGedooDir.DS.'GDO_ContentType.class' );
			require_once( $phpGedooDir.DS.'GDO_IterationType.class' );
			require_once( $phpGedooDir.DS.'GDO_PartType.class' );
			require_once( $phpGedooDir.DS.'GDO_FusionType.class' );
			require_once( $phpGedooDir.DS.'GDO_MatrixType.class' );
			require_once( $phpGedooDir.DS.'GDO_MatrixRowType.class' );
			require_once( $phpGedooDir.DS.'GDO_AxisTitleType.class' );

			//
			// Organisation des données
			//
			$u = new GDO_Utility();
			$oMainPart = new GDO_PartType();

			if( !empty( $datas ) ) {
				foreach( Set::flatten( $datas, '_' ) as $key => $value ) {
					$type = 'text';
					if( preg_match( '/[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}/', $value ) ) {
						$type = 'date';
					}

					$oMainPart->addElement( new GDO_FieldType( strtolower( $key ), $value, $type ) );
				}
			}

			$oIteration = new GDO_IterationType( $sectionName );

			foreach( $sectionDatas as $sectionData ) {
				$oDevPart = new GDO_PartType();

				$sectionData = Set::flatten( $sectionData, '_' );
// debug( $sectionData );
				foreach( $sectionData as $key => $value ) {
					$type = 'text';
					if( preg_match( '/[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}/', $value ) ) {
						$type = 'date';
					}

					$oDevPart->addElement( new GDO_FieldType( strtolower( $key ), $value, $type ) );
				}
				$oIteration->addPart( $oDevPart );
			}
			$oMainPart->addElement($oIteration);

			$bTemplate = $u->ReadFile($path_model);
			$oTemplate = new GDO_ContentType(
				"",
				"modele.ott",
				$u->getMimeType($path_model),
				"binary",
				$bTemplate
			);

			$oFusion = new GDO_FusionType( $oTemplate, $sMimeType, $oMainPart );
			$oFusion->process();
			$oFusion->SendContentToClient();
			return ( $oFusion->getCode() == 'OK' );
		}

		/**
		*
		*/

		protected function _addPartValue( $oPart, $key, $value, $options ) {
			$type = 'text';
			if( preg_match( '/[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}/', $value ) ) {
				$type = 'date';
			}
			else if( preg_match( '/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}:[0-9]{2}:[0-9]{2})$/', $value, $matches ) ) {
				$type = 'date';
				$value = "{$matches[3]}/{$matches[2]}/{$matches[1]}";
				$oPart->addElement( new GDO_FieldType( strtolower( $key ).'_time', $matches[4], 'time' ) );
			}

			// Traduction des enums
			if( preg_match( '/^([^_]+)_(.*)$/', $key, $matches ) ) {
				if( isset( $options[$matches[1]][$matches[2]] ) ) {
					$value = Set::enum( $value, $options[$matches[1]][$matches[2]] );
				}
			}

			$oPart->addElement( new GDO_FieldType( strtolower( $key ), $value, $type ) );

			return $oPart;
		}

		/**
		* Fonction de génération de documents générique
		* @param $datas peut prendre la forme suivante:
		*     - array( ... ) si $section == false
		*     - array( 0 => array( ... ), 'section1' => array( ... ), 'section2' => array( ... ) ) si $section == true
		*/

		public function ged( &$model, $datas, $document, $section = false, $options = array() ) {
			// Définition des variables & maccros
			// FIXME: chemins
			$phpGedooDir = dirname( __FILE__ ).'/../../vendors/phpgedooo';
			$sMimeType  = "application/pdf";
			$path_model = $phpGedooDir.'/../modelesodt/'.$document;

			// Inclusion des fichiers nécessaires à GEDOOo
			// FIXME
			$phpGedooDir = dirname( __FILE__ ).'/../../vendors/phpgedooo';
			require_once( $phpGedooDir.DS.'GDO_Utility.class' );
			require_once( $phpGedooDir.DS.'GDO_FieldType.class' );
			require_once( $phpGedooDir.DS.'GDO_ContentType.class' );
			require_once( $phpGedooDir.DS.'GDO_IterationType.class' );
			require_once( $phpGedooDir.DS.'GDO_PartType.class' );
			require_once( $phpGedooDir.DS.'GDO_FusionType.class' );
			require_once( $phpGedooDir.DS.'GDO_MatrixType.class' );
			require_once( $phpGedooDir.DS.'GDO_MatrixRowType.class' );
			require_once( $phpGedooDir.DS.'GDO_AxisTitleType.class' );

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

// 			$availableFields = array();

			//
			// Organisation des données
			//
			$u = new GDO_Utility();
			$oMainPart = new GDO_PartType();

			if( !empty( $mainData ) ) {
				foreach( Set::flatten( $mainData, '_' ) as $key => $value ) {
					$oMainPart = $this->_addPartValue( $oMainPart, $key, $value, $options );
					/*$type = 'text';
					if( preg_match( '/[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}/', $value ) ) {
						$type = 'date';
					}
					else if( preg_match( '/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}:[0-9]{2}:[0-9]{2})$/', $value, $matches ) ) {
						$type = 'date';
						$value = "{$matches[3]}/{$matches[2]}/{$matches[1]}";
						$oMainPart->addElement( new GDO_FieldType( strtolower( $key ).'_time', $matches[4], 'time' ) );
					}

					// Traduction des enums
					if( preg_match( '/^([^_]+)_(.*)$/', $key, $matches ) ) {
						if( isset( $options[$matches[1]][$matches[2]] ) ) {
							$value = Set::enum( $value, $options[$matches[1]][$matches[2]] );
						}
					}

// debug( $value );
					$oMainPart->addElement( new GDO_FieldType( strtolower( $key ), $value, $type ) );

// 					$availableFields[0][] = strtolower( $key );*/
				}
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
							/*$type = 'text';
							if( preg_match( '/[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}/', $value ) ) {
								$type = 'date';
							}

							// Traduction des enums
							if( preg_match( '/^([^_]+)_(.*)$/', $key, $matches ) ) {
								if( isset( $options[$matches[1]][$matches[2]] ) ) {
									$value = Set::enum( $value, $options[$matches[1]][$matches[2]] );
								}
							}

							$sectionFields[] = strtolower( $key );
							$oDevPart->addElement( new GDO_FieldType( strtolower( $key ), $value, $type ) );

// 							$availableFields[$cohorteName][] = strtolower( $key );*/
						}
						$oIteration->addPart( $oDevPart );

					}
					$oMainPart->addElement($oIteration);
				}
			}

			// FIXME: une commande / config spéciale pour exporter, ou juste quand le debug > 0 ?
			if( Configure::read( 'debug' ) > 0 ) {
				//debug( $oMainPart );
				$mainFields = array();
				foreach( $oMainPart->field as $field ) {
					$mainFields[$field->target] = $field->dataType;
				}

				$sectionFields = array();
				if( !empty( $oMainPart->iteration ) ) {
					foreach( $oMainPart->iteration as $iteration ) {
						if( isset( $iteration->part[0] ) ) {
							foreach( $iteration->part[0]->field as $field ) {
								$sectionFields[$iteration->name][$field->target] = $field->dataType;
							}
						}
					}
				}

				$outputFile = TMP.DS.'logs'.DS.__CLASS__.'__'.str_replace( '/', '__', str_replace( '.', '_', $document ) );
				file_put_contents(
					$outputFile,
					var_export(
						array(
							'contenu' => $mainFields,
							'sections' => $sectionFields
						),
						true
					)
				);
			}

			$bTemplate = $u->ReadFile($path_model);
			$oTemplate = new GDO_ContentType(
				"",
				"modele.ott",
				$u->getMimeType($path_model),
				"binary",
				$bTemplate
			);

			$oFusion = new GDO_FusionType( $oTemplate, $sMimeType, $oMainPart );
			$oFusion->process();

			/*$oFusion->SendContentToClient();
			return ( $oFusion->getCode() == 'OK' );*/

			$success = ( $oFusion->getCode() == 'OK' );

			if( $success ) {
				$content = $oFusion->getContent();
				return $content->binary;
			}

			return $success;
		}
	}
?>