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
        //Données pour le type d'activité du bénéficiare
        ['F', 'C', 'P'].each( function( letter ) {
            observeDisableFieldsOnValue( 'ApreActivitebeneficiaire' + letter, [ 'ApreDateentreeemploiDay', 'ApreDateentreeemploiMonth', 'ApreDateentreeemploiYear', 'ApreTypecontratCDI', 'ApreTypecontratCDD', 'ApreTypecontratCON', 'ApreTypecontratAUT', 'AprePrecisionsautrecontrat', 'ApreNbheurestravaillees', 'ApreNomemployeur', 'ApreAdresseemployeur', 'ApreSecteuractivite' ],  letter, true );
        } );
        observeDisableFieldsOnValue( 'ApreActivitebeneficiaireE', [ 'ApreDateentreeemploiDay', 'ApreDateentreeemploiMonth', 'ApreDateentreeemploiYear', 'ApreTypecontratCDI', 'ApreTypecontratCDD', 'ApreTypecontratCON', 'ApreTypecontratAUT', 'AprePrecisionsautrecontrat', 'ApreNbheurestravaillees', 'ApreNomemployeur', 'ApreAdresseemployeur', 'ApreSecteuractivite' ], 'E', false );

        observeDisableFieldsetOnCheckbox( 'ApreNatureaideAmenaglogt', $( 'Amenaglogt' ), false, true );
        observeDisableFieldsetOnCheckbox( 'ApreNatureaideAcccreaentr', $( 'Acccreaentr' ), false, true );
        observeDisableFieldsetOnCheckbox( 'ApreNatureaideAcqmatprof', $( 'Acqmatprof' ), false, true );
        observeDisableFieldsetOnCheckbox( 'ApreNatureaideLocvehicinsert', $( 'Locvehicinsert' ), false, true );

        ['Formqualif', 'Formpermfimo', 'Actprof', 'Permisb'].each( function( formation ) {
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
            <legend>Identité du bénéficiaire de la demande</legend>
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
                        <br />
                        <strong>Situation familiale : </strong><?php echo Set::enum( Set::classicExtract( $personne, 'Foyer.sitfam' ), $sitfam );?>
                    </td>
                    <td class="mediumSize noborder">
                        <strong>N° Service instructeur : </strong><?php echo Set::extract( 'Serviceinstructeur.lib_service', $typeservice );?>
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
                        <br />
                        <strong>Nbre d'enfants : </strong><?php echo $nbEnfants;?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="mediumSize noborder">
                        <strong>Adresse : </strong><br /><?php echo Set::extract( $personne, 'Adresse.numvoie' ).' '.Set::extract( $typevoie, Set::extract( $personne, 'Adresse.typevoie' ) ).' '.Set::extract( $personne, 'Adresse.nomvoie' ).'<br /> '.Set::extract( $personne, 'Adresse.codepos' ).' '.Set::extract( $personne, 'Adresse.locaadr' );?>
                    </td>
                </tr>
                <tr>
                    <td class="mediumSize noborder">
                        <strong>Tél. fixe : </strong><?php echo Set::extract( $personne, 'Foyer.Modecontact.0.numtel' );?>
                    </td>
                    <td class="mediumSize noborder">
                        <strong>Tél. portable : </strong><?php echo ''/*.Set::extract( $foyer, 'Modecontact.0.numtel' );*/?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="mediumSize noborder">
                        <strong>Adresse mail : </strong><?php echo Set::extract( $personne, 'Foyer.Modecontact.0.numtel' );?> <!-- FIXME -->
                    </td>
                </tr>
            </table>
        </fieldset>

        <fieldset>
            <legend>Prescripteur</legend>
            <table class="wide noborder">
                <tr>
                    <td class="noborder">
                        <strong>Nom de l'organisme</strong>
                        <?php echo $xform->input( 'Apre.structurereferente_id', array( 'domain' => 'apre', 'label' => false, 'type' => 'select', 'options' => $structs, 'empty' => true ) );?>
                        <?php echo $ajax->observeField( 'ApreStructurereferenteId', array( 'update' => 'StructurereferenteRef', 'url' => Router::url( array( 'action' => 'ajaxstruct' ), true ) ) ); ?> 
                    </td>
                    <td class="noborder">
                        <strong>Nom du référent</strong>
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

        <!-- Aides aux démarches à la reprise d'emploi -->
        <fieldset>
            <?php
                /// Aides aux démarches à la reprise d'emploi
                $tmp = $form->checkbox( 'Apre.Natureaide.Locvehicinsert' );
                $tmp .= $html->tag( 'label', 'Aides aux démarches à la reprise d\'emploi', array( 'for' => 'ApreNatureaideLocvehicinsert' ) );
                echo $html->tag( 'h3', $tmp );
            ?>
        </fieldset>
<!--
        <fieldset>
            <?php
                /// Aide à la location d'un véhicule d'insertion
                $tmp = $form->checkbox( 'Apre.Natureaide.Locvehicinsert' );
                $tmp .= $html->tag( 'label', 'Aide à la location d\'un véhicule d\'insertion', array( 'for' => 'ApreNatureaideLocvehicinsert' ) );
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
                <fieldset>
                    <legend>Pièces jointes</legend>
                    <?php
                        $selected = Set::extract( $this->data, '/Locvehicinsert/Piecelocvehicinsert/id' );
                        echo $xform->input( 'Piecelocvehicinsert.Piecelocvehicinsert', array( 'options' => $pieceslocvehicinsert, 'multiple' => 'checkbox', 'label' => false, 'selected' => $selected ) ); ?>
                </fieldset>
            </fieldset>
        </fieldset>  -->
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