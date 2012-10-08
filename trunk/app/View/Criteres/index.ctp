<?php
	$this->pageTitle = 'Recherche par Orientation';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}
?>
<h1><?php echo $this->pageTitle; ?></h1>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		observeDisableFieldsetOnCheckbox( 'OrientstructDateValid', $( 'OrientstructDateValidFromDay' ).up( 'fieldset' ), false );
		observeDisableFieldsetOnCheckbox( 'DossierDtdemrsa', $( 'DossierDtdemrsaFromDay' ).up( 'fieldset' ), false );
	});
</script>

<?php
	if( is_array( $this->request->data ) ) {
		echo '<ul class="actionMenu"><li>'.$this->Xhtml->link(
			$this->Xhtml->image(
				'icons/application_form_magnify.png',
				array( 'alt' => '' )
			).' Formulaire',
			'#',
			array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
		).'</li></ul>';
	}
?>
<?php $pagination = $this->Xpaginator->paginationBlock( 'Orientstruct', $this->passedArgs );?>
<?php echo $this->Form->create( 'Critere', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( ( is_array( $this->request->data ) && !empty( $this->request->data ) ) ? 'folded' : 'unfolded' ) ) );?>
	<fieldset>
		<legend>Recherche par dossier</legend>
		<?php echo $this->Form->input( 'Critere.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>
		<?php //echo $this->Form->input( 'Critere.etatdosrsa', array( 'label' => 'Situation dossier rsa', 'type' => 'select', 'options' => $etatdosrsa, 'empty' => true ) );?>
		<?php echo $this->Form->input( 'Dossier.numdemrsa', array( 'label' => 'Numéro de dossier RSA', 'maxlength' => 15 ) );?>
		<?php echo $this->Form->input( 'Dossier.matricule', array( 'label' => 'Numéro CAF' ) );?>
		<?php
			echo $this->Search->natpf( $natpf );
// 			echo $this->Form->input( 'Detailcalculdroitrsa.natpf', array( 'label' => 'Nature de la prestation', 'type' => 'select', 'options' => $natpf, 'empty' => true ) );
		?>
		<?php echo $this->Form->input( 'Dossier.dtdemrsa', array( 'label' => 'Filtrer par date d\'ouverture de droit', 'type' => 'checkbox' ) );?>
		<fieldset>
			<legend>Date de demande RSA</legend>
			<?php
				$dtdemrsa_from = Set::check( $this->request->data, 'Dossier.dtdemrsa_from' ) ? Set::extract( $this->request->data, 'Dossier.dtdemrsa_from' ) : strtotime( '-1 week' );
				$dtdemrsa_to = Set::check( $this->request->data, 'Dossier.dtdemrsa_to' ) ? Set::extract( $this->request->data, 'Dossier.dtdemrsa_to' ) : strtotime( 'now' );
			?>
			<?php echo $this->Form->input( 'Dossier.dtdemrsa_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $dtdemrsa_from ) );?>
			<?php echo $this->Form->input( 'Dossier.dtdemrsa_to', array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120,  'maxYear' => date( 'Y' ) + 5, 'selected' => $dtdemrsa_to ) );?>
		</fieldset>
		<?php
			$valueDossierDernier = isset( $this->request->data['Dossier']['dernier'] ) ? $this->request->data['Dossier']['dernier'] : true;
			echo $this->Form->input( 'Dossier.dernier', array( 'label' => 'Uniquement la dernière demande RSA pour un même allocataire', 'type' => 'checkbox', 'checked' => $valueDossierDernier ) );
		?>
		<?php echo $this->Search->etatdosrsa($etatdosrsa); ?>
	</fieldset>
	<?php
		echo $this->Search->blocAllocataire(  );
		echo $this->Search->blocAdresse( $mesCodesInsee, $cantons );
	?>

	<fieldset>
		<legend>Recherche par parcours allocataire</legend>
		<?php
			echo $this->Form->input( 'Historiqueetatpe.identifiantpe', array( 'label' => 'Identifiant Pôle Emploi ', 'type' => 'text', 'maxlength' => 11 ) );
			echo $this->Form->input( 'Critere.hascontrat', array( 'label' => 'Possède un CER ? ', 'type' => 'select', 'options' => array( 'O' => 'Oui', 'N' => 'Non'), 'empty' => true ) );
			echo $this->Form->input( 'Critere.hasreferent', array( 'label' => 'Possède un référent ? ', 'type' => 'select', 'options' => array( 'O' => 'Oui', 'N' => 'Non'), 'empty' => true ) );
		?>
	</fieldset>
	<fieldset>
		<legend>Recherche par orientation</legend>
		<?php
			$valueOrientstructDerniere = isset( $this->request->data['Orientstruct']['derniere'] ) ? $this->request->data['Orientstruct']['derniere'] : false;
			echo $this->Form->input( 'Orientstruct.derniere', array( 'label' => 'Uniquement la dernière orientation pour un même allocataire', 'type' => 'checkbox', 'checked' => $valueOrientstructDerniere ) );
		?>
		<?php echo $this->Form->input( 'Orientstruct.date_valid', array( 'label' => 'Filtrer par date d\'orientation', 'type' => 'checkbox' ) );?>
			<fieldset>
				<legend>Date d'orientation</legend>
				<?php
					$date_valid_from = Set::check( $this->request->data, 'Orientstruct.date_valid_from' ) ? Set::extract( $this->request->data, 'Orientstruct.date_valid_from' ) : strtotime( '-1 week' );
					$date_valid_to = Set::check( $this->request->data, 'Orientstruct.date_valid_to' ) ? Set::extract( $this->request->data, 'Orientstruct.date_valid_to' ) : strtotime( 'now' );
				?>
				<?php echo $this->Form->input( 'Orientstruct.date_valid_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $date_valid_from ) );?>
				<?php echo $this->Form->input( 'Orientstruct.date_valid_to', array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120,  'maxYear' => date( 'Y' ) + 5, 'selected' => $date_valid_to ) );?>
			</fieldset>

	<?php if( Configure::read( 'Cg.departement' ) == 66 ):?>
		<fieldset><legend>Orienté par</legend>
			<script type="text/javascript">
				document.observe("dom:loaded", function() {
					dependantSelect( 'OrientstructReferentorientantId', 'OrientstructStructureorientanteId' );
				});
			</script>

			<?php

				echo $this->Form->input( 'Orientstruct.structureorientante_id', array( 'label' => 'Structure', 'type' => 'select', 'options' => $structsorientantes, 'empty' => true ) );
				echo $this->Form->input( 'Orientstruct.referentorientant_id', array(  'label' => 'Nom du professionnel', 'type' => 'select', 'options' => $refsorientants,  'empty' => true ) );
			?>
		</fieldset>
	<?php endif;?>


		<?php
			if( Configure::read( 'Cg.departement' ) == 93 ) {
				echo $this->Form->input( 'Orientstruct.origine', array( 'label' => __d( 'orientstruct', 'Orientstruct.origine' ), 'type' => 'select', 'options' => $options['Orientstruct']['origine'], 'empty' => true ) );
			}
		?>

		<?php echo $this->Form->input( 'Orientstruct.typeorient_id', array( 'label' =>  __d( 'structurereferente', 'Structurereferente.lib_type_orient' ), 'type' => 'select' , 'options' => $typeorient, 'empty' => true ) );?>

		<?php echo $this->Form->input( 'Orientstruct.structurereferente_id', array( 'label' => 'Nom de la structure', 'type' => 'select' , 'options' => $sr, 'empty' => true  ) );?>
	<?php echo $this->Ajax->observeField( 'OrientstructTypeorientId', array( 'update' => 'OrientstructStructurereferenteId', 'url' => Router::url( array( 'action' => 'ajaxstruc' ), true ) ) );?>

		<?php echo $this->Form->input( 'Orientstruct.referent_id', array( 'label' => 'Nom du référent', 'type' => 'select' , 'options' => $referents, 'empty' => true  ) );?>
		<?php echo $this->Form->input( 'Orientstruct.statut_orient', array( 'label' => 'Statut de l\'orientation', 'type' => 'select', 'options' => $statuts, 'empty' => true ) );?>
		<?php echo $this->Form->input( 'Orientstruct.serviceinstructeur_id', array( 'label' => __( 'lib_service' ), 'type' => 'select' , 'options' => $typeservice, 'empty' => true ) );?>
	</fieldset>

	<div class="submit noprint">
		<?php echo $this->Form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
		<?php echo $this->Form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>
<?php echo $this->Form->end();?>

<!-- Résultats -->
<?php if( isset( $orients ) ):?>

	<h2 class="noprint">Résultats de la recherche</h2>

	<?php if( is_array( $orients ) && count( $orients ) > 0  ):?>

		<?php echo $pagination;?>
		<table id="searchResults" class="tooltips">
			<thead>
				<tr>
					<th><?php echo $this->Xpaginator->sort( 'Numéro dossier', 'Dossier.numdemrsa' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Allocataire', 'Personne.nom' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Commune', 'Adresse.locaadr' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Date d\'ouverture droits', 'Dossier.dtdemrsa' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Date d\'orientation', 'Orientstruct.date_valid' );?></th>
					<?php if( Configure::read( 'Cg.departement' ) == 93 ):?>
						<th><?php echo $this->Xpaginator->sort( 'Préconisation d\'orientation', 'Orientstruct.propo_algo' );?></th>
						<th><?php echo $this->Xpaginator->sort( __d( 'orientstruct', 'Orientstruct.origine' ), 'Orientstruct.origine' );?></th>
					<?php endif;?>
					<th><?php echo $this->Xpaginator->sort( 'Type d\'orientation', 'Orientstruct.typeorient_id' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Structure référente', 'Structurereferente.lib_struc' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Statut orientation', 'Orientstruct.statut_orient' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Soumis à droits et devoirs', 'Calculdroitrsa.toppersdrodevorsa' );?></th>
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
                        
                        $adresseCanton = $orient['Adresse']['locaadr']."- \n".$orient['Canton']['canton'];


						$cells = array(
							h( $orient['Dossier']['numdemrsa'] ),
							h( $orient['Personne']['qual'].' '.$orient['Personne']['nom'].' '.$orient['Personne']['prenom'] ),
							nl2br( h( $adresseCanton ) ),
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
							( is_null( $orient['Calculdroitrsa']['toppersdrodevorsa'] ) ? $this->Xhtml->image( 'icons/help.png', array( 'alt' => '' ) ).' Non défini' : $this->Xhtml->boolean( $orient['Calculdroitrsa']['toppersdrodevorsa'] ) ),
							array(
								$this->Xhtml->viewLink(
									'Voir le dossier « '.$orient['Dossier']['numdemrsa'].' »',
									array( 'controller' => 'orientsstructs', 'action' => 'index', $orient['Personne']['id'] )
								),
								array( 'class' => 'noprint' )
							),
							array( $innerTable, array( 'class' => 'innerTableCell noprint' ) )
						);

						echo $this->Xhtml->tableCells(
							$cells,
							array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
							array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
						);
					?>
				<?php endforeach;?>
			</tbody>
		</table>

		<?php if( Set::extract( $this->request->params, 'paging.Orientstruct.count' ) > 65000 ):?>
			<p class="noprint" style="border: 1px solid #556; background: #ffe;padding: 0.5em;"><?php echo $this->Xhtml->image( 'icons/error.png' );?> <strong>Attention</strong>, il est possible que votre tableur ne puisse pas vous afficher les résultats au-delà de la 65&nbsp;000ème ligne.</p>
		<?php endif;?>
		<ul class="actionMenu">
			<li><?php
				echo $this->Xhtml->printLinkJs(
					'Imprimer le tableau',
					array( 'onclick' => 'printit(); return false;', 'class' => 'noprint' )
				);
			?></li>
			<li><?php
				echo $this->Xhtml->exportLink(
					'Télécharger le tableau',
					array( 'controller' => 'criteres', 'action' => 'exportcsv' ) + Set::flatten( $this->request->data, '__' )
				);
			?></li>
		</ul>
		<?php echo $pagination;?>
	<?php else:?>
		<p>Vos critères n'ont retourné aucun dossier.</p>
	<?php endif?>
<?php endif?>