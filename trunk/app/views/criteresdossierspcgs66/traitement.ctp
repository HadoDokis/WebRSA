<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php
	$domain = 'dossierpcg66';
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'dossierpcg66', "Criteresdossierspcgs66::{$this->action}", true )
	)
?>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		observeDisableFieldsetOnCheckbox( 'Traitementpcg66Dateecheance', $( 'Traitementpcg66DateecheanceFromDay' ).up( 'fieldset' ), false );
		observeDisableFieldsetOnCheckbox( 'Traitementpcg66Daterevision', $( 'Traitementpcg66DaterevisionFromDay' ).up( 'fieldset' ), false );
	});
</script>
<?php
	if( is_array( $this->data ) ) {
		echo '<ul class="actionMenu"><li>'.$xhtml->link(
			$xhtml->image(
				'icons/application_form_magnify.png',
				array( 'alt' => '' )
			).' Formulaire',
			'#',
			array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
		).'</li></ul>';
	}

	echo $xform->create( 'Criteredossierpcg66', array( 'type' => 'post', 'action' => 'traitement', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );
?>
<fieldset>
	<legend>Recherche par allocataire<!--FIXME: personne du foyer--></legend>
	<?php
		echo $form->input( 'Personne.nir', array( 'label' => 'NIR', 'maxlength' => 15 ) );
		echo $form->input( 'Dossier.matricule', array( 'label' => __d( 'dossier', 'Dossier.matricule', true ), 'maxlength' => 15 ) );
		echo $form->input( 'Dossier.numdemrsa', array( 'label' => __d( 'dossier', 'Dossier.numdemrsa', true ), 'maxlength' => 15 ) );
		echo $form->input( 'Personne.nom', array( 'label' => 'Nom' ) );
		echo $form->input( 'Personne.nomnai', array( 'label' => 'Nom de jeune fille' ) );
		echo $form->input( 'Personne.prenom', array( 'label' => 'Prénom' ) );
		echo $form->input( 'Personne.dtnai', array( 'label' => 'Date de naissance', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'empty' => true ) );
	?>
</fieldset>
<fieldset>
	<legend>Recherche par traitement</legend>
		<?php echo $xform->input( 'Dossierpcg66.user_id', array( 'label' => __d( 'dossierpcg66', 'Dossierpcg66.user_id', true ), 'type' => 'select', 'options' => $gestionnaire, 'empty' => true ) );?>
		<?php echo $xform->input( 'Traitementpcg66.dateecheance', array( 'label' => 'Filtrer par date d\'échéance du traitement', 'type' => 'checkbox' ) );?>
		<fieldset>
			<legend>Date d'échéance du traitement</legend>
			<?php
				$dateecheance_from = Set::check( $this->data, 'Traitementpcg66.dateecheance_from' ) ? Set::extract( $this->data, 'Traitementpcg66.dateecheance_from' ) : strtotime( '-1 week' );
				$dateecheance_to = Set::check( $this->data, 'Traitementpcg66.dateecheance_to' ) ? Set::extract( $this->data, 'Traitementpcg66.dateecheance_to' ) : strtotime( 'now' );
			?>
			<?php echo $form->input( 'Traitementpcg66.dateecheance_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 10, 'selected' => $dateecheance_from ) );?>
			<?php echo $form->input( 'Traitementpcg66.dateecheance_to', array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 10, 'maxYear' => date( 'Y' ) + 5,  'selected' => $dateecheance_to ) );?>
		</fieldset>
		
		<?php echo $xform->input( 'Traitementpcg66.daterevision', array( 'label' => 'Filtrer par date de révision du traitement', 'type' => 'checkbox' ) );?>
		<fieldset>
			<legend>Date de révision du traitement</legend>
			<?php
				$daterevision_from = Set::check( $this->data, 'Traitementpcg66.daterevision_from' ) ? Set::extract( $this->data, 'Traitementpcg66.daterevision_from' ) : strtotime( '-1 week' );
				$daterevision_to = Set::check( $this->data, 'Traitementpcg66.daterevision_to' ) ? Set::extract( $this->data, 'Traitementpcg66.daterevision_to' ) : strtotime( 'now' );
			?>
			<?php echo $form->input( 'Traitementpcg66.daterevision_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 10, 'selected' => $daterevision_from ) );?>
			<?php echo $form->input( 'Traitementpcg66.daterevision_to', array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 10, 'maxYear' => date( 'Y' ) + 5,  'selected' => $daterevision_to ) );?>
		</fieldset>
	<?php
		///Formulaire de recherche pour les PDOs
		echo $default2->subform(
			array(
				'Traitementpcg66.situationpdo_id' => array( 'label' => 'Motif concernant la personne', 'type' => 'select', 'options' => $motifpersonnepcg66, 'empty' => true ),
				'Traitementpcg66.descriptionpdo_id' => array( 'label' => __d( 'traitementpcg66', 'Traitementpcg66.descriptionpdo_id', true ), 'type' => 'select', 'options' => $descriptionpdo, 'empty' => true ),
				'Traitementpcg66.clos' => array( 'label' => __d( 'traitementpcg66', 'Traitementpcg66.clos', true ), 'type' => 'select', 'options' => $options['Traitementpcg66']['clos'], 'empty' => true ),
				'Traitementpcg66.annule' => array( 'label' => __d( 'traitementpcg66', 'Traitementpcg66.annule', true ), 'type' => 'select', 'options' => $options['Traitementpcg66']['annule'], 'empty' => true )
			),
			array(
				'options' => $options
			)
		);
	?>
</fieldset>
	<div class="submit noprint">
		<?php echo $xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
		<?php echo $xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>
<?php echo $xform->end();?>
<?php $pagination = $xpaginator->paginationBlock( 'Dossierpcg66', $this->passedArgs ); ?>

	<?php if( isset( $criteresdossierspcgs66 ) ):?>
	<br />
	<h2 class="noprint aere">Résultats de la recherche</h2>

	<?php if( is_array( $criteresdossierspcgs66 ) && count( $criteresdossierspcgs66 ) > 0  ):?>
		<?php echo $pagination;?>
		<table class="tooltips">
			<thead>
				<tr>
					<th><?php echo $xpaginator->sort( 'N° dossier', 'Dossier.numdemrsa' );?></th>
					<th><?php echo $xpaginator->sort( 'Nom du demandeur', 'Personne.nom' );?></th>
					<th><?php echo $xpaginator->sort( 'Origine de la PDO', 'Dossierpcg66.originepdo_id' );?></th>
					<th><?php echo $xpaginator->sort( 'Date de réception', 'Dossierpcg66.datereceptionpdo' );?></th>
					<th><?php echo $xpaginator->sort( 'Description du traitement', 'Traitementpcg66.descriptionpdo_id' );?></th>
					<th><?php echo $xpaginator->sort( 'Date de révision', 'Traitementpcg66.daterevision' );?></th>
					<th><?php echo $xpaginator->sort( 'Date d\'échéance', 'Traitementpcg66.dateecheance' );?></th>
					<th><?php echo $xpaginator->sort( 'Clos ?', 'Traitementpcg66.clos' );?></th>
					<th><?php echo $xpaginator->sort( 'Annulé ?', 'Traitementpcg66.annule' );?></th>
					<th class="action">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach( $criteresdossierspcgs66 as $index => $criteredossierpcg66 ) {
					
						$etatdosrsaValue = Set::classicExtract( $criteredossierpcg66, 'Situationdossierrsa.etatdosrsa' );
						$etatDossierRSA = isset( $etatdosrsa[$etatdosrsaValue] ) ? $etatdosrsa[$etatdosrsaValue] : 'Non défini';
					
						$innerTable = '<table id="innerTable'.$index.'" class="innerTable">
							<tbody>
								<tr>
									<th>Etat du droit</th>
									<td>'.h( $etatDossierRSA ).'</td>
								</tr>
								<tr>
									<th>Commune de naissance</th>
									<td>'.h( $criteredossierpcg66['Personne']['nomcomnai'] ).'</td>
								</tr>
								<tr>
									<th>Date de naissance</th>
									<td>'.h( date_short( $criteredossierpcg66['Personne']['dtnai'] ) ).'</td>
								</tr>
								<tr>
									<th>Code INSEE</th>
									<td>'.h( $criteredossierpcg66['Adresse']['numcomptt'] ).'</td>
								</tr>
								<tr>
									<th>NIR</th>
									<td>'.h( $criteredossierpcg66['Personne']['nir'] ).'</td>
								</tr>
								<tr>
									<th>N° CAF</th>
									<td>'.h( $criteredossierpcg66['Dossier']['matricule'] ).'</td>
								</tr>

							</tbody>
						</table>';
						
						echo $xhtml->tableCells(
							array(
								h( Set::classicExtract( $criteredossierpcg66, 'Dossier.numdemrsa' ) ),
								h( Set::enum( Set::classicExtract( $criteredossierpcg66, 'Personne.qual' ), $qual ).' '.Set::classicExtract( $criteredossierpcg66, 'Personne.nom' ).' '.Set::classicExtract( $criteredossierpcg66, 'Personne.prenom' ) ),
								h( Set::enum( Set::classicExtract( $criteredossierpcg66, 'Dossierpcg66.originepdo_id' ), $originepdo ) ),
								h( $locale->date( 'Locale->date',  Set::classicExtract( $criteredossierpcg66, 'Dossierpcg66.datereceptionpdo' ) ) ),
								h( Set::classicExtract( $criteredossierpcg66, 'Descriptionpdo.name' ) ),
								h( date_short( Set::classicExtract( $criteredossierpcg66, 'Traitementpcg66.daterevision' ) ) ),
								h( date_short( Set::classicExtract( $criteredossierpcg66, 'Traitementpcg66.dateecheance' ) ) ),
								h( Set::enum( Set::classicExtract( $criteredossierpcg66, 'Traitementpcg66.clos' ), $options['Traitementpcg66']['clos'] ) ),
								h( Set::enum( Set::classicExtract( $criteredossierpcg66, 'Traitementpcg66.annule' ), $options['Traitementpcg66']['annule'] ) ),
								$xhtml->viewLink(
									'Voir',
									array( 'controller' => 'traitementspcgs66', 'action' => 'index', Set::classicExtract( $criteredossierpcg66, 'Personne.id' ), Set::classicExtract( $criteredossierpcg66, 'Dossierpcg66.id' ) )
								),
								array( $innerTable, array( 'class' => 'innerTableCell noprint' ) ),
							),
							array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
							array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
						);
					}
				?>
			</tbody>
		</table>

		<?php echo $pagination;?>
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
					array( 'controller' => 'criteresdossierspcgs66', 'action' => 'exportcsv', implode_assoc( '/', ':', array_unisize( $this->data ) ) )
				);
			?></li>
		</ul>
	<?php else:?>
		<p class="notice">Vos critères n'ont retourné aucun traitement.</p>
	<?php endif?>
<?php endif?>