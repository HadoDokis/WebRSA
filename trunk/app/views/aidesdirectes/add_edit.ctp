<?php $this->pageTitle = 'Aides pour un contrat';?><!-- FIXME -->
<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>


<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout d\'une aide';
    }
    else {
        $this->pageTitle = 'Aides d\'insertion ';
    }
?>

<div class="with_treemenu">
    <h1><?php echo 'Ajout d\'une aide pour le contrat ';?></h1><!-- FIXME -->

    <?php 
        if( $this->action == 'add' ) {
            echo $form->create( 'Aidedirecte', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
            echo '<div>';
            echo $form->input( 'Aidedirecte.id', array( 'type' => 'hidden' ) );
            echo $form->input( 'Aidedirecte.actioninsertion_id', array( 'type' => 'hidden' ) );
            echo '</div>';
        }
        else {
            echo $form->create( 'Aidedirecte', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
            echo '<div>';
            echo $form->input( 'Aidedirecte.id', array( 'type' => 'hidden' ) );
            echo $form->input( 'Aidedirecte.actioninsertion_id', array( 'type' => 'hidden' ) );
            echo '</div>';
        }
    ?>


    <?php include "_form.ctp"; ?>
    <?php echo $form->submit( 'Enregistrer' );?>

    <?php echo $form->end();?>
</div>
<div class="clearer"><hr /></div>