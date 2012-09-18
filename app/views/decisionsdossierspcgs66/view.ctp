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
		echo $default2->view(
			$decisiondossierpcg66,
			array(
				'Decisionpdo.libelle',
				'Decisiondossierpcg66.commentairetechnicien',
				'Decisiondossierpcg66.datepropositiontechnicien',
				'Decisiondossierpcg66.datevalidation',
				'Decisiondossierpcg66.commentaire' => array( 'label' => 'Commentaire global : ' ),
                'Dossierpcg66.etatdossierpcg'
			),
			array(
				'class' => 'aere',
                'options' => $options
			)
		);
	?>
	
	<?php 
		echo "<h2>Pièces liées à la décision du dossier</h2>";
		echo $fileuploader->results( Set::classicExtract( $decisiondossierpcg66, 'Fichiermodule' ) );
	?>
	
</div>
	<div class="submit">
		<?php

			echo $form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>
	<?php echo $form->end();?>
<div class="clearer"><hr /></div>