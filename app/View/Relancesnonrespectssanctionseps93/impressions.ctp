<?php
	if( Configure::read( 'debug' ) ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}
	$this->pageTitle = __d( 'relancenonrespectsanctionep93', 'Relancesnonrespectssanctionseps93::impressions' );
?>

<h1><?php echo $this->pageTitle;?></h1>

<?php if( is_array( $this->request->data ) ):?>
	<ul class="actionMenu">
		<?php
			echo '<li>'.$this->Xhtml->link(
				$this->Xhtml->image(
					'icons/application_form_magnify.png',
					array( 'alt' => '' )
				).' Formulaire',
				'#',
				array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "var form = $$( 'form' ); form = form[0]; $( form ).toggle(); return false;" )
			).'</li>';
		?>
	</ul>
<?php endif;?>

<?php
	// Formulaire
	echo $this->Xform->create( null, array( 'id' => 'Search' ) );

	echo $this->Xhtml->tag( 'fieldset', $this->Xhtml->tag( 'legend', 'Recherche par bénéficiaire' ).
		$this->Default2->subform(
			array(
				'Personne.nom' => array( 'required' => false ),
				'Personne.nomnai',
				'Personne.prenom' => array( 'required' => false ),
				'Personne.nir',
				'Adresse.numcomptt' => array( 'required' => false ),
				'Serviceinstructeur.id' => array( 'domain' => 'relancenonrespectsanctionep93' ),
			),
			array(
				'options' => $options
			)
		)
	);

	echo $this->Xhtml->tag( 'fieldset', $this->Xhtml->tag( 'legend', 'Recherche par dossier CAF' ).
		$this->Default2->subform(
			array(
				'Dossier.matricule',
				'Dossiercaf.nomtitulaire',
				'Dossiercaf.prenomtitulaire',
				'Nonrespectsanctionep93.origine' => array( 'label' => 'Présence contrat', 'type' => 'radio', 'options' => array( 'orientstruct' => 'Non', 'contratinsertion' => 'Oui' ), 'required' => false ),
			)
		)
	);
?>

<div class="noprint">
	<?php echo $this->Form->input( 'Relancenonrespectsanctionep93.daterelance', array( 'label' => 'Filtrer par période de relance', 'type' => 'checkbox' ) );?>
