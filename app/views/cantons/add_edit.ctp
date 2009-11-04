<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Canton';?>

<h1><?php echo $this->pageTitle;?></h1>

<?php
	echo $form->create( 'Canton', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
    if( $this->action == 'edit' ) {
        echo $form->input( 'Canton.id', array( 'type' => 'hidden' ) );
    }

	echo $form->input( 'Canton.canton', array( 'label' => 'Nom du canton' ) );
	echo $form->input( 'Canton.typevoie', array( 'label' => 'Type de voie', 'type' => 'select', 'options' => $typesvoies, 'empty' => '' ) );
	echo $form->input( 'Canton.nomvoie', array( 'label' => 'Nom de voie' ) );
	echo $form->input( 'Canton.locaadr', array( 'label' => 'Localité' ) );
	echo $form->input( 'Canton.codepos', array( 'label' => 'Code postal' ) );
	echo $form->input( 'Canton.numcomptt', array( 'label' => 'Numéro de commune au sens INSEE' ) );

	echo $form->submit( 'Enregistrer' );
	echo $form->end();
?>
