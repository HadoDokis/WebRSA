<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $xhtml->css( array( 'fileuploader' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $javascript->link( 'fileuploader.js' );
	}

	echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
?>

<?php  $this->pageTitle = 'Validation PDO';?>
<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<?php
	if( $this->action == 'add' ) {
		$this->pageTitle = 'Ajout d\'une PDO';
	}
	else {
		$this->pageTitle = 'Édition de la PDO';
	}
?>

<div class="with_treemenu">
	<h1><?php echo $this->pageTitle;?></h1>

	<?php
		if( $this->action == 'add' ) {
			echo $xform->create( 'Propopdo', array( 'id' => 'propopdoform', 'type' => 'post', 'url' => Router::url( null, true ) ) );
		}
		else {
			echo $xform->create( 'Propopdo', array( 'id' => 'propopdoform', 'type' => 'post', 'url' => Router::url( null, true ) ) );
			echo '<div>';
				echo $xform->input( 'Propopdo.id', array( 'type' => 'hidden' ) );
				echo $xform->input( 'Decisionpropopdo.0.id', array( 'type' => 'hidden' ) );
				echo $xform->input( 'Decisionpropopdo.0.propopdo_id', array( 'type' => 'hidden' ) );
			echo '</div>';
		}
		echo '<div>';
		echo $xform->input( 'Propopdo.personne_id', array( 'type' => 'hidden', 'value' => $personne_id ) );

		echo '</div>';
	?>

	<div class="aere">
		<fieldset>
			<legend>Détails PDO</legend>
			<?php
				echo $xform->input( 'Propopdo.structurereferente_id', array( 'label' =>  $xform->required( __( 'Structure gérant la PDO', true ) ), 'type' => 'select', 'options' => $structs, 'empty' => true ) );
				echo $xform->input( 'Propopdo.typepdo_id', array( 'label' =>  $xform->required( __d( 'propopdo', 'Propopdo.typepdo_id', true ) ), 'type' => 'select', 'options' => $typepdo, 'empty' => true ) );
				echo $xform->input( 'Propopdo.datereceptionpdo', array( 'label' =>  ( __( 'Date de réception de la PDO', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => true ) );
				echo $xform->input( 'Propopdo.originepdo_id', array( 'label' =>  $xform->required( __( 'Origine', true ) ), 'type' => 'select', 'options' => $originepdo, 'empty' => true ) );
				echo $xform->input( 'Propopdo.decision', array( 'type' => 'hidden', 'value' => '1' ) ).$xform->input( 'Decisionpropopdo.0.decisionpdo_id', array( 'label' =>  $xform->required( __( 'Décision du Conseil Général', true ) ), 'type' => 'select', 'options' => $decisionpdo, 'empty' => true ) );
				echo $xform->input( 'Propopdo.motifpdo', array( 'label' =>  ( __( 'Motif de la décision', true ) ), 'type' => 'select', 'options' => $motifpdo, 'empty' => true ) );
				echo $xform->input( 'Propopdo.iscomplet', array( 'label' =>  __( 'Etat du dossier', true ),  'type' => 'radio', 'options' => $options['iscomplet'] ) );

				echo $xform->input( 'Decisionpropopdo.0.datedecisionpdo', array( 'label' =>  ( __( 'Date de décision CG', true ) ), 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear'=>date('Y')+5, 'minYear'=>date('Y')-1, 'empty' => true ) );

				echo $xform->input( 'Decisionpropopdo.0.commentairepdo', array( 'label' =>  'Observations', 'type' => 'text', 'rows' => 3, 'empty' => true ) );

				echo $default->view(
					$dossier,
					array(
						'Dossier.fonorg',
						'Suiviinstruction.typeserins',
					),
					array(
						'widget' => 'table',
						'id' => 'dossierInfosOrganisme',
						'options' => $options
					)
				);
			?>
			<table class="noborder" id="infosPdo">
				<tr>
					<td class="noborder">
						<?php
							echo $xform->input( 'Situationpdo.Situationpdo', array( 'type' => 'select', 'label' => 'Situation de la PDO', 'multiple' => 'checkbox' , 'options' => $situationlist ) );
						?>
					</td>
					<td class="noborder">
						<?php
							echo $xform->input( 'Statutpdo.Statutpdo', array( 'type' => 'select', 'label' => 'Statut de la PDO', 'multiple' => 'checkbox' , 'options' => $statutlist ) );
						?>
					</td>

				</tr>
			</table>
		</fieldset>
		<?php if( $this->action == 'add' ):?>
		<?php echo $form->input( 'Propopdo.haspiece', array( 'type' => 'hidden', 'value' => '0' ) );?>
		<?php endif;?>
		<?php if( $this->action == 'edit' ):?>
			<fieldset>
				<legend><?php echo required( $default2->label( 'Propopdo.haspiece' ) );?></legend>

				<?php echo $form->input( 'Propopdo.haspiece', array( 'type' => 'radio', 'options' => $options['haspiece'], 'legend' => false, 'fieldset' => false ) );?>
				<fieldset id="filecontainer-piece" class="noborder invisible">
					<?php
						echo $fileuploader->create(
							$fichiers,
							Router::url( array( 'action' => 'ajaxfileupload' ), true )
						);
					?>
				</fieldset>
			</fieldset>
		<?php endif;?>
<script type="text/javascript">
	document.observe( "dom:loaded", function() {
		observeDisableFieldsetOnRadioValue(
			'propopdoform',
			'data[Propopdo][haspiece]',
			$( 'filecontainer-piece' ),
			'1',
			false,
			true
		);
	} );
</script>

	</div>
	<div class="submit">
		<?php echo $form->submit( 'Enregistrer', array( 'div' => false ) );?>
		<?php echo $form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
	</div>
	<?php echo $xform->end();?>
</div>
<div class="clearer"><hr /></div>