<fieldset>
    <?php echo $form->input( 'Structurereferente.lib_struc', array( 'label' => required( __( 'lib_struc', true ) ), 'type' => 'text' ) );?>
    <?php echo $form->input( 'Structurereferente.num_voie', array( 'label' => required( __( 'num_voie', true ) ), 'type' => 'text' ) );?>
    <?php echo $form->input( 'Structurereferente.type_voie', array( 'label' => required( __( 'type_voie', true ) ), 'type' => 'select', 'options' => $typevoie, 'empty' => true ) );?>
    <?php echo $form->input( 'Structurereferente.nom_voie', array( 'label' => required(  __( 'nom_voie', true ) ), 'type' => 'text' ) );?> 
    <?php echo $form->input( 'Structurereferente.code_postal', array( 'label' => required( __( 'code_postal', true ) ), 'type' => 'text', 'maxLength' => 5 ) );?> 
    <?php echo $form->input( 'Structurereferente.ville', array( 'label' => required( __( 'ville', true ) ), 'type' => 'text' ) );?> 
    <?php echo $form->input( 'Structurereferente.code_insee', array( 'label' => required( __( 'code_insee', true ) ), 'type' => 'text', 'maxLength' => 5 ) );?> 
</fieldset>
<fieldset class="col2">
    <legend>Zones géographiques</legend>
    <?php echo $form->input( 'Zonegeographique.Zonegeographique', array( 'label' => required( false ), 'multiple' => 'checkbox' , 'options' => $zglist ) );?>
</fieldset>
<fieldset class="col2">
    <legend>Types d'orientations</legend>
    <?php echo $form->input( 'Structurereferente.typeorient_id', array( 'label' => required( false ), 'type' => 'select' , 'options' => $options, 'empty' => true ) );?>
</fieldset>