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
					$type = 'text';
					if( preg_match( '/[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}/', $value ) ) {
						$type = 'date';
					}

					// Traduction des enums
					if( preg_match( '/^([^_]+)_(.*)$/', $key, $matches ) ) {
						if( isset( $options[$matches[1]][$matches[2]] ) ) {
							$value = Set::enum( $value, $options[$matches[1]][$matches[2]] );
						}
					}

					$oMainPart->addElement( new GDO_FieldType( strtolower( $key ), $value, $type ) );

// 					$availableFields[0][] = strtolower( $key );
				}
			}
// debug( $options );
			if( !empty( $cohorteData ) ) {
				foreach( $cohorteData as $cohorteName => $sectionDatas ) {
					// Traitement d'une section
					$sectionFields = array();

					$oIteration = new GDO_IterationType( $cohorteName );
					foreach( $sectionDatas as $sectionData ) {
						$oDevPart = new GDO_PartType();

						$sectionData = Set::flatten( $sectionData, '_' );
						foreach( $sectionData as $key => $value ) {
							$type = 'text';
							if( preg_match( '/[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}/', $value ) ) {
								$type = 'date';
							}

							// Traduction des enums
							if( preg_match( '/^([^_]+)_(.*)$/', $key, $matches ) ) {
// debug( $matches );
								if( isset( $options[$matches[1]][$matches[2]] ) ) {
									$value = Set::enum( $value, $options[$matches[1]][$matches[2]] );
// debug( $value );
								}
							}

							$sectionFields[] = strtolower( $key );
							$oDevPart->addElement( new GDO_FieldType( strtolower( $key ), $value, $type ) );

// 							$availableFields[$cohorteName][] = strtolower( $key );
						}
						$oIteration->addPart( $oDevPart );

					}
					$oMainPart->addElement($oIteration);
				}
			}
// die();
// debug( $availableFields );die();
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
	}
?>