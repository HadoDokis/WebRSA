<?php
	echo $this->FormValidator->generateJavascript();
	echo $this->Default3->titleForLayout();

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ), array( 'inline' => false ) );
	}
	
	echo '<p class="remarque center"><strong>' . __d('cuis66', 'intitule_haut_cui') . '</strong><br>' . __d('cuis66', 'intitule_haut_text') . '</p>';
	
	echo $this->Default3->DefaultForm->create( null, array( 'novalidate' => 'novalidate', 'class' => 'Cui66AddEdit', 'id' => 'CuiAddEditForm' ) );

/***********************************************************************************
 * Choix du formulaire
/***********************************************************************************/
	
	echo '<fieldset><legend id="Cui66Choixformulaire">' . __d('cuis66', 'Cui66.choixformulaire') . '</legend>'
		. $this->Default3->subform(
			array(
				'Cui.id' => array( 'type' => 'hidden' ),
				'Cui.personne_id' => array( 'type' => 'hidden' ),
				'Cui.partenairecui_id' => array( 'type' => 'hidden' ),
				'Cui.entreeromev3_id' => array( 'type' => 'hidden' ),
				'Cui66.id' => array( 'type' => 'hidden' ),
				'Cui66.cui_id' => array( 'type' => 'hidden' ),
				'Cui66.notifie' => array( 'type' => 'hidden' ),
				'Adresse.id' => array( 'type' => 'hidden' ),
				'Partenairecui.id' => array( 'type' => 'hidden' ),
				'Partenairecui.adressecui_id' => array( 'type' => 'hidden' ),
				'Partenairecui66.id' => array( 'type' => 'hidden' ),
				'Partenairecui66.partenairecui_id' => array( 'type' => 'hidden' ),
				'Adresse.id' => array( 'type' => 'hidden' ),
				'Entreeromev3.id' => array( 'type' => 'hidden' ),
				'Cui66.encouple' => array( 'type' => 'hidden' ),
				'Cui66.avecenfant' => array( 'type' => 'hidden' ),
				'Cui66.etatdossiercui66' => array( 'type' => 'hidden' ),
				'Cui66.typeformulaire' => array( 'empty' => true )
			),
			array( 'options' => $options )
		) . '</fieldset>'
	;
	
/***********************************************************************************
 * Secteur
/***********************************************************************************/	
	
	echo '<fieldset id="CuiSecteur"><legend>' . __d('cuis66', 'Cui.secteur') . '</legend>'
		. $this->Default3->subform(
			array(
				'Cui.secteurmarchand' => array( 'empty' => true, 'type' => 'select' ),
				'Cui66.typecontrat' => array( 'empty' => true ),
				'Cui66.codecdiae',
				'Cui.numconventionindividuelle' => array( 'type' => 'text' ),
				'Cui.numconventionobjectif' => array( 'type' => 'text' )				
			),
			array( 'options' => $options )
		) . '</fieldset>'
	;

