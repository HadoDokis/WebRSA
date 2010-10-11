<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Périodes d\'immersion';?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<div class="with_treemenu aere">
    <?php
        echo $html->tag(
            'h1',
            $this->pageTitle = __d( 'periodeimmersion', "Periodesimmersion::{$this->action}", true )
        );
    ?>
    <?php
        echo $default->index(
            $periodesimmersion,
            array(
                'Periodeimmersion.datedebperiode',
                'Periodeimmersion.datefinperiode',
                'Periodeimmersion.nomentaccueil',
                'Periodeimmersion.objectifimmersion' => array( 'options' => $options['objectifimmersion'] ),
                'Periodeimmersion.datesignatureimmersion'
            ),
            array(
                'actions' => array(
                    'Periodeimmersion.edit',
                    'Periodeimmersion.print' => array( 'controller' => 'periodesimmersion', 'action' => 'gedooo' ),
                    'Periodeimmersion.delete'
                ),
                'add' => array( 'Periodeimmersion.add' => $cui_id )
            )
        );

    ?>
</div>
    <?php echo $xform->create( 'Periodeimmersion' );?>
    <div class="submit">
        <?php
            echo $xform->submit( 'Retour au CUI', array( 'name' => 'Cancel', 'div' => false ) );
        ?>
    </div>
    <?php echo $xform->end(); ?>
<div class="clearer"><hr /></div>