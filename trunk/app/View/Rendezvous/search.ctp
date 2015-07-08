<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}

	echo $this->Default3->titleForLayout();

	$departement = Configure::read( 'Cg.departement' );

	$actions = array(
		'/Dossiers/index/#toggleform' => array(
			'onclick' => '$( \'RendezvousSearchForm\' ).toggle(); return false;',
			'class' => 'search'
		)
	);

	echo $this->Default3->actions( $actions );
?>

<?php echo $this->Form->create( null, array( 'type' => 'post', 'url' => array( 'action' => 'search' ), 'id' => 'RendezvousSearchForm', 'class' => ( !empty( $this->request->params['named'] ) ? 'folded' : 'unfolded' ) ) );?>
	<?php
		echo $this->Allocataires->blocAllocataire( array( 'prefix' => 'Search', 'options' => $options ) );
		echo $this->Allocataires->blocAdresse( array( 'prefix' => 'Search', 'options' => $options ) );
		echo $this->Allocataires->blocDossier( array( 'prefix' => 'Search', 'options' => $options ) );
	?>
	<fieldset>
		<legend><?php echo __m( 'Search.Rendezvous' ); ?></legend>
		<?php
			// FIXME: fieldset
			echo $this->Form->input( 'Search.Rendezvous.statutrdv_id', array( 'label' => __m( 'Search.Rendezvous.statutrdv_id' ), 'type' => 'select', 'multiple' => 'checkbox', 'options' => $options['Rendezvous']['statutrdv_id'], 'empty' => false ) );
			echo $this->Form->input( 'Search.Rendezvous.structurereferente_id', array( 'label' => __m( 'Search.Rendezvous.structurereferente_id' ), 'type' => 'select', 'options' => $options['PersonneReferent']['structurereferente_id'], 'empty' => true ) );
			echo $this->Form->input( 'Search.Rendezvous.referent_id', array( 'label' => __m( 'Search.Rendezvous.referent_id' ), 'type' => 'select', 'options' => $options['PersonneReferent']['referent_id'], 'empty' => true ) );
			echo $this->Form->input( 'Search.Rendezvous.permanence_id', array( 'label' => __m( 'Search.Rendezvous.permanence_id' ), 'type' => 'select', 'options' => $options['Rendezvous']['permanence_id'], 'empty' => true ) );
			echo $this->Form->input( 'Search.Rendezvous.typerdv_id', array( 'label' => __m( 'Search.Rendezvous.typerdv_id' ), 'type' => 'select', 'options' => $options['Rendezvous']['typerdv_id'], 'empty' => true ) );

			// Thématiques du RDV
			if( isset( $options['Rendezvous']['thematiquerdv_id'] ) && !empty( $options['Rendezvous']['thematiquerdv_id'] ) ) {
				foreach( $options['Rendezvous']['thematiquerdv_id'] as $typerdv_id => $thematiques ) {
					$input = $this->Xform->input(
						'Search.Rendezvous.thematiquerdv_id',
						array(
							'type' => 'select',
							'multiple' => 'checkbox',
							'options' => $options['Rendezvous']['thematiquerdv_id'],
							'label' => __m( 'Search.Rendezvous.thematiquerdv_id' )
						)
					);
					echo $this->Xhtml->tag( 'fieldset', $input, array( 'id' => "CritererdvThematiquerdvId{$typerdv_id}", 'class' => 'invisible' ) );
				}
			}

			echo $this->SearchForm->dateRange( 'Search.Rendezvous.daterdv', array(
				'domain' => 'rendezvous', // FIXME
				'minYear_from' => 2009,
				'minYear_to' => 2009,
				'maxYear_from' => date( 'Y' ) + 1,
				'maxYear_to' => date( 'Y' ) + 1,
			) );
		?>
	</fieldset>
	<?php
		echo $this->Allocataires->blocReferentparcours( array( 'prefix' => 'Search', 'options' => $options ) );
		echo $this->Allocataires->blocPagination( array( 'prefix' => 'Search', 'options' => $options ) );
		echo $this->Allocataires->blocScript( array( 'prefix' => 'Search', 'options' => $options ) );
	?>
	<div class="submit noprint">
		<?php echo $this->Form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
		<?php echo $this->Form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>
<?php
	echo $this->Form->end();
	echo $this->Observer->disableFormOnSubmit();
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
	}
?>
<?php if( isset( $results ) && !empty( $results ) ): ?>
	<ul class="actionMenu">
		<li><?php
			echo $this->Xhtml->printLinkJs(
				'Imprimer le tableau', array( 'onclick' => 'printit(); return false;', 'class' => 'noprint' )
			);
		?></li>
		<li><?php
			echo $this->Xhtml->exportLink(
				'Télécharger le tableau', array( 'controller' => 'criteresrdv', 'action' => 'exportcsv' ) + Hash::flatten( $this->request->data, '__' ), $this->Permissions->check( 'criteresrdv', 'exportcsv' )
			);
		?></li>
	</ul>
<?php endif; ?>

<script type="text/javascript">
	// TODO
	document.observe("dom:loaded", function() {
		dependantSelect( 'SearchRendezvousReferentId', 'SearchRendezvousStructurereferenteId' );

		<?php if( isset( $options['Rendezvous']['thematiquerdv_id'] ) && !empty( $options['Rendezvous']['thematiquerdv_id'] ) ):?>
			<?php foreach( $options['Rendezvous']['thematiquerdv_id'] as $typerdv_id => $thematiques ):?>
				observeDisableFieldsetOnValue(
					'SearchRendezvousTyperdvId',
					'SearchRendezvousThematiquerdvId<?php echo $typerdv_id;?>',
					[ '<?php echo $typerdv_id;?>' ],
					false,
					true
				);
			<?php endforeach;?>
		<?php endif;?>
	});
</script>
<?php
	echo $this->Search->observeDisableFormOnSubmit( 'RendezvousSearchForm' );
?>