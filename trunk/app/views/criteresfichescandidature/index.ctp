<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
	$this->pageTitle = 'Recherche par Fiches de candidature';
?>

<h1><?php echo $this->pageTitle;?></h1>

<?php
	echo '<ul class="actionMenu"><li>'.$xhtml->link(
		$xhtml->image(
			'icons/application_form_magnify.png',
			array( 'alt' => '' )
		).' Formulaire',
		'#',
		array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
	).'</li></ul>';
?>
<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $javascript->link( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}
?>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		observeDisableFieldsetOnCheckbox( 'ActioncandidatPersonneDatesignature', $( 'ActioncandidatPersonneDatesignatureFromDay' ).up( 'fieldset' ), false );
		observeDisableFieldsetOnCheckbox( 'DossierDtdemrsa', $( 'DossierDtdemrsaFromDay' ).up( 'fieldset' ), false );
	});
</script>


<script type="text/javascript">
	document.observe("dom:loaded", function() {
		dependantSelect( 'ActioncandidatPersonneActioncandidatId', 'PartenaireLibstruc' );
	});
</script>

<?php echo $xform->create( 'Criterefichecandidature', array( 'type' => 'post', 'action' => $this->action, 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );?>

	<?php echo $xform->input( 'ActioncandidatPersonne.indexparams', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>
	<?php
		echo $search->blocAllocataire( );
		echo $search->blocAdresse( $mesCodesInsee, $cantons );
	?>
	<fieldset>
		<legend>Recherche par dossier</legend>
		<?php 
			echo $form->input( 'Dossier.numdemrsa', array( 'label' => 'Numéro de demande RSA' ) );
			echo $form->input( 'Dossier.matricule', array( 'label' => 'N° CAF', 'maxlength' => 15 ) );
			
			$valueDossierDernier = isset( $this->data['Dossier']['dernier'] ) ? $this->data['Dossier']['dernier'] : true;
			echo $form->input( 'Dossier.dernier', array( 'label' => 'Uniquement la dernière demande RSA pour un même allocataire', 'type' => 'checkbox', 'checked' => $valueDossierDernier ) );
			echo $search->etatdosrsa($etatdosrsa);
		?>
		<?php echo $xform->input( 'Dossier.dtdemrsa', array( 'label' => 'Filtrer par date de demande RSA', 'type' => 'checkbox' ) );?>
		<fieldset>
			<legend>Filtrer par période</legend>
			<?php
				$dtdemrsa_from = Set::check( $this->data, 'Dossier.dtdemrsa_from' ) ? Set::extract( $this->data, 'Dossier.dtdemrsa_from' ) : strtotime( '-1 week' );
				$dtdemrsa_to = Set::check( $this->data, 'Dossier.dtdemrsa_to' ) ? Set::extract( $this->data, 'Dossier.dtdemrsa_to' ) : strtotime( 'now' );
			?>
			<?php echo $xform->input( 'Dossier.dtdemrsa_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1, 'minYear' => date( 'Y' ) - 10, 'selected' => $dtdemrsa_from ) );?>
			<?php echo $xform->input( 'Dossier.dtdemrsa_to', array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1, 'minYear' => date( 'Y' ) - 10, 'selected' => $dtdemrsa_to ) );?>
		</fieldset>

	</fieldset>

	<fieldset>
		<legend>Filtrer par Fiche de candidature</legend>
		<?php

			echo $default2->subform(
				array(
					'Partenaire.libstruc' => array( 'type' => 'select', 'options' => $partenaires ),
					'ActioncandidatPersonne.actioncandidat_id' => array( 'type' => 'select', 'options' => $listeactions ),
					'ActioncandidatPersonne.referent_id' => array( 'type' => 'select', 'options' => $referents ),
					'ActioncandidatPersonne.positionfiche' => array( 'type' => 'select', 'options' => $options['positionfiche'] ),
				),
				array(
					'options' => $options
				)
			);

		?>

		<?php echo $xform->input( 'ActioncandidatPersonne.datesignature', array( 'label' => 'Filtrer par date de Fiche de candidature', 'type' => 'checkbox' ) );?>
		<fieldset>
			<legend>Filtrer par période</legend>
			<?php
				$datesignature_from = Set::check( $this->data, 'ActioncandidatPersonne.datesignature_from' ) ? Set::extract( $this->data, 'ActioncandidatPersonne.datesignature_from' ) : strtotime( '-1 week' );
				$datesignature_to = Set::check( $this->data, 'ActioncandidatPersonne.datesignature_to' ) ? Set::extract( $this->data, 'ActioncandidatPersonne.datesignature_to' ) : strtotime( 'now' );
			?>
			<?php echo $xform->input( 'ActioncandidatPersonne.datesignature_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1, 'minYear' => date( 'Y' ) - 10, 'selected' => $datesignature_from ) );?>
			<?php echo $xform->input( 'ActioncandidatPersonne.datesignature_to', array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1, 'minYear' => date( 'Y' ) - 10, 'selected' => $datesignature_to ) );?>
		</fieldset>
	</fieldset>

	<div class="submit noprint">
		<?php echo $xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
		<?php echo $xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>

<?php echo $xform->end();?>
<?php $pagination = $xpaginator->paginationBlock( 'ActioncandidatPersonne', $this->passedArgs ); ?>
<?php echo $pagination;?>
<?php if( isset( $actionscandidats_personnes ) ):?>
	<?php if( is_array( $actionscandidats_personnes ) && count( $actionscandidats_personnes ) > 0  ):?>
		<?php
			echo '<table id="searchResults" class="tooltips"><thead>';
				echo '<tr>
					<th>'.$xpaginator->sort( __d( 'actioncandidat_personne', 'ActioncandidatPersonne.actioncandidat_id', true ), 'ActioncandidatPersonne.actioncandidat_id' ).'</th>
					<th>'.$xpaginator->sort( __d( 'partenaire', 'Partenaire.libstruc', true ), 'Partenaire.libstruc' ).'</th>
					<th>'.$xpaginator->sort( __d( 'personne', 'Personne.nom_complet', true ), 'Personne.nom_complet' ).'</th>
					<th>'.$xpaginator->sort( __d( 'referent', 'Referent.nom_complet', true ), 'Referent.nom_complet' ).'</th>
					<th>'.$xpaginator->sort( __d( 'actioncandidat_personne', 'ActioncandidatPersonne.positionfiche', true ), 'ActioncandidatPersonne.positionfiche' ).'</th>
					<th>'.$xpaginator->sort( __d( 'actioncandidat_personne', 'ActioncandidatPersonne.datesignature', true ), 'ActioncandidatPersonne.datesignature' ).'</th>
					<th>Actions</th>
					<th class="innerTableHeader noprint">Informations complémentaires</th>
				</tr></thead><tbody>';
			foreach( $actionscandidats_personnes as $index => $actioncandidat_personne ) {
				$innerTable = '<table id="innerTablesearchResults'.$index.'" class="innerTable">
					<tbody>
						<tr>
							<th>Code INSEE</th>
							<td>'.$actioncandidat_personne['Adresse']['numcomptt'].'</td>
						</tr>
						<tr>
							<th>Localité</th>
							<td>'.$actioncandidat_personne['Adresse']['locaadr'].'</td>
						</tr>
					</tbody>
				</table>';

				echo '<tr>
					<td>'.h( $actioncandidat_personne['Actioncandidat']['name'] ).'</td>
					<td>'.h( $actioncandidat_personne['Partenaire']['libstruc'] ).'</td>
					<td>'.h( $actioncandidat_personne['Personne']['nom_complet'] ).'</td>
					<td>'.h( $actioncandidat_personne['Referent']['nom_complet'] ).'</td>
					<td>'.h( Set::enum( Set::classicExtract( $actioncandidat_personne, 'ActioncandidatPersonne.positionfiche' ),  $options['positionfiche'] ) ).'</td>',
					'<td>'.h( date_short( $actioncandidat_personne['ActioncandidatPersonne']['datesignature'] ) ).'</td>',
					'<td>'.$xhtml->link( 'Voir', array( 'controller' => 'actionscandidats_personnes', 'action' => 'index', $actioncandidat_personne['ActioncandidatPersonne']['personne_id'] ) ).'</td>
					<td class="innerTableCell noprint">'.$innerTable.'</td>
				</tr>';
			}
			echo '</tbody></table>';
	?>
	<ul class="actionMenu">
		<li><?php
			echo $xhtml->printLinkJs(
				'Imprimer le tableau',
				array( 'onclick' => 'printit(); return false;', 'class' => 'noprint' )
			);
		?></li>
		<li><?php
			echo $xhtml->exportLink(
				'Télécharger le tableau',
				array( 'controller' => 'criteresfichescandidature', 'action' => 'exportcsv' ) + Set::flatten( $this->data, '__' )
			);
		?></li>
	</ul>
<?php echo $pagination;?>

	<?php else:?>
		<?php echo $xhtml->tag( 'p', 'Aucun résultat ne correspond aux critères choisis.', array( 'class' => 'notice' ) );?>
	<?php endif;?>
<?php endif;?>