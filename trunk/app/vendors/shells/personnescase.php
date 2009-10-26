<?php
	// http://www.unicode.org/Public/MAPPINGS/ISO8859/8859-1.TXT

    function replace_accents( $string ) {
        $accents = array(
			'[âàÂÀ]',
			'[çÇ]',
			'[éêèëÉÊÈË]',
			'[îïÎÏ]',
			'[ôöÔÖ]',
			'[ûùÛÙ]'
		);

        $replace = array(
			'a',
			'c',
			'e',
			'i',
			'o',
			'u'
		);

        foreach( $accents as $key => $accent ) {
            $string = mb_ereg_replace( $accent, $replace[$key], $string );
        }

        return $string;
    }

	//echo strtoupper( replace_accents( $result['nom'].' '.$result['prenom']."\n" ) );

	// *************************************************************************

    class PersonnescaseShell extends Shell
    {
        var $uses = array( 'Personne' );

        function main() {
            /** ****************************************************************
            *   Démarrage du script
            *** ***************************************************************/

            $this_start = microtime( true );
            echo "Demarrage du script: ".date( 'Y-m-d H:i:s' )."\n";

			$this->Personne->begin();
            $success = true;

            /** ****************************************************************
			*
            *** ***************************************************************/

			$doublons = 0;
			$changements = 0;
			$sql = "SELECT * FROM personnes WHERE nom !~ '^([A-Z]|-| |\')+$' OR  prenom !~ '^([A-Z]|-| |\')+$' OR nomnai IS NULL OR nomnai = '';";
			$results = $this->Personne->query( $sql );

			echo sprintf( "%s personnes à traiter\n", count( $results ) );

			foreach( $results as $result ) {
				$result = $result[0];
				$resultXml = $this->Personne->find(
					'first',
					array(
						'conditions' => array(
							'Personne.id <>' => $result['id'],
							'Personne.nom' => strtoupper( $result['nom'] ),
							'Personne.prenom' => strtoupper( $result['prenom'] ),
							'Personne.dtnai' => $result['dtnai']
						),
						'recursive' => -1
					)
				);
				if( !empty( $resultXml ) ) {
					$doublons++;

					$tables = array( 'activites', 'allocationssoutienfamilial', 'avispcgpersonnes', 'calculsdroitsrsa', 'contratsinsertion', 'creancesalimentaires', 'dossierscaf', 'dspps', 'grossesses', 'informationseti', 'infosagricoles', 'orientsstructs', 'prestations', 'rendezvous', 'ressources', 'titres_sejour' );
					foreach( $tables as $table ) {
						$nombre = $this->Personne->query( "SELECT COUNT(id) FROM $table WHERE personne_id = ".$result['id'].";" );
						if( Set::classicExtract( $nombre, '0.0.count' ) > 0 ) {
							$this->Personne->query( "UPDATE $table SET personne_id = ".$resultXml['Personne']['id']." WHERE personne_id = ".$result['id'].";" );
						}
					}
					$success = $this->Personne->delete( $result['id'] ) && $success;
					if( !$success ) { debug( !$success ); }
				}
				else {
					$changements++;

					$personne = array( 'Personne' => Set::filter( $result ) ); // FIXME ?
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
				}
			}

            $this->hr();
			echo sprintf( "%s doublons, %s changements.\n", $doublons, $changements );

            /** ****************************************************************
            *   Fin du script
            *** ***************************************************************/

            $this->hr();

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