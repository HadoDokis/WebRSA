<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Type d\'aide en fonction des personnes chargées du suivi';?>

    <h1><?php echo $this->pageTitle;?></h1>

    <?php
        echo $xform->create( 'Suiviaideapretypeaide', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
    ?>

    <fieldset>
        <legend>Aides complémentaires</legend>
        <?php
            foreach( $aidesApres as $index => $model ) {
                $id = Set::classicExtract( $this->data, "Suiviaideapretypeaide.{$index}.id" );
                if( !empty( $id ) ) {
                    echo '<div>'.$xform->input( "Suiviaideapretypeaide.{$index}.id", array( 'type' => 'hidden' ) ).'</div>';
                }
                echo $xform->input( 'Suiviaideapretypeaide.'.$index.'.suiviaideapre_id', array( 'label' => Set::enum( $model, $natureAidesApres ), 'type' => 'select', 'options' => $personnessuivis, 'empty' => true ) );

                echo $xform->input( 'Suiviaideapretypeaide.'.$index.'.typeaide', array( 'label' => false, 'type' => 'hidden', 'value' => $model ) );
            }
        ?>
    </fieldset>

    <div class="submit">
        <?php
            echo $xform->submit( 'Enregistrer', array( 'div' => false ) );
            echo $xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
        ?>
    </div>
<?php echo $xform->end();?>

<div class="clearer"><hr /></div>