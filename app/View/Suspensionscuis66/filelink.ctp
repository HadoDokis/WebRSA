<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
		echo $this->Html->css( array( 'fileuploader' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
		echo $this->Html->script( 'fileuploader.js' );
	}

	$this->pageTitle =  __d( 'suspensioncui66', "Suspensionscuis66::{$this->action}" );
?>
<?php
	echo $this->Xhtml->tag( 'h1', $this->pageTitle );
	echo $this->Fileuploader->element( 'Suspensioncui66', $fichiers, $suspensioncui66, $options['Suspensioncui66']['haspiecejointe'] );
?>