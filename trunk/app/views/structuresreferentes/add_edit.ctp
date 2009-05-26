<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Structures référentes';?>

<h1><?php echo $this->pageTitle;?></h1>

<?php 
    if( $this->action == 'add' ) {
        echo $form->create( 'Structurereferente', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
        echo $form->input( 'Structurereferente.id', array( 'type' => 'hidden' ) );
        echo $form->input( 'Zonegeographique.id', array( 'type' => 'hidden' ) );
        echo $form->input( 'Structurereferente.typeorient_id', array( 'type' => 'hidden', 'value' => '' ) );

    }
    else {
        echo $form->create( 'Structurereferente', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
        echo $form->input( 'Structurereferente.id', array( 'type' => 'hidden' ) );
        echo $form->input( 'Structurereferente.typeorient_id', array( 'type' => 'hidden' ) );
        echo $form->input( 'Zonegeographique.id', array( 'type' => 'hidden' ) );
    }
?>

    <?php include '_form.ctp'; ?>
    <?php echo $form->submit( 'Enregistrer' );?>
<?php echo $form->end();?>
