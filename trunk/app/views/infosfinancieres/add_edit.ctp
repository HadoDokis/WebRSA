<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Informations financières du Dossier';?>

<?php echo $this->element( 'dossier_menu', array( 'id' => $dossier_id ) );?>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Déclaration d\'informations financières';
    }
    else {
        $this->pageTitle = 'Édition d\'informations financières';
    }
?>



<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php
        if( $this->action == 'add' ) {
            echo $form->create( 'Infofinanciere', array(  'type' => 'post', 'url' => Router::url( null, true )  ));
            echo '<div>';
            echo $form->input( 'Infofinanciere.id', array( 'type' => 'hidden', 'value' => '' ) );
            echo $form->input( 'Infofinanciere.dossier_rsa_id', array( 'type' => 'hidden' ) );
            echo '</div>';
        }
        else {
            echo $form->create( 'Infofinanciere', array( 'type' => 'post', 'url' => Router::url( null, true )  ));
            echo '<div>';
            echo $form->input( 'Infofinanciere.id', array( 'type' => 'hidden' ) );
            echo $form->input( 'Infofinanciere.dossier_rsa_id', array( 'type' => 'hidden' ) );
            echo '</div>';
        }
    ?>

<?php include( '_form.ctp' ); ?>

        <?php echo $form->submit( 'Enregistrer' );?>
    <?php echo $form->end();?>
</div>
<div class="clearer"><hr /></div>
