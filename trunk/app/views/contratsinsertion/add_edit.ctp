<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'CER';?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => Set::classicExtract( $personne, 'Personne.id' ) ) );?>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout d\'un CER';
    }
    else {
        $this->pageTitle = 'Édition d\'un CER';
    }
?>

<?php
    function value( $array, $index ) {
        $keys = array_keys( $array );
        $index = ( ( $index == null ) ? '' : $index );
        if( @in_array( $index, $keys ) && isset( $array[$index] ) ) {
            return $array[$index];
        }
        else {
            return null;
        }
    }
?>
<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php
        if( $this->action == 'add' ) {

            echo $form->create( 'Contratinsertion', array( 'type' => 'post', 'id' => 'testform', 'url' => Router::url( null, true ) ) );
            echo '<div>';
            echo $form->input( 'Contratinsertion.id', array( 'type' => 'hidden', 'value' => '' ) );
//             if( empty( $orientstruct ) && empty( $personne_referent ) ) {
//             echo $form->input( 'Contratinsertion.structurereferente_id', array( 'type' => 'hidden', 'id' => 'structId', 'value' => Set::classicExtract( $this->data, 'Structurereferente.id' ) ) );
//             }
//             else{
//                 echo $form->input( 'Contratinsertion.structurereferente_id', array( 'type' => 'hidden', 'id' => 'structId', 'value' => Set::classicExtract( $struct, 'Structurereferente.id' ) ) );
//             }

            echo $form->input( 'Contratinsertion.personne_id', array( 'type' => 'hidden', 'value' => Set::classicExtract( $personne, 'Personne.id' ) ) );
            echo $form->input( 'Contratinsertion.rg_ci', array( 'type' => 'hidden'/*, 'value' => '' */) );
            echo '</div>';

        }
        else {
            echo $form->create( 'Contratinsertion', array( 'type' => 'post', 'id' => 'testform', 'url' => Router::url( null, true ) ) );
            echo '<div>';
            echo $form->input( 'Contratinsertion.id', array( 'type' => 'hidden' ) );

            echo $form->input( 'Contratinsertion.personne_id', array( 'type' => 'hidden', 'value' => Set::classicExtract( $personne, 'Personne.id' ) ) );
            //echo $form->input( 'Suspensiondroit.id', array( 'type' => 'hidden' ) );
//             echo $form->input( 'Suspensiondroit.dossier_rsa_id', array( 'type' => 'hidden', 'value' => $dossier_id ) );
            echo '</div>';
        }


    ?>

<!--/************************************************************************/ -->

<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsOnValue( 'ContratinsertionRgCi', [ 'ContratinsertionTypocontratId' ], 1, true );
    });
</script>
<!--/************************************************************************/ -->
    <?php echo $javascript->link( 'dependantselect.js' ); ?>
    <script type="text/javascript">
        document.observe("dom:loaded", function() {
            dependantSelect( 'ContratinsertionReferentId', 'ContratinsertionStructurereferenteId' );
        });
    </script>
