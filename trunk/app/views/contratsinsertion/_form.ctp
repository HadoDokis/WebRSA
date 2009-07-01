<script type="text/javascript">

//         var newdate = date("d/M/Y", mktime(0, 0, 0, $mois + 1, $jour, $annee));
//
//         document.observe("dom:loaded", function() {
//             date( 'ContratinsertionDdCiDay', 'ContratinsertionDdCiMonth', 'ContratinsertionDdCiYear', ['ContratinsertionDureeEngag'], 'ContratinsertionDfCiDay', 'ContratinsertionDfCiMonth', 'ContratinsertionDfCiYear' )
//         });

</script>

<script type="text/javascript">
    document.observe( "dom:loaded", function() {
        observeDisableFieldsOnBoolean( 'ContratinsertionActionsPrev', [ 'ContratinsertionObstaRenc'/*, 'PrestformLibPresta', 'RefprestaNomrefpresta', 'PrestformDatePrestaDay', 'PrestformDatePrestaMonth', 'PrestformDatePrestaYear' */], '1', false );
        observeDisableFieldsOnBoolean( 'ContratinsertionEmpTrouv', [ 'ContratinsertionSectActiEmp', 'ContratinsertionEmpOccupe', 'ContratinsertionDureeHebdoEmp', 'ContratinsertionNatContTrav', 'ContratinsertionDureeCdd' ], '0', false );
        observeDisableFieldsOnValue( 'ContratinsertionNatContTrav', [ 'ContratinsertionDureeCdd' ], 'TCT3', false );

        observeDisableFieldsOnValue( 'Actioninsertion0LibAction', [ 'Aidedirecte0TypoAide', 'Aidedirecte0LibAide', 'Aidedirecte0DateAideDay', 'Aidedirecte0DateAideMonth', 'Aidedirecte0DateAideYear' ], 'A', false );
        observeDisableFieldsOnValue( 'Actioninsertion0LibAction', [ 'PrestformLibPresta', 'RefprestaNomrefpresta', 'RefprestaPrenomrefpresta', 'PrestformDatePrestaDay', 'PrestformDatePrestaMonth', 'PrestformDatePrestaYear' ], 'P', false );
    } );
</script>

<p><i>Le présent contrat d'insertion est établi en application de l'article L262-37 du code de l'action sociale </i>, entre le Président du Conseil Général </p>
<p>et d'autre part</p>

<fieldset>
    <table>
        <tbody>
            <tr>
                <th>Nom / Prénom</th>
                <td> <?php echo  $qual.' '.$nom.' '.$prenom ;?> </td>
            </tr>
            <tr>
                <th>Situation de famille</th>
                <td> <?php echo ( isset( $sitfam ) ? $legend_sitfam[$sitfam] : null );?> </td>
            </tr>
            <tr>
                <th>Date de naissance</th>
                <td> <?php echo  date_short( $dtnai );?> </td>
            </tr>
            <tr>
                <th>Couverture sociale</th>
                <td> <?php echo ( !empty( $couvsoc ) ? $legend_couvsoc[$couvsoc] : null );?> </td>
            </tr>
            <tr>
                <th>Conditions de logement</th>
                <td> <?php echo ( isset( $typeocclog ) ? $legend_typeocclog[$typeocclog] : null );?> </td>
            </tr>
            <tr>
                <th>Origine de la demande</th>
                <td> <?php echo ( isset( $oridemrsa ) ? $legend_oridemrsa[$oridemrsa] : null );?> </td>
            </tr>
            <tr>
                <th>Depuis le</th>
                <td> <?php echo  date_short( $dtdemrsa ) ;?> </td>
            </tr>
            <tr>
                <th>N° allocataire</th>
                <td> <?php echo $matricule ;?> </td>
            </tr>
            <!--<tr>
                <th>N° service instructeur</th>
                <td> <?php echo isset( $numagrins ) ? $numagrins : null ;?> </td>
            </tr>-->
        </tbody>
    </table>
</fieldset>


