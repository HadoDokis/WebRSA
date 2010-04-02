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

    function radioApre( $view, $path, $value, $label ) {
        $name = 'data['.implode( '][', explode( '.', $path ) ).']';
        $notEmptyValues = Set::filter( Set::classicExtract( $view->data, $value ) );
        $checked = ( ( !empty( $notEmptyValues ) ) ? 'checked="checked"' : '' );
        return "<label><input type=\"radio\" name=\"{$name}\" value=\"{$value}\" {$checked} />{$label}</label>";
        //$form->radio( 'Apre.Natureaide', array( 'Formqualif' ), array( 'id' => 'ApreNatureaide0', 'legend' => false, 'div' => false ) );

    }
?>
<!--/************************************************************************/ -->
    <?php echo $javascript->link( 'dependantselect.js' ); ?>
    <script type="text/javascript">
        document.observe("dom:loaded", function() {
            dependantSelect( 'ApreReferentId', 'ApreStructurereferenteId' );
        });
    </script>
<!--/************************************************************************/ -->
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        //Données pour la nature du logement
        ['P', 'L', 'H', 'S'].each( function( letter ) {
            observeDisableFieldsOnValue( 'ApreNaturelogement' + letter, [ 'AprePrecisionsautrelogement' ],  letter, true );
        } );
        observeDisableFieldsOnValue( 'ApreNaturelogementA', [ 'AprePrecisionsautrelogement' ], 'A', false );

        //Données pour le type d'activité du bénéficiare
        ['F', 'C'].each( function( letter ) {
            observeDisableFieldsOnValue( 'ApreActivitebeneficiaire' + letter, [ 'ApreDateentreeemploiDay', 'ApreDateentreeemploiMonth', 'ApreDateentreeemploiYear', 'ApreTypecontratCDI', 'ApreTypecontratCDD', 'ApreTypecontratCON', 'ApreTypecontratAUT', 'AprePrecisionsautrecontrat', 'ApreNbheurestravaillees', 'ApreNomemployeur', 'ApreAdresseemployeur', 'ApreSecteuractivite' ],  letter, true );
        } );
        observeDisableFieldsOnValue( 'ApreActivitebeneficiaireE', [ 'ApreDateentreeemploiDay', 'ApreDateentreeemploiMonth', 'ApreDateentreeemploiYear', 'ApreTypecontratCDI', 'ApreTypecontratCDD', 'ApreTypecontratCON', 'ApreTypecontratAUT', 'AprePrecisionsautrecontrat', 'ApreNbheurestravaillees', 'ApreNomemployeur', 'ApreAdresseemployeur', 'ApreSecteuractivite' ], 'E', false );

        // ....
