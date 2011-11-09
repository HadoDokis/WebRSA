<?php $this->pageTitle = 'Paramétrages des Equipes Pluridisciplinaires';?>
<h1>Paramétrage des EPs</h1>

<?php echo $form->create( 'NouvellesEPs', array( 'url'=> Router::url( null, true ) ) );?>
	<table >
		<thead>
			<tr>
				<th>Nom de Table</th>
				<th colspan="2" class="action">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php
				echo $xhtml->tableCells(
					array(
						h( 'Objets du rendez-vous' ),
						$xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'typesrdv', 'action' => 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				echo $xhtml->tableCells(
					array(
						h( 'Statut des RDVs' ),
						$xhtml->viewLink(
							'Voir la table',
							array( 'controller' => 'statutsrdvs', 'action' => 'index' )
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
				if( Configure::read( 'Cg.departement' ) == 58 ){
					echo $xhtml->tableCells(
						array(
							h( 'Passage en EP des RDVs' ),
							$xhtml->viewLink(
								'Voir la table',
								array( 'controller' => 'statutsrdvs_typesrdv', 'action' => 'index' )
							)
						),
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);
				}
			?>
		</tbody>
	</table>
	<div class="submit">
		<?php
			echo $form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>
<?php echo $form->end();?>