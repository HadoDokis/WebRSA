<?php $this->pageTitle = 'Prestations pour un contrat';?><!-- FIXME -->
<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>


<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout d\'une prestation';
    }
    else {
        $this->pageTitle = 'Prestations d\'insertion ';
    }
?>

<div class="with_treemenu">
    <h1><?php echo 'Ajout d\'une prestation pour le contrat ';?></h1>

 <?php 
        if( $this->action == 'add' ) {
            echo $form->create( 'Prestform',array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
            echo $form->input( 'Prestform.id', array( 'type' => 'hidden') );
            echo $form->input( 'Prestform.actioninsertion_id', array( 'type' => 'hidden' ) );
            echo $form->input( 'Prestform.refpresta_id', array( 'type' => 'hidden' ) );
            echo $form->input( 'Refpresta.id', array( 'type' => 'hidden' ) );

        }
         else {
            echo $form->create( 'Prestform',array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
            echo $form->input( 'Prestform.id', array( 'type' => 'hidden' ) );
            echo $form->input( 'Prestform.actioninsertion_id', array( 'type' => 'hidden' ) );
            echo $form->input( 'Prestform.refpresta_id', array( 'type' => 'hidden' ) );
            echo $form->input( 'Refpresta.id', array( 'type' => 'hidden' ) );

         }

    ?>
<?php include "_form.ctp"; ?>

        <?php echo $form->submit( 'Enregistrer' );?>
    <?php echo $form->end();?>
</div>
<div class="clearer"><hr /></div>

