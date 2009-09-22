<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsetOnCheckbox( 'FiltreDtdemrsa', $( 'FiltreDtdemrsaFromDay' ).up( 'fieldset' ), false );
    });
</script>

<?php
    $oridemrsaCochees = Set::extract( $this->data, 'Filtre.oridemrsa' );
    if( empty( $oridemrsaCochees ) ) {
        $oridemrsaCochees = array_keys( $oridemrsa );
    }
?>
<?php echo $form->create( 'Filtre', array( 'url'=> Router::url( null, true ), 'id' => 'Filtre', 'class' => ( !empty( $this->data ) ? 'folded' : 'unfolded' ) ) );?>
<!-- <?php echo $form->create( 'Filtre', array( 'url'=> Router::url( null, true ) ) );?> -->
    <fieldset>
        <legend>Code origine demande Rsa</legend>
        <?php echo $form->input( 'Filtre.oridemrsa', array( 'label' => false, 'type' => 'select', 'multiple' => 'checkbox', 'options' => $oridemrsa, 'empty' => false, 'value' => $oridemrsaCochees ) );?>
    </fieldset>

    <fieldset>
        <legend>Commune de la personne</legend>
        <?php echo $form->input( 'Filtre.locaadr', array( 'label' => __( 'locaadr', true ), 'type' => 'text' ) );?>
        <?php echo $form->input( 'Filtre.numcomptt', array( 'label' => __( 'numcomptt', true ), 'type' => 'text', 'maxlength' => 5 ) );?>
        <?php echo $form->input( 'Filtre.codepos', array( 'label' => __( 'codepos', true ), 'type' => 'text', 'maxlength' => 5 ) );?>
    </fieldset>

    <?php if( $this->action == 'orientees' ):?>
        <fieldset>
            <legend>Imprimé/Non imprimé</legend>
            <?php echo $form->input( 'Filtre.date_impression', array( 'label' => 'Filtrer par impression', 'type' => 'select', 'options' => $printed, 'empty' => true ) );?>
        </fieldset>
    <?php endif;?>

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