<?php
    class RefreshShell extends Shell
    {
        var $uses = array( 'Foyer', 'Cohorte', 'Typeorient', 'Orientstruct' );

        function main() {
            /** ****************************************************************
            *   Démarrage du script
            *** ***************************************************************/

            $this_start = microtime( true );
            echo "Demarrage du script de rafraichissement: ".date( 'Y-m-d H:i:s' )."\n";

            // $this->Foyer->begin();
            $saved = true;

            /** ****************************************************************
            *   Réparation des données du flux CAF (les rgadr ne sont pas sur deux chiffres)
            *   Si le rang est bien formé, il n'y a pas de mise à jour
            *** ***************************************************************/
//             $this->hr();
//
//             echo 'Debut de la mise a jour des rangs adresse: '.number_format( microtime( true ) - $this_start, 2 )."\n";
//
//             $adressesFoyers = $this->Foyer->Adressefoyer->find( 'list', array( 'fields' => array( 'Adressefoyer.id', 'Adressefoyer.rgadr' ) ) );
//             foreach( $adressesFoyers as $id => $rgadr ) {
//                 $rgadr = trim( $rgadr );
//                 if( strlen( $rgadr ) == 1 ) {
//                     $rgadr = '0'.$rgadr;
//                     $this->Foyer->Adressefoyer->create( array( 'Adressefoyer' => array( 'id' => $id, 'rgadr' => $rgadr ) ) );
//                     $saved = $this->Foyer->Adressefoyer->save() && $saved;
//                 }
//             }
//
//             echo 'Fin de la mise a jour des rangs adresse: '.number_format( microtime( true ) - $this_start, 2 )."\n";

            /** ****************************************************************
            *   Rafraichissement de "soumis à droits et devoirs" pour la table
            *   orientsstructs
            *** ***************************************************************/
            // FIXME: ajouter une entrée dans la table orientsstructs ?

            $this->hr();
            echo 'Debut de la mise a jour des orientsstructs: '.number_format( microtime( true ) - $this_start, 2 )."\n";

			$typesOrient = $this->Typeorient->find(
				'list',
				array(
					'fields' => array(
						'Typeorient.id',
						'Typeorient.lib_type_orient'
					),
					'order' => 'Typeorient.lib_type_orient ASC'
				)
			);
			$typesOrient = array_flip( $typesOrient );

            $foyers = $this->Foyer->find( 'list', array( 'fields' => array( 'Foyer.id', 'Foyer.id' ), 'order' => 'Foyer.id ASC' ) );
            foreach( $foyers as $foyer_id ) {
                //$tBoucle0 = microtime( true );
                $refreshRessources = $this->Foyer->refreshRessources( $foyer_id );
                if( !$refreshRessources ) {
                    echo "Erreur Foyer->refreshRessources pour l\'id $foyer_id\n";
                }

                $refreshSoumisADroitsEtDevoirs = $this->Foyer->refreshSoumisADroitsEtDevoirs( $foyer_id );
                if( !$refreshRessources ) {
                    echo "Erreur Foyer->refreshSoumisADroitsEtDevoirs pour l\'id $foyer_id\n";
                }

                $saved = $refreshRessources && $refreshSoumisADroitsEtDevoirs && $saved;
                // avant 17/08/2009 entre 0.20 et 0.40 secondes
                // le 17/08/2009 entre 0.10 et 0.20 secondes
                //echo '1 passage dans la boucle: '.number_format( microtime( true ) - $tBoucle0, 2 )."\n";

				// Calcul et sauvegarde des pré-orientations
				$this->Foyer->Personne->bindModel( array( 'hasOne' => array( 'Dspp' ), 'belongsTo' => array( 'Foyer' ) ) );
				$this->Foyer->Personne->Dspp->unbindModelAll();
				$this->Foyer->unbindModelAll();
				$this->Foyer->bindModel( array( 'hasOne' => array( 'Dspf' ) ) );
				$this->Foyer->Dspf->unbindModelAll();

				$personnes = $this->Foyer->Personne->find(
					'all',
					array(
						'fields' => array(
							'Personne.id',
							'Personne.foyer_id',
							'Personne.qual',
							'Personne.nom',
							'Personne.prenom',
							'Personne.nomnai',
							'Personne.prenom2',
							'Personne.prenom3',
							'Personne.nomcomnai',
							'Personne.dtnai',
							'Personne.rgnai',
							'Personne.typedtnai',
							'Personne.nir',
							'Personne.topvalec',
							'Personne.sexe',
							'Personne.nati',
							'Personne.dtnati',
							'Personne.pieecpres',
							'Personne.idassedic',
							'Foyer.id',
							'Foyer.dossier_rsa_id',
							'Foyer.sitfam',
							'Foyer.ddsitfam',
							'Foyer.typeocclog',
							'Foyer.mtvallocterr',
							'Foyer.mtvalloclog',
							'Foyer.contefichliairsa',
							'Dspp.id',
							'Dspp.personne_id',
							'Dspp.drorsarmiant',
							'Dspp.drorsarmianta2',
							'Dspp.couvsoc',
							'Dspp.libautrdifsoc',
							'Dspp.elopersdifdisp',
							'Dspp.obstemploidifdisp',
							'Dspp.soutdemarsoc',
							'Dspp.libautraccosocindi',
							'Dspp.libcooraccosocindi',
							'Dspp.annderdipobt',
							'Dspp.rappemploiquali',
							'Dspp.rappemploiform',
							'Dspp.libautrqualipro',
							'Dspp.permicondub',
							'Dspp.libautrpermicondu',
							'Dspp.libcompeextrapro',
							'Dspp.persisogrorechemploi',
							'Dspp.libcooraccoemploi',
							'Dspp.hispro',
							'Dspp.libderact',
							'Dspp.libsecactderact',
							'Dspp.dfderact',
							'Dspp.domideract',
							'Dspp.libactdomi',
							'Dspp.libsecactdomi',
							'Dspp.duractdomi',
							'Dspp.libemploirech',
							'Dspp.libsecactrech',
							'Dspp.creareprisentrrech',
							'Dspp.moyloco',
							'Dspp.diplomes',
							'Dspp.dipfra',
							'Orientstruct.id',
							'Orientstruct.personne_id',
							'Orientstruct.typeorient_id',
							'Orientstruct.structurereferente_id',
							'Orientstruct.propo_algo',
							'Orientstruct.valid_cg',
							'Orientstruct.date_propo',
							'Orientstruct.date_valid',
							'Orientstruct.statut_orient',
							'Orientstruct.date_impression',
							'Orientstruct.daterelance',
							'Orientstruct.statutrelance'
						),
						'joins' => array(
							array(
								'table'      => 'prestations',
								'alias'      => 'Prestation',
								'type'       => 'INNER',
								'foreignKey' => false,
								'conditions' => array(
									'Prestation.personne_id = Personne.id',
									'Prestation.natprest = \'RSA\'',
									'( Prestation.rolepers = \'DEM\' OR Prestation.rolepers = \'CJT\' )'
								)
							),
							array(
								'table'      => 'orientsstructs',
								'alias'      => 'Orientstruct',
								'type'       => 'INNER',
								'foreignKey' => false,
								'conditions' => array(
									'Orientstruct.personne_id = Personne.id',
									'Orientstruct.id = Orientstruct.id',
									'Orientstruct.statut_orient <> \'Orienté\''
								)
							),
						),
						'conditions' => array( 'Personne.foyer_id' => $foyer_id ),
						'recursive' => 2
					)
				);
				foreach( $personnes as $personne ) {
					$orientstruct = array( 'Orientstruct' => Set::classicExtract( $personne, 'Orientstruct' ) );
					$orientstruct['Orientstruct']['propo_algo_texte'] = $this->Cohorte->preOrientation( $personne );
					$orientstruct['Orientstruct']['propo_algo'] = Set::enum( $orientstruct['Orientstruct']['propo_algo_texte'], $typesOrient );

					$this->Orientstruct->create( $orientstruct );
					$this->Orientstruct->validate = array();
					$saved = $this->Orientstruct->save() && $saved;
				}
            }

            echo 'Fin de la mise a jour des orientsstructs: '.number_format( microtime( true ) - $this_start, 2 )."\n";

            /** ****************************************************************
            *   Fin du script
            *** ***************************************************************/

            $this->hr();

            if( $saved ) {
                //$this->Foyer->commit();
                echo "Script de rafraicissement termine avec succes: ".date( 'Y-m-d H:i:s' ).'( en '.number_format( microtime( true ) - $this_start, 2 ).' secondes )'."\n";
                return 0;
            }
            else {
                //$this->Foyer->rollback();
                echo "Script de rafraicissement termine avec erreurs: ".date( 'Y-m-d H:i:s' ).'( en '.number_format( microtime( true ) - $this_start, 2 ).' secondes )'."\n";
                return 1;
            }

        }
    }
?>