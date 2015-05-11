<?php $this->pageTitle = 'Paramétrages des CUIs';?>
<h1>Paramétrage des CUIs</h1>

<?php echo $this->Form->create( 'Cuis', array() );?>
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
						h( 'Motifs de rupture' ),
						$this->Xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'motifsrupturescuis66', 'action' => 'index' ),
							$this->Permissions->check( 'motifsrupturescuis66', 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);

                echo $this->Xhtml->tableCells(
                    array(
                        h( 'Motifs de suspension' ),
                        $this->Xhtml->viewLink(
                            'Voir la table',
                            array( 'controller' => 'motifssuspensioncuis66', 'action' => 'index' ),
                            $this->Permissions->check( 'motifssuspensioncuis66', 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );

                echo $this->Xhtml->tableCells(
                    array(
                        h( 'Pièces liées aux mails employeur' ),
                        $this->Xhtml->viewLink(
                            'Voir la table',
                            array( 'controller' => 'piecesmailscuis66', 'action' => 'index' ),
                            $this->Permissions->check( 'piecesmailscuis66', 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );

                echo $this->Xhtml->tableCells(
                    array(
                        __d( 'piecemanquantecui66', 'Pièces manquantes mails employeur' ),
                        $this->Xhtml->viewLink(
                            'Voir la table',
                            array( 'controller' => 'piecesmanquantescuis66', 'action' => 'index' ),
                            $this->Permissions->check( 'piecesmanquantescuis66', 'index' )
                        )
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
				
				echo $this->Xhtml->tableCells(
					array(
						h( 'Raisons sociales des employeurs' ),
						$this->Xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'raisonssocialespartenairescuis66', 'action' => 'index' ),
							$this->Permissions->check( 'raisonssocialespartenairescuis66', 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				
				echo $this->Xhtml->tableCells(
					array(
						h( 'Lien entre les secteurs et les taux' ),
						$this->Xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'tauxcgscuis', 'action' => 'index' ),
							$this->Permissions->check( 'tauxcgscuis', 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);

				echo $this->Xhtml->tableCells(
					array(
						h( 'Types de secteurs' ),
						$this->Xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'secteurscuis', 'action' => 'index' ),
							$this->Permissions->check( 'secteurscuis', 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);

				echo $this->Xhtml->tableCells(
					array(
						h( 'Modèles de mails pour les employeurs' ),
						$this->Xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'textsmailscuis66', 'action' => 'index' ),
							$this->Permissions->check( 'textsmailscuis66', 'index' )
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