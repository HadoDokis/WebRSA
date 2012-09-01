<?php
	$this->pageTitle = 'Décisions PDOs';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>

<h1><?php echo $this->pageTitle;?></h1>

<?php
	if( $this->action == 'add' ) {
		echo $form->create( 'Decisionpdo', array( 'id' => 'decisionpdoform', 'type' => 'post', 'url' => Router::url( null, true ) ) );
		echo $form->input( 'Decisionpdo.id', array( 'type' => 'hidden', 'value' => '' ) );
	}
	else {
		echo $form->create( 'Decisionpdo', array( 'id' => 'decisionpdoform', 'type' => 'post', 'url' => Router::url( null, true ) ) );
		echo $form->input( 'Decisionpdo.id', array( 'type' => 'hidden' ) );
	}
?>

<fieldset>
	<?php

		$fields = array(
			'Decisionpdo.libelle',
			'Decisionpdo.clos' => array( 'type' => 'radio' )
		);

		if ( Configure::read( 'Cg.departement' ) == 66 ) {
			$fields = array_merge(
				$fields,
				array( 'Decisionpdo.cerparticulier' => array( 'type' => 'radio' ) ),
				array( 'Decisionpdo.decisioncerparticulier' => array( 'type' => 'select', 'options' => $decision_ci, 'empty' => true ) )
			);
		}
		else {
			$fields = array_merge(
				$fields,
				array( 'Decisionpdo.modeleodt' )
			);
		}

		echo $default->subform(
			$fields,
			array(
				'options' => $options
			)
		);
	?>
</fieldset>

<div class="submit">
	<?php
		echo $xform->submit( 'Enregistrer', array( 'div' => false ) );
		echo $xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
	?>
</div>

<?php echo $form->end();?>

<div class="clearer"><hr /></div>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		observeDisableFieldsOnRadioValue(
			'decisionpdoform',
			'data[Decisionpdo][cerparticulier]',
			[ 'DecisionpdoDecisioncerparticulier' ],
			'O',
			true,
			true
		);


	});
</script>