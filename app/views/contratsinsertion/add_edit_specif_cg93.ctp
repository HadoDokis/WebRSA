<!--  CER de la version 2.0rc9 remis en place suite à la demande du CG93 du 09 Mars 2011 -->
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

        //observeDisableFieldsOnBoolean( 'ContratinsertionActionsPrev', [ 'ContratinsertionObstaRenc' ], '1', false );
        observeDisableFieldsOnRadioValue(
            'testform',
            'data[Contratinsertion][actions_prev]',
            [ 'ContratinsertionObstaRenc' ],
            'N',
            true
        );


        observeDisableFieldsOnValue( 'ContratinsertionNatContTrav', [ 'ContratinsertionDureeCdd' ], 'TCT3', false );

        observeDisableFieldsOnRadioValue(
            'testform',
            'data[Contratinsertion][emp_trouv]',
            [ 'ContratinsertionSectActiEmp', 'ContratinsertionEmpOccupe', 'ContratinsertionDureeHebdoEmp', 'ContratinsertionNatContTrav', 'ContratinsertionDureeCdd' ],
            'O',
            true
        );
    } );
</script>

<fieldset>
    <legend> CONTRATS D'INSERTION </legend>
        <?php echo $form->input( 'Contratinsertion.dd_ci', array( 'label' => required( __d( 'contratinsertion', 'Contratinsertion.dd_ci', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true)  );?>
        <?php echo $form->input( 'Contratinsertion.duree_engag', array( 'label' => required( __d( 'contratinsertion', 'Contratinsertion.duree_engag', true ) ), 'type' => 'select', 'options' => $duree_engag_cg93, 'empty' => true )  ); ?>
        <?php echo $form->input( 'Contratinsertion.df_ci', array( 'label' => required( __d( 'contratinsertion', 'Contratinsertion.df_ci', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true ) ) ;?>
</fieldset>

<fieldset >
    <legend> FORMATION ET EXPERIENCE </legend>
    <?php echo $form->input( 'Dsp.id', array( 'label' => false, 'div' => false,  'type' => 'hidden' ) );?>
    <?php echo $form->input( 'Dsp.personne_id', array( 'label' => false, 'div' => false, /*'value' => Set::classicExtract( $personne, 'Personne.id' ), */ 'type' => 'hidden' ) );?>
    <?php echo $form->input( 'Dsp.nivetu', array( 'label' => __d( 'dsp', 'Dsp.nivetu', true ), 'options' => $nivetus, 'empty' => true ) );?>
    <?php echo $form->input( 'Contratinsertion.diplomes', array( 'label' => __d( 'contratinsertion', 'Contratinsertion.diplomes', true ), 'type' => 'textarea', 'rows' => 3)  ); ?>
    <?php echo $form->input( 'Contratinsertion.expr_prof', array( 'label' => __d( 'contratinsertion', 'Contratinsertion.expr_prof', true ), 'type' => 'textarea', 'rows' => 3)  ); ?>
    <?php echo $form->input( 'Contratinsertion.form_compl', array( 'label' =>  __d( 'contratinsertion', 'Contratinsertion.form_compl', true ), 'type' => 'textarea', 'rows' => 3)  ); ?>
</fieldset>
<fieldset>
    <legend> PARCOURS D'INSERTION ANTERIEUR </legend>
        <?php
            echo $widget->booleanRadio( 'Contratinsertion.actions_prev', array( 'legend' => required( __d( 'contratinsertion', 'Contratinsertion.actions_prev', true ) ) ) );
        ?>

        <?php echo $form->input( 'Contratinsertion.obsta_renc', array( 'label' => __d( 'contratinsertion', 'Contratinsertion.obsta_renc', true ), 'type' => 'textarea', 'rows' => 3)  ); ?>
</fieldset>
<fieldset>
    <legend> PROJET ET ACTIONS D'INSERTION </legend>
        <?php echo $form->input( 'Contratinsertion.objectifs_fixes', array( 'label' => __d( 'contratinsertion', 'Contratinsertion.objectifs_fixes', true ), 'type' => 'textarea', 'rows' => 3)  ); ?>

        <?php
            echo $form->input( 'Action.id', array( 'type' => 'hidden' ) );
            echo $form->input( 'Action.code', array( 'label' => __d( 'action', 'Action.code_action', true ), 'type' => 'text', 'empty' => true, 'maxlength' => 2 )  );
            echo $form->input( 'Contratinsertion.engag_object', array( 'label' => __d( 'contratinsertion', 'Contratinsertion.engag_object', true ), 'type' => 'select', 'options' => $actions, 'empty' => true )  );
        ?>
        <?php
            ///FIXME
            $contratinsertion_id = Set::extract( $this->data, 'Actioninsertion.contratinsertion_id' );
            if( $this->action == 'edit' && !empty( $contratinsertion_id ) ) :?>
            <?php echo $form->input( 'Actioninsertion.contratinsertion_id', array( 'label' => false, 'div' => false,  'type' => 'hidden' ) );?>
        <?php endif;?>
        <?php
            echo $form->input( 'Actioninsertion.dd_action', array( 'label' => __d( 'action', 'Action.dd_action', true ), 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true )  );
            echo $form->input( 'Contratinsertion.commentaire_action', array( 'label' => __d( 'contratinsertion', 'Contratinsertion.commentaire_action', true ), 'type' => 'textarea', 'rows' => 3 )  );
        ?>
        <?php
            echo $widget->booleanRadio( 'Contratinsertion.emp_trouv', array( 'legend' => required( __d( 'contratinsertion', 'Contratinsertion.emp_trouv', true ) )) );
        ?>
        Si oui, veuillez préciser :
        <?php echo $form->input( 'Contratinsertion.sect_acti_emp', array( 'label' => required( __d( 'contratinsertion', 'Contratinsertion.sect_acti_emp', true ) ), 'type' => 'select', 'options' => $sect_acti_emp, 'empty' => true )  ); ?>
        <?php echo $form->input( 'Contratinsertion.emp_occupe', array( 'label' => required( __d( 'contratinsertion', 'Contratinsertion.emp_occupe', true ) ), 'type' => 'select', 'options' => $emp_occupe, 'empty' => true )  ); ?>
        <?php echo $form->input( 'Contratinsertion.duree_hebdo_emp', array( 'label' => required( __d( 'contratinsertion', 'Contratinsertion.duree_hebdo_emp', true ) ), 'type' => 'select', 'options' => $duree_hebdo_emp, 'empty' => true )  ); ?>
        <?php echo $form->input( 'Contratinsertion.nat_cont_trav', array( 'label' => required( __d( 'contratinsertion', 'Contratinsertion.nat_cont_trav', true ) ), 'type' => 'select', 'options' => $nat_cont_trav, 'empty' => true )  ); ?>
        <?php echo $form->input( 'Contratinsertion.duree_cdd', array( 'label' => required( __d( 'contratinsertion', 'Contratinsertion.duree_cdd', true ) ), 'type' => 'select', 'options' => $duree_cdd, 'empty' => true )  ); ?>
</fieldset>

<fieldset>
        <?php echo $form->input( 'Contratinsertion.nature_projet', array( 'label' => required( __d( 'contratinsertion', 'Contratinsertion.nature_projet', true ) ), 'type' => 'textarea', 'rows' => 6)  ); ?>
        <?php echo $form->input( 'Contratinsertion.lieu_saisi_ci', array( 'label' => required( __d( 'contratinsertion', 'Contratinsertion.lieu_saisi_ci', true ) ), 'type' => 'text', 'maxlength' => 50 )  ); ?><br />
        <?php echo $form->input( 'Contratinsertion.date_saisi_ci', array( 'label' => __d( 'contratinsertion', 'Contratinsertion.date_saisi_ci', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true)  ); ?>
        <?php
            echo $xhtml->tag(
                'div',
                $xhtml->tag( 'span', 'Le bénéficiaire :', array( 'class' => 'label' ) ).
                $xhtml->tag( 'span', $personne['Personne']['nom'].' '.$personne['Personne']['prenom'], array( 'class' => 'input' ) ),
                array( 'class' => 'input text' )
            );
        ?>
</fieldset>





<!-- CER en version 2.0rc15 Modifié suite à la demande du CG93 du 09 Mars 2011

<script type="text/javascript">
    document.observe( "dom:loaded", function() {
        Event.observe( $( 'ActionCode' ), 'keyup', function() {
            var value = $F( 'ActionCode' );
            if( value.length == 2 ) { //FIXME: in_array
                $$( '#ContratinsertionEngagObject option').each( function ( option ) {
                    if( $( option ).value == value ) {
                        $( option ).selected = 'selected';
                    }
                } );
            }
        } );

        //observeDisableFieldsOnBoolean( 'ContratinsertionActionsPrev', [ 'ContratinsertionObstaRenc' ], '1', false );
//        observeDisableFieldsOnRadioValue(
//            'testform',
//            'data[Contratinsertion][actions_prev]',
//            [ 'ContratinsertionObstaRenc' ],
//            'N',
//            true
//        );

        observeDisableFieldsOnValue( 'ContratinsertionNatContTrav', [ 'ContratinsertionDureeCdd' ], 'TCT3', false );

        observeDisableFieldsOnRadioValue(
            'testform',
            'data[Contratinsertion][emp_trouv]',
            [ 'ContratinsertionSectActiEmp', 'ContratinsertionEmpOccupe', 'ContratinsertionDureeHebdoEmp', 'ContratinsertionNatContTrav', 'ContratinsertionDureeCdd' ],
            'O',
            true
        );
    } );
</script>

<fieldset >
    <legend> FORMATION ET EXPERIENCE </legend>
    <?php echo $form->input( 'Dsp.id', array( 'label' => false, 'div' => false,  'type' => 'hidden' ) );?>
    <?php echo $form->input( 'Dsp.personne_id', array( 'label' => false, 'div' => false, /*'value' => Set::classicExtract( $personne, 'Personne.id' ), */ 'type' => 'hidden' ) );?>
    <?php echo $form->input( 'Dsp.nivetu', array( 'label' => __d( 'dsp', 'Dsp.nivetu', true ), 'options' => $nivetus, 'empty' => true ) );?>
    <?php echo $form->input( 'Dsp.nivdipmaxobt', array( 'label' => __d( 'dsp', 'Dsp.nivdipmaxobt', true ), 'options' => $nivdipmaxobt, 'empty' => true ) );?>
    <?php echo $form->input( 'Dsp.annobtnivdipmax', array( 'label' => __d( 'dsp', 'Dsp.annobtnivdipmax', true ) )) ;?>    
    <?php echo $form->input( 'Contratinsertion.expr_prof', array( 'label' => __d( 'contratinsertion', 'Contratinsertion.expr_prof', true ), 'type' => 'textarea', 'rows' => 6)  ); ?>
    <?php echo $form->input( 'Contratinsertion.form_compl', array( 'label' =>  __d( 'contratinsertion', 'Contratinsertion.form_compl', true ), 'type' => 'textarea', 'rows' => 3)  ); ?>
</fieldset>
<fieldset>
    <legend> HISTORIQUE DU PARCOURS D'INSERTION </legend>
        <h4>( les 5 derniers contrats du plus récent au plus ancien) </h4>
        <table>
            <thead>
                <tr>
                    <th>Rang</th>
                    <th>Date de début</th>
                    <th>Date de fin</th>
                    <th>Service d'accompagnement (structure référente)</th>
                    <th>Thématique du contrat</th>
                    <th>Statut (validé, rejeté, ajourné, en attente)</th>
                    <th>Motif</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach( $lastContrat as $key => $value)
                    {
                        echo '<tr>';
                            echo $xhtml->tag('td', $xhtml->tag('strong',$value['Contratinsertion']['rg_ci']));
                            echo $xhtml->tag('td', date_short( $value['Contratinsertion']['dd_ci'] ) );
                            echo $xhtml->tag('td', date_short( $value['Contratinsertion']['df_ci'] ) );
                            echo $xhtml->tag('td', $value['Structurereferente']['lib_struc']);
                            echo $xhtml->tag('td', '?');
                            echo $xhtml->tag('td', value( $decision_ci, Set::extract( 'Contratinsertion.decision_ci', $value ) ));
                            echo $xhtml->tag('td', $value['Contratinsertion']['observ_ci']);
                        echo '</tr>';
                    }
                ?>
            </tbody>
        </table>
</fieldset>
<fieldset>
    <legend> BILAN DU CONTRAT PRÉCÉDENT</legend>
        <span>Le précédent contrat portait sur&nbsp;:&nbsp;</span>
        <?php 
            $value = isset($lastContrat[0]['Contratinsertion']['engag_object']) ? $lastContrat[0]['Contratinsertion']['engag_object'] : 'NC';
            foreach($actions as $type => $action)
            {
                if(isset($action[$value]))
                {
                    $value = "{$type} / {$action[$value]}";
                    break;
                }       
            }
            echo $xhtml->tag('span', $value);
            echo $form->input( 'Ci.autre', array( 'label' => 'Si autre (préciser)', 'type'=>'text'));
        ?>
        <?php echo $form->input( 'Contratinsertion.obsta_renc', array( 'label' => 'Quel bilan faites vous des actions précisées dans le précédent contrat (les avancées et/ou les freins)  ? ', 'type' => 'textarea', 'rows' => 3)  ); ?>
</fieldset>
<fieldset>
    <legend> PROJET DE CE NOUVEAU CONTRAT</legend>
        <?php echo $form->input( 'Contratinsertion.objectifs_fixes', array( 'label' => __d( 'contratinsertion', 'Contratinsertion.objectifs_fixes', true ), 'type' => 'textarea', 'rows' => 3)  ); ?>
        <?php echo $form->input( 'Contratinsertion.dd_ci', array( 'label' => required( __d( 'contratinsertion', 'Contratinsertion.dd_ci', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true)  );?>
        <?php echo $form->input( 'Contratinsertion.duree_engag', array( 'label' => required( __d( 'contratinsertion', 'Contratinsertion.duree_engag', true ) ), 'type' => 'select', 'options' => $duree_engag_cg93, 'empty' => true )  ); ?>
        <?php echo $form->input( 'Contratinsertion.df_ci', array( 'label' => required( __d( 'contratinsertion', 'Contratinsertion.df_ci', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true ) ) ;?>
</fieldset>
<fieldset>
        <?php echo $form->input( 'Contratinsertion.nature_projet', array( 'label' => required( __d( 'contratinsertion', 'Contratinsertion.nature_projet', true ) ), 'type' => 'textarea', 'rows' => 6)  ); ?>
        <?php echo $form->input( 'Contratinsertion.lieu_saisi_ci', array( 'label' => required( __d( 'contratinsertion', 'Contratinsertion.lieu_saisi_ci', true ) ), 'type' => 'text', 'maxlength' => 50 )  ); ?><br />
        <?php echo $form->input( 'Contratinsertion.date_saisi_ci', array( 'label' => __d( 'contratinsertion', 'Contratinsertion.date_saisi_ci', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true)  ); ?>
        <?php
            echo $xhtml->tag(
                'div',
                $xhtml->tag( 'span', 'Le bénéficiaire :', array( 'class' => 'label' ) ).
                $xhtml->tag( 'span', $personne['Personne']['nom'].' '.$personne['Personne']['prenom'], array( 'class' => 'input' ) ),
                array( 'class' => 'input text' )
            );
        ?>
</fieldset>
-->