<!--/************************************************************************/ -->
<script type="text/javascript">
    function checkDatesToRefresh() {
        if( ( $F( 'ContratinsertionDdCiMonth' ) ) && ( $F( 'ContratinsertionDdCiYear' ) ) && ( $F( 'ContratinsertionDureeEngag' ) ) ) {
            var correspondances = new Array();
            // FIXME: voir pour les array associatives
             //$duree_engag_cg66
            <?php
                $duree_engag = 'duree_engag_'.Configure::read( 'nom_form_ci_cg' );
                foreach( $$duree_engag as $index => $duree ):?>correspondances[<?php echo $index;?>] = <?php echo str_replace( ' mois', '' ,$duree );?>;<?php endforeach;?>

            setDateInterval( 'ContratinsertionDdCi', 'ContratinsertionDfCi', correspondances[$F( 'ContratinsertionDureeEngag' )], false );
            //INFO: setDateInterval2 permet de conserver le jour lors du choix de la durée
            //      setDateInterval affiche le dernier jour du mois lors du choix de la durée
        }
    }

    document.observe( "dom:loaded", function() {
        Event.observe( $( 'ContratinsertionDdCiDay' ), 'change', function() {
            checkDatesToRefresh();
        } );
        Event.observe( $( 'ContratinsertionDdCiMonth' ), 'change', function() {
            checkDatesToRefresh();
        } );
        Event.observe( $( 'ContratinsertionDdCiYear' ), 'change', function() {
            checkDatesToRefresh();
        } );

        Event.observe( $( 'ContratinsertionDureeEngag' ), 'change', function() {
            checkDatesToRefresh();
        } );

        // form, radioName, fieldsetId, value, condition, toggleVisibility
        observeDisableFieldsetOnRadioValue(
            'testform',
            'data[Contratinsertion][forme_ci]',
            $( 'Contratsuite' ),
            'C',
            false,
            true
        );

        observeDisableFieldsOnRadioValue(
            'testform',
            'data[Contratinsertion][raison_ci]',
            [
                'SituationdossierrsaDtclorsaDay',
                'SituationdossierrsaDtclorsaMonth',
                'SituationdossierrsaDtclorsaYear',
                'SituationdossierrsaId',
                'SituationdossierrsaDossierRsaId',
                'ContratinsertionAvisraisonRadiationCiD',
                'ContratinsertionAvisraisonRadiationCiN'
            ],
            'R',
            true
        );

        observeDisableFieldsOnRadioValue(
            'testform',
            'data[Contratinsertion][raison_ci]',
            [
                'ContratinsertionAvisraisonSuspensionCiD',
                'ContratinsertionAvisraisonSuspensionCiN',
                'SuspensiondroitDdsusdrorsaDay',
                'SuspensiondroitDdsusdrorsaMonth',
                'SuspensiondroitDdsusdrorsaYear',
                'SuspensiondroitSituationdossierrsaId'
            ],
            'S',
            true
        );


        <?php
        $ref_id = Set::extract( $this->data, 'Contratinsertion.referent_id' );
            echo $ajax->remoteFunction(
                array(
                    'update' => 'StructurereferenteRef',
                    'url' => Router::url(
                        array(
                            'action' => 'ajaxstruct',
                            Set::extract( $this->data, 'Contratinsertion.structurereferente_id' )
                        ),
                        true
                    )
                )
            ).';';
            echo $ajax->remoteFunction(
                array(
                    'update' => 'ReferentRef',
                    'url' => Router::url(
                        array(
                            'action' => 'ajaxref',
                            Set::extract( $this->data, 'Contratinsertion.referent_id' )
                        ),
                        true
                    )
                )
            ).';';
        ?>
    } );
</script>

