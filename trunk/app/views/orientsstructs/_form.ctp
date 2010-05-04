<?php
    $typeorient_id = null;
    if( !empty( $this->data['Structurereferente']['Typeorient']['id'] ) ) {
        $typeorient_id = $this->data['Structurereferente']['Typeorient']['id'];
    }
?>

<fieldset>
    <legend>Ajout d'une orientation</legend>
    <?php echo $form->input( 'Orientstruct.typeorient_id', array( 'label' =>  required( __( 'lib_type_orient', true ) ), 'type' => 'select', 'options' => $options, 'empty' => true, 'value' => $typeorient_id ) );?>
    <?php
        if( $this->action == 'edit' ) {
            if( !empty( $this->data['Orientstruct']['structurereferente_id'] ) ) {
                $this->data['Orientstruct']['structurereferente_id'] = preg_replace( '/^[^_]+_/', '', $this->data['Orientstruct']['typeorient_id'] ).'_'.$this->data['Orientstruct']['structurereferente_id'];

                $this->data['Orientstruct']['referent_id'] = preg_replace( '/^[^_]+_/', '', $this->data['Orientstruct']['structurereferente_id'] ).'_'.$this->data['Orientstruct']['referent_id'];

            }
        }
        else {
            $this->data = Set::insert( $this->data, 'Orientstruct.structurereferente_id', '' );
            $this->data = Set::insert( $this->data, 'Orientstruct.referent_id', '' );
        }

        if( isset( $this->data['Calculdroitrsa']['id'] ) ) {
            echo $form->input( 'Calculdroitrsa.id', array(  'label' =>  false, 'type' => 'hidden' ) );
        }
        echo $form->input( 'Orientstruct.statut_orient', array(  'label' =>  false, 'type' => 'hidden', 'value' => 'Orienté' ) );

    ?>
    <?php
        echo $form->input( 'Orientstruct.structurereferente_id', array( 'label' => required(__( 'lib_struc', true  )), 'type' => 'select', 'options' => $options2, 'empty' => true, 'selected' => $this->data['Orientstruct']['structurereferente_id'] ) );

        echo $form->input( 'Orientstruct.referent_id', array(  'label' => __( 'nom_referent', true  ), 'type' => 'select', 'options' => $referents, 'empty' => true, 'selected' => $this->data['Orientstruct']['referent_id'] ) );
    ?>
    <?php echo $form->input( 'Calculdroitrsa.toppersdrodevorsa', array(  'label' =>  required( __( 'toppersdrodevorsa', true ) ), 'options' => $toppersdrodevorsa, 'type' => 'select', 'empty' => 'Non défini'  ) );?>
    <?php echo $form->input( 'Orientstruct.date_propo', array(  'label' =>  required( __( 'date_propo', true ) ), 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' )+10, 'minYear' => ( date( 'Y' ) - 10 ), 'empty' => true ) );?>
    <?php echo $form->input( 'Orientstruct.date_valid', array(  'label' =>  required( __( 'date_valid', true ) ), 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' )+10, 'minYear' => ( date( 'Y' ) - 10 ), 'empty' => true ) );?>
</fieldset>
