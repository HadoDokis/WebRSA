<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>
<?php $this->pageTitle = 'Orientations';?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<?php
	if( $this->action == 'add' ) {
		$this->pageTitle = 'Orientation';
	}
	else {
		$this->pageTitle = 'Édition de l\'orientation';
	}

	if( Configure::read( 'debug' ) > 0 ) {
		echo $javascript->link( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		dependantSelect( 'OrientstructStructurereferenteId', 'OrientstructTypeorientId' );
		try { $( 'OrientstructStructurereferenteId' ).onchange(); } catch(id) { }

		dependantSelect( 'OrientstructReferentId', 'OrientstructStructurereferenteId' );
		try { $( 'OrientstructReferentId' ).onchange(); } catch(id) { }
	});
</script>

<div class="with_treemenu">
	<h1><?php echo $this->pageTitle;?></h1>

	<?php
		if( $this->action == 'add' ) {
			echo $form->create( 'Orientstruct', array(  'type' => 'post', 'url' => Router::url( null, true )  ));
			echo '<div>';
			echo $form->input( 'Orientstruct.id', array( 'type' => 'hidden', 'value' => '' ) );
			echo '</div>';
		}
		else {
			echo $form->create( 'Orientstruct', array( 'type' => 'post', 'url' => Router::url( null, true )  ));
			echo '<div>';
			echo $form->input( 'Orientstruct.id', array( 'type' => 'hidden' ) );
			echo '</div>';
		}
		echo '<div>';
		echo $form->input( 'Orientstruct.origine', array( 'type' => 'hidden', 'value' => 'manuelle' ) );
		echo '</div>';

		$typeorient_id = null;
		if( !empty( $this->data['Structurereferente']['Typeorient']['id'] ) ) {
			$typeorient_id = $this->data['Structurereferente']['Typeorient']['id'];
		}
		$domain = 'orientstruct';
	?>

	<?php if( Configure::read( 'nom_form_ci_cg' ) == 'cg66' ):?>
	<fieldset><legend>Orienté par</legend>
		<script type="text/javascript">
			document.observe("dom:loaded", function() {
				dependantSelect( 'OrientstructReferentorientantId', 'OrientstructStructureorientanteId' );
				try { $( 'OrientstructReferentorientantId' ).onchange(); } catch(id) { }
			});
		</script>

		<?php
			$selected = null;
			if( $this->action == 'edit' ){
				$selected = preg_replace( '/^[^_]+_/', '', $this->data['Orientstruct']['structureorientante_id'] ).'_'.$this->data['Orientstruct']['referentorientant_id'];
			}

			echo $default2->subform(
				array(
					'Orientstruct.structureorientante_id' => array( 'type' => 'select', 'options' => $structsorientantes, 'required' => true ),
					'Orientstruct.referentorientant_id' => array(  'type' => 'select', 'options' => $refsorientants, 'selected' => $selected, 'required' => true )
				),
				array(
					'options' => $options
				)
			);
		?>
	</fieldset>
	<?php endif;?>
	<fieldset>
		<legend>Ajout d'une orientation</legend>
		<?php
			echo $form->input( 'Orientstruct.typeorient_id', array( 'label' =>  required( __d( 'structurereferente', 'Structurereferente.lib_type_orient', true ) ), 'type' => 'select', 'options' => $typesorients, 'empty' => true, 'value' => $typeorient_id ) );

			$selectedtype = Set::classicExtract( $this->data, 'Orientstruct.typeorient_id' );
			$selectedstruct = Set::classicExtract( $this->data, 'Orientstruct.structurereferente_id' );
			$selectedref = Set::classicExtract( $this->data, 'Orientstruct.referent_id' );

			if( !empty( $selectedtype ) && !empty( $selectedstruct ) && ( strpos( $selectedstruct, '_' ) === false ) ) {
				if( !empty( $selectedref ) && ( strpos( $selectedref, '_' ) === false ) ) {
						$selectedref = "{$selectedstruct}_{$selectedref}";
				}
				$selectedstruct = "{$selectedtype}_{$selectedstruct}";
			}

			if( isset( $this->data['Calculdroitrsa']['id'] ) ) {
				echo $form->input( 'Calculdroitrsa.id', array(  'label' =>  false, 'type' => 'hidden' ) );
			}
			echo $form->input( 'Orientstruct.statut_orient', array(  'label' =>  false, 'type' => 'hidden', 'value' => 'Orienté' ) );
			echo $form->input( 'Orientstruct.structurereferente_id', array( 'label' => required(__d( 'structurereferente', 'Structurereferente.lib_struc', true  )), 'type' => 'select', 'options' => $structs, 'empty' => true, 'selected' => $selectedstruct ) );
			echo $form->input( 'Orientstruct.referent_id', array(  'label' => __d( 'structurereferente', 'Structurereferente.nom_referent', true  ), 'type' => 'select', 'options' => $referents, 'empty' => true, 'selected' => $selectedref ) );
			echo $form->input( 'Calculdroitrsa.toppersdrodevorsa', array(  'label' =>  required( __d( 'calculdroitrsa', 'Calculdroitrsa.toppersdrodevorsa', true ) ), 'options' => $toppersdrodevorsa, 'type' => 'select', 'empty' => 'Non défini'  ) );

			$selectedDateDemande = $dossier['Dossier']['dtdemrsa'];
			if( $this->action == 'edit' ){
				$selectedDateDemande = $this->data['Orientstruct']['date_propo'];
			}
			echo $form->input( 'Orientstruct.date_propo', array(  'label' =>  required( __d( 'contratinsertion', 'Contratinsertion.date_propo', true ) ), 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 3, 'minYear' => ( date( 'Y' ) - 3 ), 'empty' => true, 'selected' => $selectedDateDemande ) );

			echo $form->input( 'Orientstruct.date_valid', array(  'label' =>  required( __d( 'contratinsertion', 'Contratinsertion.date_valid', true ) ), 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 3, 'minYear' => ( date( 'Y' ) - 3 ) ) );
		?>
	</fieldset>

		<div class="submit">
			<?php
				echo $xform->submit( 'Enregistrer', array( 'div' => false ) );
				echo $xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
			?>
		</div>
	<?php echo $form->end();?>
</div>
<div class="clearer"><hr /></div>