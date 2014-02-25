<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
	}

	echo $this->Xhtml->tag( 'h1', $title_for_layout );
	echo '<ul class="actionMenu"><li>'.$this->Xhtml->link(
		$this->Xhtml->image(
			'icons/application_form_magnify.png',
			array( 'alt' => '' )
		).' Formulaire',
		'#',
		array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
	).'</ul>';

	echo $this->Form->create( null, array( 'type' => 'post', 'id' => 'Search', 'class' => ( !empty( $this->request->data ) ? 'folded' : 'unfolded' ) ) );
	// TODO: autre filtre: groupement de cantons + présentation
	echo $this->Search->blocAdresse( $mesCodesInsee, $cantons, null, false );
	echo $this->Form->input( 'Search.serviceinstructeur', array( 'type' => 'select', 'options' => $servicesinstructeurs, 'empty' => true, 'label' => 'Service instructeur' ) );
	echo $this->Form->input( 'Search.annee', array( 'type' => 'select', 'options' => array_combine( range( date( 'Y' ), 2009, -1 ), range( date( 'Y' ), 2009, -1 ) ), 'label' => 'Année' ) );
	echo $this->Xhtml->tag(
		'div',
		$this->Form->submit( 'Rechercher', array( 'div' => false, 'type' => 'submit' ) )
		.' '.$this->Form->submit( 'Réinitialiser', array( 'div' => false, 'type'=>'reset' ) ),
		array( 'class' => 'submit noprint' )
	);
	echo $this->Form->end();
?>