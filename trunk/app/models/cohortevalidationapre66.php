<?php
    class Cohortevalidationapre66 extends AppModel
    {
        public $name = 'Cohortevalidationapre66';

        public $useTable = false;

        /**
        *
        */

        public function search( $statutValidation, $mesCodesInsee, $filtre_zone_geo, $criteresapres, $lockedDossiers ) {
            /// Conditions de base
            $conditions = array(
            );

            if( !empty( $statutValidation ) ) {
                if( $statutValidation == 'Validationapre::apresavalider' ) {
                    $conditions[] = '( ( Apre.etatdossierapre = \'COM\' ) AND ( Apre.isdecision = \'N\' ) )';
                }
//                 else if( $statutValidation == 'Validationapre::enattente' ) {
//                     $conditions[] = 'Apre.etatdossierapre = \'E\'';
//                 }
                else if( $statutValidation == 'Validationapre::validees' ) {
                    $conditions[] = 'Apre.etatdossierapre = \'VAL\' ';
                }
            }

            /// Filtre zone géographique
            if( $filtre_zone_geo ) {
                $mesCodesInsee = ( !empty( $mesCodesInsee ) ? $mesCodesInsee : '0' );
                $conditions[] = 'Adresse.numcomptt IN ( \''.implode( '\', \'', $mesCodesInsee ).'\' )';
            }

            /// Dossiers lockés
            if( !empty( $lockedDossiers ) ) {
                $conditions[] = 'Dossier.id NOT IN ( '.implode( ', ', $lockedDossiers ).' )';
            }

            /// Critères
            $datedemandeapre = Set::extract( $criteresapres, 'Filtre.datedemandeapre' );
            $daterelance = Set::extract( $criteresapres, 'Filtre.daterelance' );
            $locaadr = Set::extract( $criteresapres, 'Filtre.locaadr' );
            $numcomptt = Set::extract( $criteresapres, 'Filtre.numcomptt' );
            $numdemrsa = Set::extract( $criteresapres, 'Filtre.numdemrsa' );
            $matricule = Set::extract( $criteresapres, 'Filtre.matricule' );
            $nir = Set::extract( $criteresapres, 'Filtre.nir' );
            $typedemandeapre = Set::extract( $criteresapres, 'Filtre.typedemandeapre' );
            $etatdossierapre = Set::extract( $criteresapres, 'Filtre.etatdossierapre' );


            /// Critères sur la demande APRE - date de demande
            if( isset( $criteresapres['Filtre']['datedemandeapre'] ) && !empty( $criteresapres['Filtre']['datedemandeapre'] ) ) {
                $valid_from = ( valid_int( $criteresapres['Filtre']['datedemandeapre_from']['year'] ) && valid_int( $criteresapres['Filtre']['datedemandeapre_from']['month'] ) && valid_int( $criteresapres['Filtre']['datedemandeapre_from']['day'] ) );
                $valid_to = ( valid_int( $criteresapres['Filtre']['datedemandeapre_to']['year'] ) && valid_int( $criteresapres['Filtre']['datedemandeapre_to']['month'] ) && valid_int( $criteresapres['Filtre']['datedemandeapre_to']['day'] ) );
                if( $valid_from && $valid_to ) {
                    $conditions[] = 'Apre.datedemandeapre BETWEEN \''.implode( '-', array( $criteresapres['Filtre']['datedemandeapre_from']['year'], $criteresapres['Filtre']['datedemandeapre_from']['month'], $criteresapres['Filtre']['datedemandeapre_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteresapres['Filtre']['datedemandeapre_to']['year'], $criteresapres['Filtre']['datedemandeapre_to']['month'], $criteresapres['Filtre']['datedemandeapre_to']['day'] ) ).'\'';
                }
            }



            // Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
            $filtersPersonne = array();
            foreach( array( 'nom', 'prenom', 'nomnai' ) as $criterePersonne ) {
                if( isset( $criteresapres['Filtre'][$criterePersonne] ) && !empty( $criteresapres['Filtre'][$criterePersonne] ) ) {
                    $conditions[] = 'Personne.'.$criterePersonne.' ILIKE \''.$this->wildcard( $criteresapres['Filtre'][$criterePersonne] ).'\'';
                }
            }

            // Localité adresse
            if( !empty( $locaadr ) ) {
                $conditions[] = 'Adresse.locaadr ILIKE \'%'.Sanitize::clean( $locaadr ).'%\'';
            }

            /// Critères sur l'adresse - canton
            if( Configure::read( 'CG.cantons' ) ) {
                if( isset( $criteresapres['Canton']['canton'] ) && !empty( $criteresapres['Canton']['canton'] ) ) {
                    $this->Canton = ClassRegistry::init( 'Canton' );
                    $conditions[] = $this->Canton->queryConditions( $criteresapres['Canton']['canton'] );
                }
            }

            // NIR
            if( !empty( $nir ) ) {
                $conditions[] = 'Personne.nir ILIKE \'%'.Sanitize::clean( $nir ).'%\'';
            }

            // Commune au sens INSEE
            if( !empty( $numcomptt ) ) {
                $conditions[] = 'Adresse.numcomptt ILIKE \'%'.Sanitize::clean( $numcomptt ).'%\'';
            }

            // N° Dossier RSA
            if( !empty( $numdemrsa ) ) {
                $conditions[] = 'Dossier.numdemrsa ILIKE \'%'.Sanitize::clean( $numdemrsa ).'%\'';
            }

            // N° CAF
            if( !empty( $matricule ) ) {
                $conditions[] = 'Dossier.matricule ILIKE \'%'.Sanitize::clean( $matricule ).'%\'';
            }


            //Etat du dossier apre
            if( !empty( $etatdossierapre ) ) {
                $conditions[] = 'Apre.etatdossierapre = \''.Sanitize::clean( $etatdossierapre ).'\'';
            }



            /// Requête
            $this->Dossier = ClassRegistry::init( 'Dossier' );

            $joins = array(
                array(
                    'table'      => 'personnes',
                    'alias'      => 'Personne',
                    'type'       => 'INNER',
                    'foreignKey' => false,
                    'conditions' => array( 'Personne.id = Apre.personne_id' ),
                ),
                array(
                    'table'      => 'aidesapres66',
                    'alias'      => 'Aideapre66',
                    'type'       => 'INNER',
                    'foreignKey' => false,
                    'conditions' => array( 'Aideapre66.apre_id = Apre.id' ),
                ),
                array(
                        'table'      => 'prestations',
                        'alias'      => 'Prestation',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array(
                            'Personne.id = Prestation.personne_id',
                            'Prestation.natprest = \'RSA\'',
                            '( Prestation.rolepers = \'DEM\' OR Prestation.rolepers = \'CJT\' )',
                        )
                    ),
                    array(
                        'table'      => 'foyers',
                        'alias'      => 'Foyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Personne.foyer_id = Foyer.id' )
                    ),
                    array(
                        'table'      => 'dossiers',
                        'alias'      => 'Dossier',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Foyer.dossier_id = Dossier.id' )
                    ),
                    array(
                        'table'      => 'adressesfoyers',
                        'alias'      => 'Adressefoyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array(
                            'Foyer.id = Adressefoyer.foyer_id',
                            'Adressefoyer.id IN (
                                '.ClassRegistry::init( 'Adressefoyer' )->sqDerniereRgadr01('Adressefoyer.foyer_id').'
                            )'
                        )
                    ),
                    array(
                        'table'      => 'adresses',
                        'alias'      => 'Adresse',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
                    )
            );


            $query = array(
                'fields' => array(
                    'Apre.id',
                    'Apre.personne_id',
                    'Apre.numeroapre',
                    'Apre.typedemandeapre',
                    'Apre.datedemandeapre',
                    'Apre.naturelogement',
                    'Apre.anciennetepoleemploi',
                    'Apre.activitebeneficiaire',
                    'Apre.etatdossierapre',
                    'Apre.dateentreeemploi',
                    'Apre.eligibiliteapre',
                    'Apre.typecontrat',
                    'Apre.statutapre',
                    'Apre.mtforfait',
                    'Apre.isdecision',
                    'Apre.nbenf12',
                    'Aideapre66.id',
                    'Aideapre66.apre_id',
                    'Aideapre66.decisionapre',
                    'Aideapre66.montantaccorde',
                    'Aideapre66.datedemande',
                    'Aideapre66.datemontantaccorde',
                    'Aideapre66.motifrejetequipe',
                    'Dossier.id',
                    'Dossier.numdemrsa',
                    'Dossier.dtdemrsa',
                    'Dossier.matricule',
                    'Personne.id',
                    'Personne.nom',
                    'Personne.prenom',
                    'Personne.dtnai',
                    'Personne.nir',
                    'Personne.qual',
                    'Personne.nomcomnai',
                    'Adresse.locaadr',
                    'Adresse.codepos',
                    'Adressefoyer.rgadr',
                    'Adresse.numcomptt'
                ),
                'joins' => $joins,
                'contain' => false,
                'conditions' => $conditions
            );

            return $query;


        }
    }
?>