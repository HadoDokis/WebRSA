<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Connexion';?>

<h1>Connexion</h1>

<?php if( isset( $success ) ): ?>
    <p class="success"><?php echo $success; ?></p>
<?php else: ?>
    <?php if( isset( $error ) ): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php echo $form->create( 'User', array( 'action' => 'login' ) ); ?>
        <?php echo $form->input( 'username', array( 'label' => 'Identifiant' ) ); ?>
        <?php echo $form->input( 'password', array( 'label' => 'Mot de passe' ) ); ?>
        <?php echo $form->submit( 'Connexion' ); ?>
    <?php echo $form->end(); ?>
<?php endif; ?>

<script type="text/javascript">
	Event.observe(
		'UserLoginForm',
		'submit',
		function() {
			var notice = new Element( 'p', { 'class': 'notice' } ).update( 'Connexion en cours ...' );
			$( 'UserLoginForm' ).insert( { 'top' : notice } );

			$$( 'input[type=submit]' ).each( function( submit ) {
				$( submit ).disable();
			} );
		}
	);
</script>