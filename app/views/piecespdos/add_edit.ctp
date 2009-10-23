<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Pieces PDOs';?>

    <h1><?php echo $this->pageTitle;?></h1>

    <?php 
        if( $this->action == 'add' ) {
            echo $form->create( 'Piecepdo', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
        }
        else {
            echo $form->create( 'Piecepdo', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
//             echo $form->input( 'Piecepdo.id', array( 'type' => 'hidden' ) );
        }
    ?>

    <fieldset>
        <?php echo $form->input( 'Piecepdo.propopdo_id', array( 'label' => false, 'type' => 'hidden', 'value' => Set::classicExtract( $pdo, 'Propopdo.id' ) ) );?>
        <?php echo $form->input( 'Piecepdo.libelle', array( 'label' => required( __( 'Intitulé de la pièce', true ) ), 'type' => 'text' ) );?>
         <?php echo $form->input( 'Piecepdo.dateajout', array( 'label' => required( __( 'Date de l\'ajout', true ) ), 'type' => 'date', 'dateFormat' => 'DMY' ) );?>
    </fieldset>

        <?php echo $form->submit( 'Enregistrer' );?>
    <?php echo $form->end();?>

<div class="clearer"><hr /></div>