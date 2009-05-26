<fieldset>
    <?php echo $form->input( 'Structurereferente.lib_struc', array( 'label' =>  __( 'lib_struc', true ), 'type' => 'text' ) );?>
    <?php echo $form->input( 'Structurereferente.num_voie', array( 'label' =>  __( 'num_voie', true ), 'type' => 'text' ) );?>
    <?php echo $form->input( 'Structurereferente.type_voie', array( 'label' =>  __( 'type_voie', true ), 'type' => 'text' ) );?>
    <?php echo $form->input( 'Structurereferente.nom_voie', array( 'label' =>  __( 'nom_voie', true ), 'type' => 'text' ) );?> 
    <?php echo $form->input( 'Structurereferente.code_postal', array( 'label' =>  __( 'code_postal', true ), 'type' => 'text' ) );?> 
    <?php echo $form->input( 'Structurereferente.ville', array( 'label' =>  __( 'ville', true ), 'type' => 'text' ) );?> 
    <?php echo $form->input( 'Structurereferente.code_insee', array( 'label' =>  __( 'code_insee', true ), 'type' => 'text' ) );?> 
</fieldset>
<fieldset class="col2">
    <legend>Zones géographiques</legend>
    <?php echo $form->input( 'Structurereferente.zonegeographique_id', array( 'label' => false, 'div' => false, 'multiple' => 'checkbox' , 'options' => $zg ) );?>
</fieldset>

<fieldset class="col2">
    <legend>Types d'orientations</legend>
    <?php echo $form->input( 'Structurereferente.typeorient_id', array( 'label' => false, 'type' => 'select' , 'options' => $type, 'empty' => true ) );?>
</fieldset>