<fieldset>
    <table class="wide noborder">
        <tr>
            <td class="mediumSize noborder">
                <strong>Statut de la personne : </strong><?php echo Set::extract( $rolepers, Set::extract( $personne, 'Prestation.rolepers' ) ); ?>
                <br />
                <strong>Nom : </strong><?php echo Set::enum( Set::classicExtract( $personne, 'Personne.qual') , $qual ).' '.Set::classicExtract( $personne, 'Personne.nom' );?>
                <br />
                <strong>Prénom : </strong><?php echo Set::classicExtract( $personne, 'Personne.prenom' );?>
                <br />
                <strong>Date de naissance : </strong><?php echo date_short( Set::classicExtract( $personne, 'Personne.dtnai' ) );?>
            </td>
            <td class="mediumSize noborder">
                <strong>N° Service instructeur : </strong><?php echo Set::classicExtract( $personne, 'Serviceinstructeur.lib_service');?>
                <br />
                <strong>N° demandeur : </strong><?php echo Set::classicExtract( $personne, 'Dossier.numdemrsa' );?>
                <br />
                <strong>N° CAF/MSA : </strong><?php echo Set::classicExtract( $personne, 'Dossier.matricule' );?>
                <br />
                <strong>Inscrit au Pôle emploi</strong>
                <?php
                    $isPoleemploi = Set::classicExtract( $personne, 'Activite.act' );
                    if( $isPoleemploi == 'ANP' )
                        echo 'Oui';
                    else
                        echo 'Non';
                ?>
                <br />
                <strong>N° identifiant : </strong><?php echo Set::classicExtract( $personne, 'Personne.idassedic' );?>
            </td>
        </tr>
        <tr>
            <td class="mediumSize noborder">
                <strong>Adresse : </strong><br /><?php echo Set::classicExtract( $personne, 'Adresse.numvoie' ).' '.Set::enum( Set::classicExtract( $personne, 'Adresse.typevoie' ), $typevoie ).' '.Set::classicExtract( $personne, 'Adresse.nomvoie' ).'<br /> '.Set::classicExtract( $personne, 'Adresse.codepos' ).' '.Set::classicExtract( $personne, 'Adresse.locaadr' );?>
            </td>
            <td class="mediumSize noborder">
                <?php if( Set::extract( $personne, 'Modecontact.0.autorutitel' ) == 'A' ):?>
                        <strong>Numéro de téléphone 1 : </strong><?php echo Set::extract( $personne, 'Modecontact.0.numtel' );?>
                <?php endif;?>
                <?php if( Set::extract( $personne, 'Modecontact.1.autorutitel' ) == 'A' ):?>
                        <br />
                        <strong>Numéro de téléphone 2 : </strong><?php echo Set::extract( $personne, 'Modecontact.1.numtel' );?>
                <?php endif;?>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="mediumSize noborder">
            <?php if( Set::extract( $personne, 'Modecontact.0.autorutiadrelec' ) == 'A' ):?>
                <strong>Adresse mail : </strong><?php echo Set::extract( $personne, 'Modecontact.0.adrelec' );?> <!-- FIXME -->
            <?php endif;?>
            </td>
        </tr>
    </table>
</fieldset>

