<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Contrats d\'insertion';?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout d\'un contrat d\'insertion';
    }
    else {
        $this->pageTitle = 'Édition d\'un contrat d\'insertion';
    }
?>

<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsOnValue( 'ContratinsertionRgCi', [ 'ContratinsertionTypocontratId1' ], 1, true );
    });
</script>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php 
        if( $this->action == 'add' ) {

            echo $form->create( 'Contratinsertion', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
            echo '<div>';
            echo $form->input( 'Contratinsertion.id', array( 'type' => 'hidden', 'value' => '' ) );
            echo $form->input( 'Contratinsertion.personne_id', array( 'type' => 'hidden', 'value' => $personne_id ) );
            echo $form->input( 'Contratinsertion.rg_ci', array( 'type' => 'hidden'/*, 'value' => '' */) );
            echo '</div>';

        }
        else {
            echo $form->create( 'Contratinsertion', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
            echo '<div>';
            echo $form->input( 'Contratinsertion.id', array( 'type' => 'hidden' ) );

            echo $form->input( 'Contratinsertion.personne_id', array( 'type' => 'hidden', 'value' => $personne_id ) );

            echo '</div>';
        }


    ?>
<!--/************************************************************************/ -->
<script type="text/javascript">
    function checkDatesToRefresh() {
        if( ( $F( 'ContratinsertionDdCiMonth' ) ) && ( $F( 'ContratinsertionDdCiYear' ) )&& ( $F( 'ContratinsertionDureeEngag' ) ) ) {
            var correspondances = new Array();
            // FIXME: voir pour les array associatives
            <?php foreach( $duree_engag as $index => $duree ):?>correspondances[<?php echo $index;?>] = <?php echo str_replace( ' mois', '' ,$duree );?>;<?php endforeach;?>

            setDateInterval( 'ContratinsertionDdCi', 'ContratinsertionDfCi', correspondances[$F( 'ContratinsertionDureeEngag' )], false );
        }
    }

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

        Event.observe( $( 'ContratinsertionDdCiMonth' ), 'change', function() {
            checkDatesToRefresh();
        } );
        Event.observe( $( 'ContratinsertionDdCiYear' ), 'change', function() {
            checkDatesToRefresh();
        } );
        Event.observe( $( 'ContratinsertionDureeEngag' ), 'change', function() {
            checkDatesToRefresh();
        } );

        observeDisableFieldsOnBoolean( 'ContratinsertionActionsPrev', [ 'ContratinsertionObstaRenc' ], '1', false );
        observeDisableFieldsOnBoolean( 'ContratinsertionEmpTrouv', [ 'ContratinsertionSectActiEmp', 'ContratinsertionEmpOccupe', 'ContratinsertionDureeHebdoEmp', 'ContratinsertionNatContTrav', 'ContratinsertionDureeCdd' ], '0', false );
        observeDisableFieldsOnValue( 'ContratinsertionNatContTrav', [ 'ContratinsertionDureeCdd' ], 'TCT3', false );

        observeDisableFieldsOnValue( 'ActioninsertionLibAction', [ 'Aidedirecte0TypoAide', 'Aidedirecte0LibAide', 'Aidedirecte0DateAideDay', 'Aidedirecte0DateAideMonth', 'Aidedirecte0DateAideYear' ], 'A', false );
        observeDisableFieldsOnValue( 'ActioninsertionLibAction', [ 'Prestform0LibPresta', 'RefprestaNomrefpresta', 'RefprestaPrenomrefpresta', 'Prestform0DatePrestaDay', 'Prestform0DatePrestaMonth', 'Prestform0DatePrestaYear' ], 'P', false );
    } );
</script>

