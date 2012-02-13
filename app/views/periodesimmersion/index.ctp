<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Périodes d\'immersion';?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<div class="with_treemenu aere">
	<?php
		echo $xhtml->tag(
			'h1',
			$this->pageTitle = __d( 'periodeimmersion', "Periodesimmersion::{$this->action}", true )
		);

		$listeoptions = $options;
		unset( $options );
		$options['Periodeimmersion'] = $listeoptions;

		echo $default2->index(
			$periodesimmersion,
			array(
				'Periodeimmersion.datedebperiode',
				'Periodeimmersion.datefinperiode',
				'Periodeimmersion.nomentaccueil',
				'Periodeimmersion.objectifimmersion',
				'Periodeimmersion.datesignatureimmersion'
			),
			array(
				'actions' => array(
					'Periodesimmersion::edit',
					'Periodesimmersion::gedooo',
					'Periodesimmersion::delete'
				),
				'add' => array( 'Periodesimmersion::add' => $cui_id ),
				'options' => $options
			)
		);
	?>
</div>
	<?php echo $xform->create( 'Periodeimmersion' );?>
	<div class="submit">
		<?php
			echo $xform->submit( 'Retour au CUI', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>
	<?php echo $xform->end(); ?>
<div class="clearer"><hr /></div>