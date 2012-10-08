<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $this->Xhtml->css( array( 'fileuploader' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $this->Html->script( 'fileuploader.js' );
	}

	$this->pageTitle =  __d( 'nonoriente66', "Nonorientes66::{$this->action}" );
	echo $this->element( 'dossier_menu', array( 'personne_id' => $personneId ) );
?>
<div class="with_treemenu">
	<?php
		echo $this->Xhtml->tag( 'h1', $this->pageTitle );
		echo $this->Fileuploader->element( 'Nonoriente66', $fichiers, $nonoriente66, $options['haspiecejointe'] );
	?>
</div>
<div class="clearer"><hr /></div>