/***********************************************************************************
 * L'Employeur
/***********************************************************************************/
	
	echo '<fieldset id="PartenairecuiEmployeur"><legend>' . __d('cuis66', 'Partenairecui.employeur') . '</legend>'; 
	echo '<fieldset><legend>' . __d('cuis66', 'Partenaire.charger') . '</legend>'
		. $this->Default3->subform(
			array(
				'Cui.partenaire_id' => array( 'empty' => true, 'type' => 'select' ),		
				//'Partenaire.actioncandidat_id' => array( 'empty' => true, 'type' => 'select' )
			),
			array( 'options' => $options )
		) 
		. '<div class="submit"><input type="button" id="PartenaireCharger" value="Charger" /><input type="button" id="PartenaireUpdate" value="Mettre à jour" /></div></fieldset>'
		. $this->Default3->subform(
			array(
				'Partenairecui.raisonsociale',
				'Partenairecui.enseigne'
			),
			array( 'options' => $options )
		) 
		. '<div class="twopart"></div><div class="twopart"><p class="remarque">' 
			. __d( 'cuis66', 'Partenairecui.remarque')
			. '</p><input type="checkbox" id="PartenairecuiUtiliseradradministrative" /><label for="PartenairecuiUtiliseradradministrative">'
			. __d( 'cuis66', 'Partenairecui.utiliseradradministrative') . '</label></div>'
		. '<div class="twopart">'	
			. '<fieldset class="first" id="PartenairecuiAdresseemployeur">'
			.		'<legend>' . __d('cuis66', 'Partenairecui.adresseemployeur') . '</legend>'
			
		. $this->Default3->subform(
			array(
				'Adressecui.numvoie',
				'Adressecui.typevoie',
				'Adressecui.nomvoie',
				'Adressecui.complement',
				'Adressecui.codepostal',
				'Adressecui.commune',
				'Adressecui.numtel',
				'Adressecui.email',
				'Adressecui.numfax',
				'Adressecui.canton' => array( 'empty' => true ),
			),
			array( 'options' => $options )
		)
		.		'</fieldset>'
			
		. '</div><div class="twopart">'
			. '<fieldset class="last" id="PartenairecuiAdresseadministrative">'
			.		'<legend>' . __d('cuis66', 'Partenairecui.adresseadministrative') . '</legend>'
			
		. $this->Default3->subform(
			array(
				'Adressecui.numvoie2',
				'Adressecui.typevoie2',
				'Adressecui.nomvoie2',
				'Adressecui.complement2',
				'Adressecui.codepostal2',
				'Adressecui.commune2',
				'Adressecui.numtel2',
				'Adressecui.email2',
				'Adressecui.numfax2',
				'Adressecui.canton2' => array( 'empty' => true ),
			),
			array( 'options' => $options )
		)
			. '</fieldset></div>'
		.	'<fieldset id="CuiVoletdroit">'
		.		'<legend>' . __d('cuis66', 'Cui.voletdroit') . '</legend>'
		. $this->Default3->subform(
			array(
				'Partenairecui.siret',
				'Partenairecui.naf',
				'Partenairecui.statut' => array( 'type' => 'text' ),
				'Partenairecui.effectif' => array( 'type' => 'text' ),
				'Partenairecui.organismerecouvrement' => array( 'empty' => true ),
				'Partenairecui.assurancechomage' => array( 'type' => 'radio', 'class' => 'uncheckable', 'legend' => __d( 'cuis66', 'Partenairecui.assurancechomage' ) ),
			),
			array( 'options' => $options )
		) 
		. '</fieldset>'
		. '<fieldset id="Partenairecui66Informationssup">'
		.	 '<legend>' . __d('cuis66', 'Partenairecui66.informationssup') . '</legend>'
		. $this->Default3->subform(
			array(
				'Partenairecui66.codepartenaire',
				'Partenairecui66.objet',
				'Partenairecui66.nomtitulairerib',
				'Partenairecui66.codebanque',
				'Partenairecui66.codeguichet',
				'Partenairecui66.numerocompte',
				'Partenairecui66.etablissementbancaire',
				'Partenairecui66.clerib',
				'Partenairecui66.nblits',
				'Partenairecui66.nbcontratsaideshorscg',
				'Partenairecui66.nbcontratsaidescg' => array( 'view' => true, 'hidden' => true ),
			),
			array( 'options' => $options )
		)
		. '</fieldset>'
		. $this->Default3->subform(
			array(
				'Partenairecui.ajourversement' => array( 'type' => 'radio', 'class' => 'uncheckable add-parent-id', 'legend' => __d( 'cuis66', 'Partenairecui.ajourversement' ) ),
			),
			array( 'options' => $options )
		)
		. '</fieldset>'
	;
	
/***********************************************************************************
 * DOSSIER RECU/ELIGIBLE/COMPLET
/***********************************************************************************/
	
	echo '<fieldset id="Cui66Dossier"><legend>' . __d('cuis66', 'Cui66.dossier') . '</legend>'
		. $this->Default3->subform(
			array(
				'Cui66.dossiereligible' => array( 'empty' => true, 'type' => 'select' ),
				'Cui66.dateeligibilite' => array( 'dateFormat' => 'DMY', 'minYear' => '2009', 'maxYear' => date('Y')+4 ),
				'Cui66.dossierrecu' => array( 'empty' => true, 'type' => 'select' ),
				'Cui66.datereception' => array( 'dateFormat' => 'DMY', 'minYear' => '2009', 'maxYear' => date('Y')+4 ),
				'Cui66.dossiercomplet' => array( 'empty' => true, 'type' => 'select' ),
				'Cui66.datecomplet' => array( 'dateFormat' => 'DMY', 'minYear' => '2009', 'maxYear' => date('Y')+4 ),
				'Cui66.notedossier'
			),
			array( 'options' => $options )
		) . '</fieldset>'
	;

	/**
	 * Condition d'affichage : le dossier doit être reçu pour avoir la suite
	 */
	echo '<div id="CuiHiddenForm">';

