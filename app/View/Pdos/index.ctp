<?php $this->pageTitle = 'Paramétrages des PDOs';?>
<h1>Paramétrage des PDOs</h1>

<?php echo $this->Form->create( 'NouvellesPDOs', array( 'url'=> Router::url( null, true ) ) );?>
	<table >
		<thead>
			<tr>
				<th>Nom de Table</th>
				<th colspan="2" class="action">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php

				echo $this->Xhtml->tableCells(
					array(
						h( 'Décision PDOs' ),
						$this->Xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'decisionspdos', 'action' => 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);

				echo $this->Xhtml->tableCells(
					array(
						h( 'Description pour traitements PDOs' ),
						$this->Xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'descriptionspdos', 'action' => 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				/*echo $this->Xhtml->tableCells(
					array(
						h( 'Liste des courriers pour un traitement de PDOs' ),
						$this->Xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'courrierspdos', 'action' => 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);*/
                                
				echo $this->Xhtml->tableCells(
					array(
						h( 'Origine PDOs' ),
						$this->Xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'originespdos', 'action' => 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);

				if ( Configure::read( 'Cg.departement' ) == 66 ) {
                                        echo $this->Xhtml->tableCells(
                                            array(
                                                h( 'Module courriers lié aux traitements PCGs' ),
                                                $this->Xhtml->viewLink(
                                                    'Voir la table',
                                                    array( 'controller' => 'courrierspcgs66', 'action' => 'index' )
                                                )
                                            ),
                                            array( 'class' => 'odd' ),
                                            array( 'class' => 'even' )
                                        );
					echo $this->Xhtml->tableCells(
						array(
							h( 'Paramétrage pour les décisions de dossiers PCG' ),
							$this->Xhtml->viewLink(
								'Voir la table',
								array( 'controller' => 'decisionsdossierspcgs66', 'action' => 'index' )
							)
						),
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);
				}
				
				echo $this->Xhtml->tableCells(
					array(
						h( 'Situation PDOs' ),
						$this->Xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'situationspdos', 'action' => 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				echo $this->Xhtml->tableCells(
					array(
						h( 'Statut PDOs' ),
						$this->Xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'statutspdos', 'action' => 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				echo $this->Xhtml->tableCells(
					array(
						h( 'Type de notification' ),
						$this->Xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'typesnotifspdos', 'action' => 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				if ( Configure::read( 'Cg.departement' ) != 66 ) {
					echo $this->Xhtml->tableCells(
						array(
							h( 'Types de traitements PDOs' ),
							$this->Xhtml->viewLink(
								'Voir la table',
								array( 'controller' => 'traitementstypespdos', 'action' => 'index' )
							)
						),
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);
				}
				echo $this->Xhtml->tableCells(
					array(
						h( 'Type de PDOs' ),
						$this->Xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'typespdos', 'action' => 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				
				if ( Configure::read( 'Cg.departement' ) == 66 ) {
					echo $this->Xhtml->tableCells(
						array(
							h( 'Types de RSA' ),
							$this->Xhtml->viewLink(
								'Voir la table',
								array( 'controller' => 'typesrsapcgs66', 'action' => 'index' )
							)
						),
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);
				}
				
				echo $this->Xhtml->tableCells(
					array(
						h( 'Zones supplémentaires pour les courriers de traitements PDOs' ),
						$this->Xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'textareascourrierspdos', 'action' => 'index' ),
							( ( $compteurs['Courrierpdo'] > 0 ) )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);

			?>
		</tbody>
	</table>
	<div class="submit">
		<?php
			echo $this->Form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>
<?php echo $this->Form->end();?>