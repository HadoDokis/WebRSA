<?php
	if( $this->action == 'add' ) {
		$this->pageTitle = 'Ajout Rendez-vous';
	}
	else {
		$this->pageTitle = 'Édition Rendez-vous';
	}

	if( Configure::read( 'debug' ) ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		dependantSelect( 'RendezvousPermanenceId', 'RendezvousStructurereferenteId' );
		dependantSelect( 'RendezvousReferentId', 'RendezvousStructurereferenteId' );

		<?php
			echo $this->Ajax->remoteFunction(
				array(
					'update' => 'ReferentFonction',
					'url' => Router::url(
						array(
							'action' => 'ajaxreffonct',
							Set::extract( $this->request->data, 'Rendezvous.referent_id' )
						),
						true
					)
				)
			);
		?>

		<?php if( Configure::read( 'Cg.departement') == 58 ):?>
			observeDisableFieldsOnCheckbox(
				'RendezvousIsadomicile',
				[
					'RendezvousPermanenceId'
				],
				true
			);
		<?php endif;?>
	});
</script>

<div class="with_treemenu">
	<h1><?php echo $this->pageTitle;?></h1>

	<?php
		if( $this->action == 'add' ) {
			echo $this->Form->create( 'Rendezvous', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
		}
		else {
			echo $this->Form->create( 'Rendezvous', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
			echo '<div>';
			echo $this->Form->input( 'Rendezvous.id', array( 'type' => 'hidden' ) );
			echo '</div>';
		}
		echo '<div>';
		echo $this->Form->input( 'Rendezvous.personne_id', array( 'type' => 'hidden', 'value' => $personne_id ) );
		echo '</div>';
	?>

	<div class="aere">
		<fieldset>
			<?php
				echo $this->Form->input( 'Rendezvous.structurereferente_id', array( 'label' =>  required( __d( 'rendezvous', 'Rendezvous.lib_struct' ) ), 'type' => 'select', 'options' => $struct, 'empty' => true ) );
				echo $this->Form->input( 'Rendezvous.referent_id', array( 'label' =>  ( 'Nom de l\'agent / du référent' ), 'type' => 'select', 'options' => $referents, 'empty' => true, 'selected' => $struct_id.'_'.$referent_id ) );
				///Ajax
				echo $this->Ajax->observeField( 'RendezvousReferentId', array( 'update' => 'ReferentFonction', 'url' => Router::url( array( 'action' => 'ajaxreffonct' ), true ) ) );

				echo $this->Xhtml->tag(
					'div',
					'<b></b>',
					array(
						'id' => 'ReferentFonction'
					)
				);

				/// Ajout d'une case à cocher permettant de déterminer si le RDV se déroulera chez l'allocataire pour le CG58
				if( Configure::read( 'Cg.departement') == 58 ){
					echo $this->Form->input( 'Rendezvous.isadomicile', array( 'label' => 'Visite à domicile', 'type' => 'checkbox' ) );
				}

				///Ajout d'une permanence liée à une structurereferente
				echo $this->Form->input( 'Rendezvous.permanence_id', array( 'label' => 'Permanence liée à la structure', 'type' => 'select', 'options' => $permanences, 'selected' => $struct_id.'_'.$permanence_id, 'empty' => true ) );

				echo $this->Form->input( 'Rendezvous.typerdv_id', array( 'label' =>  required( __d( 'rendezvous', 'Rendezvous.lib_rdv' ) ), 'type' => 'select', 'options' => $typerdv, 'empty' => true ) );

				if( Configure::read( 'Cg.departement') == 58 ){
					echo $this->Form->input( 'Rendezvous.statutrdv_id', array( 'label' =>  ( __d( 'rendezvous', 'Rendezvous.statutrdv' ) ), 'type' => 'select', 'options' => $statutrdv, 'empty' => true ) );
					echo $this->Form->input( 'Rendezvous.rang', array( 'label' =>  required( __d( 'rendezvous', 'Rendezvous.rang', true ) ), 'type' => 'text' ) );
				}
				else{
					echo $this->Form->input( 'Rendezvous.statutrdv_id', array( 'label' =>  required( __d( 'rendezvous', 'Rendezvous.statutrdv' ) ), 'type' => 'select', 'options' => $statutrdv, 'empty' => true ) );
				}

				echo $this->Form->input( 'Rendezvous.daterdv', array( 'label' =>  required( __d( 'rendezvous', 'Rendezvous.daterdv' ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+1, 'minYear'=>date('Y')-1 ) );

				echo $this->Xform->input( 'Rendezvous.heurerdv', array( 'label' =>  required( __d( 'rendezvous', 'Rendezvous.heurerdv' ) ), 'type' => 'time', 'timeFormat' => '24','minuteInterval'=> 5,  'empty' => true, 'hourRange' => array( 8, 19 ) ) );

				echo $this->Form->input( 'Rendezvous.objetrdv', array( 'label' =>  ( __d( 'rendezvous', 'Rendezvous.objetrdv' ) ), 'type' => 'text', 'rows' => 2, 'empty' => true ) );

				echo $this->Form->input( 'Rendezvous.commentairerdv', array( 'label' =>  ( __d( 'rendezvous', 'Rendezvous.commentairerdv' ) ), 'type' => 'text', 'rows' => 3, 'empty' => true ) );
			?>
		</fieldset>
	</div>
	<div class="submit">
		<?php echo $this->Form->submit( 'Enregistrer', array( 'div' => false ) );?>
		<?php echo $this->Form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
	</div>
	<?php echo $this->Form->end();?>
</div>
<div class="clearer"><hr /></div>