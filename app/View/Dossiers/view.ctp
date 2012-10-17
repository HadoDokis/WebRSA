<?php
	function thead( $pct = 10 ) {
		return '<thead>
				<tr>
					<th>&nbsp;</th>
					<th style="width: '.$pct.'%;">Demandeur</th>
					<th style="width: '.$pct.'%;">Conjoint</th>
				</tr>
			</thead>';
	}

	function theadPastDossierDEM( $pctValue = 10, $pctAction = 8 ) {
		return '<thead>
				<tr>
					<th>&nbsp;</th>
					<th style="width: '.$pctValue.'%;">Demandeur</th>
					<th style="width: '.$pctAction.'%;">Action</th>
				</tr>
			</thead>';
	}

	function theadPastDossierCJT( $pctValue = 10, $pctAction = 8 ) {
		return '<thead>
				<tr>
					<th>&nbsp;</th>
					<th style="width: '.$pctValue.'%;">Conjoint</th>
					<th style="width: '.$pctAction.'%;">Action</th>
				</tr>
			</thead>';
	}

	function linkedValue( $links, $details, $personne, $table, $field ) {
		$value = ( ( isset( $details[$personne][$table] ) && isset( $details[$personne][$table][$field] ) ) ? ( $details[$personne][$table][$field] ) : null );
		return ( isset( $links[$value] ) ? $links[$value] : null );
	}

	function textToppersdrodevorsa( $calculdroitrsa ) {
		if( !isset( $calculdroitrsa['toppersdrodevorsa'] ) ) {
			return null;
		}

		if( is_null( $calculdroitrsa['toppersdrodevorsa'] ) ) {
			return 'Non défini';
		}
		else if( $calculdroitrsa['toppersdrodevorsa'] == 1 ) {
			return 'Oui';
		}
		else {
			return 'Non';
		}
	}

	function textPresenceDsp( $allocataire ) {
		if( !isset( $allocataire['Personne']['id'] ) ) {
			return null;
		}

		if( isset( $allocataire['Dsp']['id'] ) ) {
			return 'Oui';
		}
		else {
			return 'Non';
		}
	}

	/////  Récupération données du Contratinsertion pour le DEM et le CJT
	$DT = Set::extract( 'DEM.Contratinsertion.num_contrat', $details);
	$CT = Set::extract( 'CJT.Contratinsertion.num_contrat', $details);

	$deciD = Set::extract( 'DEM.Contratinsertion.decision_ci', $details);
	$deciC = Set::extract( 'CJT.Contratinsertion.decision_ci', $details);

?>
<?php $this->pageTitle = 'Dossier RSA '.$details['Dossier']['numdemrsa'];?>

<?php echo $this->element( 'dossier_menu', array( 'id' => $details['Dossier']['id'] ) );?>

