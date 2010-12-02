<?php
    $domain = 'pdo';
    echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
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
//        observeDisableFieldsetOnCheckbox( 'PropopdoSuivi', $( 'PropopdoDaterevisionDay' ).up( 'fieldset' ), false );
//         observeDisableFieldsetOnCheckbox( 'PropopdoAutres', $( 'PropopdoCommentairepdo' ).up( 'fieldset' ), false );
        observeDisableFieldsetOnCheckbox( 'PropopdoIsvalidation', $( 'PropopdoDatevalidationdecisionDay' ).up( 'fieldset' ), false );
        observeDisableFieldsetOnCheckbox( 'PropopdoIsdecisionop', $( 'PropopdoObservationop' ).up( 'fieldset' ), false );


//         observeDisableFieldsetOnCheckbox( 'PropopdoMotifpdo', $( 'PropopdoNonadmis' ).up( 'fieldset' ), false );
        /*observeDisableFieldsetOnRadioValue(
            'propopdoform',
            'data[Propopdo][motifpdo]',
            $( 'nonadmis' ),
            'N',
            false,
            true
        );*/
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

        <?php
            echo $ajax->remoteFunction(
                array(
                    'update' => 'Etatpdo6',
                    'url' => Router::url(
                        array(
                            'action' => 'ajaxetatpdo',
                            Set::extract( $this->data, 'Propopdo.typepdo_id' ),
                            Set::extract( $this->data, 'Propopdo.iscomplet' ),
                            Set::extract( $this->data, 'Propopdo.decisionpdo_id' ),
                            Set::extract( $this->data, 'Propopdo.isvalidation' ),
                            Set::extract( $this->data, 'Propopdo.isdecisionop' )
                        ),
                        true
                    )
                )
            ).';';
//             echo $ajax->remoteFunction(
//                 array(
//                     'update' => 'Etatpdo2',
//                     'url' => Router::url(
//                         array(
//                             'action' => 'ajaxetat2',
//                             Set::extract( $this->data, 'Propopdo.iscomplet' )
//                         ),
//                         true
//                     )
//                 )
//             ).';';
//             echo $ajax->remoteFunction(
//                 array(
//                     'update' => 'Etatpdo3',
//                     'url' => Router::url(
//                         array(
//                             'action' => 'ajaxetat3',
//                             Set::extract( $this->data, 'Propopdo.decisionpdo_id' )
//                         ),
//                         true
//                     )
//                 )
//             ).';';
//             echo $ajax->remoteFunction(
//                 array(
//                     'update' => 'Etatpdo4',
//                     'url' => Router::url(
//                         array(
//                             'action' => 'ajaxetat4',
//                             Set::extract( $this->data, 'Propopdo.isvalidation' )
//                         ),
//                         true
//                     )
//                 )
//             ).';';
//             echo $ajax->remoteFunction(
//                 array(
//                     'update' => 'Etatpdo5',
//                     'url' => Router::url(
//                         array(
//                             'action' => 'ajaxetat5',
//                             Set::extract( $this->data, 'Propopdo.isdecisionop' )
//                         ),
//                         true
//                     )
//                 )
//             ).';';
        ?>

        observeDisableFieldsetOnRadioValue(
            'propopdoform',
            'data[Propopdo][iscomplet]',
            $( 'FicheCalcul' ),
            'COM',
            false,
            true
        );
    });


