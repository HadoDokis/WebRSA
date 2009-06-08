<script type="text/javascript">
    function ecart_mois(date_max, date_min)
    {
        var explode_date_min;
        var explode_date_max;
        var mois_min;
        var annee_min;
        var mois_max;
        var annee_max;
        var ecart;

        explode_date_min = date_min.split('/');
        explode_date_max = date_max.split('/');

        mois_min = parseInt(explode_date_min[0]);
        annee_min = parseInt(explode_date_min[1]);

        mois_max = parseInt(explode_date_max[0]);
        annee_max = parseInt(explode_date_max[1]);

        ecart = ((annee_max - annee_min)*12) - (mois_min) + (mois_max);

        return ecart;


        document.observe("dom:loaded", function() {
            Event.observe( 'ecart', 'click', ecart() );
        });
    }
</script>


<p><i>Le présent contrat d'insertion est établi en application de l'article L262-37 du code de l'action sociale </i></p>
<fieldset>
    <legend>Contrats d'insertion</legend>
        <?php echo $form->input( 'Contratinsertion.serviceinstructeur_id', array( 'label' => __( 'lib_service', true ), 'type' => 'select' , 'options' => $typeservice, 'empty' => true ) );?>
        <?php echo $form->input( 'Contratinsertion.typocontrat_id', array( 'label' => __( 'lib_typo', true ), 'type' => 'select' , 'options' => $tc, 'empty' => true ) );?>
        <?php echo $form->input( 'Structurereferente.id', array( 'label' => __( 'Structure référente', true ), 'type' => 'select' , 'options' => $sr, 'empty' => true ) );?>
        <?php echo $form->input( 'Contratinsertion.dd_ci', array( 'label' => required( __( 'dd_ci', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true)  );?>
        <?php echo $form->input( 'Contratinsertion.df_ci', array( 'label' => required( __( 'df_ci', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true)  ) ;?>
</fieldset>
<fieldset>
    <legend> FORMATION ET EXPERIENCE </legend>
        <?php echo $form->input( 'Nivetu.Nivetu', array( 'label' => false, 'div' => false,  'multiple' => 'checkbox', 'options' => $nivetus ) );?>
        <?php echo $form->input( 'Contratinsertion.diplomes', array( 'label' => __( 'diplomes', true ), 'type' => 'textarea', 'rows' => 3)  ); ?>
        <?php echo $form->input( 'Contratinsertion.expr_prof', array( 'label' => __( 'expr_prof', true ), 'type' => 'textarea', 'rows' => 3)  ); ?>
        <?php echo $form->input( 'Contratinsertion.form_compl', array( 'label' => __( 'form_compl', true ), 'type' => 'textarea', 'rows' => 3)  ); ?>
    <legend> PARCOURS D'INSERTION ANTERIEUR </legend>
        <?php echo $form->input( 'Contratinsertion.actions_prev', array( 'label' => __( 'actions_prev', true ), 'type' => 'textarea', 'rows' => 3)  ); ?>
       <!-- <?php echo $form->input( 'Contratinsertion.actions_prev', array( 'label' => __( 'actions_prev', true ), 'multiple' => 'checkbox')  ); ?> -->
        <?php echo $form->input( 'Contratinsertion.obsta_renc', array( 'label' => __( 'obsta_renc', true ), 'type' => 'textarea', 'rows' => 3)  ); ?>
</fieldset>
        <?php echo $form->input( 'Contratinsertion.service_soutien', array( 'label' => __( 'service_soutien', true ), 'type' => 'textarea', 'rows' => 3)  ); ?> 
        <?php echo $form->input( 'Contratinsertion.pers_charg_suivi', array( 'label' => __( 'pers_charg_suivi', true ), 'type' => 'textarea', 'rows' => 1 )  ); ?>
<fieldset>
    <legend> PROJET ET ACTIONS D'INSERTION </legend>
        <?php echo $form->input( 'Contratinsertion.objectifs_fixes', array( 'label' => __( 'objectifs_fixes', true ), 'type' => 'textarea', 'rows' => 3)  ); ?>
        <?php echo $form->input( 'Contratinsertion.engag_object', array( 'label' => __( 'engag_object', true ), 'type' => 'textarea', 'rows' => 4)  ); ?>
        Si vous avez trouvé un emploi, veuilez préciser : 
        <?php echo $form->input( 'Contratinsertion.sect_acti_emp', array( 'label' => __( 'sect_acti_emp', true ), 'type' => 'select', 'options' => $sect_acti_emp, 'empty' => true )  ); ?>
        <?php echo $form->input( 'Contratinsertion.emp_occupe', array( 'label' => __( 'emp_occupe', true ), 'type' => 'select', 'options' => $emp_occupe, 'empty' => true )  ); ?>
        <?php echo $form->input( 'Contratinsertion.duree_hebdo_emp', array( 'label' => __( 'duree_hebdo_emp', true ), 'type' => 'select', 'options' => $duree_hebdo_emp, 'empty' => true )  ); ?>
        <?php echo $form->input( 'Contratinsertion.nat_cont_trav', array( 'label' => __( 'nat_cont_trav', true ), 'type' => 'select', 'options' => $nat_cont_trav, 'empty' => true )  ); ?>
        <?php echo $form->input( 'Contratinsertion.duree_cdd', array( 'label' => __( 'duree_cdd', true ), 'type' => 'select', 'options' => $duree_cdd, 'empty' => true )  ); ?>
</fieldset>
<fieldset>
        <?php echo $form->input( 'Contratinsertion.duree_engag', array( 'label' => __( 'duree_engag', true ), 'size' => 3 )  ); ?>
        <?php echo $form->input( 'Contratinsertion.nature_projet', array( 'label' => __( 'nature_projet', true ), 'type' => 'textarea', 'rows' => 6)  ); ?>
        <?php echo $form->input( 'Fait à :', array( 'label' => 'Fait à : ', 'type' => 'text')  ); ?><br />
        <?php echo $form->input( 'Le ', array( 'label' => 'Le : ', 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true)  ); ?>
        <?php echo $form->input( 'Le bénéficiaire : ', array( 'label' => 'Le bénéficiaire : ', 'type' => 'text', 'value' => $personne['Personne']['nom'].' '.$personne['Personne']['prenom'] )  ); ?>
</fieldset>
<fieldset>
    <legend> PARTIE RESERVEE AU DEPARTEMENT</legend>
        <?php echo $form->input( 'Contratinsertion.observ_ci', array( 'label' => __( 'observ_ci', true ), 'type' => 'textarea', 'rows' => 6)  ); ?>
        <?php echo $form->input( 'Contratinsertion.decision_ci', array( 'label' => __( 'decision_ci', true ), 'type' => 'select', 'options' => $decision_ci, 'empty' => true ) ); ?>
        <?php echo $form->input( 'Contratinsertion.datevalidation_ci', array( 'label' => '', 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true)  ); ?><br />
        <?php echo $form->input( 'Fait à :', array( 'label' => 'Fait à : ', 'type' => 'text')  ); ?><br />
        <?php echo $form->input( 'Le ', array( 'label' => 'Le : ', 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true)  ); ?>
        <?php echo $form->input( 'Le Président du Conseil Général : ', array( 'label' => 'Le Président du Conseil Général : ', 'type' => 'text')  ); ?>
</fieldset>
