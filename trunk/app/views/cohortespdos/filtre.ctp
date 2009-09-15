<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php echo $form->create( 'Cohortepdo', array( 'url'=> Router::url( null, true ) ) );?>

<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsetOnCheckbox( 'CohortepdoDatedecisionpdo', $( 'CohortepdoDatedecisionpdoFromDay' ).up( 'fieldset' ), false );
    });
</script>

 <fieldset class= "noprint">
        <legend>Recherche PDO</legend>
        <?php echo $form->input( 'Cohortepdo.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>
        <?php
            echo $form->input( 'Cohortepdo.typepdo', array( 'label' => __( 'typepdo', true ), 'type' => 'select', 'options' => $typepdo, 'empty' => true ) );
            echo $form->input( 'Cohortepdo.decisionpdo', array( 'label' => __( 'decisionpdo', true ), 'type' => 'select', 'options' => $decisionpdo, 'empty' => true ) );
        ?>
            <?php echo $form->input( 'Cohortepdo.datedecisionpdo', array( 'label' => 'Filtrer par date de décision des PDOs', 'type' => 'checkbox' ) );?>
            <fieldset>
                <legend>Date de saisie du contrat</legend>
                <?php
                    $datedecisionpdo_from = Set::check( $this->data, 'Cohortepdo.datedecisionpdo_from' ) ? Set::extract( $this->data, 'Cohortepdo.datedecisionpdo_from' ) : strtotime( '-1 week' );
                    $datedecisionpdo_to = Set::check( $this->data, 'Cohortepdo.datedecisionpdo_to' ) ? Set::extract( $this->data, 'Cohortepdo.datedecisionpdo_to' ) : strtotime( 'now' );
                ?>
                <?php echo $form->input( 'Cohortepdo.datedecisionpdo_from', array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $datedecisionpdo_from ) );?>
                <?php echo $form->input( 'Cohortepdo.datedecisionpdo_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $datedecisionpdo_to ) );?>
            </fieldset>
    </fieldset>
    <div class="submit noprint">
        <?php echo $form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
         <?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?> 
    </div>
<?php echo $form->end();?>