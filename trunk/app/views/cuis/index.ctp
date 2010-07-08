<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Contrat Unique d\'Insertion';?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<div class="with_treemenu">
    <?php
        echo $html->tag(
            'h1',
            $this->pageTitle = __d( 'cui', "Cuis::{$this->action}", true )
        );
    ?>
    <?php
        if( $alerteRsaSocle ) {
            echo $html->tag(
                'p',
                $html->image( 'icons/error.png', array( 'alt' => 'Remarque' ) ).' '.sprintf( 'Cette personne ne peux bénéficier d\'un contrat Unique d\'insertion car elle ne possède pas de Rsa Socle' ),
                array( 'class' => 'error' )
            );
        }
        else{
            echo $default->index(
                $cuis,
                array(
                    'Cui.datecontrat',
                    'Cui.secteur',
                    'Cui.nomemployeur',
                    'Cui.decisioncui' => array( 'options' => $options['decisioncui'] ),
                    'Cui.datevalidationcui'
                ),
                array(
                    'actions' => array(
                        'Cui.valider',
                        'Cui.edit',
                        'Cui.print' => array( 'controller' => 'cuis', 'action' => 'gedooo' ),
                        'Cui.delete'
                    ),
                    'add' => array( 'Cui.add' => $personne_id ),
                )
            );
        }
    ?>
</div>
<div class="clearer"><hr /></div>