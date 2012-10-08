<?php
	$this->pageTitle = __d( 'decisioncui66', "Decisionscuis66::{$this->action}" );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>

<div class="with_treemenu aere">
	<?php
		echo $this->Xhtml->tag( 'h1', $this->pageTitle );

		echo $this->Default2->index(
			$decisionscuis66,
			array(
				'Decisioncui66.decisioncui',
				'Decisioncui66.datedecisioncui',
				'Decisioncui66.observdecisioncui'
			),
			array(
				'actions' => array(
					'Decisionscuis66::edit',
					'Decisionscuis66::notifelu' => array( 'label' => 'Décision élu', 'url' => array( 'controller' => 'decisionscuis66', 'action' => 'impression', '#Decisioncui66.id#', 'elu' ) ),
					'Decisionscuis66::notifbenef' => array( 'label' => 'Notification bénéficiaire', 'url' => array( 'controller' => 'decisionscuis66', 'action' => 'impression', '#Decisioncui66.id#', 'benef' ) ),
					'Decisionscuis66::notifemployeur' => array( 'label' => 'Notification employeur','url' => array( 'controller' => 'decisionscuis66', 'action' => 'impression', '#Decisioncui66.id#', 'employeur' ) ),
					'Decisionscuis66::delete'
				),
				'add' => array(
					'Decisioncui66.add' => array( 'controller'=>'decisionscuis66', 'action'=>'add', $cui_id )
				),
				'options' => $options
			)
		);
	?>
</div>
	<?php echo $this->Xform->create( 'Decisioncui66' );?>
	<div class="submit">
		<?php
			echo $this->Xform->submit( 'Retour au CUI', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>
	<?php echo $this->Xform->end(); ?>
<div class="clearer"><hr /></div>