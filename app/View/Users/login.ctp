<?php
	$this->pageTitle = 'Connexion';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>

<h1><?php echo $this->pageTitle;?></h1>

<?php if( isset( $success ) ): ?>
	<p class="success"><?php echo $success; ?></p>
<?php else: ?>
	<?php if( isset( $error ) ): ?>
		<p class="error"><?php echo $error; ?></p>
	<?php endif; ?>

	<?php echo $this->Form->create( 'User', array( 'action' => 'login' ) ); ?>
		<?php echo $this->Form->input( 'username', array( 'label' => 'Identifiant' ) ); ?>
		<?php echo $this->Form->input( 'password', array( 'label' => 'Mot de passe' ) ); ?>
		<?php echo $this->Form->submit( 'Connexion' ); ?>
	<?php echo $this->Form->end(); ?>
<?php endif; ?>

<script type="text/javascript">
	observeDisableFormOnSubmit( 'UserLoginForm', 'Connexion en cours ...' );
</script>