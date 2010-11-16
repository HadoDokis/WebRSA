<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Type d\'actions d\'insertion';?>

<h1><?php echo $this->pageTitle;?></h1>

<?php
    if( $this->action == 'add' ) {
        echo $form->create( 'Typeaction', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
    }
    else {
        echo $form->create( 'Typeaction', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
        echo $form->input( 'Typeaction.id', array( 'type' => 'hidden' ) );
    }
?>

<fieldset>
    <?php echo $form->input( 'Typeaction.libelle', array( 'label' =>  required( __( 'lib_action', true ) ), 'type' => 'text' ) );?>
</fieldset>

    <?php echo $form->submit( 'Enregistrer' );?>
<?php echo $form->end();?>