/***********************************************************************************
 * LE SALARIÉ
/***********************************************************************************/
	// On prépare les informations
	$dtnai = new DateTime( $personne['Personne']['dtnai'] );
	$dtdemrsa = new DateTime( $personne['Dossier']['dtdemrsa'] );
	$personne['Personne']['dtnai'] = date_format($dtnai, 'd/m/Y');
	$personne['Dossier']['dtdemrsa'] = date_format($dtdemrsa, 'd/m/Y');
	$personne['Adresse']['complete'] = $personne['Adresse']['numvoie'] . ' ' . $personne['Adresse']['libtypevoie'] . ' ' . $personne['Adresse']['nomvoie'] . '<br />';
	$personne['Adresse']['complete'] .= $personne['Adresse']['complideadr'] !== null ? $personne['Adresse']['complideadr'] . '<br>' : '';
	$personne['Adresse']['complete'] .= $personne['Adresse']['compladr'] !== null ? $personne['Adresse']['compladr'] . '<br />' : '';
	$personne['Adresse']['complete'] .= $personne['Adresse']['lieudist'] !== null ? $personne['Adresse']['lieudist'] . '<br />' : '';
	$personne['Adresse']['complete'] .= $personne['Adresse']['codepos'] . ' ' . $personne['Adresse']['nomcom'];
	$diffMonth = floor((time() - strtotime(date_format($dtdemrsa, 'Y-m-d'))) / 60 / 60 / 24 / (365 / 12));
	$diffMonth < 6 && $diffStr = 'moins de 6 mois';
	$diffMonth >= 6 && $diffMonth < 11 && $diffStr = 'de 6 à 11 mois';
	$diffMonth >= 11 && $diffMonth < 23 && $diffStr = 'de 12 à 23 mois';
	$diffMonth >= 24 && $diffStr = '24 et plus';
	
	$darkLabelGauche = array(
		array( __d( 'cuis66', 'Personne.nom' ), $personne['Personne']['nom'] ),
		array( __d( 'cuis66', 'Personne.dtnai' ), $personne['Personne']['dtnai'] ),
		array( __d( 'cuis66', 'Personne.nomcomnai' ), $personne['Personne']['nomcomnai'] ),
		array( __d( 'cuis66', 'Adresse.complete' ), $personne['Adresse']['complete'] ),
	);
	$darkLabelDroit = array(		
		array( __d( 'cuis66', 'Personne.prenom' ), $personne['Personne']['prenom'] ),
		array( __d( 'cuis66', 'Personne.nir' ), $personne['Personne']['nir'] ),
		array( __d( 'cuis66', 'Departement.name' ), $personne['Departement']['name'] ),
		array( __d( 'cuis66', 'Adresse.canton' ), $personne['Adresse']['canton'] ),	
		array( __d( 'cuis66', 'Personne.nati' ), $personne['Personne']['nati'] ),
		array( __d( 'cuis66', 'Referentparcours.nom_complet' ), $personne['Referentparcours']['nom_complet'] ),	
	);
	
	// On affiche les informations
	echo '<fieldset id="PersonneInfo"><legend>' . __d('cuis66', 'Personne.info') . '</legend><div class="twopart">';
	
	foreach($darkLabelGauche as $value){
		echo '<div class="input value"><label class="little dark label">' . $value[0] . '</label><p class="dark label value">' . $value[1] . '</p></div>';
	}
	
	echo '</div><div class="twopart">';
	
	foreach($darkLabelDroit as $value){
		echo '<div class="input value"><label class="little dark label">' . $value[0] . '</label><p class="dark label value">' . $value[1] . '</p></div>';
	}
	
	echo '</div>' . $this->Default3->subform(
			array(
				'Cui66.zonecouverte' => array( 'empty' => true, 'type' => 'select' ),
				'Cui66.datefinsejour' => array( 'empty' => true, 'dateFormat' => 'DMY', 'minYear' => '2009', 'maxYear' => date('Y')+4 ),
			),
			array( 'options' => $options )
		)
		. $this->Xform->fieldValue('Dossier.matricule', $personne['Dossier']['matricule'])
		. $this->Xform->fieldValue('Dossier.fonorg', $personne['Dossier']['fonorg'])
		. '<div class="input radio"><fieldset id="Cui66Coupleenfant"><legend>' . __d('cuis66', 'Cui66.coupleenfant') . '</legend>'
	;

	echo '<input type="radio" name="data[Couple][enfants]" id="CoupleEnfants_1_0" value="1_0" /><label for="CoupleEnfants_1_0">'
		. __d('cuis66', 'Couple.enfants_1_0') . '</label>'
		. '<input type="radio" name="data[Couple][enfants]" id="CoupleEnfants_1_1" value="1_1" /><label for="CoupleEnfants_1_1">'
		. __d('cuis66', 'Couple.enfants_1_1') . '</label>'
		. '<input type="radio" name="data[Couple][enfants]" id="CoupleEnfants_0_0" value="0_0" /><label for="CoupleEnfants_0_0">'
		. __d('cuis66', 'Couple.enfants_0_0') . '</label>'
		. '<input type="radio" name="data[Couple][enfants]" id="CoupleEnfants_0_1" value="0_1" /><label for="CoupleEnfants_0_1">'
		. __d('cuis66', 'Couple.enfants_0_1') . '</label>'
		. '</fieldset></div>'
		. '<p class="notice">' . __d('cuis66', 'Cui66.defaultprestation') . '</p>'
		. $this->Xform->fieldValue('Cuis66.nbenfants', $personne['Foyer']['nb_enfants'])
		. '<div class="input text"><span class="label">' . __d('cuis66', 'Dossier.dtdemrsa') . '</span><span class="input">' . $personne['Dossier']['dtdemrsa'] . ' (' . $dtdemrsa->diff(new DateTime())->format('%y an(s) %m mois %d jours') . ')</span></div>'
		. '<div class="input text"><span class="label">' . __d('cuis66', 'Dossier.date_entree_dispositif') . '</span><span class="input">' . $diffStr . '</span></div></fieldset>'
	;

