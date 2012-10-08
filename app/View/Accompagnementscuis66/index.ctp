<?php
	$this->pageTitle = 'Accompagnement';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>
<div class="with_treemenu aere">
	<?php
		echo $this->Xhtml->tag(
			'h1',
			$this->pageTitle = __d( 'accompagnementcui66', "Accompagnementscuis66::{$this->action}" )
		);

		echo $this->Default2->index(
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
	<div>
	<?
			echo $this->Default->button(
				'back',
				array(
					'controller' => 'cuis',
					'action'     => 'index',
					$personne_id
				),
				array(
					'id' => 'Back'
				)
			);
		?>
	</div>
</div>
<div class="clearer"><hr /></div>