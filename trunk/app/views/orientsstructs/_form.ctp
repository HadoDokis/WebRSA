
<fieldset>
    <legend>Ajout d'une orientation</legend>
    <?php echo $form->input( 'Typeorient.lib_type_orient', array( 'label' =>  __( 'lib_type_orient', true  ), 'type' => 'select', 'options' => $options, 'empty' => true ) );?>
    <?php echo $form->input( 'Structurereferente.lib_struc', array( 'label' => __( 'lib_struc', true  ), 'type' => 'select', 'options' => $options2, 'empty' => true ) );?>
    <?php echo $form->input( 'Personne.toppersdrodevorsa', array(  'label' =>   __( 'toppersdrodevorsa', true ), 'options' => $toppersdrodevorsa, 'type' => 'select', 'empty' => 'Non défini'  ) );?> 
</fieldset>
