<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Participant aux Comités d\'examen APRE';?>

    <h1><?php echo $this->pageTitle;?></h1>

    <?php
        echo $xform->create( 'Participantcomiteexamen', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );

        if( $this->action == 'edit' ) {
            echo '<div>';
            echo $xform->input( 'Participantcomiteexamen.id', array( 'type' => 'hidden' ) );
            echo '</div>';
        }
    ?>

    <fieldset>
        <?php echo $xform->input( 'Participantcomiteexamen.qual', array( 'label' => required( __( 'qual', true ) ), 'type' => 'select', 'options' => $qual, 'empty' => true ) );?>
        <?php echo $xform->input( 'Participantcomiteexamen.nom', array( 'label' => required( __( 'nom', true ) ), 'type' => 'text' ) );?>
        <?php echo $xform->input( 'Participantcomiteexamen.prenom', array( 'label' => required( __( 'prenom', true ) ), 'type' => 'text' ) );?>
        <?php echo $xform->input( 'Participantcomiteexamen.fonction', array( 'label' => required( __( 'Fonction du participant', true ) ), 'type' => 'text' ) );?>
        <?php echo $xform->input( 'Participantcomiteexamen.organisme', array( 'label' => required( __( 'Organisme du participant', true ) ), 'type' => 'text' ) );?>
        <?php echo $xform->input( 'Participantcomiteexamen.numtel', array( 'label' =>  __( 'numtel', true ), 'type' => 'text', 'maxlength' => 10 ) );?>
        <?php echo $xform->input( 'Participantcomiteexamen.mail', array( 'label' => __( 'email', true ), 'type' => 'text' ) );?>
    </fieldset>

    <?php echo $xform->submit( 'Enregistrer' );?>
<?php echo $xform->end();?>

<div class="clearer"><hr /></div>