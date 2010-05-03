<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Types de PDOs';?>

    <h1><?php echo $this->pageTitle;?></h1>

    <?php 
        if( $this->action == 'add' ) {
            echo $form->create( 'Typepdo', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
            echo $form->input( 'Typepdo.id', array( 'type' => 'hidden', 'value' => '' ) );
        }
        else {
            echo $form->create( 'Typepdo', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
            echo $form->input( 'Typepdo.id', array( 'type' => 'hidden' ) );
        }
    ?>

    <fieldset>
        <?php echo $form->input( 'Typepdo.libelle', array( 'label' => required( __( 'Type de PDO', true ) ), 'type' => 'text' ) );?>
    </fieldset>

        <?php echo $form->submit( 'Enregistrer' );?>
    <?php echo $form->end();?>

<div class="clearer"><hr /></div>