<?php $this->pageTitle = 'Paramétrages';?>
<h1>Paramétrage des tables</h1>

<?php echo $form->create( 'NouvellesDemandes', array( 'url'=> Router::url( null, true ) ) );?>
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
					echo $xhtml->tableCells(
						array(
							h( 'Actions d\'insertion' ),
							$xhtml->viewLink(
								'Voir la table',
								array( 'controller' => 'actions', 'action' => 'index' )
							)
						),
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);
				}
				echo $xhtml->tableCells(
					array(
						h( 'APREs' ),
						$xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'apres'.Configure::read( 'Apre.suffixe' ), 'action' => 'indexparams' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				echo $xhtml->tableCells(
					array(
						h( 'Cantons' ),
						$xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'cantons', 'action' => 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				echo $xhtml->tableCells(
					array(
						h( 'CUIs' ),
						$xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'cuis', 'action' => 'indexparams' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				echo $xhtml->tableCells(
					array(
						h( 'DSPs' ),
						$xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'gestionsdsps', 'action' => 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				echo $xhtml->tableCells(
					array(
						h( 'Équipes pluridisciplinaires' ),
						$xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'gestionseps', 'action' => 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				if( Configure::read( 'Cg.departement' ) == 66 ){
					echo $xhtml->tableCells(
						array(
							h( 'Fiches de Candidature' ),
							$xhtml->viewLink(
								'Voir la table',
								array( 'controller' => 'actionscandidats_personnes', 'action' => 'indexparams' )
							)
						),
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);
				}
				else if( Configure::read( 'Cg.departement' ) == 93 ){
					echo $xhtml->tableCells(
						array(
							h( 'Fiches de Liaison' ),
							$xhtml->viewLink(
								'Voir la table',
								array( 'controller' => 'actionscandidats_personnes', 'action' => 'indexparams' )
							)
						),
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);
				}

				echo $xhtml->tableCells(
					array(
						h( 'Gestion des rendez-vous' ),
						$xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'gestionsrdvs', 'action' => 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				echo $xhtml->tableCells(
					array(
						h( 'Groupes d\'utilisateurs' ),
						$xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'groups', 'action' => 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				if ( Configure::read( 'Cg.departement' ) == 58 ) {
					echo $xhtml->tableCells(
						array(
							h( 'Liste des sanctions' ),
							$xhtml->viewLink(
								'Voir la table',
								array( 'controller' => 'listesanctionseps58', 'action' => 'index' )
							)
						),
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);
				}
				if ( Configure::read( 'Cg.departement' ) == 66 ) {
					echo $xhtml->tableCells(
						array(
							h( 'Motifs de non validation de CER' ),
							$xhtml->viewLink(
								'Voir la table',
								array( 'controller' => 'motifscersnonvalids66', 'action' => 'index' )
							)
						),
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);
				}
				echo $xhtml->tableCells(
					array(
						h( 'Objets de l\'entretien' ),
						$xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'objetsentretien', 'action' => 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				echo $xhtml->tableCells(
					array(
						h( 'PDOs' ),
						$xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'pdos', 'action' => 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				echo $xhtml->tableCells(
					array(
						h( 'Permanences' ),
						$xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'permanences', 'action' => 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				echo $xhtml->tableCells(
					array(
						h( 'Référents pour les structures' ),
						$xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'referents', 'action' => 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				echo $xhtml->tableCells(
					array(
						h( 'Services instructeurs' ),
						$xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'servicesinstructeurs', 'action' => 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				if ( Configure::read( 'Cg.departement' ) == 58 ) {
					echo $xhtml->tableCells(
						array(
							h( 'Sites d\'actions médico-sociale COVs' ),
							$xhtml->viewLink(
								'Voir la table',
								array( 'controller' => 'sitescovs58', 'action' => 'index' )
							)
						),
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);
				}
				echo $xhtml->tableCells(
					array(
						h( 'Structures référentes' ),
						$xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'structuresreferentes', 'action' => 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				if( Configure::read( 'Cg.departement' ) != 66 ){
					echo $xhtml->tableCells(
						array(
							h( 'Types d\'actions' ),
							$xhtml->viewLink(
								'Voir la table',
								array( 'controller' => 'typesactions', 'action' => 'index' )
							)
						),
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);
				}
				echo $xhtml->tableCells(
					array(
						h( 'Types d\'orientations' ),
						$xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'typesorients', 'action' => 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				echo $xhtml->tableCells(
					array(
						h( 'Utilisateurs' ),
						$xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'users', 'action' => 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				echo $xhtml->tableCells(
					array(
						h( 'Vérification de l\'application' ),
						$xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'checks', 'action' => 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				echo $xhtml->tableCells(
					array(
						h( 'Zones géographiques' ),
						$xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'zonesgeographiques', 'action' => 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
			?>
		</tbody>
	</table>
<?php echo $form->end();?>