/***********************************************************************************
 * SITUATION DU SALARIE AVANT LA SIGNATURE DE LA CONVENTION
/***********************************************************************************/
	
	echo '<fieldset id="CuiSituationsalarie"><legend>' . __d('cuis66', 'Cui.situationsalarie') . '</legend>'
		. $this->Default3->subform(
			array(
				'Cui.niveauformation' => array( 'empty' => true, 'type' => 'select' ),
				'Cui.inscritpoleemploi' => array( 'type' => 'radio', 'class' => 'uncheckable', 'legend' => __d( 'cuis66', 'Cui.inscritpoleemploi' ) ),
				'Cui.sansemploi' => array( 'type' => 'radio', 'class' => 'uncheckable', 'legend' => __d( 'cuis66', 'Cui.sansemploi' ) ),
				'Cui.beneficiairede' => array( 'type' => 'radio', 'class' => 'uncheckable', 'legend' => __d( 'cuis66', 'Cui.beneficiairede' ) ),
				'Cui.majorationrsa' => array( 'type' => 'radio', 'class' => 'uncheckable add-parent-id', 'legend' => __d( 'cuis66', 'Cui.majorationrsa' ) ),
				'Cui.rsadepuis' => array( 'type' => 'radio', 'class' => 'uncheckable add-parent-id', 'legend' => __d( 'cuis66', 'Cui.rsadepuis' ) ),
				'Cui.travailleurhandicape' => array( 'type' => 'radio', 'class' => 'uncheckable', 'legend' => __d( 'cuis66', 'Cui.travailleurhandicape' ) ),
			),
			array( 'options' => $options )
		) . '</fieldset>'
	;
	
