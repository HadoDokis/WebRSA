<?php //debug($options); ?>
<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Recherche par DSPs';
if( Configure::read( 'debug' ) > 0 ) {
	echo $javascript->link( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
}
?>
<h1>Recherche par DSPs</h1>
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
	$pagination = $xpaginator->paginationBlock( 'Personne', $this->passedArgs );
?>

<?php echo $form->create( 'Dsp', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( ( !empty( $this->data ) && empty( $this->validationErrors ) ) ? 'folded' : 'unfolded' ) ) );?>
	<fieldset>
		<legend>Recherche par personne</legend>
		<?php echo $form->input( 'Personne.nom', array( 'label' => 'Nom ', 'type' => 'text' ) );?>
		<?php echo $form->input( 'Personne.prenom', array( 'label' => 'Prénom ', 'type' => 'text' ) );?>
		<?php echo $form->input( 'Personne.nir', array( 'label' => 'NIR ', 'maxlength' => 15 ) );?>
		<?php echo $form->input( 'Dossier.matricule', array( 'label' => 'N° CAF ', 'maxlength' => 15 ) );?>
	</fieldset>
	<?php echo $search->etatdosrsa($etatdosrsa); ?>
	<fieldset>
		<legend>Recherche par Adresse</legend>
		<?php echo $form->input( 'Adresse.locaadr', array( 'label' => 'Commune de l\'allocataire ', 'type' => 'text' ) );?>
		<?php echo $form->input( 'Adresse.numcomptt', array( 'label' => 'Numéro de commune au sens INSEE', 'type' => 'select', 'options' => $mesCodesInsee, 'empty' => true ) );?>
		<?php
			if( Configure::read( 'CG.cantons' ) ) {
				echo $form->input( 'Canton.canton', array( 'label' => 'Canton', 'type' => 'select', 'options' => $cantons, 'empty' => true ) );
			}

		?>
	</fieldset>	
	<fieldset>
		<legend>Situation professionnelle</legend>
		<?php 
			echo $form->input( 'Dsp.nivetu', array( 'label' => "Quelle est votre niveau d'étude ? ", 'type' => 'select', 'options' => $options['Dsp']['nivetu'], 'empty' => true ) );
			echo $form->input( 'Dsp.hispro', array( 'label' => "Passé professionnel ", 'type' => 'select', 'options' => $options['Dsp']['hispro'], 'empty' => true ) );
			if( Configure::read( 'Cg.departement' ) == 66 ) {
				echo '<fieldset><legend>Dernière activité dominante</legend>';
					echo $form->input( 'Dsp.libsecactdomi66_secteur_id' , array( 'label' => "Dans quel secteur d'activité avez-vous exercé votre activité professionnelle dominante ? ", 'type' => 'select', 'options' => $options['Coderomesecteurdsp66'], 'empty' => true ) );
					echo $form->input( 'Dsp.libsecactdomi', array( 'label' => "Si le secteur est non présent dans la liste, précisez " ) );
					
					echo $form->input( 'Dsp.libactdomi66_metier_id' , array( 'label' => "Précisez quelle a été l'activité professionnelle dominante ? ", 'type' => 'select', 'options' => $options['Coderomemetierdsp66'], 'empty' => true ) );				
					echo $form->input( 'Dsp.libactdomi', array( 'label' => "Si le métier est non présent dans la liste, précisez " ) );
				echo '</fieldset>';
				
				echo '<fieldset><legend>Emploi recherché</legend>';
					echo $form->input('Dsp.libsecactrech66_secteur_id' , array('label' => "Quel est le secteur d'activité recherché ? ",  'type' => 'select', 'options' => $options['Coderomesecteurdsp66'], 'empty' => true ) );
					echo $form->input( 'Dsp.libsecactrech', array( 'label' => "Si le secteur recherché est non présent dans la liste, précisez " ) );
					
					echo $form->input( 'Dsp.libemploirech66_metier_id' , array( 'label' => "Quel est l'emploi recherché ? ", 'type' => 'select', 'options' => $options['Coderomemetierdsp66'], 'empty' => true ) );				
					echo $form->input( 'Dsp.libemploirech', array( 'label' => "Si le métier recherché est non présent dans la liste, précisez " ) );
				echo '</fieldset>';
				
			}
			else {
				echo $form->input( 'Dsp.libsecactdomi', array( 'label' => "Dans quel secteur d'activité avez-vous exercé votre activité professionnelle dominante ?" ) );
				echo $form->input( 'Dsp.libactdomi', array( 'label' => "Précisez quelle a été l'activité professionnelle dominante ? " ) );
				echo $form->input( 'Dsp.libsecactrech', array( 'label' => "Quel est le secteur d'activité recherché ?" ) );
				echo $form->input( 'Dsp.libemploirech', array( 'label' => "Quel est l'emploi recherché ? " ) );
				
				
				
			}
		?>
	</fieldset>

	<div class="submit noprint">
		<?php echo $form->button( 'Filtrer', array( 'type' => 'submit' ) );?>
		<?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>
