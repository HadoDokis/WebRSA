<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Types de notification PDO';?>

    <h1><?php echo $this->pageTitle;?></h1>

    <?php 
        if( $this->action == 'add' ) {
            echo $form->create( 'Typenotif', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
            echo $form->input( 'Typenotif.id', array( 'type' => 'hidden', 'value' => '' ) );
        }
        else {
            echo $form->create( 'Typenotif', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
            echo $form->input( 'Typenotif.id', array( 'type' => 'hidden' ) );
        }
    ?>

    <fieldset>
        <?php echo $form->input( 'Typenotif.libelle', array( 'label' => required( __( 'Type de notification', true ) ), 'type' => 'text' ) );?>
        <?php echo $form->input( 'Typenotif.modelenotifpdo', array( 'label' => required( __( 'Modèle de notification', true ) ), 'type' => 'text' ) );?>
    </fieldset>

        <?php echo $form->submit( 'Enregistrer' );?>
    <?php echo $form->end();?>

<div class="clearer"><hr /></div>