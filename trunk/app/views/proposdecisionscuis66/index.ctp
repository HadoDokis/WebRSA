<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Avis techniques';?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<div class="with_treemenu aere">
	<?php
		echo $xhtml->tag(
			'h1',
			$this->pageTitle = __d( 'propodecisioncui66', "Proposdecisionscuis66::{$this->action}", true )
		);

		echo $default2->index(
			$proposdecisionscuis66,
			array(
				'Propodecisioncui66.datedebperiode',
				'Propodecisioncui66.datefinperiode',
				'Propodecisioncui66.nomentaccueil',
				'Propodecisioncui66.objectifimmersion',
				'Propodecisioncui66.datesignatureimmersion'
			),
			array(
				'actions' => array(
					'Proposdecisionscuis66::edit',
					'Proposdecisionscuis66::delete'
				),
				'add' => array( 'Proposdecisionscuis66::add' => $cui_id ),
				'options' => $options
			)
		);
	?>
</div>
	<?php echo $xform->create( 'Propodecisioncui66' );?>
	<div class="submit">
		<?php
			echo $xform->submit( 'Retour au CUI', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>
	<?php echo $xform->end(); ?>
<div class="clearer"><hr /></div>