<div id="menu1Wrapper">
	<div class="menu1">
		<ul>
		<?php if( $this->Session->check( 'Auth.User' ) ): ?>
			<?php if(
					$this->Permissions->check( 'cohortes', 'nouvelles' )
					|| $this->Permissions->check( 'cohortes', 'orientees' )
					|| $this->Permissions->check( 'cohortes', 'enattente' )
					|| $this->Permissions->check( 'cohortesvalidationapres66', 'apresavalider' )
					|| $this->Permissions->check( 'cohortesvalidationapres66', 'notifiees' )
					|| $this->Permissions->check( 'cohortesvalidationapres66', 'validees' )
					|| $this->Permissions->check( 'cohortesvalidationapres66', 'traitement' )
					|| $this->Permissions->check( 'cohortesci', 'nouveaux' )
					|| $this->Permissions->check( 'cohortesci', 'valides' )
					|| $this->Permissions->check( 'cohortesci', 'nouveauxsimple' )
					|| $this->Permissions->check( 'cohortesci', 'nouveauxparticulier' )
					|| $this->Permissions->check( 'cohortesci', 'nouveauxparticulier' )
					|| $this->Permissions->check( 'cohortesfichescandidature66', 'fichesenattente' )
					|| $this->Permissions->check( 'cohortesfichescandidature66', 'fichesencours' )
					|| $this->Permissions->check( 'cohortesdossierspcgs66', 'enattenteaffectation' )
					|| $this->Permissions->check( 'cohortesdossierspcgs66', 'affectes' )
					|| $this->Permissions->check( 'cohortesdossierspcgs66', 'aimprimer' )
					|| $this->Permissions->check( 'cohortesdossierspcgs66', 'atransmettre' )
					|| $this->Permissions->check( 'cohortesnonorientes66', 'isemploi' )
					|| $this->Permissions->check( 'cohortesnonorientes66', 'notisemploi' )
					|| $this->Permissions->check( 'cohortesnonorientes66', 'notisemploiaimprimer' )
					|| $this->Permissions->check( 'cohortesnonorientes66', 'oriente' )
					|| $this->Permissions->check( 'cohortespdos', 'avisdemande' )
					|| $this->Permissions->check( 'cohortespdos', 'valide' )
					|| $this->Permissions->check( 'cohortespdos', 'enattente' )
					|| $this->Permissions->check( 'relancesnonrespectssanctionseps93', 'cohorte' )
					|| $this->Permissions->check( 'relancesnonrespectssanctionseps93', 'impressions' )
					|| $this->Permissions->check( 'nonorientationsproseps', 'index' )
					|| $this->Permissions->check( 'nonorientationsproseps', 'selectionradies' )
				):?>
				<li id="menu1one" onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
					<?php
						if( Configure::read( 'Cg.departement' ) == 66 ) {
							echo $this->Xhtml->link( 'Gestion de listes', '#' );
						}
						else {
							echo $this->Xhtml->link( 'Cohortes', '#' );
						}
					?>
					<ul>
						<?php if( ( Configure::read( 'Cg.departement' ) == 66 ) && ( $this->Permissions->check( 'cohortesvalidationapres66', 'apresavalider' ) || $this->Permissions->check( 'cohortesvalidationapres66', 'notifiees' ) || $this->Permissions->check( 'cohortesvalidationapres66', 'transfert' ) || $this->Permissions->check( 'cohortesvalidationapres66', 'validees' ) || $this->Permissions->check( 'cohortesvalidationapres66', 'traitement' ) ) ):?>
							<!-- AJOUT POUR LA GESTION DES CONTRATS D'ENGAGEMENT RECIPROQUE (Cohorte) -->
							<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
								<?php  echo $this->Xhtml->link( 'APRE ', '#' );?>
									<ul>
										<?php if( $this->Permissions->check( 'cohortesvalidationapres66', 'apresavalider' ) ): ?>
											<li><?php echo $this->Xhtml->link( 'À valider', array( 'controller' => 'cohortesvalidationapres66', 'action' => 'apresavalider' ), array( 'title' => 'À valider' ) );?></li>
										<?php endif; ?>
										<?php if( $this->Permissions->check( 'cohortesvalidationapres66', 'validees' ) ): ?>
											<li><?php echo $this->Xhtml->link( 'À notifier', array( 'controller' => 'cohortesvalidationapres66', 'action' => 'validees' ), array( 'title' => 'À notifier' ) );?></li>
										<?php endif; ?>
										<?php if( $this->Permissions->check( 'cohortesvalidationapres66', 'notifiees' ) ): ?>
											<li><?php echo $this->Xhtml->link( 'Notifiées', array( 'controller' => 'cohortesvalidationapres66', 'action' => 'notifiees' ), array( 'title' => 'Notifiées' ) );?></li>
										<?php endif; ?>
										<?php if( $this->Permissions->check( 'cohortesvalidationapres66', 'transfert' ) ): ?>
											<li><?php echo $this->Xhtml->link( 'Transfert cellule', array( 'controller' => 'cohortesvalidationapres66', 'action' => 'transfert' ), array( 'title' => 'Transfert cellule' ) );?></li>
										<?php endif; ?>
										<?php if( $this->Permissions->check( 'cohortesvalidationapres66', 'traitement' ) ): ?>
											<li><?php echo $this->Xhtml->link( 'Traitement cellule', array( 'controller' => 'cohortesvalidationapres66', 'action' => 'traitement' ), array( 'title' => 'Traitement cellule' ) );?></li>
										<?php endif; ?>
									</ul>
							</li>
						<?php endif;?>
						<?php if( in_array( Configure::read( 'Cg.departement' ), array( 66, 93 ) ) && ( $this->Permissions->check( 'cohortesci', 'nouveaux' ) || $this->Permissions->check( 'cohortesci', 'valides' ) || $this->Permissions->check( 'cohortesci', 'nouveauxsimple' ) || $this->Permissions->check( 'cohortesci', 'nouveauxparticulier' ) ) ) :?>
							<!-- AJOUT POUR LA GESTION DES CONTRATS D'ENGAGEMENT RECIPROQUE (Cohorte) -->
							<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
								<?php  echo $this->Xhtml->link( 'CER ', '#' );?>
									<ul>
										<?php if( Configure::read( 'Cg.departement' ) == 66 ):?>
											<?php if( $this->Permissions->check( 'cohortesci', 'nouveauxsimple' ) ): ?>
												<li><?php echo $this->Xhtml->link( 'Contrats Simples à valider', array( 'controller' => 'cohortesci', 'action' => 'nouveauxsimple' ), array( 'title' => 'Contrats Simples à valider' ) );?></li>
											<?php endif; ?>
											<?php if( $this->Permissions->check( 'cohortesci', 'nouveauxparticulier' ) ): ?>
												<li><?php echo $this->Xhtml->link( 'Contrats Particuliers à valider', array( 'controller' => 'cohortesci', 'action' => 'nouveauxparticulier' ), array( 'title' => 'Contrats Particuliers à valider' ) );?></li>
											<?php endif; ?>
											<?php if( $this->Permissions->check( 'cohortesci', 'valides' ) ): ?>
												<li><?php echo $this->Xhtml->link( 'Décisions prises', array( 'controller' => 'cohortesci', 'action' => 'valides' ), array( 'title' => 'Décisions prises' ) );?></li>
											<?php endif; ?>
										<?php elseif( Configure::read( 'Cg.departement' ) == 93 ):?>
											<?php if( $this->Permissions->check( 'cohortesci', 'nouveaux' ) ): ?>
												<li><?php echo $this->Xhtml->link( 'Contrats à valider', array( 'controller' => 'cohortesci', 'action' => 'nouveaux' ), array( 'title' => 'Contrats à valider' ) );?></li>
											<?php endif; ?>
											<?php if( $this->Permissions->check( 'cohortesci', 'valides' ) ): ?>
												<li><?php echo $this->Xhtml->link( 'Contrats validés', array( 'controller' => 'cohortesci', 'action' => 'valides' ), array( 'title' => 'Contrats validés' ) );?></li>
											<?php endif; ?>
										<?php endif; ?>
									</ul>
							</li>
						<?php endif;?>
						<?php if( ( Configure::read( 'Cg.departement' ) == 66 ) && ( $this->Permissions->check( 'cohortesfichescandidature66', 'fichesenattente' ) || $this->Permissions->check( 'cohortesfichescandidature66', 'fichesencours' ) ) ):?>
							<!-- AJOUT POUR LA GESTION DES Fiches de candidature 66 (Cohorte) -->
							<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
								<?php  echo $this->Xhtml->link( 'Fiches de candidature ', '#' );?>
									<ul>
										<?php if( $this->Permissions->check( 'cohortesfichescandidature66', 'fichesenattente' ) ): ?>
											<li><?php echo $this->Xhtml->link( 'Fiches en attente', array( 'controller' => 'cohortesfichescandidature66', 'action' => 'fichesenattente' ), array( 'title' => 'Fiches en attente' ) );?></li>
										<?php endif; ?>
										<?php if( $this->Permissions->check( 'cohortesfichescandidature66', 'fichesencours' ) ): ?>
											<li><?php echo $this->Xhtml->link( 'Fiches en cours', array( 'controller' => 'cohortesfichescandidature66', 'action' => 'fichesencours' ), array( 'title' => 'Fiches en cours' ) );?></li>
										<?php endif; ?>
									</ul>
							</li>
						<?php endif;?>
						<?php if( ( Configure::read( 'Cg.departement' ) == 66 ) && ( $this->Permissions->check( 'cohortesdossierspcgs66', 'enattenteaffectation' ) || $this->Permissions->check( 'cohortesdossierspcgs66', 'affectes' ) || $this->Permissions->check( 'cohortesdossierspcgs66', 'aimprimer' ) || $this->Permissions->check( 'cohortesdossierspcgs66', 'atransmettre' ) ) ):?>
                            <!-- AJOUT POUR LA GESTION DES Fiches de candidature 66 (Cohorte) -->
                            <li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
                                <?php  echo $this->Xhtml->link( 'Dossiers PCGs ', '#' );?>
                                    <ul>
                                        <?php if( $this->Permissions->check( 'cohortesdossierspcgs66', 'enattenteaffectation' ) ): ?>
                                            <li>
												<?php echo $this->Xhtml->link( 'Dossiers en attente d\'affectation', array( 'controller' => 'cohortesdossierspcgs66', 'action' => 'enattenteaffectation' ), array( 'title' => 'Dossiers en attente d\'affectation' ) );?>
											</li>
                                        <?php endif; ?>
                                        <?php if( $this->Permissions->check( 'cohortesdossierspcgs66', 'affectes' ) ): ?>
                                            <li>
												<?php echo $this->Xhtml->link( 'Dossiers affectés', array( 'controller' => 'cohortesdossierspcgs66', 'action' => 'affectes' ), array( 'title' => 'Dossiers affectés' ) );?>
											</li>
                                        <?php endif; ?>
                                        <?php if( $this->Permissions->check( 'cohortesdossierspcgs66', 'aimprimer' ) ): ?>
                                            <li>
												<?php echo $this->Xhtml->link( 'Dossiers à imprimer', array( 'controller' => 'cohortesdossierspcgs66', 'action' => 'aimprimer' ), array( 'title' => 'Dossiers à imprimer' ) );?>
											</li>
                                        <?php endif; ?>
                                        <?php if( $this->Permissions->check( 'cohortesdossierspcgs66', 'atransmettre' ) ): ?>
                                            <li>
												<?php echo $this->Xhtml->link( 'Dossiers à transmettre', array( 'controller' => 'cohortesdossierspcgs66', 'action' => 'atransmettre' ), array( 'title' => 'Dossiers à transmettre' ) );?>
											</li>
                                        <?php endif; ?>
                                    </ul>
                            </li>
                        <?php endif;?>
                        <?php if( ( Configure::read( 'Cg.departement' ) == 66 ) && ( $this->Permissions->check( 'cohortesnonorientes66', 'isemploi' ) || $this->Permissions->check( 'cohortesnonorientes66', 'notisemploi' ) || $this->Permissions->check( 'cohortesnonorientes66', 'notisemploiaimprimer' ) || $this->Permissions->check( 'cohortesnonorientes66', 'oriente' ) ) ):?>
                            <!-- AJOUT POUR LA GESTION DES Non orientés 66 (Cohorte) -->
                            <li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
                                <?php  echo $this->Xhtml->link( 'Non orientation ', '#' );?>
                                    <ul>
                                        <?php if( $this->Permissions->check( 'cohortesnonorientes66', 'isemploi' ) ): ?>
                                            <li>
												<?php echo $this->Xhtml->link( 'Inscrits PE', array( 'controller' => 'cohortesnonorientes66', 'action' => 'isemploi' ), array( 'title' => 'Inscrits PE' ) );?>
											</li>
                                        <?php endif; ?>
                                        <?php if( $this->Permissions->check( 'cohortesnonorientes66', 'notisemploiaimprimer' ) ): ?>
                                            <li>
												<?php echo $this->Xhtml->link( 'Non inscrits PE', array( 'controller' => 'cohortesnonorientes66', 'action' => 'notisemploiaimprimer' ), array( 'title' => 'Non inscrits PE' ) );?>
											</li>
                                        <?php endif; ?>
                                        <?php if( $this->Permissions->check( 'cohortesnonorientes66', 'notisemploi' ) ): ?>
                                            <li>
												<?php echo $this->Xhtml->link( 'Gestion des réponses', array( 'controller' => 'cohortesnonorientes66', 'action' => 'notisemploi' ), array( 'title' => 'Gestion des réponses' ) );?>
											</li>
                                        <?php endif; ?>
                                        <?php if( $this->Permissions->check( 'cohortesnonorientes66', 'notifaenvoyer' ) ): ?>
                                            <li>
												<?php echo $this->Xhtml->link( 'Notifications à envoyer', array( 'controller' => 'cohortesnonorientes66', 'action' => 'notifaenvoyer' ), array( 'title' => 'Notifications à envoyer' ) );?>
											</li>
                                        <?php endif; ?>
                                        <?php if( $this->Permissions->check( 'cohortesnonorientes66', 'oriente' ) ): ?>
                                            <li>
												<?php echo $this->Xhtml->link( 'Orientés et notifiés', array( 'controller' => 'cohortesnonorientes66', 'action' => 'oriente' ), array( 'title' => 'Orientés et notifiés' ) );?>
											</li>
                                        <?php endif; ?>
                                    </ul>
                            </li>
                        <?php endif;?>
						<?php if( $this->Permissions->check( 'cohortes', 'nouvelles' ) || $this->Permissions->check( 'cohortes', 'orientees' ) || $this->Permissions->check( 'cohortes', 'enattente' ) /*|| $this->Permissions->check( 'cohortes', 'preconisationscalculables' )|| $this->Permissions->check( 'cohortes', 'preconisationsnoncalculables' ) || $this->Permissions->check( 'cohortes', 'statistiques' )*/ ): ?>
							<!-- MODIF POUR LA GESTION DES ORIENTATIONS (Cohorte) -->
							<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
								<?php  echo $this->Xhtml->link( 'Orientation', '#' );?>
									<ul>
										<?php /*if( $this->Permissions->check( 'cohortes', 'statistiques' ) ): ?>
											<li><?php echo $this->Xhtml->link( 'Statistiques', array( 'controller' => 'cohortes', 'action' => 'statistiques' ), array( 'title'=>'Statistiques' ) );?></li>
										<?php endif; */ ?>
										<?php if( $this->Permissions->check( 'cohortes', 'nouvelles' ) ): ?>
											<li><?php echo $this->Xhtml->link( 'Demandes non orientées', array( 'controller' => 'cohortes', 'action' => 'nouvelles' ), array( 'title'=>'Demandes non orientées' ) );?></li>
										<?php endif; ?>
										<?php if( $this->Permissions->check( 'cohortes', 'enattente' ) ): ?>
											<li><?php echo $this->Xhtml->link( 'Demandes en attente de validation d\'orientation', array( 'controller' => 'cohortes', 'action' => 'enattente' ), array( 'title'=>'Demandes en attente de validation d\'orientation' ) );?></li>
										<?php endif; ?>
										<?php /*if( $this->Permissions->check( 'cohortes', 'preconisationscalculables' ) ): ?>
											<li><?php echo $this->Xhtml->link( 'Demandes d\'orientation préorientées', array( 'controller' => 'cohortes', 'action' => 'preconisationscalculables' ), array( 'title'=>'Demandes à orienter, possédant une préconisation' ) );?></li>
										<?php endif; ?>
										<?php if( $this->Permissions->check( 'cohortes', 'preconisationsnoncalculables' ) ): ?>
											<li><?php echo $this->Xhtml->link( 'Demandes d\'orientation non préorientées', array( 'controller' => 'cohortes', 'action' => 'preconisationsnoncalculables' ), array( 'title'=>'Demandes à orienter, ne possédant pas de préconisation' ) );?></li>
										<?php endif; */?>
										<?php if( $this->Permissions->check( 'cohortes', 'orientees' ) ): ?>
											<li><?php echo $this->Xhtml->link( 'Demandes orientées', array( 'controller' => 'cohortes', 'action' => 'orientees' ), array( 'title'=>'Demandes orientées' ) );?></li>
										<?php endif; ?>
									</ul>
							</li>
						<?php endif;?>
						<?php if( ( $this->Permissions->check( 'cohortespdos', 'avisdemande' ) || $this->Permissions->check( 'cohortespdos', 'valide' ) || $this->Permissions->check( 'cohortespdos', 'enattente' ) ) && Configure::read( 'Cg.departement' ) == 93 ): ?>
							<!-- AJOUT POUR LA GESTION DES PDOs (Cohorte) -->
							<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
								<?php echo $this->Xhtml->link( 'PDOs', '#' );?>
								<ul>
									<?php if( $this->Permissions->check( 'cohortespdos', 'avisdemande' ) ): ?>
										<li><?php echo $this->Xhtml->link( 'Nouvelles demandes', array( 'controller' => 'cohortespdos', 'action' => 'avisdemande' ), array( 'title' => 'Avis CG demandé' ) );?></li>
									<?php endif; ?>
									<?php if( $this->Permissions->check( 'cohortespdos', 'valide' ) ): ?>
										<li><?php echo $this->Xhtml->link( 'Liste PDOs', array( 'controller' => 'cohortespdos', 'action' => 'valide' ), array( 'title' => 'PDOs validés' ) );?></li>
									<?php endif; ?>
								</ul>
							</li>
						<?php endif;?>
						<?php if( ( $this->Permissions->check( 'relancesnonrespectssanctionseps93', 'cohorte' ) || $this->Permissions->check( 'relancesnonrespectssanctionseps93', 'impressions' ) || $this->Permissions->check( 'nonorientationsproseps', 'index' ) || $this->Permissions->check( 'nonrespectssanctionseps93', 'selectionradies' ) ) && Configure::read( 'Cg.departement' ) == 93 ): ?>
                            <!-- AJOUT POUR LA GESTION DES PDOs (Cohorte) -->
                            <li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
                                <?php echo $this->Xhtml->link( 'EPs', '#' );?>
                                <ul>
								<?php if( $this->Permissions->check( 'relancesnonrespectssanctionseps93', 'cohorte' ) || $this->Permissions->check( 'relancesnonrespectssanctionseps93', 'impressions' ) ): ?>
									<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
										<?php echo $this->Xhtml->link( 'Relances (EP)','#' );?>
										<ul>
											<?php if( $this->Permissions->check( 'relancesnonrespectssanctionseps93', 'cohorte' ) ): ?>
												<li><?php echo $this->Xhtml->link( __d( 'relancenonrespectsanctionep93', 'Relancesnonrespectssanctionseps93::cohorte', true ), array( 'controller' => 'relancesnonrespectssanctionseps93', 'action' => 'cohorte' ), array( 'title' => __d( 'relancenonrespectsanctionep93', 'Relancesnonrespectssanctionseps93::cohorte' ) ) );?></li>
											<?php endif;?>
											<?php if( $this->Permissions->check( 'relancesnonrespectssanctionseps93', 'impressions' ) ): ?>
												<li><?php echo $this->Xhtml->link( __d( 'relancenonrespectsanctionep93', 'Relancesnonrespectssanctionseps93::impressions', true ), array( 'controller' => 'relancesnonrespectssanctionseps93', 'action' => 'impressions' ), array( 'title' => __d( 'relancenonrespectsanctionep93', 'Relancesnonrespectssanctionseps93::impressions' ) ) );?></li>
											<?php endif;?>
										</ul>
									</li>
								<?php endif; ?>
                                <?php if( $this->Permissions->check( 'nonorientationsproseps', 'index' ) ): ?>
									<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
										<?php echo $this->Xhtml->link( 'Parcours social sans réorientation', array( 'controller' => 'nonorientationsproseps', 'action' => 'index' ) );?>
									</li>
                                <?php endif; ?>
                                <?php if( $this->Permissions->check( 'nonrespectssanctionseps93', 'selectionradies' ) ): ?>
                                    <li> <?php echo $this->Xhtml->link( 'Radiés de Pôle Emploi',  array( 'controller' => 'nonrespectssanctionseps93', 'action' => 'selectionradies'  ) );?> </li>
                                <?php endif;?>
                                </ul>
                            </li>
                        <?php endif;?>
					</ul>
				</li>
			<?php endif;?>
			<?php if( $this->Permissions->check( 'dossiers', 'index' ) || $this->Permissions->check( 'criteres', 'index' ) || $this->Permissions->check( 'criteresci', 'index' ) ) :?>
				<li id="menu2one" >
					<?php echo $this->Xhtml->link( 'Recherches', '#' );?>
					<ul>
						<?php if( $this->Permissions->check( 'dossiers', 'index' ) ):?>
							<li><?php echo $this->Xhtml->link( 'Par dossier / allocataire', array( 'controller' => 'dossiers', 'action' => 'index' ) );?></li>
						<?php endif;?>
						<?php if( $this->Permissions->check( 'criteres', 'index' ) ):?>
							<li><?php echo $this->Xhtml->link( 'Par Orientation', array( 'controller' => 'criteres', 'action' => 'index' )  );?></li>
						<?php endif;?>
						<?php if( Configure::read( 'Cg.departement' ) == 66 ): ?>
							<?php if( $this->Permissions->check( 'criteresapres', 'index' ) ): ?>
								<li><?php echo $this->Xhtml->link( 'Par APREs', array( 'controller' => 'criteresapres', 'action' => 'all' ) );?></li>
							<?php endif;?>
						<?php endif;?>
						<?php if( $this->Permissions->check( 'criteresci', 'index' ) || $this->Permissions->check( 'criterescuis', 'index' ) ):?>
							<li>
								<?php echo $this->Xhtml->link( 'Par Contrats', '#' );?>
								<ul>
									<li><?php echo $this->Xhtml->link( 'Par CER',  array( 'controller' => 'criteresci', 'action' => 'index'  ) );?></li>
									<li><?php echo $this->Xhtml->link( 'Par CUI',  array( 'controller' => 'criterescuis', 'action' => 'index'  ) );?></li>
								</ul>
							</li>
						<?php endif;?>
						<?php if( $this->Permissions->check( 'criteresentretiens', 'index' ) ): ?>
							<li><?php echo $this->Xhtml->link( 'Par Entretiens', array( 'controller' => 'criteresentretiens', 'action' => 'index' ) );?>
							</li>
						<?php endif;?>

                        <?php if( Configure::read( 'Cg.departement' ) == 66 ): ?>
                            <?php if( $this->Permissions->check( 'criteresfichescandidature', 'index' ) ): ?>
                                <li><?php echo $this->Xhtml->link( 'Par Fiches de candidature', array( 'controller' => 'criteresfichescandidature', 'action' => 'index' ) );?>
                                </li>
                            <?php endif;?>
                        <?php endif;?>

						<?php if( $this->Permissions->check( 'cohortesindus', 'index' ) ): ?>
							<li><?php echo $this->Xhtml->link( 'Par Indus', array( 'controller' => 'cohortesindus', 'action' => 'index' ) );?>
							</li>
						<?php endif;?>

						<?php if( $this->Permissions->check( 'dsps', 'index' ) ): ?>
							<li><?php echo $this->Xhtml->link( 'Par DSPs', array( 'controller' => 'dsps', 'action' => 'index' ) );?>
							</li>
						<?php endif;?>

						<?php if( $this->Permissions->check( 'criteresrdv', 'index' ) ):?>
							<li><?php echo $this->Xhtml->link( 'Par Rendez-vous',  array( 'controller' => 'criteresrdv', 'action' => 'index'  ) );?></li>
						<?php endif;?>
						<?php if( Configure::read( 'Cg.departement' ) == 66 ): ?>
							<?php if( $this->Permissions->check( 'criteresdossierspcgs66', 'dossier' ) || $this->Permissions->check( 'criterestraitementspcgs66', 'index' ) || $this->Permissions->check( 'criteresdossierspcgs66', 'gestionnaire' ) ):?>
								<li>
									<?php echo $this->Xhtml->link( 'Par Dossiers PCGs', '#' );?>
									<ul>
										<?php if( $this->Permissions->check( 'criteresdossierspcgs66', 'dossier' ) ):?>
											<li><?php echo $this->Xhtml->link( 'Dossiers PCGs',  array( 'controller' => 'criteresdossierspcgs66', 'action' => 'dossier'  ) );?></li>
										<?php endif;?>
										<?php if( $this->Permissions->check( 'criterestraitementspcgs66', 'index' ) ):?>
											<li><?php echo $this->Xhtml->link( 'Traitements PCGs',  array( 'controller' => 'criterestraitementspcgs66', 'action' => 'index'  ) );?></li>
										<?php endif;?>
										<?php if( $this->Permissions->check( 'criteresdossierspcgs66', 'gestionnaire' ) ):?>
											<li><?php echo $this->Xhtml->link( 'Gestionnaires PCGs',  array( 'controller' => 'criteresdossierspcgs66', 'action' => 'gestionnaire'  ) );?></li>
										<?php endif;?>
									</ul>
								</li>
							<?php endif;?>
						<?php else:?>
							<?php if( $this->Permissions->check( 'criterespdos', 'index' ) ):?>
								<li>
									<?php echo $this->Xhtml->link( 'Par PDOs', '#' );?>
									<ul>
										<li><?php echo $this->Xhtml->link( 'Nouvelles PDOs',  array( 'controller' => 'criterespdos', 'action' => 'nouvelles'  ) );?></li>
										<li><?php echo $this->Xhtml->link( 'Liste des PDOs',  array( 'controller' => 'criterespdos', 'action' => 'index'  ) );?></li>
									</ul>
								</li>
							<?php endif;?>
						<?php endif;?>
						<?php if( Configure::read( 'Cg.departement' ) == 58 ): ?>
							<?php if( $this->Permissions->check( 'criteresdossierscovs58', 'index' ) ):?>
								<li> <?php echo $this->Xhtml->link( 'Par Dossiers COV', array( 'controller' => 'criteresdossierscovs58', 'action' => 'index'  ) );?> </li>
							<?php endif;?>
							<?php if( $this->Permissions->check( 'sanctionseps58', 'selectionnoninscrits' ) ):?>
								<li>
									<?php echo $this->Xhtml->link( 'Pôle Emploi', '#' );?>
									<ul>
										<li><?php echo $this->Xhtml->link( 'Radiation de Pôle Emploi', array( 'controller' => 'sanctionseps58', 'action' => 'selectionradies' ) );?></li>
										<li><?php echo $this->Xhtml->link( 'Non inscription à Pôle Emploi', array( 'controller' => 'sanctionseps58', 'action' => 'selectionnoninscrits' ) );?></li>
									</ul>
								</li>
							<?php endif;?>
							<?php if( $this->Permissions->check( 'nonorientationsproseps', 'index' ) ):?>
								<li><?php echo $this->Xhtml->link( 'Demande de maintien dans le social', array( 'controller' => 'nonorientationsproseps', 'action' => 'index' ) );?></li>
							<?php endif;?>
						<?php endif;?>
						<?php if( Configure::read( 'Cg.departement' ) == '66' ): ?>
							<?php if( $this->Permissions->check( 'criteresbilansparcours66', 'index' ) ):?>
								<li><?php echo $this->Xhtml->link( 'Par Bilans de parcours',  array( 'controller' => 'criteresbilansparcours66', 'action' => 'index'  ) );?></li>
							<?php endif;?>
							<!-- TODO : à faire !!! -->
							<?php if( $this->Permissions->check( 'defautsinsertionseps66', 'selectionnoninscrits' ) ):?>
								<li>
									<?php echo $this->Xhtml->link( 'Pôle Emploi', '#' );?>
									<ul>
										<li><?php echo $this->Xhtml->link( 'Non inscrits au Pôle Emploi',  array( 'controller' => 'defautsinsertionseps66', 'action' => 'selectionnoninscrits'  ) );?></li>
										<li><?php echo $this->Xhtml->link( 'Radiés de Pôle Emploi',  array( 'controller' => 'defautsinsertionseps66', 'action' => 'selectionradies'  ) );?></li>
									</ul>
								</li>
							<?php endif;?>
							<?php if( $this->Permissions->check( 'nonorientationsproseps', 'index' ) ):?>
								<li><?php echo $this->Xhtml->link( 'Demande de maintien dans le social',  array( 'controller' => 'nonorientationsproseps', 'action' => 'index'  ) );?></li>
							<?php endif;?>
						<?php endif;?>
					</ul>
				</li>
			<?php endif;?>
			<?php if( ( Configure::read( 'Cg.departement' ) == 93 )  && ( $this->Permissions->check( 'criteresapres', 'index' ) || $this->Permissions->check( 'repsddtefp', 'index' ) || $this->Permissions->check( 'comitesapres', 'index' ) || $this->Permissions->check( 'recoursapres', 'index' ) ) ):?>
				<li id="menu3one" >
					<?php echo $this->Xhtml->link( 'APRE', '#' );?>
					<ul>
						<?php if( $this->Permissions->check( 'criteresapres', 'index' ) ):?>
							<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
								<?php echo $this->Xhtml->link( 'Liste des demandes d\'APRE', '#');?>
									<ul>
										<?php if( $this->Permissions->check( 'criteresapres', 'index' ) ): ?>
											<li><?php echo $this->Xhtml->link( 'Toutes les APREs', array( 'controller' => 'criteresapres', 'action' => 'all' ) );?></li>
										<?php endif;?>
										<?php if( $this->Permissions->check( 'criteresapres', 'index' ) && ( Configure::read( 'Cg.departement' ) != 66 ) ): ?>
											<li><?php echo $this->Xhtml->link( 'Eligibilité des APREs', array( 'controller' => 'criteresapres', 'action' => 'eligible' ) );?></li>
										<?php endif;?>
										<?php if( $this->Permissions->check( 'recoursapres', 'index' ) ): ?>
											<li><?php echo $this->Xhtml->link( 'Demande de recours', array( 'controller' => 'recoursapres', 'action' => 'demande' ) );?></li>
										<?php endif;?>
										<?php if( $this->Permissions->check( 'recoursapres', 'index' ) ): ?>
											<li><?php echo $this->Xhtml->link( 'Visualisation des recours', array( 'controller' => 'recoursapres', 'action' => 'visualisation' ) );?></li>
										<?php endif;?>
									</ul>
								</li>
						<?php endif;?>

				<?php if( Configure::read( 'nom_form_apre_cg' ) == 'cg93' ):?> <!-- Début de l'affichage en fonction du CG-->
						<?php if( $this->Permissions->check( 'comitesapres', 'index' ) || $this->Permissions->check( 'cohortescomitesapres', 'index' ) ):?>
							<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
								<?php echo $this->Xhtml->link( 'Comité d\'examen', '#');?>
								<ul>
									<?php if( $this->Permissions->check( 'comitesapres', 'index' ) ): ?>
										<li><?php echo $this->Xhtml->link( 'Recherche de Comité', array( 'controller' => 'comitesapres', 'action' => 'index' ) );?></li>
									<?php endif;?>
									<?php if( $this->Permissions->check( 'cohortescomitesapres', 'index' ) ): ?>
										<li><?php echo $this->Xhtml->link( 'Gestion des décisions Comité', array( 'controller' => 'cohortescomitesapres', 'action' => 'aviscomite' ) );?></li>
									<?php endif;?>
									<?php if( $this->Permissions->check( 'cohortescomitesapres', 'index' ) ): ?>
										<li><?php echo $this->Xhtml->link( 'Notifications décisions Comité', array( 'controller' => 'cohortescomitesapres', 'action' => 'notificationscomite' ) );?></li>
									<?php endif;?>
									<?php if( $this->Permissions->check( 'comitesapres', 'liste' ) ): ?>
										<li><?php echo $this->Xhtml->link( 'Liste des Comités', array( 'controller' => 'comitesapres', 'action' => 'liste' ) );?></li>
									<?php endif;?>
								</ul>
							</li>
						<?php endif;?>
						<?php if( $this->Permissions->check( 'repsddtefp', 'index' ) || $this->Permissions->check( 'repsddtefp', 'suivicontrole' ) ):?>
							<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
								<?php echo $this->Xhtml->link( 'Reporting bi-mensuel', '#' );?>
								<ul>
									<?php if( $this->Permissions->check( 'repsddtefp', 'index' ) ):?>
										<li><?php echo $this->Xhtml->link( 'Reporting bi-mensuel DDTEFP', array( 'controller' => 'repsddtefp', 'action' => 'index' ) );?></li>
									<?php endif;?>
									<?php if( $this->Permissions->check( 'repsddtefp', 'suivicontrole' ) ):?>
										<li><?php echo $this->Xhtml->link( 'Suivi et contrôle de l\'enveloppe APRE', array( 'controller' => 'repsddtefp', 'action' => 'suivicontrole' ) );?></li>
									<?php endif;?>
								</ul>
							</li>
						<?php endif;?>
						<?php if( $this->Permissions->check( 'integrationfichiersapre', 'index' ) ):?>
							<li><?php echo $this->Xhtml->link( 'Journal d\'intégration des fichiers CSV', array( 'controller' => 'integrationfichiersapre', 'action' => 'index' ) );?></li>
						<?php endif;?>
						<?php if( $this->Permissions->check( 'etatsliquidatifs', 'index' ) ):?>
							<li><?php echo $this->Xhtml->link( 'États liquidatifs APRE', array( 'controller' => 'etatsliquidatifs', 'action' => 'index' ) );?></li>
						<?php endif;?>
						<?php if( $this->Permissions->check( 'budgetsapres', 'index' ) ):?>
							<li><?php echo $this->Xhtml->link( 'Budgets APRE', array( 'controller' => 'budgetsapres', 'action' => 'index' ) );?></li>
						<?php endif;?>

				<?php endif;?> <!-- Fin de l'affichage en fonction du CG-->

					</ul>
				</li>
			<?php endif;?>

			<!-- Menu de gestion de la COV pour le cg 58-->
			<?php if( Configure::read( 'Cg.departement' ) == 58 ): ?>
				<?php if( $this->Permissions->check( 'covs58', 'index' ) ): ?>
					<li id="menu8one">
						<?php echo $this->Xhtml->link( 'COV', array( 'controller' => 'covs58', 'action' => 'index' ) ); ?>
					</li>
				<?php endif; ?>
			<?php endif;?>

			<!-- Début du menu des maquettes offre d'insertion-->
			<?php if( Configure::read( 'Cg.departement' ) == 66 ):?>
					<?php if( $this->Permissions->check( 'offresinsertion', 'index' ) ): ?>
					<li id="menu10one">
						<?php echo $this->Xhtml->link( 'Offre d\'Insertion', array( 'controller' => 'offresinsertion', 'action' => 'index' ) ); ?>
					</li>
				<?php endif; ?>
			<?php endif;?>
			<?php if( Configure::read( 'Cg.departement' ) == 34 ):?>
				<li id="menuTest0one" >
					<?php echo $this->Xhtml->link( 'Offre d\'Insertion', '#' );?>
					<ul>
						<li>
							<?php echo $this->Xhtml->link( 'Appels à projet', '#' );?>
							<ul>
								<li><?php echo $this->Xhtml->link( 'Saisie appels à projet', array( 'controller' => 'pages/display/webrsa/', 'action' => 'candidature_appel_a_projet' ) );?></li>
								<li><?php echo $this->Xhtml->link( 'Liste des appels à projet', array( 'controller' => 'pages/display/webrsa/', 'action' => 'liste_appels_a_projet' ) );?></li>
							</ul>
						</li>
						<li>
							<?php echo $this->Xhtml->link( 'Candidatures', '#' );?>
							<ul>
								<li><?php echo $this->Xhtml->link( 'Saisie de candidatures', array( 'controller' => 'pages/display/webrsa/', 'action' => 'saisie_candidature_structure1' ) );?></li>
								<li><?php echo $this->Xhtml->link( 'Liste des candidatures', array( 'controller' => 'pages/display/webrsa/', 'action' => 'suivi_candidats' ) );?></li>
							</ul>
						</li>
						<li>
							<?php echo $this->Xhtml->link( 'Analyses des candidatures', '#' );?>
							<ul>
								<li><?php echo $this->Xhtml->link( 'Par lot', array( 'controller' => 'pages/display/webrsa/', 'action' => 'analyse_candidature' ) );?></li>
								<li><?php echo $this->Xhtml->link( 'Par structures', array( 'controller' => 'pages/display/webrsa/', 'action' => 'suivi_structure_candidate' ) );?></li>
								<li><?php echo $this->Xhtml->link( 'Par actions', array( 'controller' => 'pages/display/webrsa/', 'action' => 'selection_actions' ) );?></li>
							</ul>
						</li>
						<li>
							<?php echo $this->Xhtml->link( 'Administration structure', '#' );?>
							<ul>
								<li><?php echo $this->Xhtml->link( 'Recherche de structures', array( 'controller' => 'pages/display/webrsa/', 'action' => 'recherche_admin_structure' ) );?></li>
								<li><?php echo $this->Xhtml->link( 'Suivi des étapes / pièces', array( 'controller' => 'pages/display/webrsa/', 'action' => 'suivi_etapes_pieces' ) );?></li>
							</ul>
						</li>
						<li>
							<?php echo $this->Xhtml->link( 'Conventions', '#' );?>
							<ul>
								<li><?php echo $this->Xhtml->link( 'Liste des conventions', array( 'controller' => 'pages/display/webrsa/', 'action' => 'liste_convention' ) );?></li>
								<li><?php echo $this->Xhtml->link( 'Création de convention', array( 'controller' => 'pages/display/webrsa/', 'action' => 'create_convention' ) );?></li>
								<li><?php echo $this->Xhtml->link( 'Gestion des conventions', array( 'controller' => 'pages/display/webrsa/', 'action' => 'gestion_convention' ) );?></li>
							</ul>
						</li>
						<li>
							<?php echo $this->Xhtml->link( 'Paiements', '#' );?>
							<ul>
								<li><?php echo $this->Xhtml->link( 'Saisie déclenchements paiement', array( 'controller' => 'pages/display/webrsa/', 'action' => 'gestion_convention' ) );?></li>
								<li><?php echo $this->Xhtml->link( 'Suivi des paiements', array( 'controller' => 'pages/display/webrsa/', 'action' => 'liste_suivi_paiement' ) );?></li>
								<li><?php echo $this->Xhtml->link( 'Demande de remboursement', array( 'controller' => 'pages/display/webrsa/', 'action' => 'demande_remboursement' ) );?></li>
							</ul>
						</li>
						<li>
							<?php echo $this->Xhtml->link( 'Offres', '#' );?>
							<ul>
								<li><?php echo $this->Xhtml->link( 'Recherche d\'offres', array( 'controller' => 'pages/display/webrsa/', 'action' => 'recherche_offre' ) );?></li>
								<li><?php echo $this->Xhtml->link( 'Création d\'offres', array( 'controller' => 'pages/display/webrsa/', 'action' => 'create_offre' ) );?></li>
								<li><?php echo $this->Xhtml->link( 'Gestion des offres', array( 'controller' => 'pages/display/webrsa/', 'action' => 'gestion_offre' ) );?></li>
							</ul>
						</li>
							<li><?php echo $this->Xhtml->link( 'Suivi des stagiaires', array( 'controller' => 'pages/display/webrsa/', 'action' => 'suivi_stagiaires' ) );?></li>
					</ul>
				</li>
			<?php endif;?>
			<!-- Fin du menu des maquettes offre d'insertion-->

			<!-- Début du Nouveau menu pour les Equipes pluridisciplinaires -->

			<?php if( $this->Permissions->check( 'eps', 'liste' ) ) :?>
			<li id="menu4one">
				<?php echo $this->Xhtml->link( 'Eq. Pluri.', '#' );?>
				<ul>
					<li>
						<?php if( Configure::read( 'Cg.departement' ) == 66 ):?>
							<a href="#">1. Gestion des EPs</a>
						<?php else:?>
							<a href="#">1. Mise en place du dispositif</a>
						<?php endif;?>
						<ul>
							<?php if( Configure::read( 'Cg.departement' ) == 66 && $this->Permissions->check( 'defautsinsertionseps66', 'courriersinformations' ) ):?>
								<li><?php echo $this->Xhtml->link( 'Courriers d\'information avant EPL Audition',  array( 'controller' => 'defautsinsertionseps66', 'action' => 'courriersinformations'  ) );?></li>
							<?php endif;?>
							<?php if( $this->Permissions->check( 'membreseps', 'index' ) ):?>
								<li><?php echo $this->Xhtml->link( 'Création des membres', array( 'controller' => 'membreseps', 'action' => 'index' ) );?></li>
							<?php endif;?>
							<?php if( $this->Permissions->check( 'eps', 'index' ) ):?>
								<li><?php echo $this->Xhtml->link( 'Création des EPs', array( 'controller' => 'eps', 'action' => 'index' ) );?></li>
							<?php endif;?>
							<?php if( $this->Permissions->check( 'commissionseps', 'add' ) ):?>
								<li><?php echo $this->Xhtml->link( 'Création des Commissions', array( 'controller' => 'commissionseps', 'action' => 'add' ) );?></li>
							<?php endif;?>
						</ul>
					</li>
					<?php if( $this->Permissions->check( 'commissionseps', 'recherche' ) ):?>
						<li>
							<?php if( Configure::read( 'Cg.departement' ) == 66 ): ?>
								<?php echo $this->Xhtml->link( '2. Recherche de commission', array( 'controller' => 'commissionseps', 'action' => 'recherche' ) );?>
							<?php else:?>
								<?php echo $this->Xhtml->link( '2. Constitution de la commission', array( 'controller' => 'commissionseps', 'action' => 'recherche' ) );?>
							<?php endif;?>
						</li>
					<?php endif;?>
					<?php if( Configure::read( 'Cg.departement' ) == 58 ): ?>
						<li><?php echo $this->Xhtml->link( '3. Arbitrage EP', array( 'controller' => 'commissionseps', 'action' => 'arbitrageep' ) );?></li>
					<?php else: ?>
						<li>
							<?php if( Configure::read( 'Cg.departement' ) == 66 ):?>
								<a href="#">3. Avis/Décisions</a>
							<?php else:?>
								<a href="#">3. Arbitrage</a>
							<?php endif;?>

							<ul>
                                <?php if( Configure::read( 'Cg.departement' ) == 66 ): ?>
                                    <li><?php echo $this->Xhtml->link( 'Avis EP', array( 'controller' => 'commissionseps', 'action' => 'arbitrageep' ) );?></li>
                                    <li><?php echo $this->Xhtml->link( 'Décisions CG', array( 'controller' => 'commissionseps', 'action' => 'arbitragecg' ) );?></li>
                                <?php else: ?>
                                    <li><?php echo $this->Xhtml->link( 'EP', array( 'controller' => 'commissionseps', 'action' => 'arbitrageep' ) );?></li>
                                    <li><?php echo $this->Xhtml->link( 'CG', array( 'controller' => 'commissionseps', 'action' => 'arbitragecg' ) );?></li>
								<?php endif; ?>
							</ul>
						</li>
					<?php endif; ?>
					<li><?php echo $this->Xhtml->link( '4. Consultation et impression des décisions', array( 'controller' => 'commissionseps', 'action' => 'decisions' ) );?></li>

					<?php if( Configure::read( 'Cg.departement' ) == 58 ):?>
						<?php if( $this->Permissions->check( 'gestionssanctionseps58', 'traitement' ) || $this->Permissions->check( 'gestionssanctionseps58', 'visualisation' ) ):?>
						<li>
							<?php echo $this->Xhtml->link( '5. Gestion des sanctions', array( 'controller' => 'gestionssanctionseps58', 'action' => '#' ) );?>
							<ul>
								<?php if( $this->Permissions->check( 'gestionssanctionseps58', 'traitement' ) ):?>
									<li>
										<?php echo $this->Xhtml->link( 'Gestion des sanctions', array( 'controller' => 'gestionssanctionseps58', 'action' => 'traitement' ) );?>
									</li>
								<?php endif;?>
								<?php if( $this->Permissions->check( 'gestionssanctionseps58', 'visualisation' ) ):?>
									<li>
										<?php echo $this->Xhtml->link( 'Visualisation des sanctions', array( 'controller' => 'gestionssanctionseps58', 'action' => 'visualisation' ) );?>
									</li>
								<?php endif;?>
							</ul>
						</li>
						<?php endif;?>
					<?php endif;?>
				</ul>
			</li>
			<?php endif;?>
			<!-- Fin du Nouveau menu pour les Equipes pluridisciplinaires -->

			<!-- Début workflow CER 93 -->
			<?php if( Configure::read( 'Cg.departement' ) == 93 && ( $this->Permissions->check( 'cohortesreferents93', 'affecter' ) || $this->Permissions->check( 'cohortesreferents93', 'affectes' ) ) ) :?>
			<li id="menu4one">
				<?php echo $this->Xhtml->link( 'CER', '#' );?>
				<ul>
					<li>
						<?php echo $this->Xhtml->link( '1. Affectation d\'un référent', '#' );?>
						<ul>
							<li><?php echo $this->Xhtml->link( 'Référents à affecter',  array( 'controller' => 'cohortesreferents93', 'action' => 'affecter'  ) );?></li>
							<li><?php echo $this->Xhtml->link( 'Référents déjà affectés',  array( 'controller' => 'cohortesreferents93', 'action' => 'affectes'  ) );?></li>
						</ul>
					</li>
				</ul>
			</li>
			<?php endif;?>
			<!-- Fin workflow CER 93 -->

			<?php if( $this->Permissions->check( 'indicateursmensuels', 'index' ) || $this->Permissions->check( 'statistiquesministerielles', '#' ) ) :?>
				<li id="menu5one" >
					<?php echo $this->Xhtml->link( 'Tableaux de bord', '#' );?>
					<ul>
						<?php if( $this->Permissions->check( 'indicateursmensuels', 'index' ) ):?>
							<li><?php echo $this->Xhtml->link( 'Indicateurs mensuels', array( 'controller' => 'indicateursmensuels', 'action' => 'index' ) );?></li>
						<?php endif;?>

						<?php if( $this->Permissions->check( 'statistiquesministerielles', 'indicateursOrientations' ) || $this->Permissions->check( 'statistiquesministerielles', 'indicateursOrganismes' ) || $this->Permissions->check( 'statistiquesministerielles', 'indicateursNatureContrats' ) || $this->Permissions->check( 'statistiquesministerielles', 'indicateursCaracteristiquesContrats' ) ):?>
							<li>
								<?php echo $this->Xhtml->link( 'Statistiques ministérielles', '#' );?>
								<ul>
									<li>
										<?php echo $this->Xhtml->link( 'Indicateurs d\'orientations',  array( 'controller' => 'statistiquesministerielles', 'action' => 'indicateursOrientations'  ) );?>
									</li>

									<li>
										<?php echo $this->Xhtml->link( 'Indicateurs d\'organismes',  array( 'controller' => 'statistiquesministerielles', 'action' => 'indicateursOrganismes'  ) );?>
									</li>
									<li>
										<?php echo $this->Xhtml->link( 'Indicateurs de nature de contrats',  array( 'controller' => 'statistiquesministerielles', 'action' => 'indicateursDelais'  ) );?>
									</li>
									<li>
										<?php echo $this->Xhtml->link( 'Indicateurs de caractéristiques de contrats',  array( 'controller' => 'statistiquesministerielles', 'action' => 'indicateursCaracteristiquesContrats'  ) );?>
									</li>
									<li>
										<?php echo $this->Xhtml->link( 'Indicateurs de réorientations',  array( 'controller' => 'statistiquesministerielles', 'action' => 'indicateursReorientations'  ) );?>
									</li>
									<li>
										<?php echo $this->Xhtml->link( 'Indicateurs de motifs de réorientations',  array( 'controller' => 'statistiquesministerielles', 'action' => 'indicateursMotifsReorientation'  ) );?>
									</li>
								</ul>
							</li>

							<?php if( $this->Permissions->check( 'indicateurssuivis', 'index' ) ):?>
								<li><?php echo $this->Xhtml->link( 'Indicateurs de suivi', array( 'controller' => 'indicateurssuivis', 'action' => 'index' ) );?></li>
							<?php endif;?>

						<?php endif;?>
					</ul>
				</li>
			<?php endif;?>

			<?php if( $this->Permissions->check( 'parametrages', 'index' ) || $this->Permissions->check( 'infosfinancieres', 'indexdossier' ) || $this->Permissions->check( 'totalisationsacomptes', 'index' ) ): ?>
					<li id="menu6one">
						<?php echo $this->Xhtml->link( 'Administration', '#' );?>
						<ul>
							<?php if( $this->Permissions->check( 'parametrages', 'index' ) ):?>
								<li><?php echo $this->Xhtml->link( 'Paramétrages',  array( 'controller' => 'parametrages', 'action' => 'index'  ) );?></li>
							<?php endif;?>
							<?php if( $this->Permissions->check( 'infosfinancieres', 'indexdossier' ) || $this->Permissions->check( 'totalisationsacomptes', 'index' ) ):?>
								<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
								<?php  echo $this->Xhtml->link( 'Paiement allocation', '#' );?>
									<ul>
										<?php if( $this->Permissions->check( 'infosfinancieres', 'indexdossier' ) ):?>
											<li><?php echo $this->Xhtml->link( 'Listes nominatives', array( 'controller' => 'infosfinancieres', 'action' => 'indexdossier' ), array( 'title' => 'Listes nominatives' ) );?></li>
										<?php endif;?>
										<?php if( $this->Permissions->check( 'totalisationsacomptes', 'index' ) ):?>
											<li><?php echo $this->Xhtml->link( 'Mandats mensuels', array( 'controller' => 'totalisationsacomptes', 'action' => 'index' ), array( 'title' => 'Mandats mensuels' ) );?></li>
										<?php endif;?>
									</ul>
								</li>
							<?php endif;?>
							<?php if( $this->Permissions->check( 'gestionsanomaliesbdds', 'index' ) ):?>
								<li onmouseover="$(this).addClassName( 'hover' );" onmouseout="$(this).removeClassName( 'hover' );">
								<?php  echo $this->Xhtml->link( 'Gestion des anomalies', '#' );?>
									<ul>
										<?php if( $this->Permissions->check( 'gestionsanomaliesbdds', 'index' ) ):?>
											<li><?php echo $this->Xhtml->link( 'Doublons simples',  array( 'controller' => 'gestionsanomaliesbdds', 'action' => 'index'  ), array( 'title' => 'Gestion des anomalies de doublons simples au sein d\'un foyer donné' ) );?></li>
										<?php endif;?>
									</ul>
								</li>
							<?php endif;?>
						</ul>
					</li>
			<?php endif;?>

			<?php if( $this->Permissions->check( 'visionneuses', 'index' ) ) :?>
				<li id="menu7one" >
					<?php echo $this->Xhtml->link( 'Visionneuse', '#' );?>
					<ul>
						<?php if( $this->Permissions->check( 'visionneuses', 'index' ) ):?>
							<li><?php echo $this->Xhtml->link( 'logs', array( 'controller' => 'visionneuses', 'action' => 'index' ) );?></li>
						<?php endif;?>
					</ul>
				</li>
			<?php endif;?>

			<li id="menu9one"><?php echo $this->Xhtml->link( 'Déconnexion '.$this->Session->read( 'Auth.User.username' ), array( 'controller' => 'users', 'action' => 'logout' ) );?></li>
			<?php else: ?>
				<li><?php echo $this->Xhtml->link( 'Connexion', array( 'controller' => 'users', 'action' => 'login' ) );?></li>
			<?php endif; ?>
		</ul>
	</div>
</div>