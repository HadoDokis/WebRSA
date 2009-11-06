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
        observeDisableFieldsetOnCheckbox( 'NatureaideAcccreaentr', $( 'Acccreaentr' ), false, true );
        observeDisableFieldsetOnCheckbox( 'NatureaideAcqmatprof', $( 'Acqmatprof' ), false, true );
        observeDisableFieldsetOnCheckbox( 'NatureaideLocvehicinsert', $( 'Locvehicinsert' ), false, true );

        <?php
            echo $ajax->remoteFunction(
                array(
//                     'parameters' => "Form.Element.serialize('ApreReferentapreId')",
                    'update' => 'ReferentapreOrganismeref',
                    'url' => Router::url( array( 'action' => 'ajaxrefapre', Set::extract( $this->data, 'Apre.referentapre_id' ) ), true )
                )
            );
        ?>
    });
</script>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php
		echo $form->create( 'Apre', array( 'type' => 'post', 'id' => 'Apre', 'url' => Router::url( null, true ) ) );
        $ApreId = Set::classicExtract( $this->data, 'Apre.id' );
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
                        <strong>Civilité : </strong><?php echo Set::classicExtract( $qual, Set::classicExtract( $personne, 'Personne.qual' ) );?>
                        <br />
                        <strong>Nom : </strong><?php echo Set::classicExtract( $personne, 'Personne.nom' );?>
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
                    <td class="mediumsize noborder"><?php echo $xform->enum( 'Apre.activitebeneficiaire', array( 'legend' => required( __d( 'apre', 'Apre.activitebeneficiaire', true ) ), 'type' => 'radio', 'separator' => '<br />', 'options' => $options['activitebeneficiaire'] ) );?></td>
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
                        <?php echo $ajax->observeField( 'ApreReferentapreId', array( 'update' => 'ReferentapreOrganismeref', 'url' => Router::url( array( 'action' => 'ajaxrefapre' ), true ) ) ); ?> </td>
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
                $formqualifs = Set::extract( $this->data, 'Formqualif' );
                $formqualifs = ( !empty( $formqualifs ) ? $formqualifs : array( 0 => array() ) );

                $FormsqualifsIds = Set::classicExtract( $this->data, 'Formqualif.{n}.id' );

                foreach( $formqualifs as $key => $formqualif ) {
                    if( $this->action == 'edit' && !empty( $FormqualifId ) && !empty( $ApreId ) ) {
                        echo $xform->input( 'Formqualif.'.$key.'.id', array( 'type' => 'hidden' ) );
                        echo $xform->input( 'Formqualif.'.$key.'.apre_id', array( 'type' => 'hidden' ) );
                    }
                    echo $xform->input( 'Formqualif.'.$key.'.intituleform', array( 'domain' => 'apre' ) );
                    echo $xform->input( 'Formqualif.'.$key.'.organismeform', array( 'domain' => 'apre' ) );
                    echo $xform->input( 'Formqualif.'.$key.'.ddform', array( 'domain' => 'apre', 'type' => 'date', 'dateFormat' => 'DMY' ) );
                    echo $xform->input( 'Formqualif.'.$key.'.dfform', array( 'domain' => 'apre', 'type' => 'date', 'dateFormat' => 'DMY' ) );
                    echo $xform->input( 'Formqualif.'.$key.'.dureeform', array( 'domain' => 'apre' ) );
                    echo $xform->input( 'Formqualif.'.$key.'.modevalidation', array( 'domain' => 'apre' ) );
                    echo $xform->input( 'Formqualif.'.$key.'.coutform', array( 'domain' => 'apre' ) );
                    echo $xform->input( 'Formqualif.'.$key.'.cofinanceurs', array( 'domain' => 'apre' ) );
                    echo $xform->input( 'Formqualif.'.$key.'.montantaide', array( 'domain' => 'apre' ) );
                }
            ?>
            <!--<fieldset>
                <legend>Pièces jointes</legend>
                <?php echo $xform->input( 'Pieceformqualif.Pieceformqualif', array( 'options' => $piecesformqualif, 'multiple' => 'checkbox', 'label' => false ) ); ?>
            </fieldset>-->
        </fieldset>
        <!-- <?php
            /// Action de professionnalisation
            $tmp = $form->checkbox( 'Natureaide.Actprof' );
            $tmp .= $html->tag( 'label', 'Action de professionnalisation des contrats aides et salariés dans les SIAE', array( 'for' => 'NatureaideActprof' ) );
            echo $html->tag( 'h3', $tmp );
        ?>
        <fieldset id="Actprof" class="invisible">
            <?php
                $ActprofId = Set::classicExtract( $this->data, 'Actprof.id' );
                if( $this->action == 'edit' && !empty( $ActprofId ) ) {
                    echo $form->input( 'Actprof.id', array( 'type' => 'hidden' ) );
                }
                echo $xform->input( 'Actprof.nomemployeur', array( 'domain' => 'apre' ) );
                echo $xform->address( 'Actprof.adresseemployeur', array( 'domain' => 'apre' ) );
                echo $xform->enum( 'Actprof.typecontratact', array( 'div' => false, 'legend' => 'Type de contrat', 'type' => 'radio', 'options' => $optionsacts['typecontratact'] ) );
                echo $xform->input( 'Actprof.ddconvention', array( 'domain' => 'apre', 'dateFormat' => 'DMY' ) );
                echo $xform->input( 'Actprof.dfconvention', array( 'domain' => 'apre', 'dateFormat' => 'DMY' ) );
                echo $xform->input( 'Actprof.intituleformation', array( 'domain' => 'apre' ) );
                echo $xform->input( 'Actprof.ddform', array( 'domain' => 'apre', 'dateFormat' => 'DMY' ) );
                echo $xform->input( 'Actprof.dfform', array( 'domain' => 'apre', 'dateFormat' => 'DMY' ) );
                echo $xform->input( 'Actprof.dureeform', array( 'domain' => 'apre' ) );
                echo $xform->input( 'Actprof.modevalidation', array( 'domain' => 'apre' ) );;
                echo $xform->input( 'Actprof.coutform', array( 'domain' => 'apre' ) );
                echo $xform->input( 'Actprof.cofinanceurs', array( 'domain' => 'apre' ) );
                echo $xform->input( 'Actprof.montantaide', array( 'domain' => 'apre' ) );
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
                $PermisbId = Set::classicExtract( $this->data, 'Permisb.id' );
                if( $this->action == 'edit' && !empty( $PermisbId ) ) {
                    echo $form->input( 'Permisb.id', array( 'type' => 'hidden' ) );
                }
                echo $xform->input( 'Permisb.nomautoecole', array( 'domain' => 'apre' ) );
                echo $xform->address( 'Permisb.adresseautoecole', array( 'domain' => 'apre' ) );
                echo $xform->input( 'Permisb.code',
                    array( 'div' => false, 'label' => 'Code', 'type' => 'checkbox' )
                );
                echo $xform->input( 'Permisb.conduite',
                    array( 'div' => false, 'label' => 'Conduite', 'type' => 'checkbox' )
                );
                echo $xform->input( 'Permisb.dureeform', array( 'domain' => 'apre' ) );
                echo $xform->input( 'Permisb.coutform', array( 'domain' => 'apre' ) );
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
                $AmenaglogtId = Set::classicExtract( $this->data, 'Amenaglogt.id' );
                if( $this->action == 'edit' && !empty( $AmenaglogtId ) ) {
                    echo $form->input( 'Amenaglogt.id', array( 'type' => 'hidden' ) );
                }
                echo $xform->enum( 'Amenaglogt.typeaidelogement', array( 'div' => false, 'legend' => false, 'type' => 'radio', 'options' => $optionslogts['typeaidelogement'] ) );
                echo $xform->address( 'Amenaglogt.besoins', array( 'domain' => 'apre' ) );
                echo $xform->input( 'Amenaglogt.montantaide', array( 'domain' => 'apre' ) );
            ?>
            <fieldset>
                <legend>Pièces jointes</legend>
                <?php echo $xform->input( 'Pieceamenaglogt.Pieceamenaglogt', array( 'options' => $piecesamenaglogt, 'multiple' => 'checkbox', 'label' => false ) ); ?>
            </fieldset>
        </fieldset>
        <?php
            /// Accompagnement à la création d'entreprise
            $tmp = $form->checkbox( 'Natureaide.Acccreaentr' );
            $tmp .= $html->tag( 'label', 'Accompagnement à la création d\'entreprise', array( 'for' => 'NatureaideAcccreaentr' ) );
            echo $html->tag( 'h3', $tmp );
        ?>
        <fieldset id="Acccreaentr" class="invisible">
            <?php
                $AcccreaentrId = Set::classicExtract( $this->data, 'Acccreaentr.id' );
                if( $this->action == 'edit' && !empty( $AcccreaentrId ) ) {
                    echo $form->input( 'Acccreaentr.id', array( 'type' => 'hidden' ) );
                }
                echo $xform->enum( 'Acccreaentr.nacre', array( 'legend' => 'Dispositif Nacre', 'div' => false, 'type' => 'radio', 'options' => $optionscrea['nacre'] ) );
                echo $xform->enum( 'Acccreaentr.microcredit', array( 'legend' => 'Dispositif Micro-crédit', 'div' => false, 'type' => 'radio', 'options' => $optionscrea['microcredit'] ) );
                echo $xform->address( 'Acccreaentr.projet', array( 'domain' => 'apre' ) );
                echo $xform->input( 'Acccreaentr.montantaide', array( 'domain' => 'apre' ) );
            ?>
            <fieldset>
                <legend>Pièces jointes</legend>
                <?php echo $xform->input( 'Pieceacccreaentr.Pieceacccreaentr', array( 'options' => $piecesacccreaentr, 'multiple' => 'checkbox', 'label' => false ) ); ?>
            </fieldset>
        </fieldset>
        <?php
            /// Acquisition de matériels professionnels
            $tmp = $form->checkbox( 'Natureaide.Acqmatprof' );
            $tmp .= $html->tag( 'label', 'Acquisition de matériels professionnels', array( 'for' => 'NatureaideAcqmatprof' ) );
            echo $html->tag( 'h3', $tmp );
        ?>
        <fieldset id="Acqmatprof" class="invisible">
            <?php
                $AcqmatprofId = Set::classicExtract( $this->data, 'Acqmatprof.id' );
                if( $this->action == 'edit' && !empty( $AcqmatprofId ) ) {
                    echo $form->input( 'Acqmatprof.id', array( 'type' => 'hidden' ) );
                }
                echo $xform->address( 'Acqmatprof.besoins', array( 'domain' => 'apre' ) );
                echo $xform->input( 'Acqmatprof.montantaide', array( 'domain' => 'apre' ) );
            ?>
            <fieldset>
                <legend>Pièces jointes</legend>
                <?php echo $xform->input( 'Pieceacqmatprof.Pieceacqmatprof', array( 'options' => $piecesacqmatprof, 'multiple' => 'checkbox', 'label' => false ) ); ?>
            </fieldset>
        </fieldset>
        <?php
            /// Aide à la location d'un véhicule d'insertion
            $tmp = $form->checkbox( 'Natureaide.Locvehicinsert' );
            $tmp .= $html->tag( 'label', 'Aide à la location d\'un véhicule d\'insertion', array( 'for' => 'NatureaideLocvehicinsert' ) );
            echo $html->tag( 'h3', $tmp );
        ?>
        <fieldset id="Locvehicinsert" class="invisible">
            <?php
                $LocvehicinsertId = Set::classicExtract( $this->data, 'Locvehicinsert.id' );
                if( $this->action == 'edit' && !empty( $LocvehicinsertId ) ) {
                    echo $form->input( 'Locvehicinsert.id', array( 'type' => 'hidden' ) );
                }
                echo $xform->input( 'Locvehicinsert.societelocation', array( 'domain' => 'apre' ) );
                echo $xform->input( 'Locvehicinsert.dureelocation', array( 'domain' => 'apre' ) );
                echo $xform->input( 'Locvehicinsert.montantaide', array( 'domain' => 'apre' ) );
            ?>
            <fieldset>
                <legend>Pièces jointes</legend>
                <?php echo $xform->input( 'Piecelocvehicinsert.Piecelocvehicinsert', array( 'options' => $pieceslocvehicinsert, 'multiple' => 'checkbox', 'label' => false ) ); ?>
            </fieldset>
        </fieldset>
        <fieldset class="aere">
            <legend>Avis technique et motivé du référent (Article 5.1 relatif au règlement de l'APRE): </legend>
            <?php echo $xform->input(  'Apre.avistechreferent', array( 'domain' => 'apre', 'label' => false ) );?>
        </fieldset> -->
    </div>

        <?php echo $form->submit( 'Enregistrer' );?>
    <?php echo $form->end();?>
</div>
<div class="clearer"><hr /></div>