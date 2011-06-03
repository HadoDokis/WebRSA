<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<h1>
<?php
	echo $this->pageTitle = "Gestion de la composition du regroupement d'E.P. : {$this->data['Regroupementep']['name']}.";
?>
</h1>

<?php
	echo $xform->create( null );

	foreach( $fonctionsmembreseps as $functionId => $functionName ) {
		echo "<fieldset><legend>{$functionName}</legend>";
		echo $html->tag(
			'div',
			$default->subform(
				array(
					"Compositionregroupementep.{$functionId}.id" => array( 'type' => 'hidden' ),
					"Compositionregroupementep.{$functionId}.prioritaire" => array( 'type' => 'radio' ),
					"Compositionregroupementep.{$functionId}.obligatoire" => array( 'type' => 'radio' )
				),
				array(
					'options' => $options
				)
			)
		);
		echo '</fieldset>';
	}

	echo $xform->end( __( 'Save', true ) );

	echo $default->button(
		'back',
		array(
			'controller' => 'compositionsregroupementseps',
			'action'     => 'index'
		),
		array(
			'id' => 'Back'
		)
	);
?>