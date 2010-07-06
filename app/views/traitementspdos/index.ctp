<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Traitements des PDOs';?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<div class="with_treemenu">
    <?php
        echo $html->tag(
            'h1',
            $this->pageTitle = __d( 'traitementpdo', "Traitementspdos::{$this->action}", true )
        );
    ?>

    <?php

// debug($traitementspdos);

        echo $default->index(
            $traitementspdos,
            array(
                'Traitementpdo.descriptionpdo_id',
                'Traitementpdo.datereception',
                'Traitementpdo.datedepart',
                'Traitementpdo.traitementtypepdo_id',
                'Traitementpdo.hascourrier',
                'Traitementpdo.hasrevenu',
                'Traitementpdo.hasficheanalyse',
                'Traitementpdo.haspiecejointe'
            ),
            array(
                'actions' => array(
                    'Traitementpdo.edit',
                    'Traitementpdo.print' => array( 'controller' => 'traitementspdos', 'action' => 'gedooo' ),
                    'Traitementpdo.delete'
                ),
                'add' => array( 'Traitementpdo.add' => $pdo_id ),
                'options' => $options
            )
        );

    ?>
</div>
<div class="clearer"><hr /></div>