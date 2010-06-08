<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Groupe';?>

<h1><?php echo $this->pageTitle." ".$this->data['Group']['name'];?></h1><br />

<div id="tabbedWrapper" class="tabs">
    <div id="infos">
        <h2 class="title">Informations</h2>
		<?php 
			if( $this->action == 'add' ) {
				echo $form->create( 'Group', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
				echo $form->input( 'Group.id', array( 'type' => 'hidden', 'value' => '' ) );
			}
			else {
				echo $form->create( 'Group', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
				echo $form->input( 'Group.id', array( 'type' => 'hidden' ) );
			}
		?>

	<?php include '_form.ctp'; ?>
    </div>
    
    <div id="droits">
    	<h2 class="title">Droits</h2>
    	<?php
    		if( $this->action == 'add' ) {
				echo $html->para(null, __('Sauvegardez puis &eacute;ditez &agrave; nouveau le groupe pour modifier ses droits.', true));
				echo $html->para(null, __('Les nouveaux groupes h&eacute;ritent des droits des profils auxquels ils sont rattach&eacute;s.', true));
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
