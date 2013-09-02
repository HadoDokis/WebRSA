<?php
	// Filtre
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo '<ul class="actionMenu"><li>'.$this->Xhtml->link(
		$this->Xhtml->image(
			'icons/application_form_magnify.png',
			array( 'alt' => '' )
		).' Formulaire',
		'#',
		array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "var form = $$( 'form' ); form = form[0]; $( form ).toggle(); return false;" )
	).'</li></ul>';

	// Filtre
	echo $this->Form->create( null, array( 'type' => 'post', 'url' => array( 'controller' => $this->request->params['controller'], 'action' => $this->request->params['action'] ), 'id' => 'Search', 'class' => ( ( is_array( $this->request->data ) && !empty( $this->request->data ) && isset( $this->request->data['Search']['active'] ) ) ? 'folded' : 'unfolded' ) ) );

	echo $this->Form->input( 'Search.active', array( 'type' => 'hidden', 'value' => true ) );

	echo $this->Search->blocAllocataire( array(), 'Search' );
	echo $this->Search->toppersdrodevorsa( $options['toppersdrodevorsa'], 'Search.Calculdroitrsa.toppersdrodevorsa' );

	echo $this->Search->date( 'Search.Orientstruct.date_valid' );
// 	echo $this->Form->input( 'Search.Dossier.dernier', array( 'label' => 'Uniquement la dernière demande RSA pour un même allocataire', 'type' => 'checkbox' ) );
	echo $this->Search->blocAdresse( $options['mesCodesInsee'], $options['cantons'], 'Search' );

	// TODO: dans la visualisation
//	echo $this->Form->input( 'Search.Adresse.departement', array( 'label' => 'Département de la nouvelle adresse', 'type' => 'select', 'options' => $options['departementsnvadresses'], 'empty' => true ) );

	echo $this->Search->blocDossier( $options['etatdosrsa'], 'Search' );
// 	echo $this->Search->etatdosrsa( $options['etatdosrsa'], 'Search.Situationdossierrsa.etatdosrsa' );
    
    if( $this->action == 'transferes' ) {
        echo $this->Search->date( 'Search.Transfertpdv93.created', 'Dates de transfert' );
    }

	echo $this->Form->input( 'Search.Orientstruct.typeorient_id', array( 'label' => 'Type d\'orientation', 'type' => 'select', 'empty' => true, 'options' => $options['typesorients'] ) );

	echo $this->Search->paginationNombretotal( 'Search.Pagination.nombre_total' );

	echo $this->Form->submit( __( 'Search' ) );
	echo $this->Form->end();
?>