<fieldset>
    <table class="wide noborder">
        <tr>
            <td class="mediumSize noborder">
                <strong>Statut de la personne : </strong><?php echo Set::extract( $rolepers, Set::extract( $personne, 'Prestation.rolepers' ) ); ?>
                <br />
                <strong>Nom : </strong><?php echo $qual.' '.$nom;?>
                <br />
                <strong>Prénom : </strong><?php echo $prenom;?>
                <br />
                <strong>Date de naissance : </strong><?php echo date_short( $dtnai );?>
            </td>
            <td class="mediumSize noborder">
                <strong>N° Service instructeur : </strong><?php echo Set::extract( 'Serviceinstructeur.lib_service', $typeservice );?>
                <br />
                <strong>N° demandeur : </strong><?php echo $numdemrsa;?>
                <br />
                <strong>N° CAF/MSA : </strong><?php echo $matricule;?>
                <br />
                <strong>Inscrit au Pôle emploi</strong>
                <?php
                    $isPoleemploi = Set::classicExtract( $personne, 'Personne.idassedic' );
                    if( !empty( $isPoleemploi ) )
                        echo 'Oui';
                    else
                        echo 'Non';
                ?>
                <br />
                <strong>N° identifiant : </strong><?php echo $idassedic;?>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="mediumSize noborder">
                <strong>Adresse : </strong><br /><?php echo Set::extract( $personne, 'Adresse.numvoie' ).' '.Set::extract( $typevoie, Set::extract( $personne, 'Adresse.typevoie' ) ).' '.Set::extract( $personne, 'Adresse.nomvoie' ).'<br /> '.Set::extract( $personne, 'Adresse.codepos' ).' '.Set::extract( $personne, 'Adresse.locaadr' );?>
            </td>
        </tr>
        <tr>
            <td class="mediumSize noborder">
                <strong>Tél. fixe : </strong><?php echo Set::extract( $foyer, 'Modecontact.0.numtel' );?>
            </td>
            <td class="mediumSize noborder">
                <strong>Tél. portable : </strong><?php echo ''/*.Set::extract( $foyer, 'Modecontact.0.numtel' );*/?>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="mediumSize noborder">
                <strong>Adresse mail : </strong><?php echo Set::extract( $foyer, 'Modecontact.0.adrelec' )?> <!-- FIXME -->
            </td>
        </tr>
    </table>
</fieldset>

