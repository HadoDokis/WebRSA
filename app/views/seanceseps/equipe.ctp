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
// debug( $demandesreorient );
	echo $default->index(
		$demandesreorient,
		array(
			'Demandereorient.urgent' => array( 'type' => 'boolean' ),
			"Demandereorient.accordconcertation",
			"NvTypeorient.lib_type_orient" => array( 'domain' => 'decisionreorient' ),
			"NvStructurereferente.lib_struc" => array( 'domain' => 'decisionreorient' ),
			"NvReferent.nom_complet" => array( 'domain' => 'decisionreorient' ),
			"Decisionreorient{$step}.decision" => array( 'input' => 'select', 'empty' => true, 'domain' => 'decisionreorient' ),
			"Decisionreorient{$step}.nv_typeorient_id" => array( 'input' => 'select', 'empty' => true, 'domain' => 'decisionreorient' ),
			"Decisionreorient{$step}.nv_structurereferente_id" => array( 'input' => 'select', 'empty' => true, 'domain' => 'decisionreorient' ),
			"Decisionreorient{$step}.nv_referent_id" => array( 'input' => 'select', 'empty' => true, 'domain' => 'decisionreorient' ),
			"Decisionreorient{$step}.created" => array( 'domain' => 'decisionreorient' ),
		),
		array(
			'cohorte' => true,
			'hidden' => array(
				"Decisionreorient{$step}.id",
				"Decisionreorient{$step}.demandereorient_id" => array( 'valuePath' => 'Demandereorient.id' ),
// 				"Decisionreorient{$step}.ep_id" => array( 'valuePath' => 'Ep.id' ),
				"Decisionreorient{$step}.etape" => array( 'value' => ( $step == 'equipe' ? 'ep' : 'cg' ) ),
			),
			'options' => $options,
			'tooltip' => array(
				"VxTypeorient.lib_type_orient" => array( 'domain' => 'decisionreorient' ),
				"VxStructurereferente.lib_struc" => array( 'domain' => 'decisionreorient' ),
				"VxReferent.nom_complet" => array( 'domain' => 'decisionreorient' ),
				'Motifdemreorient.name',
			),
			'paginate' => 'Demandereorient',
			'groupColumns' => array(
				$pvstep => array( 1, 2, 3, 4 ),
				$step => array( 5, 6, 7, 8 )
			)
		)
	);

//   echo $default->button(
//         'back',
//         array(
//             'controller' => 'eps',
//             'action'     => 'liste',
//             'Search__active' => 1
//         ),
//         array(
//             'id' => 'Back'
//         )
//     );
?>