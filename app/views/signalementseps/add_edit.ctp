<?php
	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );

	if( $this->action == 'add' ) {
		$this->pageTitle = 'Ajout d\'un signalement';
	}
	else {
		$this->pageTitle = 'Modification d\'un signalement';
	}
	$modelClassName = 'Signalementep'.Configure::read( 'Cg.departement' );
?>
<div class="with_treemenu">
	<h1> <?php echo $this->pageTitle; ?> </h1>

	<?php
		if( Configure::read( 'debug' ) > 0 ) {
			echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		}
	?>

	<?php
		echo $xform->create();

		if( empty( $this->data ) || !dateComplete( $this->data, "{$modelClassName}.date" ) ) {
			$defaultDate = date( 'Y-m-d' );
		}
		else {
			$defaultDate = $this->data[$modelClassName]['date'];
		}

		echo $default->subform(
			array(
				"{$modelClassName}.id" => array( 'type' => 'hidden' ),
				"{$modelClassName}.date" => array( 'selected' => $defaultDate ),
				"{$modelClassName}.motif",
			)
		);

		echo '<div class="submit">';
		echo $xform->submit( 'Enregistrer', array( 'div' => false ) );
		echo $xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
		echo '</div>';
		echo $xform->end();
	?>
</div>