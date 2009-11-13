<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Référents APRE';?>

    <h1><?php echo $this->pageTitle;?></h1>

    <?php 
        if( $this->action == 'add' ) {
            echo $form->create( 'Referentapre', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
//             echo $form->input( 'Referentapre.id', array( 'type' => 'hidden', 'value' => '' ) );
        }
        else {
            echo $form->create( 'Referentapre', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
            echo '<div>';
            echo $form->input( 'Referentapre.id', array( 'type' => 'hidden' ) );
            echo '</div>';
        }
    ?>

    <fieldset>
        <?php echo $form->input( 'Referentapre.qual', array( 'label' => required( __( 'qual', true ) ), 'type' => 'select', 'options' => $qual, 'empty' => true ) );?>
        <?php echo $form->input( 'Referentapre.nom', array( 'label' => required( __( 'nom', true ) ), 'type' => 'text' ) );?>
        <?php echo $form->input( 'Referentapre.prenom', array( 'label' => required( __( 'prenom', true ) ), 'type' => 'text' ) );?>
        <?php echo $form->input( 'Referentapre.adresse', array( 'label' => required( __( 'adresse', true ) ), 'type' => 'text' ) );?>
        <?php echo $form->input( 'Referentapre.numtel', array( 'label' => required( __( 'numtel', true ) ), 'type' => 'text' ) );?>
        <?php echo $form->input( 'Referentapre.email', array( 'label' => required( __( 'email', true ) ), 'type' => 'text' ) );?>
        <?php echo $form->input( 'Referentapre.fonction', array( 'label' => required( __( 'fonction', true ) ), 'type' => 'text' ) );?>
        <?php echo $form->input( 'Referentapre.organismeref', array( 'label' => required( __d( 'apre', 'Referentapre.organismeref', true ) ), 'type' => 'text' ) );?>
        <?php echo $xform->enum( 'Referentapre.spe', array( 'label' => required( __d( 'apre', 'Referentapre.spe', true ) ), 'type' => 'select', 'options' => $options['spe'], 'empty' => true ) );?>
    </fieldset>

    <?php echo $form->submit( 'Enregistrer' );?>
<?php echo $form->end();?>

<div class="clearer"><hr /></div>