//         observeDisableFieldsetOnCheckbox( 'ApreNatureaideFormqualif', $( 'Formqualif' ), false, true );
//         observeDisableFieldsetOnCheckbox( 'ApreNatureaideFormpermfimo', $( 'Formpermfimo' ), false, true );
//         observeDisableFieldsetOnCheckbox( 'ApreNatureaideActprof', $( 'Actprof' ), false, true );
//         observeDisableFieldsetOnCheckbox( 'ApreNatureaidePermisb', $( 'Permisb' ), false, true );
//         observeDisableFieldsetOnCheckbox( 'ApreNatureaideAmenaglogt', $( 'Amenaglogt' ), false, true );
//         observeDisableFieldsetOnCheckbox( 'ApreNatureaideAcccreaentr', $( 'Acccreaentr' ), false, true );
//         observeDisableFieldsetOnCheckbox( 'ApreNatureaideAcqmatprof', $( 'Acqmatprof' ), false, true );
//         observeDisableFieldsetOnCheckbox( 'ApreNatureaideLocvehicinsert', $( 'Locvehicinsert' ), false, true );

        ['Formqualif', 'Formpermfimo', 'Actprof', 'Permisb', 'Amenaglogt', 'Acccreaentr', 'Acqmatprof', 'Locvehicinsert' ].each( function( formation ) {
            observeDisableFieldsetOnRadioValue(
                'Apre',
                'data[Apre][Natureaide]',
                $( formation ),
                formation,
                false,
                true
            );
        } );


        <?php
            echo $ajax->remoteFunction(
                array(
                    'update' => 'StructurereferenteRef',
                    'url' => Router::url( array( 'action' => 'ajaxstruct', Set::extract( $this->data, 'Apre.structurereferente_id' ) ), true )
                )
            ).';';
            echo $ajax->remoteFunction(
                array(
                    'update' => 'ReferentRef',
                    'url' => Router::url( array( 'action' => 'ajaxref', Set::extract( $this->data, 'Apre.referent_id' ) ), true )
                )
            ).';';
            echo $ajax->remoteFunction(
                array(
                    'update' => 'FormqualifCoordonnees',
                    'url' => Router::url( array( 'action' => 'ajaxtiersprestaformqualif', Set::extract( $this->data, 'Formqualif.tiersprestataireapre_id' ) ), true )
                )
            ).';';
            echo $ajax->remoteFunction(
                array(
                    'update' => 'FormpermfimoCoordonnees',
                    'url' => Router::url( array( 'action' => 'ajaxtiersprestaformpermfimo', Set::extract( $this->data, 'Formpermfimo.tiersprestataireapre_id' ) ), true )
                )
            ).';';
            echo $ajax->remoteFunction(
                array(
                    'update' => 'ActprofAdresseemployeur',
                    'url' => Router::url( array( 'action' => 'ajaxtiersprestaactprof', Set::extract( $this->data, 'Actprof.tiersprestataireapre_id' ) ), true )
                )
            ).';';
            echo $ajax->remoteFunction(
                array(
                    'update' => 'PermisbAdresseautoecole',
                    'url' => Router::url( array( 'action' => 'ajaxtiersprestapermisb', Set::extract( $this->data, 'Permisb.tiersprestataireapre_id' ) ), true )
                )
            ).';';
        ?>

    });
</script>

<div class="with_treemenu">
    <h1>Formulaire de demande de l'APRE COMPLÉMENTAIRE</h1>
