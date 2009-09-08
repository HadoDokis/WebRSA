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
        }
        else {
            echo $form->create( 'Propopdo', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
            echo '<div>';
            echo $form->input( 'Propopdo.id', array( 'type' => 'hidden' ) );
            echo '</div>';
        }
        echo '<div>';
        echo $form->input( 'Propopdo.dossier_rsa_id', array( 'type' => 'hidden', 'value' => $dossier_rsa_id ) );
        echo '</div>';
    ?>

    <div class="aere">
        <fieldset>
            <legend>Détails PDO</legend>
            <?php echo $form->input( 'Propopdo.typepdo', array( 'label' =>  ( __( 'typepdo', true ) ), 'type' => 'select', 'options' => $typepdo, 'empty' => true ) );?>
            <?php echo $form->input( 'Propopdo.datedecisionpdo', array( 'label' =>  ( __( 'datedecisionpdo', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => true ) );?>
            <?php echo $form->input( 'Propopdo.decisionpdo', array( 'label' =>  ( __( 'decisionpdo', true ) ), 'type' => 'select', 'options' => $decisionpdo, 'empty' => true ) );?>
            <?php echo $form->input( 'Propopdo.commentairepdo', array( 'label' =>  ( __( 'commentairepdo', true ) ), 'type' => 'text', 'rows' => 3, 'empty' => true ) );?>
            <!-- <?php  /*echo $form->input( 'Propopdo.ressdero', array( 'label' =>  ( __( 'ressdero', true ) ), 'type' => 'text') );?>
            <?php echo $form->input( 'Propopdo.motidempdo', array( 'label' =>  ( __( 'motidempdo', true ) ), 'type' => 'select', 'options' => $motidempdo, 'empty' => true ) );?>
            <?php echo $form->input( 'Propopdo.ciobli', array( 'label' =>  ( __( 'Contrat d\'insertion obligatoire', true ) ), 'type' => 'select', 'options' => $avisdero, 'empty' => true ) );?>
            <?php echo $form->input( 'Propopdo.datesave', array( 'label' =>  ( __( 'Date d\'enregistrement CG', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => true ) );?>
        </fieldset>
        <fieldset>
            <legend>Décision CG</legend>
            <?php echo $form->input( 'Propopdo.commission', array( 'label' =>  ( __( 'commission', true ) ), 'type' => 'select', 'options' => $commission, 'empty' => true ) );?>
            <?php echo $form->input( 'Propopdo.motideccg', array( 'label' =>  ( __( 'motideccg', true ) ), 'type' => 'select', 'options' => $motideccg, 'empty' => true ) );?>
            <?php echo $form->input( 'Propopdo.commentdero', array( 'label' =>  ( __( 'commentdero', true ) ), 'type' => 'textarea', 'rows' => 3, 'empty' => true ) );*/?> -->
        </fieldset>
    </div>

            <?php echo $form->submit( 'Enregistrer' );?>
    <?php echo $form->end();?>
</div>
<div class="clearer"><hr /></div>