<?php
	if( $this->action == 'add' ) {
		$this->pageTitle = 'Ajout d\'une prestation';
	}
	else {
		$this->pageTitle = 'Prestations d\'insertion ';
	}

	if( Configure::read( 'debug' ) > 0 ) {
		echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>

<div class="with_treemenu">
	<h1><?php echo 'Ajout d\'une prestation pour le contrat ';?></h1>
	<?php
		if( $this->action == 'add' ) {
			echo $form->create( 'Prestform',array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
			echo '<div>';
			echo $form->input( 'Prestform.id', array( 'type' => 'hidden') );
			echo $form->input( 'Prestform.actioninsertion_id', array( 'type' => 'hidden' ) );
			echo $form->input( 'Prestform.refpresta_id', array( 'type' => 'hidden' ) );
			echo $form->input( 'Refpresta.id', array( 'type' => 'hidden' ) );
			echo '</div>';
		}
		else {
			echo $form->create( 'Prestform',array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
			echo '<div>';
			echo $form->input( 'Prestform.id', array( 'type' => 'hidden' ) );
			echo $form->input( 'Prestform.actioninsertion_id', array( 'type' => 'hidden' ) );
			echo $form->input( 'Prestform.refpresta_id', array( 'type' => 'hidden' ) );
			echo $form->input( 'Refpresta.id', array( 'type' => 'hidden' ) );
			echo '</div>';
		}
	?>

	<fieldset>
		<?php echo $form->input( 'Prestform.lib_presta', array( 'label' => required( __d( 'action', 'Action.lib_presta', true ) ), 'type' => 'select', 'options' => $actions, 'empty' => true )  ); ?>
		<?php echo $form->input( 'Refpresta.nomrefpresta', array( 'label' => required( __d( 'action', 'Action.nomrefpresta', true ) ), 'type' => 'text')); ?>
		<?php echo $form->input( 'Prestform.date_presta', array( 'label' => required( __d( 'action', 'Action.date_presta', true ) ), 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true )  ); ?>
	</fieldset>

	<?php echo $form->submit( 'Enregistrer' );?>
	<?php echo $form->end();?>
</div>
<div class="clearer"><hr /></div>