<?php
	$cohorteFormId = Inflector::camelize( "{$this->request->params['controller']}_{$this->request->params['action']}_cohorte" );
	$paramDate = array(
		'domain' => null,
		'minYear_from' => '2009',
		'maxYear_from' => date( 'Y' ) + 1,
		'minYear_to' => '2009',
		'maxYear_to' => date( 'Y' ) + 4
	);
?>
<?php $this->start( 'custom_search_filters' );?>
	<fieldset>
		<legend>Recherche par affectation</legend>
		<?php echo $this->Form->input( 'Search.Referent.filtrer', array( 'type' => 'checkbox', 'label' => 'Filtrer par désignation' ) );?>
		<fieldset class="invisible" id="SearchFiltreReferent">
			<?php
				echo $this->Form->input( 'Search.Referent.designe', array( 'type' => 'radio', 'options' => $options['Referent']['designe'], 'legend' => false, 'separator' => '<br/>' ) );
				echo $this->Form->input( 'Search.Referent.id', array( 'label' => 'Nom du référent', 'type' => 'select', 'options' => $options['PersonneReferent']['referent_id'], 'empty' => true ) );
				echo $this->Allocataires->SearchForm->dateRange( 'Search.PersonneReferent.dddesignation', $paramDate );
			?>
		</fieldset>
		<?php
			echo $this->Allocataires->SearchForm->dependantCheckboxes( 'Search.Personne.situation', array( 'options' => $options['Personne']['situation'], 'domain' => $this->request->params['controller'] ) );
			echo $this->Form->input( 'Search.Dossier.transfere', array( 'label' => 'Dossier transféré ?', 'empty' => true, 'options' => $options['exists'] ) );
		?>
	</fieldset>
	<?php
		echo $this->Allocataires->SearchForm->dateRange( 'Search.Orientstruct.date_valid', $paramDate );
	?>
<?php $this->end();?>

<?php
	// Boutons "Tout activer"
	$buttons = null;
	if( isset( $results ) ) {
		$buttons = $this->Form->button( 'Tout activer', array( 'type' => 'button', 'onclick' => "return toutChoisir( $( '{$cohorteFormId}' ).getInputs( 'radio' ), '1', true );" ) );
		$buttons .= $this->Form->button( 'Tout désactiver', array( 'type' => 'button', 'onclick' => "return toutChoisir( $( '{$cohorteFormId}' ).getInputs( 'radio' ), '0', true );" ) );
	}

	echo $this->element(
		'ConfigurableQuery/cohorte',
		array(
			'customSearch' => $this->fetch( 'custom_search_filters' ),
			'exportcsv' => array( 'action' => 'exportcsv_affectation93' ),
			'afterResults' => $buttons,
			'modelName' => 'Personne'
		)
	);
?>
<?php if( isset( $results ) && !empty( $results ) ):?>
<script type="text/javascript">
	document.observe( "dom:loaded", function() {
		try {
			observeDisableFieldsetOnCheckbox(
				'SearchReferentFiltrer',
				'SearchFiltreReferent',
				false
			);
		} catch( e ) {
			console.log( e );
		}

		try {
			observeDisableFieldsOnRadioValue(
				'PersonnesReferentsCohorteAffectation93Form',
				'data[Search][Referent][designe]',
				[ 'SearchReferentId', 'SearchPersonneReferentDddesignation' ],
				[ '1' ],
				true
			);
		} catch( e ) {
			console.log( e );
		}

		// On désactive le select du référent si on ne choisit pas de valider
		<?php foreach( array_keys( $results ) as $index ):?>
		try {
			observeDisableFieldsOnRadioValue(
				'<?php echo $cohorteFormId;?>',
				'data[Cohorte][<?php echo $index;?>][PersonneReferent][active]',
				[ 'Cohorte<?php echo $index;?>PersonneReferentReferentId' ],
				[ '1' ],
				true
			);
		} catch( e ) {
			console.log( e );
		}
		<?php endforeach;?>
	} );
</script>
<?php endif;?>