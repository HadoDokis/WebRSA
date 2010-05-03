<fieldset>
    <?php echo $form->input( 'User.nom', array( 'label' =>  required( __( 'nom', true ) ), 'type' => 'text' ) );?>
    <?php echo $form->input( 'User.prenom', array( 'label' =>  required( __( 'prenom', true ) ), 'type' => 'text' ) );?>
    <?php echo $form->input( 'User.username', array( 'label' =>  required( __( 'username', true ) ), 'type' => 'text' ) );?>
    <?php echo $form->input( 'User.passwd', array( 'label' =>  required( __( 'password', true ) ), 'type' => 'password', 'value' => '' ) );?>
    <?php
        echo $form->input( 'User.numtel', array( 'label' =>  required( __( 'numtel', true ) ), 'type' => 'text', 'maxlength' => 15 ) );

        if( Configure::read( 'User.adresse' ) ) {
            echo $form->input( 'User.numvoie', array( 'label' =>  __( 'numvoie', true ), 'type' => 'text' ) );
            echo $form->input( 'User.typevoie', array( 'label' =>  __( 'typevoie', true ), 'type' => 'select', 'options' => $typevoie, 'empty' => true  ) );
            echo $form->input( 'User.nomvoie', array( 'label' =>  __( 'nomvoie', true ), 'type' => 'text' ) );
            echo $form->input( 'User.compladr', array( 'label' =>  __( 'compladr', true ), 'type' => 'text' ) );
            echo $form->input( 'User.codepos', array( 'label' =>  __( 'codepos', true ), 'type' => 'text', 'maxlength' => 5 ) );
            echo $form->input( 'User.ville', array( 'label' =>  __( 'ville', true ), 'type' => 'text' ) );
        }

    ?>
    <?php echo $form->input( 'User.date_naissance', array( 'label' =>  __( 'date_naissance', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y'), 'minYear'=>date('Y') - 80 , 'empty' => true ) ) ;?>
    <?php echo $form->input( 'User.date_deb_hab', array( 'label' => required(  __( 'date_deb_hab', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y') + 10, 'minYear'=>date('Y') - 10 , 'empty' => true ) );?>
    <?php echo $form->input( 'User.date_fin_hab', array( 'label' => required(  __( 'date_fin_hab', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y') + 10, 'minYear'=>date('Y') - 10, 'empty' => true ) ) ;?>
</fieldset>
<div><?php echo $form->input( 'User.filtre_zone_geo', array( 'label' => 'Restreindre les zones géographiques', 'type' => 'checkbox' ) );?></div>
<fieldset class="col2" id="filtres_zone_geo">
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
            observeDisableFieldsetOnCheckbox( 'UserFiltreZoneGeo', 'filtres_zone_geo', false );
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