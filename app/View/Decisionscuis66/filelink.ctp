<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $this->Xhtml->css( array( 'fileuploader' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $this->Html->script( 'fileuploader.js' );
	}

	$this->pageTitle =  __d( 'decisioncui66', "Decisionscuis66::{$this->action}" );
?>
<?php
	echo $this->Xhtml->tag( 'h1', $this->pageTitle );
	echo $this->Fileuploader->element( 'Decisioncui66', $fichiers, $decisioncui66, $options['Decisioncui66']['haspiecejointe'] );
?>