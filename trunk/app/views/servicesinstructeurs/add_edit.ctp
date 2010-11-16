<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Services instructeurs';?>

<h1><?php echo $this->pageTitle;?></h1>

<?php 
    if( $this->action == 'add' ) {
        echo $form->create( 'Serviceinstructeur', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
        echo '<div>';
        echo $form->input( 'Serviceinstructeur.id', array( 'type' => 'hidden' ) );
        echo '</div>';
    }
    else {
        echo $form->create( 'Serviceinstructeur', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
        echo '<div>';
        echo $form->input( 'Serviceinstructeur.id', array( 'type' => 'hidden' ) );
        echo '</div>';
    }
?>

    <?php include '_form.ctp'; ?>

        <div class="submit">
            <?php
                echo $xform->submit( 'Enregistrer', array( 'div' => false ) );
                echo $xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
            ?>
        </div>
<?php echo $form->end();?>
