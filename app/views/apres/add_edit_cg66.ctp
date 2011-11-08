<?php
    echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
    $this->modelClass = $this->params['models'][0];

    $this->pageTitle = 'APRE';

    echo $this->element( 'dossier_menu', array( 'id' => $dossier_id ) );

    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout APRE';
    }
    else {
        $this->pageTitle = 'Édition APRE';
    }


	if( Configure::read( 'debug' ) > 0 ) {
		echo $javascript->link( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}
?>
    <script type="text/javascript">
        document.observe("dom:loaded", function() {
            dependantSelect(
                '<?php echo $this->modelClass;?>ReferentId',
                '<?php echo $this->modelClass;?>StructurereferenteId'
            );
        });
    </script>

    <script type="text/javascript">
        document.observe("dom:loaded", function() {
            dependantSelect(
                'Aideapre66Typeaideapre66Id',
                'Aideapre66Themeapre66Id'
            );


//                 observeDisableFieldsOnRadioValue(
//                     'Apre',
//                     'data[Apre66][activitebeneficiaire]',
//                     [ 
//                         '<?php echo $this->modelClass;?>Typecontrat_',
//                         '<?php echo $this->modelClass;?>TypecontratCDI',
//                         '<?php echo $this->modelClass;?>TypecontratCDD',
//                         '<?php echo $this->modelClass;?>TypecontratCON',
//                         '<?php echo $this->modelClass;?>TypecontratAUT',
//                         '<?php echo $this->modelClass;?>Dureecontrat',
//                         //'<?php echo $this->modelClass;?>Nbheurestravaillees',
//                         '<?php echo $this->modelClass;?>Nomemployeur',
//                         '<?php echo $this->modelClass;?>Adresseemployeur'
//                     ],
//                     ['C', 'P', 'F', undefined],
//                     false
//                 );

/***************************************************************************/






            observeDisableFieldsetOnCheckbox( '<?php echo $this->modelClass;?>Hasfrais', $( 'Fraisdeplacement66Destination' ).up( 'fieldset' ), false );

            observeDisableFieldsetOnRadioValue(
                'Apre',
                'data[Aideapre66][versement]',
                $( 'Soussigne' ),
                'TIE',
                false,
                true
            );

//             observeDisableFieldsetOnRadioValue(
//                 'Apre',
//                 'data[<?php echo $this->modelClass;?>][isdecision]',
//                 $( 'DecisionApre' ),
//                 'O',
//                 false,
//                 true
//             );

        });



    <?php
		$url = Router::url(
			array(
				'action' => 'ajaxpiece',
				'typeaideapre66_id' => Set::classicExtract( $this->data, 'Aideapre66.typeaideapre66_id' ),
				'pieceadmin' => implode( ',', ( isset( $this->data['Pieceaide66']['Pieceaide66'] ) ? (array)$this->data['Pieceaide66']['Pieceaide66'] : array() ) ),
				'piececomptable' => implode( ',', ( isset( $this->data['Piececomptable66']['Piececomptable66'] ) ? (array)$this->data['Piececomptable66']['Piececomptable66'] : array() ) )
			),
			true
		);

        echo $ajax->remoteFunction(
            array(
                'update' => 'Piece66',
                'url' => $url
            )
        );
    ?>
    </script>

<!--/************************************************************************/ -->

<!--/************************************************************************/ -->

<script type="text/javascript">
    document.observe("dom:loaded", function() {
        //Données pour le type d'activité du bénéficiare
//         ['CDD', 'CDI', 'CON' ].each( function( letter ) {
//             observeDisableFieldsOnValue(
//                 '<?php echo $this->modelClass;?>Typecontrat' + letter,
//                 [
//                     '<?php echo $this->modelClass;?>Precisionsautrecontrat'
//                 ],
//                 letter,
//                 true
//             );
//         } );
//         observeDisableFieldsOnValue(
//             '<?php echo $this->modelClass;?>TypecontratAUT',
//             [
//                 '<?php echo $this->modelClass;?>Precisionsautrecontrat'
//             ],
//             'AUT',
//             false
//         );
// 
//         observeDisableFieldsOnValue(
//             '<?php echo $this->modelClass;?>TypecontratAUT',
//             [
//                 '<?php echo $this->modelClass;?>Precisionsautrecontrat'
//             ],
//             'AUT',
//             false
//         );

//         observeDisableFieldsOnValue(
//             'Aideapre66DecisionapreREF',
//             [
//                 'Aideapre66Montantaccorde'
//             ],
//             'REF',
//             true
//         );
        //Données pour le type d'activité du bénéficiare
//         observeDisableFieldsOnValue(
//             'Aideapre66DecisionapreACC',
//             [
//                 'Aideapre66Montantaccorde'
//             ],
//             'ACC',
//             false
//         );

//         observeDisableFieldsOnValue(
//             'Aideapre66DecisionapreREF',
//             [
//                 'Aideapre66Motifrejetequipe'
//             ],
//             'REF',
//             false
//         );
        //Données pour le type d'activité du bénéficiare
//         observeDisableFieldsOnValue(
//             'Aideapre66DecisionapreACC',
//             [
//                 'Aideapre66Motifrejetequipe'
//             ],
//             'ACC',
//             true
//         );

        <?php
            echo $ajax->remoteFunction(
                array(
                    'update' => 'StructurereferenteRef',
                    'url' => Router::url(
                        array(
                            'action' => 'ajaxstruct',
                            Set::extract( $this->data, "{$this->modelClass}.structurereferente_id" )
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
                            Set::extract( $this->data, "{$this->modelClass}.referent_id" )
                        ),
                        true
                    )
                )
            ).';';
        ?>

    });
</script>

<div class="with_treemenu">
    <h1>Formulaire de demande de l'APRE</h1>
<br />
    <?php
        echo $form->create( 'Apre', array( 'type' => 'post', 'id' => 'Apre', 'url' => Router::url( null, true ) ) );
        $ApreId = Set::classicExtract( $this->data, "{$this->modelClass}.id" );
        if( $this->action == 'edit' ) {
            echo '<div>';
            echo $form->input( "{$this->modelClass}.id", array( 'type' => 'hidden' ) );
            echo $form->input( 'Modecontact.0.id', array( 'type' => 'hidden' ) );
            echo $form->input( 'Modecontact.1.id', array( 'type' => 'hidden' ) );
            echo '</div>';
        }
        echo '<div>';
        echo $form->input( 'Modecontact.0.foyer_id', array( 'type' => 'hidden', 'value' => $foyer_id ) );
        echo $form->input( 'Modecontact.1.foyer_id', array( 'type' => 'hidden', 'value' => $foyer_id ) );

        echo $form->input( "{$this->modelClass}.personne_id", array( 'type' => 'hidden', 'value' => $personne_id ) );
        echo '</div>';
    ?>

    <div class="aere">
        <fieldset>
            <table class="wide noborder">
                <tr>
                    <td class="mediumSize noborder">
                        <?php echo $form->input( "{$this->modelClass}.numeroapre", array( 'type' => 'hidden', 'value' => $numapre ) ); ?>
                        <strong>Numéro de l'APRE : </strong><?php echo $numapre; ?>
                    </td>
                </tr>
            </table>
        </fieldset>

        <fieldset>
            <legend>Demandeur</legend>
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
                        <!-- <br />
                         <strong>Situation familiale : </strong><?php /*echo Set::enum( Set::classicExtract( $personne, 'Foyer.sitfam' ), $sitfam );*/?> -->
                    </td>
                    <td class="mediumSize noborder">
                        <strong>N° Service instructeur : </strong><?php echo Set::classicExtract( $personne, 'Serviceinstructeur.lib_service' );?>
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
                        <!-- <br />
                        <strong>Nbre d'enfants : </strong><?php /*echo $nbEnfants;*/?> -->
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="mediumSize noborder">
                        <strong>Adresse : </strong><br /><?php echo Set::classicExtract( $personne, 'Adresse.numvoie' ).' '.Set::enum( Set::classicExtract( $personne, 'Adresse.typevoie' ), $typevoie ).' '.Set::classicExtract( $personne, 'Adresse.nomvoie' ).'<br /> '.Set::classicExtract( $personne, 'Adresse.codepos' ).' '.Set::classicExtract( $personne, 'Adresse.locaadr' );?>
                    </td>
                </tr>
                <tr>
                    <td class="mediumSize noborder">
                        <strong>Tél. fixe : </strong>
                        <?php
                            $numtelfixe = Set::classicExtract( $personne, 'Foyer.Modecontact.0.numtel' );
                            if( !empty( $numtelfixe ) ) {
                                echo Set::extract( $personne, 'Foyer.Modecontact.0.numtel' );
                            }
                            else{
                                echo $xform->input( 'Modecontact.0.numtel', array( 'label' => false, 'type' => 'text' ) );
                            }
                        ?>
                    </td>
                    <td class="mediumSize noborder">
                        <strong>Tél. portable : </strong>
                        <?php
                            $numtelport = Set::extract( $personne, 'Foyer.Modecontact.1.numtel' );
                            if( !empty( $numtelport ) ) {
                                echo Set::extract( $personne, 'Foyer.Modecontact.1.numtel' );
                            }
                            else{
                                echo $xform->input( 'Modecontact.1.numtel', array( 'label' => false, 'type' => 'text' ) );
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="mediumSize noborder">
                        <strong>Adresse mail : </strong>
                        <?php
                            $email = Set::extract( $personne, 'Foyer.Modecontact.0.adrelec' );
                            if( !empty( $email ) ) {
                                echo Set::extract( $personne, 'Foyer.Modecontact.0.adrelec' );
                            }
                            else{
                                echo $xform->input( 'Modecontact.0.adrelec', array( 'label' => false, 'type' => 'text' ) );
                            }
                        ?>
                    </td>
                </tr>
            </table>
        </fieldset>

        <fieldset>
            <legend>Référent ou prescripteur habilité</legend>
            <table class="wide noborder">
                <tr>
                    <td class="noborder">
                        <strong>Nom de l'organisme</strong>
                        <?php echo $xform->input( "{$this->modelClass}.structurereferente_id", array( 'domain' => 'apre', 'label' => false, 'type' => 'select', 'options' => $structs, 'selected' => $struct_id,  'empty' => true ) );?>
                        <?php echo $ajax->observeField( $this->modelClass.'StructurereferenteId', array( 'update' => 'StructurereferenteRef', 'url' => Router::url( array( 'action' => 'ajaxstruct' ), true ) ) ); ?> 
                    </td>
                    <td class="noborder">
                        <strong>Nom du référent</strong>
                        <?php echo $xform->input( "{$this->modelClass}.referent_id", array( 'domain' => 'apre', 'label' => false, 'type' => 'select', 'options' => $referents, 'selected' => $struct_id.'_'.$referent_id, 'empty' => true ) );?>
                        <?php echo $ajax->observeField( $this->modelClass.'ReferentId', array( 'update' => 'ReferentRef', 'url' => Router::url( array( 'action' => 'ajaxref' ), true ) ) ); ?>
                    </td>
                </tr>
                <tr>
                    <td class="wide noborder"><div id="StructurereferenteRef"></div></td>

                    <td class="wide noborder"><div id="ReferentRef"></div></td>
                </tr>
            </table>
        </fieldset>

<script type="text/javascript">
    Event.observe( $( 'Apre66StructurereferenteId' ), 'change', function( event ) {
        $( 'ReferentRef' ).update( '' );
    } );
</script>

         <fieldset>
            <legend>Activité (Emploi, formation, Création d'entreprise)</legend>
            <table class="wide noborder">
                <tr>
                    <td class="mediumsize noborder"><strong>Type d'activité </strong></td>
                    <td class="mediumsize noborder"><?php echo $xform->enum( "{$this->modelClass}.activitebeneficiaire", array( 'legend' => __d( 'apre', 'Apre.activitebeneficiaire', true ), 'type' => 'radio', 'separator' => '<br />', 'options' => array( 'P' => 'Recherche d\'Emploi', 'E' => 'Emploi' , 'F' => 'Formation', 'C' => 'Création d\'Entreprise' ) ) );?></td>
                </tr>
            </table>
        </fieldset>

		<fieldset>
			<?php
				//Ajout des 3 checkbox pour les APREs 66 concernant le droit ou non à une APRE
				echo $xform->input( "{$this->modelClass}.isbeneficiaire", array( 'label' => __d( 'apre', 'Apre66.isbeneficiaire', true ), 'type' => 'checkbox' ) );
				echo $xform->input( "{$this->modelClass}.hascer", array( 'label' => __d( 'apre', 'Apre66.hascer', true ), 'type' => 'checkbox' ) );
				echo $xform->input( "{$this->modelClass}.respectdelais", array( 'label' => __d( 'apre', 'Apre66.respectdelais', true ), 'type' => 'checkbox' ) );
			?>
		</fieldset>
<fieldset>
    <legend><strong>Aide demandée</strong></legend>
    <?php

        $Aideapre66Id = Set::classicExtract( $this->data, 'Aideapre66.id' );
        $Fraisdeplacement66Id = Set::classicExtract( $this->data, 'Fraisdeplacement66.id' );
        $ApreId = Set::classicExtract( $this->data, "{$this->modelClass}.id" );


        if( $this->action == 'edit' && !empty( $Aideapre66Id ) ) {
            echo $form->input( 'Aideapre66.id', array( 'type' => 'hidden' ) );
            echo $form->input( 'Fraisdeplacement66.id', array( 'type' => 'hidden' ) );
            echo $form->input( 'Aideapre66.apre_id', array( 'type' => 'hidden', 'value' => $ApreId ) );
            echo $form->input( 'Fraisdeplacement66.aideapre66_id', array( 'type' => 'hidden', 'value' => $Aideapre66Id ) );

        }

        echo $default->subform(
            array(
                'Aideapre66.themeapre66_id' => array( 'options' => $themes ),
                'Aideapre66.typeaideapre66_id' => array( 'options' => $typesaides )
            ),
            array(
                'options' => $options
            )
        );
        
		$url = Router::url(
			array(
				'action' => 'ajaxpiece',
				'typeaideapre66_id' => Set::classicExtract( $this->data, 'Aideapre66.typeaideapre66_id' ),
				'pieceadmin' => implode( ',', ( isset( $this->data['Pieceaide66']['Pieceaide66'] ) ? (array)$this->data['Pieceaide66']['Pieceaide66'] : array() ) ),
				'piececomptable' => implode( ',', ( isset( $this->data['Piececomptable66']['Piececomptable66'] ) ? (array)$this->data['Piececomptable66']['Piececomptable66'] : array() ) )
			),
			true
		);

        echo $ajax->observeField(
			'Aideapre66Typeaideapre66Id',
			array(
				'update' => 'Piece66',
				'url' => $url
			)
		);

        echo $xhtml->tag( 'div', null, array( 'id' => 'Piece66' ) );
        echo $xhtml->tag( '/div' );


        echo $default->subform(
            array(
                'Aideapre66.virement' => array( 'domain' => 'aideapre66', 'type' => 'radio', 'options' => $options['virement'], 'separator' => '<br />' ),
                'Aideapre66.versement' => array( 'domain' => 'aideapre66', 'type' => 'radio', 'options' => $options['versement'], 'separator' => '<br />' )
            ),
            array(
                'options' => $options
            )
        );

        echo $xhtml->tag(
            'fieldset',
            'Je soussigné '. '<strong>'.Set::enum( Set::classicExtract( $personne, 'Personne.qual') , $qual ).' '.$personne['Personne']['nom'].' '.$personne['Personne']['prenom'].'</strong>'.' souhaite que mon aide ( si elle est acceptée ) soit versée sur le compte du '.$default->subform( 'Aideapre66.creancier', array( 'rows' => 2 ) ),
            array( 'id' => 'Soussigne' )
        );

        echo $default->subform(
            array(
                'Aideapre66.datedemande' => array( 'empty' => false )
            )
        );
    ?>
</fieldset>
<?php
    if( !empty( $listApres ) ) {
        $Aideapre66Id = Set::extract( $listesAidesSelonApre, '/Aideapre66/apre_id' );
    }
?>
<fieldset>
    <legend>Attributions antérieures de l'APRE (le cas échéant)</legend>
    <?php if( !empty( $listesAidesSelonApre ) ):?>
        <table>
            <thead>
                <tr>
                    <th>Date de demande de l'APRE</th>
                    <th>Thème de l'aide</th>
                    <th>Type d'aide</th>
                    <th>Montant accordé</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach( $listesAidesSelonApre as $i => $liste ){

                        echo $xhtml->tableCells(
                            array(
                                h( date_short( Set::classicExtract( $liste, 'Aideapre66.datedemande' ) ) ),
                                h( Set::enum( Set::classicExtract( $liste, 'Aideapre66.themeapre66_id' ), $themes ) ),
                                h( Set::enum( Set::classicExtract( $liste, 'Aideapre66.typeaideapre66_id' ), $nomsTypeaide ) ),
                                h( $locale->money( Set::classicExtract( $liste, 'Aideapre66.montantpropose' ) ) ),
                            )
                        );
                    }
                ?>
            </tbody>
        </table>
    <?php else:?>
        <p class="notice">Aucune APRE antérieure présente pour cette personne</p>
    <?php endif;?>
</fieldset>
<?php
    echo $xform->input( "{$this->modelClass}.hasfrais", array( 'label' => 'Présence de frais', 'type' => 'checkbox' ) );
?>
<fieldset id="Hasfrais">
    <legend><strong>Calcul des frais de déplacements, d'hébergement et de restauration</strong></legend>
    <?php
        $tmp = array(
            'Fraisdeplacement66.lieuresidence' => Set::extract( $personne, 'Adresse.numvoie' ).' '.Set::extract( $typevoie, Set::extract( $personne, 'Adresse.typevoie' ) ).' '.Set::extract( $personne, 'Adresse.nomvoie' ).' '.Set::extract( $personne, 'Adresse.codepos' ).' '.Set::extract( $personne, 'Adresse.locaadr' )
        );
        echo $default->view(
            Xset::bump( $tmp ),
            array(
                'Fraisdeplacement66.lieuresidence'
            ),
            array(
                'class' => 'inform'
            )
        );

        echo $xform->input( 'Fraisdeplacement66.destination', array( 'label' => __d( 'fraisdeplacement66', "Fraisdeplacement66.destination", true ), 'type' => 'text' ) );
    ?>

	<div class="fraisdepct">
		<table class="fraisdepct">
			<caption>Véhicule personnel</caption>
			<tbody>
				<tr>
					<th>Nb km par trajet</th>
					<td colspan="2"  class="fraisdepct"><?php echo $xform->input( 'Fraisdeplacement66.nbkmvoiture', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
				</tr>
				<tr>
					<th>Nb trajet </th>
					<td colspan="2"  class="fraisdepct"><?php echo $xform->input( 'Fraisdeplacement66.nbtrajetvoiture', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
				</tr>
                <tr>
                    <th>Nb total km</th>
                    <td colspan="2"  class="fraisdepct"><span></span><?php echo $xform->input( 'Fraisdeplacement66.nbtotalkm', array( 'label' => false, 'div' => false, 'type' => 'hidden' ) );?> </td>
                </tr>
				<tr>
					<th>Forfait "Km"</th>
					<td colspan="2"  class="fraisdepct"><?php echo $locale->money( Configure::read( 'Fraisdeplacement66.forfaitvehicule' ) );?></td>
				</tr>
				<tr>
					<th>Total</th>
					<td colspan="2"  class="fraisdepct noborder"><span></span><?php echo $xform->input( 'Fraisdeplacement66.totalvehicule', array( 'label' => false, 'div' => false, 'type' => 'hidden' ) );?> &euro;</td>
<!--                     <td  class="fraisdepct noborder">&euro;</td> -->
				</tr>
			</tbody>
		</table>
	</div>

	<div class="fraisdepct">
		<table class="fraisdepct">
			<caption>Transport public</caption>
			<tbody>
				<tr>
					<th>Nb trajet</th>
					<td colspan="2"  class="fraisdepct"><?php echo $xform->input( 'Fraisdeplacement66.nbtrajettranspub', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
				</tr>
				<tr>
					<th>Prix billet </th>
					<td colspan="2"  class="fraisdepct"><?php echo $xform->input( 'Fraisdeplacement66.prixbillettranspub', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
				</tr>
				<tr>
					<th>Total</th>
					<td colspan="2" class="fraisdepct noborder"><span></span><?php echo $xform->input( 'Fraisdeplacement66.totaltranspub', array( 'label' => false, 'div' => false, 'type' => 'hidden' ) );?> &euro;</td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="fraisdepct">
		<table class="fraisdepct">
			<caption>Hébergement</caption>
			<tbody>
				<tr>
					<th>Nb nuitées</th>
					<td colspan="2"  class="fraisdepct"><?php echo $xform->input( 'Fraisdeplacement66.nbnuithebergt', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
				</tr>
				<tr>
					<th>Forfait "nuitées"</th>
					<td colspan="2"  class="fraisdepct"><?php echo $locale->money( Configure::read( 'Fraisdeplacement66.forfaithebergt' ) );?></td>
				</tr>
				<tr>
					<th>Total</th>
					<td class="fraisdepct noborder"><span></span><?php echo $xform->input( 'Fraisdeplacement66.totalhebergt', array( 'label' => false, 'div' => false, 'type' => 'hidden' ) );?> &euro;</td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="fraisdepct">
		<table class="fraisdepct">
			<caption>Repas</caption>
			<tbody>
				<tr>
					<th>Nb repas</th>
					<td colspan="2"  class="fraisdepct"><?php echo $xform->input( 'Fraisdeplacement66.nbrepas', array( 'label' => false, 'div' => false, 'type' => 'text' ) );?></td>
				</tr>
				<tr>
					<th>Forfait "Repas"</th>
					<td colspan="2" class="fraisdepct"><?php echo $locale->money( Configure::read( 'Fraisdeplacement66.forfaitrepas' ) );?></td>
				</tr>
				<tr>
					<th>Total</th>
					<td class="fraisdepct noborder"><span></span><?php echo $xform->input( 'Fraisdeplacement66.totalrepas', array( 'label' => false, 'div' => false, 'type' => 'hidden' ) );?> &euro;</td>
				</tr>
			</tbody>
		</table>
	</div>

</fieldset>

<fieldset class="aere">
    <legend><strong>Observations du référent</strong></legend>
    <?php
        echo $xform->input(  "{$this->modelClass}.avistechreferent", array( 'domain' => 'apre', 'label' => false, 'type' => 'textarea', 'required' => true ) );
    ?>
</fieldset>

<fieldset>
    <legend><strong>Administration</strong></legend>
        <?php
            echo $default->subform(
                array(
                    'Aideapre66.motifrejet',
                    'Aideapre66.montantpropose' => array( 'type' => 'text' ),
                    'Aideapre66.datemontantpropose' => array( 'empty' => false )
                )
            );
        ?>
</fieldset>

<?php if( $this->action == 'edit' ):?>
	<?php
// 		$error = Set::classicExtract( $this->validationErrors, "{$this->modelClass}.isdecision" );
// 		$class = 'radio'.( !empty( $error ) ? ' error' : '' );
// 		$thisDataIsDecision = Set::classicExtract( $this->data, "{$this->modelClass}.isdecision" );
// 		if( !empty( $thisDataIsDecision ) ) {
// 			$valueIsDecision = $thisDataIsDecision;
// 		}
// 		$input = $form->input( "{$this->modelClass}.isdecision", array( 'type' => 'radio' , 'options' => $options['isdecision'], 'legend' => required( __d( 'apre', "{$this->modelClass}.isdecision", true )  ), 'value' => $valueIsDecision ) );
// 		echo $xhtml->tag( 'div', $input, array( 'class' => $class ) );
	?>

	<fieldset id="DecisionApre">
		<legend><strong>Décision et engagement financier de l'équipe de direction</strong></legend>
			<?php
				$avis = Set::classicExtract( $this->data, 'Aideapre66.decisionapre' );
				if( !empty( $avis ) ){
					$tmp = array(
						'Aideapre66.decisionapre' => Set::enum( Set::classicExtract( $this->data, 'Aideapre66.decisionapre' ), $options['decisionapre'] ),
						'Aideapre66.montantaccorde' => Set::classicExtract( $this->data, 'Aideapre66.montantaccorde' ),
						'Aideapre66.motifrejetequipe' => Set::classicExtract( $this->data, 'Aideapre66.motifrejetequipe' ),
						'Aideapre66.datemontantaccorde' => Set::classicExtract( $this->data, 'Aideapre66.datemontantaccorde' )
					);
					echo $default->view(
						Xset::bump( $tmp ),
						array(
							'Aideapre66.decisionapre',
							'Aideapre66.montantaccorde' => array( 'type' => 'money' ),
							'Aideapre66.motifrejetequipe' => array( 'type' => 'text' ),
							'Aideapre66.datemontantaccorde' => array( 'type' => 'date' )
						),
						array(
							'class' => 'inform'
						)
					);
				}
				else{
					echo $xhtml->tag(
						'p',
						'Aucune décision n\'a encore été prise pour cette demande d\'APRE',
						array( 'class' => 'notice' )
					);
				}



// 				echo $default->subform(
// 					array(
// 						'Aideapre66.decisionapre' => array( 'legend' => false, 'type' => 'radio', 'options' => $options['decisionapre'], 'separator' => '<br />' ),
// 						'Aideapre66.montantaccorde' => array( 'type' => 'text' ),
// 						'Aideapre66.motifrejetequipe' => array( 'type' => 'textarea' ),
// 						'Aideapre66.datemontantaccorde' => array( 'empty' => false )
// 					),
// 					array(
// 						'class' => 'fraisdepct'
// 					)
// 				);
			?>

	</fieldset>

<?php endif;?>

<fieldset class="loici">
    <p>
        Un formulaire de demande par type d'aide demandée. Il doit être établi par un référent, pour Pôle Emploi en son absence par un prescripteur habilité.
    </p>
</fieldset>
        <?php
        ///FIXME: Voir si on peut faire mieux
            $etat = Set::enum( Set::classicExtract( $this->data, "{$this->modelClass}.etatdossierapre" ), $options['etatdossierapre'] );
//             debug($etat);
            if( empty( $etat ) ) {
                echo 'Etat du dossier : <strong>'.$etat.'</strong>';
            }
            else{
                echo 'Etat du dossier : <strong>'.$etat.'</strong>';
            }
        ?>
    </div>

    <div class="submit">
        <?php echo $form->submit( 'Enregistrer', array( 'div' => false ) );?>
        <?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
    </div>
    <?php echo $form->end();?>
</div>

<script type="text/javascript">
    /**
    *   Javascript gérant les frais de déplacements
    * FIXME: vérifier les types
    */

    function frenchToJsFloatValue( id ) {
        return $F( id ).replace( ',', '.' );
    }

    function jsToFrenchFloatValue( jsValue ) {
        return jsValue.toString().replace( '.', ',' );
    }

    function calculTotalVoiture() {
        // Frais de déplacement pour un véhicule individuel
        var Nbkmvoiture = frenchToJsFloatValue( 'Fraisdeplacement66Nbkmvoiture' );
        var Nbtrajetvoiture = frenchToJsFloatValue( 'Fraisdeplacement66Nbtrajetvoiture' );

		var Nbtotalkm = jsToFrenchFloatValue( Nbkmvoiture * Nbtrajetvoiture );
        $( 'Fraisdeplacement66Nbtotalkm' ).setValue( Nbtotalkm );
		$( 'Fraisdeplacement66Nbtotalkm' ).up().down( 'span' ).update( Nbtotalkm );

		var TotalVehicule = jsToFrenchFloatValue( ( Nbkmvoiture * Nbtrajetvoiture * <?php echo str_replace( ',', '.', Configure::read( 'Fraisdeplacement66.forfaitvehicule' ) );?> ).toFixed( 2 ) );
        $( 'Fraisdeplacement66Totalvehicule' ).setValue( TotalVehicule );
		$( 'Fraisdeplacement66Totalvehicule' ).up().down( 'span' ).update( TotalVehicule );
    }
    // Frais de déplacement pour un véhicule individuel
    $( 'Fraisdeplacement66Nbtotalkm' ).observe( 'blur', function( event ) { calculTotalVoiture(); } );
    $( 'Fraisdeplacement66Nbtrajetvoiture' ).observe( 'blur', function( event ) { calculTotalVoiture(); } );


    function calculTotalTranspub() {
        // Frais de déplacement pour un transport public
        var Nbtrajettranspub = frenchToJsFloatValue( 'Fraisdeplacement66Nbtrajettranspub' );
        var Prixbillettranspub = frenchToJsFloatValue( 'Fraisdeplacement66Prixbillettranspub' );
		var TotalTransportpub = jsToFrenchFloatValue( Nbtrajettranspub * Prixbillettranspub );
        $( 'Fraisdeplacement66Totaltranspub' ).setValue( TotalTransportpub );
		$( 'Fraisdeplacement66Totaltranspub' ).up().down( 'span' ).update( TotalTransportpub );

    }
    // Frais de déplacement pour un transport public
    $( 'Fraisdeplacement66Nbtrajettranspub' ).observe( 'blur', function( event ) { calculTotalTranspub(); } );
    $( 'Fraisdeplacement66Prixbillettranspub' ).observe( 'blur', function( event ) { calculTotalTranspub(); } );

    function calcultotalHebergt() {
        // Frais de déplacement pour un hébergement
        var Nbnuithebergt = frenchToJsFloatValue( 'Fraisdeplacement66Nbnuithebergt' );
		var Totalhebergt = jsToFrenchFloatValue( Nbnuithebergt * <?php echo str_replace( ',', '.', Configure::read( 'Fraisdeplacement66.forfaithebergt' ) ); ?> );
        $( 'Fraisdeplacement66Totalhebergt' ).setValue( Totalhebergt );
		$( 'Fraisdeplacement66Totalhebergt' ).up().down( 'span' ).update( Totalhebergt );
    }
    // Frais de déplacement pour un hébergement
    $( 'Fraisdeplacement66Nbnuithebergt' ).observe( 'blur', function( event ) { calcultotalHebergt(); } );
		


    function calculTotalRepas() {
        // Frais de déplacement pour un repas
        var Nbrepas = frenchToJsFloatValue( 'Fraisdeplacement66Nbrepas' );
		var Totalrepas = jsToFrenchFloatValue( Nbrepas * <?php echo str_replace( ',', '.', Configure::read( 'Fraisdeplacement66.forfaitrepas' ) );?> );
        $( 'Fraisdeplacement66Totalrepas' ).setValue( Totalrepas );
		$( 'Fraisdeplacement66Totalrepas' ).up().down( 'span' ).update( Totalrepas );
    }
    // Frais de déplacement pour un repas
    $( 'Fraisdeplacement66Nbrepas' ).observe( 'blur', function( event ) { calculTotalRepas(); } );

</script>

<script type="text/javascript">
	calculTotalVoiture();
	calculTotalTranspub();
	calcultotalHebergt();
	calculTotalRepas();

    Event.observe( $( 'ApreStructurereferenteId' ), 'change', function( event ) {
        $( 'ReferentRef' ).update( '' );
    } );
</script>
<div class="clearer"><hr /></div>
