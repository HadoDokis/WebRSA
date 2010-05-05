<?php
    class ApreComiteapre extends AppModel
    {
        var $name = 'ApreComiteapre';

        var $actsAs = array(
            'Enumerable' => array(
                'fields' => array(
                    'decisioncomite' => array( 'type' => 'decisioncomite', 'domain' => 'apre' ),
                    'recoursapre' => array( 'type' => 'recoursapre', 'domain' => 'apre' ),
                )
            ),
            'Frenchfloat' => array( 'fields' => array( 'montantattribue' ) )

        );

        var $validate = array(
            'decisioncomite' => array(
                array(
                    'rule'      => array( 'inList', array( 'AJ', 'ACC', 'REF' ) ),
                    'message'   => 'Veuillez choisir une valeur.',
                    'allowEmpty' => false
                )
            ),
            'montantattribue' => array(
                array(
                    'rule' => 'numeric',
                    'message' => 'Veuillez entrer une valeur numérique.',
                    'allowEmpty' => true
                )
            ),
        );

        var $belongsTo = array(
            'Apre'
        );

        /**
        *   Before Save pour remettre à zéro les montants attribués par le comité si la décision est passée en Refus
        **/
        function beforeSave( $options = array() ) {
            $return = parent::beforeSave( $options );

            //FIXME: a mettre dans le beforeValidate
            if( isset( $this->data[$this->name]['decisioncomite'] ) ) {
                if( $this->data[$this->name]['decisioncomite'] != 'ACC' ) {
                    $this->data[$this->name]['montantattribue'] = null;
                }
                else {
                    //$apre = $this->Apre->findById( $this->data[$this->name]['apre_id'], null, null, -1 );
                    $apre = $this->Apre->read( array( 'id', $this->Apre->sousRequeteMontantTotal().' AS "Apre__montantaverser"' ), $this->data[$this->name]['apre_id'] );

                    /// INFO: devrait fonctionner avec comparison, mais ce n'est pas le cas
                    $montantpositif = ( $this->data[$this->name]['montantattribue'] >= 0 );
                    if( !$montantpositif ) {
                        $this->invalidate( 'montantattribue', 'Veuillez entrer un nombre positif' );
                    }

                    $montantacceptable = ( $this->data[$this->name]['montantattribue'] <= $apre['Apre']['montantaverser'] );
                    if( !$montantacceptable ) {
                        $this->invalidate( 'montantattribue', 'Maximum: '.$apre['Apre']['montantaverser'].' €' );
                    }
                    $return = ( $return && $montantacceptable && $montantpositif );
                }
            }
            return $return;
        }

    }
?>