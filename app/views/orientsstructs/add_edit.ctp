<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Orientations';?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Orientation';
    }
    else {
        $this->pageTitle = 'Édition de l\'orientation';
    }
?>




<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php
        if( $this->action == 'add' ) {
            echo $form->create( 'Orientstruct', array(  'type' => 'post', 'url' => Router::url( null, true )  ));
            echo '<div>';
            echo $form->input( 'Orientstruct.id', array( 'type' => 'hidden', 'value' => '' ) );
//             echo $form->input( 'Structurereferente.zonegeographique_id', array( 'type' => 'hidden', 'value' => '' ) );
//             echo $form->input( 'Structurereferente.typeorient_id', array( 'type' => 'hidden', 'value' => '' ) );
//             echo $form->input( 'Structurereferente.orientstruct_id', array( 'type' => 'hidden', 'value' => '' ) );
            echo '</div>';
        }
        else {
            echo $form->create( 'Orientstruct', array( 'type' => 'post', 'url' => Router::url( null, true )  ));
            echo '<div>';
            echo $form->input( 'Orientstruct.id', array( 'type' => 'hidden' ) );
            echo '</div>';
        }
    ?>

<?php include( '_form.ctp' ); ?>

        <?php echo $form->submit( 'Enregistrer' );?>
    <?php echo $form->end();?>
</div>
<div class="clearer"><hr /></div>