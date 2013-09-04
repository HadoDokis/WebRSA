<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>
<?php $this->pageTitle = 'Paramétrage des référents';?>

<div>
	<h1><?php echo 'Visualisation de la table référents ';?></h1>

	<ul class="actionMenu">
		<?php
			echo '<li>'.$this->Xhtml->addLink(
				'Ajouter',
				array( 'controller' => 'referents', 'action' => 'add' ),
				$this->Permissions->check( 'referents', 'add' )
			).' </li>';
		?>
	</ul>

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

<?php echo $this->Xform->create( 'Referent', array( 'type' => 'post', 'action' => $this->action, 'id' => 'Search', 'class' => ( ( is_array( $this->request->data ) && !empty( $this->request->data ) ) ? 'folded' : 'unfolded' ) ) );?>
		<fieldset>
			<?php echo $this->Xform->input( 'Referent.index', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>

			<legend>Filtrer par Référent</legend>
			<?php
				echo $this->Default2->subform(
					array(
						'Referent.nom',
						'Referent.prenom',
						'Referent.fonction',
						'Referent.structurereferente_id' => array( 'label' => 'Structure référente liée', 'options' => $options['Referent']['structurereferente_id'], 'type' => ( $this->action == 'index' ? 'select': 'hidden' ) )
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

<?php  if( isset( $referents ) ): ?>
	<?php if( empty( $referents ) ):?>
		<?php
			$message = 'Aucune référent ne correspond à votre recherche';
		?>
		<p class="notice"><?php echo $message;?></p>
	<?php else:?>
	<?php $pagination = $this->Xpaginator->paginationBlock( 'Referent', $this->passedArgs ); ?>
	<?php echo $pagination;?>
		<h2>Table Référents</h2>
		<table class="default2">
		<thead>
			<tr>
				<th>Civilité</th>
				<th>Nom</th>
				<th>Prénom</th>
				<th>Fonction</th>
				<th>N° téléphone</th>
				<th>Email</th>
				<th>Structure référente liée</th>
				<th>Actif</th>
				<th colspan="3" class="action">Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach( $referents as $referent ):?>
				<?php
					$cloturable = ( empty( $referent['Referent']['datecloture'] ) || ( $referent['PersonneReferent']['nb_referents_lies'] > 0 ) );

					echo $this->Xhtml->tableCells(
						array(
							h( $qual[$referent['Referent']['qual']] ),
							h( $referent['Referent']['nom'] ),
							h( $referent['Referent']['prenom'] ),
							h( $referent['Referent']['fonction'] ),
							h( $referent['Referent']['numero_poste'] ),
							h( $referent['Referent']['email'] ),
							h( $referent['Structurereferente']['lib_struc'] ),
							h( Set::enum( $referent['Referent']['actif'], $options['Referent']['actif'] ) ),
							$this->Default2->button(
								'cloture_referent',
								array( 'controller' => 'referents', 'action' => 'cloturer',
								$referent['Referent']['id'] ),
								array( 'enabled' => ( $cloturable && $this->Permissions->check( 'referents', 'cloturer' ) ) )
							),
							$this->Default2->button(
								'edit',
								array( 'controller' => 'referents', 'action' => 'edit',
								$referent['Referent']['id'] ),
								array( 'enabled' => ( $this->Permissions->check( 'referents', 'edit' ) ) )
							),
							$this->Default2->button(
								'delete',
								array( 'controller' => 'referents', 'action' => 'delete',
								$referent['Referent']['id'] ),
								array( 'enabled' => ( $this->Permissions->check( 'referents', 'delete' ) ) )
							)
						),
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);
				?>
			<?php endforeach;?>
			</tbody>
		</table>
		<?php echo $pagination;?>
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