<br />
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
                        <strong>Situation familiale : </strong><?php echo Set::enum( Set::classicExtract( $personne, 'Foyer.sitfam' ), $sitfam );?>
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
                    <td class="mediumsize noborder"><strong>Secteur professionnel en lien avec la demande <?php echo REQUIRED_MARK;?></strong></td>
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
                    <td class="activiteSize noborder" colspan="2"><strong>Secteur d'activité  </strong></td>
                </tr>
                <tr>
                    <td class="activiteSize noborder" colspan="2"><?php echo $xform->input( 'Apre.secteuractivite', array( 'domain' => 'apre', 'label' => false, 'type' => 'select', 'class' => 'activiteSize', 'options' => $sect_acti_emp, 'empty' => true ) );?></td>
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
            <legend>Structure référente</legend>
            <table class="wide noborder">
                <tr>
                    <td class="noborder">
                        <strong>Nom de l'organisme <?php echo REQUIRED_MARK;?></strong>
                        <?php echo $xform->input( 'Apre.structurereferente_id', array( 'domain' => 'apre', 'label' => false, 'type' => 'select', 'options' => $structs, 'empty' => true ) );?>
                        <?php echo $ajax->observeField( 'ApreStructurereferenteId', array( 'update' => 'StructurereferenteRef', 'url' => Router::url( array( 'action' => 'ajaxstruct' ), true ) ) ); ?>
                    </td>
                    <td class="noborder">
                        <strong>Nom du référent <?php echo REQUIRED_MARK;?></strong>
                        <?php echo $xform->input( 'Apre.referent_id', array( 'domain' => 'apre', 'label' => false, 'type' => 'select', 'options' => $referents, 'empty' => true ) );?>
                        <?php echo $ajax->observeField( 'ApreReferentId', array( 'update' => 'ReferentRef', 'url' => Router::url( array( 'action' => 'ajaxref' ), true ) ) ); ?>
                    </td>
                </tr>
                <tr>
                    <td class="wide noborder"><div id="StructurereferenteRef"></div></td>

                    <td class="wide noborder"><div id="ReferentRef"></div></td>
                </tr>
            </table>
        </fieldset>
        <fieldset class="wide">
            <legend>Justificatif</legend>
            <?php
                echo $xform->enum( 'Apre.justificatif', array(  'legend' => false, 'div' => false,  'required' => true, 'type' => 'radio', 'separator' => '<br />', 'options' => $options['justificatif'] ) );
            ?>
        </fieldset>
            <?php
                echo $xform->input( 'Pieceapre.Pieceapre', array( 'options' => $piecesapre, 'multiple' => 'checkbox',  'label' => 'Pièces jointes', ) );
            ?>

        <h2 class="center">Nature de la demande</h2>
        <br />
        <h3 class="center" style="font-style:italic">Liée à une Formation</h3>
        <fieldset>
            <?php
                /// Formation qualifiante
                $tmp = radioApre( $this, 'Apre.Natureaide', 'Formqualif', 'Formations individuelles qualifiantes' );
                echo $html->tag( 'h3', $tmp );
            ?>
            <fieldset id="Formqualif" class="invisible">
                <?php
                    $FormqualifId = Set::classicExtract( $this->data, 'Formqualif.id' );
                    if( $this->action == 'edit' && !empty( $FormqualifId ) ) {
                        echo $form->input( 'Formqualif.id', array( 'type' => 'hidden' ) );
                    }
                    echo $xform->input( 'Formqualif.intituleform', array(  'required' => true, 'domain' => 'apre' ) );
                    echo $xform->enum( 'Formqualif.tiersprestataireapre_id', array( 'required' => true, 'domain' => 'apre', 'options' => $tiersFormqualif, 'empty' => true ) );
                    echo $ajax->observeField( 'FormqualifTiersprestataireapreId', array( 'update' => 'FormqualifCoordonnees', 'url' => Router::url( array( 'action' => 'ajaxtiersprestaformqualif' ), true ) ) );
                    echo $html->tag(
                        'div',
                        $html->tag( 'div', ( isset( $FormqualifCoordonnees ) ? $FormqualifCoordonnees : ' ' ), array( 'id' => 'FormqualifCoordonnees' ) ).'<br />'
                    );

                    echo $xform->input( 'Formqualif.ddform', array( 'required' => true, 'domain' => 'apre', 'type' => 'date', 'dateFormat' => 'DMY' ) );
                    echo $xform->input( 'Formqualif.dfform', array( 'required' => true, 'domain' => 'apre', 'type' => 'date', 'dateFormat' => 'DMY' ) );
                    echo $xform->input( 'Formqualif.dureeform', array( 'required' => true, 'domain' => 'apre' ) );
                    echo $xform->input( 'Formqualif.modevalidation', array( 'domain' => 'apre' ) );
                    echo $xform->input( 'Formqualif.coutform', array( 'required' => true, 'domain' => 'apre' ) );
                    echo $xform->input( 'Formqualif.cofinanceurs', array( 'domain' => 'apre' ) );
                    echo $xform->input( 'Formqualif.montantaide', array( 'required' => true, 'domain' => 'apre' ) );;
                ?>
                <?php
                    $selected = Set::extract( $this->data, '/Formqualif/Pieceformqualif/id' );
                    echo $xform->input( 'Pieceformqualif.Pieceformqualif', array( 'options' => $piecesformqualif, 'multiple' => 'checkbox', 'label' => 'Pièces jointes','selected' => $selected ) );
                ?>
            </fieldset>
        </fieldset>
        <fieldset>
            <?php
                /// Formation qualifiante Perm FIMO
                $tmp = radioApre( $this, 'Apre.Natureaide', 'Formpermfimo', 'Formation permis de conduire Poids Lourd + FIMO' );
                echo $html->tag( 'h3', $tmp );
                /*$tmp = $form->radio( 'Apre.Natureaide', array( 'Formpermfimo' ), array( 'id' => 'ApreNatureaide1' ) );
                $tmp .= $html->tag( 'label', 'Formation permis de conduire Poids Lourd + FIMO', array( 'for' => 'ApreNatureaideFormpermfimo' ) );
                echo $html->tag( 'h3', $tmp );*/
            ?>
            <fieldset id="Formpermfimo" class="invisible">
                <?php
                    $FormpermfimoId = Set::classicExtract( $this->data, 'Formpermfimo.id' );
                    if( $this->action == 'edit' && !empty( $FormpermfimoId ) ) {
                        echo $form->input( 'Formpermfimo.id', array( 'type' => 'hidden' ) );
                    }
                    echo $xform->input( 'Formpermfimo.intituleform', array( 'required' => true, 'domain' => 'apre' ) );
