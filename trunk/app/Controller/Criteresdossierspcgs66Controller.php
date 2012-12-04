<?php
	/**
	 * Code source de la classe Criteresdossierspcgs66Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::import( 'Sanitize' );

	/**
	 * La classe Criteresdossierspcgs66Controller ...
	 *
	 * @package app.Controller
	 */
	class Criteresdossierspcgs66Controller extends AppController
	{
		public $uses = array( 'Criteredossierpcg66', 'Dossierpcg66', 'Option', 'Canton' );
		public $helpers = array( 'Default', 'Default2', 'Locale', 'Csv', 'Search' );

		public $components = array( 'Gestionzonesgeos', 'Search.Prg' => array( 'actions' => array( 'dossier', 'gestionnaire' ) ) );

		/**
		*
		*/

		protected function _setOptions() {

			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'etatdosrsa', $this->Option->etatdosrsa() );
			$this->set( 'typepdo', $this->Dossierpcg66->Typepdo->find( 'list' ) );
			$this->set( 'originepdo', $this->Dossierpcg66->Originepdo->find( 'list' ) );
			$this->set( 'descriptionpdo', $this->Dossierpcg66->Personnepcg66->Traitementpcg66->Descriptionpdo->find( 'list' ) );
			$this->set( 'motifpersonnepcg66', $this->Dossierpcg66->Personnepcg66->Situationpdo->find( 'list' ) );

			$this->set( 'gestionnaire', $this->User->find(
					'list',
					array(
						'fields' => array(
							'User.nom_complet'
						),
						'conditions' => array(
							'User.isgestionnaire' => 'O'
						)
					)
				)
			);

			$options = $this->Dossierpcg66->enums();
			$etatdossierpcg = $options['Dossierpcg66']['etatdossierpcg'];

			$options = array_merge(
				$options,
				$this->Dossierpcg66->Personnepcg66->Traitementpcg66->enums()
			);
			$this->set( compact( 'options', 'etatdossierpcg', 'mesCodesInsee' ) );
		}

		/**
		*
		*/

		private function _index( $searchFunction ) {

			$this->Gestionzonesgeos->setCantonsIfConfigured();

			$mesZonesGeographiques = $this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );



			$params = $this->request->data;
			if( !empty( $params ) ) {
				$this->paginate = $this->Criteredossierpcg66->{$searchFunction}( $this->request->data, $mesCodesInsee,
					$mesZonesGeographiques );

				$this->paginate = $this->_qdAddFilters( $this->paginate );
				$this->Dossierpcg66->forceVirtualFields = true;
				$criteresdossierspcgs66 = $this->paginate( 'Dossierpcg66' );

				foreach( $criteresdossierspcgs66 as $i => $criteredossierpcg66 ) {
					$dossierpcg66_id = Set::classicExtract( $criteredossierpcg66, 'Dossierpcg66.id' );

					$traitementspcgs66 = $this->Dossierpcg66->Personnepcg66->Traitementpcg66->find(
						'all',
						array(
							'fields' => array(
								'Traitementpcg66.typetraitement'
							),
							'joins' => array(
								$this->Dossierpcg66->Personnepcg66->Traitementpcg66->join( 'Personnepcg66', array( 'type' => 'INNER' ) )
							),
							'conditions' => array(
								'Personnepcg66.dossierpcg66_id' => $dossierpcg66_id
							),
							'contain' => false
						)
					);
					//Liste des différents statuts de la personne
					$listeTraitementspcgs66 = Set::extract( $traitementspcgs66, '/Traitementpcg66/typetraitement' );

					$criteresdossierspcgs66[$i]['Dossierpcg66']['listetraitements'] = $listeTraitementspcgs66;
					
					$listeSituationsPersonnePCG66 = $this->Dossierpcg66->Personnepcg66->find(
						'all',
						array(
							'fields' => array(
								'Situationpdo.libelle'
							),
							'conditions' => array(
								'Personnepcg66.dossierpcg66_id' => $dossierpcg66_id
							),
							'joins' => array(
								$this->Dossierpcg66->Personnepcg66->join( 'Personnepcg66Situationpdo', array( 'type' => 'LEFT OUTER' ) ),
								$this->Dossierpcg66->Personnepcg66->Personnepcg66Situationpdo->join( 'Situationpdo', array( 'type' => 'LEFT OUTER' ) )
							)
						)
					);

					
					$listeStatuts = Set::extract( $listeSituationsPersonnePCG66, '/Situationpdo/libelle' );
					$listeSituationsPersonnePCG66 = $listeStatuts;
					$criteresdossierspcgs66[$i]['Personnepcg66']['listemotifs'] = $listeSituationsPersonnePCG66;
				}

				$this->set( compact( 'criteresdossierspcgs66', 'listeStatuts' ) );
			}
			else {
				$filtresdefaut = Configure::read( "Filtresdefaut.{$this->name}_{$this->action}" );
				$this->data = Set::merge( $this->data, $filtresdefaut );
			}
// debug($params);
			$this->_setOptions();
			$this->set( 'mesCodesInsee', $this->Gestionzonesgeos->listeCodesInsee() );
			$this->render( $this->action );
		}

		/**
		*
		*/

		public function dossier() {
			$this->_index( 'searchDossier' );
		}

		/**
		*
		*/

		public function gestionnaire() {
			$this->_index( 'searchGestionnaire' );
		}
	}
?>