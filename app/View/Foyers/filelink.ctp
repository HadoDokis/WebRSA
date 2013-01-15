<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $this->Xhtml->css( array( 'fileuploader' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $this->Html->script( 'fileuploader.js' );
	}
	$this->pageTitle =  __d( 'foyer', "Foyers::{$this->action}", true );
	echo $this->element( 'dossier_menu', array( 'dossier_id' => $dossier_id ) );
?>
<div class="with_treemenu">
	<?php
		echo $this->Xhtml->tag( 'h1', $this->pageTitle );
		echo $this->Fileuploader->element( 'Foyer', $fichiers, $foyer, $options['Foyer']['haspiecejointe'] );
	?>
</div>
<div class="clearer"><hr /></div>