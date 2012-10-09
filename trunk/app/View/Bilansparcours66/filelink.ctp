<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $this->Xhtml->css( array( 'fileuploader' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $this->Html->script( 'fileuploader.js' );
	}

	$this->pageTitle =  __d( 'bilanparcours66', "Bilansparcours66::{$this->action}" );
	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );

?>
<div class="with_treemenu">
	<?php
		echo $this->Xhtml->tag( 'h1', $this->pageTitle );
		echo $this->Fileuploader->element( 'Bilanparcours66', $fichiers, $bilanparcours66, $options['Bilanparcours66']['haspiecejointe'] );
	?>
</div>
<div class="clearer"><hr /></div>