<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $xhtml->css( array( 'fileuploader' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $javascript->link( 'fileuploader.js' );
	}

	$this->pageTitle =  __d( 'decisiondossierpcg66', "Decisionsdossierspcgs66::{$this->action}", true );
	echo $this->element( 'dossier_menu', array( 'id' => $dossier_id ) );
?>
<div class="with_treemenu">
	<?php
		echo $xhtml->tag( 'h1', $this->pageTitle );
		echo $fileuploader->element( 'Decisiondossierpcg66', $fichiers, $decisiondossierpcg66, $options['Decisiondossierpcg66']['haspiecejointe'] );
	?>
</div>
<div class="clearer"><hr /></div>