<?php
	if( $this->action == 'add' ) {
		$this->pageTitle = 'Orientation';
	}
	else {
		$this->pageTitle = 'Édition de l\'orientation';
	}

	if( Configure::read( 'debug' ) > 0 ) {
		echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $javascript->link( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		dependantSelect( 'Propoorientationcov58StructurereferenteId', 'Propoorientationcov58TypeorientId' );
		try { $( 'Propoorientationcov58StructurereferenteId' ).onchange(); } catch(id) { }

		dependantSelect( 'Propoorientationcov58ReferentId', 'Propoorientationcov58StructurereferenteId' );
		try { $( 'Propoorientationcov58ReferentId' ).onchange(); } catch(id) { }
	});
</script>

<div class="with_treemenu">
	<h1><?php echo $this->pageTitle;?></h1>

	<?php
		if( $this->action == 'add' ) {
			echo $form->create( 'Propoorientationcov58', array(  'type' => 'post', 'url' => Router::url( null, true )  ) );
			echo '<div>';
			echo $form->input( 'Propoorientationcov58.id', array( 'type' => 'hidden', 'value' => '' ) );
			echo '</div>';
		}
		else {
			echo $form->create( 'Propoorientationcov58', array( 'type' => 'post', 'url' => Router::url( null, true )  ) );
			echo '<div>';
			echo $form->input( 'Propoorientationcov58.id', array( 'type' => 'hidden' ) );
			echo $form->input( 'Propoorientationcov58.dossiercov58_id', array( 'type' => 'hidden' ) );
			echo '</div>';
		}

		$typeorient_id = null;
		if( !empty( $this->data['Structurereferente']['Typeorient']['id'] ) ) {
			$typeorient_id = $this->data['Structurereferente']['Typeorient']['id'];
		}
		$domain = 'orientstruct';
	?>

	<fieldset>
		<legend>Ajout d'une orientation</legend>
		<script type="text/javascript">
			document.observe("dom:loaded", function() {
				dependantSelect( 'Propoorientationcov58ReferentorientantId', 'Propoorientationcov58StructureorientanteId' );
				try { $( 'Propoorientationcov58ReferentorientantId' ).onchange(); } catch(id) { }
			});
		</script>
		<?php
			$selected = null;
			if( $this->action == 'edit' ){
				$selected = preg_replace( '/^[^_]+_/', '', $this->data['Propoorientationcov58']['structureorientante_id'] ).'_'.$this->data['Propoorientationcov58']['referentorientant_id'];
			}

			echo $default2->subform(
				array(
					'Propoorientationcov58.structureorientante_id' => array( 'type' => 'select', 'options' => $structsorientantes, 'required' => true ),
					'Propoorientationcov58.referentorientant_id' => array(  'type' => 'select', 'options' => $refsorientants, 'selected' => $selected, 'required' => true )
				)
			);

		?>



		<?php echo $form->input( 'Propoorientationcov58.typeorient_id', array( 'label' =>  required( __d( 'structurereferente', 'Structurereferente.lib_type_orient', true ) ), 'type' => 'select', 'options' => $typesorients, 'empty' => true, 'value' => $typeorient_id ) );?>
		<?php
			if( $this->action == 'edit' ) {
				if( !empty( $this->data['Propoorientationcov58']['structurereferente_id'] ) ) {
					$this->data['Propoorientationcov58']['structurereferente_id'] = preg_replace( '/^[^_]+_/', '', $this->data['Propoorientationcov58']['typeorient_id'] ).'_'.$this->data['Propoorientationcov58']['structurereferente_id'];

					$this->data['Propoorientationcov58']['referent_id'] = preg_replace( '/^[^_]+_/', '', $this->data['Propoorientationcov58']['structurereferente_id'] ).'_'.$this->data['Propoorientationcov58']['referent_id'];
				}
			}
			else {
				if( !Set::check( $this->data, 'Propoorientationcov58.structurereferente_id', '' ) ) {
					$this->data = Set::insert( $this->data, 'Propoorientationcov58.structurereferente_id', '' );
				}
				if( !Set::check( $this->data, 'Propoorientationcov58.referent_id', '' ) ) {
					$this->data = Set::insert( $this->data, 'Propoorientationcov58.referent_id', '' );
				}
			}

			/// Rustine sinon 13_10_5_4
			$this->data['Propoorientationcov58']['structurereferente_id'] = preg_replace( '/^.*(?<![0-9])([0-9]+_[0-9]+)$/', '\1', $this->data['Propoorientationcov58']['structurereferente_id'] );
			$this->data['Propoorientationcov58']['referent_id'] = preg_replace( '/^.*(?<![0-9])([0-9]+_[0-9]+)$/', '\1', $this->data['Propoorientationcov58']['referent_id'] );

			echo $form->input( 'Propoorientationcov58.structurereferente_id', array( 'label' => required(__d( 'structurereferente', 'Structurereferente.lib_struc', true  )), 'type' => 'select', 'options' => $structuresreferentes, 'empty' => true, 'selected' => $this->data['Propoorientationcov58']['structurereferente_id'] ) );
			echo $form->input( 'Propoorientationcov58.referent_id', array(  'label' => __d( 'structurereferente', 'Structurereferente.nom_referent', true  ), 'type' => 'select', 'options' => $referents, 'empty' => true, 'selected' => $this->data['Propoorientationcov58']['referent_id'] ) );

// 		echo $form->input( 'Propoorientationcov58.datedemande', array(  'label' =>  required( __d( 'contratinsertion', 'Contratinsertion.date_propo', true ) ), 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 3, 'minYear' => ( date( 'Y' ) - 3 ), 'empty' => true, 'type' => 'date' ) );

			echo $form->input( 'Propoorientationcov58.datedemande', array( 'type' => 'hidden', 'value' => date( 'Y-m-d' ) ) );
			echo $form->input( 'Propoorientationcov58.user_id', array( 'type' => 'hidden', 'value' => $session->read( 'Auth.User.id' ) ) );
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