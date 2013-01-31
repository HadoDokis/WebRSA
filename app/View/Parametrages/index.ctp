<?php $this->pageTitle = 'Paramétrages';?>
<h1>Paramétrage des tables</h1>

<?php echo $this->Form->create( 'NouvellesDemandes', array( 'url'=> Router::url( null, true ) ) );?>
	<table >
		<thead>
			<tr>
				<th>Nom de Table</th>
				<th colspan="2" class="action">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php
				if( Configure::read( 'Cg.departement' ) != 66 ){
					echo $this->Xhtml->tableCells(
						array(
							h( 'Actions d\'insertion' ),
							$this->Xhtml->viewLink(
								'Voir la table',
								array( 'controller' => 'actions', 'action' => 'index' ),
								$this->Permissions->check( 'actions', 'index' )
							)
						),
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);
				}
				echo $this->Xhtml->tableCells(
					array(
						h( 'APREs' ),
						$this->Xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'apres'.Configure::read( 'Apre.suffixe' ), 'action' => 'indexparams' ),
							$this->Permissions->check(  'apres'.Configure::read( 'Apre.suffixe' ), 'indexparams' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				echo $this->Xhtml->tableCells(
					array(
						h( 'Cantons' ),
						$this->Xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'cantons', 'action' => 'index' ),
							$this->Permissions->check( 'cantons', 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				if( Configure::read( 'Cg.departement' ) == 93 ) {
					echo $this->Xhtml->tableCells(
						array(
							h( 'CERs' ),
							$this->Xhtml->viewLink(
								'Voir la table',
								array( 'controller' => 'cers93', 'action' => 'indexparams' ),
								$this->Permissions->check( 'cers93', 'indexparams' )
							)
						),
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);
				}
				echo $this->Xhtml->tableCells(
					array(
						h( 'CUIs' ),
						$this->Xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'cuis', 'action' => 'indexparams' ),
							$this->Permissions->check( 'cuis', 'indexparams' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				echo $this->Xhtml->tableCells(
					array(
						h( 'DSPs' ),
						$this->Xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'gestionsdsps', 'action' => 'index' ),
							$this->Permissions->check( 'gestionsdsps', 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				echo $this->Xhtml->tableCells(
					array(
						h( 'Équipes pluridisciplinaires' ),
						$this->Xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'gestionseps', 'action' => 'index' ),
							$this->Permissions->check( 'gestionseps', 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				if( Configure::read( 'Cg.departement' ) == 66 ){
					echo $this->Xhtml->tableCells(
						array(
							h( 'Fiches de Candidature' ),
							$this->Xhtml->viewLink(
								'Voir la table',
								array( 'controller' => 'actionscandidats_personnes', 'action' => 'indexparams' ),
								$this->Permissions->check( 'actionscandidats_personnes', 'indexparams' )
							)
						),
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);
				}
				else if( Configure::read( 'Cg.departement' ) == 93 ){
					echo $this->Xhtml->tableCells(
						array(
							h( 'Fiches de prescription' ),
							$this->Xhtml->viewLink(
								'Voir la table',
								array( 'controller' => 'actionscandidats_personnes', 'action' => 'indexparams' ),
								$this->Permissions->check( 'actionscandidats_personnes', 'indexparams' )
							)
						),
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);
				}

				echo $this->Xhtml->tableCells(
					array(
						h( 'Gestion des rendez-vous' ),
						$this->Xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'gestionsrdvs', 'action' => 'index' ),
							$this->Permissions->check( 'gestionsrdvs', 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				echo $this->Xhtml->tableCells(
					array(
						h( 'Groupes d\'utilisateurs' ),
						$this->Xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'groups', 'action' => 'index' ),
							$this->Permissions->check( 'groups', 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				if ( Configure::read( 'Cg.departement' ) == 58 ) {
					echo $this->Xhtml->tableCells(
						array(
							h( 'Liste des sanctions' ),
							$this->Xhtml->viewLink(
								'Voir la table',
								array( 'controller' => 'listesanctionseps58', 'action' => 'index' ),
								$this->Permissions->check( 'listesanctionseps58', 'index' )
							)
						),
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);
				}
				if ( Configure::read( 'Cg.departement' ) == 66 ) {
					echo $this->Xhtml->tableCells(
						array(
							h( 'Motifs de non validation de CER' ),
							$this->Xhtml->viewLink(
								'Voir la table',
								array( 'controller' => 'motifscersnonvalids66', 'action' => 'index' ),
								$this->Permissions->check( 'motifscersnonvalids66', 'index' )
							)
						),
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);
				}
				echo $this->Xhtml->tableCells(
					array(
						h( 'Objets de l\'entretien' ),
						$this->Xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'objetsentretien', 'action' => 'index' ),
							$this->Permissions->check( 'objetsentretien', 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				echo $this->Xhtml->tableCells(
					array(
						h( 'PDOs' ),
						$this->Xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'pdos', 'action' => 'index' ),
							$this->Permissions->check( 'pdos', 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				echo $this->Xhtml->tableCells(
					array(
						h( 'Permanences' ),
						$this->Xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'permanences', 'action' => 'index' ),
							$this->Permissions->check( 'permanences', 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				echo $this->Xhtml->tableCells(
					array(
						h( 'Référents pour les structures' ),
						$this->Xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'referents', 'action' => 'index' ),
							$this->Permissions->check( 'referents', 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				echo $this->Xhtml->tableCells(
					array(
						h( 'Services instructeurs' ),
						$this->Xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'servicesinstructeurs', 'action' => 'index' ),
							$this->Permissions->check( 'servicesinstructeurs', 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				if ( Configure::read( 'Cg.departement' ) == 58 ) {
					echo $this->Xhtml->tableCells(
						array(
							h( 'Sites d\'actions médico-sociale COVs' ),
							$this->Xhtml->viewLink(
								'Voir la table',
								array( 'controller' => 'sitescovs58', 'action' => 'index' ),
								$this->Permissions->check( 'sitescovs58', 'index' )
							)
						),
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);
				}
				echo $this->Xhtml->tableCells(
					array(
						h( 'Structures référentes' ),
						$this->Xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'structuresreferentes', 'action' => 'index' ),
							$this->Permissions->check( 'structuresreferentes', 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				if( Configure::read( 'Cg.departement' ) != 66 ){
					echo $this->Xhtml->tableCells(
						array(
							h( 'Types d\'actions' ),
							$this->Xhtml->viewLink(
								'Voir la table',
								array( 'controller' => 'typesactions', 'action' => 'index' ),
								$this->Permissions->check( 'typesactions', 'index' )
							)
						),
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);
				}
				echo $this->Xhtml->tableCells(
					array(
						h( 'Types d\'orientations' ),
						$this->Xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'typesorients', 'action' => 'index' ),
							$this->Permissions->check( 'typesorients', 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				echo $this->Xhtml->tableCells(
					array(
						h( 'Utilisateurs' ),
						$this->Xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'users', 'action' => 'index' ),
							$this->Permissions->check( 'users', 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				echo $this->Xhtml->tableCells(
					array(
						h( 'Vérification de l\'application' ),
						$this->Xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'checks', 'action' => 'index' ),
							$this->Permissions->check( 'checks', 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				echo $this->Xhtml->tableCells(
					array(
						h( 'Zones géographiques' ),
						$this->Xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'zonesgeographiques', 'action' => 'index' ),
							$this->Permissions->check( 'zonesgeographiques', 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
			?>
		</tbody>
	</table>
<?php echo $this->Form->end();?>
