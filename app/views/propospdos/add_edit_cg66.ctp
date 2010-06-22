<?php
    $domain = 'pdo';
    echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
?>

<?php  $this->pageTitle = 'Validation PDO';?>

<?php
    echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );

?>
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        //Utilisé en cas de motif de PDO non admissible
//         observeDisableFieldsOnValue( 'PropopdoMotifpdo', [ 'PropopdoNonadmis' ], 'N', false );

        observeDisableFieldsetOnCheckbox( 'PropopdoDecision', $( 'PropopdoDecisionpdoId' ).up( 'fieldset' ), false );
        observeDisableFieldsetOnCheckbox( 'PropopdoSuivi', $( 'PropopdoDaterevisionDay' ).up( 'fieldset' ), false );
        observeDisableFieldsetOnCheckbox( 'PropopdoAutres', $( 'PropopdoCommentairepdo' ).up( 'fieldset' ), false );

//         observeDisableFieldsetOnCheckbox( 'PropopdoMotifpdo', $( 'PropopdoNonadmis' ).up( 'fieldset' ), false );
        observeDisableFieldsetOnRadioValue(
            'propopdoform',
            'data[Propopdo][motifpdo]',
            $( 'nonadmis' ),
            'N',
            false,
            true
        );
    });
</script>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout d\'une PDO';
    }
    else {
        $this->pageTitle = 'Édition de la PDO';
    }
?>

<script type="text/javascript">

    function checkDatesToRefresh() {
        if( ( $F( 'PropopdoDaterevisionMonth' ) ) && ( $F( 'PropopdoDaterevisionYear' ) ) ) {
            setDateInterval2( 'PropopdoDaterevision', 'PropopdoDateecheance', 4, false );

        }
    }

    document.observe("dom:loaded", function() {
        setDateInterval2( 'PropopdoDaterevision', 'PropopdoDateecheance', 4, false );

        Event.observe( $( 'PropopdoDaterevisionMonth' ), 'change', function() {
            checkDatesToRefresh();
        } );
        Event.observe( $( 'PropopdoDaterevisionYear' ), 'change', function() {
            checkDatesToRefresh();
        } );




    });


