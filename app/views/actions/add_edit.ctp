<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Actions d\'insertion';?>

<h1><?php echo $this->pageTitle;?></h1>

<?php
    if( $this->action == 'add' ) {
        echo $form->create( 'Action', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
//         echo '<div>';
//         echo $form->input( 'Typeaction.id', array( 'type' => 'hidden' ) );
//         echo '</div>';
    }
    else {
        echo $form->create( 'Action', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
        echo $form->input( 'Action.id', array( 'type' => 'hidden' ) );
    }
?>

<fieldset>
    <?php echo $form->input( 'Action.code', array( 'label' =>  required( __( 'code_action', true ) ), 'type' => 'text', 'maxlength' => 2 ) );?>
    <?php echo $form->input( 'Action.libelle', array( 'label' =>  required( __( 'lib_action', true ) ), 'type' => 'text' ) );?>
    <?php echo $form->input( 'Action.typeaction_id', array( 'label' =>  required( __( 'type_action', true ) ), 'type' => 'select', 'options' => $libtypaction, 'empty' => true ) );?>
</fieldset>

    <?php echo $form->submit( 'Enregistrer' );?>
<?php echo $form->end();?>