/***********************************************************************************	
 * CONTRAT DE TRAVAIL
/***********************************************************************************/
	
	echo '<fieldset id="CuiContrattravail"><legend>' . __d('cuis66', 'Cui.contrattravail') . '</legend>'
		. $this->Default3->subform(
			array(
				'Cui.typecontrat' => array( 'type' => 'radio', 'class' => 'uncheckable', 'legend' => __d( 'cuis66', 'Cui.typecontrat' ) ),
				'Cui.dateembauche' => array( 'dateFormat' => 'DMY', 'minYear' => '2009', 'maxYear' => date('Y')+4 ),
				'Cui.findecontrat' => array( 'dateFormat' => 'DMY', 'minYear' => '2009', 'maxYear' => date('Y')+4 ),
			),
			array( 'options' => $options )
		) 
		. $this->Romev3->fieldset( 'Entreeromev3', array( 'options' => $options ) )
		. $this->Default3->subform(
			array(
				'Cui.salairebrut' => array( 'type' => 'text', 'class' => 'euros' ),
				'Cui.dureehebdo' => array( 'class' => 'heures_minutes' ),
				'Cui.modulation' => array( 'type' => 'radio', 'class' => 'uncheckable', 'legend' => __d( 'cuis66', 'Cui.modulation' ) ),
				'Cui.dureecollectivehebdo' => array( 'class' => 'heures_minutes' ),
			),
			array( 'options' => $options )
		) . '</fieldset>'
	;
	
/***********************************************************************************	
 * LES ACTIONS D'ACCOMPAGNEMENT ET DE FORMATION PRÉVUES
/***********************************************************************************/
	
	echo '<fieldset id="CuiAccompagnement"><legend>' . __d('cuis66', 'Cui.accompagnement') . '</legend>'
		. $this->Default3->subform(
			array(
				'Cui.nomtuteur',
				'Cui.fonctiontuteur',
				'Cui.organismedesuivi',
				'Cui.nomreferent',
				'Cui.actionaccompagnement' => array( 'type' => 'radio', 'class' => 'uncheckable', 'legend' => __d( 'cuis66', 'Cui.actionaccompagnement' ) ),
			),
			array( 'options' => $options )
		) 
		. '<br /><h4>' . __d('cuis66', 'Cui.small_title_accompagnement') . '</h4>'
		. $this->Default3->subform(
			array(
				'Cui.remobilisationemploi' => array( 'empty' => true ),
				'Cui.aidepriseposte' => array( 'empty' => true ),
				'Cui.elaborationprojet' => array( 'empty' => true ),
				'Cui.evaluationcompetences' => array( 'empty' => true ),
				'Cui.aiderechercheemploi' => array( 'empty' => true ),
				'Cui.autre' => array( 'empty' => true ),
				'Cui.autrecommentaire'
			),
			array( 'options' => $options )
		) 
		. '<br /><h4>' . __d('cuis66', 'Cui.small_title_formation') . '</h4>'
		. $this->Default3->subform(
			array(
				'Cui.adaptationauposte' => array( 'empty' => true ),
				'Cui.remiseaniveau' => array( 'empty' => true ),
				'Cui.prequalification' => array( 'empty' => true ),
				'Cui.acquisitioncompetences' => array( 'empty' => true ),
				'Cui.formationqualifiante' => array( 'empty' => true ),
				'Cui.formation' => array( 'type' => 'radio', 'class' => 'uncheckable', 'legend' => __d( 'cuis66', 'Cui.formation' ) ),
				'Cui.periodeprofessionnalisation' => array( 'type' => 'radio', 'class' => 'uncheckable', 'legend' => __d( 'cuis66', 'Cui.periodeprofessionnalisation' ) ),
				'Cui.niveauqualif' => array( 'empty' => true, 'type' => 'select' ),
				'Cui.validationacquis' => array( 'type' => 'radio', 'class' => 'uncheckable', 'legend' => __d( 'cuis66', 'Cui.validationacquis' ) ),
			),
			array( 'options' => $options )
		) . '</fieldset>'
	;
	
