<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Utilisateurs';?>

<h1><?php echo $this->pageTitle;?></h1><br />

<?php
	echo $form->create( 'User', array( 'type' => 'post', 'url' => Router::url( null, true ), 'autocomplete' => 'off' ) );

	if( $this->action == 'add' ) {
		echo '<div>';
		echo $form->input( 'User.id', array( 'type' => 'hidden', 'value' => null ) );
		echo '</div>';
	}
	else {
		echo '<div>';
		echo $form->input( 'User.id', array( 'type' => 'hidden' ) );
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
				echo $xhtml->para(null, __('Sauvegardez puis &eacute;ditez &agrave; nouveau l\'utilisateur pour modifier ses droits.', true));
				echo $xhtml->para(null, __('Les nouveaux utilisateurs h&eacute;ritent des droits des profils auxquels ils sont rattach&eacute;s.', true));
			}
			else {
				echo $this->element('editDroits');
			}
		?>
	</div>
</div>
<?php echo $form->submit( 'Enregistrer' );?>
<?php echo $form->end();?>

<?php
	echo $javascript->link( 'prototype.livepipe.js' );
	echo $javascript->link( 'prototype.tabs.js' );
?>

<script type="text/javascript">
	makeTabbed( 'tabbedWrapper', 2 );
</script>
