<?php
    $typeorient_id = null;
    if( !empty( $this->data['Structurereferente']['Typeorient']['id'] ) ) {
        $typeorient_id = $this->data['Structurereferente']['Typeorient']['id'];
    }
?>

<fieldset>
    <legend>Ajout d'une orientation</legend>
    <?php echo $form->input( 'Typeorient.id', array( 'label' =>  required( __( 'lib_type_orient', true ) ), 'type' => 'select', 'options' => $options, 'empty' => true, 'value' => $typeorient_id ) );?>
    <?php echo $form->input( 'Orientstruct.structurereferente_id', array( 'label' => required(__( 'lib_struc', true  )), 'type' => 'select', 'options' => $options2, 'empty' => true ) );?>
    <?php echo $form->input( 'Personne.toppersdrodevorsa', array(  'label' =>  required( __( 'toppersdrodevorsa', true ) ), 'options' => $toppersdrodevorsa, 'type' => 'select', 'empty' => 'Non défini'  ) );?>
</fieldset>
