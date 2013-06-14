<?php

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $this->Xhtml->css( array( 'fileuploader' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $this->Html->script( 'fileuploader.js' );
	}

	$this->pageTitle =  __d( 'actioncandidat_personne', "ActionscandidatsPersonnes::{$this->action}" );
?>
<?php
	echo $this->Xhtml->tag( 'h1', $this->pageTitle );
	echo $this->Fileuploader->element( 'ActioncandidatPersonne', $fichiers, $actioncandidat_personne, $options['ActioncandidatPersonne']['haspiecejointe'] );
?>