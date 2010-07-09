<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Périodes d\'immersion';?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<div class="with_treemenu">
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
                'Periodeimmersion.datdebperiode',
                'Periodeimmersion.datfinperiode',
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
                'add' => array( 'Periodeimmersion.add' => $personne_id ),
            )
        );

    ?>
</div>
<div class="clearer"><hr /></div>