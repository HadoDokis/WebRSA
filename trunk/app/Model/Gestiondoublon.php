<?php
	/**
	 * Code source de la classe Gestiondoublon.
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Gestiondoublon ...
	 *
	 * @package app.Model
	 */
	class Gestiondoublon extends AppModel
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'Gestiondoublon';

		/**
		 * On n'utilise pas de table.
		 *
		 * @var boolean
		 */
		public $useTable = false;

		/**
		 * Behaviors utilisés par le modèle.
		 *
		 * @var array
		 */
		public $actsAs = array(
			'Conditionnable'
		);

		/**
		 * Existe-t'il des fichiers modules liés aux enregistrements que nous
		 * voulons fusionner ?
		 *
		 * @param array $donnees
		 * @return array
		 */
		public function fichiersModuleLies( array $data, $extractPath = '/%s/%s/id' ) {
			$conditions = array();

			$modelNames = array_keys( $data );

			if( !empty( $modelNames ) ) {
				foreach( $modelNames as $modelName ) {
					if( $modelName != 'Fichiermodule' ) {
						$values = Set::extract( $data, str_replace( '%s', $modelName, $extractPath ) );
						$conditions[] = array(
							'Fichiermodule.modele' => $modelName,
							'Fichiermodule.fk_value' => $values
						);
					}
				}
			}

			$query = array(
				'fields' => array(
					'Fichiermodule.modele',
					'Fichiermodule.fk_value',
				),
				'conditions' => array(
					'OR' => $conditions
				),
				'contain' => false,
				'order' => array(
					'Fichiermodule.modele ASC',
					'Fichiermodule.fk_value DESC',
				)
			);

			$results = ClassRegistry::init( 'Fichiermodule' )->find( 'all', $query );

			return $results;
		}

		/**
		 *
		 * @param array $search
		 * @param integer $differenceThreshold
		 * @return array
		 */
		public function searchComplexes( array $search = array(), $differenceThreshold = 4 ) {
			$Foyer = ClassRegistry::init( 'Foyer' );

			$conditionsJoinDemandeur = array(
				'OR' => array(
					'Personne.id IN ( SELECT prestations.personne_id FROM prestations WHERE prestations.personne_id = Personne.id AND prestations.natprest = \'RSA\' AND prestations.rolepers = \'DEM\' ORDER BY prestations.personne_id ASC LIMIT 1 )',
					'Personne.id IS NULL'
				)
			);

			$etatdosrsa2 = (array)Configure::read( 'Gestiondoublon.Situationdossierrsa2.etatdosrsa' );
			if( empty( $etatdosrsa2 ) ) {
				$etatdosrsa2 = array( 'Z' );
			}

			$query = array(
				'joins' => array(
					$Foyer->join(
						'Adressefoyer',
						array(
							'type' => 'LEFT OUTER',
							'conditions' => array(
								'OR' => array(
									'Adressefoyer.id IS NULL',
									'Adressefoyer.id IN ( '.$Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
								)
							)
						)
					),
					$Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
					$Foyer->join( 'Personne', array( 'type' => 'INNER' ) ),
					$Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
					$Foyer->Dossier->join( 'Situationdossierrsa', array( 'type' => 'LEFT OUTER' ) ),
					$Foyer->Personne->join( 'Prestation', array( 'type' => 'LEFT OUTER' ) ),
					array_words_replace(
						$Foyer->join(
							'Personne',
							array(
								'type' => 'LEFT OUTER',
								'conditions' => $conditionsJoinDemandeur
							)
						),
						array( 'Personne' => 'Demandeur' )
					),
					// Jointure avec ce qui est en doublon
					array(
						'table'      => 'personnes',
						'alias'      => 'p2',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id <> p2.id',
							'OR' => array(
								array(
									'nir_correct13(Personne.nir)',
									'nir_correct13(p2.nir)',
									'SUBSTRING( TRIM( BOTH \' \' FROM Personne.nir ) FROM 1 FOR 13 ) = SUBSTRING( TRIM( BOTH \' \' FROM p2.nir ) FROM 1 FOR 13 )',
									'Personne.dtnai = p2.dtnai'
								),
								array(
									'UPPER(Personne.nom) = UPPER(p2.nom)',
									'UPPER(Personne.prenom) = UPPER(p2.prenom)',
									'Personne.dtnai = p2.dtnai'
								),
								// TODO: seulement si on a la fonction
								array(
									'difference(Personne.nom, p2.nom) >=' => $differenceThreshold,
									'difference(Personne.prenom, p2.prenom) >=' => $differenceThreshold,
									'Personne.dtnai = p2.dtnai'
								),
							)
						),
					),
					array(
						'table'      => 'foyers',
						'alias'      => 'Foyer2',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'p2.foyer_id = Foyer2.id',
							'p2.foyer_id <> Foyer.id',
						)
					),
					array_words_replace(
						$Foyer->join(
							'Adressefoyer',
							array(
								'type' => 'LEFT OUTER',
								'conditions' => array(
									'OR' => array(
										'Adressefoyer.id IS NULL',
										'Adressefoyer.id IN ( '.$Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
									)
								)
							)
						),
						array( 'Foyer' => 'Foyer2', 'Adressefoyer' => 'Adressefoyer2' )
					),
					array_words_replace(
						$Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
						array( 'Adressefoyer' => 'Adressefoyer2', 'Adresse' => 'Adresse2' )
					),
					array(
						'table'      => 'dossiers',
						'alias'      => 'Dossier2',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Foyer2.dossier_id = Dossier2.id',
							'Dossier2.id <> Dossier.id'
						)
					),
					array(
						'table'      => 'situationsdossiersrsa',
						'alias'      => 'Situationdossierrsa2',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Situationdossierrsa2.dossier_id = Dossier2.id',
							'Situationdossierrsa2.etatdosrsa' => $etatdosrsa2
						)
					),
					array_words_replace(
						$Foyer->join(
							'Personne',
							array(
								'type' => 'LEFT OUTER',
								'conditions' => $conditionsJoinDemandeur
							)
						),
						array( 'Personne' => 'Demandeur2' )
					),
				),
				'conditions' => array(
					array(
						'OR' => array(
							'Prestation.id IS NULL',
							'Prestation.rolepers' => array( 'DEM', 'CJT' ),
						)
					)
				),
				'contain' => false,
				'order' => array(
					'Demandeur.nom',
					'Demandeur.prenom',
					'Dossier.matricule',
					'Dossier.dtdemrsa DESC',
					'Dossier.id',
				)
			);

			$query['conditions'] = $this->conditionsPersonneFoyerDossier( $query['conditions'], $search );
			$query['conditions'] = array_words_replace( $query['conditions'], array( 'Personne' => 'Demandeur' ) );

			$query['fields'] = $query['group'] = Hash::merge(
				$Foyer->fields(),
				$Foyer->Dossier->fields(),
				$Foyer->Dossier->fields(),
				$Foyer->Adressefoyer->Adresse->fields(),
				$Foyer->Dossier->Situationdossierrsa->fields(),
				array_words_replace( $Foyer->Personne->fields(), array( 'Personne' => 'Demandeur' ) ),
				array_words_replace( $Foyer->fields(), array( 'Foyer' => 'Foyer2' ) ),
				array_words_replace( $Foyer->Dossier->fields(), array( 'Dossier' => 'Dossier2' ) ),
				array_words_replace( $Foyer->Adressefoyer->Adresse->fields(), array( 'Adresse' => 'Adresse2' ) ),
				array_words_replace( $Foyer->Dossier->Situationdossierrsa->fields(), array( 'Situationdossierrsa' => 'Situationdossierrsa2' ) ),
				array_words_replace( $Foyer->Personne->fields(), array( 'Personne' => 'Demandeur2' ) )
			);

			return $query;
		}

		/**
		 * Fusion de deux foyers et des enregistrements liés.
		 *
		 * @param integer $foyer1_id
		 * @param integer $foyer2_id
		 * @param array $results
		 * @param array $data
		 * @return boolean
		 */
		public function fusionComplexe( $foyer1_id, $foyer2_id, array $results, array $data ) {
			$Foyer = ClassRegistry::init( 'Foyer' );
			$success = true;

			$foyerAGarderId = Hash::get( $data, 'Foyer.id' );
			$foyerASupprimerId = ( ( $foyerAGarderId == $foyer1_id ) ? $foyer2_id : $foyer1_id );

			$success = true;
			$Foyer->begin();

			foreach( $data as $modelName => $values ) {
				if( !in_array( $modelName, array( 'Foyer', 'Save' ) ) ) {
					$ids = Hash::extract( $results, "{n}.{$modelName}.{n}.id" );
					$idsAGarder = Hash::extract( $data, "{$modelName}.id" );
					$idsASupprimer = array_diff( $ids, $idsAGarder );

					if( !empty( $idsAGarder ) ) {
						$success = $Foyer->{$modelName}->updateAllUnbound(
							array( "{$modelName}.foyer_id" => $foyerAGarderId ),
							array( "{$modelName}.id" => $idsAGarder )
						) && $success;
					}

					if( !empty( $idsASupprimer ) ) {
						$success = $Foyer->{$modelName}->deleteAll(
							array( "{$modelName}.id" => $idsASupprimer )
						) && $success;
					}
				}
			}

			$dossier = $Foyer->find(
				'first',
				array(
					'fields' => array( 'Dossier.id' ),
					'contain' => array(
						'Dossier'
					),
					'conditions' => array(
						'Foyer.id' => $foyerASupprimerId
					)
				)
			);

			$success = $Foyer->Dossier->delete( $dossier['Dossier']['id'] ) && $success;

			return $success;
		}
	}
?>