<?php
	$this->pageTitle = 'Recherche par DSPs';
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
	}
?>
<h1><?php echo $this->pageTitle;?></h1>
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
	$pagination = $this->Xpaginator->paginationBlock( 'Personne', $this->passedArgs );
?>

<?php echo $this->Form->create( 'Dsp', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( ( !empty( $this->request->data ) ) ? 'folded' : 'unfolded' ) ) );?>
	<?php
		echo $this->Search->blocAllocataire();
		echo $this->Search->blocAdresse( $mesCodesInsee, $cantons );
	?>
	<fieldset>
		<legend>Recherche par dossier</legend>
		<?php
			echo $this->Form->input( 'Dossier.numdemrsa', array( 'label' => 'Numéro de demande RSA' ) );
			echo $this->Form->input( 'Dossier.matricule', array( 'label' => 'N° CAF', 'maxlength' => 15 ) );

			$valueDossierDernier = isset( $this->request->data['Dossier']['dernier'] ) ? $this->request->data['Dossier']['dernier'] : true;
			echo $this->Form->input( 'Dossier.dernier', array( 'label' => 'Uniquement la dernière demande RSA pour un même allocataire', 'type' => 'checkbox', 'checked' => $valueDossierDernier ) );
			echo $this->Search->etatdosrsa($etatdosrsa);
		?>
	</fieldset>

	<fieldset>
		<legend>Données socio-professionnelles</legend>
		<?php
			if( Configure::read( 'Cg.departement' ) == 93 ) {
				echo $this->Form->input( 'Detaildifsoc.difsoc', array( 'label' => 'Difficultés sociales', 'type' => 'select', 'options' => $options['Detaildifsoc']['difsoc'], 'empty' => true ) );
				echo $this->Form->input( 'Detailaccosocindi.nataccosocindi', array( 'label' => 'Domaine d\'accompagnement individuel', 'type' => 'select', 'options' => $options['Detailaccosocindi']['nataccosocindi'], 'empty' => true ) );
				echo $this->Form->input( 'Detaildifdisp.difdisp', array( 'label' => 'Obstacles à la recherche d\'emploi', 'type' => 'select', 'options' => $options['Detaildifdisp']['difdisp'], 'empty' => true ) );
			}
		?>

		<fieldset>
			<legend>Situation professionnelle</legend>
			<?php
				echo $this->Form->input( 'Dsp.nivetu', array( 'label' => "Quelle est votre niveau d'étude ? ", 'type' => 'select', 'options' => $options['Dsp']['nivetu'], 'empty' => true ) );
				echo $this->Form->input( 'Dsp.hispro', array( 'label' => "Passé professionnel ", 'type' => 'select', 'options' => $options['Dsp']['hispro'], 'empty' => true ) );
				if( Configure::read( 'Romev3.enabled' ) ) {
					echo $this->Romev3->fieldset(
						array(
							'modelName' => 'Dsp',
							'prefix' => 'deract',
							'options' => $options
						)
					);

					echo $this->Romev3->fieldset(
						array(
							'modelName' => 'Dsp',
							'prefix' => 'deractdomi',
							'options' => $options
						)
					);

					echo $this->Romev3->fieldset(
						array(
							'modelName' => 'Dsp',
							'prefix' => 'actrech',
							'options' => $options
						)
					);

					// INFO: codes ROME v2
					// TODO: les textes libres à garder
					echo '<fieldset><legend>Dernière activité dominante</legend>';
						echo $this->Form->input( 'Dsp.libsecactdomi66_secteur_id' , array( 'label' => "Dans quel secteur d'activité avez-vous exercé votre activité professionnelle dominante ? ", 'type' => 'select', 'options' => $options['Coderomesecteurdsp66'], 'empty' => true ) );
						echo $this->Form->input( 'Dsp.libsecactdomi', array( 'label' => "Si le secteur est non présent dans la liste, précisez " ) );

						echo $this->Form->input( 'Dsp.libactdomi66_metier_id' , array( 'label' => "Précisez quelle a été l'activité professionnelle dominante ? ", 'type' => 'select', 'options' => $options['Coderomemetierdsp66'], 'empty' => true ) );
						echo $this->Form->input( 'Dsp.libactdomi', array( 'label' => "Si le métier est non présent dans la liste, précisez " ) );
					echo '</fieldset>';

					echo '<fieldset><legend>Emploi recherché</legend>';
						echo $this->Form->input('Dsp.libsecactrech66_secteur_id' , array('label' => "Quel est le secteur d'activité recherché ? ",  'type' => 'select', 'options' => $options['Coderomesecteurdsp66'], 'empty' => true ) );
						echo $this->Form->input( 'Dsp.libsecactrech', array( 'label' => "Si le secteur recherché est non présent dans la liste, précisez " ) );

						echo $this->Form->input( 'Dsp.libemploirech66_metier_id' , array( 'label' => "Quel est l'emploi recherché ? ", 'type' => 'select', 'options' => $options['Coderomemetierdsp66'], 'empty' => true ) );
						echo $this->Form->input( 'Dsp.libemploirech', array( 'label' => "Si le métier recherché est non présent dans la liste, précisez " ) );
					echo '</fieldset>';

				}
				else {
					echo $this->Form->input( 'Dsp.libsecactdomi', array( 'label' => "Dans quel secteur d'activité avez-vous exercé votre activité professionnelle dominante ?" ) );
					echo $this->Form->input( 'Dsp.libactdomi', array( 'label' => "Précisez quelle a été l'activité professionnelle dominante ? " ) );
					echo $this->Form->input( 'Dsp.libsecactrech', array( 'label' => "Quel est le secteur d'activité recherché ?" ) );
					echo $this->Form->input( 'Dsp.libemploirech', array( 'label' => "Quel est l'emploi recherché ? " ) );
				}
			?>
		</fieldset>
	</fieldset>

	<?php
		echo $this->Search->referentParcours( $structuresreferentesparcours, $referentsparcours );
		echo $this->Search->paginationNombretotal();
	?>

	<div class="submit noprint">
		<?php echo $this->Form->button( 'Filtrer', array( 'type' => 'submit' ) );?>
		<?php echo $this->Form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>
