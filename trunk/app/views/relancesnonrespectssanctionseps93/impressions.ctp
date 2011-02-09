<?php
	if( Configure::read( 'debug' ) ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	$this->pageTitle = __d( 'relancenonrespectsanctionep93', 'Relancesnonrespectssanctionseps93::impressions', true );
?>

<h1><?php echo $this->pageTitle;?></h1>

<?php if( is_array( $this->data ) ):?>
	<ul class="actionMenu">
		<?php
			echo '<li>'.$xhtml->link(
				$xhtml->image(
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
	echo $xform->create();

	echo $xhtml->tag( 'fieldset', $xhtml->tag( 'legend', 'Recherche par bénéficiaire' ).
		$default->subform(
			array(
				'Personne.nom' => array( 'type' => 'text', 'required' => false ),
				'Personne.nomnai' => array( 'type' => 'text' ),
				'Personne.prenom' => array( 'type' => 'text', 'required' => false ),
				'Personne.nir' => array( 'type' => 'text' ),
				'Adresse.numcomptt' => array( 'required' => false ),
				'Serviceinstructeur.id' => array( 'domain' => 'relancenonrespectsanctionep93' ),// suiviinstruction
			),
			array(
				'options' => $options
			)
		)
	);

	echo $xhtml->tag( 'fieldset', $xhtml->tag( 'legend', 'Recherche par dossier CAF' ).
		$default->subform(
			array(
				'Dossier.matricule' => array( 'type' => 'text' ),
				'Dossiercaf.nomtitulaire' => array( 'type' => 'text' ),
				'Dossiercaf.prenomtitulaire' => array( 'type' => 'text' ),
				//'Relance.contrat' => array( 'type' => 'radio', 'options' => array( 0 => 'Non', 1 => 'Oui' ) ),
				'Nonrespectsanctionep93.origine' => array( 'label' => 'Présence contrat', 'type' => 'radio', 'options' => array( 'orientstruct' => 'Non', 'contratinsertion' => 'Oui' ), 'required' => false ),
			)
		)
	);
?>

<div class="noprint">
	<?php echo $form->input( 'Relancenonrespectsanctionep93.daterelance', array( 'label' => 'Filtrer par période de relance', 'type' => 'checkbox' ) );?>
</div>
<fieldset class="noprint">
	<legend class="noprint">Date de Relance</legend>
	<?php
		$daterelance_from = Set::check( $this->data, 'Relancenonrespectsanctionep93.daterelance_from' ) ? Set::extract( $this->data, 'Relancenonrespectsanctionep93.daterelance_from' ) : strtotime( '-1 week' );
		$daterelance_to = Set::check( $this->data, 'Relancenonrespectsanctionep93.daterelance_to' ) ? Set::extract( $this->data, 'Relancenonrespectsanctionep93.daterelance_to' ) : strtotime( 'now' );
	?>
	<?php echo $form->input( 'Relancenonrespectsanctionep93.daterelance_from', array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $daterelance_from ) );?>
	<?php echo $form->input( 'Relancenonrespectsanctionep93.daterelance_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 5, 'minYear' => date( 'Y' ) - 120, 'selected' => $daterelance_to ) );?>
</fieldset>

<?php
	echo $xform->end( __( 'Rechercher', true ) );
?>

<?php if( isset( $relances ) ):?>
	<?php if( empty( $relances ) ):?>
		<p class="notice">Aucun dossier relancé ne correspond à vos critères.</p>
	<?php else:?>
		<?php
			$pagination = $xpaginator->paginationBlock( 'Relancenonrespectsanctionep93', $this->passedArgs );
			echo $pagination;
		?>
		<table class="default2">
			<thead>
				<tr>
					<th><?php echo $xpaginator->sort( __d( 'dossier', 'Dossier.matricule', true ), 'Dossier.matricule' );?></th>
					<th><?php echo $xpaginator->sort( 'Nom / prénom bénéficiaire', 'Personne.nom' );?></th>
					<th><?php echo $xpaginator->sort( __d( 'personne', 'Personne.nir', true ), 'Personne.nir' );?></th>
					<th><?php echo $xpaginator->sort( 'Ville', 'Adresse.locaadr' );?></th>
					<th><?php echo $xpaginator->sort( 'Présence contrat ?', 'Contratinsertion.id' );?></th>
					<th><?php echo $xpaginator->sort( 'Date de fin du dernier contrat', 'Contratinsertion.df_ci' );?></th>
					<th><?php echo $xpaginator->sort( 'Nbre jours depuis la fin du dernier contrat', 'Contratinsertion.nbjours' );?></th>
					<th><?php echo $xpaginator->sort( 'Date d\'orientation', 'Orientstruct.date_impression' );?></th>
					<th><?php echo $xpaginator->sort( 'Nbre jours depuis orientation', 'Orientstruct.nbjours' );?></th>
					<th><?php echo $xpaginator->sort( 'Statut EP', 'Dossierep.etapedossierep' );?></th>
					<th><?php echo $xpaginator->sort( 'Date de relance', 'Relancenonrespectsanctionep93.daterelance' );?></th>
					<th><?php echo $xpaginator->sort( 'Rang de relance', 'Relancenonrespectsanctionep93.numrelance' );?></th>
					<th colspan="2">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach( $relances as $relance ) {
						echo $xhtml->tableCells(
							array(
								h( $relance['Dossier']['matricule'] ),
								h( "{$relance['Personne']['nom']} {$relance['Personne']['prenom']}" ),
								h( $relance['Personne']['nir'] ),
								h( $relance['Adresse']['locaadr'] ),
								h( empty( $relance['Contratinsertion']['id'] ) ? 'Non' : 'Oui' ),
								$locale->date( 'Locale->date', $relance['Contratinsertion']['df_ci'] ),
								h( $relance['Contratinsertion']['nbjours'] ),
								$locale->date( 'Locale->date', $relance['Orientstruct']['date_impression'] ),
								h( $relance['Orientstruct']['nbjours'] ),
								h( Set::enum( $relance['Dossierep']['etapedossierep'], $options['Dossierep']['etapedossierep'] ) ),
								$locale->date( 'Locale->date', $relance['Relancenonrespectsanctionep93']['daterelance'] ),
								( ( $relance['Relancenonrespectsanctionep93']['numrelance'] < 2 ) ? '1ère relance' : "{$relance['Relancenonrespectsanctionep93']['numrelance']}ème relance" ),
								$default2->button( 'view', array( 'controller' => 'relancesnonrespectssanctionseps93', 'action' => 'index', $relance['Personne']['id'] ), array( 'label' => 'Voir' ) ),
								$default2->button( 'print', array( 'controller' => 'relancesnonrespectssanctionseps93', 'action' => 'impression_individuelle', $relance['Pdf']['id'] ), array( 'enabled' => !empty( $relance['Pdf']['id'] ), 'label' => 'Imprimer' ) )
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
			echo $xhtml->printLinkJs(
				'Imprimer le tableau',
				array( 'onclick' => 'printit(); return false;' )
			);
		?></li>
		<li><?php
			echo $xhtml->exportLink(
				'Télécharger',
				array( 'controller' => $this->params['controller'], 'action' => 'exportcsv', implode_assoc( '/', ':', Set::flatten( $this->data ) ) )
			);
		?></li>
		<li><?php
		echo $xhtml->printCohorteLink(
			'Imprimer la cohorte',
			Set::merge(
				array(
					'controller' => $this->params['controller'],
					'action'     => 'impression_cohorte'
				),
				Set::flatten( $this->data )
			)
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