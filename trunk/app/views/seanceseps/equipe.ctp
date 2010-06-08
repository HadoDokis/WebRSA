<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'decisionreorient', "Decisionsreorients::{$this->action}", true )
    )
?>
<?php echo $javascript->link( 'dependantselect.js' ); ?>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php foreach( array_keys( $demandesreorient ) as $key ):?>
		dependantSelect( '<?php echo "Decisionreorient{$step}{$key}NvStructurereferenteId";?>', '<?php echo "Decisionreorient{$step}{$key}NvTypeorientId";?>' );
		dependantSelect( '<?php echo "Decisionreorient{$step}{$key}NvReferentId";?>', '<?php echo "Decisionreorient{$step}{$key}NvStructurereferenteId";?>' );
		<?php endforeach;?>
	});
</script>

<?php
	/// FIXME: contrôleur / modèle
	if( $step == 'equipe' ) {
		$pvstep = 'referent';
	}
	else if( $step == 'conseil' ) {
		$pvstep = 'equipe';
	}

	foreach( $demandesreorient as $key => $decisionreorient ) {
		/// Valeurs par défaut
		if( ( $step == 'equipe' ) || ( $step == 'conseil' ) ) {
			if( empty( $this->data ) && is_null( $demandesreorient[$key]["Decisionreorient{$step}"]['decision'] ) ) {
				foreach( array( 'decision', 'nv_typeorient_id', 'nv_structurereferente_id', 'nv_referent_id' ) as $column ) {
					$demandesreorient[$key]["Decisionreorient{$step}"][$column] = Set::classicExtract( $decisionreorient, "Decisionreorient{$pvstep}.{$column}" );
				}
			}
		}

		$demandesreorient[$key]["Decisionreorient{$step}"]['nv_structurereferente_id'] = $demandesreorient[$key]["Decisionreorient{$step}"]['nv_typeorient_id'].'_'.$demandesreorient[$key]["Decisionreorient{$step}"]['nv_structurereferente_id'];
	}

	echo $default->index(
		$demandesreorient,
		array(
			'Demandereorient.urgent' => array( 'type' => 'boolean' ),
// 			"Decisionreorient{$pvstep}.decision" => array( 'type' => 'boolean' ),
// 			"Decisionreorient{$pvstep}.Typeorient.lib_type_orient",
// 			"Decisionreorient{$pvstep}.Structurereferente.lib_struc",
// 			"Decisionreorient{$pvstep}.Referent.nom_complet",
			"Decisionreorient{$step}.decision" => array( 'input' => 'select', 'empty' => true ),
			"Decisionreorient{$step}.nv_typeorient_id" => array( 'input' => 'select', 'empty' => true ),
			"Decisionreorient{$step}.nv_structurereferente_id" => array( 'input' => 'select', 'empty' => true ),
			"Decisionreorient{$step}.nv_referent_id" => array( 'input' => 'select', 'empty' => true ),
			"Decisionreorient{$step}.created",
		),
		array(
			'cohorte' => true,
			'hidden' => array(
				"Decisionreorient{$step}.id",
				"Decisionreorient{$step}.demandereorient_id" => array( 'valuePath' => 'Demandereorient.id' ),
				"Decisionreorient{$step}.ep_id" => array( 'valuePath' => 'Ep.id' ),
				"Decisionreorient{$step}.rolereorient" => array( 'value' => $step ),
			),
			'options' => $options,
/*			'tooltip' => array(
				'Reforigine.nom_complet',
// 				'Reforigine.Structurereferente.lib_struc', // FIXME
				'Motifdemreorient.name',
			),*/
			'paginate' => 'Demandereorient',
			'groupColumns' => array(
				$pvstep => array( 1, 2, 3, 4 ),
				$step => array( 5, 6, 7, 8 )
			)
		)
	);

  echo $default->button(
        'back',
        array(
            'controller' => 'eps',
            'action'     => 'liste',
            'Search__active' => 1
        ),
        array(
            'id' => 'Back'
        )
    );
?>