<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Canton';?>

<h1><?php echo $this->pageTitle;?></h1>

<?php
	echo $form->create( 'Canton', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
	if( $this->action == 'edit' ) {
		echo $form->input( 'Canton.id', array( 'type' => 'hidden' ) );
	}

	echo $form->input( 'Canton.canton', array( 'label' => required( 'Nom du canton' ) ) );
	echo $form->input( 'Canton.zonegeographique_id', array( 'label' => required( 'Zone géographique associée' ), 'empty' => true ) );
	echo $form->input( 'Canton.typevoie', array( 'label' => 'Type de voie', 'type' => 'select', 'options' => $typesvoies, 'empty' => '' ) );
	echo $form->input( 'Canton.nomvoie', array( 'label' => 'Nom de voie' ) );
	echo $form->input( 'Canton.locaadr', array( 'label' => required( 'Localité' ) ) );
	echo $form->input( 'Canton.codepos', array( 'label' => 'Code postal' ) );
	echo $form->input( 'Canton.numcomptt', array( 'label' => required( 'Numéro de commune au sens INSEE' ) ) );

	echo '<div class="submit">';
		echo $xform->submit( 'Enregistrer', array( 'div' => false ) );
		echo $xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
	echo '</div>';

	echo $form->end();
?>