/***********************************************************************************
 * SI CAE - PERIODE IMMERSION ?
/***********************************************************************************/
	
	echo $this->Default3->subform(array(
		'Cui.periodeimmersion' => array( 'type' => 'radio', 'class' => 'uncheckable add-parent-id', 'legend' => __d( 'cuis66', 'Cui.periodeimmersion' ) ),
		),
		array( 'options' => $options )
	);
		
/***********************************************************************************
 * LA PRISE EN CHARGE (CADRE RÉSERVÉ AU PRESCRIPTEUR)
/***********************************************************************************/
	
	echo '<fieldset id="CuiPrise_en_charge"><legend>' . __d('cuis66', 'Cui.prise_en_charge') . '</legend>'
		. $this->Default3->subform(
			array(
				'Cui.effetpriseencharge' => array( 'empty' => true, 'dateFormat' => 'DMY', 'minYear' => '2009', 'maxYear' => date('Y')+4 ),
				'Cui.finpriseencharge' => array( 'empty' => true, 'dateFormat' => 'DMY', 'minYear' => '2009', 'maxYear' => date('Y')+4 ),
				'Cui.decisionpriseencharge' => array( 'empty' => true, 'dateFormat' => 'DMY', 'minYear' => '2009', 'maxYear' => date('Y')+4 ),
				'Cui.dureehebdoretenu' => array( 'class' => 'heures_minutes'),
				'Cui.operationspeciale' => array( 'type' => 'text' ),
				'Cui.tauxfixeregion' => array( 'type' => 'text', 'class' => 'percent'),
				'Cui.priseenchargeeffectif' => array( 'type' => 'text', 'class' => 'percent'),
				'Cui.exclusifcg' => array( 'type' => 'radio', 'class' => 'uncheckable', 'legend' => __d( 'cuis66', 'Cui.exclusifcg' ) ),
				'Cui.tauxcg' => array( 'type' => 'text', 'class' => 'percent'),
				'Cui.organismepayeur' => array( 'type' => 'radio', 'class' => 'uncheckable', 'legend' => __d( 'cuis66', 'Cui.organismepayeur' ) ),
				'Cui.intituleautreorganisme',
				'Cui.adressautreorganisme',
			),
			array( 'options' => $options )
		) . '</fieldset>'
	;

	echo '</div>';

/***********************************************************************************
 * DATE
/***********************************************************************************/
	
	echo '<fieldset id="CuiDate"><legend>' . __d('cuis66', 'Cui.date') . '</legend>'
		. $this->Default3->subform(
			array(
				'Cui.faitle' => array( 'dateFormat' => 'DMY', 'minYear' => '2009', 'maxYear' => date('Y')+4 ),
				'Cui66.demandeenregistree' => array( 'view' => true, 'hidden' => true, 'type' => 'date' )
			),
			array( 'options' => $options )
		) 
		. '</fieldset>'
	;
	
/***********************************************************************************
 * FIN DU FORMULAIRE
/***********************************************************************************/
	
	echo $this->Default3->DefaultForm->buttons( array( 'Save', 'Cancel' ) );
	echo $this->Default3->DefaultForm->end();
	echo $this->Observer->disableFormOnSubmit( 'CuiAddEditForm' );
	?>
