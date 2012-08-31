<?php  echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Recherche par Orientation';?>

<h1>Recherche par Orientation</h1>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		observeDisableFieldsetOnCheckbox( 'OrientstructDateValid', $( 'OrientstructDateValidFromDay' ).up( 'fieldset' ), false );
		observeDisableFieldsetOnCheckbox( 'DossierDtdemrsa', $( 'DossierDtdemrsaFromDay' ).up( 'fieldset' ), false );
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
?>
<?php $pagination = $xpaginator->paginationBlock( 'Orientstruct', $this->passedArgs );?>
<?php echo $form->create( 'Critere', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );?>
	<fieldset>
		<legend>Recherche par dossier</legend>
		<?php echo $form->input( 'Critere.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>
		<?php //echo $form->input( 'Critere.etatdosrsa', array( 'label' => 'Situation dossier rsa', 'type' => 'select', 'options' => $etatdosrsa, 'empty' => true ) );?>
		<?php echo $form->input( 'Dossier.numdemrsa', array( 'label' => 'Numéro de dossier RSA', 'maxlength' => 15 ) );?>
		<?php echo $form->input( 'Dossier.matricule', array( 'label' => 'Numéro CAF' ) );?>
		<?php 
			echo $search->natpf( $natpf );
// 			echo $form->input( 'Detailcalculdroitrsa.natpf', array( 'label' => 'Nature de la prestation', 'type' => 'select', 'options' => $natpf, 'empty' => true ) );
		?>
		<?php echo $form->input( 'Dossier.dtdemrsa', array( 'label' => 'Filtrer par date d\'ouverture de droit', 'type' => 'checkbox' ) );?>
		<fieldset>
			<legend>Date de demande RSA</legend>
			<?php
				$dtdemrsa_from = Set::check( $this->data, 'Dossier.dtdemrsa_from' ) ? Set::extract( $this->data, 'Dossier.dtdemrsa_from' ) : strtotime( '-1 week' );
				$dtdemrsa_to = Set::check( $this->data, 'Dossier.dtdemrsa_to' ) ? Set::extract( $this->data, 'Dossier.dtdemrsa_to' ) : strtotime( 'now' );
			?>
			<?php echo $form->input( 'Dossier.dtdemrsa_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $dtdemrsa_from ) );?>
			<?php echo $form->input( 'Dossier.dtdemrsa_to', array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120,  'maxYear' => date( 'Y' ) + 5, 'selected' => $dtdemrsa_to ) );?>
		</fieldset>
		<?php
			$valueDossierDernier = isset( $this->data['Dossier']['dernier'] ) ? $this->data['Dossier']['dernier'] : true;
			echo $form->input( 'Dossier.dernier', array( 'label' => 'Uniquement la dernière demande RSA pour un même allocataire', 'type' => 'checkbox', 'checked' => $valueDossierDernier ) );
		?>
		<?php echo $search->etatdosrsa($etatdosrsa); ?>
	</fieldset>
	<?php
		echo $search->blocAllocataire(  );
		echo $search->blocAdresse( $mesCodesInsee, $cantons );
	?>

	<fieldset>
		<legend>Recherche par parcours allocataire</legend>
		<?php 
			echo $form->input( 'Historiqueetatpe.identifiantpe', array( 'label' => 'Identifiant Pôle Emploi ', 'type' => 'text', 'maxlength' => 11 ) );		
			echo $form->input( 'Critere.hascontrat', array( 'label' => 'Possède un CER ? ', 'type' => 'select', 'options' => array( 'O' => 'Oui', 'N' => 'Non'), 'empty' => true ) );
			echo $form->input( 'Critere.hasreferent', array( 'label' => 'Possède un référent ? ', 'type' => 'select', 'options' => array( 'O' => 'Oui', 'N' => 'Non'), 'empty' => true ) );
		?>
	</fieldset>
	<fieldset>
		<legend>Recherche par orientation</legend>
		<?php
			$valueOrientstructDerniere = isset( $this->data['Orientstruct']['derniere'] ) ? $this->data['Orientstruct']['derniere'] : false;
			echo $form->input( 'Orientstruct.derniere', array( 'label' => 'Uniquement la dernière orientation pour un même allocataire', 'type' => 'checkbox', 'checked' => $valueOrientstructDerniere ) );
		?>
		<?php echo $form->input( 'Orientstruct.date_valid', array( 'label' => 'Filtrer par date d\'orientation', 'type' => 'checkbox' ) );?>
			<fieldset>
				<legend>Date d'orientation</legend>
				<?php
					$date_valid_from = Set::check( $this->data, 'Orientstruct.date_valid_from' ) ? Set::extract( $this->data, 'Orientstruct.date_valid_from' ) : strtotime( '-1 week' );
					$date_valid_to = Set::check( $this->data, 'Orientstruct.date_valid_to' ) ? Set::extract( $this->data, 'Orientstruct.date_valid_to' ) : strtotime( 'now' );
				?>
				<?php echo $form->input( 'Orientstruct.date_valid_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $date_valid_from ) );?>
				<?php echo $form->input( 'Orientstruct.date_valid_to', array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120,  'maxYear' => date( 'Y' ) + 5, 'selected' => $date_valid_to ) );?>
			</fieldset>
			
	<?php if( Configure::read( 'Cg.departement' ) == 66 ):?>
		<fieldset><legend>Orienté par</legend>
			<?php
				if( Configure::read( 'debug' ) > 0 ) {
					echo $javascript->link( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
				}
			?>
			<script type="text/javascript">
				document.observe("dom:loaded", function() {
					dependantSelect( 'OrientstructReferentorientantId', 'OrientstructStructureorientanteId' );
				});
			</script>

			<?php

				echo $form->input( 'Orientstruct.structureorientante_id', array( 'label' => 'Structure', 'type' => 'select', 'options' => $structsorientantes, 'empty' => true ) );
				echo $form->input( 'Orientstruct.referentorientant_id', array(  'label' => 'Nom du professionnel', 'type' => 'select', 'options' => $refsorientants,  'empty' => true ) );
			?>
		</fieldset>
	<?php endif;?>
			
			
		<?php
			if( Configure::read( 'Cg.departement' ) == 93 ) {
				echo $form->input( 'Orientstruct.origine', array( 'label' => __d( 'orientstruct', 'Orientstruct.origine', true ), 'type' => 'select', 'options' => $options['Orientstruct']['origine'], 'empty' => true ) );
			}
		?>

		<?php echo $form->input( 'Orientstruct.typeorient_id', array( 'label' =>  __d( 'structurereferente', 'Structurereferente.lib_type_orient', true ), 'type' => 'select' , 'options' => $typeorient, 'empty' => true ) );?>

		<?php echo $form->input( 'Orientstruct.structurereferente_id', array( 'label' => 'Nom de la structure', 'type' => 'select' , 'options' => $sr, 'empty' => true  ) );?>
	<?php echo $ajax->observeField( 'OrientstructTypeorientId', array( 'update' => 'OrientstructStructurereferenteId', 'url' => Router::url( array( 'action' => 'ajaxstruc' ), true ) ) );?>

		<?php echo $form->input( 'Orientstruct.referent_id', array( 'label' => 'Nom du référent', 'type' => 'select' , 'options' => $referents, 'empty' => true  ) );?>
		<?php echo $form->input( 'Orientstruct.statut_orient', array( 'label' => 'Statut de l\'orientation', 'type' => 'select', 'options' => $statuts, 'empty' => true ) );?>
		<?php echo $form->input( 'Orientstruct.serviceinstructeur_id', array( 'label' => __( 'lib_service', true ), 'type' => 'select' , 'options' => $typeservice, 'empty' => true ) );?>
	</fieldset>

	<div class="submit noprint">
		<?php echo $form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
		<?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>
<?php echo $form->end();?>

<!-- Résultats -->
<?php if( isset( $orients ) ):?>

	<h2 class="noprint">Résultats de la recherche</h2>

	<?php if( is_array( $orients ) && count( $orients ) > 0  ):?>

		<?php echo $pagination;?>
		<table id="searchResults" class="tooltips">
			<thead>
				<tr>
					<th><?php echo $xpaginator->sort( 'Numéro dossier', 'Dossier.numdemrsa' );?></th>
					<th><?php echo $xpaginator->sort( 'Allocataire', 'Personne.nom' );?></th>
					<th><?php echo $xpaginator->sort( 'Commune', 'Adresse.locaadr' );?></th>
					<th><?php echo $xpaginator->sort( 'Date d\'ouverture droits', 'Dossier.dtdemrsa' );?></th>
					<th><?php echo $xpaginator->sort( 'Date d\'orientation', 'Orientstruct.date_valid' );?></th>
					<?php if( Configure::read( 'Cg.departement' ) == 93 ):?>
						<th><?php echo $xpaginator->sort( 'Préconisation d\'orientation', 'Orientstruct.propo_algo' );?></th>
						<th><?php echo $xpaginator->sort( __d( 'orientstruct', 'Orientstruct.origine', true ), 'Orientstruct.origine' );?></th>
					<?php endif;?>
					<th><?php echo $xpaginator->sort( 'Type d\'orientation', 'Orientstruct.typeorient_id' );?></th>
					<th><?php echo $xpaginator->sort( 'Structure référente', 'Structurereferente.lib_struc' );?></th>
					<th><?php echo $xpaginator->sort( 'Statut orientation', 'Orientstruct.statut_orient' );?></th>
					<th><?php echo $xpaginator->sort( 'Soumis à droits et devoirs', 'Calculdroitrsa.toppersdrodevorsa' );?></th>
					<th class="action noprint">Actions</th>
					<th class="innerTableHeader noprint">Informations complémentaires</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $orients as $index => $orient ):?>
					<?php
						$innerTable = '<table id="innerTablesearchResults'.$index.'" class="innerTable">
							<tbody>
								<tr>
									<th>Etat du droit</th>
									<td>'.Set::classicExtract( $etatdosrsa, Set::classicExtract( $orient, 'Situationdossierrsa.etatdosrsa' ) ).'</td>
								</tr>
								<tr>
									<th>Commune de naissance</th>
									<td>'. $orient['Personne']['nomcomnai'].'</td>
								</tr>
								<tr>
									<th>Date de naissance</th>
									<td>'.date_short( $orient['Personne']['dtnai']).'</td>
								</tr>
								<tr>
									<th>Code INSEE</th>
									<td>'.$orient['Adresse']['numcomptt'].'</td>
								</tr>
								<tr>
									<th>NIR</th>
									<td>'.$orient['Personne']['nir'].'</td>
								</tr>
								<tr>
									<th>Identifiant Pôle Emploi</th>
									<td>'.$orient['Historiqueetatpe']['identifiantpe'].'</td>
								</tr>
								<tr>
									<th>N° Téléphone</th>
									<td>'.h( $orient['Modecontact']['numtel'] ).'</td>
								</tr>
								<tr>
									<th>Rôle</th>
									<td>'.Set::enum( $orient['Prestation']['rolepers'], $rolepers ).'</td>
								</tr>								
							</tbody>
						</table>';

						$cells = array(
							h( $orient['Dossier']['numdemrsa'] ),
							h( $orient['Personne']['qual'].' '.$orient['Personne']['nom'].' '.$orient['Personne']['prenom'] ),
							h( $orient['Adresse']['locaadr'] ),
							h( date_short( $orient['Dossier']['dtdemrsa'] ) ),
							h( date_short( $orient['Orientstruct']['date_valid'] ) )
						);

						if( Configure::read( 'Cg.departement' ) == 93 ) {
							$cells[] = h( Set::enum( $orient['Orientstruct']['propo_algo'], $typeorient ) );
							$cells[] = h( Set::enum( $orient['Orientstruct']['origine'], $options['Orientstruct']['origine'] ) );
						}

						array_push(
							$cells,
							h( Set::enum( $orient['Orientstruct']['typeorient_id'], $typeorient ) ),
							h( isset( $sr[$orient['Orientstruct']['structurereferente_id']] ) ? $sr[$orient['Orientstruct']['structurereferente_id']] : null ),
							h( $orient['Orientstruct']['statut_orient'] ),
							( is_null( $orient['Calculdroitrsa']['toppersdrodevorsa'] ) ? $xhtml->image( 'icons/help.png', array( 'alt' => '' ) ).' Non défini' : $xhtml->boolean( $orient['Calculdroitrsa']['toppersdrodevorsa'] ) ),
							array(
								$xhtml->viewLink(
									'Voir le dossier « '.$orient['Dossier']['numdemrsa'].' »',
									array( 'controller' => 'orientsstructs', 'action' => 'index', $orient['Personne']['id'] )
								),
								array( 'class' => 'noprint' )
							),
							array( $innerTable, array( 'class' => 'innerTableCell noprint' ) )
						);

						echo $xhtml->tableCells(
							$cells,
							array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
							array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
						);
					?>
				<?php endforeach;?>
			</tbody>
		</table>

		<?php if( Set::extract( $xpaginator, 'params.paging.Orientstruct.count' ) > 65000 ):?>
			<p class="noprint" style="border: 1px solid #556; background: #ffe;padding: 0.5em;"><?php echo $xhtml->image( 'icons/error.png' );?> <strong>Attention</strong>, il est possible que votre tableur ne puisse pas vous afficher les résultats au-delà de la 65&nbsp;000ème ligne.</p>
		<?php endif;?>
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
					array( 'controller' => 'criteres', 'action' => 'exportcsv' ) + Set::flatten( $this->data, '__' )
				);
			?></li>
		</ul>
		<?php echo $pagination;?>
	<?php else:?>
		<p>Vos critères n'ont retourné aucun dossier.</p>
	<?php endif?>
<?php endif?>