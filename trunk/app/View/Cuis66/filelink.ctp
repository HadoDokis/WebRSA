<?php
	$this->pageTitle =  __d( 'cuis66', "Cuis66::{$this->action}" );

	echo $this->Xhtml->tag( 'h1', $this->pageTitle );
	echo $this->Fileuploader->element( 'Cui', $fichiers, $record, $options['Cui']['haspiecejointe'] );

	echo $this->Observer->disableFormOnSubmit( 'cui66form' );
?>