<div class="with_treemenu">
<div id="resumeDossier">
	<?php if( Configure::read( 'Cg.departement' ) == 93 ):?>
		<ul class="actionMenu">
			<li><?php
				echo $this->Xhtml->printLinkJs(
					'Imprimer l\'écran',
					array( 'onclick' => 'printit(); return false;', 'class' => 'noprint' )
				);
			?></li>
		</ul>
		<?php else:?>
			<h1>&nbsp;</h1> 
	<?php endif;?>
	
	<table  id="ficheDossier">
		<tbody>
			<tr>
				<td>
					<h1>Dossier RSA <?php echo h( $details['Dossier']['numdemrsa'] );?></h1>
					<table>
						<tbody>
							<tr class="odd">
								<th>Numéro de dossier</th>
								<td><?php echo h( $details['Dossier']['numdemrsa'] );?></td>
							</tr>
							<tr class="even">
								<th>Date de demande</th>
								<td><?php echo h( date_short( $details['Dossier']['dtdemrsa'] ) );?></td>
							</tr>
							<tr class="odd">
								<th>État du dossier</th>
								<td><?php echo h( value( $etatdosrsa, Set::extract( 'Situationdossierrsa.etatdosrsa', $details ) ) );?></td>
							</tr>
							<tr class="even">
								<th>Service instructeur</th>
								<td><?php echo h( value( $typeserins, Set::extract( 'Suiviinstruction.typeserins', $details ) ) );?></td>
							</tr>
							<tr class="odd">
								<th>Statut du demandeur</th>
								<td><?php echo value( $statudemrsa, Set::extract( 'Dossier.statudemrsa', $details ) );?></td>
							</tr>
							<?php if( Configure::read( 'Cg.departement' ) == 58 ):?>
								<tr class="even">
									<th>Numéro allocataire</th>
									<td><?php echo Set::extract( 'Dossier.matricule', $details );;?></td>
								</tr>
							<?php endif;?>
						</tbody>
					</table>
				</td>
				<td>
					<h2>Orientation</h2>
					<table>
					<?php
						$rowCnt = 0;
						echo thead( 10 );
					?>
						<tbody>
							<?php if( Configure::read( 'Cg.departement' ) == 93 ):?>
							<tr class="<?php echo ( ( $rowCnt++ ) % 2 ? 'even' : 'odd' )?>">
								<th><?php echo __d( 'orientstruct', 'Orientstruct.origine' ) ?></th>
								<td><?php echo value( $options['Orientstruct']['origine'], Set::classicExtract( $details, 'DEM.Orientstruct.derniere.Orientstruct.origine' ) );?></td>
								<td><?php echo value( $options['Orientstruct']['origine'], Set::classicExtract( $details, 'CJT.Orientstruct.derniere.Orientstruct.origine' ) );?></td>
							</tr>
							<?php endif;?>
							<?php if( Configure::read( 'Cg.departement' ) == 66 ):?>
								<tr class="<?php echo ( ( $rowCnt++ ) % 2 ? 'even' : 'odd' )?>">
									<th>Orientation en cours...</th>
									<?php foreach( array( 'DEM', 'CJT' ) as $rolepers ):?>
									<td><?php
										$nonoriente66 = Set::extract( "{$rolepers}.Nonoriente66.derniere", $details );
										$orientation = Set::extract( "{$rolepers}.Orientstruct.derniere", $details );
										if( empty( $orientation ) && !empty( $nonoriente66 ) ) {
											echo '<p class="error">Orientation en cours: Traitement DPS</p>';
										}
									?></td>
									<?php endforeach;?>
								</tr>
							<?php endif;?>
							<tr class="<?php echo ( ( $rowCnt++ ) % 2 ? 'even' : 'odd' )?>">
								<th>Type d'orientation</th>
								<td><?php echo Set::classicExtract( $details, 'DEM.Orientstruct.derniere.Typeorient.lib_type_orient' );?></td>
								<td><?php echo Set::classicExtract( $details, 'CJT.Orientstruct.derniere.Typeorient.lib_type_orient' );?></td>
							</tr>
							<tr class="<?php echo ( ( $rowCnt++ ) % 2 ? 'even' : 'odd' )?>">
								<th>Structure référente<!--Type de structure--></th>
								<td><?php echo Set::classicExtract( $details, 'DEM.Orientstruct.derniere.Structurereferente.lib_struc' );?></td>
								<td><?php echo Set::classicExtract( $details, 'CJT.Orientstruct.derniere.Structurereferente.lib_struc' );?></td>
							</tr>
							<tr class="<?php echo ( ( $rowCnt++ ) % 2 ? 'even' : 'odd' )?>">
								<th>Date de l'orientation</th>
								<td><?php echo date_short( Set::classicExtract( $details, 'DEM.Orientstruct.derniere.Orientstruct.date_valid' ) );?></td>
								<td><?php echo date_short( Set::classicExtract( $details, 'CJT.Orientstruct.derniere.Orientstruct.date_valid' ) );?></td>
							</tr>
							<tr class="<?php echo ( ( $rowCnt++ ) % 2 ? 'even' : 'odd' )?>">
								<th>Statut de l'orientation</th>
								<td><?php echo Set::classicExtract( $details, 'DEM.Orientstruct.derniere.Orientstruct.statut_orient' );?></td>
								<td><?php echo Set::classicExtract( $details, 'CJT.Orientstruct.derniere.Orientstruct.statut_orient' );?></td>
							</tr>
							<tr class="<?php echo ( ( $rowCnt++ ) % 2 ? 'even' : 'odd' )?>">
								<th>Rang de l'orientation</th>
								<td><?php echo Set::classicExtract( $details, 'DEM.Orientstruct.derniere.Orientstruct.rgorient' );?></td>
								<td><?php echo Set::classicExtract( $details, 'CJT.Orientstruct.derniere.Orientstruct.rgorient' );?></td>
							</tr>
							<tr class="<?php echo ( ( $rowCnt++ ) % 2 ? 'even' : 'odd' )?>">
								<th>Référent en cours</th>
								<?php foreach( array( 'DEM', 'CJT' ) as $rolepers ):?>
								<td><?php
									$referent = Set::extract( "{$rolepers}.Referent", $details );
									if( empty( $referent ) ) {
										echo 'Aucun référent actif';
									}
									else {
										echo implode( ' ', array( Set::classicExtract( $referent, 'qual' ), Set::classicExtract( $referent, 'nom' ), Set::classicExtract( $referent, 'prenom' ) ) );
									}
								?></td>
								<?php endforeach;?>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<h2>Personnes</h2>
					<table>
						<?php echo thead( 10 );?>
						<tbody>
							<tr class="even">
								<th><?php echo __d( 'personne', 'Personne.qual' );?></th>
								<td><?php echo value( $qual,  Set::extract( 'DEM.Personne.qual', $details ) );?></td>
								<td><?php echo value( $qual,  Set::extract( 'CJT.Personne.qual', $details ) );?></td>
							</tr>
							<tr class="odd">
								<th><?php echo __d( 'personne', 'Personne.nom' );?></th>
								<td><?php echo Set::extract( 'DEM.Personne.nom', $details );?></td>
								<td><?php echo Set::extract( 'CJT.Personne.nom', $details );?></td>
							</tr>
							<tr class="even">
								<th><?php echo __d( 'personne', 'Personne.prenom' );?></th>
								<td><?php echo Set::extract( 'DEM.Personne.prenom', $details );?></td>
								<td><?php echo Set::extract( 'CJT.Personne.prenom', $details );?></td>
							</tr>
							<tr class="odd">
								<th><?php echo __d( 'personne', 'Personne.dtnai' );?></th>
								<td><?php echo date_short( Set::extract( 'DEM.Personne.dtnai', $details ) );?></td>
								<td><?php echo date_short( Set::extract( 'CJT.Personne.dtnai', $details ) );?></td>
							</tr>
							<tr class="even">
								<th><?php echo __d( 'foyer', 'Foyer.sitfam' );?></th>
								<td colspan="2"><?php echo ( isset( $sitfam[$details['Foyer']['sitfam']] ) ?  $sitfam[$details['Foyer']['sitfam']] : null );?></td>
							</tr>
							<tr class="odd">
								<th><?php echo __( 'adresse' );?></th>
								<td colspan="2">
									<?php echo $details['Adresse']['numvoie'].' '.( isset( $typevoie[$details['Adresse']['typevoie']] ) ? $typevoie[$details['Adresse']['typevoie']] : null ).' '. $details['Adresse']['nomvoie'];?>
								</td>
							</tr>
							<tr class="even">
								<th><?php echo __d( 'adresse', 'Adresse.locaadr' );?></th>
								<td colspan="2"><?php echo ( isset( $details['Adresse']['locaadr'] ) ? $details['Adresse']['locaadr'] : null );?></td>
							</tr>
							<tr class="odd">
								<th>Soumis à droits et devoirs</th>
								<td><?php echo textToppersdrodevorsa( Set::extract( 'DEM.Calculdroitrsa', $details ) );?></td>
								<td><?php echo textToppersdrodevorsa( Set::extract( 'CJT.Calculdroitrsa', $details ) );?></td>
							</tr>
							<tr class="even">
								<th>DSP</th>
								<td><?php echo h( textPresenceDsp( @$details['DEM'] ) );?></td>
								<td><?php echo h( textPresenceDsp( @$details['CJT'] ) );?></td>
							</tr>
							<?php if( Configure::read( 'Cg.departement' ) == 58 ):?>
								<tr class="odd">
									<th>Code activité</th>
									<td><?php echo value( $act,  Set::extract( 'DEM.Activite.act', $details ) );?></td>
									<td><?php echo value( $act,  Set::extract( 'CJT.Activite.act', $details ) );?></td>
								</tr>
								<?php if( Configure::read( 'Cg.departement' ) == 58 ):?>
									<tr class="even">
										<th colspan="3" class="center">Grossesse</th>

									</tr>
									<tr class="odd">
										<th>Date de début</th>
										<td><?php echo date_short( Set::extract( 'DEM.Grossesse.ddgro', $details ) );?></td>
										<td><?php echo date_short( Set::extract( 'CJT.Grossesse.ddgro', $details ) );?></td>
									</tr>
									<tr class="even">
										<th>Date de fin</th>
										<td><?php echo date_short( Set::extract( 'DEM.Grossesse.dfgro', $details ) );?></td>
										<td><?php echo date_short( Set::extract( 'CJT.Grossesse.dfgro', $details ) );?></td>
									</tr>
									<tr class="odd">
										<th>Date de déclaration</th>
										<td><?php echo date_short( Set::extract( 'DEM.Grossesse.dtdeclgro', $details ) );?></td>
										<td><?php echo date_short( Set::extract( 'CJT.Grossesse.dtdeclgro', $details ) );?></td>
									</tr>
									<tr class="even">
										<th>Motif de fin</th>
										<td><?php echo value( $natfingro, Set::extract( 'DEM.Grossesse.natfingro', $details ) );?></td>
										<td><?php echo value( $natfingro, Set::extract( 'CJT.Grossesse.natfingro', $details ) );?></td>
									</tr>
								<?php endif;?>
							<?php endif;?>
						</tbody>
					</table>
				</td>
				<td>
					<h2>Contrat d'Engagement Réciproque</h2>
					<table>
					<?php echo thead( 10 );?>
						<tbody>
							<tr class="even">
								<th>Type de contrat</th>
								<td><?php echo Set::enum( Set::classicExtract( $details, 'DEM.Contratinsertion.num_contrat' ), $numcontrat['num_contrat'] );?></td>
								<td><?php echo Set::enum( Set::classicExtract( $details, 'CJT.Contratinsertion.num_contrat' ), $numcontrat['num_contrat'] );?></td>
							</tr>
							<tr class="odd">
								<th>Date de début</th>
								<td><?php echo date_short( Set::extract( 'DEM.Contratinsertion.dd_ci', $details) );?></td>
								<td><?php echo date_short( Set::extract( 'CJT.Contratinsertion.dd_ci', $details) );?></td>
							</tr>
							<tr class="even">
								<th>Date de fin</th>
								<td><?php echo date_short( Set::extract( 'DEM.Contratinsertion.df_ci', $details) );?></td>
								<td><?php echo date_short( Set::extract( 'CJT.Contratinsertion.df_ci', $details) );?></td>
							</tr>
							<tr class="odd">
								<th>Décision</th>
								<td>
									<?php if(  Set::extract( 'DEM.Contratinsertion', $details) != null ):?>
										<?php echo ( !empty( $deciD )  ) ? $decision_ci[$deciD] : $decision_ci[''] ;?>
									<?php endif;?>
								</td>
								<td>
									<?php if( Set::extract( 'CJT.Contratinsertion', $details) != null ):?>
										<?php echo ( !empty( $deciC )  ) ? $decision_ci[$deciC] : $decision_ci[''] ;?>
									<?php endif;?>
								</td>
							</tr>
							<tr class="even">
								<th>Date de décision</th>
								<td><?php echo date_short( Set::extract( 'DEM.Contratinsertion.datevalidation_ci', $details) );?></td>
								<td><?php echo date_short( Set::extract( 'CJT.Contratinsertion.datevalidation_ci', $details) );?></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<h2>Informations CAF / MSA</h2>
					<table >
						<tbody>
							<?php if( Configure::read( 'Cg.departement' ) != 58 ):?>
								<tr class="even">
									<th>Numéro CAF / MSA</th>
									<td><?php echo Set::extract( 'Dossier.matricule', $details );;?></td>
								</tr>
							<?php endif;?>
							<tr class="odd">
								<th>Date d'ouverture des droits</th>
								<td><?php echo h( date_short( Set::extract( 'Dossier.dtdemrsa', $details ) ) );?></td>
							</tr>
							<tr class="even">
								<th>Date de fin de droits</th>
								<td><?php echo h( date_short( Set::extract( 'Situationdossierrsa.dtclorsa', $details ) ) );?></td>
							</tr>
							<tr class="odd">
								<th>Motif de fin de droits</th>
								<td><?php echo h( value( $moticlorsa, Set::extract( 'Situationdossierrsa.moticlorsa', $details ) ) );?></td>
							</tr>
							<tr class="even">
								<th>Numéro de demande RSA</th>
								<td><?php echo Set::extract( 'Dossier.numdemrsa', $details );?></td>
							</tr>
							<tr class="even">
								<th>Montant RSA</th>
								<td><?php echo $this->Locale->money( Set::extract( 'Detailcalculdroitrsa.0.mtrsavers', $details ) ); ?></td>
							</tr>
							<tr class="odd">
								<th>Date dernier montant</th>
								<td><?php echo date_short( Set::extract( 'Detailcalculdroitrsa.0.dtderrsavers', $details ) );?></td>
							</tr>
							<tr class="even">
								<th>Motif</th>
								<td><?php echo value( $natpf, Set::extract( 'Detailcalculdroitrsa.0.natpf', $details ) );?></td>
							</tr>
							<tr class="odd">
								<th>Montant INDUS</th>
								<td><?php echo $this->Locale->money( Set::extract( 'Infofinanciere.mtmoucompta', $details ) );?></td>
							</tr>
							<tr class="even">
								<th>Motif</th>
								<td><?php echo h( Set::extract( 'Creance.motiindu', $details ) );/*FIXME: traduction, manque dans Option*/?></td>
							</tr>
							<tr class="odd">
								<th>Début du traitement CAF / MSA</th>
								<td><?php echo $this->Locale->date( 'Date::short', Set::extract( 'DEM.Dossiercaf.ddratdos', $details ) );?></td>
							</tr>
							<tr class="even">
								<th>Fin du traitement CAF / MSA</th>
								<td><?php echo h(  date_short( Set::extract( 'DEM.Dossiercaf.dfratdos', $details ) ) );?></td>
							</tr>
						</tbody>
					</table>
				</td>
				<td>
					<h2>Contrat Unique d'Insertion</h2>
					<table>
					<?php echo thead( 10 );?>
						<tbody>
							<tr class="odd">
								<th>Secteur</th>
								<td><?php echo Set::enum( Set::classicExtract( $details, 'DEM.Cui.secteur' ), $enumcui['secteur'] );?></td>
								<td><?php echo Set::enum( Set::classicExtract( $details, 'CJT.Cui.secteur' ), $enumcui['secteur'] );?></td>
							</tr>
							<tr class="even">
								<th>Date du contrat</th>
								<td><?php echo date_short( Set::extract( 'DEM.Cui.datecontrat', $details) );?></td>
								<td><?php echo date_short( Set::extract( 'CJT.Cui.datecontrat', $details) );?></td>
							</tr>
							<tr class="odd">
								<th>Décision</th>
								<td><?php echo Set::enum( Set::classicExtract( $details, 'DEM.Cui.decisioncui' ), $enumcui['decisioncui'] );?></td>
								<td><?php echo Set::enum( Set::classicExtract( $details, 'CJT.Cui.decisioncui' ), $enumcui['decisioncui'] );?></td>
							</tr>
							<tr class="even">
								<th>Date de décision</th>
								<td><?php echo date_short( Set::extract( 'DEM.Cui.datevalidationcui', $details) );?></td>
								<td><?php echo date_short( Set::extract( 'CJT.Cui.datevalidationcui', $details) );?></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<h2>Dernière Information Pôle Emploi</h2>
					<table>
					<?php echo thead( 10 );?>
						<tbody>
							<tr class="even">
								<th>Identifiant pôle-emploi</th>
								<td><?php echo Set::extract( 'DEM.Informationpe.0.identifiantpe', $details);?></td>
								<td><?php echo Set::extract( 'CJT.Informationpe.0.identifiantpe', $details);?></td>
							</tr>
							<tr class="odd">
								<th>Etat actuel Pôle Emploi</th>
								<td><?php echo Set::enum( Set::extract( 'DEM.Informationpe.0.etat', $details ), $etatpe['etat'] );?></td>
								<td><?php echo Set::enum( Set::extract( 'CJT.Informationpe.0.etat', $details ), $etatpe['etat'] );?></td>
							</tr>
							<tr class="even">
								<th>Dernière date</th>
								<td><?php echo date_short( Set::extract( 'DEM.Informationpe.0.date', $details) );?></td>
								<td><?php echo date_short( Set::extract( 'CJT.Informationpe.0.date', $details) );?></td>
							</tr>
							<tr class="odd">
								<th>Code état</th>
								<td><?php echo Set::enum( Set::extract( 'DEM.Informationpe.0.code', $details), $categorie );?></td>
								<td><?php echo Set::enum( Set::extract( 'CJT.Informationpe.0.code', $details), $categorie );?></td>
							</tr>
							<tr class="even">
								<th>Motif</th>
								<td><?php echo Set::extract( 'DEM.Informationpe.0.motif', $details);?></td>
								<td><?php echo Set::extract( 'CJT.Informationpe.0.motif', $details);?></td>
							</tr>
						</tbody>
					</table>
				</td>
				<td>
					<h2>Historique des dossiers RSA</h2>
					<table>
					<?php echo theadPastDossierDEM( 50, 8 );?>
						<tbody>

							<?php
								$nbdem = count( Set::extract( 'DEM.Dossiermultiple', $details ) );
								if( $nbdem == 0 ):
							?>
							<tr class="odd">
								<!-- Partie Demandeur-->
								<th>Autre N° de demande RSA</th>
								<td colspan="2"><?php
										echo 'Aucun dossier passé pour le demandeur';
									?>
								</td>
							</tr>
							<?php else:?>
							<?php for( $iteration = 0; $iteration <= $nbdem-1; $iteration++ ):?>
								<tr class="odd">
									<!-- Partie Demandeur-->
									<th>Autre N° de demande RSA</th>
									<td><?php
											echo Set::extract( 'DEM.Dossiermultiple.'.$iteration.'.Dossier.numdemrsa', $details ).' en date du '.date_short( Set::extract( 'DEM.Dossiermultiple.'.$iteration.'.Dossier.dtdemrsa', $details ) ).' avec un état à '.value( $etatdosrsa, Set::extract( 'DEM.Dossiermultiple.'.$iteration.'.Situationdossierrsa.etatdosrsa', $details ) );
										?>
									</td>
									<td><?php
											echo $this->Xhtml->viewLink(
												'Voir',
												array( 'controller' => 'dossiers', 'action' => 'view', Set::extract( 'DEM.Dossiermultiple.'.$iteration.'.Dossier.id', $details) )
											);
										?>
									</td>
								</tr>
								<?php endfor;?>
								<?php endif;?>
							</tbody>
						</table>
						<table>
						<?php echo theadPastDossierCJT( 50, 8 );?>
						<tbody>
							<?php
								$nbcjt = count( Set::extract( 'CJT.Dossiermultiple', $details ) );
								if( $nbcjt == 0 ):
							?>
							<tr class="odd">
								<!-- Partie Conjoint-->
								<th>Autre N° de demande RSA</th>
								<td colspan="2"><?php
										echo 'Aucun dossier passé pour le conjoint';
									?>
								</td>
							</tr>
							<?php else:?>
							<?php for( $iteration = 0; $iteration <= $nbcjt-1; $iteration++ ):?>
							<tr class="odd">
								<!-- Partie Conjoint-->
								<th>Autre N° de demande RSA</th>
								<td><?php
										echo Set::extract( 'CJT.Dossiermultiple.'.$iteration.'.Dossier.numdemrsa', $details ).' en date du '.date_short( Set::extract( 'CJT.Dossiermultiple.'.$iteration.'.Dossier.dtdemrsa', $details ) ).' avec un état à '.value( $etatdosrsa, Set::extract( 'CJT.Dossiermultiple.'.$iteration.'.Situationdossierrsa.etatdosrsa', $details ) );
									?>
								</td>
								<td><?php
										echo $this->Xhtml->viewLink(
											'Voir',
											array( 'controller' => 'dossiers', 'action' => 'view', Set::extract( 'CJT.Dossiermultiple.'.$iteration.'.Dossier.id', $details) )
										);
									?>
								</td>
							</tr>
							<?php endfor;?>
							<?php endif;?>
						</tbody>
					</table>
				</td>
			</tr>
			<!-- Partie passage en EP-->

			<tr>
				<td>
					<?php if( Configure::read( 'Cg.departement' ) == 93 ):?>
					<h2>Dernière relance</h2>
					<table >
					<?php echo thead( 10 );?>
						<tbody>
							<tr class="even">
								<th>Type de relance</th>
								<td><?php echo Set::enum( Set::classicExtract( $details, 'DEM.Nonrespectsanctionep93.derniere.Nonrespectsanctionep93.origine' ), $relance['origine'] );?></td>
								<td><?php echo Set::enum( Set::classicExtract( $details, 'CJT.Nonrespectsanctionep93.derniere.Nonrespectsanctionep93.origine' ), $relance['origine'] );?></td>
							</tr>
							<tr class="odd">
								<th>Date de relance</th>
								<td><?php echo h( date_short( Set::extract( 'DEM.Nonrespectsanctionep93.derniere.Relancenonrespectsanctionep93.daterelance', $details ) ) );?></td>
								<td><?php echo h( date_short( Set::extract( 'CJT.Nonrespectsanctionep93.derniere.Relancenonrespectsanctionep93.daterelance', $details ) ) );?></td>
							</tr>
							<tr class="even">
								<th>Type de relance</th>
								<td><?php
									$numrelance = Set::extract( 'DEM.Nonrespectsanctionep93.derniere.Relancenonrespectsanctionep93.numrelance', $details );
									if( !empty($numrelance) ){
										if( $numrelance == 1 ) {
											echo '1ère relance';
										}
										else {
											echo "{$numrelance}ème relance";
										}
									}
								?></td>
								<td><?php
									$numrelance = Set::extract( 'CJT.Nonrespectsanctionep93.derniere.Relancenonrespectsanctionep93.numrelance', $details );
									if( !empty($numrelance) ){
										if( $numrelance == 1 ) {
											echo '1ère relance';
										}
										else {
											echo "{$numrelance}ème relance";
										}
									}
								?></td>
							</tr>
						</tbody>
					</table>
				<?php endif;?>
				</td>
				<td>
					<h2>Dernier passage en EP</h2>
					<?php
						$detailsEp = array();
						if( $displayingInfoEp ) {
							foreach( array( 'DEM', 'CJT' ) as $roleEp ) {
								if( isset( $details[$roleEp]['Dossierep']['derniere']['Dossierep'] ) ){
									$detailsEp[$roleEp]['dateEp'] = h( date_short( Set::extract( "{$roleEp}.Dossierep.derniere.Commissionep.dateseance", $details ) ) );
									$themeep = Set::classicExtract( $details, "{$roleEp}.Dossierep.derniere.Dossierep.themeep" );
									$modeleDecision = 'Decision'.Inflector::singularize( $themeep );
									$detailsEp[$roleEp]['themeEp'] = Set::enum( Set::classicExtract( $details, "{$roleEp}.Dossierep.derniere.Dossierep.themeep" ), $dossierep['themeep'] );
									$detailsEp[$roleEp]['decisionEp'] = Set::enum( Set::classicExtract( $details, "{$roleEp}.Dossierep.derniere.{$modeleDecision}.decision" ), $optionsep[$modeleDecision]['decision'] );
									$detailsEp[$roleEp]['etatDossierep'] = Set::enum( Set::classicExtract( $details, "{$roleEp}.Dossierep.derniere.Passagecommissionep.etatdossierep" ), $optionsep['Passagecommissionep']['etatdossierep'] );
								}
							}
						}
					?>
					<table>
					<?php echo thead( 10 );?>
						<tbody>
							<tr class="even">
								<th>Date de la commission d'EP</th>
								<td><?php echo @$detailsEp['DEM']['dateEp'];?></td>
								<td><?php echo @$detailsEp['CJT']['dateEp'];?></td>
							</tr>
							<tr class="odd">
								<th>Motif de passage en EP</th>
								<td><?php echo @$detailsEp['DEM']['themeEp'];?></td>
								<td><?php echo @$detailsEp['CJT']['themeEp'];?></td>
							</tr>
							<tr class="even">
								<th>État dossier EP</th>
								<td><?php echo @$detailsEp['DEM']['etatDossierep'];?></td>
								<td><?php echo @$detailsEp['CJT']['etatDossierep'];?></td>
							</tr>
							<tr class="odd">
								<th>Décision de la commission d'EP</th>
								<td><?php echo @$detailsEp['DEM']['decisionEp'];?></td>
								<td><?php echo @$detailsEp['CJT']['decisionEp'];?></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>

		</tbody>
	</table>
</div>
</div>
<div class="clearer"><hr /></div>