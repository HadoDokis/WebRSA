<?php
	$this->pageTitle =  __d( 'suspensioncui66', "Suspensionscuis66::{$this->action}" );

	echo $this->Xhtml->tag( 'h1', $this->pageTitle );
	echo $this->Fileuploader->element( 'Suspensioncui66', $fichiers, $suspensioncui66, $options['Suspensioncui66']['haspiecejointe'] );
?>