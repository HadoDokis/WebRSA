<?php
	echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );

	if( Configure::read( 'Cg.departement') == 66 ) {
		$this->pageTitle = 'Avis techniques';
	}
	else {
		$this->pageTitle = 'Proposition';
	}

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>

<div class="with_treemenu">
		<?php
			echo $xhtml->tag(
				'h1',
				$this->pageTitle = __d( 'propodecisioncui66', "Propodecisioncui66::{$this->action}", true )
			);
		?>
		<fieldset id="avismne">
			<legend><?php echo 'Avis MNE';?></legend>
				<?php
					echo $xform->create( 'Propodecisioncui66', array( 'id' => 'propodecisioncui66form' ) );
					if( Set::check( $this->data, 'Propodecisioncui66.id' ) ){
						echo $xform->input( 'Propodecisioncui66.id', array( 'type' => 'hidden' ) );
					}
					
					echo $xform->input( 'Propodecisioncui66.cui_id', array( 'type' => 'hidden', 'value' => $cui_id ) );
					echo $xform->input( 'Propodecisioncui66.user_id', array( 'type' => 'hidden', 'value' => $userConnected ) );

					echo $xform->input( 'Propodecisioncui66.structurereferente_id', array( 'type' => 'hidden' ) );
					echo $xform->input( 'Propodecisioncui66.observcui', array( 'label' => __d( 'propodecisioncui66', 'Propodecisioncui66.observcui', true ), 'type' => 'textarea', 'rows' => 6)  );
					echo $xform->input( 'Propodecisioncui66.propositioncui', array( 'label' => __d( 'propodecisioncui66', 'Propodecisioncui66.propositioncui', true ), 'type' => 'select', 'options' => $options['Propodecisioncui66']['propositioncui'], 'empty' => true ) );
					echo $xform->input( 'Propodecisioncui66.datepropositioncui', array( 'label' => __d( 'propodecisioncui66', 'Propodecisioncui66.datepropositioncui', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+2, 'minYear'=>date('Y')-2 , 'empty' => true)  );
				?>
		</fieldset>

		<fieldset>
			<?php
				echo $default2->subform(
					array(
						'Propodecisioncui66.isaviselu' => array( 'label' => __d( 'propodecisioncui66', 'Propodecisioncui66.isaviselu', true ), 'type' => 'checkbox' ),
					),
					array(
						'options' => $options['Propodecisioncui66']
					)
				);
			?>
			<fieldset id="aviselu">
				<legend></legend>
					<?php
						echo $xform->input( 'Propodecisioncui66.observcuielu', array( 'label' => __d( 'propodecisioncui66', 'Propodecisioncui66.observcuielu', true ), 'type' => 'textarea', 'rows' => 6)  );
						echo $xform->input( 'Propodecisioncui66.propositioncuielu', array( 'label' => __d( 'propodecisioncui66', 'Propodecisioncui66.propositioncuielu', true ), 'type' => 'select', 'options' => $options['Propodecisioncui66']['propositioncui'], 'empty' => true ) );
						echo $xform->input( 'Propodecisioncui66.datepropositioncuielu', array( 'label' => __d( 'propodecisioncui66', 'Propodecisioncui66.datepropositioncuielu', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+2, 'minYear'=>date('Y')-2 , 'empty' => true)  );
					?>
			</fieldset>
		</fieldset>
		
		<fieldset>
			<?php
				echo $default2->subform(
					array(
						'Propodecisioncui66.isavisreferent' => array( 'label' => __d( 'propodecisioncui66', 'Propodecisioncui66.isavisreferent', true ), 'type' => 'checkbox' ),
					),
					array(
						'options' => $options['Propodecisioncui66']
					)
				);
			?>
			<fieldset id="avisreferent">
				<legend></legend>
					<?php
						echo $xform->input( 'Propodecisioncui66.observcuireferent', array( 'label' => __d( 'propodecisioncui66', 'Propodecisioncui66.observcuireferent', true ), 'type' => 'textarea', 'rows' => 6)  );
						echo $xform->input( 'Propodecisioncui66.propositioncuireferent', array( 'label' => __d( 'propodecisioncui66', 'Propodecisioncui66.propositioncuireferent', true ), 'type' => 'select', 'options' => $options['Propodecisioncui66']['propositioncui'], 'empty' => true ) );
						echo $xform->input( 'Propodecisioncui66.datepropositioncuireferent', array( 'label' => __d( 'propodecisioncui66', 'Propodecisioncui66.datepropositioncuireferent', true ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+2, 'minYear'=>date('Y')-2 , 'empty' => true)  );
					?>
			</fieldset>
		</fieldset>
	<div class="submit">
		<?php echo $form->submit( 'Enregistrer', array( 'div' => false ) );?>
		<?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
	</div>
	<?php echo $form->end();?>
</div>

<div class="clearer"><hr /></div>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		observeDisableFieldsetOnCheckbox(
			'Propodecisioncui66Isaviselu',
			'aviselu',
			false,
			true
		);
	
		observeDisableFieldsetOnCheckbox(
			'Propodecisioncui66Isavisreferent',
			'avisreferent',
			false,
			true
		);
	});

</script>