<?php
    $typeorient_id = null;
    if( !empty( $this->data['Structurereferente']['Typeorient']['id'] ) ) {
        $typeorient_id = $this->data['Structurereferente']['Typeorient']['id'];
    }
    $domain = 'orientstruct';
?>


<?php if( Configure::read( 'nom_form_ci_cg' ) == 'cg66' ):?>
<fieldset><legend></legend>
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        dependantSelect( 'OrientstructReferentorientantId', 'OrientstructStructureorientanteId' );
        try { $( 'OrientstructReferentorientantId' ).onchange(); } catch(id) { }
    });
</script>

    <?php
        $selected = null;
        if( $this->action == 'edit' ){
            $selected = preg_replace( '/^[^_]+_/', '', $this->data['Orientstruct']['structureorientante_id'] ).'_'.$this->data['Orientstruct']['referentorientant_id'];
        }
        echo $default2->subform(
            array(
                'Orientstruct.structureorientante_id' => array( 'type' => 'select', 'options' => $structsorientantes/*, 'required' => true*/ ),
                'Orientstruct.referentorientant_id' => array( 'type' => 'select', 'options' => $refsorientants, 'selected' => $selected/*, 'required' => true*/ )
            ),
            array(
                'options' => $options
            )
        );

    ?>
</fieldset>
<?php endif;?>
<fieldset>
    <legend>Ajout d'une orientation</legend>
    <?php echo $form->input( 'Orientstruct.typeorient_id', array( 'label' =>  required( __d( 'structurereferente', 'Structurereferente.lib_type_orient', true ) ), 'type' => 'select', 'options' => $typesorients, 'empty' => true, 'value' => $typeorient_id ) );?>
    <?php
        $selectedstruct = null;
        $selectedref = null;
        if( $this->action == 'edit' ) {
            if( !empty( $this->data['Orientstruct']['structurereferente_id'] ) ) {
                $selectedstruct = preg_replace( '/^[^_]+_/', '', $this->data['Orientstruct']['typeorient_id'] ).'_'.$this->data['Orientstruct']['structurereferente_id'];

                $selectedref = preg_replace( '/^[^_]+_/', '', $this->data['Orientstruct']['structurereferente_id'] ).'_'.$this->data['Orientstruct']['referent_id'];
// debug($this->data);
            }
        }
//         else {
//             if( !Set::check( $this->data, 'Orientstruct.structurereferente_id', '' ) ) {
//                 $this->data = Set::insert( $this->data, 'Orientstruct.structurereferente_id', '' );
//             }
//             if( !Set::check( $this->data, 'Orientstruct.referent_id', '' ) ) {
//                 $this->data = Set::insert( $this->data, 'Orientstruct.referent_id', '' );
//             }
//         }

        /// Rustine sinon 13_10_5_4
//         $this->data['Orientstruct']['structurereferente_id'] = preg_replace( '/^.*(?<![0-9])([0-9]+_[0-9]+)$/', '\1', $this->data['Orientstruct']['structurereferente_id'] );
//         $this->data['Orientstruct']['referent_id'] = preg_replace( '/^.*(?<![0-9])([0-9]+_[0-9]+)$/', '\1', $this->data['Orientstruct']['referent_id'] );


        if( isset( $this->data['Calculdroitrsa']['id'] ) ) {
            echo $form->input( 'Calculdroitrsa.id', array(  'label' =>  false, 'type' => 'hidden' ) );
        }
        echo $form->input( 'Orientstruct.statut_orient', array(  'label' =>  false, 'type' => 'hidden', 'value' => 'Orienté' ) );

    ?>
    <?php
        echo $form->input( 'Orientstruct.structurereferente_id', array( 'label' => required(__d( 'structurereferente', 'Structurereferente.lib_struc', true  )), 'type' => 'select', 'options' => $structs, 'empty' => true, 'selected' => $selectedstruct ) );

        echo $form->input( 'Orientstruct.referent_id', array(  'label' => __d( 'structurereferente', 'Structurereferente.nom_referent', true  ), 'type' => 'select', 'options' => $referents, 'empty' => true, 'selected' => $selectedref ) );
    ?>
    <?php echo $form->input( 'Calculdroitrsa.toppersdrodevorsa', array(  'label' =>  required( __d( 'calculdroitrsa', 'Calculdroitrsa.toppersdrodevorsa', true ) ), 'options' => $toppersdrodevorsa, 'type' => 'select', 'empty' => 'Non défini'  ) );?>
    <?php echo $form->input( 'Orientstruct.date_propo', array(  'label' =>  required( __d( 'contratinsertion', 'Contratinsertion.date_propo', true ) ), 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' )+10, 'minYear' => ( date( 'Y' ) - 10 ), 'empty' => true ) );?>
    <?php echo $form->input( 'Orientstruct.date_valid', array(  'label' =>  required( __d( 'contratinsertion', 'Contratinsertion.date_valid', true ) ), 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' )+10, 'minYear' => ( date( 'Y' ) - 10 ), 'empty' => true ) );?>
    <?php
        if( Configure::read( 'nom_form_ci_cg' ) == 'cg58' ) {
            echo $default->subform(
                array(
                    'Orientstruct.etatorient' => array( 'legend' => required( __d( 'orientstruct', 'Orientstruct.etatorient', true )  ), 'type' => 'radio', 'options' => $options['etatorient'] )
                ),
                array(
                    'domain' => $domain,
                    'options' => $options
                )
            );
        }
    ?>
</fieldset>
