<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php echo $form->create( 'Cohortepdo', array( 'url'=> Router::url( null, true ) ) );?>

 <fieldset class= "noprint">
        <legend>Recherche PDO</legend>
        <?php echo $form->input( 'Cohortepdo.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>
        <?php
//             if( $this->action == 'valide' ) {
                echo $form->input( 'Cohortepdo.typepdo', array( 'label' => __( 'typepdo', true ), 'type' => 'select', 'options' => $typepdo, 'empty' => true ) );
                echo $form->input( 'Cohortepdo.decisionpdo', array( 'label' => __( 'decisionpdo', true ), 'type' => 'select', 'options' => $decisionpdo, 'empty' => true ) );
                echo $form->input( 'Cohortepdo.datedecisionpdo', array( 'label' => __( 'datedecisionpdo', true ), 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear'=>date('Y'), 'minYear'=>date('Y')-80, 'empty' => true ) );
//             }
        ?>
    </fieldset>
    <div class="submit noprint">
        <?php echo $form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
         <?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?> 
    </div>
<?php echo $form->end();?>