<fieldset>
    <legend>Contrats d'insertion</legend>
        <?php if( $this->data['Contratinsertion']['typocontrat_id'] == 1 ):?>
            <?php echo $form->input( 'Contratinsertion.typocontrat_id', array( 'label' => false, 'type' => 'hidden' ,  'id' => 'freu') );?>
        <?php endif;?>
        <?php echo $form->input( 'Contratinsertion.typocontrat_id', array( 'label' => required( __( 'lib_typo', true ) ), 'type' => 'select' , 'options' => $tc ) );?>
        <?php echo $form->input( 'Contratinsertion.structurereferente_id', array( 'label' => required( __( 'Structure référente', true ) ), 'type' => 'select' , 'options' => $sr, 'empty' => true ) );?>
        <?php echo $form->input( 'Contratinsertion.dd_ci', array( 'label' => required( __( 'dd_ci', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true)  );?>
        <?php echo $form->input( 'Contratinsertion.duree_engag', array( 'label' => required( __( 'duree_engag', true ) ), 'type' => 'select', 'options' => $duree_engag, 'empty' => true )  ); ?>
        <!-- <?php echo $form->input( 'Contratinsertion.df_ci', array( 'label' => required( __( 'df_ci', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true)  ) ;?> -->
        <?php echo $form->input( 'Contratinsertion.df_ci', array( 'label' => required( __( 'df_ci', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true/*, 'value' => $new_date*/ ) ) ;?>
</fieldset>
<fieldset >
    <legend> FORMATION ET EXPERIENCE </legend>
        <?php echo $form->input( 'Dspp.id', array( 'label' => false, 'div' => false,  'type' => 'hidden' ) );?>
        <?php echo $form->input( 'Nivetu.Nivetu', array( 'label' => false, 'div' => false,  'multiple' => 'checkbox', 'options' => $nivetus ) );?>
        <?php echo $form->input( 'Contratinsertion.diplomes', array( 'label' => required( __( 'diplomes', true ) ), 'type' => 'textarea', 'rows' => 3)  ); ?>
        <?php echo $form->input( 'Contratinsertion.expr_prof', array( 'label' => required( __( 'expr_prof', true ) ), 'type' => 'textarea', 'rows' => 3)  ); ?>
        <?php echo $form->input( 'Contratinsertion.form_compl', array( 'label' =>  __( 'form_compl', true ), 'type' => 'textarea', 'rows' => 3)  ); ?>
</fieldset>
<fieldset>
    <legend> PARCOURS D'INSERTION ANTERIEUR </legend>
        <?php
            echo $widget->booleanRadio( 'Contratinsertion.actions_prev', array( 'legend' => required( __( 'actions_prev', true ) ) ) );
        ?>

        <?php echo $form->input( 'Contratinsertion.obsta_renc', array( 'label' => required( __( 'obsta_renc', true ) ), 'type' => 'textarea', 'rows' => 3)  ); ?>

        <?php echo $form->input( 'Actioninsertion.0.lib_action', array( 'label' => required( __( 'lib_action', true ) ), 'type' => 'select', 'options' => $lib_action, 'empty' => true ) ); ?>
        <?php echo $form->input( 'Actioninsertion.0.dd_action', array( 'label' => required( __( 'dd_action', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true) ); ?>
        <?php echo $form->input( 'Actioninsertion.0.df_action', array( 'label' => required( __( 'df_action', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true) ); ?>

        <?php echo $form->input( 'Aidedirecte.0.typo_aide', array( 'label' => required( __( 'typo_aide', true ) ), 'type' => 'select', 'options' => $typo_aide, 'empty' => true )  ); ?>
        <?php echo $form->input( 'Aidedirecte.0.lib_aide', array( 'label' => required( __( 'lib_aide', true ) ), 'type' => 'select', 'options' => $actions, 'empty' => true )  ); ?>
        <?php echo $form->input( 'Aidedirecte.0.date_aide', array( 'label' => required( __( 'date_aide', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true)  ); ?>

        <?php echo $form->input( 'Prestform.lib_presta', array( 'label' => required( __( 'lib_presta', true ) ), 'type' => 'select', 'options' => $actions, 'empty' => true )  ); ?>
        <?php echo $form->input( 'Refpresta.nomrefpresta', array( 'label' => required( __( 'nomrefpresta', true ) ), 'type' => 'text')); ?>
        <?php echo $form->input( 'Refpresta.prenomrefpresta', array( 'label' => required( __( 'prenomrefpresta', true ) ), 'type' => 'text')); ?>
        <?php echo $form->input( 'Prestform.date_presta', array( 'label' => required( __( 'date_presta', true ) ), 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true )  ); ?>
</fieldset>
        <?php echo $form->input( 'Contratinsertion.serviceinstructeur_id', array( 'label' => required( __( 'Nom du service d\'accompagnement', true ) ), 'type' => 'select' , 'options' => $typeservice, 'empty' => true ) );?>
        <?php echo $form->input( 'Contratinsertion.service_soutien', array( 'label' => '<em>'.required( __( 'service_soutien', true ) ).'</em>', 'type' => 'textarea', 'rows' => 3 )  ); ?>
        <?php echo $form->input( 'Contratinsertion.pers_charg_suivi', array( 'label' => '<em>'.required( __( 'pers_charg_suivi', true ) ).'</em>', 'type' => 'textarea', 'rows' => 1 )  ); ?>
<fieldset>
    <legend> PROJET ET ACTIONS D'INSERTION </legend>
        <?php echo $form->input( 'Contratinsertion.objectifs_fixes', array( 'label' => required( __( 'objectifs_fixes', true ) ), 'type' => 'textarea', 'rows' => 3)  ); ?>
        <?php echo $form->input( 'Contratinsertion.engag_object', array( 'label' => required( __( 'engag_object', true ) ), 'type' => 'textarea', 'rows' => 4)  ); ?>
        <?php
            echo $widget->booleanRadio( 'Contratinsertion.emp_trouv', array( 'legend' => required( __( 'emp_trouv', true ) )) );
        ?>
        Si oui, veuilez préciser :
        <?php echo $form->input( 'Contratinsertion.sect_acti_emp', array( 'label' => required( __( 'sect_acti_emp', true ) ), 'type' => 'select', 'options' => $sect_acti_emp, 'empty' => true )  ); ?>
        <?php echo $form->input( 'Contratinsertion.emp_occupe', array( 'label' => required( __( 'emp_occupe', true ) ), 'type' => 'select', 'options' => $emp_occupe, 'empty' => true )  ); ?>
        <?php echo $form->input( 'Contratinsertion.duree_hebdo_emp', array( 'label' => required( __( 'duree_hebdo_emp', true ) ), 'type' => 'select', 'options' => $duree_hebdo_emp, 'empty' => true )  ); ?>
        <?php echo $form->input( 'Contratinsertion.nat_cont_trav', array( 'label' => required( __( 'nat_cont_trav', true ) ), 'type' => 'select', 'options' => $nat_cont_trav, 'empty' => true )  ); ?>
        <?php echo $form->input( 'Contratinsertion.duree_cdd', array( 'label' => required( __( 'duree_cdd', true ) ), 'type' => 'select', 'options' => $duree_cdd, 'empty' => true )  ); ?>
</fieldset>
<fieldset>
        <?php echo $form->input( 'Contratinsertion.nature_projet', array( 'label' => required( __( 'nature_projet', true ) ), 'type' => 'textarea', 'rows' => 6)  ); ?>
        <?php echo $form->input( 'Contratinsertion.lieu_saisi_ci', array( 'label' => required( __( 'lieu_saisi_ci', true ) ), 'type' => 'text')  ); ?><br />
        <?php echo $form->input( 'Contratinsertion.date_saisi_ci', array( 'label' => required( __( 'date_saisi_ci', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 /*, 'empty' => true*/)  ); ?>
        <?php echo $form->input( 'Le bénéficiaire : ', array( 'label' => 'Le bénéficiaire : ', 'type' => 'text', 'value' => $personne['Personne']['nom'].' '.$personne['Personne']['prenom'] )  ); ?>
</fieldset>