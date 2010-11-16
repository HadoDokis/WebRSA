<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
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
<?php echo $javascript->link( 'dependantselect.js' ); ?>
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        dependantSelect( 'ContratinsertionReferentId', 'ContratinsertionStructurereferenteId' );
    });
</script>

<script type="text/javascript">
    function checkDatesToRefresh() {
        if( ( $F( 'ContratinsertionDdCiMonth' ) ) && ( $F( 'ContratinsertionDdCiYear' ) ) && ( $F( 'ContratinsertionDureeEngag' ) ) ) {
            var correspondances = new Array();

            <?php
                $duree_engag = 'duree_engag_'.Configure::read( 'nom_form_ci_cg' );
                foreach( $$duree_engag as $index => $duree ):?>correspondances[<?php echo $index;?>] = <?php echo str_replace( ' mois', '' ,$duree );?>;<?php endforeach;?>

            setDateIntervalCer( 'ContratinsertionDdCi', 'ContratinsertionDfCi', correspondances[$F( 'ContratinsertionDureeEngag' )], false );
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
//             alert($F( 'ContratinsertionDureeEngag' ));
        } );

    });


</script>
<script type="text/javascript">
    document.observe( "dom:loaded", function() {


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

            echo $form->input( 'Contratinsertion.personne_id', array( 'type' => 'hidden', 'value' => Set::classicExtract( $personne, 'Personne.id' ) ) );
            echo $form->input( 'Contratinsertion.rg_ci', array( 'type' => 'hidden'/*, 'value' => '' */) );
            echo '</div>';

        }
        else {
            echo $form->create( 'Contratinsertion', array( 'type' => 'post', 'id' => 'testform', 'url' => Router::url( null, true ) ) );
            echo '<div>';
            echo $form->input( 'Contratinsertion.id', array( 'type' => 'hidden' ) );

            echo $form->input( 'Contratinsertion.personne_id', array( 'type' => 'hidden', 'value' => Set::classicExtract( $personne, 'Personne.id' ) ) );
            echo '</div>';
        }
    ?>
<fieldset>
   <!-- <fieldset class="loici">
        <p class="caution58 center">
            <strong>CONTRAT D’ENGAGEMENT RÉCIPROQUE<br /> REVENU DE SOLIDARITÉ ACTIVE (RSA)<br /> Conditions générales</strong>
        </p>
    </fieldset>
    <table class="noborder">
        <tr>
            <td class="noborder"><strong>VU</strong> le code de l’Action Sociale et des Familles </td>
        </tr>
        <tr>
            <td class="noborder"><strong>VU</strong> la loi n° 2008-1249 du 1er décembre 2008 généralisant le revenu de solidarité active et réformant les politiques d'insertion</td>
        </tr>
        <tr>
            <td class="noborder"><strong>VU</strong> le décret n° 2009-404 du 15 avril 2009 relative au Revenu de Solidarité Active</td>
        </tr>
    </table>

    <table class="noborder">
        <tr class="aere">
            <td class="noborder"><strong>Entre</strong></td>
        </tr>
        <tr class="aere">
            <td class="noborder">
                Le bénéficiaire ci-après désigné, <strong><?php /*echo Set::enum( Set::classicExtract( $personne, 'Personne.qual'), $qual ).' '.$personne['Personne']['nom'].' '.$personne['Personne']['prenom'];*/?></strong>
            </td>
        </tr>
        <tr class="aere">
            <td class="noborder"><strong>Et</strong> </td>
        </tr>
        <tr class="aere">
            <td class="noborder">le Département de la Nièvre, représenté par le Président du Conseil Général</td>
        </tr>
    </table>

    <table class="aere noborder">
        <tr>
            <td class="noborder"><strong>Il est convenu ce qui suit :</strong></td>
        </tr>
        <tr>
            <td class="noborder"><strong>Article 1 :</strong></td>
        </tr>
        <tr>
            <td class="noborder">Le présent contrat d’engagement réciproque a été débattu entre le référent unique désigné au verso et le bénéficiaire. Il est librement conclu par les parties et repose sur des engagements réciproques de leur part.</td>
        </tr>
        <tr>
            <td class="noborder"><strong>Article 2 :</strong></td>
        </tr>
        <tr>
            <td class="noborder">En contrepartie du versement du Revenu de Solidarité Active et des actions mises en œuvre ou financées par le Conseil Général pour faciliter la démarche d’insertion de l’allocataire et de ses ayant droits, le bénéficiaire s’engage à mettre en œuvre les moyens nécessaires à sa réinsertion, en particulier les actions définies aux conditions particulières figurant au verso.</td>
        </tr>
        <tr>
            <td class="noborder"><strong>Article 3 :</strong></td>
        </tr>
        <tr>
            <td class="noborder">Le référent unique s’engage à rencontrer régulièrement le bénéficiaire, à lui apporter toutes les informations et conseils nécessaires à l’atteinte des objectifs fixés et à effectuer les démarches relevant de sa compétence.</td>
        </tr>
        <tr>
            <td class="noborder"><strong>Article 4 :</strong></td>
        </tr>
        <tr>
            <td class="noborder">En vertu de l’article L262-37 du code de l’action sociale et des familles, le versement du revenu de solidarité active pourra être suspendu, en tout ou partie, par le président du conseil général :
                <ul>
                    <li>si le contrat d’insertion n’est pas établi ou renouvelé du fait du bénéficiaire, sans motif légitime</li>
                    <li>si le contrat d’insertion n’est pas respecté par le bénéficiaire, sans motif légitime</li>
                    <li>lorsque le bénéficiaire refuse de se soumettre aux contrôles prévus dans le cadre du RSA</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td class="noborder"><strong>Article 5 :</strong></td>
        </tr>
        <tr>
            <td class="noborder">Conformément à la loi du 6 janvier 1978 relative à l’informatique, aux fichiers et aux libertés, le bénéficiaire dispose d’un droit d’accès aux informations qui le concernent et de rectification des erreurs éventuelles. Par ailleurs, il lui appartient de signaler toute modification dans la composition de sa famille et de ses ressources.</td>
        </tr>
        <tr>
            <td class="noborder"><strong>Article 6 :</strong></td>
        </tr>
        <tr>
            <td class="noborder">Toute réclamation dirigée contre une décision relative au revenu de solidarité active fait l'objet, préalablement à l'exercice d'un recours contentieux, d'un recours administratif auprès du président du conseil général.</td>
        </tr>
    </table>

    <fieldset class="aere loici">
        <p class="caution58 center">
            <strong>CONTRAT D’ENGAGEMENT RÉCIPROQUE<br />REVENU DE SOLIDARITÉ ACTIVE (RSA)<br />Conditions particulières</strong>
        </p>
    </fieldset>

    <fieldset>
        <?php
            /*echo $default->subform(
                array(
                    'Contratinsertion.typeinsertion' => array( 'type' => 'radio', 'options' => $options['typeinsertion'] )
                ),
                array(
                    'options' => $options
                )
            );
        ?>
    </fieldset>

    <fieldset>
        <legend>BÉNÉFICIAIRE DU CONTRAT</legend>
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
                    <strong>Adresse : </strong><br /><?php echo Set::classicExtract( $personne, 'Adresse.numvoie' ).' '.Set::enum( Set::classicExtract( $personne, 'Adresse.typevoie' ), $typevoie ).' '.Set::classicExtract( $personne, 'Adresse.nomvoie' ).'<br /> '.Set::classicExtract( $personne, 'Adresse.codepos' ).' '.Set::classicExtract( $personne, 'Adresse.locaadr' );?>
                </td>
                <td class="mediumSize noborder">
                    <?php if( Set::extract( $personne, 'Foyer.Modecontact.0.autorutitel' ) == 'A' ):?>
                            <strong>Numéro de téléphone 1 : </strong><?php echo Set::extract( $personne, 'Foyer.Modecontact.0.numtel' );?>
                    <?php endif;?>
                    <?php if( Set::extract( $personne, 'Foyer.Modecontact.1.autorutitel' ) == 'A' ):?>
                            <br />
                            <strong>Numéro de téléphone 2 : </strong><?php echo Set::extract( $personne, 'Foyer.Modecontact.1.numtel' );?>
                    <?php endif;?>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="mediumSize noborder">
                <?php if( Set::extract( $personne, 'Foyer.Modecontact.0.autorutiadrelec' ) == 'A' ):?>
                    <strong>Adresse mail : </strong><?php echo Set::extract( $personne, 'Foyer.Modecontact.0.adrelec' );?> <!-- FIXME -->
                <?php endif;*/?>
                </td>
            </tr>
        </table>
    </fieldset>


-->


    <fieldset>
        <legend>RÉFÉRENT UNIQUE</legend>
        <table class="wide noborder">
            <tr>
                <td class="noborder">
                    <strong>Organisme chargé de l'instruction du dossier :</strong>
                    <?php echo $xform->input( 'Contratinsertion.structurereferente_id', array( 'label' => false, 'type' => 'select', 'options' => $structures, 'selected' => $struct_id, 'empty' => true ) );?>
                    <?php echo $ajax->observeField( 'ContratinsertionStructurereferenteId', array( 'update' => 'StructurereferenteRef', 'url' => Router::url( array( 'action' => 'ajaxstruct' ), true ) ) ); ?> 
                </td>
                <td class="noborder">
                    <strong>Nom du référent unique :</strong>
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

<!--

    <fieldset>
        <legend>BILAN DU PRÉCÉDENT CONTRAT</legend>
            <?php
                /*if( ( $nbContratsPrecedents != 0 ) ) {
                    echo $xform->input( 'Contratinsertion.objectifs_fixes', array( 'label' => 'Objectif du dernier contrat', 'value' => Set::classicExtract( $lastContrat, '0.Contratinsertion.objectifs_fixes' ) ) );

                    echo $xhtml->tag(
                        'p',
                        'Sur une durée de '.Set::enum( Set::classicExtract( $lastContrat, '0.Contratinsertion.duree_engag' ), $duree_engag_cg58 ),
                        array(
                            'class' => 'wideHeader mediumSize'
                        )
                    );

                    echo $xform->input( 'Contratinsertion.bilancontrat', array( 'label' => 'Bilan', 'value' => Set::classicExtract( $lastContrat, '0.Contratinsertion.bilancontrat' ) ) );
                    echo $xform->input( 'Contratinsertion.outilsmobilises', array( 'label' => 'Outils mobilisés' ) );
                }
                else {
                    echo '<p class="notice">Cette personne ne possède pas encore de Contrat d\'Engagement Réciproque</p>';
                }
            ?>

    </fieldset>

    <fieldset>
        <legend>ACTIONS PRÉVUES AU PRESENT CONTRAT</legend>
            <?php
                echo $default->subform(
                    array(
                        'Contratinsertion.nature_projet' => array( 'label' => 'Projet négocié' ),
                        'Contratinsertion.engag_object' => array( 'label' => 'Actions à réaliser par le bénéficiaire' ),
                        'Contratinsertion.engag_object_referent' => array( 'label' => 'Actions à réaliser par le référent' ),
                        'Contratinsertion.outilsamobiliser' => array( 'label' => 'Outils à mobiliser' ),
                    ),
                    array(
                        'options' => $options
                    )
                );
            ?>

    </fieldset>
    
    <fieldset>
        <legend>DANS LE CADRE  D’UN ACCOMPAGNEMENT PROFESSIONNEL</legend>
        <?php
            echo $default->subform(
                array(
                    'Contratinsertion.sect_acti_emp' => array( 'label' => 'Nature et caractéristiques de l’emploi recherché', 'type' => 'select', 'options' => $sect_acti_emp, 'empty' => true ),
                    'Contratinsertion.niveausalaire' => array( 'type' => 'text' ),
                    'Contratinsertion.zonegeographique_id' => array( 'label' => 'Zone géographique privilégiée', 'type' => 'select', 'options' => $zoneprivilegie, 'empty' => true ),
                ),
                array(
                    'options' => $options
                )
            );
        ?>
    </fieldset>
    <fieldset class="cnilci invisible">
        <p>
            Le bénéficiaire déclare avoir pris connaissance des conditions générales figurant au verso et s’engage à réaliser l’ensemble des actions prévues aux conditions particulières telles que définies ci-dessus avec l’appui de son référent. Il s’engage à informer son référent des démarches qu’il effectue ainsi que de tout changement dans sa situation.
        </p>
    </fieldset>
    <fieldset class="invisible">
        <table class="wide noborder">
            <tr>
                <td class="signature noborder center">
                    <strong>Le bénéficiaire du contrat</strong><br /><?php echo Set::enum( Set::classicExtract( $personne, 'Personne.qual'), $qual ).' '.$personne['Personne']['nom'].' '.$personne['Personne']['prenom'];?>
                </td>
                <td class="signature noborder center">
                    <strong>Pour le Président et par délégation</strong><br />
                    <p class="caution center">( précédé de la mention manuscrite "Lu et Approuvé" )</p>
                </td>
            </tr>
        </table>
        <br />
            <?php echo $form->input( 'Contratinsertion.lieu_saisi_ci', array( 'label' => __( 'lieu_saisi_ci', true ).REQUIRED_MARK, 'type' => 'text', 'maxlength' => 50 )  ); ?><br />
            <?php echo $form->input( 'Contratinsertion.date_saisi_ci', array( 'label' => __( 'date_saisi_ci', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10  )  ); */?>
    </fieldset>

</fieldset>

-->
    <!-- <fieldset>
            <legend>DÉCISION DE LA COMMISSION D’ORIENTATION ET DE VALIDATION</legend>
                <?php /*echo $form->input( 'Contratinsertion.observ_ci', array( 'label' => __( 'observ_ci', true ), 'type' => 'textarea', 'rows' => 6)  ); ?>
                <?php echo $form->input( 'Contratinsertion.decision_ci', array( 'label' => __( 'decision_ci', true ), 'type' => 'select', 'options' => $decision_ci ) ); ?>
                <?php echo $form->input( 'Contratinsertion.datevalidation_ci', array( 'label' => '', 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+2, 'minYear'=>date('Y')-2 , 'empty' => true)  );*/ ?>
        </fieldset> -->

    <fieldset>
        <legend>CARACTÉRISTIQUES DU PRÉSENT CONTRAT</legend>

        <?php echo $xform->input( 'Contratinsertion.num_contrat', array( 'label' => 'Type de contrat' , 'type' => 'select', 'options' => $options['num_contrat'], 'empty' => true, 'value' => $tc ) );?>

        <table class="nbrCi wide noborder">
            <tr class="nbrCi">
                <td class="noborder">Nombre de renouvellements </td>
                <td class="noborder"> <?php echo $nbrCi;?> </td>
            </tr>
        </table>

        <?php echo $xform->input( 'Contratinsertion.dd_ci', array( 'label' => __d( 'contratinsertion', 'Contratinsertion.dd_ci', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+2, 'minYear'=>date('Y')-2 , 'empty' => false ) );?>
        <?php echo $xform->input( 'Contratinsertion.duree_engag', array( 'label' => __d( 'contratinsertion', 'Contratinsertion.duree_engag', true ), 'type' => 'select', 'options' => $duree_engag_cg58, 'empty' => true ) );?>
        <?php echo $xform->input( 'Contratinsertion.df_ci', array( 'label' => __d( 'contratinsertion', 'Contratinsertion.df_ci', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+2, 'minYear'=>date('Y')-2 , 'empty' => true ) ) ;?>

    </fieldset>
        <?php echo $xform->input( 'Contratinsertion.date_saisi_ci', array( 'label' => __d( 'contratinsertion', 'Contratinsertion.date_saisi_ci', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+2, 'minYear'=>date('Y')-2  ) ) ;?>
</fieldset>

    <div class="submit">
        <?php echo $form->submit( 'Enregistrer', array( 'div' => false ) );?>
        <?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
    </div>
    <?php echo $form->end();?>
</div>

<div class="clearer"><hr /></div>