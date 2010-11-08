<?php

	class ChecksController extends AppController {

		public $name = 'Checks';
		
		public $components = array( 'Dbdroits' );

		public $uses = array( 'Structurereferente', 'User' );

		public function index() {
			$this->Dbdroits->majActions();
			$this->set('webrsaIncExist', $this->_checkWebrsaInc( array( 'Cohorte.dossierTmpPdfs' ) ) );
			$this->set('pdftkInstalled', $this->_checkMissingBinaries( array( 'pdftk' ) ) );
			$this->_checkDecisionsStructures();
			$this->_checkDonneesUtilisateursEtServices();
			$this->set('donneesApreExist', $this->_checkDonneesApre() );
			$this->set('checkWritePdfDirectory', $this->_checkTmpPdfDirectory( Configure::read( 'Cohorte.dossierTmpPdfs' ) ) );
		}

		/**
		*
		*/

		function _checkWebrsaInc( $paths = array() ) {
			$errorPaths = array();
			if( !empty( $paths ) ) {
				foreach( $paths as $path ) {
					$value = Configure::read( $path );
					if( empty( $value ) && !is_numeric( $value ) ) {
						$errorPaths[] = $path;
					}
				}
			}
			if( !empty( $errorPaths ) )
				return false;
			else
				return true;
		}

		/**
		*
		*/

        function _checkMissingBinaries( $binaries = array() ) {
			$missing = array();
			if( !empty( $binaries ) ) {
				foreach( $binaries as $binary ) {
					$which = exec( "which {$binary}" );
					if( empty( $which ) ) {
						$missing[] = $binary;
					}
				}
			}
			if( !empty( $missing ) )
				return false;
			else
				return true;
		}

        /**
        * Vérifie que pour toutes les structures référentes, le fait qu'elles gèrent
        * ou non l'Apre ou le contrat d'engagement soit décidé.
        * Si la décision n'a pas été prise pour au moins une structure, on bloque
        * l'utilisateur avec une erreur 401 et un message d'erreur approprié.
		*
        * INFO: n'est réellement exécuté que la première fois
		*
        * @access protected
        */

        protected function _checkDecisionsStructures() {
			$structs = $this->Structurereferente->find(
				'all',
				array(
        			'fields'=>array(
        				'Structurereferente.lib_struc',
        				'Structurereferente.apre',
        				'Structurereferente.contratengagement'
        			),
        			'recursive'=>-1,
					'conditions' => array(
						'OR' => array(
							'Structurereferente.apre' => NULL,
							'Structurereferente.contratengagement' => NULL
						)
					)
				)
			);
			$this->set( compact( 'structs' ) );
        }

        /**
        * @access protected
        */

        function _checkDonneesUtilisateursEtServices() {
        	$users = $this->User->find(
        		'all',
        		array(
        			'fields'=>array(
        				'User.nom',
        				'User.prenom',
        				'User.serviceinstructeur_id',
        				'User.date_deb_hab',
        				'User.date_fin_hab',
        				'Serviceinstructeur.lib_service',
        				'Serviceinstructeur.numdepins',
        				'Serviceinstructeur.typeserins',
        				'Serviceinstructeur.numcomins',
        				'Serviceinstructeur.numagrins',
        			),
        			'recursive'=>-1,
        			'joins'=>array(
        				array(
                            'table'      => 'servicesinstructeurs',
                            'alias'      => 'Serviceinstructeur',
                            'type'       => 'LEFT OUTER',
                            'foreignKey' => false,
                            'conditions' => array(
                            	'User.serviceinstructeur_id = Serviceinstructeur.id'
                        	)
                        )
        			),
        			'conditions' => array(
        				'OR' => array(
        					'User.nom IS NULL',
        					'TRIM(User.nom)' => null,
        					'User.prenom IS NULL',
        					'TRIM(User.prenom)' => null,
        					'User.serviceinstructeur_id IS NULL',
        					'User.date_deb_hab IS NULL',
        					'User.date_fin_hab IS NULL',
        					'Serviceinstructeur.lib_service IS NULL',
        					'TRIM(Serviceinstructeur.lib_service)' => null,
        					'Serviceinstructeur.numdepins IS NULL',
        					'TRIM(Serviceinstructeur.numdepins)' => null,
        					'Serviceinstructeur.typeserins IS NULL',
        					'TRIM(Serviceinstructeur.typeserins)' => null,
        					'Serviceinstructeur.numcomins IS NULL',
        					'TRIM(Serviceinstructeur.numcomins)' => null,
        					'Serviceinstructeur.numagrins IS NULL'
        				)
    				)
        		)
        	);
			$this->set( compact( 'users' ) );
        }

		/**
		*
		*/

        function _checkDonneesApre() {
			$montantMaxComplementaires = Configure::read( 'Apre.montantMaxComplementaires' );
			$periodeMontantMaxComplementaires = Configure::read( 'Apre.periodeMontantMaxComplementaires' );

			$missing = null;

			if( empty( $montantMaxComplementaires ) )
				$missing[] = 'montantMaxComplementaires';
			if( empty( $periodeMontantMaxComplementaires ) )
				$missing[] = 'periodeMontantMaxComplementaires';

			return $missing;
		}

		/**
		*
		*/

        function _checkTmpPdfDirectory( $dir ) {
			$notWritable = array();
			$oldUmask = umask(0);

			if( !( is_dir( $dir ) && is_writable( $dir ) ) && !@mkdir( $dir, 0777, true ) ) {
				$notWritable[] = $dir;
			}

			umask( $oldUmask );

			if( !empty( $notWritable ) )
				return false;
			else
				return true;
		}

	}

?>
