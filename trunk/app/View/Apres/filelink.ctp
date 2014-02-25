<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
		echo $this->Html->css( array( 'fileuploader' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
		echo $this->Html->script( 'fileuploader.js' );
	}
	$this->modelClass = Inflector::classify( $this->request->params['controller'] );
	$this->pageTitle =  __d( 'apre', "Apres::{$this->action}" );

?>
<?php
	echo $this->Xhtml->tag( 'h1', $this->pageTitle );
	echo $this->Fileuploader->element( $this->modelClass, $fichiers, $apre, $options['haspiecejointe'] );
?>