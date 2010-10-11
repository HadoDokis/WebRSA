<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Référents';?>

<h1><?php echo $this->pageTitle;?></h1>

<?php 
    if( $this->action == 'add' ) {
        echo $form->create( 'Referent', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
        echo '<div>';
        echo $form->input( 'Referent.id', array( 'type' => 'hidden' ) );
        echo $form->input( 'Referent.structurereferente_id', array( 'type' => 'hidden', 'value' => '' ) );
        echo '</div>';
    }
    else {
        echo $form->create( 'Referent', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
        echo '<div>';
        echo $form->input( 'Referent.id', array( 'type' => 'hidden' ) );
//         echo $form->input( 'Referent.structurereferente_id', array( 'type' => 'hidden' ) );
        echo '</div>';
    }
?>

    <?php include '_form.ctp'; ?>
    <?php echo $form->submit( 'Enregistrer' );?>
<?php echo $form->end();?>