//                     echo $xform->address( 'Formpermfimo.organismeform', array( 'required' => true, 'domain' => 'apre' ) );
                    echo $xform->enum( 'Formpermfimo.tiersprestataireapre_id', array( 'required' => true, 'domain' => 'apre', 'options' => $tiersFormpermfimo, 'empty' => true ) );
                    echo $ajax->observeField( 'FormpermfimoTiersprestataireapreId', array( 'update' => 'FormpermfimoCoordonnees', 'url' => Router::url( array( 'action' => 'ajaxtiersprestaformpermfimo' ), true ) ) );
                    echo $html->tag(
                        'div',
                        $html->tag( 'div', ( isset( $FormpermfimoCoordonnees ) ? $FormpermfimoCoordonnees : ' ' ), array( 'id' => 'FormpermfimoCoordonnees' ) ).'<br />'
                    );

                    echo $xform->input( 'Formpermfimo.ddform', array( 'required' => true, 'domain' => 'apre', 'type' => 'date', 'dateFormat' => 'DMY' ) );
                    echo $xform->input( 'Formpermfimo.dfform', array( 'required' => true, 'domain' => 'apre', 'type' => 'date', 'dateFormat' => 'DMY' ) );
                    echo $xform->input( 'Formpermfimo.dureeform', array( 'required' => true, 'domain' => 'apre' ) );
                    echo $xform->input( 'Formpermfimo.modevalidation', array( 'domain' => 'apre' ) );
                    echo $xform->input( 'Formpermfimo.coutform', array( 'required' => true, 'domain' => 'apre' ) );
                    echo $xform->input( 'Formpermfimo.cofinanceurs', array( 'domain' => 'apre' ) );
                    echo $xform->input( 'Formpermfimo.montantaide', array( 'required' => true, 'domain' => 'apre' ) );;
                ?>
                <?php
                    $selected = Set::extract( $this->data, '/Formpermfimo/Pieceformpermfimo/id' );
                    echo $xform->input( 'Pieceformpermfimo.Pieceformpermfimo', array( 'options' => $piecesformpermfimo, 'multiple' => 'checkbox', 'label' => 'Pièces jointes', 'selected' => $selected ) );
                ?>
            </fieldset>
        </fieldset>
        <fieldset>
            <?php
                /// Action de professionnalisation
                $tmp = radioApre( $this, 'Apre.Natureaide', 'Actprof', 'Action de professionnalisation des contrats aides et salariés dans les SIAE' );
                echo $html->tag( 'h3', $tmp );
                /*$tmp = $form->checkbox( 'Apre.Natureaide.Actprof' );
                $tmp .= $html->tag( 'label', 'Action de professionnalisation des contrats aides et salariés dans les SIAE', array( 'for' => 'ApreNatureaideActprof' ) );
                echo $html->tag( 'h3', $tmp );*/
            ?>
            <fieldset id="Actprof" class="invisible">
                <?php
                    $ActprofId = Set::classicExtract( $this->data, 'Actprof.id' );
                    if( $this->action == 'edit' && !empty( $ActprofId ) ) {
                        echo $form->input( 'Actprof.id', array( 'type' => 'hidden' ) );
                    }
                    echo $xform->enum( 'Actprof.tiersprestataireapre_id', array( 'required' => true, 'domain' => 'apre', 'options' => $tiersActprof, 'empty' => true ) );
                    echo $ajax->observeField( 'ActprofTiersprestataireapreId', array( 'update' => 'ActprofAdresseemployeur', 'url' => Router::url( array( 'action' => 'ajaxtiersprestaactprof' ), true ) ) );
                    echo $html->tag(
                        'div',
                        $html->tag( 'div', ( isset( $ActprofAdresseemployeur ) ? $ActprofAdresseemployeur : ' ' ), array( 'id' => 'ActprofAdresseemployeur' ) ).'<br />'
                    );
