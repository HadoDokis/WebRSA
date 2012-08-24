<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $xhtml->css( array( 'fileuploader' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $javascript->link( 'fileuploader.js' );
	}

	$this->pageTitle =  __d( 'offreinsertion', "Offresinsertion::{$this->action}", true );
?>
	<?php
		echo "<h2>Liste des pièces liées à l'action '".Set::classicExtract( $actioncandidat, 'Actioncandidat.name' )."'</h2>";
		echo $fileuploader->results( Set::classicExtract( $actioncandidat, 'Fichiermodule' ) );

		
		$urlParams = Set::flatten( $this->params['named'], '__' );

		echo $default->button(
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