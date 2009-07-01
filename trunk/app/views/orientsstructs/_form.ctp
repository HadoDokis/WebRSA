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
                $this->data['Orientstruct']['structurereferente_id'] = $this->data['Orientstruct']['typeorient_id'].'_'.$this->data['Orientstruct']['structurereferente_id'];
            }
        }
        else {
            $this->data = Set::insert( $this->data, 'Orientstruct.structurereferente_id', '' );
        }

        if( isset( $this->data['Prestation']['id'] ) ) {
            echo $form->input( 'Prestation.id', array(  'label' =>  false, 'type' => 'hidden' ) );
        }

    ?>
    <?php echo $form->input( 'Orientstruct.structurereferente_id', array( 'label' => required(__( 'lib_struc', true  )), 'type' => 'select', 'options' => $options2, 'empty' => true, 'selected' => $this->data['Orientstruct']['structurereferente_id'] ) );?>
    <?php echo $form->input( 'Prestation.toppersdrodevorsa', array(  'label' =>  required( __( 'toppersdrodevorsa', true ) ), 'options' => $toppersdrodevorsa, 'type' => 'select', 'empty' => 'Non défini'  ) );?>
    <?php echo $form->input( 'Orientstruct.date_propo', array(  'label' =>  required( __( 'date_propo', true ) ), 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' )+10, 'minYear' => ( date( 'Y' ) - 10 ), 'empty' => true ) );?>
    <?php echo $form->input( 'Orientstruct.date_valid', array(  'label' =>  required( __( 'date_valid', true ) ), 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' )+10, 'minYear' => ( date( 'Y' ) - 10 ), 'empty' => true ) );?>
</fieldset>
