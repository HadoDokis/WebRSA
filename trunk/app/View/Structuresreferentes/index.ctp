<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
	}
?>
<?php $this->pageTitle = 'Paramétrage des Structures référentes';?>
<div>
	<h1><?php echo 'Visualisation de la table  ';?></h1>

	<?php if( $this->Permissions->check( 'structuresreferentes', 'add' ) ):?>
		<ul class="actionMenu">
			<?php
				echo '<li>'.$this->Xhtml->addLink(
					'Ajouter',
					array( 'controller' => 'structuresreferentes', 'action' => 'add' )
				).' </li>';
			?>
		</ul>
	<?php endif;?>
<?php
	echo '<ul class="actionMenu"><li>'.$this->Xhtml->link(
		$this->Xhtml->image(
			'icons/application_form_magnify.png',
			array( 'alt' => '' )
		).' Formulaire',
		'#',
		array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
	).'</li></ul>';
?>

<?php echo $this->Xform->create( 'Structurereferente', array( 'type' => 'post', 'action' => 'index', 'id' => 'Search', 'class' => ( ( is_array( $this->request->data ) && !empty( $this->request->data ) ) ? 'folded' : 'unfolded' ) ) );?>
		<fieldset>
			<?php echo $this->Xform->input( 'Structurereferente.index', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>

			<legend>Filtrer par Référent</legend>
			<?php
				echo $this->Default2->subform(
					array(
						'Structurereferente.lib_struc',
						'Structurereferente.ville',
						'Structurereferente.typeorient_id' => array( 'label' => 'Type d\'orientation', 'options' => $options['Structurereferente']['typeorient_id'] )
					),
					array(
						'options' => $options
					)
				);
			?>
		</fieldset>

		<div class="submit noprint">
			<?php echo $this->Xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
			<?php echo $this->Xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
		</div>

<?php echo $this->Xform->end();?>

<?php  if( isset( $structuresreferentes ) ): ?>
	<?php if( empty( $structuresreferentes ) ):?>
		<?php
			$message = 'Aucune structure ne correspond à votre recherche';
		?>
		<p class="notice"><?php echo $message;?></p>
	<?php else:?>
	<?php $pagination = $this->Xpaginator->paginationBlock( 'Structurereferente', $this->passedArgs ); ?>
	<?php echo $pagination;?>
		<h2>Table Structures référentes</h2>
		<table>
		<thead>
			<tr>
				<th>Nom de la structure</th>
				<th>N° de voie</th>
				<th>Type de voie</th>
				<th>Nom de voie</th>
				<th>Code postal</th>
				<th>Ville</th>
				<th>Code insee</th>
				<th>Téléphone</th>
				<th>Type d'orientation</th>
				<th>Active</th>
				<th>Type de structure</th>
				<th colspan="2" class="action">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach( $structuresreferentes as $structurereferente ):?>
				<?php
					echo $this->Xhtml->tableCells(
						array(
							h( $structurereferente['Structurereferente']['lib_struc'] ),
							h( $structurereferente['Structurereferente']['num_voie'] ),
							h( isset( $typevoie[$structurereferente['Structurereferente']['type_voie']] ) ? $typevoie[$structurereferente['Structurereferente']['type_voie']] : null ),
							h( $structurereferente['Structurereferente']['nom_voie'] ),
							h( $structurereferente['Structurereferente']['code_postal'] ),
							h( $structurereferente['Structurereferente']['ville'] ),
							h( $structurereferente['Structurereferente']['code_insee'] ),
							h( $structurereferente['Structurereferente']['numtel'] ),
							h( Set::enum( $structurereferente['Structurereferente']['typeorient_id'], $options['Structurereferente']['typeorient_id'] ) ),
							h( Set::enum( $structurereferente['Structurereferente']['actif'], $options['Structurereferente']['actif'] ) ),
							h( Set::enum( $structurereferente['Structurereferente']['typestructure'], $options['Structurereferente']['typestructure'] ) ),
							$this->Xhtml->editLink(
								'Éditer la structure référente ',
								array( 'controller' => 'structuresreferentes', 'action' => 'edit', $structurereferente['Structurereferente']['id'] ),
								$this->Permissions->check( 'structuresreferentes', 'edit' )
							),
							$this->Xhtml->deleteLink(
								'Supprimer la structure référente ',
								array( 'controller' => 'structuresreferentes', 'action' => 'delete', $structurereferente['Structurereferente']['id'] ),
								( $this->Permissions->check( 'structuresreferentes', 'delete' ) && !( $structurereferente['Structurereferente']['has_linkedrecords'] ) )
							)
						),
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);
				?>
			<?php endforeach;?>
			</tbody>
		</table>
	<?php endif?>
<?php endif?>
</div>
<?php
	echo $this->Default->button(
		'back',
		array(
			'controller' => 'parametrages',
			'action'     => 'index'
		),
		array(
			'id' => 'Back'
		)
	);
?>