</div>
<fieldset class="noprint">
	<legend class="noprint">Date de Relance</legend>
	<?php
		$daterelance_from = Set::check( $this->request->data, 'Relancenonrespectsanctionep93.daterelance_from' ) ? Set::extract( $this->request->data, 'Relancenonrespectsanctionep93.daterelance_from' ) : strtotime( '-1 week' );
		$daterelance_to = Set::check( $this->request->data, 'Relancenonrespectsanctionep93.daterelance_to' ) ? Set::extract( $this->request->data, 'Relancenonrespectsanctionep93.daterelance_to' ) : strtotime( 'now' );
	?>
	<?php echo $this->Form->input( 'Relancenonrespectsanctionep93.daterelance_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $daterelance_from ) );?>
	<?php echo $this->Form->input( 'Relancenonrespectsanctionep93.daterelance_to', array( 'label' => 'Au (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 5, 'minYear' => date( 'Y' ) - 120, 'selected' => $daterelance_to ) );?>
</fieldset>

<?php
	echo $this->Search->referentParcours( $structuresreferentesparcours, $referentsparcours, 'Search' );
	echo $this->Search->paginationNombretotal( 'Search.Pagination.nombre_total' );
	echo $this->Search->observeDisableFormOnSubmit( 'Search' );

	echo $this->Xform->end( __( 'Rechercher' ) );
?>

<?php if( isset( $relances ) ):?>
	<?php if( empty( $relances ) ):?>
		<p class="notice">Aucun dossier relancé ne correspond à vos critères.</p>
	<?php else:?>
		<?php
			$pagination = $this->Xpaginator->paginationBlock( 'Relancenonrespectsanctionep93', $this->passedArgs );
			echo $pagination;
		?>
		<table class="default2">
			<thead>
				<tr>
					<th><?php echo $this->Xpaginator->sort( __d( 'dossier', 'Dossier.matricule' ), 'Dossier.matricule' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Nom / prénom bénéficiaire', 'Personne.nom' );?></th>
					<th><?php echo $this->Xpaginator->sort( __d( 'personne', 'Personne.nir' ), 'Personne.nir' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Ville', 'Adresse.locaadr' );?></th>
					<th><?php echo $this->Xpaginator->sort( __d( 'foyer', 'Foyer.enerreur' ), 'Foyer.enerreur' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Présence contrat ?', 'Contratinsertion.id' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Date de fin du dernier contrat', 'Contratinsertion.df_ci' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Nbre jours depuis la fin du dernier contrat', 'Contratinsertion.nbjours' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Date d\'orientation', 'Orientstruct.date_impression' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Nbre jours depuis orientation', 'Orientstruct.nbjours' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Statut EP', 'Passagecommissionep.etatdossierep' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Origine', 'Nonrespectsanctionep93.origine' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Date de relance', 'Relancenonrespectsanctionep93.daterelance' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Rang de relance', 'Relancenonrespectsanctionep93.numrelance' );?></th>
					<th colspan="2">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach( $relances as $relance ) {
						$etatdossierep = $relance['Passagecommissionep']['etatdossierep'];
						if( empty( $etatdossierep ) && !empty( $relance['Dossierep']['id'] ) ) {
							$etatdossierep = 'En attente';
						}
						else {
							$etatdossierep = Set::enum( $relance['Passagecommissionep']['etatdossierep'], $options['Passagecommissionep']['etatdossierep'] );
						}

						echo $this->Xhtml->tableCells(
							array(
								h( $relance['Dossier']['matricule'] ),
								h( "{$relance['Personne']['nom']} {$relance['Personne']['prenom']}" ),
								h( $relance['Personne']['nir'] ),
								h( $relance['Adresse']['locaadr'] ),
								array( h( @$relance['Foyer']['enerreur'] ), array( 'class' => 'foyer_enerreur '.( empty( $relance['Foyer']['enerreur'] ) ? 'empty' : null ) ) ),
								h( empty( $relance['Contratinsertion']['id'] ) ? 'Non' : 'Oui' ),
								$this->Locale->date( 'Locale->date', $relance['Contratinsertion']['df_ci'] ),
								h( $relance['Contratinsertion']['nbjours'] ),
								$this->Locale->date( 'Locale->date', $relance['Orientstruct']['date_impression'] ),
								h( $relance['Orientstruct']['nbjours'] ),
								h( $etatdossierep ),
								h( Set::enum( $relance['Nonrespectsanctionep93']['origine'], $options['Nonrespectsanctionep93']['origine'] ) ),
								$this->Locale->date( 'Locale->date', $relance['Relancenonrespectsanctionep93']['daterelance'] ),
								( ( $relance['Relancenonrespectsanctionep93']['numrelance'] < 2 ) ? '1ère relance' : "{$relance['Relancenonrespectsanctionep93']['numrelance']}ème relance" ),
								$this->Default2->button( 'view', array( 'controller' => 'relancesnonrespectssanctionseps93', 'action' => 'index', $relance['Personne']['id'] ), array( 'label' => 'Voir', 'enabled' => $this->Permissions->check( 'relancesnonrespectssanctionseps93', 'index' ), 'target' => 'external' ) ),
								$this->Default2->button( 'print', array( 'controller' => 'relancesnonrespectssanctionseps93', 'action' => 'impression', $relance['Relancenonrespectsanctionep93']['id'] ), array( 'enabled' => ( !empty( $relance['Pdf']['id'] ) && $this->Permissions->check( 'relancesnonrespectssanctionseps93', 'index' ) ), 'label' => 'Imprimer' ) )
							),
							array( 'class' => 'odd' ),
							array( 'class' => 'even' )
						);
					}
				?>
			</tbody>
		</table>
		<?php echo $pagination;?>
	<?php endif;?>

	<ul class="actionMenu">
		<li><?php
			echo $this->Xhtml->printLinkJs(
				'Imprimer le tableau',
				array( 'onclick' => 'printit(); return false;' )
			);
		?></li>
		<li><?php
			echo $this->Xhtml->exportLink(
				'Télécharger',
				Hash::merge( array( 'controller' => $this->request->params['controller'], 'action' => 'exportcsv' ), Hash::flatten( $this->request->data, '__' ) ),
				$this->Permissions->check( $this->request->params['controller'], 'exportcsv' )
			);
		?></li>
		<li><?php
		echo $this->Xhtml->printCohorteLink(
			'Imprimer la cohorte',
			Set::merge(
				array(
					'controller' => $this->request->params['controller'],
					'action'     => 'impression_cohorte'
				),
				Hash::flatten( $this->request->data, '__' )
			),
			$this->Permissions->check( $this->request->params['controller'], 'impression_cohorte' )
		);
		?></li>
	</ul>
<?php endif;?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		observeDisableFieldsetOnCheckbox( 'Relancenonrespectsanctionep93Daterelance', $( 'Relancenonrespectsanctionep93DaterelanceFromDay' ).up( 'fieldset' ), false );

		var form = $$( 'form' );
		form = form[0];
		<?php if( isset( $relances ) ):?>$( form ).hide();<?php endif;?>
	});
</script>