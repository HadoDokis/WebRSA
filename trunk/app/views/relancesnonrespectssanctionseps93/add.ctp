<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id) );?>

<div class="with_treemenu">
	<h1><?php echo $this->pageTitle = 'Ajout d\'une relance';?></h1>

	<?php
		echo $xform->create();
		echo $xform->end( 'Enregistrer' );
	?>
</div>
<div class="clearer"><hr /></div>