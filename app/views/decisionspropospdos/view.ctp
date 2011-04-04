<?php
    $this->pageTitle =  __d( 'decisionpropopdo', "Decisionspropospdos::{$this->action}", true );

    echo $this->element( 'dossier_menu', array( 'id' => $dossier_id ) );
?>

<div class="with_treemenu">
    <?php
        echo $xhtml->tag( 'h1', $this->pageTitle );
        echo $form->create( 'Decisionpropopdo', array( 'type' => 'post', 'id' => 'decisionpropopdoform', 'url' => Router::url( null, true ) ) );

        echo $default2->view(
            $decisionpropopdo,
            array(
                'Decisionpropopdo.datedecisionpdo',
                'Decisionpdo.libelle',
                'Decisionpropopdo.commentairepdo',
                'Decisionpropopdo.avistechnique' => array( 'type' => 'boolean' ),
                'Decisionpropopdo.dateavistechnique',
                'Decisionpropopdo.commentaireavistechnique',
                'Decisionpropopdo.validationdecision' => array( 'type' => 'boolean' ),
                'Decisionpropopdo.datevalidationdecision',
                'Decisionpropopdo.commentairedecision'
            )/*,
            array(
                'options' => $options
            )*/
        );

    ?>
</div>
    <div class="submit">
        <?php

            echo $form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
        ?>
    </div>
    <?php echo $form->end();?>
<div class="clearer"><hr /></div>