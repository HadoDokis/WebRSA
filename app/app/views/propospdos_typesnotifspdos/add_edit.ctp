<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  $this->pageTitle = 'Ajout d\'un traitement pour la PDO';?>

<?php echo $this->element( 'dossier_menu', array( 'id' => $dossier_rsa_id ) );?>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout traitement';
    }
    else {
        $this->pageTitle = 'Édition traitement';
    }
?>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php

        if( $this->action == 'add' ) {
            echo $form->create( 'PropopdoTypenotifpdo', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
        }
        else {
            echo $form->create( 'PropopdoTypenotifpdo', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
            echo '<div>';
            echo $form->input( 'PropopdoTypenotifpdo.id', array( 'type' => 'hidden' ) );
            echo '</div>';
        }

    ?>

    <div class="aere">
        <fieldset>
            <legend>Détails PDO</legend>
                <?php echo $form->input( 'PropopdoTypenotifpdo.propopdo_id', array( 'label' => false, 'type' => 'hidden' ) ) ;?>
              <?php echo $form->input( 'PropopdoTypenotifpdo.typenotifpdo_id', array( 'label' =>  ( __( 'Type de notification', true ) ), 'type' => 'select', 'options' => $typenotifpdo, 'empty' => true ) );?>
                <?php echo $form->input( 'PropopdoTypenotifpdo.datenotifpdo', array( 'label' =>  ( __( 'Date de notification', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => true ) );?> 
        </fieldset>
    </div>

            <?php echo $form->submit( 'Enregistrer' );?>
    <?php echo $form->end();?>
</div>
<div class="clearer"><hr /></div>