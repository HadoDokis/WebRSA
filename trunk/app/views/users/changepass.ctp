<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Changer votre mot de passe';?>

<h1><?php echo $this->pageTitle;?></h1><br />

    <h2 class="title">Informations personnelles</h2>
    <?php
		echo $form->create( 'User', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
		echo $form->input( 'User.id', array( 'type' => 'hidden', 'value' => null ) );
		echo $form->input( 'User.passwd', array( 'label' =>  required( __( 'oldpassword', true ) ), 'type' => 'password', 'value' => '' ) );
		echo $form->input( 'User.newpasswd', array( 'label' =>  required( __( 'newpassword', true ) ), 'type' => 'password', 'value' => '' ) );
		echo $form->input( 'User.confnewpasswd', array( 'label' =>  required( __( 'confnewpassword', true ) ), 'type' => 'password', 'value' => '' ) );
	?>
<?php echo $form->submit( 'Changer' );?>
<?php echo $form->end();?>

