<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'precoreorient', "Precosreorients::{$this->action}", true )
    )
?>
<?php echo $javascript->link( 'dependantselect.js' ); ?>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php foreach( array_keys( $precosreorient ) as $key ):?>
		dependantSelect( '<?php echo "Precoreorient{$step}{$key}StructurereferenteId";?>', '<?php echo "Precoreorient{$step}{$key}TypeorientId";?>' );
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

	foreach( $precosreorient as $key => $precoreorient ) {
		/// Valeurs par défaut
		if( ( $step == 'equipe' ) || ( $step == 'conseil' ) ) {
			if( empty( $this->data ) && is_null( $precosreorient[$key]["Precoreorient{$step}"]['accord'] ) ) {
				foreach( array( 'accord', 'typeorient_id', 'structurereferente_id', 'referent_id' ) as $column ) {
					$precosreorient[$key]["Precoreorient{$step}"][$column] = Set::classicExtract( $precoreorient, "Precoreorient{$pvstep}.{$column}" );
				}
			}
		}

		$precosreorient[$key]["Precoreorient{$step}"]['structurereferente_id'] = $precosreorient[$key]["Precoreorient{$step}"]['typeorient_id'].'_'.$precosreorient[$key]["Precoreorient{$step}"]['structurereferente_id'];
	}

	echo $default->index(
		$precosreorient,
		array(
			'Demandereorient.urgent' => array( 'type' => 'boolean' ),
			"Precoreorient{$pvstep}.accord" => array( 'type' => 'boolean' ),
			"Precoreorient{$pvstep}.Typeorient.lib_type_orient",
			"Precoreorient{$pvstep}.Structurereferente.lib_struc",
			"Precoreorient{$pvstep}.Referent.nom_complet",
			"Precoreorient{$step}.accord" => array( 'input' => 'checkbox' ),
			"Precoreorient{$step}.typeorient_id" => array( 'input' => 'select', 'empty' => true ),
			"Precoreorient{$step}.structurereferente_id" => array( 'input' => 'select', 'empty' => true ),
			"Precoreorient{$step}.referent_id" => array( 'input' => 'select', 'empty' => true ),
			"Precoreorient{$step}.created",
		),
		array(
			'cohorte' => true,
			'hidden' => array(
				"Precoreorient{$step}.id",
				"Precoreorient{$step}.demandereorient_id" => array( 'valuePath' => 'Demandereorient.id' ),
				"Precoreorient{$step}.ep_id" => array( 'valuePath' => 'Ep.id' ),
				"Precoreorient{$step}.rolereorient" => array( 'value' => $step ),
			),
			'options' => $options,
			'tooltip' => array(
				'Reforigine.nom_complet',
// 				'Reforigine.Structurereferente.lib_struc', // FIXME
				'Motifdemreorient.name',
			),
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

// 	debug( $precosreorient );
?>