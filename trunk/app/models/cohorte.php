<?php
    App::import( 'Sanitize' );
//     App::import( 'Dossier' );

    // ************************************************************************

    class Cohorte extends AppModel
    {
        var $name = 'Cohorte';
        var $useTable = false;

        /**
        *
        * INFO: Préprofessionnelle => Socioprofessionnelle --> mettre un type dans la table ?
        *
        */

        function preOrientation( $element ) {
            $propo_algo = null;

            if( isset( $element['Dspp'] ) ) {
                $accoemploiCodes = Set::extract( 'Dspp.Accoemploi.{n}.code', $element );

                // Socioprofessionnelle, Social
                // 1°) Passé professionnel ? -> Emploi
                //     1901 : Vous avez toujours travaillé
                //     1902 : Vous travaillez par intermittence
                if( !empty( $element['Dspp']['hispro'] ) && ( $element['Dspp']['hispro'] == '1901' || $element['Dspp']['hispro'] == '1902' ) ) {
                    $propo_algo = 'Emploi'; // Emploi (Pôle emploi)
                }
                // 2°) Etes-vous accompagné dans votre recherche d'emploi ?
                //     1802 : Pôle Emploi
                else if( empty( $propo_algo ) && !empty( $accoemploiCodes ) && in_array( '1802', $accoemploiCodes ) ) {
                    $propo_algo = 'Emploi';
                }
                // 3°) Êtes-vous sans activité depuis moins de 24 mois ?
                //     Date éventuelle de cessation d’activité ?
                else if( empty( $propo_algo ) ) {
                    $dfderact = null;
                    if( !empty( $element['Dspp']['dfderact'] ) ) {
                        list( $year, $month, $day ) = explode( '-', $element['Dspp']['dfderact'] );
                        $dfderact = mktime( 0, 0, 0, $month, $day, $year );
                    }
                    if( !empty( $dfderact ) && ( $dfderact > strtotime( '-24 months' ) ) ) {
                        $propo_algo = 'Emploi';
                    }
                }

                if( empty( $propo_algo ) && isset( $element['Foyer']['Dspf'] ) && !empty( $element['Foyer']['Dspf'] ) ) {
                    $dspf = Classregistry::init( 'Dossier' )->Foyer->Dspf->find(
                        'first',
                        array(
                            'conditions' => array( 'Dspf.id' => $element['Foyer']['Dspf']['id'] )
                        )
                    );
                    if( !empty( $dspf ) ) {
                        // FIXME: grosse requête pour pas grand-chose
                        if( $element['Foyer']['Dspf']['accosocfam'] == 'O' ) {
                            $propo_algo = 'Social'; // SSD (Service Social Départemental)
                        }
                        else {
                            $propo_algo = 'Socioprofessionnelle'; // PDV (Projet De Ville)
                        }
                    }
                }
            }

            if( empty( $propo_algo ) ) {
				$propo_algo = null;
            }


            return $propo_algo;
        }

		/**
		*
		*/

        function search( $statutOrientation, $mesCodesInsee, $filtre_zone_geo, $criteres, $lockedDossiers, $limit = PHP_INT_MAX ) {
            /// Conditions de base
            $conditions = array(
                'prestations.toppersdrodevorsa = true',
                'orientsstructs.statut_orient = \''.Sanitize::clean( $statutOrientation ).'\''
            );

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

            // Origine de la demande
            if( !empty( $oridemrsa ) ) {
                $conditions[] = 'detailsdroitsrsa.oridemrsa IN ( \''.implode( '\', \'', $oridemrsa ).'\' )';
            }

            // Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
            $filtersPersonne = array();
            foreach( array( 'nom', 'prenom', 'nomnai' ) as $criterePersonne ) {
                if( isset( $criteres['Filtre'][$criterePersonne] ) && !empty( $criteres['Filtre'][$criterePersonne] ) ) {
                    $conditions[] = 'personnes.'.$criterePersonne.' ILIKE \'%'.replace_accents( $criteres['Filtre'][$criterePersonne] ).'%\'';
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
                // FIXME: vérifier le bon formattage des dates
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

            /// Requête
            $Situationdossierrsa =& ClassRegistry::init( 'Situationdossierrsa' );

            /*INNER JOIN situationsdossiersrsa ON ( situationsdossiersrsa.dossier_rsa_id = dossiers_rsa.id )*/
            /*LEFT OUTER JOIN suivisinstruction ON ( suivisinstruction.dossier_rsa_id = dossiers_rsa.id )*/
            $this->Dossier =& ClassRegistry::init( 'Dossier' );
            $sql = 'SELECT DISTINCT personnes.id
                    FROM personnes
                        INNER JOIN prestations ON ( prestations.personne_id = personnes.id AND prestations.natprest = \'RSA\' AND ( prestations.rolepers = \'DEM\' OR prestations.rolepers = \'CJT\' ) )
                        '.( ( $statutOrientation == 'Non orienté' ) ? 'INNER JOIN  dspps ON ( dspps.personne_id = personnes.id )' : '' ).'
                        INNER JOIN foyers ON ( personnes.foyer_id = foyers.id )
                        INNER JOIN dossiers_rsa ON ( foyers.dossier_rsa_id = dossiers_rsa.id )
                        INNER JOIN adresses_foyers ON ( adresses_foyers.foyer_id = foyers.id AND adresses_foyers.rgadr = \'01\' )
                        INNER JOIN adresses as Adresse ON ( adresses_foyers.adresse_id = Adresse.id)
                        INNER JOIN orientsstructs ON ( orientsstructs.personne_id = personnes.id )
                        INNER JOIN detailsdroitsrsa ON ( detailsdroitsrsa.dossier_rsa_id = dossiers_rsa.id )
                        INNER JOIN situationsdossiersrsa ON ( situationsdossiersrsa.dossier_rsa_id = dossiers_rsa.id AND ( situationsdossiersrsa.etatdosrsa IN ( \''.implode( '\', \'', $Situationdossierrsa->etatOuvert() ).'\' ) ) )
                    WHERE '.implode( ' AND ', $conditions ).'
                    LIMIT '.$limit;

            $cohorte = $this->Dossier->query( $sql );

            return Set::extract( $cohorte, '{n}.0.id' );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function structuresAutomatiques() {
            App::import( 'Model','Structurereferente' );
            $this->Structurereferente = new Structurereferente();
            App::import( 'Model','Typeorient' );
            $this->Typeorient = new Typeorient();

            // FIXME: valeurs magiques
            $typesPermis = $this->Typeorient->find(
                'list',
                array(
                    'conditions' => array(
                        'Typeorient.lib_type_orient' => array( 'Emploi', 'Socioprofessionnelle' ) )
                )
            );
            $typesPermis = array_keys( $typesPermis );

            $this->Structurereferente->unbindModelAll();
            $this->Structurereferente->bindModel( array( 'hasAndBelongsToMany' => array( 'Zonegeographique' ) ) );
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