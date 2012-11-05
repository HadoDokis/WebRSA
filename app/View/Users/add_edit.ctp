<?php $this->pageTitle = 'Utilisateurs';?>

<h1><?php echo $this->pageTitle;?></h1><br />

<?php
	echo $this->Form->create( 'User', array( 'type' => 'post', 'url' => Router::url( null, true ), 'autocomplete' => 'off' ) );

	if( $this->action == 'add' ) {
		echo '<div>';
		echo $this->Form->input( 'User.id', array( 'type' => 'hidden', 'value' => null ) );
		echo '</div>';
	}
	else {
		echo '<div>';
		echo $this->Form->input( 'User.id', array( 'type' => 'hidden' ) );
		echo '</div>';
	}
?>

<div id="tabbedWrapper" class="tabs">
	<div id="infos">
		<h2 class="title">Informations personnelles</h2>
		<?php include '_form.ctp'; ?>
	</div>
	<div id="droits">
		<h2 class="title">Droits</h2>
		<?php
			if( $this->action == 'add' ) {
				echo $this->Xhtml->para(null, __( 'Sauvegardez puis &eacute;ditez &agrave; nouveau l\'utilisateur pour modifier ses droits.' ));
				echo $this->Xhtml->para(null, __( 'Les nouveaux utilisateurs h&eacute;ritent des droits des profils auxquels ils sont rattach&eacute;s.' ));
			}
			else {
				echo $this->element('editDroits');
			}
		?>
	</div>
</div>
<?php echo $this->Form->submit( 'Enregistrer' );?>
<?php echo $this->Form->end();?>

<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $this->Html->script( 'prototype.livepipe.js' );
		echo $this->Html->script( 'prototype.tabs.js' );
	}
?>

<script type="text/javascript">
	makeTabbed( 'tabbedWrapper', 2 );
</script>
<?php
	echo $this->Default->button(
		'back',
		array(
			'controller' => 'users',
			'action'     => 'index'
		),
		array(
			'id' => 'Back'
		)
	);
?>
