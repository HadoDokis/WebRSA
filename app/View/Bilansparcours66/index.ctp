<?php $personne_id = Set::classicExtract( $this->request->params, 'pass.0' ); ?>

<?php if( empty( $personne_id ) ):?>
	<h1> <?php echo $this->pageTitle = 'Écran de synthèse des bilans de parcours'; ?> </h1>
	<?php
		unset( $options['Bilanparcours66']['saisineepparcours'] );
		echo $this->Default2->index(
			$bilansparcours66,
			array(
				'Bilanparcours66.created' => array( 'type' => 'date' ),
				// Personne
				'Personne.nom_complet' => array( 'type' => 'text' ),
				'Orientstruct.Personne.Foyer.Adressefoyer.0.Adresse.locaadr' => array( 'type' => 'text' ),
				// Orientation
				'Orientstruct.date_valid',
				'Orientstruct.Typeorient.lib_type_orient',
				'Orientstruct.Structurereferente.lib_struc',
				// Contrat d'insertion
				'Contratinsertion.date_saisi_ci',
				'Contratinsertion.Structurereferente.Typeorient.lib_type_orient',
				'Contratinsertion.Structurereferente.lib_struc',
				'Bilanparcours66.saisineepparcours' => array( 'type' => 'boolean' ),
				'Saisinebilanparcoursep66.Dossierep.etapedossierep'
			),
			array(
				'actions' => array(
					'Bilansparcours66::view' => array( 'label' => 'Voir', 'url' => array( 'controller' => 'bilansparcours66', 'action' => 'index', '#Orientstruct.personne_id#' ) )
				),
				'groupColumns' => array(
					'Orientation' => array( 1, 2, 3 ),
					'Contrat d\'insertion' => array( 4, 5, 6 ),
					'Équipe pluridisciplinaire' => array( 7, 8 ),
				),
				'paginate' => 'Bilanparcours66',
				'options' => $options
			)
		);
	?>
