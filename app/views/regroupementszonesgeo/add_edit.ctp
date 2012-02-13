<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Regroupements en région';?>

<h1><?php echo $this->pageTitle;?></h1>

	<?php
		if( $this->action == 'add' ) {
			echo $form->create( 'Regroupementzonegeo', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
			echo $form->input( 'Regroupementzonegeo.id', array( 'type' => 'hidden', 'value' => null ) );
		}
		else {
			echo $form->create( 'Regroupementzonegeo', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
			echo $form->input( 'Regroupementzonegeo.id', array( 'type' => 'hidden' ) );
		}
	?>
	<fieldset>
		<?php echo $form->input( 'Regroupementzonegeo.lib_rgpt', array( 'label' =>  required( __( 'lib_rgpt', true ) ), 'type' => 'text' ) );?>
	</fieldset>

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
<?php echo $form->end();?>