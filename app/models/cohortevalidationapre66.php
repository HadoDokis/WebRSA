<?php
App::import( 'Sanitize' );
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

            /// Cohortevalidationapre66 zone géographique
            if( $filtre_zone_geo ) {
                $mesCodesInsee = ( !empty( $mesCodesInsee ) ? $mesCodesInsee : '0' );
                $conditions[] = 'Adresse.numcomptt IN ( \''.implode( '\', \'', $mesCodesInsee ).'\' )';
            }

            /// Dossiers lockés
            if( !empty( $lockedDossiers ) ) {
                $conditions[] = 'Dossier.id NOT IN ( '.implode( ', ', $lockedDossiers ).' )';
            }

            /// Critères
            $numeroapre = Set::extract( $criteresapres, 'Apre.numeroapre' );
            $referent = Set::extract( $criteresapres, 'Apre.referent_id' );
            $locaadr = Set::extract( $criteresapres, 'Adresse.locaadr' );
            $numcomptt = Set::extract( $criteresapres, 'Adresse.numcomptt' );
            $numdemrsa = Set::extract( $criteresapres, 'Dossier.numdemrsa' );
            $matricule = Set::extract( $criteresapres, 'Dossier.matricule' );


            // Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
            foreach( array( 'nom', 'prenom', 'nomnai', 'nir' ) as $criterePersonne ) {
                if( isset( $criteresapres['Personne'][$criterePersonne] ) && !empty( $criteresapres['Personne'][$criterePersonne] ) ) {
                    $conditions[] = 'Personne.'.$criterePersonne.' ILIKE \''.$this->wildcard( $criteresapres['Personne'][$criterePersonne] ).'\'';
                }
            }

            // Localité adresse
            if( !empty( $locaadr ) ) {
                $conditions[] = 'Adresse.locaadr ILIKE \'%'.Sanitize::clean( $locaadr ).'%\'';
            }


            // Commune au sens INSEE
            if( !empty( $numcomptt ) ) {
                $conditions[] = 'Adresse.numcomptt ILIKE \'%'.Sanitize::clean( $numcomptt ).'%\'';
            }

            // Référent lié à l'APRE
            if( !empty( $referent ) ) {
                $conditions[] = 'Apre.referent_id = \''.Sanitize::clean( $referent ).'\'';
            }

            //Critères sur le dossier de l'allocataire - numdemrsa + matricule
            foreach( array( 'numdemrsa', 'matricule' ) as $critereDossier ) {
                if( isset( $criteresapres['Dossier'][$critereDossier] ) && !empty( $criteresapres['Dossier'][$critereDossier] ) ) {
                    $conditions[] = 'Dossier.'.$critereDossier.' ILIKE \''.$this->wildcard( $criteresapres['Dossier'][$critereDossier] ).'\'';
                }
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