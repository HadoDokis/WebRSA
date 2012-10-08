<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $passages[0]['Dossierep']['personne_id']) );?>

<div class="with_treemenu">
	<h1><?php echo $this->pageTitle = 'Visualisation d\'un dossier d\'commission d\'EP';?></h1>
	<?php
		debug( $passages );
	?>
</div>
<div class="clearer"><hr /></div>