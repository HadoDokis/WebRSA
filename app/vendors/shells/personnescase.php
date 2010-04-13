<?php
    class PersonnescaseShell extends Shell
    {
        var $uses = array( 'Personne' );
		var $updateIds = true;
		var $logfile = 'logfile-%s.txt';
		var $debug = '';
		var $doDebug = true;

		/// FIXME: une fois que les tables dspps et les tables liées seront supprimées, à nettoyer
		var $tables = array( 'personnes_referents', 'activites', 'allocationssoutienfamilial', 'avispcgpersonnes', 'calculsdroitsrsa', 'contratsinsertion', 'creancesalimentaires', /*'creancesalimentaires_personnes',*/ 'dossierscaf', 'dsps', 'dspps', 'grossesses', 'informationseti', 'infosagricoles', 'orientsstructs', 'prestations', 'rattachements', 'rendezvous', 'ressources', 'titres_sejour' );
		var $dependances = array(
				'dsps' => array(
					'detailsdifsocs',
					'detailsaccosocfams',
					'detailsaccosocindis',
					'detailsdifdisps',
					'detailsnatmobs',
					'detailsdiflogs',
				),
				'dspps' => array(
					'dspps_accoemplois',
					'dspps_difdisps',
					'dspps_difsocs',
					'dspps_nataccosocindis',
					'dspps_natmobs',
					'dspps_nivetus'
				)
			);

		/**
		*
		*/

		function cleanRecords( $table, $personneAppliId, $personneXmlId ) {
			$success = true;
			$sql = "SELECT * FROM $table WHERE personne_id = $personneAppliId";
			$recordsAppli = $this->Personne->query( $sql );

			$sql = "SELECT * FROM $table WHERE personne_id = $personneXmlId";
			$recordsXml = $this->Personne->query( $sql );

			if( !( empty( $recordsAppli ) && empty( $recordsXml ) ) ) {
				if( empty( $recordsAppli ) ) {
					$pIdToKeep = $personneXmlId;
					$pIdToDelete = $personneAppliId;
				}
				else if( empty( $recordsXml ) ) {
					$pIdToKeep = $personneAppliId;
					$pIdToDelete = $personneXmlId;
				}
				else {
					// FIXME: voir quelles entrées sont bonnes
					if( in_array( $table, array( 'creancesalimentaires', 'dsps', 'dspps', 'orientsstructs' ) ) ) {
						$pIdToKeep = $personneAppliId;
						$pIdToDelete = $personneXmlId;
					}
					else {
						$pIdToKeep = $personneXmlId;
						$pIdToDelete = $personneAppliId;
					}
				}

				///
				if( $pIdToKeep == $personneXmlId ) {
					$recordsToUpdate = $recordsXml;
					$recordsToDelete = $recordsAppli;
				}
				else {
					$recordsToUpdate = $recordsAppli;
					$recordsToDelete = $recordsXml;
				}

				///
				if( !empty( $recordsToDelete ) ) {
					if( $table == 'dsps' || $table == 'dspps' ) {
						if( isset( $this->dependances[$table] ) ) {
							$fk = strtolower( Inflector::classify( $table ) ).'_id';

							foreach( $this->dependances[$table] as $tableLiee ) {
								$sql = "DELETE FROM $tableLiee WHERE $fk = ( SELECT id FROM $table WHERE personne_id = ".$pIdToDelete." );";
								if( $this->doDebug ) $this->debug .= $sql."\n";
								$this->Personne->query( $sql );
							}
						}
					}

					$sql = "DELETE FROM $table WHERE personne_id = ".$pIdToDelete.";";
					if( $this->doDebug ) $this->debug .= $sql."\n";
					$this->Personne->query( $sql );
				}

				if( !empty( $recordsToUpdate ) && ( $personneXmlId != $pIdToKeep ) ) {
					$sql = "UPDATE $table SET personne_id = ".$personneXmlId." WHERE personne_id = ".$pIdToKeep.";";
					if( $this->doDebug ) $this->debug .= $sql."\n";
					$this->Personne->query( $sql );
				}

				if( $this->doDebug ) {
					ob_start();
					debug(
						array(
							$recordsAppli,
							$recordsXml
						)
					);
					$this->debug .= "\n".ob_get_clean()."\n";
				}
			}
			return $success; // FIXME
		}

		/**
		*
		*/

        function startup() {
			$this->logfile = APP_DIR.'/tmp/logs/'.str_replace( 'shell', '', strtolower( $this->name ) ).'-%s.txt';
		}

		/**
		*
		*/

        function main() {
            ///   Démarrage du script
            $this_start = microtime( true );
            echo "Demarrage du script: ".date( 'Y-m-d H:i:s' )."\n";

			$this->Personne->begin();
            $success = true;
			$doublons = 0;
			$changements = 0;
			//$countXml = array();

			/// Recherche des personnes rentrées à la main
			//$exemple = array( 1463, 58679 );
// 			$exemple = array( 1463, 58679 );
			$sql = "SELECT * FROM personnes WHERE ( nom !~ '^([A-Z]|-| |\')+$' OR  prenom !~ '^([A-Z]|-| |\')+$' OR nomnai IS NULL OR nomnai = '' );";
			// /* AND personnes.id IN ( ".implode( ", ", $exemple )." )*/ LIMIT 5 OFFSET 10
			$personneApplis = $this->Personne->query( $sql );

			echo sprintf( "%s personnes à traiter\n", count( $personneApplis ) );

            /**
                // Voir les autres doublons ... problème: quel tuple doit-on garder ?
                SELECT p1.*
                    FROM personnes AS p1, personnes AS p2
                    WHERE p1.nir = p2.nir
                        AND p1.id <> p2.id
                        AND p1.foyer_id = p2.foyer_id
                        AND p1.nir <> '' AND p1.nir IS NOT NULL
                    ORDER BY p1.nom ASC, p1.prenom ASC, p1.id ASC
            */

			$this->hr();

			if( $this->updateIds ) {
				echo "Mise à jour des ids\n";
			}

			/// Mise à jour des ids
			foreach( $personneApplis as $personneAppli ) {
				$personneAppli = $personneAppli[0];

				if( $this->doDebug ) {
					$sql = "SELECT * FROM personnes WHERE id <> ".$personneAppli['id'].
								" AND nom = '".strtoupper( $personneAppli['nom'] )."'".
								" AND prenom = '".strtoupper( $personneAppli['prenom'] )."'".
								" AND dtnai = '".date( 'Y-m-d',  strtotime( $personneAppli['dtnai'] ) )."'".
								" AND foyer_id = '".$personneAppli['foyer_id']."';";
					$this->debug .= $sql."\n";
				}

				$personneXml = $this->Personne->find(
					'first',
					array(
						'conditions' => array(
							'Personne.id <>' => $personneAppli['id'],
							'Personne.nom' => strtoupper( $personneAppli['nom'] ),
							'Personne.prenom' => strtoupper( $personneAppli['prenom'] ),
							'Personne.dtnai' => date( 'Y-m-d',  strtotime( $personneAppli['dtnai'] ) ),
							'Personne.foyer_id' => $personneAppli['foyer_id']
						),
						'recursive' => -1
					)
				);
				$personneXml = $personneXml['Personne'];

				if( $this->updateIds ) {
					if( !empty( $personneXml ) ) {
						$doublons++;

						foreach( $this->tables as $table ) {
							$success = $this->cleanRecords( $table, $personneAppli['id'], $personneXml['id'] ) && $success;
						}

						$sql = "DELETE FROM personnes WHERE id = ".$personneAppli['id'].";";
						if( $this->doDebug ) $this->debug .= $sql."\n";

						$success = $this->Personne->delete( $personneAppli['id'] ) && $success;
						if( !$success ) die();
					}
					// Mise en majuscule de nom, prénom, et remplissage de nomnai
					else {
						$changements++;

						$personne = array( 'Personne' => Set::filter( $personneAppli ) ); // FIXME ?
						$personne['Personne']['nom'] = strtoupper( replace_accents( $personne['Personne']['nom'] ) );
						$personne['Personne']['prenom'] = strtoupper( replace_accents( $personne['Personne']['prenom'] ) );
						if( empty( $personne['Personne']['nomnai'] ) ) {
							$personne['Personne']['nomnai'] = $personne['Personne']['nom'];
						}
						else {
							$personne['Personne']['nomnai'] = strtoupper( replace_accents( $personne['Personne']['nomnai'] ) );
						}

						$this->Personne->validate = null;
						$this->Personne->create( $personne );
						$success = $this->Personne->save() && $success;
						if( !$success ) die();
					}
				}
			}

			if( $this->updateIds ) {
				$this->hr();
			}

			echo sprintf( "%s doublons, %s changements.\n", $doublons, $changements );

            /** ****************************************************************
            *   Fin du script
            *** ***************************************************************/

            $this->hr();

			file_put_contents( sprintf( $this->logfile, date( 'Ymd-His' ) ), $this->debug );

            if( $success ) {
				$this->Personne->commit();
                echo "Script termine avec succes: ".date( 'Y-m-d H:i:s' ).'( en '.number_format( microtime( true ) - $this_start, 2 ).' secondes )'."\n";
                return 0;
            }
            else {
				$this->Personne->rollback();
                echo "Script termine avec erreurs: ".date( 'Y-m-d H:i:s' ).'( en '.number_format( microtime( true ) - $this_start, 2 ).' secondes )'."\n";
                return 1;
            }
        }
    }
?>