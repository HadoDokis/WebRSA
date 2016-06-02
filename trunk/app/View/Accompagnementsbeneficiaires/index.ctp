<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->script( 'prototype.livepipe.js' );
		echo $this->Html->script( 'prototype.tabs.js' );
		echo $this->Html->script( 'prototype.fabtabulous.js' );
		echo $this->Html->script( 'prototype.tablekit.js' );
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
	}

	$personne_id = Hash::get( $dossierMenu, 'personne_id' );
	$personneDossier = null;
	foreach( (array)Hash::get( $dossierMenu, 'Foyer.Personne' ) as $personne ) {
		if( (int)$personne_id === (int)$personne['id'] ) {
			$personneDossier = $personne;
		}
	}
	$this->pageTitle = DefaultUtility::evaluateString( (array)$personneDossier, 'Synthèse de l\'accompagnement de #qual# #nom# #prenom#' );
	echo $this->Html->tag( 'h1', $this->pageTitle );
?>
<div id="tabbedWrapper" class="tabs">
	<div id="accompagnement">
		<?php
			// Premier onglet, accompagnement
			echo $this->Html->tag( 'h2', 'Accompagnement', array( 'class' => 'title' ) );
			echo $this->Html->tag( 'h3', 'Droits' );
			echo $this->Default3->view(
				$details,
				array(
					'Dossier.dtdemrmi' => array(
						'label' => __m( 'Dossier.dtdemrmi' ),
					),
					'Dossier.dtdemrsa' => array(
						'label' => __m( 'Dossier.dtdemrsa' ),
					),
					'Detailcalculdroitrsa.natpf_activite' => array(
						'label' => __m( 'Detailcalculdroitrsa.natpf_activite' ),
					),
					'Detailcalculdroitrsa.natpf_majore' => array(
						'label' => __m( 'Detailcalculdroitrsa.natpf_majore' ),
					),
					'Detailcalculdroitrsa.natpf_socle' => array(
						'label' => __m( 'Detailcalculdroitrsa.natpf_socle' ),
					),
					'Situationdossierrsa.etatdosrsa' => array(
						'label' => __m( 'Situationdossierrsa.etatdosrsa' ),
					),
					'Cer93.cmu' => array(
						'label' => __m( 'Cer93.cmu' ),
					),
					'Cer93.cmuc' => array(
						'label' => __m( 'Cer93.cmuc' ),
					),
					'Historiqueetatpe.identifiantpe' => array(
						'label' => __m( 'Historiqueetatpe.identifiantpe' ),
					),
					'Calculdroitrsa.toppersdrodevorsa' => array(
						'label' => __m( 'Calculdroitrsa.toppersdrodevorsa' ),
					),
					'Typeorient.lib_type_orient' => array(
						'label' => __m( 'Typeorient.lib_type_orient' ),
					),
					'Structurereferente.lib_struc' => array(
						'label' => __m( 'Structurereferente.lib_struc' ),
					)
				),
				array(
					'options' => $options,
					'id' => 'TableAccompagnementsbeneficiairesIndexDroits',
					'th' => true,
					'class' => 'details'
				)
			);

			echo $this->Html->tag( 'h3', 'Compétences' );
			echo $this->Default3->view(
				$details,
				array(
					'Cer93.nivetu' => array(
						'label' => __m( 'Cer93.nivetu' ),
						'condition' => '"#Cer93.nivetu#" != ""'
					),
					'DspRev.nivetu' => array(
						'label' => __m( 'DspRev.nivetu' ),
						'condition' => '"#DspRev.nivetu#" != ""'
					),
					'Questionnaired1pdv93.nivetu' => array(
						'label' => __m( 'Questionnaired1pdv93.nivetu' ),
						'condition' => '"#Questionnaired1pdv93.nivetu#" != ""'
					),
					'Dsp.nivetu' => array(
						'label' => __m( 'Dsp.nivetu' ),
						'condition' => '("#Dsp.nivetu#" != "") || ( "#Cer93.nivetu#" == "" && "#DspRev.nivetu#" == "" && "#Questionnaired1pdv93.nivetu#" == "" )'
					),
					'Diplomecer93.name' => array(
						'label' => __m( 'Diplomecer93.name' ),
					),
					'Appellationromev3.name' => array(
						'label' => __m( 'Appellationromev3.name' ),
					),
					'Accompagnement.topmoyloco' => array(
						'label' => __m( 'Accompagnement.topmoyloco' ),
					),
					'Accompagnement.toppermicondu' => array(
						'label' => __m( 'Accompagnement.toppermicondu' ),
					),
				),
				array(
					'options' => $options,
					'id' => 'TableAccompagnementsbeneficiairesIndexCompetences',
					'th' => true,
					'class' => 'details'
				)
			);
			echo $this->Html->tag( 'h3', 'Suivi' );
			echo $this->Default3->view(
				$details,
				array(
					'Referent.nom_complet' => array(
						'label' => __m( 'Referent.nom_complet' ),
					),
				),
				array(
					'options' => $options,
					'id' => 'TableAccompagnementsbeneficiairesIndexSuivi',
					'th' => true,
					'class' => 'details'
				)
			);

			// Tableau d'actions
			echo $this->Html->tag( 'h3', 'Actions' );

			echo $this->Default3->DefaultForm->create();
			echo $this->Default3->DefaultForm->input(
				'Search.Action.name',
				array(
					'label' => __m( 'Search.Action.name' ),
					'options' => $options['Action']['name'],
					'empty' => true
				)
			);

			$this->request->data = array(
				'Search' => array(
					'Action' => array(
						'date_from' => date_sql_to_cakephp( '2009-01-01' )
					)
				)
			);

			echo $this->SearchForm->dateRange(
				'Search.Action.date',
				array(
					'prefix' => 'Search',
					'legend' => __m( 'Search.Action.date' ),
					'minYear_from' => 2009,
					'minYear_to' => 2009,
					'maxYear_from' => date( 'Y' ) + 1,
					'maxYear_to' => date( 'Y' ) + 1
				)
			);
			echo $this->Default3->DefaultForm->end();

			echo $this->Default3->index(
				$actions,
				array(
					'Action.lib_struc' => array(
						'label' => __m( 'Action.lib_struc' )
					),
					'Action.nom_complet' => array(
						'label' => __m( 'Action.nom_complet' )
					),
					'Action.date' => array(
						'label' => __m( 'Action.date' ),
						'type' => 'date',
						'class' => 'sortable date filter_date'
					),
					'Action.name' => array(
						'label' => __m( 'Action.name' ),
						'class' => '#Action.name#'
					),
					'Action.statut' => array(
						'label' => __m( 'Action.statut' )
					),
					//  ----------------------------------------------------------------
					'Action.informations_rendezvous' => array(
						'label' => __m( 'Action.informations' ),
						'sort' => false,
						'condition' => 'in_array("#Action.name#", array( "Rendezvousindividuel", "Rendezvouscollectif" ))',
						'value' => '#Action.informations#',
						'class' => 'informations'
					),
					'Action.informations_contratsinsertion' => array(
						'label' => __m( 'Action.informations' ),
						'sort' => false,
						'condition' => '"#Action.name#" === "Contratinsertion"',
						'value' => 'Contrat de #Action.duree# mois du #Action.dd_ci,date_short# au #Action.df_ci,date_short#',
						'class' => 'informations'
					),
					'Action.informations_fichesprescriptions93' => array(
						'label' => __m( 'Action.informations' ),
						'sort' => false,
						'condition' => '"#Action.name#" === "Ficheprescription93"',
						'value' => '#Action.thematiquefp#, #Action.categoriefp#',
						'class' => 'informations'
					),
					'Action.informations_questionnairesd1pdvs93' => array(
						'label' => __m( 'Action.informations' ),
						'sort' => false,
						'condition' => '"#Action.name#" === "Questionnaired1pdv93"',
						'value' => '',
						'class' => 'informations'
					),
					'Action.informations_questionnairesd2pdvs93' => array(
						'label' => __m( 'Action.informations' ),
						'sort' => false,
						'condition' => '"#Action.name#" === "Questionnaired2pdv93"',
						'value' => '#Action.informations#',
						'class' => 'informations'
					),
					'Action.informations_dsps_revs' => array(
						'label' => __m( 'Action.informations' ),
						'sort' => false,
						'condition' => '"#Action.name#" === "DspRev"',
						'value' => '',
						'class' => 'informations'
					),
					'Action.informations_entretiens' => array(
						'label' => __m( 'Action.informations' ),
						'sort' => false,
						'condition' => '"#Action.name#" === "Entretien"',
						'value' => '#Action.informations#',
						'class' => 'informations'
					),
					//  ----------------------------------------------------------------
					// FIXME: les liens
					'/#Action.view#' => array(
						'class' => 'view',
						'msgid' => 'Voir'
					),
					'/#Action.edit#' => array(
						'class' => 'edit',
						'msgid' => 'Modifier'
					)
				),
				array(
					'class' => 'search sortable',
					'paginate' => false,
					'options' => $options,
					'id' => 'TableAccompagnementsbeneficiairesIndexActions',
					'innerTable' => array(
						'Action.commentairerdv' => array(
							'label' => __m( 'Action.commentairerdv' ),
							'condition' => 'in_array( "#Action.name#", array( "Rendezvouscollectif", "Rendezvousindividuel" ) )',
						),
						'Action.prestatairefphorspdi' => array(
							'label' => __m( 'Action.prestatairefp' ),
							'condition' => '"#Action.name#" === "Ficheprescription93" && "#Action.prestatairefphorspdi#" != ""',
						),
						'Action.prestatairefppdi' => array(
							'label' => __m( 'Action.prestatairefp' ),
							'condition' => '"#Action.name#" === "Ficheprescription93" && "#Action.prestatairefphorspdi#" == ""',
						),
						'Action.sujets_virgules' => array(
							'label' => __m( 'Action.sujets_virgules' ),
							'condition' => '"#Action.name#" === "Contratinsertion"',
						),
						'Action.prevu' => array(
							'label' => __m( 'Action.prevu' ),
							'condition' => '"#Action.name#" === "Contratinsertion"',
						),
					)
				)
			);
		?>
	</div>
	<div id="fichiersmodules">
		<?php
			// TODO: les mettre dans leurs propres vues (appelées en ajax à la demande)
			echo $this->Html->tag( 'h2', 'Fichiers liés', array( 'class' => 'title' ) );
			echo $this->Default3->index(
				$fichiersmodules,
				array(
					'Fichiermodule.modele' => array(
						'label' => 'Module',
						//'sort' => true // TODO
					),
					'Fichiermodule.name' => array(
						'label' => 'Nom'
					),
					'Fichiermodule.mime' => array(
						'label' => 'Type'
					),
					'Fichiermodule.created' => array(
						'label' => 'Créé le'
					),
					'/#Fichiermodule.controller#/download/#Fichiermodule.id#' => array(
						'msgid' => 'Télécharger',
						'title' => 'Télécharger le fichier lié'
					),
					'/#Fichiermodule.controller#/view/#Fichiermodule.fk_value#' => array(
						'msgid' => 'Voir',
						'title' => 'Voir l\'enregistrement auquel le fichier est lié'
					),
					'/#Fichiermodule.controller#/filelink/#Fichiermodule.fk_value#' => array(
						'msgid' => 'Liste',
						'title' => 'Liste des fichiers liés'
					)
				),
				array(
					'class' => 'search',
					'paginate' => false,
					'id' => 'TableAccompagnementsbeneficiairesIndexFichiersmodules'
				)
			);
		?>
	</div>
	<div id="impressions">
		<?php
			echo $this->Html->tag( 'h2', 'Impressions', array( 'class' => 'title' ) );
			echo $this->Default3->index(
				$pdfs,
				array(
					'Pdf.modele' => array(
						'label' => 'Module',
						//'sort' => true // TODO
					),
					'Pdf.created' => array(
						'label' => 'Créé le'
					),
					'/#Pdf.controller#/impression/#Pdf.fk_value#' => array(
						'msgid' => 'Imprimer'
					),
					'/#Pdf.controller#/view/#Pdf.fk_value#' => array(
						'msgid' => 'Voir',
						'title' => 'Voir l\'enregistrement auquel le fichier est lié'
					),
					"/#Pdf.controller#/index/{$personne_id}" => array(
						'msgid' => 'index',
						'msgid' => 'Liste'
					)
				),
				array(
					'class' => 'search',
					'paginate' => false,
					'id' => 'TableAccompagnementsbeneficiairesIndexImpressions'
				)
			);
		?>
