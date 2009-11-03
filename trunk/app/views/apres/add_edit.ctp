<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  $this->pageTitle = 'APRE';?>

<?php echo $this->element( 'dossier_menu', array( 'id' => $dossier_id ) );?>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout APRE';
    }
    else {
        $this->pageTitle = 'Édition APRE';
    }
?>

<script type="text/javascript">
    document.observe("dom:loaded", function() {
        //Données pour la nature du logement
        ['P', 'L', 'H', 'S'].each( function( letter ) {
            observeDisableFieldsOnValue( 'ApreNaturelogement' + letter, [ 'AprePrecisionsautrelogement' ],  letter, true );
        } );
        observeDisableFieldsOnValue( 'ApreNaturelogementA', [ 'AprePrecisionsautrelogement' ], 'A', false );

        //Données pour le type d'activité du bénéficiare
        ['F', 'C'].each( function( letter ) {
            observeDisableFieldsOnValue( 'ApreActivitebeneficiaire' + letter, [ 'ApreDateentreeemploiDay', 'ApreDateentreeemploiMonth', 'ApreDateentreeemploiYear', 'ApreTypecontratCDI', 'ApreTypecontratCDD', 'ApreTypecontratCON', 'ApreTypecontratAUT', 'AprePrecisionsautrecontrat', 'ApreNbheurestravaillees', 'ApreNomemployeur', 'ApreAdresseemployeur' ],  letter, true );
        } );
        observeDisableFieldsOnValue( 'ApreActivitebeneficiaireE', [ 'ApreDateentreeemploiDay', 'ApreDateentreeemploiMonth', 'ApreDateentreeemploiYear', 'ApreTypecontratCDI', 'ApreTypecontratCDD', 'ApreTypecontratCON', 'ApreTypecontratAUT', 'AprePrecisionsautrecontrat', 'ApreNbheurestravaillees', 'ApreNomemployeur', 'ApreAdresseemployeur' ], 'E', false );

        // ....
        observeDisableFieldsetOnCheckbox( 'NatureaideFormqualif', $( 'Formqualif' ), false );
//         observeDisableFieldsetOnCheckbox( 'ApreNatureaidePCA', $( 'Actprof' ), false );
//         observeDisableFieldsetOnCheckbox( 'ApreNatureaidePCB', $( 'Permisb' ), false );
    });