<fieldset>
    <legend>Type de Contrat</legend>
        <table class="wide noborder">
            <tr>
                <td class="mediumSize noborder">
                    <?php echo $form->input( 'Contratinsertion.forme_ci', array( 'label' => false, 'type' => 'radio' , 'options' => array( 'S' => 'Simple', 'C' => 'Complexe' ), 'legend' => required( __( 'forme_ci', true ) ) ) );?>
                </td>
            </tr>
            <tr>
                <td class="mediumSize noborder">
                    <strong>Date d'ouverture du droit ( RMI, API, rSa ) : </strong><?php echo $oridemrsa;?>
                </td>
                <td class="mediumSize noborder" colspan ="2">
                    <strong>rSa majoré</strong>
                        <?php
                            $soclmajValues = array_unique( Set::extract( $dossier, '/Infofinanciere/natpfcre' ) );
                            if( array_intersects( $soclmajValues, array_keys( $soclmaj ) )   )
                                echo 'Oui';
                            else
                                echo 'Non';
                        ?>
                </td>
            </tr>
            <tr>
                <td class="mediumSize noborder">
                    <strong>Ouverture de droit ( nombre d'ouvertures ) : </strong><?php echo '';?>
                </td>
            </tr>
            <tr>
                <td class="mediumSize noborder">
                    <?php if( $this->data['Contratinsertion']['typocontrat_id'] == 1 ):?>
                        <?php echo $form->input( 'Contratinsertion.typocontrat_id', array( 'label' => false, 'type' => 'hidden', 'id' => 'freu' ) );?>
                    <?php endif;?>
                    <?php echo $form->input( 'Contratinsertion.typocontrat_id', array( 'label' => false , 'type' => 'radio' , 'options' => $tc, 'legend' => false ) );
                    echo '(nombre de renouvellement) : '.$nbrCi;?>
                </td>
            </tr>
        </table>
        <table class="wide noborder">
            <tr>
                <td colspan="3" class="noborder center">
                    <em>Ce contrat fait suite à : </em>
                </td>
            </tr>
            <tr>
                <td class="radioSize noborder">
                    <?php echo $form->input( 'Contratinsertion.avis_ci', array( 'label' => false , 'type' => 'radio' , 'options' => $avis_ci, 'separator' => '<br />', 'legend' => false ) );?>
                </td>
                <td class="radioSize noborder">
                    <?php echo $form->input( 'Contratinsertion.raison_ci', array( 'label' => false , 'type' => 'radio' , 'options' => $raison_ci, 'separator' => '<br />', 'legend' => false ) );?>
                </td>
                <td class="radioSize noborder">
                    L'avis de l'equipe pluridisciplinaire :
                    <?php echo $form->input( 'Contratinsertion.aviseqpluri', array( 'label' => false , 'type' => 'radio' , 'options' => $aviseqpluri, 'separator' => '<br />', 'legend' => false ) );?>
                </td>
            </tr>
        </table>
</fieldset>

<fieldset>
    <legend>Type d'Orientation</legend>
        <table class="wide noborder">
            <tr>
                <td class="mediumSize noborder" colspan="3">
                    <?php
                        echo $form->input( 'Orientstruct.typeorient_id', array( 'label' => false, 'type' => 'radio', 'options' => $typeorient, 'value' => Set::classicExtract( $personne, 'Orientstruct.0.Typeorient.parentid' ), 'legend' => false ) );
                    ?>
                </td>
            </tr>
            <tr>
                <td class="noborder" colspan="3">
                    <?php 
                        echo $form->input( 'Contratinsertion.structurereferente_id', array( 'label' => __( '<em>Nom de l\'organisme de suivi </em>', true ), 'type' => 'select' , 'options' => $sr, 'empty' => true ) );
                    ?>
                </td><!--
                <td class="noborder"></td>
                <td class="noborder"></td>-->
            </tr>
            <tr>
                <td class="textArea noborder">
                    <?php 
                        echo $form->input( 'Contratinsertion.service_soutien', array( 'label' => '<em> et coordonnées</em>', 'type' => 'textarea', 'rows' => 3 )  );
                        echo $ajax->observeField( 'ContratinsertionStructurereferenteId', array( 'update' => 'ContratinsertionServiceSoutien', 'url' => Router::url( array( 'action' => 'ajax' ), true ) ) ) ;
                    ?>
                </td>
                <td class="textArea noborder">
                    <?php
                        echo $form->input( 'Contratinsertion.pers_charg_suivi', array( 'label' => '<em>'. __( '<em>Nom du référent </em>: et coordonnées', true ).'</em>', 'type' => 'textarea', 'rows' => 3 )  ); 
                    ?>
                </td>
                <td class="textArea noborder">
                    <?php
                       echo $form->input( 'Contratinsertion.fonction_ref', array( 'label' => '<em>'. __( 'Fonction du référent', true ).'</em>', 'type' => 'textarea', 'rows' => 3 )  ); 
                    ?>
                </td>
            </tr>
        </table>
</fieldset>
<fieldset class="loici">
    <p>
        Loi N°2008-1249 du 1er Décembre, généralisant le revenu de solidarité active et réformant les politiques d'insertion : <strong>Contrat librement débattu avec engagements réciproques</strong> ( articles L.263.35 et L.262.36 )<br />
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
        <em>Conformément à la loi "Informatique et liberté" n°78-17 du 06 janvier 1978 relative à l'informatique, aux fichiers et aux libertés nous nous engageons à prendre toutes les précautions afin de préserver la sécurité de ces informations liées à l'adresse, téléphone et mail seront utilisées uniquement pour permettre la prise de contact, dans le cadre du parcours d'insertion.</em>
    </p>
</fieldset>

        <?php echo $form->submit( 'Enregistrer' );?>
    <?php echo $form->end();?>
</div>

<div class="clearer"><hr /></div>