<?php
	$this->pageTitle =  __d( 'decisiondossierpcg66', "Decisionsdossierspcgs66::{$this->action}" );

	echo $this->element( 'dossier_menu', array( 'id' => $dossier_id ) );
?>

<div class="with_treemenu">
	<?php
		echo $this->Xhtml->tag( 'h1', $this->pageTitle );
		echo $this->Form->create( 'Decisiondossierpcg66', array( 'type' => 'post', 'id' => 'decisiondossierpcg66form', 'url' => Router::url( null, true ) ) );
	?>

	<?php
		echo $this->Default2->view(
			$decisiondossierpcg66,
			array(
				'Decisionpdo.libelle',
				'Decisiondossierpcg66.commentairetechnicien',
				'Decisiondossierpcg66.datepropositiontechnicien',
				'Decisiondossierpcg66.datevalidation',
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
		echo $this->Fileuploader->results( Set::classicExtract( $decisiondossierpcg66, 'Fichiermodule' ) );
	?>
	
</div>
	<div class="submit">
		<?php

			echo $this->Form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
		?>
	</div>
	<?php echo $this->Form->end();?>
<div class="clearer"><hr /></div>