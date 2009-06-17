<?php
    class Orientstruct extends AppModel
    {
        var $name = 'Orientstruct';
        var $useTable = 'orientsstructs';

        // ********************************************************************

        var $belongsTo = array(
            'Personne' => array(
                'classname'     => 'Personne',
                'foreignKey'    => 'personne_id'
            ),
            'Structurereferente' => array(
                'classname'     => 'Structurereferente',
                'foreignKey'    => 'structurereferente_id'
            )/*,///FIXMRE: test avant confirmation !!!!!!!
            'Serviceinstructeur' => array(
                'classname' => 'Serviceinstructeur',
                'foreignKey' => 'serviceinstructeur_id'
            )*/
        );

        // ********************************************************************

        var $validate = array(
            'structurereferente_id' => array(
                array(
                    'rule' => array( 'choixStructure', 'statut_orient' ),
                    'message' => 'Champ obligatoire'
                )
            )
        );

        // --------------------------------------------------------------------

        function choixStructure( $field = array(), $compare_field = null ) {
            foreach( $field as $key => $value ) {
                if( !empty( $this->data[$this->name][$compare_field] ) && ( $this->data[$this->name][$compare_field] != 'En attente' ) && empty( $value ) ) {
                    return false;
                }
            }
            return true;
        }

        // ********************************************************************

        function beforeSave( $options = array() ) {
            $return = parent::beforeSave( $options );
            $hasMany = ( array_depth( $this->data ) > 2 );

            if( !$hasMany ) { // INFO: 1 seul enregistrement
                if( array_key_exists( 'structurereferente_id', $this->data['Orientstruct'] ) ) {
                    $this->data['Orientstruct']['structurereferente_id'] = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $this->data['Orientstruct']['structurereferente_id'] );
                }
            }
            else { // INFO: plusieurs enregistrements
                foreach( $this->data['Orientstruct'] as $key => $value ) {
                    if( is_array( $value ) && array_key_exists( 'structurereferente_id', $value ) ) {
                        $this->data['Orientstruct'][$key]['structurereferente_id'] = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $value['structurereferente_id'] );
                    }
                }
            }

            return $return;
        }

        //*********************************************************************

        function dossierId( $ressource_id ) {
            $this->unbindModelAll();
            $this->bindModel(
                array(
                    'hasOne' => array(
                        'Personne' => array(
                            'foreignKey' => false,
                            'conditions' => array( 'Personne.id = Orientstruct.personne_id' )
                        ),
                        'Foyer' => array(
                            'foreignKey' => false,
                            'conditions' => array( 'Foyer.id = Personne.foyer_id' )
                        )
                    )
                )
            );
            $ressource = $this->findById( $ressource_id, null, null, 1 );

            if( !empty( $ressource ) ) {
                return $ressource['Foyer']['dossier_rsa_id'];
            }
            else {
                return null;
            }
        }
    }
?>