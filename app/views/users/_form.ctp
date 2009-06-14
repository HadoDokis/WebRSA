<fieldset>
    <?php echo $form->input( 'User.nom', array( 'label' =>  required( __( 'nom', true ) ), 'type' => 'text' ) );?>
    <?php echo $form->input( 'User.prenom', array( 'label' =>  required( __( 'prenom', true ) ), 'type' => 'text' ) );?>
    <?php echo $form->input( 'User.username', array( 'label' =>  required( __( 'username', true ) ), 'type' => 'text' ) );?>
    <?php echo $form->input( 'User.passwd', array( 'label' =>  required( __( 'password', true ) ), 'type' => 'password', 'value' => '' ) );?>
    <?php echo $form->input( 'User.date_naissance', array( 'label' =>  __( 'date_naissance', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y'), 'minYear'=>date('Y') - 80 , 'empty' => true ) ) ;?>
    <?php echo $form->input( 'User.date_deb_hab', array( 'label' =>  __( 'date_deb_hab', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y') + 10, 'minYear'=>date('Y') - 10 , 'empty' => true ) );?>
    <?php echo $form->input( 'User.date_fin_hab', array( 'label' =>  __( 'date_fin_hab', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y') + 10, 'minYear'=>date('Y') - 10, 'empty' => true ) ) ;?>
</fieldset>
<fieldset class="col2">
    <legend>Zones géographiques</legend>
    <script type="text/javascript">
        function toutCocher() {
            $$( 'input[name="data[Zonegeographique][Zonegeographique][]"]' ).each( function( checkbox ) {
                $( checkbox ).checked = true;
            });
        }

        function toutDecocher() {
            $$( 'input[name="data[Zonegeographique][Zonegeographique][]"]' ).each( function( checkbox ) {
                $( checkbox ).checked = false;
            });
        }

        document.observe("dom:loaded", function() {
            Event.observe( 'toutCocher', 'click', toutCocher );
            Event.observe( 'toutDecocher', 'click', toutDecocher );
        });
    </script>
    <?php echo $form->button( 'Tout cocher', array( 'id' => 'toutCocher' ) );?>
    <?php echo $form->button( 'Tout décocher', array( 'id' => 'toutDecocher' ) );?>

    <?php echo $form->input( 'Zonegeographique.Zonegeographique', array( 'label' => false, 'multiple' => 'checkbox' , 'options' => $zglist ) );?>
</fieldset>
<fieldset class="col2">
    <legend><?php echo required( 'Groupe d\'utilisateur' );?></legend>
    <?php echo $form->input( 'User.group_id', array( 'label' => false, 'type' => 'select' , 'options' => $gp, 'empty' => true ) );?>
</fieldset>
<fieldset class="col2">
    <legend><?php echo required( 'Service instructeur' );?></legend>
    <?php echo $form->input( 'User.serviceinstructeur_id', array( 'label' => false, 'type' => 'select' , 'options' => $si, 'empty' => true ) );?>
</fieldset>