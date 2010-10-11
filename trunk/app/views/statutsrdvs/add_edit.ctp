<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Statut de rendez-vous';?>

<h1><?php echo $this->pageTitle;?></h1>

<?php
    if( $this->action == 'add' ) {
        echo $form->create( 'Statutrdv', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
        echo $form->input( 'Statutrdv.id', array( 'type' => 'hidden' ) );
    }
    else {
        echo $form->create( 'Statutrdv', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
        echo $form->input( 'Statutrdv.id', array( 'type' => 'hidden' ) );
    }
?>

<fieldset>
    <?php echo $form->input( 'Statutrdv.libelle', array( 'label' =>  required( __( 'Statut du RDV', true ) ), 'type' => 'text' ) );?>
</fieldset>

    <?php echo $form->submit( 'Enregistrer' );?>
<?php echo $form->end();?>
