<?php
	echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>

<div class="with_treemenu">
	<?php
		echo $xhtml->tag(
			'h1',
			$this->pageTitle = __d( 'propodecisioncer66', "Proposdecisionscers66::{$this->action}", true )
		);

		echo $xform->create( 'Propodecisioncer66', array( 'id' => 'propodecisioncer66form' ) );
		if( Set::check( $this->data, 'Propodecisioncer66.id' ) ){
			echo $xform->input( 'Propodecisioncer66.id', array( 'type' => 'hidden' ) );
		}
		echo $xform->input( 'Propodecisioncer66.contratinsertion_id', array( 'type' => 'hidden', 'value' => $contratinsertion_id ) );
	?>

	<fieldset>

		<?php
			$formeci = Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.forme_ci' ), $forme_ci );
			$ddci = date_short( $contratinsertion['Contratinsertion']['dd_ci'] );
			$dfci = date_short( $contratinsertion['Contratinsertion']['df_ci'] );
			$duree = Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.duree_engag' ), $duree_engag_cg66 );
			$referent = $contratinsertion['Referent']['nom_complet'];

			echo $xform->fieldValue( 'Contratinsertion.forme_ci', $formeci );
			echo $xform->fieldValue( 'Contratinsertion.dd_ci', $ddci );
			echo $xform->fieldValue( 'Contratinsertion.df_ci', $dfci );
			echo $xform->fieldValue( 'Contratinsertion.duree_engag', $duree );
			echo $xform->fieldValue( 'Referent.nom_complet', $referent );
			
			echo $form->input( 'Propodecisioncer66.isvalidcer', array( 'legend' => __d( 'propodecisioncer66', 'Propodecisioncer66.isvalidcer', true ), 'type' => 'radio', 'options' => $options['isvalidcer'] ) );

		?>
		<fieldset id="motifcer" class="invisible">
			<?php
				echo $default2->subform(
					array(
						'Motifcernonvalid66.Motifcernonvalid66' => array( 'type' => 'select', 'label' => 'Motif de non validation', 'multiple' => 'checkbox', 'empty' => false, 'options' => $listMotifs ),
						'Propodecisioncer66.motifficheliaison' => array( 'type' => 'textarea' ),
						'Propodecisioncer66.motifnotifnonvalid' => array( 'type' => 'textarea' )
					),
					array(
						'options' => $options
					)
				);
			?>
		</fieldset>

	</fieldset>
	<div class="submit">
		<?php echo $form->submit( 'Enregistrer', array( 'div' => false ) );?>
		<?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
	</div>
	<?php echo $form->end();?>
<div class="clearer"><hr /></div>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		observeDisableFieldsetOnRadioValue(
			'propodecisioncer66form',
			'data[Propodecisioncer66][isvalidcer]',
			$( 'motifcer' ),
			['N'],
			false,
			true
		);
	});
</script>