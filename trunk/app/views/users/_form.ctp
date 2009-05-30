<fieldset>
    <?php echo $form->input( 'User.nom', array( 'label' =>  __( 'nom', true ), 'type' => 'text' ) );?>
    <?php echo $form->input( 'User.prenom', array( 'label' =>  __( 'prenom', true ), 'type' => 'text' ) );?>
    <?php echo $form->input( 'User.username', array( 'label' =>  __( 'username', true ), 'type' => 'text' ) );?>
    <?php echo $form->input( 'User.password', array( 'label' =>  __( 'password', true ), 'type' => 'text', 'value' => '' ) );?>
    <?php echo $form->input( 'User.date_naissance', array( 'label' =>  __( 'date_naissance', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y'), 'minYear'=>date('Y') - 80 , 'empty' => true ) ) ;?>
    <?php echo $form->input( 'User.date_deb_hab', array( 'label' =>  __( 'date_deb_hab', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y') + 10, 'minYear'=>date('Y') - 10 , 'empty' => true ) );?>
    <?php echo $form->input( 'User.date_fin_hab', array( 'label' =>  __( 'date_fin_hab', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y') + 10, 'minYear'=>date('Y') - 10, 'empty' => true ) ) ;?>
</fieldset>
<fieldset class="col2">
    <legend>Zones géographiques</legend>
    <?php echo $form->input( 'Zonegeographique.Zonegeographique', array( 'label' => false, 'multiple' => 'checkbox' , 'options' => $zglist ) );?>
</fieldset>
<fieldset class="col2">
    <legend>Groupe d'utilisateur</legend>
    <?php echo $form->input( 'User.group_id', array( 'label' => false, 'type' => 'select' , 'options' => $gp ) );?>
</fieldset>
<fieldset class="col2">
    <legend>Service instructeur</legend>
    <?php echo $form->input( 'User.serviceinstructeur_id', array( 'label' => false, 'type' => 'select' , 'options' => $si ) );?>
</fieldset>