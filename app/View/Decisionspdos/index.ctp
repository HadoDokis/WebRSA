<?php $this->pageTitle = 'Paramétrage des décisions de PDO';?>
<?php echo $this->Xform->create( 'Decisionpdo' );?>
<div>
	<h1><?php echo 'Visualisation de la table  ';?></h1>

	<ul class="actionMenu">
		<?php
			echo '<li>'.$this->Xhtml->addLink(
				'Ajouter',
				array( 'controller' => 'decisionspdos', 'action' => 'add' )
			).' </li>';
		?>
	</ul>
	<div>
		<h2>Table Décision de PDO</h2>
		<table>
		<thead>
			<tr>
				<th>Libellé</th>
				<th>Ce type clotûre-t-il le dossier ?</th>
				<?php if( Configure::read( 'Cg.departement' ) == 66  ) :?>
					<th>Cette décision est-elle liée à un CER Particulier ?</th>
				<?php endif;?>
				<?php if( Configure::read( 'Cg.departement' ) == 93  ) :?>
					<th>Modèle de document lié</th>
				<?php endif;?>
				<th colspan="2" class="action">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach( $decisionspdos as $decisionpdo ):
			
				if( Configure::read( 'Cg.departement' ) == 66  ) {
					$arrayCells = array(
						h( $decisionpdo['Decisionpdo']['libelle'] ),
						( $decisionpdo['Decisionpdo']['clos'] == 'N' ) ? 'Non' : 'Oui',
						( $decisionpdo['Decisionpdo']['cerparticulier'] == 'N' ) ? 'Non' : 'Oui',
						$this->Xhtml->editLink(
							'Éditer la décision de PDO ',
							array( 'controller' => 'decisionspdos', 'action' => 'edit', $decisionpdo['Decisionpdo']['id'] )
						),
						$this->Xhtml->deleteLink(
							'Supprimer la décision de PDO ',
							array( 'controller' => 'decisionspdos', 'action' => 'delete', $decisionpdo['Decisionpdo']['id'] )
						)
					);
				}
				else{
					$arrayCells = array(
						h( $decisionpdo['Decisionpdo']['libelle'] ),
						( $decisionpdo['Decisionpdo']['clos'] == 'N' ) ? 'Non' : 'Oui',
						h( $decisionpdo['Decisionpdo']['modeleodt'] ),
						$this->Xhtml->editLink(
							'Éditer la décision de PDO ',
							array( 'controller' => 'decisionspdos', 'action' => 'edit', $decisionpdo['Decisionpdo']['id'] )
						),
						$this->Xhtml->deleteLink(
							'Supprimer la décision de PDO ',
							array( 'controller' => 'decisionspdos', 'action' => 'delete', $decisionpdo['Decisionpdo']['id'] )
						)
					);
				}
			

				
				echo $this->Xhtml->tableCells(
					$arrayCells,
					array( 'class' => 'odd', 'id' => 'innerTableTrigger' ),
					array( 'class' => 'even', 'id' => 'innerTableTrigger' )
				);
			endforeach;?>
		</tbody>
		</table>
</div>
</div>
	<div class="submit">
		<?php
			echo $this->Xform->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>

<div class="clearer"><hr /></div>
<?php echo $this->Xform->end();?>