<fieldset>
    <legend>Type de Contrat</legend>
        <table class="wide noborder">
            <tr>
                <td class="noborder">
                    <?php
                        $error = Set::classicExtract( $this->validationErrors, 'Contratinsertion.forme_ci' );
                        $class = 'radio'.( !empty( $error ) ? ' error' : '' );

                        $thisDataFormeCi = Set::classicExtract( $this->data, 'Contratinsertion.forme_ci' );
                        if( !empty( $thisDataFormeCi ) ) {
                            $valueFormeci = $thisDataFormeCi;
                        }
                        $input =  $form->input( 'Contratinsertion.forme_ci', array( 'type' => 'radio' , 'options' => $forme_ci, /*'div' => false,*/ 'legend' => required( __( 'forme_ci', true )  ), 'value' => $valueFormeci ) );

                        echo $html->tag( 'div', $input, array( 'class' => $class ) );
                    ?>
                </td>
            </tr>
            <tr>
                <td class="noborder" colspan="2">
                    <strong>Date d'ouverture du droit ( RMI, API, rSa ) : </strong><?php echo date_short( Set::classicExtract( $personne, 'Dossier.dtdemrsa' ) );?>
                </td>
            </tr>
            <tr>
                <td class="mediumSize noborder">
                    <strong>Ouverture de droit ( nombre d'ouvertures ) : </strong><?php echo count( Set::extract( $personne, '/Foyer/Dossier/dtdemrsa' ) );?>
                </td>
                <td class="mediumSize noborder">
                    <strong>rSa majoré</strong>
                    <?php
                        $soclmajValues = array_unique( Set::extract( $personne, '/Foyer/Dossier/Infofinanciere/natpfcre' ) );
                        if( array_intersects( $soclmajValues, array_keys( $soclmaj ) )   )
                            echo 'Oui';
                        else
                            echo 'Non';
                    ?>
                </td>
            </tr>

            <?php if( Configure::read( 'nom_form_ci_cg' ) == 'cg66' ):?>
                <tr>
                    <td class="noborder">
                        <?php
                            echo $xform->input( 'Contratinsertion.num_contrat', array( 'label' => false , 'type' => 'select', 'options' => $options['num_contrat'], 'empty' => true, 'value' => $tc ) );
//                             echo $tc;
                        ?>
                    </td>
                    <td class="noborder">
                        <?php echo '(nombre de renouvellement) : '.$nbrCi;?>
                    </td>
                </tr>
            <?php endif;?>

            <?php if( Configure::read( 'nom_form_ci_cg' ) == 'cg93' ):?>
                <tr>
                    <td class="noborder">
                        <?php
                            echo $xform->input( 'Contratinsertion.num_contrat', array( 'label' => false , 'type' => 'hidden', 'value' => $tc ) );
                            echo Set::enum( $tc, $options['num_contrat'] );

                        ?>
                    </td>
                    <td class="noborder">
                        <?php echo '(nombre de renouvellement) : '.$nbrCi;?>
                    </td>
                </tr>
            <?php endif;?>
        </table>
    </fieldset>
    <fieldset id="Contratsuite">
        <table class="wide noborder">
            <tr>
                <td colspan="2" class="noborder center" id="contrat">
                    <em>Ce contrat est établi pour : </em>
                </td>
            </tr>
            <?php /*if( Configure::read( 'nom_form_ci_cg' ) == 'cg66' ): */?>
                <tr>
                    <td colspan="2" class="noborder">
                        <div class="demi"><?php echo $form->input( 'Contratinsertion.type_demande', array( 'label' => 'Raison : ' , 'type' => 'radio', 'div' => false, 'separator' => '</div><div class="demi">', 'options' => $options['type_demande'], 'legend' => false ) );?></div>
                    </td>
                </tr>
            <?php /*endif;*/?>

            <tr>
                <td colspan="2" class="noborder center" id="contrat">
                    <em>Ce contrat fait suite à : </em>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="noborder">
                    <div class="demi"><?php echo $form->input( 'Contratinsertion.raison_ci', array( 'label' => 'Raison : ' , 'type' => 'radio', 'div' => false, 'separator' => '</div><div class="demi">', 'options' => $raison_ci, 'legend' => false ) );?></div>
                </td>
            </tr>
            <tr>
                <td class="noborder">
                    <?php
                        $suspensiondroit_id = Set::classicExtract( $this->data, 'Suspensiondroit.id' );
                        if( !empty( $suspensiondroit_id ) ) {
                            echo $form->input( 'Suspensiondroit.id', array( 'type' => 'hidden' ) );
                        }

                        echo $form->input( 'Suspensiondroit.situationdossierrsa_id', array( 'type' => 'hidden', 'value' => $situationdossierrsa_id ) );

                        echo $form->input( 'Suspensiondroit.ddsusdrorsa', array( 'label' => false, 'type' => 'date' , 'dateFormat' => 'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => true ) );
                    ?>
                </td>
                <td class="noborder">
                    <?php
                        echo $form->input( 'Situationdossierrsa.id', array( 'type' => 'hidden', 'value' => $situationdossierrsa_id ) );
                        echo $form->input( 'Situationdossierrsa.dossier_rsa_id', array( 'type' => 'hidden', 'value' => $dossier_id ) );

                        echo $form->input( 'Situationdossierrsa.dtclorsa', array( 'label' => false, 'type' => 'date' , 'dateFormat' => 'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => true ) );
                    ?>
                </td>
            </tr>
            <tr>
                <td class="noborder">
                    <?php echo $form->input( 'Contratinsertion.avisraison_suspension_ci', array( 'type' => 'radio', 'separator' => '<br />', 'options' => $avisraison_ci, 'legend' => false,   ) );?>
                </td>
                <td class="noborder">
                    <?php echo $form->input( 'Contratinsertion.avisraison_radiation_ci', array( 'type' => 'radio', 'separator' => '<br />', 'options' => $avisraison_ci, 'legend' => false ) );?>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="noborder center">
                    <em><strong>Lorsque le contrat conditionne l'ouverture du droit, il ne sera effectif qu'après décision du Président du Conseil Général</strong></em>
                </td>
            </tr>
        </table>
</fieldset>




<fieldset>
    <legend>Type d'orientation</legend>
    <table class="wide noborder">
        <tr>
            <td class="noborder">
                <strong>Nom de l'organisme de suivi :</strong>
                <?php echo $xform->input( 'Contratinsertion.structurereferente_id', array( 'label' => false, 'type' => 'select', 'options' => $structures, 'selected' => $struct_id, 'empty' => true ) );?>
                <?php echo $ajax->observeField( 'ContratinsertionStructurereferenteId', array( 'update' => 'StructurereferenteRef', 'url' => Router::url( array( 'action' => 'ajaxstruct' ), true ) ) ); ?> 
            </td>
            <td class="noborder">
                <strong>Nom du référent chargé du suivi :</strong>
                <?php echo $xform->input( 'Contratinsertion.referent_id', array('label' => false, 'type' => 'select', 'options' => $referents, 'empty' => true, 'selected' => $struct_id.'_'.$referent_id ) );?>
                <?php echo $ajax->observeField( 'ContratinsertionReferentId', array( 'update' => 'ReferentRef', 'url' => Router::url( array( 'action' => 'ajaxref' ), true ) ) ); ?> 
            </td>
        </tr>
        <tr>
            <td class="wide noborder"><div id="StructurereferenteRef"></div></td>

            <td class="wide noborder"><div id="ReferentRef"></div></td>
        </tr>
    </table>

</fieldset>

<script type="text/javascript">
    Event.observe( $( 'ContratinsertionStructurereferenteId' ), 'change', function( event ) {
        $( 'ReferentRef' ).update( '' );
    } );
</script>



<fieldset class="loici">
    <p>
        Loi N°2008-1249 du 1er Décembre, généralisant le revenu de solidarité active et réformant les politiques d'engagement réciproque : <strong>Contrat librement débattu avec engagements réciproques</strong> ( articles L.263.35 et L.262.36 )<br />
        <strong>Respect du Contrat</strong> ( Article L-262-37 1° et 2° ) :<br />
        <em>"Sauf décision prise au regard de la situation particulière du bénéficiaire, le versement du revenu de solidarité active est suspendu, en tout ou partie, par le Président du Conseil Général :<br />
        lorsque, du fait du bénéficiaire et sans motif légitime, le projet personnalisé d'accès à l'emploi ou l'un des contrats mentionnés aux articles L.262-35 et L.262-36 ne sont pas établis dans les délais prévus ou ne sont pas renouvelés.<br />
        lorsque, sans motif légitime, les dispositions du projet personnalisé d'accès à l'emploi ou les stipulations de l'un des contrats mentionnés aux articles L.262-35 et L.262-36 ne sont pas respectés par le bénéficiaire."<br />
        </em>
        <strong>Lorsque le bénéficiaire ne respecte pas les conditions de ce contrat, l'organisme signataire le signale au Président du conseil Général.</strong>
    </p>
</fieldset>

<?php include 'add_edit_specif_'.Configure::read( 'nom_form_ci_cg' ).'.ctp';?>

<fieldset class="cnilci">
    <p>
        <em>Conformément à la loi "Informatique et liberté" n°78-17 du 06 janvier 1978 relative à l'informatique, aux fichiers et aux libertés nous nous engageons à prendre toutes les précautions afin de préserver la sécurité de ces informations et notamment empêcher qu'elles soient déformées, endommagées ou communiquées à des tiers. Les coordonnées informations liées à l'adresse, téléphone et mail seront utilisées uniquement pour permettre la prise de contact, dans le cadre du parcours d'engagement réciproque.</em>
    </p>
</fieldset>
    <div class="submit">
        <?php echo $form->submit( 'Enregistrer', array( 'div' => false ) );?>
        <?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
    </div>
    <?php echo $form->end();?>
</div>

<div class="clearer"><hr /></div>

<?php /*debug( array_keys( $this->viewVars ) );*/?>