<?php echo $form->end();?>

<script type="text/javascript">
<?php if ( Configure::read( 'Cg.departement' ) == 66 ):?>
	document.observe("dom:loaded", function() {
// 		dependantSelect( 'DspLibderact66MetierId', 'DspLibsecactderact66SecteurId' );
// 		try { $( 'DspLibderact66MetierId' ).onchange(); } catch(id) { }

		dependantSelect( 'DspLibactdomi66MetierId', 'DspLibsecactdomi66SecteurId' );
		try { $( 'DspLibactdomi66MetierId' ).onchange(); } catch(id) { }

		dependantSelect( 'DspLibemploirech66MetierId', 'DspLibsecactrech66SecteurId' );
		try { $( 'DspLibemploirech66MetierId' ).onchange(); } catch(id) { }
	} );
<?php endif;?>
</script>



<!-- Résultats -->
<?php if( isset( $dsps ) ):?>

	<h2 class="noprint">Résultats de la recherche</h2>

	<?php if( is_array( $dsps ) && count( $dsps ) > 0  ):?>

		<?php echo $pagination;?>
		<table id="searchResults" class="tooltips">
			<thead>
				<tr>
					<th><?php echo $xpaginator->sort( 'Nom de l\'allocataire', 'Personne.nom' );?></th>
					<th><?php echo $xpaginator->sort( 'Commune de l\'allocataire', 'Adresse.locaadr' );?></th>
					<th><?php echo $xpaginator->sort( 'N° CAF', 'Dossier.matricule' );?></th>
					<?php if( Configure::read( 'Cg.departement' ) == 66 ): ?>
						<th>Code secteur activité</th>
						<th>Code métier</th>
						<th>Secteur dernière activité dominante</th>
						<th>Dernière activité dominante</th>
						
						<th>Code secteur recherché</th>
						<th>Code métier recherché</th>
						<th>Secteur activité recherché</th>
						<th>Activité recherchée</th>
					<?php else: ?>
						<th>Secteur dernière activité dominante</th>
						<th>Dernière activité dominante</th>
						<th>Secteur activité recherché</th>
						<th>Activité recherchée</th>
					<?php endif; ?>
					
					<th class="action noprint">Actions</th>
					<th class="innerTableHeader noprint">Informations complémentaires</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $dsps as $index => $dsp ):?>
					<?php
						$title = $dsp['Dossier']['numdemrsa'];

						$innerTable = '<table id="innerTablesearchResults'.$index.'" class="innerTable">
							<tbody>
								<tr>
									<th>Date de naissance</th>
									<td>'.date_short( $dsp['Personne']['dtnai'] ).'</td>
								</tr>
								<tr>
									<th>Code INSEE</th>
									<td>'.$dsp['Adresse']['numcomptt'].'</td>
								</tr>
								<tr>
									<th>NIR</th>
									<td>'.$dsp['Personne']['nir'].'</td>
								</tr>
								<tr>
									<th>État du dossier</th>
									<td>'.$etatdosrsa[$dsp['Situationdossierrsa']['etatdosrsa']].'</td>
								</tr>
								<tr>
									<th>Niveau étude</th>
									<td>'.Set::enum( $dsp['Donnees']['nivetu'], $options['Dsp']['nivetu'] ).'</td>
								</tr>
								<tr>
									<th>Passé pofessionnel</th>
									<td>'.Set::enum( $dsp['Donnees']['hispro'], $options['Dsp']['hispro'] ).'</td>
								</tr>
							</tbody>
						</table>';