//                     echo $xform->input( 'Actprof.nomemployeur', array( 'required' => true, 'domain' => 'apre' ) );
//                     echo $xform->address( 'Actprof.adresseemployeur', array( 'required' => true, 'domain' => 'apre' ) );
                    echo $xform->enum( 'Actprof.typecontratact', array( 'required' => true, 'div' => false, 'legend' => 'Type de contrat', 'type' => 'radio', 'options' => $optionsacts['typecontratact'] ) );
                    echo $xform->input( 'Actprof.ddconvention', array( 'required' => true, 'domain' => 'apre', 'dateFormat' => 'DMY' ) );
                    echo $xform->input( 'Actprof.dfconvention', array( 'required' => true, 'domain' => 'apre', 'dateFormat' => 'DMY' ) );
                    echo $xform->input( 'Actprof.intituleformation', array( 'required' => true, 'domain' => 'apre' ) );
                    echo $xform->input( 'Actprof.ddform', array( 'required' => true, 'domain' => 'apre', 'dateFormat' => 'DMY' ) );
                    echo $xform->input( 'Actprof.dfform', array( 'required' => true, 'domain' => 'apre', 'dateFormat' => 'DMY' ) );
                    echo $xform->input( 'Actprof.dureeform', array( 'required' => true, 'domain' => 'apre' ) );
                    echo $xform->input( 'Actprof.modevalidation', array( 'domain' => 'apre' ) );;
                    echo $xform->input( 'Actprof.coutform', array('required' => true,  'domain' => 'apre' ) );
                    echo $xform->input( 'Actprof.cofinanceurs', array( 'domain' => 'apre' ) );
                    echo $xform->input( 'Actprof.montantaide', array( 'required' => true, 'domain' => 'apre' ) );
                ?>
                <?php
                    $selected = Set::extract( $this->data, '/Actprof/Pieceactprof/id' );
                    echo $xform->input( 'Pieceactprof.Pieceactprof', array( 'options' => $piecesactprof, 'multiple' => 'checkbox', 'label' => 'Pièces jointes', 'selected' => $selected ) );
                ?>
            </fieldset>
        </fieldset>
        <fieldset>
            <?php
                /// Permis B
                $tmp = radioApre( $this, 'Apre.Natureaide', 'Permisb', 'Permis de conduire B' );
                echo $html->tag( 'h3', $tmp );
                /*$tmp = $form->checkbox( 'Apre.Natureaide.Permisb' );
                $tmp .= $html->tag( 'label', 'Permis de conduire B', array( 'for' => 'ApreNatureaidePermisb' ) );
                echo $html->tag( 'h3', $tmp );*/
            ?>
            <fieldset id="Permisb" class="invisible">
                <?php
                    $PermisbId = Set::classicExtract( $this->data, 'Permisb.id' );
                    if( $this->action == 'edit' && !empty( $PermisbId ) ) {
                        echo $form->input( 'Permisb.id', array( 'type' => 'hidden' ) );
                    }

                    echo $xform->enum( 'Permisb.tiersprestataireapre_id', array( 'required' => true, 'domain' => 'apre', 'options' => $tiersPermisb, 'empty' => true ) );
                    echo $ajax->observeField( 'PermisbTiersprestataireapreId', array( 'update' => 'PermisbAdresseautoecole', 'url' => Router::url( array( 'action' => 'ajaxtiersprestapermisb' ), true ) ) );
                    echo $html->tag(
                        'div',
                        $html->tag( 'div', ( isset( $PermisbAdresseautoecole ) ? $PermisbAdresseautoecole : ' ' ), array( 'id' => 'PermisbAdresseautoecole' ) ).'<br />'
                    );
//                     echo $xform->input( 'Permisb.nomautoecole', array( 'required' => true, 'domain' => 'apre' ) );
//                     echo $xform->address( 'Permisb.adresseautoecole', array( 'required' => true, 'domain' => 'apre' ) );
                    echo $xform->input( 'Permisb.code',
                        array( 'div' => false, 'label' => 'Code', 'type' => 'checkbox' )
                    );
                    echo $xform->input( 'Permisb.conduite',
                        array( 'div' => false, 'label' => 'Conduite', 'type' => 'checkbox' )
                    );
                    echo $xform->input( 'Permisb.dureeform', array( 'required' => true, 'domain' => 'apre' ) );
                    echo $xform->input( 'Permisb.montantaide', array( 'required' => true, 'domain' => 'apre', 'maxlength' => 4 ) );
                ?>
                <?php
                    $selected = Set::extract( $this->data, '/Permisb/Piecepermisb/id' );
                    echo $xform->input( 'Piecepermisb.Piecepermisb', array( 'options' => $piecespermisb, 'multiple' => 'checkbox', 'label' => 'Pièces jointes', 'selected' => $selected ) );
                ?>
            </fieldset>
        </fieldset>
        <br />
        <h3 class="center" style="font-style:italic">Hors Formation</h3>
        <fieldset>
            <?php
                /// Amenagement logement
