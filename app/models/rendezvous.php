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

        function search( $criteres ) {
            /// Conditions de base
            $conditions = array();

            /// Critères
            $statutrdv = Set::extract( $criteres, 'Filtre.statutrdv' );
            $daterdv = Set::extract( $criteres, 'Filtre.daterdv' );

            /// Date du flux RDV
            if( !empty( $daterdv ) && dateComplete( $criterespdo, 'Filtre.daterdv' ) ) {
                $daterdv = $daterdv['year'].'-'.$daterdv['month'].'-'.$daterdv['day'];
                $conditions[] = 'Rendezvous.daterdv = \''.$daterdv.'\'';
            }

            /// Statut RDV
            if( !empty( $statut ) ) {
                $conditions[] = 'Rendezvous.statutrdv ILIKE \'%'.Sanitize::clean( $statutrdv ).'%\'';
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
                    '"Personne"."nom"',
                    '"Personne"."prenom"',
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
                        'table'      => 'orientsstructs',
                        'alias'      => 'Orientstruct',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Orientstruct.personne_id = Personne.id' ),
                    ),
                    array(
                        'table'      => 'structuresreferentes',
                        'alias'      => 'Structurereferente',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Structurereferente.id = Orientstruct.structurereferente_id' ),
                    )
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