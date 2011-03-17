<?php
	/**
	* Classe de vérification de l'installation et du paramétrage de WebRSA.
	*/

	class ChecksController extends AppController
	{
		public $name = 'Checks';

		public $uses = array( 'Structurereferente', 'User', 'Serviceinstructeur' );

		public function index() {
			$this->set( 'webrsaIncExist', $this->_checkWebrsaInc( array( 'Cohorte.dossierTmpPdfs' ) ) );
			$this->set( 'pdftkInstalled', $this->_checkMissingBinaries( array( 'pdftk' ) ) );
			$this->_checkDecisionsStructures();
			$this->_checkDonneesUtilisateursEtServices();
			$this->set( 'donneesApreExist', $this->_checkDonneesApre() );
			$this->set( 'checkWritePdfDirectory', $this->_checkTmpPdfDirectory( Configure::read( 'Cohorte.dossierTmpPdfs' ) ) );
			$this->set( 'compressedAssets', $this->_compressedAssets() );
			$this->set( 'checkWebrsaIncEps', $this->_checkWebrsaIncEps() );
			$this->set( 'checkSqrecherche', $this->_checkSqrecherche() );
		}

		/**
		*
		*/

		protected function _checkSqrecherche() {
			$errors = array();

			if( Configure::read( 'Recherche.qdFilters.Serviceinstructeur' ) ) {
				$results = $this->Serviceinstructeur->find(
					'all',
					array(
						'fields' => array( 'id', 'lib_service', 'sqrecherche' ),
						'recursive' => -1,
						'conditions' => array( 'Serviceinstructeur.sqrecherche IS NOT NULL' )
					)
				);

				$debugLevel = Configure::read( 'debug' );
				Configure::write( 'debug', 0 );

				foreach( $results as $result ) {
					$error = $this->Serviceinstructeur->sqrechercheErrors( $result['Serviceinstructeur']['sqrecherche'] );
					if( !empty( $error ) ) {
						$errors[] = $result;
					}
				}

				Configure::write( 'debug', $debugLevel );
			}

			return $errors;
		}

		/**
		*
		*/

		private function __configureReadError( $keys ) {
			$errors = array();

			foreach( $keys as $key => $type ) {
				$value = Configure::read( $key );

				switch( $type ) {
					case 'integer':
						if( is_null( $value ) || !is_integer( $value ) ) {
							$errors[$key] = $type;
						}
						break;
					case 'numeric':
						if( is_null( $value ) || !is_numeric( $value ) ) {
							$errors[$key] = $type;
						}
						break;
					case 'string':
						if( is_null( $value ) || !is_string( $value ) ) {
							$errors[$key] = $type;
						}
						break;
				}
			}

			return $errors;
		}


		/**
		* Vérifie la présence des fichiers CSS et Javascript "compilés" et minifiés
		*/

		protected function _compressedAssets() {
			return array(
				'webrsa.css' => file_exists( CSS.'webrsa.css' ),
				'webrsa.js' => file_exists( JS.'webrsa.js' )
			);
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

		protected function _checkDonneesUtilisateursEtServices() {
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

		protected function _checkDonneesApre() {
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

		protected function _checkTmpPdfDirectory( $dir ) {
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

		/**
		* Vérification du paramétrage des EPs
		*/

		protected function _checkWebrsaIncEps() { // FIXME
			$keys = array( 'Cg.departement' => 'integer', );

			$errors = $this->__configureReadError( $keys );

			$departement = Configure::read( 'Cg.departement' );
			$method = "_checkWebrsaIncEps{$departement}";

			if( method_exists( $this, $method ) ) {
				$errors = Set::merge( $errors, $this->{$method}() );
			}
			else {
				$errors['Cg.departement'] = 'integer';
			}

			return $errors;
		}

		/**
		* Vérification du paramétrage des EPs pour le CG 93
		*/

		protected function _checkWebrsaIncEps93() {
			$keys = array(
				'Nonrespectsanctionep93.relanceDecisionNonRespectSanctions' => 'integer',
				'Nonrespectsanctionep93.relanceOrientstructCer1' => 'integer',
				'Nonrespectsanctionep93.relanceOrientstructCer2' => 'integer',
				'Nonrespectsanctionep93.relanceOrientstructCer3' => 'integer',
				'Nonrespectsanctionep93.relanceCerCer1' => 'integer',
				'Nonrespectsanctionep93.relanceCerCer2' => 'integer',
				'Nonrespectsanctionep93.montantReduction' => 'numeric',
				'Nonrespectsanctionep93.dureeSursis' => 'integer',
			);

			return $this->__configureReadError( $keys );
		}

		/**
		* Vérification du paramétrage des EPs pour le CG 66
		*/

		protected function _checkWebrsaIncEps66() {
			$keys = array(
				'traitementResultatId' => 'integer',
				'traitementEnCoursId' => 'integer',
				'traitementClosId' => 'integer',
				'Traitementpdo.fichecalcul_coefannee1' => 'numeric',// %
				'Traitementpdo.fichecalcul_coefannee2' => 'numeric',// %
				'Traitementpdo.fichecalcul_cavntmax' => 'numeric',
				'Traitementpdo.fichecalcul_casrvmax' => 'numeric',
				'Traitementpdo.fichecalcul_abattbicvnt' => 'numeric',// %
				'Traitementpdo.fichecalcul_abattbicsrv' => 'numeric',// %
				'Traitementpdo.fichecalcul_abattbncsrv' => 'numeric',// %
			);

			return $this->__configureReadError( $keys );
		}

		/**
		* Vérification du paramétrage des EPs pour le CG 58
		*/

		protected function _checkWebrsaIncEps58() {
			return array();
		}
	}
?>