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
	<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id) );?>

	<div class="with_treemenu">
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

					echo '<table class="tooltips"  id="searchResults"><thead><tr>';
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
						echo "<th colspan='5'>Actions</th>";
						echo "</tr></thead><tbody>";

					foreach($bilansparcours66 as $bilanparcour66) {

						$innerTable = '<table id="innerTablesearchResults'.$index.'" class="innerTable">
								<tbody>
									<tr>
										<th>Raison annulation</th>
										<td>'.$bilanparcour66['Bilanparcours66']['motifannulation'].'</td>
									</tr>
								</tbody>
							</table>';
							
						$nbFichiersLies = 0;
						$nbFichiersLies = ( isset( $bilanparcour66['Fichiermodule'] ) ? count( $bilanparcour66['Fichiermodule'] ) : 0 );

						$positionbilan = Set::classicExtract( $bilanparcour66, 'Bilanparcours66.positionbilan' );
						$block = true;
						if( $positionbilan == 'annule' ){
							$block = false;
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
								$bilanparcour66['Bilanparcours66']['choixparcours'] = 'maintien';
								echo $this->Type2->format( $bilanparcour66, 'Bilanparcours66.choixparcours', array( 'tag' => 'td', 'options' => $options ) );
							}
							else {
								echo $this->Type2->format( $bilanparcour66, 'Bilanparcours66.choixparcours', array( 'tag' => 'td', 'options' => $options ) );
							}

							// FIXME: en cas de plusieurs passages ?
							$thematique = array_values( Set::filter( Set::classicExtract( $bilanparcour66, '{s}.Dossierep.themeep' ) ) );
							$thematique = @$thematique[0];

							if( $thematique == 'saisinesbilansparcourseps66' ) {
								// Proposition du référent
								echo $this->Xhtml->tag(
									'td',
									( !empty( $bilanparcour66['Saisinebilanparcoursep66']['typeorient_id'] ) ) ? Set::classicExtract( $typesorients, Set::classicExtract( $bilanparcour66, 'Saisinebilanparcoursep66.typeorient_id' ) ) : null
								);
								echo $this->Xhtml->tag(
									'td',
									( !empty( $bilanparcour66['Saisinebilanparcoursep66']['structurereferente_id'] ) ) ? Set::classicExtract( $structuresreferentes, Set::classicExtract( $bilanparcour66, 'Saisinebilanparcoursep66.structurereferente_id' ) ) : null
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
												__d( 'decisionsaisinebilanparcoursep66', 'ENUM::DECISION::'.$decision['decision'] ),
												array(
													'colspan' => 2
												)
											);
										}
										else { // reorientation
											echo $this->Xhtml->tag(
												'td',
												Set::classicExtract( $typesorients, $decision['typeorient_id'] )
											);
											echo $this->Xhtml->tag(
												'td',
												Set::classicExtract( $structuresreferentes, $decision['structurereferente_id'] )
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

										if( in_array( $decision['decision'], array( 'suspensionnonrespect', 'suspensiondefaut', 'maintien', 'reorientationprofverssoc', 'reorientationsocversprof', 'suspensionnonrespect', 'suspensiondefaut', 'maintien', 'annule', 'reporte' ) ) && empty( $decision['decisionsup'] ) ) {
											echo $this->Xhtml->tag(
												'td',
												__d( 'decisiondefautinsertionep66', 'ENUM::DECISION::'.$decision['decision'] ),
												array(
													'colspan' => 2
												)
											);

										}
										else if( in_array( $decision['decision'], array( 'suspensionnonrespect', 'suspensiondefaut', 'maintien', 'reorientationprofverssoc', 'reorientationsocversprof', 'suspensionnonrespect', 'suspensiondefaut', 'maintien', 'annule', 'reporte' ) ) && !empty( $decision['decisionsup'] ) ) {
											echo $this->Xhtml->tag(
												'td',
												__d( 'decisiondefautinsertionep66', 'ENUM::DECISION::'.$decision['decisionsup'], true ).' - <br />'.__d( 'decisiondefautinsertionep66', 'ENUM::DECISION::'.$decision['decision'] ),
												array(
													'colspan' => 2
												)
											);

										}
										else { // reorientationprofverssoc, reorientationsocversprof
											echo $this->Xhtml->tag(
												'td',
												Set::classicExtract( $typesorients, $decision['typeorient_id'] )
											);
											echo $this->Xhtml->tag(
												'td',
												Set::classicExtract( $structuresreferentes, $decision['structurereferente_id'] )
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
								$this->Xhtml->editLink( 'Modifier', array( 'controller'=>'bilansparcours66', 'action'=>'edit', Set::classicExtract($bilanparcour66, 'Bilanparcours66.id') ), ( $this->Permissions->check( 'bilansparcours66', 'edit' ) == 1 && $block ) )
							);
							echo $this->Xhtml->tag(
								'td',
								$this->Xhtml->printLink( 'Imprimer', array( 'controller'=>'bilansparcours66', 'action'=>'impression', Set::classicExtract($bilanparcour66, 'Bilanparcours66.id') ), ( $this->Permissions->check( 'bilansparcours66', 'impression' ) == 1 && $block )  )
							);
							echo $this->Xhtml->tag(
								'td',
								$this->Xhtml->cancelLink( 'Annuler ce bilan de parcours', array( 'controller'=>'bilansparcours66', 'action'=>'cancel', Set::classicExtract($bilanparcour66, 'Bilanparcours66.id') ), ( $this->Permissions->check( 'bilansparcours66', 'cancel' ) == 1 && $block ) )
							);
							echo $this->Xhtml->tag(
								'td',
								$this->Xhtml->fileLink( 'Fichiers liés', array( 'controller'=>'bilansparcours66', 'action'=>'filelink', Set::classicExtract($bilanparcour66, 'Bilanparcours66.id') ), ( $this->Permissions->check( 'bilansparcours66', 'filelink' ) == 1 && $block ) )
							);
							echo $this->Xhtml->tag(
								'td',
								'('.$nbFichiersLies.')'
							);
							echo $html->tag(
								'td',
								$innerTable,
								array( 'class' => 'innerTableCell noprint' ) 
							);
						echo "</tr>";
					}
					echo "</tbody></table>";
				}
			?>

	</div>
	<div class="clearer"><hr /></div>
<?php endif;?>
