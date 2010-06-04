<?php
    @set_time_limit( 0 );
    // Mémoire maximum allouée à l'exécution de ce script
    @ini_set( 'memory_limit', '512M' );
    // Temps maximum d'exécution du script (en secondes)
    @ini_set( 'max_execution_time', 2000 );
    // Temps maximum (en seconde), avant que le script n'arrête d'attendre la réponse de Gedooo
    @ini_set( 'default_socket_timeout', 12000 );

    class GedoooComponent extends Component
    {
        /**
        * The initialize method is called before the controller's beforeFilter method.
        */

        function initialize( &$controller, $settings = array() ) {
            $this->controller = &$controller;
        }

		/**
		*
		*/

        function mkOrientstructPdf( $orientstruct_id = null ) {

			$orientstructModelClass = ClassRegistry::init( 'Orientstruct' );
			$pdfModelClass = ClassRegistry::init( 'Pdf' );

//         $test = $orientstructModelClass->findById( $orientstruct_id, null, null, 2 );
//         debug($orientstructModelClass);
//         die();


			$orientstruct = $orientstructModelClass->getDataForPdf( $orientstruct_id, $this->controller->Session->read( 'Auth.User.id' ) );

            $modele = $orientstruct['Typeorient']['modele_notif'];

			$vieuxPdf = $pdfModelClass->find(
				'first',
				array(
					'conditions' => array(
						"{$pdfModelClass->alias}.modele" => 'Orientstruct',
						"{$pdfModelClass->alias}.fk_value" => $orientstruct_id
					)
				)
			);

			$modeledoc = 'Orientation/'.$modele.'.odt';
            $pdfContent = $this->getPdf( $orientstruct, $modeledoc );
			if( !is_bool( $pdfContent ) ) {
				$record = Set::merge(
					$vieuxPdf,
					array(
						$pdfModelClass->alias => array(
							'modele' => 'Orientstruct',
							'modeledoc' => $modeledoc,
							'fk_value' => $orientstruct_id,
							'document' => $pdfContent
						)
					)
				);

				$pdfModelClass->create( $record );
				return $pdfModelClass->save();
			}
			return $pdfContent;
        }

		/**
		*
		*/

		function getCohortePdfForClient( $queryData ) {
			/// FIXME: supprimer si besoin dans UsersController::login et UsersController::logout
			/// FIXME: voir le formulaire CG66
			$pdfModel = ClassRegistry::init( 'Pdf' );
			$pdfTmpDir = rtrim( Configure::read( 'Cohorte.dossierTmpPdfs' ), '/' ).'/'.session_id().'/Orientstruct';
			$old = umask(0);
			@mkdir( $pdfTmpDir, 0777, true ); /// FIXME: vérification
			umask($old);

			$pdfs = $pdfModel->find( 'all', $queryData );

			foreach( $pdfs as $pdf ) {
				file_put_contents( "{$pdfTmpDir}/{$pdf['Pdf']['id']}.pdf", $pdf['Pdf']['document'] );
			}

			exec( "pdftk {$pdfTmpDir}/*.pdf cat output {$pdfTmpDir}/all.pdf" ); // FIXME: nom de fichier cohorte-orientation-20100423-12h00.pdf

			$c = file_get_contents( "{$pdfTmpDir}/all.pdf" );

			exec( "rm {$pdfTmpDir}/*.pdf" );
			exec( "rmdir {$pdfTmpDir}" );

			return $c; /// FIXME: false si problème
		}

		/**
		*
		*/

		function sendPdfContentToClient( $content, $filename ) {
			header( 'Content-type: application/pdf' );
			header( 'Content-Length: '.strlen( $content ) );
			header( "Content-Disposition: attachment; filename={$filename}" );

			echo $content;
			die();
		}

		/**
		* FIXME: la faire utiliser par GedoooComponent::generate
		*/

        function getPdf( $datas, $model ) {
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
		*
		*/

        function generate( $datas, $model ) {
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

//                 $fieldList[] = strtolower( $key );
//                 $fieldList[strtolower( $key )] = $value;
            }
// debug( $fieldList );
// die();
            // fusion des documents
            $oFusion = new GDO_FusionType($oTemplate, $sMimeType, $oMainPart);
            $oFusion->process();
            $oFusion->SendContentToClient();

            return ( $oFusion->getCode() == 'OK' );
        }

        /**
        *
        */

        function generateCohorte( $sectionName, $sectionDatas, $model, $datas = null ) {
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
            $oIteration = new GDO_IterationType( $sectionName );

            if( !empty( $datas ) ) {
                foreach( Set::flatten( $datas, '_' ) as $key => $value ) {
                    $type = 'text';
                    if( preg_match( '/[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}/', $value ) ) {
                        $type = 'date';
                    }

                    $oMainPart->addElement( new GDO_FieldType( strtolower( $key ), $value, $type ) );
                }
            }

            foreach( $sectionDatas as $sectionData ) {
                $oDevPart = new GDO_PartType();

                $sectionData = Set::flatten( $sectionData, '_' );

/*$fielList = array_keys( $sectionData );
$fielList = array_map( 'strtolower', $fielList );
debug( $fielList );
die();*/
                foreach( $sectionData as $key => $value ) {
                    $type = 'text';
                    if( preg_match( '/[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}/', $value ) ) {
                        $type = 'date';
                    }

                    $oDevPart->addElement( new GDO_FieldType( strtolower( $key ), $value, $type ) );
                }
                $oIteration->addPart($oDevPart);
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

        /** *******************************************************************
            The beforeRedirect method is invoked when the controller's redirect method
            is called but before any further action. If this method returns false the
            controller will not continue on to redirect the request.
            The $url, $status and $exit variables have same meaning as for the controller's method.
        ******************************************************************** */
        function beforeRedirect( &$controller, $url, $status = null, $exit = true ) {
            parent::beforeRedirect( $controller, $url, $status , $exit );
        }
    }
?>