<script type="text/javascript">
	/*global $, $$, $F, $break, Ajax, console, Event, Math, fieldId*/
	
	/**
	 * Spécial couple/célibataire avec/sans enfants. 4 radio remplissent 2 hidden
	 * @param {HTML} input
	 * @returns {void}
	 */ 
	$$('input[name="data[Couple][enfants]"]').each(function( radio ){
		if ( $F('Cui66Encouple') === '1' ){
			if ( $F('Cui66Avecenfant') === '1' ){ $('CoupleEnfants_1_1').checked = true; }
			else{ $('CoupleEnfants_1_0').checked = true; }
		}
		else{
			if ( $F('Cui66Avecenfant') === '1' ){ $('CoupleEnfants_0_1').checked = true; }
			else{ $('CoupleEnfants_0_0').checked = true; }
		}
		radio.onclick = function(){
			$('Cui66Encouple').value = radio.value.substr(0,1); // Si 1_0 retourne 1
			$('Cui66Avecenfant').value = radio.value.substr(2,3); // Si 1_0 retourne 0
		};
	});
	
	/**
	 * Converti le contenu de "heures H minutes" en minutes dans le champ caché
	 * @param {HTML} input
	 * @returns {void}
	 */
	function setDureeHebdo( input ){
		var dureeEnHeure = isNaN( parseInt( $F('heures' +  input.id ) ) ) ? 0 : parseInt( $F('heures' +  input.id ) );
		var minutesRestante = isNaN( parseInt( $F('minutes' +  input.id ) ) ) ? 0 : parseInt( $F('minutes' +  input.id ) );
		$( input.id ).value = Math.floor( dureeEnHeure * 60 + minutesRestante );
		
		var minutes = isNaN( parseInt( $F( input.id ) ) ) ? 0 : parseInt( $F( input.id ) );
		dureeEnHeure = Math.floor( minutes / 60 );
		minutesRestante = Math.floor( minutes - (dureeEnHeure * 60) );
		$('heures' +  input.id ).value = dureeEnHeure === 0 ? '' : dureeEnHeure;
		$('minutes' +  input.id ).value = minutesRestante === 0 ? '' : minutesRestante;
		
		if ( $F( input.id ) === '0' ) {
			$( input.id ).value = '';
		}
	}
	
	/**
	 * Cache les inputs de class heures_minutes et ajoute 2 inputs séparé par un H
	 * Rempli les inputs avec la valeur initiale en minutes vers "heures H minutes"
	 * Ajoute des evenements onchange sur ces derniers, qui lancent setDureeHebdo()
	 */
	$$('input.heures_minutes').each(function( input ){
		input.insert({after: '<input type="text" id="heures' + input.id + '" class="miniInput" /> H <input type="text" id="minutes' + input.id + '" class="miniInput" />'});
		var minutes = isNaN(parseInt($F(input.id))) ? 0 : parseInt($F(input.id));
		var dureeEnHeure = Math.floor( minutes / 60 );
		var minutesRestante = Math.floor( minutes - (dureeEnHeure * 60) );
		$('heures' + input.id).value = dureeEnHeure === 0 ? '' : dureeEnHeure;
		$('minutes' + input.id).value = minutesRestante === 0 ? '' : minutesRestante;
		
		Event.observe( $('heures' + input.id ), 'change', function(){ setDureeHebdo(input); } );
		Event.observe( $('minutes' + input.id ), 'change', function(){ setDureeHebdo(input); } );
		input.type = 'hidden';
	});
		
	/**
	 * Rempli les champs en fonction de Partenaire.id
	 * @param {Object} json
	 * @returns {void}
	 */
	function remplirChamps( json ){
		var key, champ, correspondancesChamps = <?php echo $correspondancesChamps;?>;
		for (key in correspondancesChamps){
			if (correspondancesChamps.hasOwnProperty(key)){
				champ = $( fieldId(correspondancesChamps[key]) );
				if (champ !== null){
					champ.value = json[key];
					champ.simulate('change');
				}
			}
		}
	}
	
	/**
	 * Bouton Charger de Partenaire.id
	 * @returns {void}
	 */
	$('PartenaireCharger').onclick = function(){
		new Ajax.Request('<?php echo Router::url( array( 'controller' => 'partenaires', 'action' => 'ajax_coordonnees' ) ); ?>/'+$F('CuiPartenaireId'), {
			asynchronous:true, 
			evalScripts:true, 
			onComplete:function(request, json) {
				remplirChamps( json.Partenaire );
			}
		});
	};
	
	/**
	 * Coche "Utiliser une adresse administrative différente" si une valeur est présente dans adresse administrative
	 */
	var haveValue = false;
	$('PartenairecuiAdresseadministrative').select('input').each(function( input ){
		if ( $F(input) ){
			haveValue = true;
			throw $break;
		}
	});
	if ( haveValue ){
		$('PartenairecuiUtiliseradradministrative').checked = true;
	}
	
	function hiddenForm(){
		if ( $F('Cui66Dossierrecu') === '1' ){
			$('CuiHiddenForm').show();
		}
		else{
			$('CuiHiddenForm').hide();
		}
	}
	$('Cui66Dossierrecu').observe( 'change', hiddenForm);
	hiddenForm();
