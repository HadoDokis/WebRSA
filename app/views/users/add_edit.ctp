<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Utilisateurs';?>

<h1><?php echo $this->pageTitle;?></h1><br />

<div id="tabbedWrapper" class="tabs">
    <div id="infos">
        <h2 class="title">Informations personnelles</h2>
        <?php
			if( $this->action == 'add' ) {
				echo $form->create( 'User', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
				echo '<div>';
				echo $form->input( 'User.id', array( 'type' => 'hidden', 'value' => null ) );
				echo '</div>';
		//         echo $form->input( 'Zonegeographique.id', array( 'type' => 'hidden' ) );
		//         echo $form->input( 'User.group_id', array( 'type' => 'hidden' ) );
		//         echo $form->input( 'User.serviceinstructeur_id', array( 'type' => 'hidden' ) );
			}
			else {
				echo $form->create( 'User', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
				echo '<div>';
				echo $form->input( 'User.id', array( 'type' => 'hidden' ) );
				echo '</div>';
		//         echo $form->input( 'Zonegeographique.id', array( 'type' => 'hidden' ) );
		//         echo $form->input( 'User.group_id', array( 'type' => 'hidden' ) );
		//         echo $form->input( 'User.serviceinstructeur_id', array( 'type' => 'hidden' ) );
			}
		?>
	    <?php include '_form.ctp'; ?>
    </div>
    <div id="droits">
    	<h2 class="title">Droits</h2>
    	<?php
    		if( $this->action == 'add' ) {
				echo $html->para(null, __('Sauvegardez puis &eacute;ditez &agrave; nouveau l\'utilisateur pour modifier ses droits.', true));
				echo $html->para(null, __('Les nouveaux utilisateurs h&eacute;ritent des droits des profils auxquels ils sont rattach&eacute;s.', true));
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
