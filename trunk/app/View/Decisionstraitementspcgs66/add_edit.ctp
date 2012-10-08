<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>

<div class="with_treemenu">
	<?php
		echo $this->Xhtml->tag(
			'h1',
			$this->pageTitle = __d( 'decisiontraitementpcg66', "Decisionstraitementspcgs66::{$this->action}" )
		);

		echo $this->Xform->create( 'Decisiontraitementpcg66', array( 'id' => 'decisiontraitementpcg66form' ) );
		if( Set::check( $this->request->data, 'Decisiontraitementpcg66.id' ) ){
			echo $this->Xform->input( 'Decisiontraitementpcg66.id', array( 'type' => 'hidden' ) );
		}
		echo $this->Xform->input( 'Decisiontraitementpcg66.actif', array( 'type' => 'hidden', 'value' => '1'  ) );
	?>

	<fieldset><legend>Proposition de décision</legend>
		<fieldset id="Decision" class="invisible">
			<?php
				echo $this->Default2->subform(
					array(
						'Decisiontraitementpcg66.traitementpcg66_id' => array( 'type' => 'hidden', 'value' => $traitementpcg66['Traitementpcg66']['id'] ),
						'Decisiontraitementpcg66.valide' => array( 'label' =>  'Confirme la décision ?', 'required' => true, 'type' => 'radio', 'options' => $options['Decisiontraitementpcg66']['valide'] ),
						'Decisiontraitementpcg66.commentaire' => array( 'label' =>  'Commentaire : ', 'required' => true, 'type' => 'textarea' )
					),
					array(
						'options' => $options
					)
				);
			?>
		</fieldset>

	</fieldset>

	<?php
		echo "<div class='submit'>";
			echo $this->Form->submit( 'Enregistrer', array( 'div' => false ) );
			echo $this->Form->button( 'Retour', array( 'type' => 'button', 'onclick' => "location.replace('".Router::url( '/decisionstraitementspcgs66/index/'.$personnepcg66_id, true )."')" ) );
		echo "</div>";

		echo $this->Form->end();
	?>
</div>
<div class="clearer"></div>