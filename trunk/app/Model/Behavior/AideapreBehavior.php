<?php
    class AideapreBehavior extends ModelBehavior
    {
        function beforeSave( Model $model ) {
            parent::beforeSave( $model );

            $suivi = ClassRegistry::init( 'Suiviaideapretypeaide' );

			$qd_personne = array(
				'conditions' => array(
					'Suiviaideapretypeaide.typeaide' => $model->name
				)
			);
			$personne = $suivi->find('first', $qd_personne);


            if( !empty( $personne ) ) {
                foreach( array( 'qual', 'nom', 'prenom', 'numtel' ) as $field ) {
                    $model->data[$model->name]["{$field}suivi"] = Set::classicExtract( $personne, "Suiviaideapre.{$field}" );
                }
            }
        }
    }
?>