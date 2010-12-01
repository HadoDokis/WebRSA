<?php
	/*
	* INFO: Parfois la variable a le nom personne_id, parfois personneId
	* 	On met tout le monde d'accord (en camelcase)
	*/

	if( isset( ${Inflector::variable( 'personne_id' )} ) ) {
		$personne_id = ${Inflector::variable( 'personne_id' )};
	}

	if( isset( ${Inflector::variable( 'foyer_id' )} ) ) {
		$foyer_id = ${Inflector::variable( 'foyer_id' )};
	}

	/*
	* Recherche du dossier à afficher
	*/

	if( isset( $personne_id ) ) {
		$dossier = $this->requestAction( array( 'controller' => 'dossiers', 'action' => 'menu' ), array( 'personne_id' => $personne_id ) );
	}
	else if( isset( $foyer_id ) ) {
		$dossier = $this->requestAction( array( 'controller' => 'dossiers', 'action' => 'menu' ), array( 'foyer_id' => $foyer_id ) );
	}
	else if( isset( $id ) ) {
		$dossier = $this->requestAction( array( 'controller' => 'dossiers', 'action' => 'menu' ), array( 'id' => $id ) );
	}
?>

<div class="treemenu">

		<h2 >
			<?php if( Configure::read( 'UI.menu.large' ) ):?>
			<?php
				echo $xhtml->link(
					$xhtml->image( 'icons/bullet_toggle_plus2.png', array( 'alt' => '', 'title' => 'Étendre le menu ', 'width' => '12px' ) ),
					'#',
					array( 'onclick' => 'treeMenuExpandsAll( \''.Router::url( '/', true ).'\' ); return false;', 'id' => 'treemenuToggleLink' ),
					false,
					false
				);
			?>
			<?php endif;?>

			<?php
				echo $xhtml->link( 'Dossier RSA '.$dossier['Dossier']['numdemrsa'], array( 'controller' => 'dossiers', 'action' => 'view', $dossier['Dossier']['id'] ) ).( $dossier['Dossier']['locked'] ? $xhtml->image( 'icons/lock.png', array( 'alt' => '', 'title' => 'Dossier verrouillé' ) ) : null );
			?>
		</h2>

<?php $etatdosrsaValue = Set::classicExtract( $dossier, 'Situationdossierrsa.etatdosrsa' );?>

<?php
	if( isset( $personne_id ) ) {
		$personneDossier = Set::extract( $dossier, '/Foyer/Personne' );
		foreach( $personneDossier as $i => $personne ) {
			if( $personne_id == Set::classicExtract( $personne, 'Personne.id' ) ) {
				$personneDossier = Set::classicExtract( $personne, 'Personne.qual' ).' '.Set::classicExtract( $personne, 'Personne.nom' ).' '.Set::classicExtract( $personne, 'Personne.prenom' );
			}
		}

		if( Configure::read( 'UI.menu.lienDemandeur' ) ) {
			echo $xhtml->tag(
				'p',
				$xhtml->link( h( $personneDossier ), sprintf( Configure::read( 'UI.menu.lienDemandeur' ), $dossier['Dossier']['matricule'] ), array(  'class' => 'external' ) ),
				array( 'class' => 'etatDossier' ),
				false,
				false
			);
		}
		else {
			echo $xhtml->tag( 'p', $personneDossier, array( 'class' => 'etatDossier' ) );
		}
	}
?>


