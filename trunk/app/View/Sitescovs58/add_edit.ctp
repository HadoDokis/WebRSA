<?php
	echo $this->Xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'sitecov58', "Sitescovs58::{$this->action}" )
	);

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	if( $this->action == 'add' ) {
		echo $this->Form->create( 'Sitecov58', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
		echo $this->Form->input( 'Sitecov58.id', array( 'type' => 'hidden', 'value' => null ) );
	}
	else {
		echo $this->Form->create( 'Sitecov58', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
		echo $this->Form->input( 'Sitecov58.id', array( 'type' => 'hidden' ) );
	}

	echo $this->Default2->subform(
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
		<?php echo $this->Form->button( 'Tout cocher', array( 'onclick' => "toutCocher( 'input[name=\"data[Zonegeographique][Zonegeographique][]\"]' )" ) );?>
		<?php echo $this->Form->button( 'Tout décocher', array( 'onclick' => "toutDecocher( 'input[name=\"data[Zonegeographique][Zonegeographique][]\"]' )" ) );?>

		<?php echo $this->Form->input( 'Zonegeographique.Zonegeographique', array( 'label' => false, 'multiple' => 'checkbox' , 'options' => $zglist ) );?>
	</fieldset>
	<?php echo $this->Form->submit( 'Enregistrer' );?>

		<?php echo $this->Default->button(
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
<?php echo $this->Form->end();?>
