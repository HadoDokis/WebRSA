<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Type de contrats d\'insertion';?>

<h1><?php echo $this->pageTitle;?></h1>

<?php 
    if( $this->action == 'add' ) {
        echo $form->create( 'Typocontrat', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
//         echo $form->input( 'Typocontrat.id', array( 'type' => 'hidden', 'value' => '' ) );
    }
    else {
        echo $form->create( 'Typocontrat', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
        echo $form->input( 'Typocontrat.id', array( 'type' => 'hidden' ) );
    }
?>

    <?php include '_form.ctp'; ?>
    <?php echo $form->submit( 'Enregistrer' );?>
<?php echo $form->end();?>