</script>
<?php
	// Ici on défini les champs à faire apparaitre que si certains autres portent une certaine valeur
	$secteurmarchand = array('Cui66.typecontrat', 'Cui.numconventionindividuelle', 'Cui.numconventionobjectif');
	$dateEligibiliteDossier = array('Cui66.dateeligibilite.day', 'Cui66.dateeligibilite.month', 'Cui66.dateeligibilite.year', 'Cui66.dossierrecu');
	$dateReceptionDossier = array('Cui66.datereception.day', 'Cui66.datereception.month', 'Cui66.datereception.year', 'Cui66.dossiercomplet');
	$dateDossierComplet = array('Cui66.datecomplet.day', 'Cui66.datecomplet.month', 'Cui66.datecomplet.year');

	echo $this->Observer->disableFieldsOnValue(
		'Cui.secteurmarchand',
		$secteurmarchand,
		array( '', null ),
		true,
		true
	);
	
	echo $this->Observer->disableFieldsetOnValue(
		'Cui.secteurmarchand',
		'PartenairecuiAjourversement0Parent',
		'1',
		false,
		true
	);
	
	echo $this->Observer->disableFieldsOnValue(
		'Cui66.typecontrat',
		'Cui66.codecdiae',
		'ACI',
		false,
		true
	);
	
	echo $this->Observer->disableFieldsetOnCheckbox(
		'Partenairecui.utiliseradradministrative',
		'PartenairecuiAdresseadministrative',
		false,
		false
	);
	
	echo $this->Observer->disableFieldsOnValue(
		'Cui66.dossiereligible',
		$dateEligibiliteDossier,
		'1',
		false,
		true
	);
	
	echo $this->Observer->disableFieldsOnValue(
		'Cui66.dossierrecu',
		$dateReceptionDossier,
		'1',
		false,
		true
	);
	
	echo $this->Observer->disableFieldsOnValue(
		'Cui66.dossiercomplet',
		$dateDossierComplet,
		'1',
		false,
		true
	);
	
	echo $this->Observer->disableFieldsetOnRadioValue(
		'CuiAddEditForm',
		'Cui.beneficiairede',
		'CuiMajorationrsa0Parent',
		'RSA',
		true,
		true
	);
	
	echo $this->Observer->disableFieldsetOnRadioValue(
		'CuiAddEditForm',
		'Cui.majorationrsa',
		'CuiRsadepuis05Parent',
		'1',
		true,
		true
	);
	
	echo $this->Observer->disableFieldsOnRadioValue(
		'CuiAddEditForm',
		'Cui.typecontrat',
		array( 'CuiDateembaucheDay', 'CuiDateembaucheMonth', 'CuiDateembaucheYear' ),
		array( '', null ),
		false,
		true
	);
	
	echo $this->Observer->disableFieldsOnRadioValue(
		'CuiAddEditForm',
		'Cui.typecontrat',
		array( 'CuiFindecontratDay', 'CuiFindecontratMonth', 'CuiFindecontratYear' ),
		'CDD',
		true,
		true
	);
	
	echo $this->Observer->disableFieldsOnValue(
		'Cui.autre',
		'Cui.autrecommentaire',
		array( '', null ),
		true,
		true
	);
	
	echo $this->Observer->disableFieldsOnRadioValue(
		'CuiAddEditForm',
		'Cui.periodeprofessionnalisation',
		'CuiNiveauqualif',
		'1',
		true,
		true
	);
	
	echo $this->Observer->disableFieldsetOnValue(
		'Cui66.typecontrat',
		'CuiPeriodeimmersion0Parent',
		'ACI',
		false,
		true
	);
	
	echo $this->Observer->disableFieldsOnRadioValue(
		'CuiAddEditForm',
		'Cui.exclusifcg',
		'CuiTauxcg',
		'1',
		true,
		true
	);
	
	echo $this->Observer->disableFieldsOnRadioValue(
		'CuiAddEditForm',
		'Cui.organismepayeur',
		array( 'CuiIntituleautreorganisme', 'CuiAdressautreorganisme' ),
		'AUTRE',
		true,
		true
	);
?>