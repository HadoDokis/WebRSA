<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Contrats d\'engagement réciproque';?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => Set::classicExtract( $personne, 'Personne.id' ) ) );?>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout d\'un contrat d\'engagement réciproque';
    }
    else {
        $this->pageTitle = 'Édition d\'un contrat d\'engagement réciproque';
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
            if( empty( $orientstruct ) && empty( $personne_referent ) ) {
                echo $form->input( 'Contratinsertion.structurereferente_id', array( 'type' => 'hidden', 'id' => 'structId', 'value' => Set::classicExtract( $this->data, 'Structurereferente.id' ) ) );
            }
            else{
                echo $form->input( 'Contratinsertion.structurereferente_id', array( 'type' => 'hidden', 'id' => 'structId', 'value' => Set::classicExtract( $struct, 'Structurereferente.id' ) ) );
            }

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

            setDateInterval2( 'ContratinsertionDdCi', 'ContratinsertionDfCi', correspondances[$F( 'ContratinsertionDureeEngag' )], true );
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
                <strong>N° demandeur : </strong><?php echo Set::classicExtract( $personne, 'Foyer.Dossier.numdemrsa' );?>
                <br />
                <strong>N° CAF/MSA : </strong><?php echo Set::classicExtract( $personne, 'Foyer.Dossier.matricule' );?>
                <br />
                <strong>Inscrit au Pôle emploi</strong>
                <?php
                    $isPoleemploi = Set::classicExtract( $personne, 'Activite.0.act' );
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
                <strong>Adresse : </strong><br /><?php echo Set::extract( $personne, 'Adresse.numvoie' ).' '.Set::extract( $typevoie, Set::extract( $personne, 'Adresse.typevoie' ) ).' '.Set::extract( $personne, 'Adresse.nomvoie' ).'<br /> '.Set::extract( $personne, 'Adresse.codepos' ).' '.Set::extract( $personne, 'Adresse.locaadr' );?>
            </td>
            <td class="mediumSize noborder">
                <strong>Téléphone : </strong><?php echo Set::extract( $personne, 'Foyer.Modecontact.0.numtel' );?>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="mediumSize noborder">
                <strong>Adresse mail : </strong><?php echo Set::extract( $personne, 'Foyer.Modecontact.0.adrelec' );?> <!-- FIXME -->
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

                        $input =  $form->input( 'Contratinsertion.forme_ci', array( 'type' => 'radio' , 'options' => $forme_ci, 'div' => false, 'legend' => required( __( 'forme_ci', true )  ), 'value' => $valueFormeci/*( empty( $forme_ci ) ? $forme_ci : 'S' )*/ ) );

                        echo $html->tag( 'div', $input, array( 'class' => $class ) );
                    ?>
                </td>
            </tr>
            <tr>
                <td class="noborder" colspan="2">
                    <strong>Date d'ouverture du droit ( RMI, API, rSa ) : </strong><?php echo date_short( Set::classicExtract( $personne, 'Foyer.Dossier.dtdemrsa' ) );?>
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
            <tr>
                <td class="noborder">
                    <?php
                        echo $xform->input( 'Contratinsertion.numcontrat', array( 'label' => false , 'type' => 'hidden', 'value' => $tc ) );
                        echo $tc;

                    ?>
                </td>
                <td class="noborder">
                    <?php echo '(nombre de renouvellement) : '.$nbrCi;?>
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset id="Contratsuite">
        <table class="wide noborder">
            <tr>
                <td colspan="2" class="noborder center">
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
    <legend>Type d'Orientation</legend>
        <table class="wide noborder">
            <tr>
                <td class="mediumSize noborder" colspan="3">
                    <strong>Type d'orientation:</strong>
                    <?php
                        if( !empty( $orientstruct ) ) {
                            echo $typeOrientation;
                        }

                        //echo Set::classicExtract( $struct, 'Structurereferente.typeorient_id' );

                    ?>
                </td>
            </tr>
            <tr>
                <td class="noborder" colspan="2">
                   <?php
                        if( empty( $orientstruct ) && !empty( $personne_referent ) ){
                            echo '<strong>Nom de l\'organisme de suivi '.REQUIRED_MARK.':</strong><br />'. value( $sr, Set::classicExtract( $personne_referent, 'PersonneReferent.structurereferente_id' ) );
                        }
                        else if( empty( $orientstruct ) && empty( $personne_referent ) ) {
                            echo $form->input( 'Contratinsertion.structurereferente_id', array( 'label' => __( '<em>Nom de l\'organisme de suivi '.REQUIRED_MARK.'</em>', true ), 'type' => 'select' , 'options' => $sr, 'empty' => true ) );
                        }
                    ?>
                    <?php if( !empty( $orientstruct ) ):?>
                        <strong>Nom de l'organisme de suivi :</strong>
                            <?php
                                echo Set::classicExtract( $struct, 'Structurereferente.lib_struc' );
                            ?>
                    <?php endif;?>
                </td>
                <td class="noborder">
<!--<<<<<<< .mine
                    <?php
                        echo $form->input( 'Contratinsertion.referent_id', array( 'label' => __( '<em>Nom du référent</em>', true ), 'type' => 'select' , 'options' => $referents, 'empty' => true ) );
					?>
=======-->
                    <?php
                        if( empty( $personne_referent ) && $this->action == 'add' ){
                            echo $form->input( 'Contratinsertion.referent_id', array( 'label' => __( '<em>Nom du référent</em>', true ), 'type' => 'select' , 'options' => $refstruct, 'empty' => true ) );
                        }
                        else if( empty( $personne_referent ) && $this->action == 'edit' ){
                            echo $form->input( 'Contratinsertion.referent_id', array( 'label' => __( '<em>Nom du référent</em>', true ), 'type' => 'select' , 'options' => $refstruct, 'selected' => $struct_id.'_'.$referent_id, 'empty' => true ) );
                        }
                    ?>
                    <?php if( !empty( $personne_referent ) ):?>
                        <strong>Nom du référent chargé du suivi :</strong> <br />
                            <?php
                                echo value( $refs, Set::classicExtract( $personne, 'PersonneReferent.referent_id' ) );
                            ?>
                    <?php endif;?>
                </td>
            </tr>
            <tr>
                <td class="textArea noborder">
                    <?php
                        if( empty( $orientstruct ) && empty( $personne_referent ) ){
                            echo $html->tag(
                                'p',
                                $html->tag( 'em', 'Coordonnées de l\'organisme' ).'<br />'.
                                $html->tag( 'span', ( isset( $StructureAdresse ) ? $StructureAdresse : ' ' ), array( 'id' => 'StructureAdresse' ) )
                            );
                            echo $ajax->observeField( 'ContratinsertionStructurereferenteId', array( 'update' => 'StructureAdresse', 'url' => Router::url( array( 'action' => 'ajaxstructadr' ), true ) ) );
                        }
                        else if( empty( $orientstruct ) && !empty( $personne_referent ) ){

                            echo $html->tag(
                                'p',
                                $html->tag( 'em', 'Coordonnées de l\'organisme' ).'<br />'.
                                Set::classicExtract( $struct, 'Structurereferente.num_voie' ).' '.
                                Set::classicExtract( $typevoie, Set::classicExtract( $struct, 'Structurereferente.type_voie' ) ).' '.
                                Set::classicExtract( $struct, 'Structurereferente.nom_voie' ).'<br />'.
                                Set::classicExtract( $struct, 'Structurereferente.code_postal' ).' '.
                                Set::classicExtract( $struct, 'Structurereferente.ville' )
                            );
                        }
                        else if( !empty( $orientstruct ) ){
                            echo $html->tag(
                                'p',
                                $html->tag( 'em', 'Coordonnées de l\'organisme' ).'<br />'.
                                Set::classicExtract( $struct, 'Structurereferente.num_voie' ).' '.
                                Set::classicExtract( $typevoie, Set::classicExtract( $struct, 'Structurereferente.type_voie' ) ).' '.
                                Set::classicExtract( $struct, 'Structurereferente.nom_voie' ).'<br />'.
                                Set::classicExtract( $struct, 'Structurereferente.code_postal' ).' '.
                                Set::classicExtract( $struct, 'Structurereferente.ville' )
                            );
                        }
                    ?>
                    <?php echo $ajax->observeField( 'ContratinsertionStructurereferenteId', array( 'update' => 'StructureAdresse', 'url' => Router::url( array( 'action' => 'ajaxstructadr' ), true ) ) );?>
                </td>
                <td class="textArea noborder">
                    <?php
                        if( empty( $personne_referent ) ){
                            echo $html->tag(
                                'p',
                                $html->tag( 'em', 'Coordonnées du référent' ).'<br />'.
                                $html->tag( 'span', ( isset( $ReferentEmail ) ? $ReferentEmail : ' ' ), array( 'id' => 'ReferentEmail' ) )
                            );
                            echo $ajax->observeField( 'ContratinsertionReferentId', array( 'update' => 'ReferentEmail', 'url' => Router::url( array( 'action' => 'ajaxrefcoord' ), true ) ) );
                        }
                        else{
                            echo $html->tag(
                                'p',
                                $html->tag( 'em', 'Coordonnées du référent' ).'<br />'.
                                Set::classicExtract( $referent, 'Referent.email' ). '<br/>' .Set::classicExtract( $referent, 'Referent.numero_poste')
                            );
                        }
                    ?>
                </td>
                <td class="textArea noborder">
                    <?php
                        if( empty( $personne_referent ) ) {
                            echo $html->tag(
                                'p',
                                $html->tag( 'em', 'Fonction du référent' ).'<br />'.
                                $html->tag( 'span', ( isset( $ReferentFonction ) ? $ReferentFonction : ' ' ), array( 'id' => 'ReferentFonction' ) )
                            );
                            echo $ajax->observeField( 'ContratinsertionReferentId', array( 'update' => 'ReferentFonction', 'url' => Router::url( array( 'action' => 'ajaxreffonct' ), true ) ) );
                       }
                        else{
                            echo $html->tag(
                                'p',
                                $html->tag( 'em', 'Fonction du référent' ).'<br />'.
                                Set::classicExtract( $referent, 'Referent.fonction' )
                            );
                        }
                    ?>
                </td>
            </tr>
        </table>
</fieldset>
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