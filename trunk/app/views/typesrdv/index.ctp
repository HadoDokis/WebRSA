<?php $this->pageTitle = 'Paramétrage des objets du rendez-vous';?>
<?php echo $xform->create( 'Rendezvous' );?>
<div>
	<h1><?php echo 'Visualisation de la table  ';?></h1>

	<ul class="actionMenu">
		<?php
			echo '<li>'.$xhtml->addLink(
				'Ajouter',
				array( 'controller' => 'typesrdv', 'action' => 'add' )
			).' </li>';
		?>
	</ul>
	<div>
		<h2>Table Objet du rendez-vous</h2>
		<table>
		<thead>
			<tr>
				<th>Objet du rendez-vous</th>
				<th>Modèle de notification de RDV</th>
				<?php
/*					if ( Configure::read( 'Cg.departement' ) == 58 ) {
						echo '<th>Nombre d\'absences non excusées avant passage en EP</th>';
						echo '<th>Description du motif de passage en EP</th>';
					}
					else*/if ( Configure::read( 'Cg.departement' ) == 66 ) {
						echo '<th>Nombre d\'absences avant possible passage en EPL Audition</th>';
					}
				?>
				<th colspan="2" class="action">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach( $typesrdv as $typerdv ):?>
				<?php
					$listefields = array(
						h( $typerdv['Typerdv']['libelle'] ),
						h( $typerdv['Typerdv']['modelenotifrdv'] )
					);
/*					if ( Configure::read( 'Cg.departement' ) == 58 ) {
						$listefields = array_merge(
							$listefields,
							array(
								h( $typerdv['Typerdv']['nbabsencesavpassageep'] ),
								h( $typerdv['Typerdv']['motifpassageep'] )
							)
						);
					}
					else*/if ( Configure::read( 'Cg.departement' ) == 66 ) {
						$listefields = array_merge(
							$listefields,
							array(
								h( $typerdv['Typerdv']['nbabsaveplaudition'] )
							)
						);
					}

					$listefields = array_merge(
						$listefields,
						array(
							$xhtml->editLink(
								'Éditer le type d\'action',
								array( 'controller' => 'typesrdv', 'action' => 'edit', $typerdv['Typerdv']['id'] )
							),
							$xhtml->deleteLink(
								'Supprimer le type d\'action',
								array( 'controller' => 'typesrdv', 'action' => 'delete', $typerdv['Typerdv']['id'] )
							)
						)
					);
					echo $xhtml->tableCells(
						$listefields,
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);
				?>
			<?php endforeach;?>
			</tbody>
		</table>
</div>
</div>
	<div class="submit">
		<?php
			echo $xform->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>

<div class="clearer"><hr /></div>
<?php echo $xform->end();?>