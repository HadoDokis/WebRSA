<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $this->Xhtml->css( array( 'fileuploader' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $this->Html->script( 'fileuploader.js' );
	}

    $this->pageTitle = __d( 'offreinsertion', "Offresinsertion::{$this->action}" );
?>

    <?php
        echo "<h2>Liste des pièces liées à l'action '".Set::classicExtract( $actioncandidat, 'Actioncandidat.name' )."'</h2>";
		echo $this->Fileuploader->element( 'Actioncandidat', $fichiers, $actioncandidat, $options['Actioncandidat']['haspiecejointe'] );
   		
		$urlParams = Hash::flatten( $this->request->params['named'], '__' );

		echo $this->Default->button(
			'back',
			array_merge(
				array(
					'controller' => 'offresinsertion',
					'action'     => 'index'
				),
				$urlParams
			),
			array(
				'id' => 'Back'
			)
		);
	?>
<div class="clearer"><hr /></div>