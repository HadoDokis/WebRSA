<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>
<?php $this->pageTitle = __d( 'ajoutdossier', "Ajoutdossiers::{$this->action}" );?>
<?php echo $this->Form->create( 'Ajoutdossiers', array( 'id' => 'SignupForm', 'url'=> Router::url( null, true ) ) );?>
	<h1>Insertion d'une nouvelle demande de RSA</h1>
	<h2>Étape 2: adresse complète</h2>

	<?php echo $this->Form->input( 'Adressefoyer.rgadr', array( 'type' => 'hidden', 'value' => '01' ) );?>
	<?php include( $this->__paths[0].'adressesfoyers/_form.ctp' ); /* FIXME */?>

	<div class="submit">
		<?php echo $this->Form->submit( '< Précédent', array( 'name' => 'Previous', 'div'=>false ) );?>
		<?php echo $this->Form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
		<?php echo $this->Form->submit( 'Suivant >', array( 'div'=>false ) );?>
	</div>
<?php echo $this->Form->end();?>