//                 $tmp = $form->checkbox( 'Apre.Natureaide.Amenaglogt' );
//                 $tmp .= $html->tag( 'label', 'Aide à l\'installation', array( 'for' => 'ApreNatureaideAmenaglogt' ) );
//                 echo $html->tag( 'h3', $tmp );
                $tmp = radioApre( $this, 'Apre.Natureaide', 'Amenaglogt', 'Aide à l\'installation' );
                echo $html->tag( 'h3', $tmp );
            ?>
            <fieldset id="Amenaglogt" class="invisible">
                <?php
                    $AmenaglogtId = Set::classicExtract( $this->data, 'Amenaglogt.id' );
                    if( $this->action == 'edit' && !empty( $AmenaglogtId ) ) {
                        echo $form->input( 'Amenaglogt.id', array( 'type' => 'hidden' ) );
                    }
                ?>
                <div class="demi">
                    <?php echo $form->input( 'Amenaglogt.typeaidelogement', array( 'label' => 'Type d\'aide au logement : ' , 'type' => 'radio', 'div' => false, 'separator' => '</div><div class="demi">', 'options' => $optionslogts['typeaidelogement'], 'legend' => false ) );?>
                </div>

                <?php
                    echo $xform->address( 'Amenaglogt.besoins', array( 'domain' => 'apre' ) );
                    echo $xform->input( 'Amenaglogt.montantaide', array( 'required' => true, 'domain' => 'apre' ) );
                ?>

                <?php
                    $selected = Set::extract( $this->data, '/Amenaglogt/Pieceamenaglogt/id' );
                    echo $xform->input( 'Pieceamenaglogt.Pieceamenaglogt', array( 'options' => $piecesamenaglogt, 'multiple' => 'checkbox', 'label' => 'Pièces jointes', 'selected' => $selected ) );
                ?>
            </fieldset>
        </fieldset>
        <fieldset>
            <?php
                /// Accompagnement à la création d'entreprise
//                 $tmp = $form->checkbox( 'Apre.Natureaide.Acccreaentr' );
//                 $tmp .= $html->tag( 'label', 'Accompagnement à la création d\'entreprise', array( 'for' => 'ApreNatureaideAcccreaentr' ) );
//                 echo $html->tag( 'h3', $tmp );
                $tmp = radioApre( $this, 'Apre.Natureaide', 'Acccreaentr', 'Accompagnement à la création d\'entreprise' );
                echo $html->tag( 'h3', $tmp );
            ?>
            <fieldset id="Acccreaentr" class="invisible">
                <?php
                    $AcccreaentrId = Set::classicExtract( $this->data, 'Acccreaentr.id' );
                    if( $this->action == 'edit' && !empty( $AcccreaentrId ) ) {
                        echo $form->input( 'Acccreaentr.id', array( 'type' => 'hidden' ) );
                    }
                    echo $xform->enum( 'Acccreaentr.nacre', array( 'required' => true, 'legend' => 'Dispositif Nacre', 'div' => false, 'type' => 'radio', 'options' => $optionscrea['nacre'] ) );
                    echo $xform->enum( 'Acccreaentr.microcredit', array( 'required' => true, 'legend' => 'Dispositif Micro-crédit', 'div' => false, 'type' => 'radio', 'options' => $optionscrea['microcredit'] ) );
                    echo $xform->address( 'Acccreaentr.projet', array( 'domain' => 'apre' ) );
                    echo $xform->input( 'Acccreaentr.montantaide', array( 'required' => true, 'domain' => 'apre' ) );
                ?>
                <?php
                    $selected = Set::extract( $this->data, '/Acccreaentr/Pieceacccreaentr/id' );
                    echo $xform->input( 'Pieceacccreaentr.Pieceacccreaentr', array( 'options' => $piecesacccreaentr, 'multiple' => 'checkbox', 'label' => 'Pièces jointes', 'selected' => $selected ) );
                ?>
            </fieldset>
        </fieldset>
        <fieldset>
            <?php
                /// Acquisition de matériels professionnels
