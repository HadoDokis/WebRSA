<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Utilisateurs';?>

<h1><?php echo $this->pageTitle;?></h1>

<?php 
    if( $this->action == 'add' ) {
        echo $form->create( 'User', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
        echo $form->input( 'User.id', array( 'type' => 'hidden' ) );
        echo $form->input( 'Zonegeographique.id', array( 'type' => 'hidden' ) );
    }
    else {
        echo $form->create( 'User', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
        echo $form->input( 'User.id', array( 'type' => 'hidden' ) );
        echo $form->input( 'Zonegeographique.id', array( 'type' => 'hidden' ) );

    }
?>

    <?php include '_form.ctp'; ?>
    <?php echo $form->submit( 'Enregistrer' );?>
<?php echo $form->end();?>
