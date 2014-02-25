<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
		echo $this->Html->css( array( 'fileuploader' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
		echo $this->Html->script( 'fileuploader.js' );
	}
	$this->pageTitle =  __d( 'foyer', "Foyers::{$this->action}", true );
?>
	<?php
		echo $this->Xhtml->tag( 'h1', $this->pageTitle );
		echo $this->Fileuploader->element( 'Foyer', $fichiers, $foyer, $options['Foyer']['haspiecejointe'] );
	?>