</script>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php
        echo $xform->create( 'Propopdo', array( 'id' => 'propopdoform' ) );
        if( $this->action == 'add' ) {
//             echo $xform->create( 'Propopdo', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
//             echo $xform->input( 'PropopdoTypenotifpdo.id', array( 'type' => 'hidden' ) );
        }
        else {
//             echo $xform->create( 'Propopdo', array( 'type' => 'post', 'url' => Router::url( null, true ), 'id' => 'propopdoform' ) );
            echo '<div>';
            echo $xform->input( 'Propopdo.id', array( 'type' => 'hidden' ) );
//             echo $xform->input( 'PropopdoTypenotifpdo.id', array( 'type' => 'hidden' ) );

            echo '</div>';
        }
        echo '<div>';
        echo $xform->input( 'Propopdo.personne_id', array( 'type' => 'hidden', 'value' => $personne_id ) );

        echo '</div>';
    ?>

    <div class="aere">

    <fieldset>
        <legend>Arrivée de la PDO</legend>
        <?php
            echo $default->subform(
                array(
                    'Propopdo.typepdo_id' => array( 'label' =>  ( __( 'typepdo', true ) ), 'type' => 'select', 'options' => $typepdo, 'empty' => true ),
                    'Propopdo.datereceptionpdo' => array( 'label' =>  ( __( 'Date de réception de la PDO', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => false ),
                    'Propopdo.choixpdo' => array( 'label' =>  ( __( 'Choix', true ) ), 'type' => 'radio', 'options' => $options['choixpdo'], 'empty' => true ),
                    'Propopdo.originepdo_id' => array( 'label' =>  ( __( 'Origine', true ) ), 'type' => 'select', 'options' => $originepdo, 'empty' => true )
//                     'Propopdo.courrierpdo'
                ),
                array(
                    'domain' => $domain,
                    'options' => $options
                )
            );

                echo $default->view(
                    $dossier,
                    array(
                        'Dossier.fonorg',
                        'Suiviinstruction.typeserins',
                    ),
                    array(
                        'widget' => 'table',
                        'id' => 'dossierInfosOrganisme',
                        'options' => $options
                    )
                );
        ?>
    </fieldset>

    <fieldset id="Etatpdo1" class="invisible">
        <?php
//             debug($step);
//             if( $step == '0' ) {
//                 echo 'Etat du dossier : <strong>'.$etatpdo.'</strong>';
//             }
//             else {
//                 echo 'Etat du dossier : ';
//             }
        ?>
    </fieldset>

    <fieldset>
        <legend>Prise de décision</legend>
        <table class="noborder" id="infosPdo">
            <tr>
                <td class="mediumSize noborder">
                    <?php
                        echo $xform->input( 'Situationpdo.Situationpdo', array( 'type' => 'select', 'label' => 'Situation de la PDO', 'multiple' => 'checkbox' , 'options' => $situationlist ) );
                    ?>
                </td>
                <td class="mediumSize noborder">
                    <?php
                        echo $xform->input( 'Statutpdo.Statutpdo', array( 'type' => 'select', 'label' => 'Statut de la PDO', 'multiple' => 'checkbox' , 'options' => $statutlist ) );
                    ?>
                </td>
            </tr>
        </table>
        <?php

            echo $html->tag(
                'p',
                'Catégories : '
            );

            echo $default->subform(
                array(
                    'Propopdo.categoriegeneral' => array( 'label' => __d( 'propopdo', 'Propopdo.categoriegeneral', true ), 'type' => 'select', 'empty' => true, 'options' => $categoriegeneral ),
                    'Propopdo.categoriedetail' => array( 'label' => __d( 'propopdo', 'Propopdo.categoriedetail', true ), 'type' => 'select', 'empty' => true, 'options' => $categoriedetail ),
                ),
                array(
                    'domain' => $domain,
                    'options' => $options
                )
            );
        ?>
    </fieldset>

    <fieldset id="Etatpdo2" class="invisible">
        <?php
//             if( $step == '1' ) {
//                 echo 'Etat du dossier : <strong>'.$etatpdo.'</strong>';
//             }
//             else {
//                 echo 'Etat du dossier : ';
//             }
        ?>
    </fieldset>

    <fieldset>
        <?php
            echo $form->input( 'Propopdo.decision', array( 'label' => 'Décision', 'type' => 'checkbox' ) );
        ?>
        <fieldset id="Decision" class="invisible">
            <?php
                echo $default->subform(
                    array(
                        'Propopdo.datedecisionpdo' => array( 'label' =>  ( __( 'Date de décision de la PDO', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => false ),
                        'Propopdo.decisionpdo_id' => array( 'label' =>  ( __( 'Décision du Conseil Général', true ) ), 'type' => 'select', 'options' => $decisionpdo, 'empty' => true ),
                        'Propopdo.dateenvoiop' => array( 'label' =>  ( __( 'Date d\'envoi à l\'OP', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => false ),
                        'Propopdo.motifpdo' => array( 'label' =>  ( __( 'Motif de la décision', true ) ), 'type' => 'radio', 'options' => $motifpdo, 'empty' => true )
                    ),
                    array(
                        'domain' => $domain,
                        'options' => $options
                    )
                );
            ?>
             <fieldset id="nonadmis" class="invisible">
                <?php
                    echo $default->subform(
                        array(
                            'Propopdo.nonadmis' => array( 'label' => 'Raison non admissible', 'type' => 'select', 'options' => $options['nonadmis'], 'empty' => true  )
                        ),
                        array(
                            'domain' => $domain,
                            'options' => $options
                        )
                    );
                ?>
            </fieldset>
        </fieldset>

    </fieldset>

    <fieldset id="Etatpdo3" class="invisible">
        <?php
//             if( $step == '2' ) {
//                 echo 'Etat du dossier : <strong>'.$etatpdo.'</strong>';
//             }
//             else {
//                 echo 'Etat du dossier : ';
//             }
        ?>

    </fieldset>

    <fieldset>
        <?php
            echo $form->input( 'Propopdo.suivi', array( 'label' => 'Suivi', 'type' => 'checkbox' ) );
        ?>
        <fieldset id="Suivi" class="invisible">
        <?php
            echo $default->subform(
                array(
                    'Propopdo.daterevision' => array( 'label' =>  ( __( 'Date de révision', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => false ),
                    'Propopdo.dateecheance' => array( 'label' =>  ( __( 'Date d\'échéance (date à laquelle on doit reprendre une décision)', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear' => date('Y')+5, 'minYear' => date('Y')-1, 'empty' => false )
                ),
                array(
                    'domain' => $domain,
                    'options' => $options
                )
            );
        ?>
        </fieldset>
    </fieldset>

    <fieldset id="Etatpdo4" class="invisible">
        <?php
//             if( $step == '3' ) {
//                 echo 'Etat du dossier : <strong>'.$etatpdo.'</strong>';
//             }
//             else {
//                 echo 'Etat du dossier : ';
//             }
        ?>

    </fieldset>

    <fieldset>
        <?php
            echo $form->input( 'Propopdo.autres', array( 'label' => 'Autres', 'type' => 'checkbox' ) );
        ?>
        <fieldset id="Autres" class="invisible">
        <?php
            echo $default->subform(
                array(
                    'Propopdo.commentairepdo' => array( 'label' =>  'Mémo pour observation', 'type' => 'textarea', 'rows' => 3 ),
                    'Propopdo.referent_id' => array( 'label' =>  'Référent du dossier PDO (instructeur en charge du dossier)', 'type' => 'select', 'options' => $referents )
                ),
                array(
                    'domain' => $domain,
                    'options' => $options
                )
            );
        ?>
        </fieldset>
    </fieldset>
    <fieldset id="Etatpdo5" class="invisible">
        <?php
//             if( $step == '4' ) {
//                 echo 'Etat du dossier : <strong>'.$etatpdo.'</strong>';
//             }
//             else {
//                 echo 'Etat du dossier : ';
//             }
        ?>

    </fieldset>

    </div>
    <div class="submit">
        <?php echo $form->submit( 'Enregistrer', array( 'div' => false ) );?>
        <?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
    </div>

    <?php echo $xform->end();?>
</div>
<div class="clearer"><hr /></div>