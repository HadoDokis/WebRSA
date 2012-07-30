<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Accompagnement';?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<div class="with_treemenu aere">
	<?php
		echo $xhtml->tag(
			'h1',
			$this->pageTitle = __d( 'accompagnementcui66', "Accompagnementscuis66::{$this->action}", true )
		);

		echo $default2->index(
			$accompagnementscuis66,
			array(
				'Accompagnementcui66.typeaccompagnementcui66',
				'Accompagnementcui66.datedebperiode',
				'Accompagnementcui66.datefinperiode',
				'Accompagnementcui66.nomentaccueil',
				'Accompagnementcui66.objectifimmersion',
				'Accompagnementcui66.datesignatureimmersion'
			),
			array(
				'actions' => array(
					'Accompagnementscuis66::edit',
					'Accompagnementscuis66::impression',
					'Accompagnementscuis66::delete'
				),
				'add' => array(
					'Accompagnementcui66.add' => array( 'controller'=>'accompagnementscuis66', 'action'=>'add', $cui_id ),
				),
				'options' => $options
			)
		);
	?>
</div>
	<?php echo $xform->create( 'Accompagnementcui66' );?>
	<div class="submit">
		<?php
			echo $xform->submit( 'Retour au CUI', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>
	<?php echo $xform->end(); ?>
<div class="clearer"><hr /></div>