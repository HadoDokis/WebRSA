<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsetOnCheckbox( 'FiltreDtdemrsa', $( 'FiltreDtdemrsaFromDay' ).up( 'fieldset' ), false );
    });
</script>

<?php echo $form->create( 'Filtre', array( 'url'=> Router::url( null, true ) ) );?>
    <fieldset>
        <legend>Code origine demande Rsa</legend>
        <?php echo $form->input( 'Filtre.oridemrsa', array( 'label' => false, 'type' => 'select', 'multiple' => 'checkbox', 'options' => $oridemrsa, 'empty' => '' ) );?>
    </fieldset>

    <?php echo $form->input( 'Filtre.dtdemrsa', array( 'label' => 'Filtrer par date de demande', 'type' => 'checkbox' ) );?>
    <fieldset>
        <legend>Date de demande RSA</legend>
        <?php echo $form->input( 'Filtre.dtdemrsa_from', array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => strtotime( '-1 week' ) ) );?>
        <?php echo $form->input( 'Filtre.dtdemrsa_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120 ) );?>
    </fieldset>

    <div class="submit">
        <?php echo $form->button( 'Filtrer', array( 'type' => 'submit' ) );?>
        <?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>
<?php echo $form->end();?>