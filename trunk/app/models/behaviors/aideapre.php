<?php
    class AideapreBehavior extends ModelBehavior
    {
        function beforeSave( &$model ) {
            parent::beforeSave( $model );

            $suivi = ClassRegistry::init( 'Suiviaideapretypeaide' );

            $personne = $suivi->findByTypeaide( $model->name );
            if( !empty( $personne ) ) {
                foreach( array( 'qual', 'nom', 'prenom', 'numtel' ) as $field ) {
                    $model->data[$model->name]["{$field}suivi"] = Set::classicExtract( $personne, "Suiviaideapre.{$field}" );
                }
            }
        }
    }
?>