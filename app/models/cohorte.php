<?php
    App::import( 'Sanitize' );
//     App::import( 'Dossier' );

    // ************************************************************************

    class Cohorte extends AppModel
    {
        var $name = 'Cohorte';
        var $useTable = false;

		/**
		* Dernière version des règles de préorientation (changement règle 4):
		* 16/04/2010, par mail
		*/

        function preOrientation( $element ) {
            $propo_algo = null;

			/// Dsp
			$this->Dsp = Classregistry::init( 'Dsp' );
			$this->Dsp->unbindModel( array( 'belongsTo' => array( 'Personne' ) ) );
			$dsp = $this->Dsp->find(
				'first',
				array(
					'conditions' => array( 'Dsp.personne_id' => $element['Personne']['id'] ),
					'recursive' => 1
				)
			);

// 			$element['Personne'] = Set::merge( $element['Personne'], $dsp );

			/// Règles de gestion

			// Règle 1 (Prioritaire) : Code XML instruction : « NATLOG ». Nature du logement ?
			// 0904 = Logement d'urgence : CHRS → Orientation vers le Social
			// 0911 = Logement précaire : résidence sociale → Orientation vers le Social
			$natlog = Set::classicExtract( $dsp, 'Dsp.natlog' );
			if( empty( $propo_algo ) && !empty( $natlog ) ) {
				if( in_array( $natlog, array( '0904', '0911' ) ) ) {
					$propo_algo = 'Social';
				}
			}

			// Règle 2 (Prioritaire)  : Code XML instruction : « DIFLOG ». Difficultés logement ?
			// 1006 = Fin de bail, expulsion → Orientation vers le Service Social
			$diflog = Set::extract( $dsp, '/Detaildiflog/diflog' );
			if( empty( $propo_algo ) && !empty( $diflog ) ) {
				if( in_array( '1006', $diflog ) ) {
					$propo_algo = 'Social';
				}
			}

			//
			// Règle 3 (Prioritaire)  : Code XML instruction : « sitpersdemrsa ». "Quel est le motif de votre demande de rSa ?"
			// 0102 = Fin de droits AAH → Orientation vers le Social
			// 0105 = Attente de pension vieillesse ou invalidité‚ ou d'allocation handicap → Orientation vers le Social
			// 0109 = Fin d'études → Orientation vers le Pôle Emploi
			// 0101 = Fin de droits ASSEDIC → Orientation vers le Pôle Emploi
			$sitpersdemrsa = Set::extract( $dsp, 'Dsp.sitpersdemrsa' );
			if( empty( $propo_algo ) && !empty( $sitpersdemrsa ) ) {
				if( in_array( $sitpersdemrsa, array( '0102', '0105' ) ) ) {
					$propo_algo = 'Social';
				}
				else if( in_array( $sitpersdemrsa, array( '0109', '0101' ) ) ) {
					$propo_algo = 'Emploi';
				}
			}

			// Règle 4 : Code XML instruction : « DTNAI ». Date de Naissance.
			$dtnai = Set::extract( $element, 'Personne.dtnai' );
			/// FIXME: change chaque année ...
			$cessderact = Set::extract( $dsp, 'Dsp.cessderact' );

			// Si le code CESSDERACT n'est pas renseigné : Règle 5
			if( empty( $propo_algo ) && !empty( $cessderact ) ) {
				$age = age( $dtnai );

				// Si - de 57 a :
				// "2701" : Encore en activité ou cessation depuis moins d'un an ->Pôle Emploi
				// "2702" : Cessation d'activité depuis plus d'un an -> PDV
				if( $age < 57 ) {
					if( $cessderact == '2701' ) {
						$propo_algo = 'Emploi';
					}
					else if( $cessderact == '2702' ) {
						$propo_algo = 'Socioprofessionnelle';
					}
				}

				// Si + de 57 a :
				// "2701" : Encore en activité ou cessation depuis moins d'un an -> PDV
				// "2702" : Cessation d'activité depuis plus d'un an ->Service Social
				else if( $age >= 57 ) {
					if( $cessderact == '2701' ) {
						$propo_algo = 'Socioprofessionnelle';
					}
					else if( $cessderact == '2702' ) {
						$propo_algo = 'Social';
					}
				}

				/*// + 57 Ans ( Date du jour) :
				// Code XML instruction : « DFDERACT » (Date éventuelle de cessation de cette activité) = -1ans ( Date du jour) → Orientation vers le PDV
				// Code XML instruction : « DFDERACT» (Date éventuelle de cessation de cette activité) = +1ans ( Date du jour) → Orientation vers le Service Social
				if( $age >= 57 ) {
					if( $cessderact == '2701' ) {
						$propo_algo = 'Socioprofessionnelle';
					}
					else {
						$propo_algo = 'Social';
					}
				}
				// -57 Ans ( Date du jour) :
				// Code XML instruction : « DFDERACT» (Date éventuelle de cessation de cette activité) = -1ans ( Date du jour)→ Orientation vers le Pôle Emploi
				// Code XML instruction : « DFDERACT »  (Date éventuelle de cessation de cette activité) = entre 1 et 5 ans ( Date du jour) → Orientation vers le PDV
				// Code XML instruction : « DFDERACT »  (Date éventuelle de cessation de cette activité) = +5 ans ( Date du jour) → Orientation vers le Service Social
				else {
					if( $cessderact == '2701' ) {
						$propo_algo = 'Emploi';
					}
					// FIXME: on ne peut plus savoir avec les nouvelles DSP
					// else if( $cessderact < 5 ) {
					// 	$propo_algo = 'Socioprofessionnelle';
					// }
					// else {
					// 	$propo_algo = 'Social';
					// }
				}*/
			}

			// Règle 5 : Code XML instruction : « HISPRO ». Question : Passé professionnel ?
			// 1901 = Oui → Orientation vers le Pôle Emploi
			// 1902 = Oui → Orientation vers le PDV
			// 1903 = Oui → Orientation vers le PDV
			// 1904 = Oui → Orientation vers le PDV
			$hispro = Set::extract( $dsp, 'Dsp.hispro' );
			if( empty( $propo_algo ) && !empty( $hispro ) ) {
				if( $hispro == '1901' ) {
					$propo_algo = 'Emploi';
				}
				else if( in_array( $hispro, array( '1902', '1903', '1904' ) ) ) {
					$propo_algo = 'Socioprofessionnelle';
				}
			}

            return $propo_algo;
		}

		/**
		*
		*/

        /*function search( $statutOrientation, $mesCodesInsee, $filtre_zone_geo, $criteres, $lockedDossiers, $limit = PHP_INT_MAX ) {
            /// Requête
            $Situationdossierrsa =& ClassRegistry::init( 'Situationdossierrsa' );

            /// Conditions de base
            $conditions = array(
                'orientsstructs.statut_orient = \''.Sanitize::clean( $statutOrientation ).'\''
            );

			if( $statutOrientation == 'Orienté' ) {
				// INFO: nouvelle manière de générer les PDFs
				$conditions[] = 'orientsstructs.id IN ( SELECT pdfs.fk_value FROM pdfs WHERE modele = \'Orientstruct\' )';
			}
			else {
				// INFO: ne faire apparaître les personnes à orienter que si la personne est soumise à droits et devoirs
                $conditions[] = 'calculsdroitsrsa.toppersdrodevorsa = \'1\'';
				// INFO: ne faire apparaître les personnes à orienter que si le dossier se trouve dans un état ouvert
                $conditions[] = '( situationsdossiersrsa.etatdosrsa IN ( \''.implode( '\', \'', $Situationdossierrsa->etatOuvert() ).'\' ) )';
			}

            /// Filtre zone géographique
            if( $filtre_zone_geo ) {
                $mesCodesInsee = ( !empty( $mesCodesInsee ) ? $mesCodesInsee : '0' );
                $conditions[] = 'Adresse.numcomptt IN ( \''.implode( '\', \'', $mesCodesInsee ).'\' )';
            }

            /// Dossiers lockés
            if( !empty( $lockedDossiers ) ) {
                $conditions[] = 'dossiers_rsa.id NOT IN ( '.implode( ', ', $lockedDossiers ).' )';
            }

            /// Critères
            $oridemrsa = Set::extract( $criteres, 'Filtre.oridemrsa' );
            $locaadr = Set::extract( $criteres, 'Filtre.locaadr' );
            $numcomptt = Set::extract( $criteres, 'Filtre.numcomptt' );
            $codepos = Set::extract( $criteres, 'Filtre.codepos' );
            $dtdemrsa = Set::extract( $criteres, 'Filtre.dtdemrsa' );
            $date_impression = Set::extract( $criteres, 'Filtre.date_impression' );
            $date_print = Set::extract( $criteres, 'Filtre.date_print' );
            $modeles = Set::extract( $criteres, 'Filtre.typeorient' );
            //-------------------------------------------------------
            $cantons = Set::extract( $criteres, 'Filtre.cantons' );


            /// FIXME: dans le modèle
            $typeorient = Set::classicExtract( $criteres, 'Filtre.typeorient' );
            if( !empty( $typeorient ) ) {
                if( Configure::read( 'with_parentid' ) ) {
                    $conditions[] = 'orientsstructs.typeorient_id IN ( SELECT typesorients.id FROM typesorients WHERE typesorients.parentid = \''.Sanitize::clean( $typeorient ).'\' )';
                }
                else {
                    $conditions[] = 'orientsstructs.typeorient_id = \''.Sanitize::clean( $typeorient ).'\'';
                }
            }
//             if( !empty( $modeles ) ) {
//                 $conditions[] = 'orientsstructs.typeorient_id = \''.Sanitize::clean( $modeles ).'\'';
//             }
            //-------------------------------------------------------

            // Origine de la demande
            if( !empty( $oridemrsa ) ) {
                $conditions[] = 'detailsdroitsrsa.oridemrsa IN ( \''.implode( '\', \'', $oridemrsa ).'\' )';
            }

            // Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
            $filtersPersonne = array();
            foreach( array( 'nom', 'prenom', 'nomnai' ) as $criterePersonne ) {
                if( isset( $criteres['Filtre'][$criterePersonne] ) && !empty( $criteres['Filtre'][$criterePersonne] ) ) {
                    $conditions[] = 'personnes.'.$criterePersonne.' ILIKE \''.$this->wildcard( replace_accents( $criteres['Filtre'][$criterePersonne] ) ).'\'';
                }
            }

            // Localité adresse
            if( !empty( $locaadr ) ) {
                $conditions[] = 'Adresse.locaadr ILIKE \'%'.Sanitize::clean( $locaadr ).'%\'';
            }
            // Commune au sens INSEE
            if( !empty( $numcomptt ) ) {
                $conditions[] = 'Adresse.numcomptt = \''.Sanitize::clean( $numcomptt ).'\'';
            }
            // Code postal adresse
            if( !empty( $codepos ) ) {
                $conditions[] = 'Adresse.codepos = \''.Sanitize::clean( $codepos ).'\'';
            }

            /// Critères sur l'adresse - canton
			if( Configure::read( 'CG.cantons' ) ) {
				if( isset( $criteres['Canton']['canton'] ) && !empty( $criteres['Canton']['canton'] ) ) {
					$this->Canton =& ClassRegistry::init( 'Canton' );
					$tmpConditions = $this->Canton->queryConditions( $criteres['Canton']['canton'] );
					$_conditions = array();
					foreach( $tmpConditions['or'] as $tmpCondition ) {
						$_condition = array();
						foreach( $tmpCondition as $field => $value ) {
							if( valid_int( $value ) ) {
								$_condition[] = "$field = '".str_replace( "'", "\\'", $value )."'";
							}
							else {
								$_condition[] = "$field '".str_replace( "'", "\\'", $value )."'";
							}
						}
						if( !empty( $_condition ) ) {
							$_conditions[] = '( '.implode( ') AND (', $_condition ).' )';
						}
					}
					if( !empty( $_conditions ) ) {
						$conditions[] = '( ( '.implode( ') OR (', $_conditions ).' ) )';
					}
				}
			}

            // Date de demande
            if( !empty( $dtdemrsa ) && $dtdemrsa != 0 ) {
                $dtdemrsa_from = Set::extract( $criteres, 'Filtre.dtdemrsa_from' );
                $dtdemrsa_to = Set::extract( $criteres, 'Filtre.dtdemrsa_to' );
                // FIXME: vérifier le bon formatage des dates
                $dtdemrsa_from = $dtdemrsa_from['year'].'-'.$dtdemrsa_from['month'].'-'.$dtdemrsa_from['day'];
                $dtdemrsa_to = $dtdemrsa_to['year'].'-'.$dtdemrsa_to['month'].'-'.$dtdemrsa_to['day'];

                $conditions[] = 'dossiers_rsa.dtdemrsa BETWEEN \''.$dtdemrsa_from.'\' AND \''.$dtdemrsa_to.'\'';
            }

            // Statut impression
            if( !empty( $date_impression ) && in_array( $date_impression, array( 'I', 'N' ) ) ) {
                if( $date_impression == 'I' ) {
                    $conditions[] = 'orientsstructs.date_impression IS NOT NULL';
                }
                else {
                    $conditions[] = 'orientsstructs.date_impression IS NULL';
                }
            }

            // Date d'impression
            if( !empty( $date_print ) && $date_print != 0 ) {
                $date_impression_from = Set::extract( $criteres, 'Filtre.date_impression_from' );
                $date_impression_to = Set::extract( $criteres, 'Filtre.date_impression_to' );
                // FIXME: vérifier le bon formatage des dates
                $date_impression_from = $date_impression_from['year'].'-'.$date_impression_from['month'].'-'.$date_impression_from['day'];
                $date_impression_to = $date_impression_to['year'].'-'.$date_impression_to['month'].'-'.$date_impression_to['day'];

                $conditions[] = 'orientsstructs.date_impression BETWEEN \''.$date_impression_from.'\' AND \''.$date_impression_to.'\'';
            }

//             INNER JOIN situationsdossiersrsa ON ( situationsdossiersrsa.dossier_rsa_id = dossiers_rsa.id )
//             LEFT OUTER JOIN suivisinstruction ON ( suivisinstruction.dossier_rsa_id = dossiers_rsa.id )
            $this->Dossier =& ClassRegistry::init( 'Dossier' );

            $sql = 'SELECT orientsstructs.id
                    FROM personnes
                        INNER JOIN prestations ON ( prestations.personne_id = personnes.id AND prestations.natprest = \'RSA\' AND ( prestations.rolepers = \'DEM\' OR prestations.rolepers = \'CJT\' ) )
                        INNER JOIN calculsdroitsrsa ON ( calculsdroitsrsa.personne_id = personnes.id )
                        '.( ( $statutOrientation == 'Non orienté' ) ? 'INNER JOIN  dsps ON ( dsps.personne_id = personnes.id )' : '' ).'
                        INNER JOIN foyers ON ( personnes.foyer_id = foyers.id )
                        INNER JOIN dossiers_rsa ON ( foyers.dossier_rsa_id = dossiers_rsa.id )
                        INNER JOIN adresses_foyers ON ( adresses_foyers.foyer_id = foyers.id AND adresses_foyers.rgadr = \'01\' AND adresses_foyers.id IN '.ClassRegistry::init( 'Adressefoyer' )->sqlFoyerActuelUnique().' )
                        INNER JOIN adresses as Adresse ON ( adresses_foyers.adresse_id = Adresse.id)
                        INNER JOIN orientsstructs ON ( orientsstructs.personne_id = personnes.id )
                        INNER JOIN detailsdroitsrsa ON ( detailsdroitsrsa.dossier_rsa_id = dossiers_rsa.id )
                        INNER JOIN situationsdossiersrsa ON ( situationsdossiersrsa.dossier_rsa_id = dossiers_rsa.id )
                    WHERE '.implode( ' AND ', $conditions ).'
                    LIMIT '.$limit;

            $cohorte = $this->Dossier->query( $sql );
//             $this->Dossier =& ClassRegistry::init( 'Dossier' );
//             $sql = 'SELECT DISTINCT personnes.id
//                     FROM personnes
//                         INNER JOIN prestations ON ( prestations.personne_id = personnes.id AND prestations.natprest = \'RSA\' AND ( prestations.rolepers = \'DEM\' OR prestations.rolepers = \'CJT\' ) )
//                         INNER JOIN calculsdroitsrsa ON ( calculsdroitsrsa.personne_id = personnes.id )
//                         '.( ( $statutOrientation == 'Non orienté' ) ? 'INNER JOIN  dsps ON ( dsps.personne_id = personnes.id )' : '' ).'
//                         INNER JOIN foyers ON ( personnes.foyer_id = foyers.id )
//                         INNER JOIN dossiers_rsa ON ( foyers.dossier_rsa_id = dossiers_rsa.id )
//                         INNER JOIN adresses_foyers ON ( adresses_foyers.foyer_id = foyers.id AND adresses_foyers.rgadr = \'01\' )
//                         INNER JOIN adresses as Adresse ON ( adresses_foyers.adresse_id = Adresse.id)
//                         INNER JOIN orientsstructs ON ( orientsstructs.personne_id = personnes.id )
//                         INNER JOIN detailsdroitsrsa ON ( detailsdroitsrsa.dossier_rsa_id = dossiers_rsa.id )
//                         INNER JOIN situationsdossiersrsa ON ( situationsdossiersrsa.dossier_rsa_id = dossiers_rsa.id AND ( situationsdossiersrsa.etatdosrsa IN ( \''.implode( '\', \'', $Situationdossierrsa->etatOuvert() ).'\' ) ) )
//                     WHERE '.implode( ' AND ', $conditions ).'
//                     LIMIT '.$limit;
//
//             $cohorte = $this->Dossier->query( $sql );

            return Set::extract( $cohorte, '{n}.0.id' );
        }*/

		/**
		*
		*/

        function search2( $statutOrientation, $mesCodesInsee, $filtre_zone_geo, $criteres, $lockedSubquery = null, $limit = PHP_INT_MAX ) {
            /// Requête
            $Situationdossierrsa =& ClassRegistry::init( 'Situationdossierrsa' );

            /// Conditions de base
            $conditions = array(
                'Orientstruct.statut_orient = \''.Sanitize::clean( $statutOrientation ).'\''
            );

			/// Sous_condition sur les jetons / les dossiers lockés ?
			if( !empty( $lockedSubquery ) ) {
				$dbo = $this->getDataSource( $this->useDbConfig );
				$conditions[] = $dbo->expression( "{$dbo->startQuote}Dossier{$dbo->endQuote}.{$dbo->startQuote}id{$dbo->endQuote} NOT IN ( {$lockedSubquery} )" );
			}

			if( $statutOrientation == 'Orienté' ) {
				// INFO: nouvelle manière de générer les PDFs
				$conditions[] = 'Orientstruct.id IN ( SELECT pdfs.fk_value FROM pdfs WHERE modele = \'Orientstruct\' )';
			}
			else {
				// INFO: ne faire apparaître les personnes à orienter que si la personne est soumise à droits et devoirs
                $conditions[] = 'Calculdroitrsa.toppersdrodevorsa = \'1\'';
				// INFO: ne faire apparaître les personnes à orienter que si le dossier se trouve dans un état ouvert
                $conditions[] = '( Situationdossierrsa.etatdosrsa IN ( \''.implode( '\', \'', $Situationdossierrsa->etatOuvert() ).'\' ) )';
			}

            /// Filtre zone géographique
            if( $filtre_zone_geo ) {
                $mesCodesInsee = ( !empty( $mesCodesInsee ) ? $mesCodesInsee : '0' );
                $conditions[] = 'Adresse.numcomptt IN ( \''.implode( '\', \'', $mesCodesInsee ).'\' )';
            }

            /// Dossiers lockés
//             if( !empty( $lockedDossiers ) ) {
//                 $conditions[] = 'Dossier.id NOT IN ( '.implode( ', ', $lockedDossiers ).' )';
//             }

            /// Critères
            $oridemrsa = Set::extract( $criteres, 'Filtre.oridemrsa' );
            $locaadr = Set::extract( $criteres, 'Filtre.locaadr' );
            $numcomptt = Set::extract( $criteres, 'Filtre.numcomptt' );
            $codepos = Set::extract( $criteres, 'Filtre.codepos' );
            $dtdemrsa = Set::extract( $criteres, 'Filtre.dtdemrsa' );
            $date_impression = Set::extract( $criteres, 'Filtre.date_impression' );
            $date_print = Set::extract( $criteres, 'Filtre.date_print' );
            $modeles = Set::extract( $criteres, 'Filtre.typeorient' );
            //-------------------------------------------------------
            $cantons = Set::extract( $criteres, 'Filtre.cantons' );


            /// FIXME: dans le modèle
            $typeorient = Set::classicExtract( $criteres, 'Filtre.typeorient' );
            if( !empty( $typeorient ) ) {
                if( Configure::read( 'with_parentid' ) ) {
                    $conditions[] = 'Orientstruct.typeorient_id IN ( SELECT typesorients.id FROM typesorients WHERE typesorients.parentid = \''.Sanitize::clean( $typeorient ).'\' )';
                }
                else {
                    $conditions[] = 'Orientstruct.typeorient_id = \''.Sanitize::clean( $typeorient ).'\'';
                }
            }
            /*if( !empty( $modeles ) ) {
                $conditions[] = 'orientsstructs.typeorient_id = \''.Sanitize::clean( $modeles ).'\'';
            }*/
            //-------------------------------------------------------

            // Origine de la demande
            if( !empty( $oridemrsa ) ) {
                $conditions[] = 'Detaildroitrsa.oridemrsa IN ( \''.implode( '\', \'', $oridemrsa ).'\' )';
            }

            // Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
            $filtersPersonne = array();
            foreach( array( 'nom', 'prenom', 'nomnai' ) as $criterePersonne ) {
                if( isset( $criteres['Filtre'][$criterePersonne] ) && !empty( $criteres['Filtre'][$criterePersonne] ) ) {
                    $conditions[] = 'Personne.'.$criterePersonne.' ILIKE \''.$this->wildcard( replace_accents( $criteres['Filtre'][$criterePersonne] ) ).'\'';
                }
            }

            // Localité adresse
            if( !empty( $locaadr ) ) {
                $conditions[] = 'Adresse.locaadr ILIKE \'%'.Sanitize::clean( $locaadr ).'%\'';
            }
            // Commune au sens INSEE
            if( !empty( $numcomptt ) ) {
                $conditions[] = 'Adresse.numcomptt = \''.Sanitize::clean( $numcomptt ).'\'';
            }
            // Code postal adresse
            if( !empty( $codepos ) ) {
                $conditions[] = 'Adresse.codepos = \''.Sanitize::clean( $codepos ).'\'';
            }

            /// Critères sur l'adresse - canton
			if( Configure::read( 'CG.cantons' ) ) {
				if( isset( $criteres['Canton']['canton'] ) && !empty( $criteres['Canton']['canton'] ) ) {
					$this->Canton =& ClassRegistry::init( 'Canton' );
					$tmpConditions = $this->Canton->queryConditions( $criteres['Canton']['canton'] );
					$_conditions = array();
					foreach( $tmpConditions['or'] as $tmpCondition ) {
						$_condition = array();
						foreach( $tmpCondition as $field => $value ) {
							if( valid_int( $value ) ) {
								$_condition[] = "$field = '".str_replace( "'", "\\'", $value )."'";
							}
							else {
								$_condition[] = "$field '".str_replace( "'", "\\'", $value )."'";
							}
						}
						if( !empty( $_condition ) ) {
							$_conditions[] = '( '.implode( ') AND (', $_condition ).' )';
						}
					}
					if( !empty( $_conditions ) ) {
						$conditions[] = '( ( '.implode( ') OR (', $_conditions ).' ) )';
					}
				}
			}

            // Date de demande
            if( !empty( $dtdemrsa ) && $dtdemrsa != 0 ) {
                $dtdemrsa_from = Set::extract( $criteres, 'Filtre.dtdemrsa_from' );
                $dtdemrsa_to = Set::extract( $criteres, 'Filtre.dtdemrsa_to' );
                // FIXME: vérifier le bon formatage des dates
                $dtdemrsa_from = $dtdemrsa_from['year'].'-'.$dtdemrsa_from['month'].'-'.$dtdemrsa_from['day'];
                $dtdemrsa_to = $dtdemrsa_to['year'].'-'.$dtdemrsa_to['month'].'-'.$dtdemrsa_to['day'];

                $conditions[] = 'Dossier.dtdemrsa BETWEEN \''.$dtdemrsa_from.'\' AND \''.$dtdemrsa_to.'\'';
            }

            // Statut impression
            if( !empty( $date_impression ) && in_array( $date_impression, array( 'I', 'N' ) ) ) {
                if( $date_impression == 'I' ) {
                    $conditions[] = 'Orientstruct.date_impression IS NOT NULL';
                }
                else {
                    $conditions[] = 'Orientstruct.date_impression IS NULL';
                }
            }

            // Date d'impression
            if( !empty( $date_print ) && $date_print != 0 ) {
                $date_impression_from = Set::extract( $criteres, 'Filtre.date_impression_from' );
                $date_impression_to = Set::extract( $criteres, 'Filtre.date_impression_to' );
                // FIXME: vérifier le bon formatage des dates
                $date_impression_from = $date_impression_from['year'].'-'.$date_impression_from['month'].'-'.$date_impression_from['day'];
                $date_impression_to = $date_impression_to['year'].'-'.$date_impression_to['month'].'-'.$date_impression_to['day'];

                $conditions[] = 'Orientstruct.date_impression BETWEEN \''.$date_impression_from.'\' AND \''.$date_impression_to.'\'';
            }

			// FIXME: prefixes pour les jointures
			return array(
				'fields' => array(
					'Adresse.canton',
					'Adresse.codepos',
					'Adresse.locaadr',
					'Adresse.numcomptt',
					'Dossier.dtdemrsa',
					'Dossier.matricule',
					'Dossier.numdemrsa',
					/// FIXME: dépendant du SGBD
					'( CASE WHEN dtdemrsa >= \'2009-06-01 00:00:00\' THEN \'Nouvelle demande\' ELSE \'Diminution des ressources\' END ) AS "Dossier__statut"',
					'Foyer.dossier_rsa_id',
					'Orientstruct.id',
					'Orientstruct.propo_algo',
					'Orientstruct.structurereferente_id',
					'Orientstruct.date_propo',
					'Orientstruct.statut_orient',
					'Orientstruct.typeorient_id',
					'Personne.dtnai',
					'Personne.id',
					'Personne.nom',
					'Personne.nir',
					'Personne.prenom',
					'Situationdossierrsa.dtclorsa',
					'Situationdossierrsa.moticlorsa',
					'Dsp.id',
					'Typeorient.lib_type_orient',
					/// FIXME: dépendant du SGBD
					'Preorientation.lib_type_orient',
					'Structurereferente.lib_struc',
					'Contratinsertion.dd_ci',
					'Suiviinstruction.numagrins',
					'Suiviinstruction.numcomins',
					'Suiviinstruction.numdepins',
					'Suiviinstruction.typeserins'
				),
				'joins' => array(
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.id = Orientstruct.personne_id' )
					),
					array(
						'table'      => 'foyers',
						'alias'      => 'Foyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Foyer.id = Personne.foyer_id' )
					),
					array(
						'table'      => 'adresses_foyers',
						'alias'      => 'Adressefoyer',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Foyer.id = Adressefoyer.foyer_id',
							// Dernière adresse
							'Adressefoyer.id IN (
								SELECT adresses_foyers.id
									FROM adresses_foyers
									WHERE
										adresses_foyers.foyer_id = Personne.foyer_id
										AND adresses_foyers.rgadr = \'01\'
									ORDER BY adresses_foyers.dtemm DESC
									LIMIT 1
							)'
						)
					),
					array(
						'table'      => 'adresses',
						'alias'      => 'Adresse',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
					),
					array(
						'table'      => 'dossiers_rsa',
						'alias'      => 'Dossier',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Dossier.id = Foyer.dossier_rsa_id'
						)
					),
					array(
						'table'      => 'situationsdossiersrsa',
						'alias'      => 'Situationdossierrsa',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Dossier.id = Situationdossierrsa.dossier_rsa_id' )
					),
					array(
						'table'      => 'typesorients',
						'alias'      => 'Typeorient',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Typeorient.id = Orientstruct.typeorient_id' )
					),
					array(
						'table'      => 'typesorients',
						'alias'      => 'Preorientation',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Preorientation.id = Orientstruct.propo_algo' )
					),
					array(
						'table'      => 'structuresreferentes',
						'alias'      => 'Structurereferente',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Structurereferente.id = Orientstruct.structurereferente_id' )
					),
					/// Une seule
					array(
						'table'      => 'dsps',
						'alias'      => 'Dsp',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.id = Dsp.personne_id' )
					),
					array(
						'table'      => 'calculsdroitsrsa',
						'alias'      => 'Calculdroitrsa',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.id = Calculdroitrsa.personne_id' )
					),
					array( // FIXME: seulement visualisation
						'table'      => 'contratsinsertion',
						'alias'      => 'Contratinsertion',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id = Contratinsertion.personne_id',
							// Dernier Contratinsertion
							'Contratinsertion.id IN (
								SELECT contratsinsertion.id
									FROM contratsinsertion
									WHERE
										contratsinsertion.personne_id = Personne.id
									ORDER BY contratsinsertion.dd_ci DESC
									LIMIT 1
							)'
						)
					),
					array(
						'table'      => 'detailsdroitsrsa',
						'alias'      => 'Detaildroitrsa',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Dossier.id = Detaildroitrsa.dossier_rsa_id' )
					),
					array(
						'table'      => 'suivisinstruction',
						'alias'      => 'Suiviinstruction',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Dossier.id = Suiviinstruction.dossier_rsa_id' )
					),
				),
				'recursive' => -1,
				'limit' => 10,
				'conditions' => $conditions
			);
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function structuresAutomatiques() {
            /*App::import( 'Model', 'Structurereferente' );
            $this->Structurereferente = new Structurereferente();*/
            $this->Structurereferente =& ClassRegistry::init( 'Structurereferente' );
            /*App::import( 'Model', 'Typeorient' );
            $this->Typeorient = new Typeorient();*/
            $this->Typeorient =& ClassRegistry::init( 'Typeorient' );

            // FIXME: valeurs magiques
            $typesPermis = $this->Typeorient->find(
                'list',
                array(
                    'conditions' => array(
                        'Typeorient.lib_type_orient' => array( 'Emploi', 'Socioprofessionnelle' )
					),
					'recursive' => -1
                )
            );
            $typesPermis = array_keys( $typesPermis );

            $this->Structurereferente->unbindModelAll();
            $this->Structurereferente->bindModel( array( 'hasAndBelongsToMany' => array( 'Zonegeographique' ) ) );
            $this->Structurereferente->Zonegeographique->unbindModelAll();
            $structures = $this->Structurereferente->find(
                'all',
                array(
                    'conditions' => array(
                        'Structurereferente.typeorient_id' => $typesPermis
                    ),
                    'recursive' => 2
                )
            );

            $return = array();
            foreach( $structures as $structure ) {
                if( !empty( $structure['Zonegeographique'] ) ) {
                    foreach( $structure['Zonegeographique'] as $zonegeographique ) {
                        $return[$structure['Structurereferente']['typeorient_id']][$zonegeographique['codeinsee']] = $structure['Structurereferente']['typeorient_id'].'_'.$structure['Structurereferente']['id'];
                    }
                }
            }

            return $return;
        }
    }
?>
