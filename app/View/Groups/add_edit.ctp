<?php
// 	$this->pageTitle = 'Groupe';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $this->Html->script( 'prototype.livepipe.js' );
		echo $this->Html->script( 'prototype.tabs.js' );
	}
?>
<h1><?php
	if( $this->action == 'add' ) {
		$this->pageTitle = 'Ajout d\'un nouveau groupe';
	}
	else {
		$this->pageTitle = 'Modification du groupe '.$this->request->data['Group']['name'];
	}
echo $this->pageTitle;?></h1><br />
<?php echo $this->Form->create( 'Group', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );?>

<div id="tabbedWrapper" class="tabs">
	<div id="infos">
		<h2 class="title">Informations</h2>
		<?php
			if( $this->action == 'add' ) {
				echo $this->Form->input( 'Group.id', array( 'type' => 'hidden', 'value' => '' ) );
			}
			else {
				echo $this->Form->input( 'Group.id', array( 'type' => 'hidden' ) );
			}
		?>

		<fieldset>
			<?php echo $this->Form->input( 'Group.name', array( 'label' => required( __( 'name' ) ), 'type' => 'text' ) );?>
			<?php echo $this->Form->input( 'Group.parent_id', array( 'label' => required(  __( 'parent_id' ) ), 'type' => 'text' ) );?>
		</fieldset>

	</div>

	<div id="droits">
		<h2 class="title">Droits</h2>
		<?php
			if( $this->action == 'add' ) {
				echo $this->Xhtml->para(null, __( 'Sauvegardez puis &eacute;ditez &agrave; nouveau le groupe pour modifier ses droits.' ));
				echo $this->Xhtml->para(null, __( 'Les nouveaux groupes h&eacute;ritent des droits des profils auxquels ils sont rattach&eacute;s.' ));
			}
			else {
				echo $this->element('editDroits');
			}
		?>
	</div>
</div>
<?php echo $this->Form->submit( 'Enregistrer' );?>
<?php echo $this->Form->end();?>

<script type="text/javascript">
	makeTabbed( 'tabbedWrapper', 2 );
</script>
<?php
	echo $this->Default->button(
		'back',
		array(
			'controller' => 'groups',
			'action'     => 'index'
		),
		array(
			'id' => 'Back'
		)
	);
?>