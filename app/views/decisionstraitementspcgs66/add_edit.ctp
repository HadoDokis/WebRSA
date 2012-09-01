<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>

<div class="with_treemenu">
	<?php
		echo $xhtml->tag(
			'h1',
			$this->pageTitle = __d( 'decisiontraitementpcg66', "Decisionstraitementspcgs66::{$this->action}", true )
		);

		echo $xform->create( 'Decisiontraitementpcg66', array( 'id' => 'decisiontraitementpcg66form' ) );
		if( Set::check( $this->data, 'Decisiontraitementpcg66.id' ) ){
			echo $xform->input( 'Decisiontraitementpcg66.id', array( 'type' => 'hidden' ) );
		}
		echo $xform->input( 'Decisiontraitementpcg66.actif', array( 'type' => 'hidden', 'value' => '1'  ) );
	?>

	<fieldset><legend>Proposition de décision</legend>
		<fieldset id="Decision" class="invisible">
			<?php
				echo $default2->subform(
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
			echo $form->submit( 'Enregistrer', array( 'div' => false ) );
			echo $form->button( 'Retour', array( 'type' => 'button', 'onclick' => "location.replace('".Router::url( '/decisionstraitementspcgs66/index/'.$personnepcg66_id, true )."')" ) );
		echo "</div>";

		echo $form->end();
	?>
</div>
<div class="clearer"></div>