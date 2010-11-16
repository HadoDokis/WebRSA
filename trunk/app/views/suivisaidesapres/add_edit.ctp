<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Personne chargée du suivi des Aides APREs';?>

    <h1><?php echo $this->pageTitle;?></h1>

    <?php
        echo $xform->create( 'Suiviaideapre', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );

        if( $this->action == 'edit' ) {
            echo '<div>';
            echo $xform->input( 'Suiviaideapre.id', array( 'type' => 'hidden' ) );
            echo '</div>';
        }
    ?>

    <fieldset>
        <?php echo $xform->input( 'Suiviaideapre.qual', array( 'label' => required( __( 'qual', true ) ), 'type' => 'select', 'options' => $qual, 'empty' => true ) );?>
        <?php echo $xform->input( 'Suiviaideapre.nom', array( 'label' => required( __( 'nom', true ) ), 'type' => 'text' ) );?>
        <?php echo $xform->input( 'Suiviaideapre.prenom', array( 'label' => required( __( 'prenom', true ) ), 'type' => 'text' ) );?>
        <?php echo $xform->input( 'Suiviaideapre.numtel', array( 'label' =>  __( 'numtel', true ), 'type' => 'text', 'maxlength' => 10 ) );?>
    </fieldset>

        <div class="submit">
            <?php
                echo $xform->submit( 'Enregistrer', array( 'div' => false ) );
                echo $xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
            ?>
        </div>
<?php echo $xform->end();?>

<div class="clearer"><hr /></div>