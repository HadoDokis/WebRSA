<?php
	$this->pageTitle =  __d( 'accompagnementcui66', "Accompagnementscuis66::{$this->action}" );

	echo $this->Xhtml->tag( 'h1', $this->pageTitle );
	echo $this->Fileuploader->element( 'Accompagnementcui66', $fichiers, $accompagnementcui66, $options['Accompagnementcui66']['haspiecejointe'] );
?>