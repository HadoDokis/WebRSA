<script type="text/javascript">

//         var newdate = date("d/M/Y", mktime(0, 0, 0, $mois + 1, $jour, $annee));
// 
//         document.observe("dom:loaded", function() {
//             date( 'ContratinsertionDdCiDay', 'ContratinsertionDdCiMonth', 'ContratinsertionDdCiYear', ['ContratinsertionDureeEngag'], 'ContratinsertionDfCiDay', 'ContratinsertionDfCiMonth', 'ContratinsertionDfCiYear' )
//         });

</script>

<script type="text/javascript">
    document.observe("dom:loaded", function() {

        observeDisableFieldsOnCheckbox( 'ContratinsertionEmpTrouv1', [ 'ContratinsertionSectActiEmp', 'ContratinsertionEmpOccupe', 'ContratinsertionDureeHebdoEmp', 'ContratinsertionNatContTrav', 'ContratinsertionDureeCdd' ], false );
        observeDisableFieldsOnCheckbox( 'ContratinsertionEmpTrouv0', [ 'ContratinsertionSectActiEmp', 'ContratinsertionEmpOccupe', 'ContratinsertionDureeHebdoEmp', 'ContratinsertionNatContTrav', 'ContratinsertionDureeCdd' ], true );
        observeDisableFieldsOnValue( 'ContratinsertionNatContTrav', [ 'ContratinsertionDureeCdd' ], 'TCT3', false );
        observeDisableFieldsOnCheckbox( 'ContratinsertionActionsPrev2', [ 'ContratinsertionObstaRenc' ],  false );
    });
</script>

