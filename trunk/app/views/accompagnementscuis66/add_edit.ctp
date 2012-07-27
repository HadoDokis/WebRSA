<?php
	echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );

	$this->pageTitle = 'Accompagnements du CUI';

?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		observeDisableFieldsetOnValue(
			'Accompagnementcui66Typeaccompagnementcui66',
			$( 'immersion' ),
			['immersion'],
			false,
			true
		);

	});
</script>

<div class="with_treemenu">
		<?php
			echo $xhtml->tag(
				'h1',
				$this->pageTitle = __d( 'accompagnementcui66', "Accompagnementcui66::{$this->action}", true )
			);
		?>

		<?php
			echo $xform->create( 'Accompagnementcui66', array( 'id' => 'accompagnementcui66form' ) );
			if( Set::check( $this->data, 'Accompagnementcui66.id' ) ){
				echo $xform->input( 'Accompagnementcui66.id', array( 'type' => 'hidden' ) );
			}

			echo $xform->input( 'Accompagnementcui66.cui_id', array( 'type' => 'hidden', 'value' => $cui_id ) );
			echo $xform->input( 'Accompagnementcui66.user_id', array( 'type' => 'hidden', 'value' => $userConnected ) );

			echo $xform->input( 'Accompagnementcui66.typeaccompagnementcui66', array( 'label' => __d( 'accompagnementcui66', 'Accompagnementcui66.typeaccompagnementcui66', true ), 'type' => 'select', 'options' => $options['Accompagnementcui66']['typeaccompagnementcui66'], 'empty' => true ) );
		?>

	<fieldset id="immersion">
		<?php
			echo $default->subform(
				array(
					'Bilanparcours66.typeformulaire' => array( 'type' => 'radio', 'value' => $typeformulaire )
				),
				array(
					'options' => $options
				)
			);
			echo $xform->input( 'Bilanparcours66.typeformulaire', array( 'type' => 'hidden', 'value' => $typeformulaire, 'id' => 'Bilanparcours66TypeformulaireHidden' ) );
		?>
	</fieldset>

	<div class="submit">
		<?php echo $form->submit( 'Enregistrer', array( 'div' => false ) );?>
		<?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
	</div>
	<?php echo $form->end();?>
</div>

<div class="clearer"><hr /></div>