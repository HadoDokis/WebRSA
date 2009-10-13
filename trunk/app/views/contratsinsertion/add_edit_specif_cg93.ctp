<script type="text/javascript">
    document.observe( "dom:loaded", function() {
        Event.observe( $( 'ActionCode' ), 'keyup', function() {
            var value = $F( 'ActionCode' );
            if( value.length == 2 ) { // FIXME: in_array
                $$( '#ContratinsertionEngagObject option').each( function ( option ) {
                    if( $( option ).value == value ) {
                        $( option ).selected = 'selected';
                    }
                } );
            }
        } );

        observeDisableFieldsOnBoolean( 'ContratinsertionActionsPrev', [ 'ContratinsertionObstaRenc' ], '1', false );
        observeDisableFieldsOnValue( 'ContratinsertionNatContTrav', [ 'ContratinsertionDureeCdd' ], 'TCT3', false );
        observeDisableFieldsOnBoolean( 'ContratinsertionEmpTrouv', [ 'ContratinsertionSectActiEmp', 'ContratinsertionEmpOccupe', 'ContratinsertionDureeHebdoEmp', 'ContratinsertionNatContTrav', 'ContratinsertionDureeCdd' ], 0, false );
    } );
</script>

<fieldset>
    <legend> CONTRATS D'INSERTION </legend>
        <?php echo $form->input( 'Contratinsertion.dd_ci', array( 'label' => required( __( 'dd_ci', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true)  );?>
        <?php echo $form->input( 'Contratinsertion.duree_engag', array( 'label' => required( __( 'duree_engag', true ) ), 'type' => 'select', 'options' => $duree_engag, 'empty' => true )  ); ?>
        <?php echo $form->input( 'Contratinsertion.df_ci', array( 'label' => required( __( 'df_ci', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true ) ) ;?>
</fieldset>
<fieldset >
    <legend> FORMATION ET EXPERIENCE </legend>
    <?php if( $this->action != 'edit' ) :?>
        <?php echo $form->input( 'Dspp.id', array( 'label' => false, 'div' => false,  'type' => 'hidden' ) );?>
        <?php echo $form->input( 'Dspp.personne_id', array( 'label' => false, 'div' => false,  'type' => 'hidden' ) );?>
    <?php endif;?>
        <?php echo $form->input( 'Nivetu.Nivetu', array( 'label' => false, 'div' => false,  'multiple' => 'checkbox', 'options' => $nivetus ) );?>
        <?php echo $form->input( 'Contratinsertion.diplomes', array( 'label' => required( __( 'diplomes', true ) ), 'type' => 'textarea', 'rows' => 3)  ); ?>
        <?php echo $form->input( 'Contratinsertion.expr_prof', array( 'label' => __( 'expr_prof', true ), 'type' => 'textarea', 'rows' => 3)  ); ?>
        <?php echo $form->input( 'Contratinsertion.form_compl', array( 'label' =>  __( 'form_compl', true ), 'type' => 'textarea', 'rows' => 3)  ); ?>
</fieldset>
<fieldset>
    <legend> PARCOURS D'INSERTION ANTERIEUR </legend>
        <?php
            echo $widget->booleanRadio( 'Contratinsertion.actions_prev', array( 'legend' => required( __( 'actions_prev', true ) ) ) );
        ?>

        <?php echo $form->input( 'Contratinsertion.obsta_renc', array( 'label' => __( 'obsta_renc', true ), 'type' => 'textarea', 'rows' => 3)  ); ?>
</fieldset>
<fieldset>
    <legend> PROJET ET ACTIONS D'INSERTION </legend>
        <?php echo $form->input( 'Contratinsertion.objectifs_fixes', array( 'label' => __( 'objectifs_fixes', true ), 'type' => 'textarea', 'rows' => 3)  ); ?>

        <?php 
            echo $form->input( 'Action.id', array( 'type' => 'hidden' ) );
            echo $form->input( 'Action.code', array( 'label' => __( 'code_action', true ), 'type' => 'text', 'empty' => true, 'maxlength' => 2 )  );
            echo $form->input( 'Contratinsertion.engag_object', array( 'label' => __( 'engag_object', true ), 'type' => 'select', 'options' => $actions, 'empty' => true )  );
        ?>
        <?php
            ///FIXME
            $contratinsertion_id = Set::extract( $this->data, 'Actioninsertion.contratinsertion_id' );
            if( $this->action == 'edit' && !empty( $contratinsertion_id ) ) :?>
            <?php echo $form->input( 'Actioninsertion.contratinsertion_id', array( 'label' => false, 'div' => false,  'type' => 'hidden' ) );?>
        <?php endif;?>
        <?php
            echo $form->input( 'Actioninsertion.dd_action', array( 'label' => __( 'dd_action', true ), 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true )  );
            echo $form->input( 'Contratinsertion.commentaire_action', array( 'label' => __( 'commentaire_action', true ), 'type' => 'textarea', 'rows' => 3 )  );
        ?>
        <?php
            echo $widget->booleanRadio( 'Contratinsertion.emp_trouv', array( 'legend' => required( __( 'emp_trouv', true ) )) );
        ?>
        Si oui, veuillez préciser :
        <?php echo $form->input( 'Contratinsertion.sect_acti_emp', array( 'label' => required( __( 'sect_acti_emp', true ) ), 'type' => 'select', 'options' => $sect_acti_emp, 'empty' => true )  ); ?>
        <?php echo $form->input( 'Contratinsertion.emp_occupe', array( 'label' => required( __( 'emp_occupe', true ) ), 'type' => 'select', 'options' => $emp_occupe, 'empty' => true )  ); ?>
        <?php echo $form->input( 'Contratinsertion.duree_hebdo_emp', array( 'label' => required( __( 'duree_hebdo_emp', true ) ), 'type' => 'select', 'options' => $duree_hebdo_emp, 'empty' => true )  ); ?>
        <?php echo $form->input( 'Contratinsertion.nat_cont_trav', array( 'label' => required( __( 'nat_cont_trav', true ) ), 'type' => 'select', 'options' => $nat_cont_trav, 'empty' => true )  ); ?>
        <?php echo $form->input( 'Contratinsertion.duree_cdd', array( 'label' => required( __( 'duree_cdd', true ) ), 'type' => 'select', 'options' => $duree_cdd, 'empty' => true )  ); ?>
</fieldset>

<fieldset>
        <?php echo $form->input( 'Contratinsertion.nature_projet', array( 'label' => required( __( 'nature_projet', true ) ), 'type' => 'textarea', 'rows' => 6)  ); ?>
        <?php echo $form->input( 'Contratinsertion.lieu_saisi_ci', array( 'label' => required( __( 'lieu_saisi_ci', true ) ), 'type' => 'text')  ); ?><br />
        <?php echo $form->input( 'Contratinsertion.date_saisi_ci', array( 'label' => __( 'date_saisi_ci', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true)  ); ?>
        <?php
            /*echo $form->input( 'Le bénéficiaire : ', array( 'label' => 'Le bénéficiaire : ', 'type' => 'text', 'value' => $personne['Personne']['nom'].' '.$personne['Personne']['prenom'] )  );*/
            echo $html->tag(
                'div',
                $html->tag( 'span', 'Le bénéficiaire :', array( 'class' => 'label' ) ).
                $html->tag( 'span', $personne['Personne']['nom'].' '.$personne['Personne']['prenom'], array( 'class' => 'input' ) ),
                array( 'class' => 'input text' )
            );
        ?>
</fieldset>

       <!-- <?php echo $form->input( 'Contratinsertion.structurereferente_id', array( 'label' => __( 'Nom du service d\'accompagnement (Structure référente)', true ), 'type' => 'select' , 'options' => $sr, 'empty' => true ) );?>
        <?php echo $form->input( 'Contratinsertion.service_soutien', array( 'label' => '<em>'.__( 'service_soutien', true ).'</em>', 'type' => 'textarea', 'rows' => 3 )  ); ?>
        <?php echo $ajax->observeField( 'ContratinsertionStructurereferenteId', array( 'update' => 'ContratinsertionServiceSoutien', 'url' => Router::url( array( 'action' => 'ajax' ), true ) ) ) ;?>

        <?php echo $form->input( 'Contratinsertion.pers_charg_suivi', array( 'label' => '<em>'. __( 'pers_charg_suivi', true ).'</em>', 'type' => 'textarea', 'rows' => 1 )  ); ?> -->