<?php
	$this->pageTitle =  __d( 'cui', "Cuis::{$this->action}" );

	echo $this->Xhtml->tag( 'h1', $this->pageTitle );
	echo $this->Fileuploader->element( 'Cui', $fichiers, $cui, $options['Cui']['haspiecejointe'] );
?>