<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Types d\'orientations';?>

<h1><?php echo $this->pageTitle;?></h1>

<?php 
    if( $this->action == 'add' ) {
        echo $form->create( 'Typeorient', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
        echo $form->input( 'Typeorient.id', array( 'type' => 'hidden' ) );
    }
    else {
        echo $form->create( 'Typeorient', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
        echo $form->input( 'Typeorient.id', array( 'type' => 'hidden' ) );
    }
?>

    <?php include '_form.ctp'; ?>
    <?php echo $form->submit( 'Enregistrer' );?>
<?php echo $form->end();?>
