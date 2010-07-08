<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>


<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsOnValue( 'CuiDecisioncui', [ 'CuiDatevalidationcuiDay', 'CuiDatevalidationcuiMonth', 'CuiDatevalidationcuiYear' ], 'V', false );
    });
</script>
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsetOnCheckbox( 'FiltreDatecontrat', $( 'FiltreDatecontratFromDay' ).up( 'fieldset' ), false );
    });
</script>


<?php
    if( is_array( $this->data ) ) {
        echo '<ul class="actionMenu"><li>'.$html->link(
            $html->image(
                'icons/application_form_magnify.png',
                array( 'alt' => '' )
            ).' Formulaire',
            '#',
            array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Filtre' ).toggle(); return false;" )
        ).'</li></ul>';
    }
?>

<?php echo $form->create( 'Filtre', array( 'url'=> Router::url( null, true ), 'id' => 'Filtre', 'class' => ( !empty( $this->data ) ? 'folded' : 'unfolded' ) ) );?>
    <fieldset>
        <legend>Recherche par personne</legend>
        <?php echo $form->input( 'Filtre.nom', array( 'label' => 'Nom ', 'type' => 'text' ) );?>
        <?php echo $form->input( 'Filtre.prenom', array( 'label' => 'Prénom ', 'type' => 'text' ) );?>
    </fieldset>
    <fieldset>
        <legend>Recherche de CUI</legend>
            <?php echo $form->input( 'Filtre.datecontrat', array( 'label' => 'Filtrer par date de saisie du contrat', 'type' => 'checkbox' ) );?>
            <fieldset>
                <legend>Date de saisie du contrat</legend>
                <?php
                    $datecontrat_from = Set::check( $this->data, 'Filtre.datecontrat_from' ) ? Set::extract( $this->data, 'Filtre.datecontrat_from' ) : strtotime( '-1 week' );
                    $datecontrat_to = Set::check( $this->data, 'Filtre.datecontrat_to' ) ? Set::extract( $this->data, 'Filtre.datecontrat_to' ) : strtotime( 'now' );
                ?>
                <?php echo $form->input( 'Filtre.datecontrat_from', array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $datecontrat_from ) );?>
                <?php echo $form->input( 'Filtre.datecontrat_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $datecontrat_to ) );?>
            </fieldset>
            <?php echo $form->input( 'Filtre.locaadr', array( 'label' => 'Commune de l\'allocataire ', 'type' => 'text' ) );?>
            <!-- <?php echo $form->input( 'Filtre.numcomptt', array( 'label' => 'Numéro de commune au sens INSEE' ) );?> -->
            <?php echo $form->input( 'Filtre.numcomptt', array( 'label' => 'Numéro de commune au sens INSEE', 'type' => 'select', 'options' => $mesCodesInsee, 'empty' => true ) );?>
            <?php
                if( Configure::read( 'CG.cantons' ) ) {
                    echo $form->input( 'Canton.canton', array( 'label' => 'Canton', 'type' => 'select', 'options' => $cantons, 'empty' => true ) );
                }
            ?>
            <?php echo $form->input( 'Filtre.structurereferente_id', array( 'label' => __( 'lib_struct', true ), 'type' => 'select', 'options' => $struct, 'empty' => true ) ); ?>
            <?php echo $form->input( 'Filtre.referent_id', array( 'label' => __( 'Nom du référent', true ), 'type' => 'select', 'options' => $referents, 'empty' => true ) ); ?>
            <?php echo $ajax->observeField( 'FiltreStructurereferenteId', array( 'update' => 'FiltreReferentId', 'url' => Router::url( array( 'action' => 'ajaxreferent' ), true ) ) );?>
            <?php echo $form->input( 'Filtre.decisioncui', array( 'label' => 'Statut du contrat', 'type' => 'select', 'options' => $options['decisioncui'], 'empty' => true ) ); ?>
            <?php echo $form->input( 'Filtre.datevalidationcui', array( 'label' => '', 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true)  ); ?>

    </fieldset>

    <div class="submit noprint">
        <?php echo $form->button( 'Filtrer', array( 'type' => 'submit' ) );?>
        <?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>
<?php echo $form->end();?>