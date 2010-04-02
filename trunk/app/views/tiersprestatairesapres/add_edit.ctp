<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Tiers prestataire APRE';?>

    <h1><?php echo $this->pageTitle;?></h1>

    <?php 
        if( $this->action == 'add' ) {
            echo $xform->create( 'Tiersprestataireapre', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
        }
        else {
            echo $xform->create( 'Tiersprestataireapre', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
            echo '<div>';
            echo $xform->input( 'Tiersprestataireapre.id', array( 'type' => 'hidden' ) );
            echo '</div>';
        }
    ?>

    <fieldset>
        <?php
            echo $xform->input( 'Tiersprestataireapre.nomtiers', array( 'required' => true, 'domain' => 'apre' ) );
            echo $xform->input( 'Tiersprestataireapre.siret', array( 'required' => true, 'domain' => 'apre' ) );
            echo $xform->input( 'Tiersprestataireapre.numvoie', array( 'domain' => 'apre' ) );
            echo $xform->enum( 'Tiersprestataireapre.typevoie', array( 'required' => true, 'domain' => 'apre', 'options' => $typevoie, 'empty' => true ) );
            echo $xform->input( 'Tiersprestataireapre.nomvoie', array( 'required' => true, 'domain' => 'apre' ) );
            echo $xform->input( 'Tiersprestataireapre.compladr', array( 'domain' => 'apre' ) );
            echo $xform->input( 'Tiersprestataireapre.codepos', array( 'required' => true, 'domain' => 'apre' ) );
            echo $xform->input( 'Tiersprestataireapre.ville', array( 'required' => true, 'domain' => 'apre' ) );
            echo $xform->input( 'Tiersprestataireapre.canton', array( 'domain' => 'apre' ) );
            echo $xform->input( 'Tiersprestataireapre.numtel', array( 'required' => true, 'domain' => 'apre' ) );
            echo $xform->input( 'Tiersprestataireapre.adrelec', array( 'domain' => 'apre' ) );
            echo $xform->input( 'Tiersprestataireapre.nomtiturib', array( 'required' => true, 'domain' => 'apre' ) );
            echo $xform->input( 'Tiersprestataireapre.etaban', array( 'required' => true, 'domain' => 'apre' ) );
            echo $xform->input( 'Tiersprestataireapre.guiban', array( 'required' => true, 'domain' => 'apre' ) );
            echo $xform->input( 'Tiersprestataireapre.numcomptban', array( 'required' => true, 'domain' => 'apre' ) );
            echo $xform->input( 'Tiersprestataireapre.clerib', array( 'required' => true, 'domain' => 'apre', 'maxlength' => 2 ) );
        ?>
    </fieldset>

    <fieldset>
        <legend>Formations liées</legend>
            <?php 
                echo $xform->enum( 'Tiersprestataireapre.aidesliees', array( 'required' => true, 'domain' => 'apre', 'options' => $natureAidesApres, 'empty' => true ) );
            ?>
    </fieldset>

    <?php echo $xform->submit( 'Enregistrer' );?>
<?php echo $xform->end();?>

<div class="clearer"><hr /></div>