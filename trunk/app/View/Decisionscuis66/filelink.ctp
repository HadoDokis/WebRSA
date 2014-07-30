<?php
	$this->pageTitle =  __d( 'decisioncui66', "Decisionscuis66::{$this->action}" );

	echo $this->Xhtml->tag( 'h1', $this->pageTitle );
	echo $this->Fileuploader->element( 'Decisioncui66', $fichiers, $decisioncui66, $options['Decisioncui66']['haspiecejointe'] );
?>