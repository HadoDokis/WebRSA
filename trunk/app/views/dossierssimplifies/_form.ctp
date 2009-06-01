<fieldset>
    <h2>Dossier RSA</h2>
    <?php echo $form->label("Numéro de demande RSA : $numdossierrsa<br /><br />" );    ?>
    <?php echo $form->label("Date de demande du dossier : $datdemdossrsa<br /><br />");?>
</fieldset>
<fieldset>
    <h2>Demandeur</h2>
    <div><?php echo $form->input( 'Personne.rolepers', array( 'label' => required( __( 'rolepers', true ) ), 'value' => 'DEM', 'type' => 'hidden') );?></div>
    <div><?php echo $form->input( 'Personne.id', array( 'label' => required( __( 'id', true ) ), 'value' =>$id , 'type' => 'hidden') );?></div>
    <?php echo $form->input( 'Personne.qual', array( 'label' => required( __( 'qual', true ) ), 'type' => 'select', 'options' => $qual, 'empty' => true ) );?>
    <?php echo $form->input( 'Personne.nom', array( 'label' => required( __( 'nom', true ) ) ) );?>
    <?php echo $form->input( 'Personne.prenom', array( 'label' => required( __( 'prenom', true ) ) ) );?>
    <?php echo $form->input( 'Personne.nir', array( 'label' => required( __( 'nir', true ) ) ) );?>
    <?php echo $form->input( 'Personne.dtnai', array( 'label' => required( __( 'dtnai', true ) ), 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => ( date( 'Y' ) - 100 ), 'empty' => true ) );?>
    <?php echo $form->input( 'Personne.toppersdrodevorsa', array(  'label' =>  required( __( 'toppersdrodevorsa', true ) ), 'options' => $toppersdrodevorsa, 'type' => 'select', 'empty' => 'Non défini'  ) );?>
</fieldset>
<fieldset>
    <h3>Orientation</h3>
    <?php echo $form->input( 'Orientstruct.typeorient_id', array( 'label' => "Type d'orientation / Type de structure",'type' => 'select', 'selected'=> $orient_id, 'options' => $typesOrient, 'empty'=>true));?>
    <?php echo $form->input( 'Orientstruct.structurereferente_id', array( 'label' => __( 'structure_referente', true ), 'type' => 'select', 'selected' => $structure_id, 'options' => $structures, 'empty' => true ) );?>
</fieldset>
