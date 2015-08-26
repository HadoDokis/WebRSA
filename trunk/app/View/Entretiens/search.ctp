<?php
    $this->pageTitle = 'Recherche par entretiens (nouveau)';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
	}

	echo $this->Default3->titleForLayout();

    echo '<ul class="actionMenu"><li>'.$this->Xhtml->link(
        $this->Xhtml->image(
            'icons/application_form_magnify.png',
            array( 'alt' => '' )
        ).' Formulaire',
        '#',
        array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'EntretiensSearchForm' ).toggle(); return false;" )
    ).'</li></ul>';
?>
<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}
?>

<?php echo $this->Xform->create( null, array( 'type' => 'post', 'action' => $this->action, 'id' => 'EntretiensSearchForm', 'class' => ( isset( $results ) ? 'folded' : 'unfolded' ) ) );?>
	<?php
		echo $this->Allocataires->blocAllocataire( array( 'prefix' => 'Search', 'options' => $options ) );
		echo $this->Allocataires->blocAdresse( array( 'prefix' => 'Search', 'options' => $options ) );
		echo $this->Allocataires->blocDossier( array( 'prefix' => 'Search', 'options' => $options ) );
	?>
	<fieldset>
		<legend>Filtrer par Entretiens</legend>
		<?php
			echo $this->Default2->subform(
				array(
					'Search.Entretien.arevoirle' => array( 'label' => __d( 'entretien', 'Entretien.arevoirle' ), 'type' => 'date', 'dateFormat' => 'MY', 'empty' => true, 'minYear' => date( 'Y' ) - 2, 'maxYear' => date( 'Y' ) + 2 ),
					'Search.Entretien.structurereferente_id' => array( 'label' => __d( 'entretien', 'Entretien.structurereferente_id' ), 'empty' => true, 'options' => $options['PersonneReferent']['structurereferente_id'] ),
					'Search.Entretien.referent_id' => array( 'label' => __d( 'entretien', 'Entretien.referent_id' ), 'empty' => true, 'options' => $options['PersonneReferent']['referent_id']  ),
					'Search.Entretien.dateentretien' => array( 'type' => 'checkbox' )
				),
				array(
					'options' => $options
				)
			);

			echo $this->Html->tag(
					'fieldset',
					$this->Html->tag( 'legend', __m( 'Search.Entretien.dateentretien' ) )
					.$this->Default2->subform(
					array(
						'Search.Entretien.dateentretien_from' => array( 'label' => __m( 'Search.Entretien.dateentretien_from' ), 'empty' => true, 'type' => 'date', 'dateFormat' => 'DMY', 'minYear' => 2009, 'maxYear' => date('Y')+1 ),
						'Search.Entretien.dateentretien_to' => array( 'label' => __m( 'Search.Entretien.dateentretien_to' ), 'empty' => true, 'type' => 'date', 'dateFormat' => 'DMY', 'minYear' => 2009, 'maxYear' => date('Y')+1 ),
					),
					array(
						'options' => $options
					)
				)
			);
		?>
	</fieldset>

	<?php
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
//	echo $this->Search->observeDisableFormOnSubmit( 'Search' );
?>

<script type="text/javascript">
	document.observe( "dom:loaded", function() {
		observeDisableFieldsetOnCheckbox( 'SearchEntretienDateentretien', $( 'SearchEntretienDateentretienFromDay' ).up( 'fieldset' ), false );
		dependantSelect( 'SearchEntretienReferentId', 'SearchEntretienStructurereferenteId' );
	} );
</script>
<?php if( isset( $results ) ): ?>
	<?php
		echo $this->Html->tag( 'h2', 'Résultats de la recherche', array( 'class' => 'noprint' ) );

		echo $this->Default3->configuredindex(
			$results,
			array(
				'options' => $options
			)
		);

		// FIXME: $this->element( 'search_footer' );
	?>

	<ul class="actionMenu">
		<li><?php
			echo $this->Xhtml->printLinkJs(
				'Imprimer le tableau',
				array(
					'onclick' => 'printit(); return false;',
					'class' => 'noprint'
				)
			);
		?></li>
		<li><?php
			echo $this->Xhtml->exportLink(
				'Télécharger le tableau',
				array( 'controller' => 'entretiens', 'action' => 'exportcsv' ) + Hash::flatten( $this->request->data, '__' ),
				$this->Permissions->check( 'entretiens', 'exportcsv' )
			);
		?></li>
	</ul>
<?php endif;?>