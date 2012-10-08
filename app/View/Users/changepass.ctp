<?php
	$this->pageTitle = 'Changer votre mot de passe';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>

<h1><?php echo $this->pageTitle;?></h1><br />

	<h2 class="title">Informations personnelles</h2>
	<?php
		echo $this->Form->create( 'User', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
		echo $this->Form->input( 'User.id', array( 'type' => 'hidden', 'value' => null ) );
		echo $this->Form->input( 'User.passwd', array( 'label' =>  required( __( 'oldpassword' ) ), 'type' => 'password', 'value' => '' ) );
		echo $this->Form->input( 'User.newpasswd', array( 'label' =>  required( __( 'newpassword' ) ), 'type' => 'password', 'value' => '' ) );
		echo $this->Form->input( 'User.confnewpasswd', array( 'label' =>  required( __( 'confnewpassword' ) ), 'type' => 'password', 'value' => '' ) );
	?>
<?php echo $this->Form->submit( 'Changer' );?>
<?php echo $this->Form->end();?>