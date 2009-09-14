<?php 
    class Rendezvous extends AppModel
    {
        var $name = 'Rendezvous';
        var $useTable = 'rendezvous';


        var $belongsTo = array(
            'Personne' => array(
                'classname' => 'Personne',
                'foreignKey' => 'personne_id'
            ),
            'Structurereferente' => array(
                'classname' => 'Structurereferente',
                'foreignKey' => 'structurereferente_id'
            )
        );

        var $validate = array(
            'statutrdv' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            ),
            'objetrdv' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            )/*,
            'daterdv' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
            )*/
        );

        function dossierId( $rdv_id ){
            $this->unbindModelAll();
             $this->bindModel(
                array(
                    'hasOne' => array(
                        'Personne' => array(
                            'foreignKey' => false,
                            'conditions' => array( 'Personne.id = Rendezvous.personne_id' )
                        ),
                        'Foyer' => array(
                            'foreignKey' => false,
                            'conditions' => array( 'Foyer.id = Personne.foyer_id' )
                        )
                    )
                )
            );
            $rdv = $this->findById( $rdv_id, null, null, 0 );
// debug( $rdv );
            if( !empty( $rdv ) ) {
                return $rdv['Foyer']['dossier_rsa_id'];
            }
            else {
                return null;
            }
        }

        function search( $mesCodesInsee, $filtre_zone_geo, $criteresrdv ) {
            /// Conditions de base
            $conditions = array();

            /// Critères
            $statutrdv = Set::extract( $criteresrdv, 'Critererdv.statutrdv' );
            $structurereferente_id = Set::extract( $criteresrdv, 'Critererdv.structurereferente_id' );
            $locaadr = Set::extract( $criteresrdv, 'Critererdv.locaadr' );
            $numcomptt = Set::extract( $criteresrdv, 'Critererdv.numcomptt' );
            $nom = Set::extract( $criteresrdv, 'Critererdv.nom' );

            /// Filtre zone géographique
            if( $filtre_zone_geo ) {
                $mesCodesInsee = ( !empty( $mesCodesInsee ) ? $mesCodesInsee : '0' );
                $conditions[] = 'Adresse.numcomptt IN ( \''.implode( '\', \'', $mesCodesInsee ).'\' )';
            }

            /// Critères sur le RDV - date de demande
            if( isset( $criteresrdv['Critererdv']['daterdv'] ) && !empty( $criteresrdv['Critererdv']['daterdv'] ) ) {
                $valid_from = ( valid_int( $criteresrdv['Critererdv']['daterdv_from']['year'] ) && valid_int( $criteresrdv['Critererdv']['daterdv_from']['month'] ) && valid_int( $criteresrdv['Critererdv']['daterdv_from']['day'] ) );
                $valid_to = ( valid_int( $criteresrdv['Critererdv']['daterdv_to']['year'] ) && valid_int( $criteresrdv['Critererdv']['daterdv_to']['month'] ) && valid_int( $criteresrdv['Critererdv']['daterdv_to']['day'] ) );
                if( $valid_from && $valid_to ) {
                    $conditions[] = 'Rendezvous.daterdv BETWEEN \''.implode( '-', array( $criteresrdv['Critererdv']['daterdv_from']['year'], $criteresrdv['Critererdv']['daterdv_from']['month'], $criteresrdv['Critererdv']['daterdv_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteresrdv['Critererdv']['daterdv_to']['year'], $criteresrdv['Critererdv']['daterdv_to']['month'], $criteresrdv['Critererdv']['daterdv_to']['day'] ) ).'\'';
                }
            }
            /// Statut RDV
            if( !empty( $statutrdv ) ) {
                $conditions[] = 'Rendezvous.statutrdv ILIKE \'%'.Sanitize::clean( $statutrdv ).'%\'';
            }

            /// Nom allocataire
            if( !empty( $nom ) ) {
                $conditions[] = 'Personne.nom ILIKE \'%'.Sanitize::clean( $nom ).'%\'';
            }

            /// Adresse personne
            if( !empty( $locaadr ) ) {
                $conditions[] = 'Adresse.locaadr ILIKE \'%'.Sanitize::clean( $locaadr ).'%\'';
            }

            /// Code INSSE
            if( !empty( $numcomptt ) ) {
                $conditions[] = 'Adresse.numcomptt ILIKE \'%'.Sanitize::clean( $numcomptt ).'%\'';
            }

            /// Structure référente
            if( !empty( $structurereferente_id ) ) {
                $conditions[] = 'Rendezvous.structurereferente_id = \''.Sanitize::clean( $structurereferente_id ).'\'';
            }
            /// Requête
            $this->Dossier =& ClassRegistry::init( 'Dossier' );

            $query = array(
                'fields' => array(
                    '"Rendezvous"."id"',
                    '"Rendezvous"."personne_id"',
                    '"Rendezvous"."structurereferente_id"',
                    '"Rendezvous"."statutrdv"',
                    '"Rendezvous"."daterdv"',
                    '"Rendezvous"."objetrdv"',
                    '"Rendezvous"."commentairerdv"',
                    '"Dossier"."numdemrsa"',
                    '"Adresse"."locaadr"',
                    '"Adresse"."numcomptt"',
                    '"Personne"."nom"',
                    '"Personne"."prenom"',
                    '"Personne"."nomcomnai"',
                    '"Personne"."dtnai"',
                    '"Structurereferente"."lib_struc"'
                ),
                'recursive' => -1,
                'joins' => array(
                    array(
                        'table'      => 'personnes',
                        'alias'      => 'Personne',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Rendezvous.personne_id = Personne.id' ),
                    ),
                    array(
                        'table'      => 'structuresreferentes',
                        'alias'      => 'Structurereferente',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Structurereferente.id = Rendezvous.structurereferente_id' ),
                    ),
                    array(
                        'table'      => 'prestations',
                        'alias'      => 'Prestation',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array(
                            'Personne.id = Prestation.personne_id',
                            'Prestation.natprest = \'RSA\'',
//                             '( Prestation.natprest = \'RSA\' OR Prestation.natprest = \'PFA\' )',
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
                        'table'      => 'adresses_foyers',
                        'alias'      => 'Adressefoyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Foyer.id = Adressefoyer.foyer_id', 'Adressefoyer.rgadr = \'01\'' )
                    ),
                    array(
                        'table'      => 'adresses',
                        'alias'      => 'Adresse',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
                    ),
                    array(
                        'table'      => 'dossiers_rsa',
                        'alias'      => 'Dossier',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Foyer.dossier_rsa_id = Dossier.id' )
                    ),
                ),
//                 'group' => array(
//                     'Totalisationacompte.type_totalisation',
//                     'Totalisationacompte.id'
//                 ),
                'order' => array( '"Rendezvous"."daterdv" ASC' ),
                'conditions' => $conditions
            );

            return $query;

        }
    }

?>