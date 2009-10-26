<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  $this->pageTitle = 'Validation PDO';?>

<?php echo $this->element( 'dossier_menu', array( 'id' => $dossier_rsa_id ) );?>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout PDO';
    }
    else {
        $this->pageTitle = 'Édition PDO';
    }
?>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php

        if( $this->action == 'add' ) {
            echo $form->create( 'Propopdo', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
//             echo $form->input( 'PropopdoTypenotifpdo.id', array( 'type' => 'hidden' ) );
        }
        else {
            echo $form->create( 'Propopdo', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
            echo '<div>';
            echo $form->input( 'Propopdo.id', array( 'type' => 'hidden' ) );
//             echo $form->input( 'PropopdoTypenotifpdo.id', array( 'type' => 'hidden' ) );

            echo '</div>';
        }
        echo '<div>';
        echo $form->input( 'Propopdo.dossier_rsa_id', array( 'type' => 'hidden', 'value' => $dossier_rsa_id ) );

        echo '</div>';
    ?>

    <div class="aere">
        <fieldset>
            <legend>Détails PDO</legend>
            <?php echo $form->input( 'Propopdo.typepdo_id', array( 'label' =>  ( __( 'typepdo', true ) ), 'type' => 'select', 'options' => $typepdo, 'empty' => true ) );?>
            <?php echo $form->input( 'Propopdo.decisionpdo_id', array( 'label' =>  ( __( 'Décision du Conseil Général', true ) ), 'type' => 'select', 'options' => $decisionpdo, 'empty' => true ) );?>
            <?php echo $form->input( 'Propopdo.motifpdo', array( 'label' =>  ( __( 'Motif de la décision', true ) ), 'type' => 'select', 'options' => $motifpdo, 'empty' => true ) );?>
            <?php echo $form->input( 'Propopdo.datedecisionpdo', array( 'label' =>  ( __( 'Date de décision CG', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => true ) );?>
             <!-- <?php echo $form->input( 'Propopdo.typenotifpdo_id', array( 'label' =>  ( __( 'Type de notification', true ) ), 'type' => 'select', 'options' => $typenotifpdo, 'empty' => true ) );?>
           <?php echo $form->input( 'PropopdoTypenotifpdo.datenotifpdo', array( 'label' =>  ( __( 'Date de notification', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => true ) );?> -->
            <?php echo $form->input( 'Propopdo.commentairepdo', array( 'label' =>  'Observations', 'type' => 'text', 'rows' => 3, 'empty' => true ) );?>
        </fieldset>
    </div>

            <?php echo $form->submit( 'Enregistrer' );?>
    <?php echo $form->end();?>
</div>
<div class="clearer"><hr /></div>