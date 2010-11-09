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
    <?php echo $form->input( 'Contratinsertion.expr_prof', array( 'label' => __( 'expr_prof', true ), 'type' => 'textarea', 'rows' => 6)  ); ?>
    <?php echo $form->input( 'Contratinsertion.form_compl', array( 'label' =>  __( 'form_compl', true ), 'type' => 'textarea', 'rows' => 3)  ); ?>
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
                            echo $html->tag('td', $html->tag('strong',$value['Contratinsertion']['rg_ci']));
                            echo $html->tag('td', date_short( $value['Contratinsertion']['dd_ci'] ) );
                            echo $html->tag('td', date_short( $value['Contratinsertion']['df_ci'] ) );
                            echo $html->tag('td', $value['Structurereferente']['lib_struc']);
                            echo $html->tag('td', '?');
                            echo $html->tag('td', value( $decision_ci, Set::extract( 'Contratinsertion.decision_ci', $value ) ));
                            echo $html->tag('td', $value['Contratinsertion']['observ_ci']);
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
            echo $html->tag('span', $value);
            echo $form->input( 'Ci.autre', array( 'label' => 'Si autre (préciser)', 'type'=>'text'));
        ?>
        <?php echo $form->input( 'Contratinsertion.obsta_renc', array( 'label' => 'Quel bilan faites vous des actions précisées dans le précédent contrat (les avancées et/ou les freins)  ? ', 'type' => 'textarea', 'rows' => 3)  ); ?>
</fieldset>
<fieldset>
    <legend> PROJET DE CE NOUVEAU CONTRAT</legend>
        <?php echo $form->input( 'Contratinsertion.objectifs_fixes', array( 'label' => __( 'objectifs_fixes', true ), 'type' => 'textarea', 'rows' => 3)  ); ?>
        <?php echo $form->input( 'Contratinsertion.dd_ci', array( 'label' => required( __( 'dd_ci', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true)  );?>
        <?php echo $form->input( 'Contratinsertion.duree_engag', array( 'label' => required( __( 'duree_engag', true ) ), 'type' => 'select', 'options' => $duree_engag_cg93, 'empty' => true )  ); ?>
        <?php echo $form->input( 'Contratinsertion.df_ci', array( 'label' => required( __( 'df_ci', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true ) ) ;?>
</fieldset>
<fieldset>
        <?php echo $form->input( 'Contratinsertion.nature_projet', array( 'label' => required( __( 'nature_projet', true ) ), 'type' => 'textarea', 'rows' => 6)  ); ?>
        <?php echo $form->input( 'Contratinsertion.lieu_saisi_ci', array( 'label' => required( __( 'lieu_saisi_ci', true ) ), 'type' => 'text', 'maxlength' => 50 )  ); ?><br />
        <?php echo $form->input( 'Contratinsertion.date_saisi_ci', array( 'label' => __( 'date_saisi_ci', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true)  ); ?>
        <?php
            echo $html->tag(
                'div',
                $html->tag( 'span', 'Le bénéficiaire :', array( 'class' => 'label' ) ).
                $html->tag( 'span', $personne['Personne']['nom'].' '.$personne['Personne']['prenom'], array( 'class' => 'input' ) ),
                array( 'class' => 'input text' )
            );
        ?>
</fieldset>