</script>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php
        if( $this->action == 'add' ) {
            echo $form->create( 'Apre', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
        }
        else {
            echo $form->create( 'Apre', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
            echo '<div>';
            echo $form->input( 'Apre.id', array( 'type' => 'hidden' ) );
            echo '</div>';
        }
        echo '<div>';
        echo $form->input( 'Apre.personne_id', array( 'type' => 'hidden', 'value' => $personne_id ) );
        echo '</div>';
    ?>

    <div class="aere">
        <fieldset>
            <table class="wide noborder">
                <tr>
                    <td class="mediumSize noborder">
                        <?php echo $form->input( 'Apre.numeroapre', array( 'type' => 'hidden', 'value' => $numapre ) ); ?>
                        <strong>Numéro de l'APRE : </strong><?php echo $numapre; ?>
                    </td>
                    <td class="mediumSize noborder">
                        <?php echo $xform->enum( 'Apre.typedemandeapre', array(  'legend' => required( __d( 'apre', 'Apre.typedemandeapre', true ) ), 'type' => 'radio', 'separator' => '<br />', 'options' => $options['typedemandeapre'] ) );?>
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
           <table class="wide noborder">
                <tr>
                    <td class="mediumSize noborder">

                    </td>

                </tr>
                <tr>
                    <td colspan="2" class="wide noborder">
                        <?php echo $xform->input( 'Apre.datedemandeapre', array( 'domain' => 'apre', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1 ) );?>
                    </td>
                </tr>
            </table>
        </fieldset>
        <h1>Demande d'Aide Personnalisee de Retour a  l'Emploi (APRE)</h1>
        <fieldset>
            <legend>Identité du beneficiaire de la demande</legend>
            <table class="wide noborder">
                <tr>
                    <td class="mediumSize noborder">
                        <strong>Nom : </strong><?php echo Set::classicExtract( $qual, Set::classicExtract( $personne, 'Personne.qual' ) ).' '.Set::classicExtract( $personne, 'Personne.nom' );?>
                        <br />
                        <strong>Prénom : </strong><?php echo Set::classicExtract( $personne, 'Personne.prenom' );?>
                        <br />
                        <strong>Date de naissance : </strong><?php echo date_short( Set::classicExtract( $personne, 'Personne.dtnai' ) );?>
                    </td>
                    <td class="mediumSize noborder">
                        <strong>Adresse : </strong><br /><?php echo Set::classicExtract( $personne, 'Adresse.numvoie' ).' '.Set::classicExtract( $typevoie, Set::classicExtract( $personne, 'Adresse.typevoie' ) ).' '.Set::classicExtract( $personne, 'Adresse.nomvoie' ).'<br /> '.Set::classicExtract( $personne, 'Adresse.codepos' ).' '.Set::classicExtract( $personne, 'Adresse.locaadr' );?>
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
            <legend>Situation administrative du bénéficiaire</legend>
            <table class="wide noborder">
                <tr>
                    <td class="mediumSize noborder">
                        <strong>N° matricule CAF : </strong><?php echo Set::classicExtract( $personne, 'Foyer.Dossier.matricule' );?>
                        <br />
                        <strong>Situation familiale : </strong><?php echo Set::classicExtract( $sitfam, Set::classicExtract( $personne, 'Foyer.sitfam' ) );?>
                    </td>
                    <td class="wide noborder">
                        <strong>Nbre d'enfants : </strong><?php echo $nbEnfants;?>
                    </td>
                </tr>
                <tr>
                    <td class="mediumSize noborder">
                        <?php echo $xform->enum( 'Apre.naturelogement', array( 'div' => false, 'label' => false, 'legend' => __d( 'apre', 'Apre.naturelogement', true ), 'type' => 'radio', 'separator' => '<br />', 'options' => $options['naturelogement'] ) );?>
                    </td>
                    <td class="noborder">
                        <?php echo $xform->input( 'Apre.precisionsautrelogement', array( 'domain' => 'apre', 'type' => 'textarea' ) );?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="mediumSize noborder">
                        <strong>Date de validation du contrat d'insertion par le Président du Conseil Général  </strong> <?php echo date_short( Set::classicExtract( $personne, 'Contratinsertion.dernier.datevalidation_ci' ) );?>
                        <br />(joindre obligatoirement la copie du contrat d'insertion)
                    </td>
                </tr>
            </table>
        </fieldset>
         <fieldset>
            <legend>Parcours du bénéficiaire</legend>
            <table class="wide noborder">
                <tr>
                    <td class="mediumsize noborder"><strong>Date de dernière cessation d'activité : </strong></td>
                    <td class="mediumsize noborder"><?php echo Set::classicExtract( $optionsdsps['cessderact'], Set::classicExtract( $personne, 'Dsp.cessderact' ) );?></td>
                </tr>
                <tr>
                    <td class="mediumsize noborder"><strong>Ancienneté pôle emploi </strong></td>
                    <td class="mediumsize noborder"><?php echo $xform->input(  'Apre.anciennetepoleemploi', array( 'domain' => 'apre', 'label' => false ) );?></td>
                </tr>
                <tr>
                    <td class="mediumsize noborder"><strong>Niveau d'étude </strong></td>
                    <td class="mediumsize noborder"><?php echo Set::classicExtract( $optionsdsps['nivetu'], Set::classicExtract( $personne, 'Dsp.nivetu' ) );?></td>
                </tr>
                <tr>
                    <td class="mediumsize noborder"><strong>Projet professionnel </strong></td>
                    <td class="mediumsize noborder"><?php echo $xform->input(  'Apre.projetprofessionnel', array( 'domain' => 'apre', 'label' => false ) );?></td>
                </tr>
                <tr>
                    <td class="mediumsize noborder"><strong>Secteur professionnel en lien avec la demande </strong></td>
                    <td class="mediumsize noborder"><?php echo $xform->input(  'Apre.secteurprofessionnel', array( 'domain' => 'apre', 'label' => false ) );?></td>
                </tr>
            </table>
        </fieldset>
         <fieldset>
            <legend>Activité du bénéficiaire</legend>
            <table class="wide noborder">
                <tr>
                    <td class="mediumsize noborder"><strong>Type d'activité </strong></td>
                    <td class="mediumsize noborder"><?php echo $xform->enum( 'Apre.activitebeneficiaire', array( 'div' => false, 'label' => false, 'legend' => required( __d( 'apre', 'Apre.activitebeneficiaire', true ) ), 'type' => 'radio', 'separator' => '<br />', 'options' => $options['activitebeneficiaire'] ) );?></td>
                </tr>
                <tr>
                    <td class="mediumsize noborder"><strong>Date de l'emploi prévu </strong></td>
                    <td class="mediumsize noborder"><?php echo $xform->input( 'Apre.dateentreeemploi', array( 'domain' => 'apre', 'label' => false, 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => true ) );?></td>
                </tr>
                <tr>
                    <td class="mediumsize noborder"><strong>Type de contrat </strong></td>
                    <td class="mediumsize noborder"><?php echo $xform->enum( 'Apre.typecontrat', array( 'div' => false, 'label' => false, 'legend' => false, 'type' => 'radio', 'separator' => '<br />', 'options' => $options['typecontrat'] ) );?></td>
                </tr>
                <tr>
                    <td class="mediumsize noborder"><strong>Si autres, préciser  </strong></td>
                    <td class="mediumsize noborder"><?php echo $xform->input( 'Apre.precisionsautrecontrat', array( 'domain' => 'apre', 'label' => false, 'type' => 'textarea' ) );?></td>
                </tr>
                <tr>
                    <td class="mediumsize noborder"><strong>Nombres d'heures travaillées </strong></td>
                    <td class="mediumsize noborder"><?php echo $xform->input(  'Apre.nbheurestravaillees', array( 'domain' => 'apre', 'label' => false ) );?></td>
                </tr>
                <tr>
                    <td class="mediumsize noborder"><strong>Nom et adresse de l'employeur </strong></td>
                    <td class="mediumsize noborder"><?php echo $xform->input(  'Apre.nomemployeur', array( 'domain' => 'apre', 'label' => false ) );?><?php echo $xform->input(  'Apre.adresseemployeur', array( 'domain' => 'apre', 'label' => false ) );?></td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
            <legend>Identité du référent</legend>
           <table class="wide noborder">
                <tr>
                    <td class="noborder">
                        <strong>Référent de l'APRE</strong>
                        <?php echo $xform->input( 'Apre.referentapre_id', array( 'domain' => 'apre', 'label' => false, 'type' => 'select', 'options' => $refsapre, 'empty' => true ) );?>
                        <?php echo $ajax->observeField( 'ApreReferentapreId', array( 'update' => 'ReferentapreOrganismeref', 'url' => Router::url( array( 'action' => 'ajaxrefapre' ), true ) ) );?>
                    </td>
                </tr>
                <tr>
                    <td class="wide noborder"><div id="ReferentapreOrganismeref"></div></td>
                </tr>
            </table>
        </fieldset>

        <h2 class="center">Nature de la demande</h2>
        <?php
            /// Formation qualifiante
            /*$tmp = $form->checkbox( null, array( 'id' => 'ApreNatureaideFQU', 'name' => 'data[Apre][natureaide][]', 'value' => 'FQU' ) );
            $tmp .= $html->tag( 'label', 'Formation qualifiante', array( 'for' => 'ApreNatureaideFQU' ) );*/
            $tmp = $form->checkbox( 'Natureaide.Formqualif' );
            $tmp .= $html->tag( 'label', 'Formation qualifiante', array( 'for' => 'NatureaideFormqualif' ) );
            echo $html->tag( 'h3', $tmp );
        ?>
        <fieldset id="Formqualif" class="invisible">
            <?php
                if( $this->action == 'edit' ) {
                    echo $form->input( 'Formqualif.id', array( 'type' => 'hidden' ) );
                }
                echo $form->input( 'Formqualif.intitule' );
            ?>
        </fieldset>
        <!--<?php
            /// Action de professionnalisation
            $tmp = $form->checkbox( null, array( 'id' => 'ApreNatureaidePCA', 'name' => 'data[Apre][natureaide][]', 'value' => 'PCA' ) );
            $tmp .= $html->tag( 'label', 'Action de professionnalisation', array( 'for' => 'ApreNatureaidePCA' ) );
            echo $html->tag( 'h3', $tmp );
        ?>
        <fieldset id="Actprof" class="invisible">
            <?php
                echo $form->input( 'Actprof.employeur' );
            ?>
        </fieldset>
        <?php
            /// Action de professionnalisation
            $tmp = $form->checkbox( null, array( 'id' => 'ApreNatureaidePCB', 'name' => 'data[Apre][natureaide][]', 'value' => 'PCB' ) );
            $tmp .= $html->tag( 'label', 'Permis de conduire B', array( 'for' => 'ApreNatureaidePCB' ) );
            echo $html->tag( 'h3', $tmp );
        ?>
        <fieldset id="Permisb" class="invisible">
            <?php
                echo $form->input( 'Permisb.autoecole' );
            ?>
        </fieldset>-->
    </div>

        <?php echo $form->submit( 'Enregistrer' );?>
    <?php echo $form->end();?>
</div>
<div class="clearer"><hr /></div>