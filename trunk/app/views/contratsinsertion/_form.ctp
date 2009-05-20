<?php
//     $title = implode(
//         ' ',
//         array(
//             $this->data['Personne']['qual'],
//             $this->data['Personne']['nom'],
//             ( !empty( $this->data['Personne']['nomnaiss'] ) ? '( née '.$this->data['Personne']['nomnaiss'].' )' : null ),
//             $this->data['Personne']['prenom'],
//             $this->data['Personne']['prenom2'],
//             $this->data['Personne']['prenom3']
//         )
//     );
// 
//     $this->pageTitle = 'Visualisation d\'une personne « '.$title.' »';
?>
<p><i>Le présent contrat d'insertion est établi en application de l'article L262-37 du code de l'action sociale </i></p><!-- et des familles entre,</i> 
<br /> <br /> 
D'une part : le Président du Conseil Général  
<br /> <br /> 
 Et d'autre part :   <b></b> 
<br /> <br />  </p>-->

<fieldset>
            <legend>Contrats d'insertion</legend>
                <?php echo $form->input( 'Contratinsertion.id', array( 'type' => 'hidden' ) );?>
                <?php echo $form->input( 'Contratinsertion.personne_id', array( 'type' => 'hidden', 'value' => $personne_id ) );?>
                <?php echo $form->input( 'Contratinsertion.structurereferente_id', array( 'type' => 'hidden', 'value' => $personne_id ) );?>
                <?php echo $form->input( 'Contratinsertion.dd_ci', array( 'label' => required( __( 'dd_ci', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y'), 'minYear'=>date('Y')-80 , 'empty' => true)  );?>
                <?php echo $form->input( 'Contratinsertion.df_ci', array( 'label' => required( __( 'df_ci', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+30, 'minYear'=>date('Y')-30 , 'empty' => true)  ) ;?>
    </fieldset>
<!--    <fieldset>
            <?php echo $form->input( '0', array( 'label' => 'Conditions de logement : ')  ); ?>

    </fieldset>-->
    <fieldset>
            <legend> FORMATION ET EXPERIENCE </legend>

                <!-- <?php echo $form->input( 'Contratinsertion.niv_etude', array( 'label' =>  __( 'niv_etude', true ) , 'type' => 'textarea', 'rows' => 3) );?> -->

                <?php echo $form->input( 'Contratinsertion.niv_etude', array( 'label' => __( 'niv_etude', true ), 'div' => false,  'type' => 'select', 'options' => $nivetu, 'empty' => true ) );?>
                <?php echo $form->input( 'Contratinsertion.diplomes', array( 'label' => __( 'diplomes', true ), 'type' => 'textarea', 'rows' => 3)  ); ?>
                <?php echo $form->input( 'Contratinsertion.expr_prof', array( 'label' => __( 'expr_prof', true ), 'type' => 'textarea', 'rows' => 3)  ); ?>
                <?php echo $form->input( 'Contratinsertion.form_compl', array( 'label' => __( 'form_compl', true ), 'type' => 'textarea', 'rows' => 3)  ); ?>
    </fieldset>
              <?php echo $form->input( 'Contratinsertion.type_ci', array( 'label' => required( __( 'type_ci', true ) ), 'type' => 'select', 'options' => $type_ci, 'empty' => true ) );?>
    <fieldset>
            <legend> PARCOURS D'INSERTION ANTERIEUR </legend>
                <?php echo $form->input( 'Contratinsertion.rg_ci', array( 'label' => __( 'rg_ci', true ), 'type' => 'text')  ); ?>
                <?php echo $form->input( 'Contratinsertion.actions_prev', array( 'label' => __( 'actions_prev', true ), 'type' => 'textarea', 'rows' => 3)  ); ?>
                <?php echo $form->input( 'Contratinsertion.obsta_renc', array( 'label' => __( 'obsta_renc', true ), 'type' => 'textarea', 'rows' => 3)  ); ?>
     </fieldset>
                <?php echo $form->input( 'Contratinsertion.service_soutien', array( 'label' => __( 'service_soutien', true ), 'type' => 'textarea', 'rows' => 3)  ); ?> 
                <?php echo $form->input( 'Contratinsertion.pers_charg_suivi', array( 'label' => __( 'pers_charg_suivi', true ), 'type' => 'textarea', 'rows' => 1 )  ); ?>
    <fieldset>
            <legend> PROJET ET ACTIONS D'INSERTION </legend>
                <?php echo $form->input( 'Contratinsertion.objectifs_fixes', array( 'label' => __( 'objectifs_fixes', true ), 'type' => 'textarea', 'rows' => 3)  ); ?>
                <?php echo $form->input( 'Contratinsertion.engag_object', array( 'label' => __( 'engag_object', true ), 'type' => 'textarea', 'rows' => 4)  ); ?>
                Si vous avez trouvé un emploi, veuilez préciser : 
                <?php echo $form->input( 'Contratinsertion.sect_acti_emp', array( 'label' => __( 'sect_acti_emp', true ), 'type' => 'text')  ); ?>
                <?php echo $form->input( 'Contratinsertion.emp_occupe', array( 'label' => __( 'emp_occupe', true ), 'type' => 'text')  ); ?>
                <?php echo $form->input( 'Contratinsertion.duree_hebdo_emp', array( 'label' => __( 'duree_hebdo_emp', true ), 'type' => 'text')  ); ?>
                <?php echo $form->input( 'Contratinsertion.nat_cont_trav', array( 'label' => __( 'nat_cont_trav', true ), 'type' => 'text')  ); ?>
                <?php echo $form->input( 'Contratinsertion.duree_cdd', array( 'label' => __( 'duree_cdd', true ), 'type' => 'text')  ); ?>
    </fieldset>
    <fieldset>
                <?php echo $form->input( 'Contratinsertion.duree_engag', array( 'label' => __( 'duree_engag', true ), 'size' => 3)  ); ?>
                <?php echo $form->input( 'Contratinsertion.nature_projet', array( 'label' => __( 'nature_projet', true ), 'type' => 'textarea', 'rows' => 6)  ); ?>
                <?php echo $form->input( 'Fait à :', array( 'label' => 'Fait à : ', 'type' => 'text')  ); ?><br />
                <?php echo $form->input( 'Le ', array( 'label' => 'Le : ', 'type' => 'text')  ); ?>
                <?php echo $form->input( 'Le bénéficiaire : ', array( 'label' => 'Le bénéficiaire : ', 'type' => 'text')  ); ?>
    </fieldset>
    <fieldset>
                <?php echo $form->input( 'Contratinsertion.observ_ci', array( 'label' => __( 'observ_ci', true ), 'type' => 'textarea', 'rows' => 6)  ); ?>
                <?php echo $form->input( 'Contratinsertion.decision_ci', array( 'label' => required( __( 'decision_ci', true ) ),'type' => 'select', 'options' => $decision_ci, 'empty' => true ) ); ?>
                <?php echo $form->input( 'Contratinsertion.datevalidation_ci', array( 'label' => '', 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y'), 'minYear'=>date('Y')-80 , 'empty' => true)  ); ?><br />
                <?php echo $form->input( 'Fait à :', array( 'label' => 'Fait à : ', 'type' => 'text')  ); ?><br />
                <?php echo $form->input( 'Le ', array( 'label' => 'Le : ', 'type' => 'text')  ); ?>
                <?php echo $form->input( 'Le Président du Conseil Général : ', array( 'label' => 'Le Président du Conseil Général : ', 'type' => 'text')  ); ?>
    </fieldset>