<?php echo $this->Form->end();?>

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
					<th><?php echo $this->Xpaginator->sort( 'Nom de l\'allocataire', 'Personne.nom' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Commune de l\'allocataire', 'Adresse.nomcom' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'N° CAF', 'Dossier.matricule' );?></th>
					<!-- TODO: ROME v3 -->
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

					<?php if( Configure::read( 'Cg.departement' ) == 93 ): ?>
						<th>Difficultés sociales</th>
						<th>Domaine d'accompagnement individuel</th>
						<th>Obstacles à la recherche d'emploi</th>
					<?php endif; ?>

					<?php if( Configure::read( 'Romev3.enabled' ) ):?>
						<th>Domaine dernière activité dominante</th>
						<th>Métier activité dominante</th>
						<th>Domaine activité recherchée</th>
						<th>Métier activité recherchée</th>
					<?php endif; ?>

					<th class="action noprint">Actions</th>
					<th class="innerTableHeader noprint">Informations complémentaires</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $dsps as $index => $dsp ):?>
					<?php
						$title = $dsp['Dossier']['numdemrsa'];

						$innerRows = '<tr>
							<th>Date de naissance</th>
							<td>'.date_short( $dsp['Personne']['dtnai'] ).'</td>
						</tr>
						<tr>
							<th>Code INSEE</th>
							<td>'.$dsp['Adresse']['numcom'].'</td>
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
						<tr>
							<th>'.__d( 'search_plugin', 'Structurereferenteparcours.lib_struc' ).'</th>
							<td>'.Hash::get( $dsp, 'Structurereferenteparcours.lib_struc' ).'</td>
						</tr>
						<tr>
							<th>'.__d( 'search_plugin', 'Referentparcours.nom_complet' ).'</th>
							<td>'.Hash::get( $dsp, 'Referentparcours.nom_complet' ).'</td>
						</tr>';

						// Codes ROME V3
						if( Configure::read( 'Romev3.enabled' ) ) {
							foreach( $prefixes as $prefix ) {
								foreach( $suffixes as $suffix ) {
									$modelName = Inflector::classify( "{$prefix}{$suffix}romev3" );
									$innerRows .= '<tr>
										<th>'.__d( 'dsps', "Dsp.{$prefix}{$suffix}romev3_id" ).'</th>
										<td>'.h( Hash::get( $dsp, "{$modelName}.name" ) ).'</td>
									</tr>';
								}
							}
						}

						$innerTable = '<table id="innerTablesearchResults'.$index.'" class="innerTable">
							<tbody>
								'.$innerRows.'
							</tbody>
						</table>';

						$libderact = '';
						$libsecactderact = '';


						if( !empty( $dsp['DspRev']['id'] ) ) {
							$viewLink = $this->Xhtml->viewLink(
								'Voir le dossier « '.$title.' »',
								array( 'controller' => 'dsps', 'action' => 'view_revs', $dsp['DspRev']['id'] ),
								$this->Permissions->check( 'dsps', 'view_revs' )
							);
						}
						else {
							$viewLink = $this->Xhtml->viewLink(
								'Voir le dossier « '.$title.' »',
								array( 'controller' => 'dsps', 'action' => 'view', $dsp['Personne']['id'] ),
								$this->Permissions->check( 'dsps', 'view' )
							);
						}



						$arrayData = array(
							h( $dsp['Personne']['nom'].' '.$dsp['Personne']['prenom'] ),
							h( $dsp['Adresse']['nomcom'] ),
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
								)
							);

							if( Configure::read( 'Cg.departement' ) == 93 ) {
								$links = array(
									'Detaildifsoc.difsoc',
									'Detailaccosocindi.nataccosocindi',
									'Detaildifdisp.difdisp',
								);

								foreach( $links as $link ) {
									list( $modelName, $fieldName ) = model_field( $link );

									$cell = '';
									$values = vfListeToArray( $dsp['Donnees'][$fieldName] );
									if( !empty( $values ) ) {
										$cell .= '<ul>';
										foreach( $values as $value ) {
											$cell .= '<li>- '.h( value( $options[$modelName][$fieldName], $value ) ).'</li>';
										}
										$cell .= '</ul>';
									}

									$arrayData = array_merge(
										$arrayData,
										array(
											$cell
										)
									);
								}
							}

							// Code ROME V3
							if( Configure::read( 'Romev3.enabled' ) ) {
								$arrayData[] = h( $dsp['Deractdomidomaineromev3']['name'] );
								$arrayData[] = h( $dsp['Deractdomimetierromev3']['name'] );
								$arrayData[] = h( $dsp['Actrechdomaineromev3']['name'] );
								$arrayData[] = h( $dsp['Actrechmetierromev3']['name'] );
							}

							$arrayData = array_merge(
								$arrayData,
								array(
									array(
										$viewLink,
										array( 'class' => 'noprint' )
									),
									array( $innerTable, array( 'class' => 'innerTableCell noprint' ) ),
								)
							);
						}

						echo $this->Xhtml->tableCells(
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
				echo $this->Xhtml->printLinkJs(
					'Imprimer le tableau',
					array( 'onclick' => 'printit(); return false;', 'class' => 'noprint' )
				);
			?></li>
			<li><?php
 				echo $this->Xhtml->exportLink(
 					'Télécharger le tableau',
 					array( 'controller' => 'dsps', 'action' => 'exportcsv' ) + Hash::flatten( $this->request->data, '__' ),
 					$this->Permissions->check( 'dsps', 'exportcsv' )
 				);
			?></li>
		</ul>
	<?php echo $pagination;?>

	<?php else:?>
		<p>Vos critères n'ont retourné aucun dossier.</p>
	<?php endif?>
<?php endif?>

<?php echo $this->Search->observeDisableFormOnSubmit( 'Search' ); ?>