<p><i>Le présent contrat d'insertion est établi en application de l'article L262-37 du code de l'action sociale </i></p>
<fieldset>
    <legend>Contrats d'insertion</legend>
        <?php if( $this->data['Contratinsertion']['typocontrat_id'] == 1 ):?>
            <?php echo $form->input( 'Contratinsertion.typocontrat_id', array( 'label' => false, 'type' => 'hidden' ,  'id' => 'freu') );?>
        <?php endif;?>
        <?php echo $form->input( 'Contratinsertion.typocontrat_id', array( 'label' => __( 'lib_typo', true ), 'type' => 'select' , 'options' => $tc ) );?>
        <?php echo $form->input( 'Contratinsertion.structurereferente_id', array( 'label' => __( 'Structure référente', true ), 'type' => 'select' , 'options' => $sr, 'empty' => true ) );?>
        <?php echo $form->input( 'Contratinsertion.dd_ci', array( 'label' => required( __( 'dd_ci', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true)  );?>
        <?php echo $form->input( 'Contratinsertion.duree_engag', array( 'label' => __( 'duree_engag', true ), 'typ' => 'text', 'size' => 3 )  ); ?>
        <!-- <?php echo $form->input( 'Contratinsertion.df_ci', array( 'label' => required( __( 'df_ci', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true)  ) ;?> -->
        <?php echo $form->input( 'Contratinsertion.df_ci', array( 'label' => required( __( 'df_ci', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true/*, 'value' => $new_date*/ ) ) ;?>
</fieldset>
<fieldset>
    <legend> FORMATION ET EXPERIENCE </legend>
        <?php echo $form->input( 'Dspp.id', array( 'label' => false, 'div' => false,  'type' => 'hidden' ) );?>
        <?php echo $form->input( 'Nivetu.Nivetu', array( 'label' => false, 'div' => false,  'multiple' => 'checkbox', 'options' => $nivetus ) );?>
        <?php echo $form->input( 'Contratinsertion.diplomes', array( 'label' => __( 'diplomes', true ), 'type' => 'textarea', 'rows' => 3)  ); ?>
        <?php echo $form->input( 'Contratinsertion.expr_prof', array( 'label' => __( 'expr_prof', true ), 'type' => 'textarea', 'rows' => 3)  ); ?>
        <?php echo $form->input( 'Contratinsertion.form_compl', array( 'label' => __( 'form_compl', true ), 'type' => 'textarea', 'rows' => 3)  ); ?>
    <legend> PARCOURS D'INSERTION ANTERIEUR </legend>
        <?php
	    echo $widget->booleanRadio( 'Contratinsertion.actions_prev', array( 'legend' => __( 'actions_prev', true )) );
	?>
        <?php echo $form->input( 'Contratinsertion.obsta_renc', array( 'label' => __( 'obsta_renc', true ), 'type' => 'textarea', 'rows' => 3)  ); ?>
</fieldset>
        <?php echo $form->input( 'Contratinsertion.serviceinstructeur_id', array( 'label' => __( 'Nom du service d\'accompagnement', true ), 'type' => 'select' , 'options' => $typeservice, 'empty' => true ) );?>
        <?php echo $form->input( 'Contratinsertion.service_soutien', array( 'label' => __( 'service_soutien', true ), 'type' => 'textarea', 'rows' => 3 )  ); ?> 
        <?php echo $form->input( 'Contratinsertion.pers_charg_suivi', array( 'label' => __( 'pers_charg_suivi', true ), 'type' => 'textarea', 'rows' => 1 )  ); ?>
<fieldset>
    <legend> PROJET ET ACTIONS D'INSERTION </legend>
        <?php echo $form->input( 'Contratinsertion.objectifs_fixes', array( 'label' => __( 'objectifs_fixes', true ), 'type' => 'textarea', 'rows' => 3)  ); ?>
        <?php echo $form->input( 'Contratinsertion.engag_object', array( 'label' => __( 'engag_object', true ), 'type' => 'textarea', 'rows' => 4)  ); ?>
       <!-- <?php echo $form->input( 'Contratinsertion.emp_trouv', array( 'label' => __( 'emp_trouv', true ), 'type' => 'checkbox', 'empty' => true) );?> -->
        <?php
	    echo $widget->booleanRadio( 'Contratinsertion.emp_trouv', array( 'legend' => __( 'emp_trouv', true )) );
        ?>
        <br />Si oui, veuilez préciser :
        <?php echo $form->input( 'Contratinsertion.sect_acti_emp', array( 'label' => __( 'sect_acti_emp', true ), 'type' => 'select', 'options' => $sect_acti_emp, 'empty' => true )  ); ?>
        <?php echo $form->input( 'Contratinsertion.emp_occupe', array( 'label' => __( 'emp_occupe', true ), 'type' => 'select', 'options' => $emp_occupe, 'empty' => true )  ); ?>
        <?php echo $form->input( 'Contratinsertion.duree_hebdo_emp', array( 'label' => __( 'duree_hebdo_emp', true ), 'type' => 'select', 'options' => $duree_hebdo_emp, 'empty' => true )  ); ?>
        <?php echo $form->input( 'Contratinsertion.nat_cont_trav', array( 'label' => __( 'nat_cont_trav', true ), 'type' => 'select', 'options' => $nat_cont_trav, 'empty' => true )  ); ?>
        <?php echo $form->input( 'Contratinsertion.duree_cdd', array( 'label' => __( 'duree_cdd', true ), 'type' => 'select', 'options' => $duree_cdd, 'empty' => true )  ); ?>
</fieldset>
<fieldset>
        <?php echo $form->input( 'Contratinsertion.nature_projet', array( 'label' => __( 'nature_projet', true ), 'type' => 'textarea', 'rows' => 6)  ); ?>
        <?php echo $form->input( 'Contratinsertion.lieu_saisi_ci', array( 'label' => __( 'lieu_saisi_ci', true ), 'type' => 'text')  ); ?><br />
        <?php echo $form->input( 'Contratinsertion.date_saisi_ci', array( 'label' => __( 'date_saisi_ci', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true)  ); ?>
        <?php echo $form->input( 'Le bénéficiaire : ', array( 'label' => 'Le bénéficiaire : ', 'type' => 'text', 'value' => $personne['Personne']['nom'].' '.$personne['Personne']['prenom'] )  ); ?>
</fieldset>