<?php else:?>
	<?php
		if( Configure::read( 'nom_form_bilan_cg' ) == 'cg66' ){
			$this->pageTitle = 'Bilan de parcours de la personne';
		}
		else {
			$this->pageTitle = 'Fiche de saisine de la personne';
		}

	?>
	<h1><?php echo $this->pageTitle;?></h1>

		<?php

			echo "<ul class='actions'><li class='add'>";
				echo $this->Default2->button('add', array('controller'=>'bilansparcours66', 'action'=>'add', $personne_id));
			echo "</li></ul>";

			if( empty( $bilansparcours66 ) ){
				echo '<p class="notice">Aucun bilan de parcours présent pour cette personne.</p>';
			}
			else{

// 					$pagination = $this->Xpaginator->paginationBlock( 'Bilanparcours66', $this->passedArgs );
// 					echo $pagination;

				echo '<table class="tooltips default2"  id="searchResults"><thead><tr>';
					echo "<th>".__d( 'bilanparcours66', 'Bilanparcours66.datebilan' )."</th>";
					echo "<th>".__d( 'bilanparcours66', 'Bilanparcours66.positionbilan' )."</th>";
					echo "<th>MSP</th>";
					echo "<th>".__d( 'structurereferente', 'Structurereferente.lib_struc' )."</th>";
					echo "<th>Nom du référent</th>";
					echo "<th>".__d( 'bilanparcours66', 'Bilanparcours66.proposition' )."</th>";
					echo "<th>Motif de la saisine</th>";
					echo "<th colspan='2'>".__d( 'saisinebilanparcoursep66', 'Saisinebilanparcoursep66.propref' )."</th>";
					echo "<th colspan='2'>".__d( 'saisinebilanparcoursep66', 'Saisinebilanparcoursep66.avisep' )."</th>";
					echo "<th colspan='2'>".__d( 'saisinebilanparcoursep66', 'Saisinebilanparcoursep66.decisioncg' )."</th>";
					echo "<th colspan='7'>Actions</th>";
					echo "</tr></thead><tbody>";

				foreach($bilansparcours66 as $index => $bilanparcour66) {

					$innerTable = '<table id="innerTablesearchResults'.$index.'" class="innerTable">
							<tbody>
								<tr>
									<th>Raison annulation</th>
									<td>'.$bilanparcour66['Bilanparcours66']['motifannulation'].'</td>
								</tr>
							</tbody>
						</table>';
// debug( $bilanparcour66 );
					$nbFichiersLies = 0;
					$nbFichiersLies = ( isset( $bilanparcour66['Fichiermodule'] ) ? count( $bilanparcour66['Fichiermodule'] ) : 0 );

					$positionbilan = Set::classicExtract( $bilanparcour66, 'Bilanparcours66.positionbilan' );
					$block = true;
					if( $positionbilan == 'annule' ){
						$block = false;
					}

					// Activation du bouton Manifestaitons uniquement si ep audition
					$epparcours = true;
					$proposition = Set::classicExtract( $bilanparcour66, 'Bilanparcours66.proposition' );
					$datecourrierconvoc = Set::classicExtract( $bilanparcour66, 'Defautinsertionep66.dateimpressionconvoc' );
					if( in_array( $proposition, array( 'audition', 'auditionpe' ) ) && !empty( $datecourrierconvoc ) ){
						$epparcours = false;
					}

					echo "<tr class=\"dynamic\" id=\"innerTableTrigger{$index}\">";
						echo $this->Type2->format( $bilanparcour66, 'Bilanparcours66.datebilan', array( 'type' => 'date', 'tag' => 'td', 'options' => $options ) );
						echo $this->Type2->format( $bilanparcour66, 'Bilanparcours66.positionbilan', array(  'tag' => 'td', 'options' => $options ) );
						echo $this->Type2->format( $bilanparcour66, 'Serviceinstructeur.lib_service', array( 'tag' => 'td', 'options' => $options ) );
						echo $this->Type2->format( $bilanparcour66, 'Structurereferente.lib_struc', array( 'tag' => 'td', 'options' => $options ) );
						echo $this->Type2->format( $bilanparcour66, 'Referent.nom_complet', array( 'type' => 'text', 'tag' => 'td', 'options' => $options ) );
						echo $this->Type2->format( $bilanparcour66, 'Bilanparcours66.proposition', array( 'tag' => 'td', 'options' => $options ) );

						if ( $bilanparcour66['Bilanparcours66']['proposition'] == 'audition' && !empty( $bilanparcour66['Bilanparcours66']['examenaudition'] ) ) {
							echo $this->Type2->format( $bilanparcour66, 'Bilanparcours66.examenaudition', array( 'tag' => 'td', 'options' => $options ) );
						}
						elseif ( $bilanparcour66['Bilanparcours66']['proposition'] == 'auditionpe' && !empty( $bilanparcour66['Bilanparcours66']['examenauditionpe'] ) ) {
							echo $this->Type2->format( $bilanparcour66, 'Bilanparcours66.examenauditionpe', array( 'tag' => 'td', 'options' => $options ) );
						}
						elseif ( $bilanparcour66['Bilanparcours66']['proposition'] == 'parcours' ) {
// 								$bilanparcour66['Bilanparcours66']['choixparcours'] = 'maintien';
							echo $this->Type2->format( $bilanparcour66, 'Bilanparcours66.choixparcours', array( 'tag' => 'td', 'options' => $options ) );
						}
						else {
							echo $this->Type2->format( $bilanparcour66, 'Bilanparcours66.choixparcours', array( 'tag' => 'td', 'options' => $options ) );
						}

						// FIXME: en cas de plusieurs passages ?
						$thematique = array_values( Hash::filter( (array)Set::classicExtract( $bilanparcour66, '{s}.Dossierep.themeep' ) ) );
						$thematique = @$thematique[0];

						if( $thematique == 'saisinesbilansparcourseps66' ) {
							// Proposition du référent
							echo $this->Xhtml->tag(
								'td',
								( !empty( $bilanparcour66['Saisinebilanparcoursep66']['typeorient_id'] ) ) ? Set::enum( Set::classicExtract( $bilanparcour66, 'Saisinebilanparcoursep66.typeorient_id' ), $typesorients ) : null
							);
							echo $this->Xhtml->tag(
								'td',
								( !empty( $bilanparcour66['Saisinebilanparcoursep66']['structurereferente_id'] ) ) ? Set::enum( Set::classicExtract( $bilanparcour66, 'Saisinebilanparcoursep66.structurereferente_id' ), $structuresreferentes ) : null
							);

							// Avis de l'EP, décision du CG - FIXME: passage 0 ? voir le tri
							$iDernierpassage = count( $bilanparcour66['Saisinebilanparcoursep66']['Dossierep']['Passagecommissionep'] ) - 1;
							foreach( array( 0, 1 ) as $niveauDecision ) {
								if( !isset( $bilanparcour66['Saisinebilanparcoursep66']['Dossierep']['Passagecommissionep'][$iDernierpassage]['Decisionsaisinebilanparcoursep66'][$niveauDecision] ) ) {
									echo '<td colspan="2"></td>';
								}
								else {
									$decision = $bilanparcour66['Saisinebilanparcoursep66']['Dossierep']['Passagecommissionep'][$iDernierpassage]['Decisionsaisinebilanparcoursep66'][$niveauDecision];
									
									if( in_array( $decision['decision'], array( 'maintien', 'annule', 'reporte' ) ) ) {
										echo $this->Xhtml->tag(
											'td',
											__d( 'decisionsaisinebilanparcoursep66', 'ENUM::DECISION::'.$decision['decision'], true ),
											array(
												'colspan' => 2
											)
										);
									}
									else { // reorientation
										echo $this->Xhtml->tag(
											'td',
											Set::enum( $decision['typeorient_id'], $typesorients )
										);
										echo $this->Xhtml->tag(
											'td',
											Set::enum( $decision['structurereferente_id'], $structuresreferentes )
										);
									}
								}
							}
						}
						else if( $thematique == 'defautsinsertionseps66' ) {
							// Proposition du référent
							echo '<td colspan="2"></td>';

							// Avis de l'EP, décision du CG - FIXME: passage 0 ? voir le tri
							$iDernierpassage = count( $bilanparcour66['Defautinsertionep66']['Dossierep']['Passagecommissionep'] ) - 1;
							foreach( array( 0, 1 ) as $niveauDecision ) {
								if( !isset( $bilanparcour66['Defautinsertionep66']['Dossierep']['Passagecommissionep'][$iDernierpassage]['Decisiondefautinsertionep66'][$niveauDecision] ) ) {
									echo '<td colspan="2"></td>';
								}
								else {
									$decision = $bilanparcour66['Defautinsertionep66']['Dossierep']['Passagecommissionep'][$iDernierpassage]['Decisiondefautinsertionep66'][$niveauDecision];

									if( isset( $decision['decision'] ) && !empty( $decision['decision'] ) && $decision['etape'] == 'ep' ) {
										if( isset( $decision['decision'] ) && !empty( $decision['decision'] ) && empty( $decision['decisionsup'] ) ) {
											echo $this->Xhtml->tag(
												'td',
												__d( 'decisiondefautinsertionep66', 'ENUM::DECISION::'.$decision['decision'] ),
												array(
													'colspan' => 2
												)
											);

										}
										else if( isset( $decision['decision'] ) && !empty( $decision['decision'] ) && !empty( $decision['decisionsup'] ) ) {
											echo $this->Xhtml->tag(
												'td',
												__d( 'decisiondefautinsertionep66', 'ENUM::DECISION::'.$decision['decisionsup'] ).' - <br />'.__d( 'decisiondefautinsertionep66', 'ENUM::DECISION::'.$decision['decision'] ),
												array(
													'colspan' => 2
												)
											);
										}

									}
									else if( isset( $decision['decision'] ) && !empty( $decision['decision'] ) && $decision['etape'] == 'cg' ) {
										if( in_array( $decision['decision'], array( 'reorientationprofverssoc', 'reorientationsocversprof' ) ) ) {
											echo $this->Xhtml->tag(
												'td',
												__d( 'decisiondefautinsertionep66', 'ENUM::DECISION::'.$decision['decision'] ),
												array(
													'colspan' => 2
												)
											);
										}
										else {
											//TODO: récupérer les décisions émises par le dossier PCG
											echo '<td colspan="2"></td>';
										}

									}
									else { // reorientationprofverssoc, reorientationsocversprof
										echo $this->Xhtml->tag(
											'td',
											Set::enum( $decision['typeorient_id'], $typesorients )
										);
										echo $this->Xhtml->tag(
											'td',
											Set::enum( $decision['structurereferente_id'], $structuresreferentes )
										);
									}
								}
							}
							//debug( $bilanparcour66['Defautinsertionep66'] );
						}
						else { // Sans passage en EP
							echo '<td colspan="2"></td>'; // Proposition du référent
							echo '<td colspan="2"></td>'; // Avis de l'EP
							echo '<td colspan="2"></td>'; // Décision du CG
						}

						echo $this->Xhtml->tag(
							'td',
							$this->Default2->button(
								'view',
								array( 'controller' => 'bilansparcours66', 'action' => 'view',
								Set::classicExtract( $bilanparcour66, 'Bilanparcours66.id' ) ),
								array(
									'enabled' => (
										( $this->Permissions->checkDossier( 'bilansparcours66', 'view', $dossierMenu ) == 1 )
										&& $block
									)
								)
							)
						);
						echo $this->Xhtml->tag(
							'td',
							$this->Default2->button(
								'edit',
								array( 'controller' => 'bilansparcours66', 'action' => 'edit',
								Set::classicExtract( $bilanparcour66, 'Bilanparcours66.id' ) ),
								array(
									'enabled' => (
										( $this->Permissions->checkDossier( 'bilansparcours66', 'edit', $dossierMenu ) == 1 )
										&& $block
									)
								)
							)
						);
						echo $this->Xhtml->tag(
							'td',
							$this->Default2->button(
								'print',
								array( 'controller' => 'bilansparcours66', 'action' => 'impression',
								Set::classicExtract( $bilanparcour66, 'Bilanparcours66.id' ) ),
								array(
									'enabled' => (
										( $this->Permissions->checkDossier( 'bilansparcours66', 'impression', $dossierMenu ) == 1 )
										&& $block
									)
								)
							)
						);
						echo $this->Xhtml->tag(
							'td',
							$this->Default2->button(
								'manifestation',
								array( 'controller' => 'manifestationsbilansparcours66', 'action' => 'index',
								$bilanparcour66['Bilanparcours66']['id'] ),
								array(
									'label' => 'Manifestations',
									'enabled' => (
										( $this->Permissions->checkDossier( 'manifestationsbilansparcours66', 'index', $dossierMenu ) == 1 )
										&& $block
										&& !$epparcours
									)
								)
							)
						);
						echo $this->Xhtml->tag(
							'td',
							$this->Default2->button(
								'cancel',
								array( 'controller' => 'bilansparcours66', 'action' => 'cancel',
								Set::classicExtract( $bilanparcour66, 'Bilanparcours66.id' ) ),
								array(
									'enabled' => (
										( $this->Permissions->checkDossier( 'bilansparcours66', 'cancel', $dossierMenu ) == 1 )
										&& $block
									)
								)
							)
						);
						echo $this->Xhtml->tag(
							'td',
							$this->Default2->button(
								'filelink',
								array( 'controller' => 'bilansparcours66', 'action' => 'filelink',
								Set::classicExtract( $bilanparcour66, 'Bilanparcours66.id' ) ),
								array(
									'enabled' => (
										( $this->Permissions->checkDossier( 'bilansparcours66', 'filelink', $dossierMenu ) == 1 )
									)
								)
							)
						);
						echo $this->Xhtml->tag(
							'td',
							'('.$nbFichiersLies.')'
						);
						echo $this->Xhtml->tag(
							'td',
							$innerTable,
							array( 'class' => 'innerTableCell noprint' )
						);
					echo "</tr>";
				}
				echo "</tbody></table>";
			}
		?>
<?php endif;?>