// debug($dsp);
						$libderact = '';
						$libsecactderact = '';

												
						if( !empty( $dsp['DspRev']['id'] ) ) {
							$viewLink = $xhtml->viewLink(
								'Voir le dossier « '.$title.' »',
								array( 'controller' => 'dsps', 'action' => 'view_revs', $dsp['DspRev']['id'] )
							);
						}
						else {
							$viewLink = $xhtml->viewLink(
								'Voir le dossier « '.$title.' »',
								array( 'controller' => 'dsps', 'action' => 'view', $dsp['Personne']['id'] )
							);
						}

						
						
						$arrayData = array(
							h( $dsp['Personne']['nom'].' '.$dsp['Personne']['prenom'] ),
							h( $dsp['Adresse']['locaadr'] ),
							h( $dsp['Dossier']['matricule'] )
						);

						if( Configure::read( 'Cg.departement' ) == 66 ) {
							$key = $dsp['Donnees']['libsecactdomi66_secteur_id'] . '_' . $dsp['Donnees']['libactdomi66_metier_id'];
							$key2 = $dsp['Donnees']['libsecactrech66_secteur_id'] . '_' . $dsp['Donnees']['libemploirech66_metier_id'];
							$arrayData = array_merge(
								$arrayData,
								array(
									h( Set::enum( $dsp['Donnees']['libsecactdomi66_secteur_id'], $options['Coderomesecteurdsp66'] ) ),
									h( @$options['Coderomemetierdsp66'][$key] ),
									h( $dsp['Donnees']['libsecactdomi'] ),
									h( $dsp['Donnees']['libactdomi'] ),
									h( Set::enum( $dsp['Donnees']['libsecactrech66_secteur_id'], $options['Coderomesecteurdsp66'] ) ),
									h( @$options['Coderomemetierdsp66'][$key2] ),
									h( $dsp['Donnees']['libsecactrech'] ),
									h( $dsp['Donnees']['libemploirech'] ),
									array(
										$viewLink,
										array( 'class' => 'noprint' )
									),
									array( $innerTable, array( 'class' => 'innerTableCell noprint' ) ),
								)
							);
						}

						if( Configure::read( 'Cg.departement' ) != 66 ) {
							$arrayData = array_merge(
								$arrayData,
								array(
									h( $dsp['Donnees']['libsecactdomi'] ),
									h( $dsp['Donnees']['libactdomi'] ),
									h( $dsp['Donnees']['libsecactrech'] ),
									h( $dsp['Donnees']['libemploirech'] ),
									array(
										$viewLink,
										array( 'class' => 'noprint' )
									),
									array( $innerTable, array( 'class' => 'innerTableCell noprint' ) ),
								)
							);
						}

						echo $xhtml->tableCells(
							$arrayData,
							array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
							array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
						);
					?>
				<?php endforeach;?>
			</tbody>
		</table>
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
 					array( 'controller' => 'dsps', 'action' => 'exportcsv', implode_assoc( '/', ':', array_unisize( $this->data ) ) )
 				);
			?></li>
		</ul>
	<?php echo $pagination;?>

	<?php else:?>
		<p>Vos critères n'ont retourné aucun dossier.</p>
	<?php endif?>

<?php endif?>
