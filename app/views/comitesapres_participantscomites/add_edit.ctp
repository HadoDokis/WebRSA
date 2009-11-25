<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  $this->pageTitle = 'Ajout de participant au comité d\'examen';?>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout participant';
    }
    else {
        $this->pageTitle = 'Édition participant';
    }
?>


    <h1><?php echo $this->pageTitle;?></h1>

    <?php echo $xform->create( 'ComiteapreParticipantcomite', array( 'type' => 'post', 'url' => Router::url( null, true ) ) ); ?>
        <div class="aere">
            <fieldset>
                <legend>Participants au comité</legend>
                <?php echo $xform->input( 'Comiteapre.id', array( 'label' => false, 'type' => 'hidden' ) ) ;?>
                <?php echo $xform->input( 'Participantcomite.Participantcomite', array( 'label' =>  false, 'type' => 'select', 'options' => $participantcomite, 'multiple' => 'checkbox' ) );?>
            </fieldset>
        </div>

        <?php echo $xform->submit( 'Enregistrer' );?>
    <?php echo $xform->end();?>

<div class="clearer"><hr /></div>