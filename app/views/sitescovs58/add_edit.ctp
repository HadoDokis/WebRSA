<?php
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'sitecov58', "Sitescovs58::{$this->action}", true )
	);

	echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );

	if( $this->action == 'add' ) {
		echo $form->create( 'Sitecov58', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
		echo $form->input( 'Sitecov58.id', array( 'type' => 'hidden', 'value' => null ) );
	}
	else {
		echo $form->create( 'Sitecov58', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
		echo $form->input( 'Sitecov58.id', array( 'type' => 'hidden' ) );
	}

	echo $default2->subform(
		array(
			'Sitecov58.name' => array( 'required' => true, 'type' => 'text' )
		)
	);
	?>
	<fieldset class="col2">
		<legend>Zones géographiques</legend>
		<script type="text/javascript">
			document.observe( "dom:loaded", function() {
			} );
		</script>
		<?php echo $form->button( 'Tout cocher', array( 'onclick' => "toutCocher( 'input[name=\"data[Zonegeographique][Zonegeographique][]\"]' )" ) );?>
		<?php echo $form->button( 'Tout décocher', array( 'onclick' => "toutDecocher( 'input[name=\"data[Zonegeographique][Zonegeographique][]\"]' )" ) );?>

		<?php echo $form->input( 'Zonegeographique.Zonegeographique', array( 'label' => false, 'multiple' => 'checkbox' , 'options' => $zglist ) );?>
	</fieldset>
	<?php echo $form->submit( 'Enregistrer' );?>
	
		<?php echo $default->button(
		'back',
		array(
			'controller' => 'sitescovs58',
			'action'     => 'index'
		),
		array(
			'id' => 'Back'
		)
	);
?>
<?php echo $form->end();?>