<p class="etatDossier"> <?php echo ( isset( $etatdosrsa[$etatdosrsaValue] ) ? $etatdosrsa[$etatdosrsaValue] : 'Non défini' );?> </p>

	<ul>
		<li><?php echo $xhtml->link( 'Composition du foyer', array( 'controller' => 'personnes', 'action' => 'index', $dossier['Foyer']['id'] ) );?>
			<ul>
				<?php foreach( $dossier['Foyer']['Personne'] as $personne ):?>
					<li><?php
							echo $xhtml->link(
								h( implode( ' ', array( $personne['qual'], $personne['nom'], $personne['prenom'] ) ) ),
								array( 'controller' => 'personnes', 'action' => 'view', $personne['id'] )
							);
						?>
							<!-- Début "Partie du sous-menu concernant uniquement le demandeur et son conjoint" -->
							<?php if( $personne['Prestation']['rolepers'] == 'DEM' || $personne['Prestation']['rolepers'] == 'CJT' ):?>
								<ul>
								<?php if( $permissions->check( 'situationsdossiersrsa', 'index' ) || $permissions->check( 'detailsdroitsrsa', 'index' ) ):?>
									<li><span>Droit</span>
										<ul>
											<?php if( $permissions->check( 'dsps', 'view' ) ):?>
												<li>
													<?php
														echo $xhtml->link(
															'DSP d\'origine',
															array( 'controller' => 'dsps', 'action' => 'view', $personne['id'] )
														);?>
												</li>
											<?php endif;?>
											<?php if( $permissions->check( 'dsps', 'histo' ) ):?>
												<li>
													<?php
														echo $xhtml->link(
															'DSPs CG',
															array( 'controller' => 'dsps', 'action' => 'histo', $personne['id'] )
														);?>
												</li>
											<?php endif;?>
											<!--<?php if( $permissions->check( 'dspps', 'view' ) ):?>
												<li>
													<?php
														echo $xhtml->link(
															h( 'DSP CAF' ),
															array( 'controller' => 'dspps', 'action' => 'view', $personne['id'] )
														);?>
												</li>
											<?php endif;?>-->

											<?php if (Configure::read( 'nom_form_ci_cg' ) == 'cg58' ) { ?>

												<?php if( $permissions->check( 'propospdos', 'index' ) ):?>
													<li>
														<?php
															echo $xhtml->link(
																'Consultation dossier PDO',
																array( 'controller' => 'propospdos', 'action' => 'index', $personne['id'] )
															);
														?>
													</li>
												<?php endif;?>
												<?php if( $permissions->check( 'orientsstructs', 'index' ) ):?>
													<li>
														<?php
															echo $xhtml->link(
																h( 'Orientation' ),
																array( 'controller' => 'orientsstructs', 'action' => 'index', $personne['id'] )
															);
														?>
													</li>
												<?php endif;?>

											<?php } else { ?>

												<?php if( $permissions->check( 'orientsstructs', 'index' ) ):?>
													<li>
														<?php
															echo $xhtml->link(
																h( 'Orientation' ),
																array( 'controller' => 'orientsstructs', 'action' => 'index', $personne['id'] )
															);
														?>
													</li>
												<?php endif;?>
												<?php if( $permissions->check( 'propospdos', 'index' ) ):?>
													<li>
														<?php
															echo $xhtml->link(
																'Consultation dossier PDO',
																array( 'controller' => 'propospdos', 'action' => 'index', $personne['id'] )
															);
														?>
													</li>
												<?php endif;?>

											<?php } ?>

										</ul>
									</li>
								<?php endif;?>


								<?php if( $permissions->check( 'personnes_referents', 'index' ) || $permissions->check( 'rendezvous', 'index' ) || $permissions->check( 'contratsinsertion', 'index' ) || $permissions->check( 'cuis', 'index' ) ):?>
									<li><span>Accompagnement du parcours</span>
										<ul>
											<li>
												<?php
													echo $xhtml->link(
														h( 'Chronologie parcours' ),
														'#'
//                                                         array( 'controller' => '#', 'action' => '#', $personne['id'] )
													);
												?>
											</li>
										<?php if( $permissions->check( 'personnes_referents', 'index' ) ):?>
											<li>
												<?php
													echo $xhtml->link(
														h( 'Référent du parcours' ),
														array( 'controller' => 'personnes_referents', 'action' => 'index', $personne['id'] )
													);
												?>
											</li>
										<?php endif;?>
										<?php if( $permissions->check( 'rendezvous', 'index' ) ):?>
											<li>
												<?php
													echo $xhtml->link(
														h( 'Gestion RDV' ),
														array( 'controller' => 'rendezvous', 'action' => 'index', $personne['id'] )
													);
												?>
											</li>
										<?php endif;?>
											<li><span>Contrats</span>
												<ul>
												<?php if( $permissions->check( 'contratsinsertion', 'index' ) ):?>
													<li>
														<?php
															echo $xhtml->link(
																'CER',
																array( 'controller' => 'contratsinsertion', 'action' => 'index', $personne['id'] )
															);
														?>
													</li>
												<?php endif;?>
												<?php if( $permissions->check( 'cuis', 'index' ) ):?>
													<li>
														<?php
															echo $xhtml->link(
																'CUI',
																array( 'controller' => 'cuis', 'action' => 'index', $personne['id'] )
															);
														?>
													</li>
												<?php endif;?>
												</ul>
											</li>
											<?php if( $permissions->check( 'entretiens', 'index' ) ):?>
											<li><span>Actualisation suivi</span>
												<ul>
													<li>
														<?php
															echo $xhtml->link(
																'Entretiens',
																array( 'controller' => 'entretiens', 'action' => 'index', $personne['id'] )
															);
														?>
													</li>

												</ul>
											</li>
											<?php endif;?>
											<li><span>Offre d'insertion</span>
												<ul>
												<!-- <li>
														<?php
															/*echo $xhtml->link(
																'Recherche action',
																array( 'controller' => 'actionscandidats_personnes', 'action' => 'index', $personne['id'] )
															);*/
														?>
													</li> -->
													<?php if( $permissions->check( 'actionscandidats_personnes', 'index' ) ):?>
													<li>
														<?php
															if( Configure::read( 'ActioncandidatPersonne.suffixe' ) == 'cg93' ){
																echo $xhtml->link(
																	'Fiche de liaison',
																	array( 'controller' => 'actionscandidats_personnes', 'action' => 'index', $personne['id'] )
																);
															}
															else{
																echo $xhtml->link(
																	'Fiche de candidature',
																	array( 'controller' => 'actionscandidats_personnes', 'action' => 'index', $personne['id'] )
																);
															}
														?>
													</li>
													<?php endif;?>
												</ul>
											</li>
											<li><span>Aides financières</span>
												<ul>
												<?php if( $permissions->check( 'apres'.Configure::read( 'Apre.suffixe' ), 'index' ) ):?>
													<li>
														<?php
															echo $xhtml->link(
																'Aides / APRE',
																array( 'controller' => 'apres'.Configure::read( 'Apre.suffixe' ), 'action' => 'index', $personne['id'] )
															);
														?>
													</li>
													<?php endif;?>
												</ul>
											</li>
											<li><span>Saisine EP</span>
												<ul>
													<?php if( $permissions->check( 'bilanparcours', 'index' ) ):?>
													<li>
														<?php
															if( Configure::read( 'nom_form_bilan_cg' ) == 'cg93' ){
																echo $xhtml->link(
																	'Fiche de saisine',
																	array( 'controller' => 'bilanparcours', 'action' => 'index', $personne['id'] )
																);
															}
															else{
																echo $xhtml->link(
																	'Bilan du parcours',
																	array( 'controller' => 'bilanparcours', 'action' => 'index', $personne['id'] )
																);
															}
														?>
													</li>
													<?php endif;?>
												</ul>
											</li>
											<li><span>Documents scannés</span>
												<ul>
													<li>
														<?php
															echo $xhtml->link(
																'Courriers',
																'#'
//                                                                 array( 'controller' => 'apres'.Configure::read( 'Apre.suffixe' ), 'action' => 'index', $personne['id'] )
															);
														?>
													</li>
												</ul>
											</li>
											<?php if( $permissions->check( 'memos', 'index' ) ):?>
											<li>
												<?php
													echo $xhtml->link(
														'Mémos',
														array( 'controller' => 'memos', 'action' => 'index', $personne['id'] )
													);
												?>
											</li>
											<?php endif;?>
										</ul>
									</li>
								<?php endif;?>

								<?php if( $permissions->check( 'ressources', 'index' ) || $permissions->check( 'indus', 'index' ) ):?>
									<li><span>Situation financière</span>
										<ul>
											<li>
												<?php
													echo $xhtml->link(
														'Ressources',
														array( 'controller' => 'ressources', 'action' => 'index', $personne['id'] )
													);
												?>
											</li>
										</ul>
									</li>
								<?php endif;?>

							</ul>
							<?php endif;?>
							<!-- Fin "Partie du sous-menu concernant uniquement le demandeur et son conjoint" -->

							<!-- Début "Partie du sous-menu concernant toutes les personnes du foyer" -->
							<!--<?php if( $permissions->check( 'dossierscaf', 'view' ) ):?>
								<li>
									<?php
										echo $xhtml->link(
											h( 'Dossier CAF' ),
											array( 'controller' => 'dossierscaf', 'action' => 'view', $personne['id'] )
										);
									?>
								</li>
							<?php endif;?>-->
							<!-- Fin "Partie du sous-menu concernant toutes les personnes du foyer" -->
					</li>
				<?php endforeach;?>
			</ul>
		</li>
		<!-- TODO: permissions à partir d'ici et dans les fichiers concernés -->
		<li><span>Informations foyer</span>
			<ul>
				<?php if( $permissions->check( 'situationsdossiersrsa', 'index' ) || $permissions->check( 'detailsdroitsrsa', 'index' ) || $permissions->check( 'dossierspdo', 'index' ) ):?>
					<li>
						<?php
							echo $xhtml->link(
								'Historique du droit',
								array( 'controller' => 'situationsdossiersrsa', 'action' => 'index', $dossier['Dossier']['id'] )
							);
						?>
					</li>
					<li>
						<?php
							echo $xhtml->link(
								'Détails du droit RSA',
								array( 'controller' => 'detailsdroitsrsa', 'action' => 'index', $dossier['Dossier']['id'] )
							);
						?>
					</li>
				<?php endif;?>
				<?php if( $permissions->check( 'adressesfoyers', 'index' ) ):?>
					<li><?php echo $xhtml->link( 'Adresses', array( 'controller' => 'adressesfoyers', 'action' => 'index', $dossier['Foyer']['id'] ) );?>
						<?php if( !empty( $dossier['Foyer']['AdressesFoyer'] ) ):?>
							<?php if( $permissions->check( 'adressesfoyers', 'view' ) ):?>
								<ul>
									<?php foreach( $dossier['Foyer']['AdressesFoyer'] as $AdressesFoyer ):?>
										<li><?php echo $xhtml->link(
												h( implode( ' ', array( $AdressesFoyer['Adresse']['numvoie'], isset( $typevoie[$AdressesFoyer['Adresse']['typevoie']] ) ? $typevoie[$AdressesFoyer['Adresse']['typevoie']] : null, $AdressesFoyer['Adresse']['nomvoie'] ) ) ),
												array( 'controller' => 'adressesfoyers', 'action' => 'view', $AdressesFoyer['id'] ) );
											;?></li>
									<?php endforeach;?>
								</ul>
							<?php endif;?>
						<?php endif;?>
					</li>
				<?php endif;?>

				<?php /*if( $permissions->check( 'foyers_evenements', 'index' ) ):*/?>
					<li>
						<?php
							echo $xhtml->link(
								'Evènements',
								array( 'controller' => 'evenements', 'action' => 'index', $dossier['Foyer']['id'] )
							);
						?>
					</li>
				<?php /* endif;*/?>

				<?php if( $permissions->check( 'modescontact', 'index' ) ):?>
					<li>
						<?php
							echo $xhtml->link(
								'Modes de contact',
								array( 'controller' => 'modescontact', 'action' => 'index', $dossier['Foyer']['id'] )
							);
						?>
					</li>
				<?php endif;?>

				<?php if( $permissions->check( 'avispcgdroitrsa', 'index' ) ):?>
					<li>
						<?php
							echo $xhtml->link(
								'Avis PCG droit rsa',
								array( 'controller' => 'avispcgdroitrsa', 'action' => 'index', $dossier['Dossier']['id'] )
							);
						?>
					</li>
				<?php endif;?>

				<?php if( $permissions->check( 'infosfinancieres', 'index' ) ):?>
					<li>
						<?php
							echo $xhtml->link(
								'Informations financières',
								array( 'controller' => 'infosfinancieres', 'action' => 'index', $dossier['Dossier']['id'] )
							);
						?>
					</li>
				<?php endif;?>
				<?php if( $permissions->check( 'indus', 'index' ) ):?>
					<li>
						<?php
							echo $xhtml->link(
								'Liste des Indus',
								array( 'controller' => 'indus', 'action' => 'index', $dossier['Dossier']['id'] )
							);
						?>
					</li>
				<?php endif;?>
			<?php if( $permissions->check( 'suivisinstruction', 'index' ) ):?>
					<li>
						<?php
							echo $xhtml->link(
								'Suivi instruction du dossier',
								array( 'controller' => 'suivisinstruction', 'action' => 'index', $dossier['Dossier']['id'] )
							);
						?>
					</li>
				<?php endif;?>
			</ul>
		</li>

		<!--<?php if( $permissions->check( 'dspfs', 'edit' ) ):?>
			<?php
				echo '<li>'.$xhtml->link(
					'DSP CAF',
					array( 'controller' => 'dspfs', 'action' => 'view', $dossier['Foyer']['id'] )
				).'</li>';
			?>
		<?php endif;?>-->

		<?php if( $permissions->check( 'infoscomplementaires', 'view' ) ):?>
			<?php
				echo '<li>'.$xhtml->link(
					'Informations complémentaires',
					array( 'controller' => 'infoscomplementaires', 'action' => 'view', $dossier['Dossier']['id'] )
				).'</li>';
			?>
		<?php endif;?>

		<?php if( $permissions->check( 'suivisinsertion', 'index' ) ):?>
			<?php
				echo '<li>'.$xhtml->link(
					'Synthèse du parcours d\'insertion',
					array( 'controller' => 'suivisinsertion', 'action' => 'index', $dossier['Dossier']['id'] )
				).'</li>';
			?>
		<?php endif;?>

		<?php if( $permissions->check( 'dossierssimplifies', 'edit' ) ):?>
			<li><span>Préconisation d'orientation</span>
				<ul>
					<?php if( !empty( $dossier['Foyer']['Personne'] ) ):?>
						<li>
							<?php foreach( $dossier['Foyer']['Personne'] as $personnes ):?>
								<?php if( $personnes['Prestation']['rolepers'] == 'DEM' || $personnes['Prestation']['rolepers'] == 'CJT' ):?>
									<?php
										echo $xhtml->link(
											$personnes['qual'].' '.$personnes['nom'].' '.$personnes['prenom'],
											array( 'controller' => 'dossierssimplifies', 'action' => 'edit', $personnes['id'] )
										);
									?>
								<?php endif ?>
							<?php endforeach?>
						</li>
					<?php endif?>
				</ul>
			</li>
		<?php endif;?>
	</ul>
</div>