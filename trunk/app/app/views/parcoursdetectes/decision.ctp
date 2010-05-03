<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'parcoursdetecte', "Parcoursdetectes::{$this->action}", true ).' : '.Set::classicExtract( $ep, 'Ep.name' )
    );

	/// FIXME: contrôleur / modèle
	foreach( $parcoursdetectes as $key => $parcoursdetecte ) {
		/// Valeurs par défaut
		if( $step == 'equipe' ) {
			$defval = is_null( $parcoursdetectes[$key]["Decisionparcours{$step}"]['maintien'] );
			$defval = $defval && !Set::check( $this->data, "Decisionparcours{$step}.{$key}.maintien" );
			if( $defval ) {
				$parcoursdetectes[$key]["Decisionparcours{$step}"]['maintien'] = true;
			}
		}
		else if( $step == 'conseil' ) {
			if( empty( $this->data ) && is_null( $parcoursdetectes[$key]["Decisionparcours{$step}"]['maintien'] ) ) {
				foreach( array( 'maintien', 'typeorient_id', 'structurereferente_id', 'referent_id' ) as $column ) {
					$parcoursdetectes[$key]["Decisionparcours{$step}"][$column] = Set::classicExtract( $parcoursdetecte, "Decisionparcoursequipe.{$column}" );
				}
			}
		}

		$parcoursdetectes[$key]["Decisionparcours{$step}"]['structurereferente_id'] = $parcoursdetectes[$key]["Decisionparcours{$step}"]['typeorient_id'].'_'.$parcoursdetectes[$key]["Decisionparcours{$step}"]['structurereferente_id'];
	}

	echo $default->index(
		$parcoursdetectes,
		array(
			"Parcoursdetecte.created",
			"Decisionparcours{$step}.maintien" => array( 'input' => 'checkbox' ),
			"Decisionparcours{$step}.typeorient_id" => array( 'input' => 'select' ),
			"Decisionparcours{$step}.structurereferente_id" => array( 'input' => 'select' ),
			"Decisionparcours{$step}.referent_id" => array( 'input' => 'select' ),
			"Decisionparcours{$step}.created",
		),
		array(
			'cohorte' => true,
			'hidden' => array(
				"Decisionparcours{$step}.id",
				"Decisionparcours{$step}.ep_id" => array( 'valuePath' => 'Ep.id' ),
				"Decisionparcours{$step}.parcoursdetecte_id" => array( 'valuePath' => 'Parcoursdetecte.id' ),
				"Decisionparcours{$step}.roleparcours" => array( 'value' => $step ),
			),
			'options' => $options,
			'paginate' => 'Parcoursdetecte'
		)
	);

// 	debug( $parcoursdetectes );
?>
<?php
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
<?php echo $javascript->link( 'dependantselect.js' ); ?>
<script type="text/javascript">
	<?php foreach( array_keys( $parcoursdetectes ) as $key ):?>
	dependantSelect( '<?php echo "Decisionparcours{$step}{$key}StructurereferenteId";?>', '<?php echo "Decisionparcours{$step}{$key}TypeorientId";?>' );
	observeDisableFieldsOnCheckbox(
		'<?php echo "Decisionparcours{$step}{$key}Maintien"?>',
		[
			'<?php echo "Decisionparcours{$step}{$key}TypeorientId"?>',
			'<?php echo "Decisionparcours{$step}{$key}StructurereferenteId"?>',
			'<?php echo "Decisionparcours{$step}{$key}ReferentId"?>'
		],
		true
	);
	<?php endforeach;?>
</script>