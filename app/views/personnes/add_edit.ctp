<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
	if( $this->action == 'add' ) {
		$this->pageTitle = 'Ajout d\'une personne';
	}
	else {
		$title = implode(
			' ',
			array(
				$this->data['Personne']['qual'],
				$this->data['Personne']['nom'],
				$this->data['Personne']['prenom'] )
		);

		$this->pageTitle = 'Édition de la personne « '.$title.' »';
		$foyer_id = $this->data['Personne']['foyer_id'];
	}
?>

<?php echo $this->element( 'dossier_menu', array( 'foyer_id' => $foyer_id ) );?>

<div class="with_treemenu">
	<h1><?php echo $this->pageTitle;?></h1>
	<?php
		if( $this->action == 'add' ) {
			echo $form->create( 'Personne', array( 'type' => 'post', 'url' => Router::url( null, true ) ));
		}
		else {
			echo $form->create( 'Personne', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
			echo '<div>';
			echo $form->input( 'Personne.id', array( 'type' => 'hidden' ) );
			echo $form->input( 'Prestation.id', array( 'type' => 'hidden', 'div' => 'div' ) );
			echo '</div>';
		}
	?>
	<div>
		<?php echo $form->input( 'Personne.foyer_id', array( 'type' => 'hidden', 'div' => 'div', 'value' => $foyer_id ) );?>
		<?php echo $form->input( 'Prestation.natprest', array( 'type' => 'hidden', 'value' => 'RSA' ) );?>
		<?php echo $form->input( 'Prestation.rolepers', array( 'label' => __d( 'prestation', 'Prestation.rolepers', true ), 'type' => 'select', 'empty' => true ) );?>
	</div>
	<?php include( '_form.ctp' );?>


	<div class="submit">
		<?php echo $form->submit( 'Enregistrer', array( 'div' => false ) );?>
		<?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
	</div>	<?php echo $form->end();?>
</div>
<div class="clearer"><hr /></div>