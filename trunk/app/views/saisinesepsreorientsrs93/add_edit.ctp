<h1> <?php echo $this->pageTitle = 'Saisine de demande de réorientation des structures référentes'; ?> </h1>
<?php
	// TODO
// 	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );

// 	if( $this->action == 'add' ) {
// 		$this->pageTitle = 'Orientation';
// 	}
// 	else {
// 		$this->pageTitle = 'Édition de l\'orientation';
// 	}
?>

<?php echo $javascript->link( 'dependantselect.js' ); ?>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		dependantSelect( 'Saisineepreorientsr93StructurereferenteId', 'Saisineepreorientsr93TypeorientId' );

		try { $( 'Saisineepreorientsr93StructurereferenteId' ).onchange(); } catch(id) { }
	});
</script>

<?php
	echo $default->form(
		array(
			'Saisineepreorientsr93.orientstruct_id' => array( 'type' => 'hidden' ),
			'Saisineepreorientsr93.typeorient_id',
			'Saisineepreorientsr93.structurereferente_id',
			'Saisineepreorientsr93.motifreorient_id',
			'Saisineepreorientsr93.commentaire',
			'Saisineepreorientsr93.accordaccueil',
			'Saisineepreorientsr93.desaccordaccueil',
			'Saisineepreorientsr93.accordallocataire',
			'Saisineepreorientsr93.urgent',
		),
		array(
			'options' => $options
		)
	);
?>