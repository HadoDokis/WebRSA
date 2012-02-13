<?php
	$this->pageTitle =  __d( 'decisiondossierpcg66', "Decisionsdossierspcgs66::{$this->action}", true );

	echo $this->element( 'dossier_menu', array( 'id' => $dossier_id ) );
?>

<div class="with_treemenu">
	<?php
		echo $xhtml->tag( 'h1', $this->pageTitle );
		echo $form->create( 'Decisiondossierpcg66', array( 'type' => 'post', 'id' => 'decisiondossierpcg66form', 'url' => Router::url( null, true ) ) );
	?>

	<?php
// 	debug( $decisiondossierpcg66 );
		echo $default2->view(
			$decisiondossierpcg66,
			array(
				'Decisionpdo.libelle',
				'Decisiondossierpcg66.commentairetechnicien',
				'Decisiondossierpcg66.datepropositiontechnicien',
				'Decisiondossierpcg66.avistechnique' => array( 'type' => 'boolean' ),
				'Decisiondossierpcg66.dateavistechnique',
				'Decisiondossierpcg66.commentaireavistechnique',
				'Decisiondossierpcg66.validationproposition' => array( 'type' => 'boolean' ),
				'Decisiondossierpcg66.retouravistechnique' => array( 'type' => 'boolean' ),
				'Decisiondossierpcg66.vuavistechnique' => array( 'type' => 'boolean' ),
				'Decisiondossierpcg66.datevalidation',
				'Decisiondossierpcg66.commentairevalidation',
				'Decisiondossierpcg66.commentaire' => array( 'label' => 'Commentaire global : ' ),
			),
			array(
				'class' => 'aere'
			)
		);
	?>
	
	<?php 
		echo "<h2>Pièces liées au dossier</h2>";
		echo $fileuploader->results( Set::classicExtract( $decisiondossierpcg66['Dossierpcg66'], 'Fichiermodule' ) );
	?>
	
</div>
	<div class="submit">
		<?php

			echo $form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>
	<?php echo $form->end();?>
<div class="clearer"><hr /></div>