<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsetOnCheckbox( 'FiltreDtdemrsa', $( 'FiltreDtdemrsaFromDay' ).up( 'fieldset' ), false );
        observeDisableFieldsetOnCheckbox( 'FiltreDatePrint', $( 'FiltreDateImpressionFromDay' ).up( 'fieldset' ), false );
    });
</script>

<?php
    $oridemrsaCochees = Set::extract( $this->data, 'Filtre.oridemrsa' );
    if( empty( $oridemrsaCochees ) ) {
        $oridemrsaCochees = array_keys( $oridemrsa );
    }
?>
<?php echo $form->create( 'Filtre', array( 'url'=> Router::url( null, true ), 'id' => 'Filtre', 'class' => ( !empty( $this->data ) ? 'folded' : 'unfolded' ) ) );?>
    <fieldset>
        <legend>Recherche par personne</legend>
        <?php echo $form->input( 'Filtre.nom', array( 'label' => 'Nom ', 'type' => 'text' ) );?>
        <?php echo $form->input( 'Filtre.prenom', array( 'label' => 'Prénom ', 'type' => 'text' ) );?>
    </fieldset>

    <fieldset>
        <legend>Code origine demande Rsa</legend>
        <?php echo $form->input( 'Filtre.oridemrsa', array( 'label' => false, 'type' => 'select', 'multiple' => 'checkbox', 'options' => $oridemrsa, 'empty' => false, 'value' => $oridemrsaCochees ) );?>
    </fieldset>

    <fieldset>
        <legend>Commune de la personne</legend>
        <?php
			if( Configure::read( 'CG.cantons' ) ) {
				echo $form->input( 'Canton.canton', array( 'label' => 'Canton', 'type' => 'select', 'options' => $cantons, 'empty' => true ) );
			}
		?>
        <?php echo $form->input( 'Filtre.locaadr', array( 'label' => __( 'locaadr', true ), 'type' => 'text' ) );?>
        <!-- <?php echo $form->input( 'Filtre.numcomptt', array( 'label' => 'Numéro de commune au sens INSEE' ) );?> -->
        <?php echo $form->input( 'Filtre.numcomptt', array( 'label' => 'Numéro de commune au sens INSEE', 'type' => 'select', 'options' => $mesCodesInsee, 'empty' => true ) );?>
        <?php echo $form->input( 'Filtre.codepos', array( 'label' => __( 'codepos', true ), 'type' => 'text', 'maxlength' => 5 ) );?>
    </fieldset>

    <?php if( $this->action == 'orientees' ):?>
        <fieldset>
            <legend>Imprimé/Non imprimé</legend>
            <?php echo $form->input( 'Filtre.date_impression', array( 'label' => 'Filtrer par impression', 'type' => 'select', 'options' => $printed, 'empty' => true ) );?>

        <?php echo $form->input( 'Filtre.date_print', array( 'label' => 'Filtrer par date d\'impression', 'type' => 'checkbox' ) );?>
        <fieldset>
            <legend>Date d'impression</legend>
            <?php
                $dateImpressionFromSelected = array();
                if( !dateComplete( $this->data, 'Filtre.date_impression_from' ) ) {
                    $dateImpressionFromSelected = array( 'selected' => strtotime( '-1 week' ) );
                }
                echo $form->input( 'Filtre.date_impression_from', Set::merge( array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 5 ), $dateImpressionFromSelected ) );

                echo $form->input( 'Filtre.date_impression_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 5, 'minYear' => date( 'Y' ) - 5 ) );
            ?>
        </fieldset>
        </fieldset>
    <?php endif;?>

    <?php echo $form->input( 'Filtre.dtdemrsa', array( 'label' => 'Filtrer par date de demande', 'type' => 'checkbox' ) );?>
    <fieldset>
        <legend>Date de demande RSA</legend>
        <?php
            $dtdemrsaFromSelected = array();
            if( !dateComplete( $this->data, 'Filtre.dtdemrsa_from' ) ) {
                $dtdemrsaFromSelected = array( 'selected' => strtotime( '-1 week' ) );
            }
            echo $form->input( 'Filtre.dtdemrsa_from', Set::merge( array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120 ), $dtdemrsaFromSelected ) );

            echo $form->input( 'Filtre.dtdemrsa_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 5, 'minYear' => date( 'Y' ) - 120 ) );
        ?>
    </fieldset>
    <fieldset>
        <?php echo $form->input( 'Filtre.typeorient', array( 'label' => __( 'Type d\'orientation', true ), 'type' => 'select', 'options' => $modeles, 'empty' => true ) );?>
    </fieldset>
    <div class="submit">
        <?php echo $form->button( 'Filtrer', array( 'type' => 'submit' ) );?>
        <?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>
<?php echo $form->end();?>