<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Type de rendez-vous';?>

<h1><?php echo $this->pageTitle;?></h1>

<?php
    if( $this->action == 'add' ) {
        echo $form->create( 'Typerdv', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
        echo $form->input( 'Typerdv.id', array( 'type' => 'hidden' ) );
    }
    else {
        echo $form->create( 'Typerdv', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
        echo $form->input( 'Typerdv.id', array( 'type' => 'hidden' ) );
    }
?>

<fieldset>
    <?php echo $form->input( 'Typerdv.libelle', array( 'label' =>  required( __( 'lib_rdv', true ) ), 'type' => 'text' ) );?>
</fieldset>

    <?php echo $form->submit( 'Enregistrer' );?>
<?php echo $form->end();?>
