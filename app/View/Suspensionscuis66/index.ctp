<?php
	$this->pageTitle = 'Suspension/Rupture';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>

<div class="with_treemenu aere">
	<?php
		echo $this->Xhtml->tag(
			'h1',
			$this->pageTitle = __d( 'suspensioncui66', "Suspensionscuis66::{$this->action}" )
		);

// 		$listeoptions = $options;
// 		unset( $options );
// 		$options['Suspensioncui66'] = $listeoptions;

		echo $this->Default2->index(
			$suspensionscuis66,
			array(
				'Suspensioncui66.datedebperiode',
				'Suspensioncui66.datefinperiode',
				'Suspensioncui66.nomentaccueil',
				'Suspensioncui66.objectifimmersion',
				'Suspensioncui66.datesignatureimmersion'
			),
			array(
				'actions' => array(
					'Suspensionscuis66::edit',
					'Suspensionscuis66::delete'
				),
				'add' => array(
					'Suspensioncui66.add' => array( 'controller'=>'suspensionscuis66', 'action'=>'add', $cui_id, 'disabled' => true ),
				),
				'options' => $options
			)
		);
	?>
</div>
	<?php echo $this->Xform->create( 'Suspensioncui66' );?>
	<div class="submit">
		<?php
			echo $this->Xform->submit( 'Retour au CUI', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>
	<?php echo $this->Xform->end(); ?>
<div class="clearer"><hr /></div>