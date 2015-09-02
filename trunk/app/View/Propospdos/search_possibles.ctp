<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
	}

	$paramDate = array(
		'minYear_from' => '2009',
		'maxYear_from' => date( 'Y' ) + 1,
		'minYear_to' => '2009',
		'maxYear_to' => date( 'Y' ) + 5
	);


	//$this->pageTitle = 'Recherche par PDOs (nouveau)';
	echo $this->Default3->titleForLayout();

    echo '<ul class="actionMenu"><li>'.$this->Xhtml->link(
        $this->Xhtml->image(
            'icons/application_form_magnify.png',
            array( 'alt' => '' )
        ).' Formulaire',
        '#',
        array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'PropospdosSearchPossiblesForm' ).toggle(); return false;" )
    ).'</li></ul>';
?>
<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}

	echo $this->Xform->create( null, array( 'type' => 'post', 'action' => $this->action, 'id' => 'PropospdosSearchPossiblesForm', 'class' => ( isset( $results ) ? 'folded' : 'unfolded' ) ) );
	echo $this->Allocataires->blocAllocataire( array( 'prefix' => 'Search', 'options' => $options ) );
	echo $this->Allocataires->blocAdresse( array( 'prefix' => 'Search', 'options' => $options ) );
	echo $this->Allocataires->blocDossier( array( 'prefix' => 'Search', 'options' => $options ) );

	echo $this->Allocataires->blocReferentparcours( array( 'prefix' => 'Search', 'options' => $options ) );
	echo $this->Allocataires->blocPagination( array( 'prefix' => 'Search', 'options' => $options ) );
	echo $this->Allocataires->blocScript( array( 'prefix' => 'Search', 'options' => $options ) );
?>
<div class="submit noprint">
	<?php echo $this->Xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
	<?php echo $this->Xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
</div>

<?php
	echo $this->Xform->end();
?>

<?php
	if( isset( $results ) ) {
		echo $this->Html->tag( 'h2', 'Résultats de la recherche', array( 'class' => 'noprint' ) );

		echo $this->Default3->configuredindex(
			$results,
			array(
				'options' => $options
			)
		);

		echo $this->element( 'search_footer', array( 'modelName' => 'Personne', 'url' => array( 'action' => 'exportcsv_possibles' ) ) );
	}
?>
