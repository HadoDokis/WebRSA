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
	}
?>