//                 $tmp = $form->checkbox( 'Apre.Natureaide.Acqmatprof' );
//                 $tmp .= $html->tag( 'label', 'Acquisition de matériels professionnels', array( 'for' => 'ApreNatureaideAcqmatprof' ) );
//                 echo $html->tag( 'h3', $tmp );
                $tmp = radioApre( $this, 'Apre.Natureaide', 'Acqmatprof', 'Acquisition de matériels professionnels' );
                echo $html->tag( 'h3', $tmp );
            ?>
            <fieldset id="Acqmatprof" class="invisible">
                <?php
                    $AcqmatprofId = Set::classicExtract( $this->data, 'Acqmatprof.id' );
                    if( $this->action == 'edit' && !empty( $AcqmatprofId ) ) {
                        echo $form->input( 'Acqmatprof.id', array( 'type' => 'hidden' ) );
                    }
                    echo $xform->address( 'Acqmatprof.besoins', array( 'domain' => 'apre' ) );
                    echo $xform->input( 'Acqmatprof.montantaide', array( 'required' => true, 'domain' => 'apre' ) );
                ?>

                <?php
                    $selected = Set::extract( $this->data, '/Acqmatprof/Pieceacqmatprof/id' );
                    echo $xform->input( 'Pieceacqmatprof.Pieceacqmatprof', array( 'options' => $piecesacqmatprof, 'multiple' => 'checkbox', 'label' => 'Pièces jointes', 'selected' => $selected ) );
                ?>
            </fieldset>
        </fieldset>
        <fieldset>
            <?php
                /// Aide à la location d'un véhicule d'insertion
//                 $tmp = $form->checkbox( 'Apre.Natureaide.Locvehicinsert' );
//                 $tmp .= $html->tag( 'label', 'Aide à la location d\'un véhicule d\'insertion', array( 'for' => 'ApreNatureaideLocvehicinsert' ) );
//                 echo $html->tag( 'h3', $tmp );
                $tmp = radioApre( $this, 'Apre.Natureaide', 'Locvehicinsert', 'Aide à la location d\'un véhicule d\'insertion' );
                echo $html->tag( 'h3', $tmp );
            ?>
            <fieldset id="Locvehicinsert" class="invisible">
                <?php
                    $LocvehicinsertId = Set::classicExtract( $this->data, 'Locvehicinsert.id' );
                    if( $this->action == 'edit' && !empty( $LocvehicinsertId ) ) {
                        echo $form->input( 'Locvehicinsert.id', array( 'type' => 'hidden' ) );
                    }
                    echo $xform->input( 'Locvehicinsert.societelocation', array('required' => true,  'domain' => 'apre' ) );
                    echo $xform->input( 'Locvehicinsert.dureelocation', array( 'required' => true, 'domain' => 'apre' ) );
                    echo $xform->input( 'Locvehicinsert.montantaide', array('required' => true,  'domain' => 'apre' ) );
                ?>
                <?php
                    $selected = Set::extract( $this->data, '/Locvehicinsert/Piecelocvehicinsert/id' );
                    echo $xform->input( 'Piecelocvehicinsert.Piecelocvehicinsert', array( 'options' => $pieceslocvehicinsert, 'multiple' => 'checkbox', 'label' => 'Pièces jointes', 'selected' => $selected ) );
                ?>
            </fieldset>
        </fieldset>
        <fieldset class="aere">
            <legend>Avis technique et motivé du référent (Article 5.1 relatif au règlement de l'APRE): </legend>
            <?php
                echo $xform->input(  'Apre.avistechreferent', array( 'domain' => 'apre', 'label' => false ) );?>
        </fieldset>
    </div>

    <div class="submit">
        <?php echo $form->submit( 'Enregistrer', array( 'div' => false ) );?>
        <?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
    </div>
    <?php echo $form->end();?>
</div>
<div class="clearer"><hr /></div>