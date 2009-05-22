<fieldset>
    <?php echo $form->input( 'User.nom', array( 'label' => 'nom', 'type' => 'text' ) );?>
    <?php echo $form->input( 'User.prenom', array( 'label' => 'prenom', 'type' => 'text' ) );?>
    <?php echo $form->input( 'User.username', array( 'label' => 'username', 'type' => 'text' ) );?>
    <?php echo $form->input( 'User.password', array( 'label' => 'password', 'type' => 'text' ) );?>
    <?php echo $form->input( 'User.date_naissance', array( 'label' => 'date_naissance', 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y'), 'minYear'=>date('Y') - 100 , 'empty' => true ) ) ;?>
    <?php echo $form->input( 'User.date_deb_hab', array( 'label' => 'date_deb_hab', 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y'), 'minYear'=>date('Y') , 'empty' => true ) );?>
    <?php echo $form->input( 'User.date_fin_hab', array( 'label' => 'date_fin_hab', 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y'), 'minYear'=>date('Y') , 'empty' => true ) ) ;?>
</fieldset>
<fieldset class="col2">
    <legend>Zones géographiques</legend>
    <?php echo $form->input( 'Zonegeographique.Zonegeographique', array( 'label' => false, 'multiple' => 'checkbox' , 'options' => $zglist ) );?>
</fieldset>