<?php
	/**
	 * Code source de la classe Criteresdossierspcgs66Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Sanitize', 'Utility' );

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
            $this->set( 'decisionpdo', $this->Dossierpcg66->Decisiondossierpcg66->Decisionpdo->find( 'list' ) );
			
			$this->set( 'motifpersonnepcg66', $this->Dossierpcg66->Personnepcg66->Situationpdo->find( 'list', array( 'order' => array( 'Situationpdo.libelle ASC' ) ) ) );
            $this->set( 'statutpersonnepcg66', $this->Dossierpcg66->Personnepcg66->Statutpdo->find( 'list', array( 'order' => array( 'Statutpdo.libelle ASC' ) ) ) );
			
			$this->set( 'orgpayeur', array('CAF'=>'CAF', 'MSA'=>'MSA') );

			$this->set( 'gestionnaire', $this->User->find(
					'list',
					array(
						'fields' => array(
							'User.nom_complet'
						),
						'conditions' => array(
							'User.isgestionnaire' => 'O'
						),
                        'order' => array( 'User.nom ASC', 'User.prenom ASC' )
					)
				)
			);

			$options = $this->Dossierpcg66->enums();

			$etatdossierpcg = $options['Dossierpcg66']['etatdossierpcg'];
			$this->set( 'exists', array( '1' => 'Oui', '0' => 'Non' ) );

			$options = array_merge(
				$options,
				$this->Dossierpcg66->Personnepcg66->Traitementpcg66->enums()
			);
            $this->set( 'natpf', $this->Option->natpf() );
            
            $this->set( 'listorganismes', $this->Dossierpcg66->Decisiondossierpcg66->Orgtransmisdossierpcg66->find(
                    'list',
                    array(
                        'condition' =>  array( 'Orgtransmisdossierpcg66.isactif' => '1' ),
                        'order' => array( 'Orgtransmisdossierpcg66.name ASC' )
                    )
                )
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
					//Liste des traitemeents liés à la personne
					$listeTraitementspcgs66 = Set::extract( $traitementspcgs66, '/Traitementpcg66/typetraitement' );
					$criteresdossierspcgs66[$i]['Dossierpcg66']['listetraitements'] = $listeTraitementspcgs66;

                    // Liste des motifs de la personne (situationpdo)
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
                    $listeMotifs = Set::extract( $listeSituationsPersonnePCG66, '/Situationpdo/libelle' );
					$listeSituationsPersonnePCG66 = $listeMotifs;
					$criteresdossierspcgs66[$i]['Personnepcg66']['listemotifs'] = $listeSituationsPersonnePCG66;
                    
                    // Liste des motifs de la personne (situationpdo)
					$listeStatutsPersonnePCG66 = $this->Dossierpcg66->Personnepcg66->find(
						'all',
						array(
							'fields' => array(
								'Statutpdo.libelle'
							),
							'conditions' => array(
								'Personnepcg66.dossierpcg66_id' => $dossierpcg66_id
							),
							'joins' => array(
								$this->Dossierpcg66->Personnepcg66->join( 'Personnepcg66Statutpdo', array( 'type' => 'LEFT OUTER' ) ),
								$this->Dossierpcg66->Personnepcg66->Personnepcg66Statutpdo->join( 'Statutpdo', array( 'type' => 'LEFT OUTER' ) )
							)
						)
					);
					$listeStatuts = Set::extract( $listeStatutsPersonnePCG66, '/Statutpdo/libelle' );
					$listeStatutsPersonnePCG66 = $listeStatuts;
					$criteresdossierspcgs66[$i]['Personnepcg66']['listestatuts'] = $listeStatutsPersonnePCG66;
                    
                    //Liste des organismes auxquels les dossiers sont transmis 
					$decisionsdossierspcgs66 = $this->Dossierpcg66->Decisiondossierpcg66->find(
                        'all',
                        array(
                             'fields' => array_merge(
                                $this->Dossierpcg66->Decisiondossierpcg66->fields()
                            ),
                            'conditions' => array(
                                'Decisiondossierpcg66.dossierpcg66_id' => $criteredossierpcg66['Dossierpcg66']['id']
                            ),
                            'contain' => array(
                                'Orgtransmisdossierpcg66'
                            ),
                            'order' => array( 'Decisiondossierpcg66.created DESC' )
                        )
                    );

                    $criteresdossierspcgs66[$i]['Decisiondossierpcg66']['organismes'] = Hash::extract( $decisionsdossierspcgs66, '{n}.Orgtransmisdossierpcg66.{n}.name' );
                   
				}

				$this->set( compact( 'criteresdossierspcgs66', 'listeMotifs' ) );
			}
			else {
				$filtresdefaut = Configure::read( "Filtresdefaut.{$this->name}_{$this->action}" );
				$this->request->data = Set::merge( $this->request->data, $filtresdefaut );
			}

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


		/**
		 * Export au format CSV des résultats de la recherche des allocataires transférés.
		 *
		 * @return void
		 */
		public function exportcsv( $searchFunction ) {
			$data = Hash::expand( $this->request->params['named'], '__' );

			$mesZonesGeographiques = (array)$this->Session->read( 'Auth.Zonegeographique' );
			$mesCodesInsee = ( !empty( $mesZonesGeographiques ) ? $mesZonesGeographiques : array() );

			$querydata = $this->Criteredossierpcg66->{$searchFunction}(
				$data,
				$mesCodesInsee,
				$mesZonesGeographiques
			);

//			unset( $querydata['limit'] );
            $querydata['limit'] = 10;

			$results = $this->Dossierpcg66->find(
				'all',
				$querydata
			);

			foreach( $results as $i => $criteredossierpcg66 ) {
				$dossierpcg66_id = Set::classicExtract( $criteredossierpcg66, 'Dossierpcg66.id' );

                //Liste des différents traitements de la personne
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
				$listeTraitementspcgs66 = Set::extract( $traitementspcgs66, '/Traitementpcg66/typetraitement' );
                $results[$i]['Dossierpcg66']['listetraitements'] = $listeTraitementspcgs66;

                //Liste des différentes situations de la personne
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
						),
						'contain' => false
					)
				);


				$listeMotifs = Set::extract( $listeSituationsPersonnePCG66, '/Situationpdo/libelle' );
				$listeSituationsPersonnePCG66 = $listeMotifs;
				$results[$i]['Personnepcg66']['listemotifs'] = $listeSituationsPersonnePCG66;
                
                //Liste des différentes situations de la personne
				$listeStatutsPersonnePCG66 = $this->Dossierpcg66->Personnepcg66->find(
					'all',
					array(
						'fields' => array(
							'Statutpdo.libelle'
						),
						'conditions' => array(
							'Personnepcg66.dossierpcg66_id' => $dossierpcg66_id
						),
						'joins' => array(
							$this->Dossierpcg66->Personnepcg66->join( 'Personnepcg66Statutpdo', array( 'type' => 'LEFT OUTER' ) ),
							$this->Dossierpcg66->Personnepcg66->Personnepcg66Statutpdo->join( 'Statutpdo', array( 'type' => 'LEFT OUTER' ) )
						),
						'contain' => false
					)
				);


				$listeStatuts = Set::extract( $listeStatutsPersonnePCG66, '/Statutpdo/libelle' );
				$listeStatutsPersonnePCG66 = $listeStatuts;
				$results[$i]['Personnepcg66']['listestatuts'] = $listeStatutsPersonnePCG66;

                
                // Liste des organismes
                $decisionsdossierspcgs66 = $this->Dossierpcg66->Decisiondossierpcg66->find(
                    'all',
                    array(
                        'fields' => array_merge(
                            $this->Dossierpcg66->Decisiondossierpcg66->fields()
                        ),
                        'conditions' => array(
                            'Decisiondossierpcg66.dossierpcg66_id' => $dossierpcg66_id
                        ),
                        'contain' => array(
                            'Orgtransmisdossierpcg66'
                        )
                    )
                );

                $listOrgs = Hash::extract( $decisionsdossierspcgs66, '{n}.Orgtransmisdossierpcg66.{n}.name' );
				$listOrganismes = $listOrgs;
				$results[$i]['Decisiondossierpcg66']['listorgs'] = $listOrganismes;
				
			}

			$this->_setOptions();
            
            
//debug($results);
//die();
			$this->layout = '';
			$this->set( compact( 'results') );
		}
	}
?>