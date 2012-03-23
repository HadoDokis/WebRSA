<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $xhtml->css( array( 'fileuploader' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $javascript->link( 'fileuploader.js' );
	}

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
?>

<div class="with_treemenu">
	<?php
		echo $xhtml->tag(
			'h1',
			$this->pageTitle = __d( 'contratinsertion', "Contratsinsertion::{$this->action}", true )
		);

		echo '<fieldset>';
		echo $xform->create( 'Contratinsertion', array( 'id' => 'notificationcontratform' ) );
		if( Set::check( $this->data, 'Contratinsertion.id' ) ){
			echo $xform->input( 'Contratinsertion.id', array( 'type' => 'hidden' ) );
		}
		echo $xform->input( 'Contratinsertion.personne_id', array( 'type' => 'hidden', 'value' => $personne_id ) );

		echo $default2->subform(
			array(
				'Contratinsertion.datenotification' => array( 'type' => 'date', 'maxYear'=>date('Y')+2, 'minYear'=>date('Y')-3 , )
			)
		);
		echo '</fieldset>';
		
		echo "<div class='submit'>";
			echo $form->submit( 'Enregistrer', array( 'div'=>false ) );
			echo $form->submit( 'Retour', array( 'name' => 'Cancel', 'div'=>false ) );
		echo "</div>";

		echo $form->end();
	?>
</div>
<div class="clearer"><hr /></div>