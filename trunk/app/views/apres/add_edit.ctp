<?php
	echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );

	$this->pageTitle = 'APRE';

	echo $this->element( 'dossier_menu', array( 'id' => $dossier_id ) );

    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout APRE';
    }
    else {
        $this->pageTitle = 'Édition APRE';
    }

	// FIXME: mettre dans bootsrap.php et enlever la définition de la fonction de toutes les vues qui l'utilisent
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
        observeDisableFieldsetOnCheckbox( 'NatureaideFormqualif', $( 'Formqualif' ), false, true );
        observeDisableFieldsetOnCheckbox( 'NatureaideActprof', $( 'Actprof' ), false, true );
        observeDisableFieldsetOnCheckbox( 'NatureaidePermisb', $( 'Permisb' ), false, true );
        observeDisableFieldsetOnCheckbox( 'NatureaideAmenaglogt', $( 'Amenaglogt' ), false, true );
    });
</script>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php
		echo $form->create( 'Apre', array( 'type' => 'post', 'id' => 'Apre', 'url' => Router::url( null, true ) ) );
        if( $this->action == 'edit' ) {
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
                        <strong>Situation familiale : </strong><?php echo value( $sitfam, Set::classicExtract( $personne, 'Foyer.sitfam' ) );?>
                    </td>
                    <td class="wide noborder">
                        <strong>Nbre d'enfants : </strong><?php echo $nbEnfants;?>
                    </td>
                </tr>
                <tr>
                    <td class="mediumSize noborder">
                        <?php echo $xform->enum( 'Apre.naturelogement', array( 'div' => false, 'legend' => __d( 'apre', 'Apre.naturelogement', true ), 'type' => 'radio', 'separator' => '<br />', 'options' => $options['naturelogement'] ) );?>
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
                    <td class="mediumsize noborder"><?php echo Set::classicExtract( $personne, 'Dsp.cessderact' ) ? Set::classicExtract( $optionsdsps['cessderact'], Set::classicExtract( $personne, 'Dsp.cessderact' ) ) : null;?></td>
                </tr>
                <tr>
                    <td class="mediumsize noborder"><strong>Ancienneté pôle emploi </strong></td>
                    <td class="mediumsize noborder"><?php echo $xform->input(  'Apre.anciennetepoleemploi', array( 'domain' => 'apre', 'label' => false ) );?></td>
                </tr>
                <tr>
                    <td class="mediumsize noborder"><strong>Niveau d'étude </strong></td>
                    <td class="mediumsize noborder"><?php echo Set::classicExtract( $personne, 'Dsp.nivetu' ) ? Set::classicExtract( $optionsdsps['nivetu'], Set::classicExtract( $personne, 'Dsp.nivetu' ) ) : null;?></td>
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
                    <td class="mediumsize noborder"><?php echo $xform->enum( 'Apre.activitebeneficiaire', array( 'div' => false, 'legend' => required( __d( 'apre', 'Apre.activitebeneficiaire', true ) ), 'type' => 'radio', 'separator' => '<br />', 'options' => $options['activitebeneficiaire'] ) );?></td>
                </tr>
                <tr>
                    <td class="mediumsize noborder"><strong>Date de l'emploi prévu </strong></td>
                    <td class="mediumsize noborder"><?php echo $xform->input( 'Apre.dateentreeemploi', array( 'domain' => 'apre', 'label' => false, 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => true ) );?></td>
                </tr>
                <tr>
                    <td class="mediumsize noborder"><strong>Type de contrat </strong></td>
                    <td class="mediumsize noborder"><?php echo $xform->enum( 'Apre.typecontrat', array( 'div' => false, 'legend' => false, 'type' => 'radio', 'separator' => '<br />', 'options' => $options['typecontrat'] ) );?></td>
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
        <fieldset>
            <legend>Pièces jointes</legend>
            <?php echo $xform->input( 'Pieceapre.Pieceapre', array( 'options' => $piecesapre, 'multiple' => 'checkbox', 'label' => false ) ); ?>
        </fieldset>

        <h2 class="center">Nature de la demande</h2>
        <?php
            /// Formation qualifiante
            $tmp = $form->checkbox( 'Natureaide.Formqualif' );
            $tmp .= $html->tag( 'label', 'Formation qualifiante / Permis C ou D + FIMO', array( 'for' => 'NatureaideFormqualif' ) );
            echo $html->tag( 'h3', $tmp );
        ?>
        <fieldset id="Formqualif" class="invisible">
            <?php
                if( $this->action == 'edit' ) {
                    echo $form->input( 'Formqualif.id', array( 'type' => 'hidden' ) );
                }
                echo $form->input( 'Formqualif.intituleform' );
                echo $form->input( 'Formqualif.organismeform' );
                echo $form->input( 'Formqualif.ddform' );
                echo $form->input( 'Formqualif.dfform' );
                echo $form->input( 'Formqualif.dureeform' );
                echo $form->input( 'Formqualif.modevalidation' );
                echo $form->input( 'Formqualif.coutform' );
                echo $form->input( 'Formqualif.cofinanceurs' );
                echo $form->input( 'Formqualif.montantaide' );
            ?>
            <fieldset>
                <legend>Pièces jointes</legend>
                <?php echo $xform->input( 'Pieceformqualif.Pieceformqualif', array( 'options' => $piecesformqualif, 'multiple' => 'checkbox', 'label' => false ) ); ?>
            </fieldset>
        </fieldset>
        <?php
            /// Action de professionnalisation
            $tmp = $form->checkbox( 'Natureaide.Actprof' );
            $tmp .= $html->tag( 'label', 'Action de professionnalisation des contrats aides et salariés dans les SIAE', array( 'for' => 'NatureaideActprof' ) );
            echo $html->tag( 'h3', $tmp );
        ?>
        <fieldset id="Actprof" class="invisible">
            <?php
                if( $this->action == 'edit' ) {
                    echo $form->input( 'Actprof.id', array( 'type' => 'hidden' ) );
                }
                echo $form->input( 'Actprof.nomemployeur' );
                echo $form->input( 'Actprof.adresseemployeur' );
                echo $xform->enum( 'Actprof.typecontratact', array( 'div' => false, 'legend' => false, 'type' => 'radio', /*'separator' => '<br />',*/ 'options' => $optionsacts['typecontratact'] ) );
                echo $form->input( 'Actprof.ddconvention' );
                echo $form->input( 'Actprof.dfconvention' );
                echo $form->input( 'Actprof.intituleformation' );
                echo $form->input( 'Actprof.ddform' );
                echo $form->input( 'Actprof.dfform' );
                echo $form->input( 'Actprof.dureeform' );
                echo $form->input( 'Actprof.modevalidation' );
                echo $form->input( 'Actprof.coutform' );
                echo $form->input( 'Actprof.cofinanceurs' );
                echo $form->input( 'Actprof.montantaide' );
            ?>
            <fieldset>
                <legend>Pièces jointes</legend>
                <?php echo $xform->input( 'Pieceactprof.Pieceactprof', array( 'options' => $piecesactprof, 'multiple' => 'checkbox', 'label' => false ) ); ?>
            </fieldset>
        </fieldset>
        <?php
            /// Permis B
            $tmp = $form->checkbox( 'Natureaide.Permisb' );
            $tmp .= $html->tag( 'label', 'Permis de conduire B', array( 'for' => 'NatureaidePermisb' ) );
            echo $html->tag( 'h3', $tmp );
        ?>
        <fieldset id="Permisb" class="invisible">
            <?php
                if( $this->action == 'edit' ) {
                    echo $form->input( 'Permisb.id', array( 'type' => 'hidden' ) );
                }
                echo $form->input( 'Permisb.nomautoecole' );
                echo $form->input( 'Permisb.adresseautoecole' );
                echo $form->input( 'Permisb.code',
                    array( 'div' => false, 'label' => 'Code', 'type' => 'checkbox' )
                );
                echo $form->input( 'Permisb.conduite',
                    array( 'div' => false, 'label' => 'Conduite', 'type' => 'checkbox' )
                );
                echo $form->input( 'Permisb.dureeform' );
                echo $form->input( 'Permisb.coutform' );
            ?>
            <fieldset>
                <legend>Pièces jointes</legend>
                <?php echo $xform->input( 'Piecepermisb.Piecepermisb', array( 'options' => $piecespermisb, 'multiple' => 'checkbox', 'label' => false ) ); ?>
            </fieldset>
        </fieldset>
        <?php
            /// Amenagement logement
            $tmp = $form->checkbox( 'Natureaide.Amenaglogt' );
            $tmp .= $html->tag( 'label', 'Aide à l\'installation', array( 'for' => 'NatureaideAmenaglogt' ) );
            echo $html->tag( 'h3', $tmp );
        ?>
        <fieldset id="Amenaglogt" class="invisible">
            <?php
                if( $this->action == 'edit' ) {
                    echo $form->input( 'Amenaglogt.id', array( 'type' => 'hidden' ) );
                }
                echo $xform->enum( 'Amenaglogt.typeaidelogement', array( 'div' => false, 'legend' => false, 'type' => 'radio', /*'separator' => '<br />',*/ 'options' => $optionslogts['typeaidelogement'] ) );
                echo $form->input( 'Amenaglogt.besoins' );
                echo $form->input( 'Amenaglogt.montantaide' );
                echo $form->input( 'Amenaglogt.dureeform' );
                echo $form->input( 'Amenaglogt.coutform' );
            ?>
            <fieldset>
                <legend>Pièces jointes</legend>
                <?php echo $xform->input( 'Pieceamenaglogt.Pieceamenaglogt', array( 'options' => $piecesamenaglogt, 'multiple' => 'checkbox', 'label' => false ) ); ?>
            </fieldset>
        </fieldset>
    </div>

        <?php echo $form->submit( 'Enregistrer' );?>
    <?php echo $form->end();?>
</div>
<div class="clearer"><hr /></div>