</script>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php

        echo $xform->create( 'Propopdo', array( 'id' => 'propopdoform' ) );
        if( $this->action == 'add' ) {
        }
        else {
            echo '<div>';
            echo $xform->input( 'Propopdo.id', array( 'type' => 'hidden' ) );
            echo '</div>';
        }
        echo '<div>';
        echo $xform->input( 'Propopdo.personne_id', array( 'type' => 'hidden', 'value' => $personne_id ) );

        echo '</div>';
    ?>

    <div class="aere">

    <fieldset>

        <?php
            echo $default->subform(
                array(
                    'Propopdo.etatdossierpdo' => array( 'type' => 'hidden' )/*,
                    'Propopdo.user_id' => array( 'label' =>  'Gestionnaire du dossier PDO (instructeur en charge du dossier)', 'type' => 'select', 'options' => $gestionnaire )*/
                ),
                array(
                    'domain' => $domain,
                    'options' => $options
                )
            );
        ?>

        <fieldset id="AdresseStruct" class="invisible"></fieldset>
        <!-- <legend>Arrivée de la PDO</legend> -->
        <?php
            echo $default->subform(
                array(
                    'Propopdo.typepdo_id' => array( 'label' =>  ( __d( 'propopdo', 'Propopdo.typepdo_id', true ) ), 'type' => 'select', 'options' => $typepdo, 'empty' => true ),
                    'Propopdo.datereceptionpdo' => array( 'label' =>  ( __( 'Date de réception de la PDO', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => false ),
//                     'Propopdo.choixpdo' => array( 'label' =>  ( __( 'Choix', true ) ), 'type' => 'radio', 'options' => $options['choixpdo'], 'empty' => true ),
                    'Propopdo.originepdo_id' => array( 'label' =>  ( __( 'Origine', true ) ), 'type' => 'select', 'options' => $originepdo, 'empty' => true ),
                   'Propopdo.orgpayeur' => array( 'label' =>  ( __d( 'propopdo', 'Propopdo.orgpayeur', true ) ), 'type' => 'select', 'options' => $orgpayeur, 'empty' => true ),
                   'Propopdo.serviceinstructeur_id' => array( 'label' =>  ( __d( 'propopdo', 'Propopdo.serviceinstructeur_id', true ) ), 'type' => 'select', 'options' => $serviceinstructeur, 'empty' => true )
                ),
                array(
                    'domain' => $domain,
                    'options' => $options
                )
            );

        ?>
    </fieldset>

    <fieldset id="Etatpdo1" class="invisible"></fieldset>

    <fieldset>
        <!--<legend>Prise de décision</legend>-->
        <table class="noborder" id="infosPdo">
            <tr>
                <td class="mediumSize noborder">
                    <?php
                        echo $xform->input( 'Situationpdo.Situationpdo', array( 'type' => 'select', 'label' => 'Motif de la PDO', 'multiple' => 'checkbox' , 'options' => $situationlist ) );
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

            /*echo $xhtml->tag(
                'p',
                'Catégories : '
            );

            echo $default->subform(
                array(
                    'Propopdo.categoriegeneral' => array( 'label' => __d( 'propopdo', 'Propopdo.categoriegeneral', true ), 'type' => 'select', 'empty' => true, 'options' => $categoriegeneral ),
                    'Propopdo.categoriedetail' => array( 'label' => __d( 'propopdo', 'Propopdo.categoriedetail', true ), 'type' => 'select', 'empty' => true, 'options' => $categoriedetail ),
                    'Propopdo.iscomplet' => array( 'legend' => false, 'type' => 'radio', 'options' => $options['iscomplet'] )
                ),
                array(
                    'domain' => $domain,
                    'options' => $options
                )
            );*/

            echo $default->subform(
                array(
                    'Propopdo.iscomplet' => array( 'legend' => false, 'type' => 'radio', 'options' => $options['iscomplet'] )
                ),
                array(
                    'domain' => $domain,
                    'options' => $options
                )
            );

            /// FIXME: à corriger car pas bon
            echo $ajax->observeField( 'PropopdoIscompletCOM', array( 'update' => 'Etatpdo2', 'url' => Router::url( array( 'action' => 'ajaxetatpdo' ), true ) ) );
            echo $ajax->observeField( 'PropopdoIscompletINC', array( 'update' => 'Etatpdo2', 'url' => Router::url( array( 'action' => 'ajaxetatpdo' ), true ) ) );

        ?>
        <fieldset id="FicheCalcul" class="invisible">
            <?php
                echo 'Fiche de calcul';
            ?>
        </fieldset>
    </fieldset>

    <fieldset id="Etatpdo2" class="invisible"></fieldset>

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
                        //'Propopdo.dateenvoiop' => array( 'label' =>  ( __( 'Date d\'envoi à l\'OP', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => false ),
                        //'Propopdo.motifpdo' => array( 'label' =>  ( __( 'Motif de la décision', true ) ), 'type' => 'radio', 'options' => $motifpdo, 'empty' => true )
                    ),
                    array(
                        'domain' => $domain,
                        'options' => $options
                    )
                );
            ?>
                <!--<fieldset id="nonadmis" class="invisible">
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
                </fieldset>-->
            <?php
                echo $default->subform(
                    array(

                        'Propopdo.commentairepdo' => array( 'label' =>  'Observation : ', 'type' => 'textarea', 'rows' => 3 ),
                     ),
                    array(
                        'domain' => $domain,
                        'options' => $options
                    )
                );
                echo $ajax->observeField( 'PropopdoDecisionpdoId', array( 'update' => 'Etatpdo3', 'url' => Router::url( array( 'action' => 'ajaxetatpdo' ), true ) ) );
            ?>
        </fieldset>

    </fieldset>

    <fieldset id="Etatpdo3" class="invisible"></fieldset>
    <fieldset>
        <?php
            echo $form->input( 'Propopdo.isvalidation', array( 'label' => 'Validation', 'type' => 'checkbox' ) );
        ?>
        <fieldset id="Isvalidation" class="invisible">
            <?php
                echo $default->subform(
                    array(
                        'Propopdo.validationdecision' => array( 'label' =>  ( __d( 'propopdo', 'Propopdo.validationdecision', true ) ), 'type' => 'radio', 'options' => $options['validationdecision'] ),
                        'Propopdo.datevalidationdecision' => array( 'label' =>  ( __d( 'propopdo', 'Propopdo.datevalidationdecision', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear' => date('Y')+5, 'minYear' => date('Y')-1, 'empty' => false )
                    ),
                    array(
                        'domain' => $domain,
                        'options' => $options
                    )
                );
                echo $ajax->observeField( 'PropopdoIsvalidation', array( 'update' => 'Etatpdo4', 'url' => Router::url( array( 'action' => 'ajaxetatpdo' ), true ) ) );
            ?>
        </fieldset>
    </fieldset>

    <fieldset id="Etatpdo4" class="invisible"></fieldset>

    <fieldset>
        <?php
            echo $form->input( 'Propopdo.isdecisionop', array( 'label' => 'Décison de l\'OP', 'type' => 'checkbox' ) );
        ?>
        <fieldset id="Decisionop" class="invisible">
        <?php
            echo $default->subform(
                array(
                    'Propopdo.decisionop' => array( 'legend' => false, 'type' => 'radio', 'options' => $options['decisionop'] ),
                    'Propopdo.datedecisionop' => array( 'label' =>  ( __d( 'propopdo', 'Propopdo.datedecisionop', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear' => date('Y')+5, 'minYear' => date('Y')-1, 'empty' => false ),
                    'Propopdo.observationop' => array( 'label' => __d( 'propopdo', 'Propopdo.observationop', true ), 'type' => 'textarea', 'rows' => 3 )
                ),
                array(
                    'domain' => $domain,
                    'options' => $options
                )
            );
            echo $ajax->observeField( 'PropopdoIsdecisionop', array( 'update' => 'Etatpdo5', 'url' => Router::url( array( 'action' => 'ajaxetatpdo' ), true ) ) );
        ?>
        </fieldset>
    </fieldset>
    <fieldset id="Etatpdo5" class="invisible"></fieldset>


    <!--<fieldset>
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
    <fieldset id="Etatpdo6" class="invisible"></fieldset>-->
    </div>
    <div class="submit">
        <?php echo $form->submit( 'Enregistrer', array( 'div' => false ) );?>
        <?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
    </div>

    <?php echo $xform->end();?>
    <?php
        //echo $ajax->observeForm( 'propopdoform', array( 'update' => 'Etatpdo6', 'url' => Router::url( array( 'action' => 'ajaxetatpdo' ), true ) ) );
    ?>
</div>
<div class="clearer"><hr /></div>