</div>
<script type="text/javascript">
	//<![CDATA[
	makeTabbed( 'tabbedWrapper', 2 );

	function cakeDateToObject( prefix ) {
		var date = new Date();

		try {
			date.setDate( parseInt( $F( prefix + 'Day' ), 10 ) );
			date.setMonth( parseInt( $F( prefix + 'Month' ), 10 ) - 1 );
			date.setYear( parseInt( $F( prefix + 'Year' ), 10 ) );
		} catch( e ) {
			console.log( e );
		}

		return date;
	}

	function conditionAction( target, row ) {
		try {
			target = $(target).value;
			return ( target === '' ) || $(row).select( 'td.' + target ).length > 0;
		} catch( e ) {
			return false;
		}
	}

	function conditionDateRange( checkbox, from, to, row ) {
		var checked = $(checkbox).checked, text, value;

		from = cakeDateToObject(from);
		to = cakeDateToObject(to);

		try {
			text = $(row).select( 'td.date.filter_date' )[0].innerHTML;

			value = new Date();
			value.setDate( parseInt( text.replace( /^([0-9]+)\/.*$/, '$1' ), 10 ) );
			value.setMonth( parseInt( text.replace( /^.*\/([0-9]+)\/.*$/, '$1' ), 10 ) - 1 );
			value.setYear( parseInt( text.replace( /^.*\/.*\/([0-9]+)$/, '$1' ), 10 ) );

		return checked === false
			|| value === null
			|| ( from.getTime() <= value && to.getTime() >= value );

		} catch( e ) {
			console.log( e );
			return false;
		}
	}


	/**
	 * Filtre des lignes de la table d'actions par type d'action suivant la valeur
	 * du champ de liste déroulante (et de la plage de dates le cas échéant).
	 */
	function filterByAction( table ) {
		var rows = $(table).select( 'tbody tr' ),
			show;

		$(rows).each( function( row ) {
			show = conditionAction( 'SearchActionName', row )
				&& conditionDateRange( 'SearchActionDate', 'SearchActionDateFrom', 'SearchActionDateTo', row );

			if( show ) {
				$(row).show();
			}
			else {
				$(row).hide();
			}
		} );
	}

	/**
	 * Filtre des lignes de la table d'actions par plage de dates (et par type
	 * d'action suivant la valeur du champ de liste déroulante le cas échéant).
	 */
	function filterByDateRange( table ) {
		var rows = $(table).select( 'tbody tr' ),
			show;

		$(rows).each( function( row ) {
			show = conditionAction( 'SearchActionName', row )
				&& conditionDateRange( 'SearchActionDate', 'SearchActionDateFrom', 'SearchActionDateTo', row );

			if( show ) {
				$(row).show();
			}
			else {
				$(row).hide();
			}
		} );
	}

	// -------------------------------------------------------------------------

	$('SearchActionName').observe( 'change', function() {
		filterByAction( 'TableAccompagnementsbeneficiairesIndexActions' );
		return false;
	} );

	[ 'SearchActionDate', 'SearchActionDateFromYear', 'SearchActionDateFromMonth', 'SearchActionDateFromDay', 'SearchActionDateToYear', 'SearchActionDateToMonth', 'SearchActionDateToDay'].each(
		function( field ) {
			$(field).observe( 'change', function() {
				filterByDateRange( 'TableAccompagnementsbeneficiairesIndexActions' );
				return false;
			} );
		}
	);

	//--------------------------------------------------------------------------

	// FIXME: on triche tant que le plugin Default(3) n'a pas été mis à jour avec la clé condition_group
	var colonne = 0;
	$$( 'table#TableAccompagnementsbeneficiairesIndexActions thead th' ).each( function ( th ) {
		if( $(th).hasClassName( 'informations' ) ) {
			colonne++;
		}
		if( colonne > 1 && false === $(th).hasClassName( 'actions' ) ) {
			$(th).hide();
		}
//		console.log(th);
	} );
	//]]>
</script>
<!-- FIXME: à factoriser - Sortable Default3 index table -->
<script type="text/javascript">
	//<![CDATA[
	TableKit.options.rowEvenClass = 'even';
	TableKit.options.rowOddClass = 'odd';
	TableKit.options.descendingClass = 'desc';
	TableKit.options.ascendingClass = 'asc';

	$$( 'table.sortable thead th' ).each( function ( th ) {
		console.log( $(th) );

		if( $(th).hasClassName( 'actions' ) ) {
			$(th).addClassName( 'nosort' );
		}

		if( $(th).hasClassName( 'date' ) ) {
			$(th).addClassName( 'date-au' );
		}
	} );
	//]]>
</script>