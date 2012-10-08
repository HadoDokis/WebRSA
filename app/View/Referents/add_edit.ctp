<?php
	$this->pageTitle = 'Référents';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>

<h1><?php echo $this->pageTitle;?></h1>

<?php
	if( $this->action == 'add' ) {
		echo $this->Form->create( 'Referent', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
		echo '<div>';
		echo $this->Form->input( 'Referent.id', array( 'type' => 'hidden' ) );
		echo '</div>';
	}
	else {
		echo $this->Form->create( 'Referent', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
		echo '<div>';
		echo $this->Form->input( 'Referent.id', array( 'type' => 'hidden' ) );
		echo '</div>';
	}
?>

	<fieldset>
		<?php
			echo $this->Default->subform(
				array(
					'Referent.qual' => array( 'options' => $qual ),
					'Referent.nom',
					'Referent.prenom',
					'Referent.fonction',
					'Referent.numero_poste' => array( 'maxlength' => 10 ),
					'Referent.email',
					'Referent.actif' => array( 'label' => 'Actif ?', 'type' => 'radio', 'options' => $options['actif'] )
				)
			);
		?>
	</fieldset>
	<fieldset class="col2">
		<legend>Structures référentes</legend>
		<?php echo $this->Form->input( 'Referent.structurereferente_id', array( 'label' => required( false ), 'type' => 'select' , 'options' => $sr, 'empty' => true ) );?>
	</fieldset>

	<div class="submit">
		<?php
			echo $this->Xform->submit( 'Enregistrer', array( 'div' => false ) );
			echo $this->Xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>
<?php echo $this->Form->end();?>
