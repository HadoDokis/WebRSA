<?php
    $domain = 'periodeimmersion';
    echo $this->element( 'dossier_menu', array( 'id' => $dossier_id, 'personne_id' => $personne_id ) );
    echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
?>

<div class="with_treemenu">
    <?php
        echo $html->tag(
            'h1',
            $this->pageTitle = __d( 'periodeimmersion', "Periodeimmersions::{$this->action}", true )
        );

    ?>
    <?php
        echo $xform->create( 'Periodeimmersion', array( 'id' => 'periodeimmersionform' ) );
        if( Set::check( $this->data, 'Periodeimmersion.id' ) ) {
            echo '<div>'.$xform->input( 'Periodeimmersion.id', array( 'type' => 'hidden' ) ).'</div>';
        }
    ?>

    <div>
        <?php
            echo $default->subform(
                array(
                    'Periodeimmersion.cui_id' => array( 'value' => $cui_id, 'type' => 'hidden' ),
                    'Periodeimmersion.convention' => array( /*'div' => false,*/ 'legend' => required( __d( 'periodeimmersion', 'Periodeimmersion.convention', true )  ), 'type' => 'radio', 'options' => $options['convention'] ),
                    'Periodeimmersion.secteur' => array( /*'div' => false,*/ 'legend' => required( __d( 'periodeimmersion', 'Periodeimmersion.secteur', true )  ), 'type' => 'radio', 'options' => $options['secteur'] )
                ),
                array(
                    'domain' => $domain,
                    'options' => $options
                )
            );
        ?>
    </div>

    <div class="submit">
        <?php
            echo $xform->submit( 'Enregistrer', array( 'div' => false ) );
            echo $xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
        ?>
    </div>
    <?php echo $xform->end();?>
</div>

<div class="clearer"><hr /></div>