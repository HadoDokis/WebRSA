<?php
	$this->pageTitle = 'Aides pour un contrat';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>


<?php
	if( $this->action == 'add' ) {
		$this->pageTitle = 'Ajout d\'une aide';
	}
	else {
		$this->pageTitle = 'Aides d\'insertion ';
	}
?>

<div class="with_treemenu">
	<h1><?php echo 'Ajout d\'une aide pour le contrat ';?></h1><!-- FIXME -->

	<?php
		if( $this->action == 'add' ) {
			echo $form->create( 'Aidedirecte', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
			echo '<div>';
			echo $form->input( 'Aidedirecte.id', array( 'type' => 'hidden' ) );
			echo $form->input( 'Aidedirecte.actioninsertion_id', array( 'type' => 'hidden' ) );
			echo '</div>';
		}
		else {
			echo $form->create( 'Aidedirecte', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
			echo '<div>';
			echo $form->input( 'Aidedirecte.id', array( 'type' => 'hidden' ) );
			echo $form->input( 'Aidedirecte.actioninsertion_id', array( 'type' => 'hidden' ) );
			echo '</div>';
		}
	?>

	<fieldset>
		<?php echo $form->input( 'Aidedirecte.typo_aide', array( 'label' => required( __d( 'action', 'Action.typo_aide', true ) ), 'type' => 'select', 'options' => $typo_aide, 'empty' => true )  ); ?>
		<?php echo $form->input( 'Aidedirecte.lib_aide', array( 'label' => required( __d( 'action', 'Action.lib_aide', true ) ), 'type' => 'select', 'options' => $actions, 'empty' => true )  ); ?>
		<?php echo $form->input( 'Aidedirecte.date_aide', array( 'label' => required( __d( 'action', 'Action.date_aide', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+10, 'minYear'=>date('Y')-10 , 'empty' => true)  ); ?>
	</fieldset>

	<?php echo $form->submit( 'Enregistrer' );?>

	<?php echo $form->end();?>
</div>
<div class="clearer"><hr /></div>