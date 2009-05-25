<fieldset>
    <?php echo $form->input( 'Referent.nom', array( 'label' =>  __( 'nom', true ), 'type' => 'text' ) );?>
    <?php echo $form->input( 'Referent.prenom', array( 'label' =>  __( 'prenom', true ), 'type' => 'text' ) );?>
    <?php echo $form->input( 'Referent.numero_poste', array( 'label' =>  __( 'numero_poste', true ), 'type' => 'text' ) );?>
    <?php echo $form->input( 'Referent.email', array( 'label' =>  __( 'email', true ), 'type' => 'text' ) );?> 
</fieldset>
<fieldset class="col2">
    <legend>Structures référentes</legend>
    <?php echo $form->input( 'Referent.structurereferente_id', array( 'label' => false, 'type' => 'select' , 'options' => $sr, 'empty' => true ) );?>
</fieldset>