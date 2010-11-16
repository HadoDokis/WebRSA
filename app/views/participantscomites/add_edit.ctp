<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Participant aux Comités d\'examen APRE';?>

    <h1><?php echo $this->pageTitle;?></h1>

    <?php
        echo $xform->create( 'Participantcomite', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );

        if( $this->action == 'edit' ) {
            echo '<div>';
            echo $xform->input( 'Participantcomite.id', array( 'type' => 'hidden' ) );
            echo '</div>';
        }
    ?>

    <fieldset>
        <?php echo $xform->input( 'Participantcomite.qual', array( 'label' => required( __( 'qual', true ) ), 'type' => 'select', 'options' => $qual, 'empty' => true ) );?>
        <?php echo $xform->input( 'Participantcomite.nom', array( 'label' => required( __( 'nom', true ) ), 'type' => 'text' ) );?>
        <?php echo $xform->input( 'Participantcomite.prenom', array( 'label' => required( __( 'prenom', true ) ), 'type' => 'text' ) );?>
        <?php echo $xform->input( 'Participantcomite.fonction', array( 'label' => required( __( 'Fonction du participant', true ) ), 'type' => 'text' ) );?>
        <?php echo $xform->input( 'Participantcomite.organisme', array( 'label' => required( __( 'Organisme du participant', true ) ), 'type' => 'text' ) );?>
        <?php echo $xform->input( 'Participantcomite.numtel', array( 'label' =>  __( 'numtel', true ), 'type' => 'text', 'maxlength' => 10 ) );?>
        <?php echo $xform->input( 'Participantcomite.mail', array( 'label' => __( 'email', true ), 'type' => 'text' ) );?>
    </fieldset>


        <div class="submit">
            <?php
                echo $xform->submit( 'Enregistrer', array( 'div' => false ) );
                echo $xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
            ?>
        </div>
<?php echo $xform->end();?>

<div class="clearer"><hr /></div>