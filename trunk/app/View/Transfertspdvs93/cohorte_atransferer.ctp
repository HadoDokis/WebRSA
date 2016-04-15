<?php echo $this->Html->script( array( 'prototype.event.simulate.js' ), array( 'inline' => false ) );?>
<?php $this->start( 'custom_search_filters' );?>
<fieldset>
	<legend>Filtrer par orientation</legend>
	<?php
		$paramDate = array(
			'domain' => null,
			'minYear_from' => '2009',
			'maxYear_from' => date( 'Y' ) + 1,
			'minYear_to' => '2009',
			'maxYear_to' => date( 'Y' ) + 4
		);
		echo $this->Allocataires->SearchForm->dateRange( 'Search.Orientstruct.date_valid', $paramDate );

		echo $this->Default3->subform(
			array(
				'Search.Orientstruct.typeorient_id' => array( 'empty' => true, 'required' => false ),
			),
			array( 'options' => array( 'Search' => $options ) )
		);

		// id du formulaire de cohorte
		$cohorteFormId = Inflector::camelize( "{$this->request->params['controller']}_{$this->request->params['action']}_cohorte" );

		// Boutons "Tout cocher"
		$buttons = null;
		if( isset( $results ) ) {
			$buttons = $this->Form->button( 'Tout valider', array( 'type' => 'button', 'onclick' => "return toutChoisir( $( '{$cohorteFormId}' ).getInputs( 'radio' ), '1', true );" ) );
			$buttons .= $this->Form->button( 'Tout mettre en attente', array( 'type' => 'button', 'onclick' => "return toutChoisir( $( '{$cohorteFormId}' ).getInputs( 'radio' ), '0', true );" ) );
		}
	?>
</fieldset>
<?php $this->end();?>
<?php
	echo $this->element(
		'ConfigurableQuery/cohorte',
		array(
			'customSearch' => $this->fetch( 'custom_search_filters' ),
			'modelName' => 'Dossier',
			'exportcsv' => false,
			'afterResults' => $buttons
		)
	);
?>
<?php if( isset( $results ) ): ?>
<?php $structuresreferentes_dst_ids = (array)Hash::get( $options, 'Transfertpdv93.structurereferente_dst_id' ); ?>
<script type="text/javascript">
	document.observe( "dom:loaded", function() {
		<?php foreach( array_keys( $results ) as $index ):?>
			<?php
				$numcom = $results[$index]['Adresse']['numcom'];
				$typeorient_id = $results[$index]['Orientstruct']['typeorient_id'];
				$structuresreferentes = array();
				if( isset( $structuresreferentes_dst_ids[$numcom][$typeorient_id] ) ) {
					$structuresreferentes = $structuresreferentes_dst_ids[$numcom][$typeorient_id];
				}
			?>
			var structuresreferentes = <?php echo json_encode( $structuresreferentes );?>;
			var select = new Element( 'select' );
			$(select).insert( { bottom: new Element( 'option', { 'value': '' } ) } );

			for( var key in structuresreferentes ) {
				if( structuresreferentes.hasOwnProperty( key ) ) {
					var option = Element( 'option', { 'value': key, 'title': structuresreferentes[key] } ).update( structuresreferentes[key] );
					$(select).insert( { bottom: option } );

				}
			}

			$( 'Cohorte<?php echo $index;?>Transfertpdv93StructurereferenteDstId' ).update( $(select).innerHTML );

			observeDisableFieldsOnRadioValue(
				'<?php echo $cohorteFormId;?>',
				'data[Cohorte][<?php echo $index;?>][Transfertpdv93][action]',
				[ 'Cohorte<?php echo $index;?>Transfertpdv93StructurereferenteDstId' ],
				1,
				true
			);

			var selected = '<?php echo Hash::get($this->request->data, "Cohorte.{$index}.Transfertpdv93.structurereferente_dst_id")?>';
			$$( 'select#Cohorte<?php echo $index;?>Transfertpdv93StructurereferenteDstId option' ).each( function( option ) {
				if( $(option).value === selected ) {
					$(option).selected = 'selected';
				}
			} );
		<?php endforeach;?>
	} );
</script>
<?php endif;?>