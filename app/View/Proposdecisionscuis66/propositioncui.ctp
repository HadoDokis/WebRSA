<?php
	$this->pageTitle = 'Avis techniques';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>

<div class="with_treemenu aere">
	<?php
		echo $this->Xhtml->tag(
			'h1',
			$this->pageTitle = __d( 'propodecisioncui66', "Proposdecisionscuis66::{$this->action}" )
		);

		echo $this->Default2->index(
			$proposdecisionscuis66,
			array(
				'Propodecisioncui66.propositioncui',
				'Propodecisioncui66.datepropositioncui',
				'Propodecisioncui66.observcui',
				'Propodecisioncui66.propositioncuielu',
				'Propodecisioncui66.datepropositioncuielu',
				'Propodecisioncui66.observcuielu',
				'Propodecisioncui66.propositioncuireferent',
				'Propodecisioncui66.datepropositioncuireferent',
				'Propodecisioncui66.observcuireferent'
			),
			array(
				'actions' => array(
					'Proposdecisionscuis66::edit',
					'Proposdecisionscuis66::notifelucui',
					'Proposdecisionscuis66::delete'
				),
				'add' => array(
					'Propodecisioncui66.add' => array( 'controller'=>'proposdecisionscuis66', 'action'=>'add', $cui_id )
				),
				'options' => $options
			)
		);
	?>
</div>
	<?php echo $this->Xform->create( 'Propodecisioncui66' );?>
	<div class="submit">
		<?php
			echo $this->Xform->submit( 'Retour au CUI', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>
	<?php echo $this->Xform->end(); ?>
<div class="clearer"><hr /></div>