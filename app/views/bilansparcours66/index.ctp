<?php $personne_id = Set::classicExtract( $this->params, 'pass.0' ); ?>

<?php if( empty( $personne_id ) ):?>
	<h1> <?php echo $this->pageTitle = 'Écran de synthèse des bilans de parcours'; ?> </h1>
	<?php
		unset( $options['Bilanparcours66']['saisineepparcours'] );
		echo $default2->index(
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
				if( empty( $nborientstruct ) ) {
					echo '<p class="error">Cette personne ne possède pas d\'orientation. Veuillez en saisir une pour pouvoir poursuivre.</p>';
				}
				else {
					echo "<ul class='actions'><li class='add'>";
						echo $default2->button('add', array('controller'=>'bilansparcours66', 'action'=>'add', $personne_id));
					echo "</li></ul>";

					if( empty( $bilansparcours66 ) ){
						echo '<p class="notice">Aucun bilan de parcours présent pour cette personne.</p>';
					}
					else{

						$pagination = $xpaginator->paginationBlock( 'Bilanparcours66', $this->passedArgs );
						echo $pagination;

						echo "<table><thead><tr>";
							echo "<th>".__d('bilanparcours66', 'Bilanparcours66.datebilan', true)."</th>";
							echo "<th>".__d('bilanparcours66', 'Bilanparcours66.positionbilan', true)."</th>";
							echo "<th>".__d('structurereferente', 'Structurereferente.lib_struc',true)."</th>";
							echo "<th>Nom du référent</th>";
							echo "<th>".__d('bilanparcours66', 'Bilanparcours66.proposition', true)."</th>";
							echo "<th>Motif de la saisine</th>";
							echo "<th colspan='2'>".__d('saisinebilanparcoursep66', 'Saisinebilanparcoursep66.propref', true)."</th>";
							echo "<th colspan='2'>".__d('saisinebilanparcoursep66', 'Saisinebilanparcoursep66.avisep', true)."</th>";
							echo "<th colspan='2'>".__d('saisinebilanparcoursep66', 'Saisinebilanparcoursep66.decisioncg', true)."</th>";
							echo "<th colspan='5'>Actions</th>";
						echo "</tr></thead><tbody>";

						foreach($bilansparcours66 as $bilanparcour66) {
							$positionbilan = Set::classicExtract( $bilanparcour66, 'Bilanparcours66.positionbilan' );
							$block = true;
							if( $positionbilan == 'annule' ){
								$block = false;
							}

							echo "<tr>";
								echo $type2->format( $bilanparcour66, 'Bilanparcours66.datebilan', array( 'type' => 'date', 'tag' => 'td', 'options' => $options ) );
								echo $type2->format( $bilanparcour66, 'Bilanparcours66.positionbilan', array(  'tag' => 'td', 'options' => $options ) );
								echo $type2->format( $bilanparcour66, 'Referent.Structurereferente.lib_struc', array( 'tag' => 'td', 'options' => $options ) );
								echo $type2->format( $bilanparcour66, 'Referent.nom_complet', array( 'type' => 'text', 'tag' => 'td', 'options' => $options ) );
								echo $type2->format( $bilanparcour66, 'Bilanparcours66.proposition', array( 'tag' => 'td', 'options' => $options ) );

								if ( $bilanparcour66['Bilanparcours66']['proposition'] == 'audition' && !empty( $bilanparcour66['Bilanparcours66']['examenaudition'] ) ) {
									echo $type2->format( $bilanparcour66, 'Bilanparcours66.examenaudition', array( 'tag' => 'td', 'options' => $options ) );
								}
								elseif ( $bilanparcour66['Bilanparcours66']['proposition'] == 'auditionpe' && !empty( $bilanparcour66['Bilanparcours66']['examenauditionpe'] ) ) {
									echo $type2->format( $bilanparcour66, 'Bilanparcours66.examenauditionpe', array( 'tag' => 'td', 'options' => $options ) );
								}
								elseif ( $bilanparcour66['Bilanparcours66']['proposition'] == 'parcours' ) {
									if ( $bilanparcour66['Bilanparcours66']['maintienorientation'] == 0)  {
										$bilanparcour66['Bilanparcours66']['choixparcours'] = 'reorientation';
									}
									else {
										$bilanparcour66['Bilanparcours66']['choixparcours'] = 'maintien';
									}
									echo $type2->format( $bilanparcour66, 'Bilanparcours66.choixparcours', array( 'tag' => 'td', 'options' => $options ) );
								}
								else {
									echo $type2->format( $bilanparcour66, 'Bilanparcours66.choixparcours', array( 'tag' => 'td', 'options' => $options ) );
								}

								// FIXME: en cas de plusieurs passages ?
								$thematique = array_values( Set::filter( Set::classicExtract( $bilanparcour66, '{s}.Dossierep.themeep' ) ) );
								$thematique = @$thematique[0];

								if( $thematique == 'saisinesbilansparcourseps66' ) {
									// Proposition du référent
									echo $xhtml->tag(
										'td',
										( !empty( $bilanparcour66['Saisinebilanparcoursep66']['typeorient_id'] ) ) ? Set::classicExtract( $typesorients, Set::classicExtract( $bilanparcour66, 'Saisinebilanparcoursep66.typeorient_id' ) ) : null
									);
									echo $xhtml->tag(
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
												echo $xhtml->tag(
													'td',
													__d( 'decisionsaisinebilanparcoursep66', 'ENUM::DECISION::'.$decision['decision'], true ),
													array(
														'colspan' => 2
													)
												);
											}
											else { // reorientation
												echo $xhtml->tag(
													'td',
													Set::classicExtract( $typesorients, $decision['typeorient_id'] )
												);
												echo $xhtml->tag(
													'td',
													Set::classicExtract( $structuresreferentes, $decision['structurereferente_id'] )
												);
											}
										}
									}
								}
								else if( $thematique == 'defautsinsertionseps66' ) {
									// Proposition du référent
									// FIXME: vide ??
									echo '<td colspan="2"></td>';
									/*echo $xhtml->tag(
										'td',
										Set::classicExtract( $typesorients, Set::classicExtract( $bilanparcour66, 'Saisinebilanparcoursep66.typeorient_id' ) )
									);
									echo $xhtml->tag(
										'td',
										Set::classicExtract( $structuresreferentes, Set::classicExtract( $bilanparcour66, 'Saisinebilanparcoursep66.structurereferente_id' ) )
									);*/

									// Avis de l'EP, décision du CG - FIXME: passage 0 ? voir le tri
									$iDernierpassage = count( $bilanparcour66['Defautinsertionep66']['Dossierep']['Passagecommissionep'] ) - 1;
									foreach( array( 0, 1 ) as $niveauDecision ) {
										if( !isset( $bilanparcour66['Defautinsertionep66']['Dossierep']['Passagecommissionep'][$iDernierpassage]['Decisiondefautinsertionep66'][$niveauDecision] ) ) {
											echo '<td colspan="2"></td>';
										}
										else {
											$decision = $bilanparcour66['Defautinsertionep66']['Dossierep']['Passagecommissionep'][$iDernierpassage]['Decisiondefautinsertionep66'][$niveauDecision];
											if( in_array( $decision['decision'], array( 'suspensionnonrespect', 'suspensiondefaut', 'maintien', 'annule', 'reporte' ) ) ) {
												echo $xhtml->tag(
													'td',
													__d( 'decisiondefautinsertionep66', 'ENUM::DECISION::'.$decision['decision'], true ),
													array(
														'colspan' => 2
													)
												);
											}
											else { // reorientationprofverssoc, reorientationsocversprof
												echo $xhtml->tag(
													'td',
													Set::classicExtract( $typesorients, $decision['typeorient_id'] )
												);
												echo $xhtml->tag(
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
								}

								/*if ( isset( $bilanparcour66['Saisinebilanparcoursep66']['typeorient_id'] ) && !empty( $bilanparcour66['Saisinebilanparcoursep66']['typeorient_id'] ) ) {
									echo $xhtml->tag(
										'td',
										Set::classicExtract( $typesorients, Set::classicExtract( $bilanparcour66, 'Saisinebilanparcoursep66.typeorient_id' ) )
									);
									echo $xhtml->tag(
										'td',
										Set::classicExtract( $structuresreferentes, Set::classicExtract( $bilanparcour66, 'Saisinebilanparcoursep66.structurereferente_id' ) )
									);
									if ( isset( $bilanparcour66['Saisinebilanparcoursep66']['Dossierep']['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['typeorient_id'] ) && !empty( $bilanparcour66['Saisinebilanparcoursep66']['Dossierep']['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0]['typeorient_id'] ) ) {
										echo $xhtml->tag(
											'td',
											Set::classicExtract( $typesorients, Set::classicExtract( $bilanparcour66, 'Saisinebilanparcoursep66.Dossierep.Passagecommissionep.0.Decisionsaisinebilanparcoursep66.0.typeorient_id' ) )
										);
										echo $xhtml->tag(
											'td',
											Set::classicExtract( $structuresreferentes, Set::classicExtract( $bilanparcour66, 'Saisinebilanparcoursep66.Dossierep.Passagecommissionep.0.Decisionsaisinebilanparcoursep66.0.structurereferente_id' ) )
										);
									}
									else {
										echo "<td colspan='2'></td>";
									}
									if ( isset( $bilanparcour66['Saisinebilanparcoursep66']['Dossierep']['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][1]['typeorient_id'] ) && !empty( $bilanparcour66['Saisinebilanparcoursep66']['Dossierep']['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][1]['typeorient_id'] ) ) {
										echo $xhtml->tag(
											'td',
											Set::classicExtract( $typesorients, Set::classicExtract( $bilanparcour66, 'Saisinebilanparcoursep66.Dossierep.Passagecommissionep.0.Decisionsaisinebilanparcoursep66.1.typeorient_id' ) )
										);
										echo $xhtml->tag(
											'td',
											Set::classicExtract( $structuresreferentes, Set::classicExtract( $bilanparcour66, 'Saisinebilanparcoursep66.Dossierep.Passagecommissionep.0.Decisionsaisinebilanparcoursep66.1.structurereferente_id' ) )
										);
									}
									else {
										echo "<td colspan='2'></td>";
									}
								}
								elseif ( isset( $bilanparcour66['Defautinsertionep66']['Dossierep']['Passagecommissionep'][0]['Decisiondefautinsertionep66'][1]['decision'] ) && in_array( $bilanparcour66['Defautinsertionep66']['Dossierep']['Passagecommissionep'][0]['Decisiondefautinsertionep66'][0]['decision'], array( 'suspensionnonrespect', 'suspensiondefaut', 'maintien', 'annule' ) )  ) {
									echo "<td colspan='2'></td>";
									echo $xhtml->tag(
										'td',
										__d( 'decisiondefautinsertionep66', 'ENUM::DECISION::'.$bilanparcour66['Defautinsertionep66']['Dossierep']['Passagecommissionep'][0]['Decisiondefautinsertionep66'][0]['decision'], true ),
										array(
											'colspan' => 2
										)
									);
									if ( isset( $bilanparcour66['Defautinsertionep66']['Dossierep']['Passagecommissionep'][0]['Decisiondefautinsertionep66'][1]['decision'] ) && in_array( $bilanparcour66['Defautinsertionep66']['Dossierep']['Passagecommissionep'][0]['Decisiondefautinsertionep66'][1]['decision'], array( 'suspensionnonrespect', 'suspensiondefaut', 'maintien', 'annule' ) ) ) {
										echo $xhtml->tag(
											'td',
											__d( 'decisiondefautinsertionep66', 'ENUM::DECISION::'.$bilanparcour66['Defautinsertionep66']['Dossierep']['Passagecommissionep'][0]['Decisiondefautinsertionep66'][1]['decision'], true ),
											array(
												'colspan' => 2
											)
										);
									}
									else {
										echo $xhtml->tag(
											'td',
											Set::classicExtract( $typesorients, Set::classicExtract( $bilanparcour66, 'Defautinsertionep66.Dossierep.0.Decisiondefautinsertionep66.1.typeorient_id' ) )
										);
										echo $xhtml->tag(
											'td',
											Set::classicExtract( $structuresreferentes, Set::classicExtract( $bilanparcour66, 'Defautinsertionep66.Dossierep.0.Decisiondefautinsertionep66.1.structurereferente_id' ) )
										);
									}
								}
								elseif ( isset( $bilanparcour66['Defautinsertionep66']['Dossierep']['Passagecommissionep'][0]['Decisiondefautinsertionep66'][0]['decision'] ) && ( $bilanparcour66['Defautinsertionep66']['Dossierep']['Passagecommissionep'][0]['Decisiondefautinsertionep66'][0]['decision'] == 'reorientationprofverssoc' || $bilanparcour66['Defautinsertionep66']['Dossierep']['Passagecommissionep'][0]['Decisiondefautinsertionep66'][0]['decision'] == 'reorientationsocversprof' ) ) {
									echo "<td colspan='2'></td>";
									echo $xhtml->tag(
										'td',
										Set::classicExtract( $typesorients, Set::classicExtract( $bilanparcour66, 'Defautinsertionep66.Dossierep.Passagecommissionep.0.Decisiondefautinsertionep66.0.typeorient_id' ) )
									);
									echo $xhtml->tag(
										'td',
										Set::classicExtract( $structuresreferentes, Set::classicExtract( $bilanparcour66, 'Defautinsertionep66.Dossierep.Passagecommissionep.0.Decisiondefautinsertionep66.0.structurereferente_id' ) )
									);
									if ( isset( $bilanparcour66['Defautinsertionep66']['Dossierep']['Passagecommissionep'][0]['Decisiondefautinsertionep66'][1]['decision'] ) && ( $bilanparcour66['Defautinsertionep66']['Dossierep']['Passagecommissionep'][0]['Decisiondefautinsertionep66'][1]['decision'] == 'suspensionnonrespect' || $bilanparcour66['Defautinsertionep66']['Dossierep']['Passagecommissionep'][0]['Decisiondefautinsertionep66'][1]['decision'] == 'suspensiondefaut' || $bilanparcour66['Defautinsertionep66']['Dossierep']['Passagecommissionep'][0]['Decisiondefautinsertionep66'][1]['decision'] == 'maintien' ) ) {
										echo $xhtml->tag(
											'td',
											__d( 'decisiondefautinsertionep66', 'ENUM::DECISION::'.$bilanparcour66['Defautinsertionep66']['Dossierep']['Passagecommissionep'][0]['Decisiondefautinsertionep66'][1]['decision'], true ),
											array(
												'colspan' => 2
											)
										);
									}
									else {
										echo $xhtml->tag(
											'td',
											Set::classicExtract( $typesorients, Set::classicExtract( $bilanparcour66, 'Defautinsertionep66.Dossierep.Passagecommissionep.0.Decisiondefautinsertionep66.1.typeorient_id' ) )
										);
										echo $xhtml->tag(
											'td',
											Set::classicExtract( $structuresreferentes, Set::classicExtract( $bilanparcour66, 'Defautinsertionep66.Dossierep.Passagecommissionep.0.Decisiondefautinsertionep66.1.structurereferente_id' ) )
										);
									}
								}
								else {
									echo "<td colspan='2'></td>";
									echo "<td colspan='2'></td>";
									echo "<td colspan='2'></td>";
								}*/
								
								echo $html->tag(
									'td',
									$xhtml->editLink( 'Modifier', array( 'controller'=>'bilansparcours66', 'action'=>'edit', Set::classicExtract($bilanparcour66, 'Bilanparcours66.id') ), ( $permissions->check( 'bilansparcours66', 'edit' ) == 1 && $block ) )
								);
								echo $html->tag(
									'td',
									$xhtml->printLink( 'Imprimer', array( 'controller'=>'bilansparcours66', 'action'=>'bilanparcoursGedooo', Set::classicExtract($bilanparcour66, 'Bilanparcours66.id') ), ( $permissions->check( 'bilansparcours66', 'bilanparcoursGedooo' ) == 1 && $block )  )
								);
								echo $html->tag(
									'td',
									$xhtml->cancelLink( 'Annuler ce bilan de parcours', array( 'controller'=>'bilansparcours66', 'action'=>'cancel', Set::classicExtract($bilanparcour66, 'Bilanparcours66.id') ), ( $permissions->check( 'bilansparcours66', 'cancel' ) == 1 && $block ) )
								);
								echo $html->tag(
									'td',
									$xhtml->fileLink( 'Fichiers liés', array( 'controller'=>'bilansparcours66', 'action'=>'filelink', Set::classicExtract($bilanparcour66, 'Bilanparcours66.id') ), ( $permissions->check( 'bilansparcours66', 'filelink' ) == 1 && $block ) )
								);
							echo "</tr>";
						}
						echo "</tbody></table>";
						echo $pagination;
					}
				}
			?>

	</div>
	<div class="clearer"><hr /></div>
<?php endif;?>
