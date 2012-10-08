<?php
	$this->pageTitle = 'Objet du rendez-vous';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>

<h1><?php echo $this->pageTitle;?></h1>

<?php
	if( $this->action == 'add' ) {
		echo $form->create( 'Typerdv', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
		echo $form->input( 'Typerdv.id', array( 'type' => 'hidden' ) );
	}
	else {
		echo $form->create( 'Typerdv', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
		echo $form->input( 'Typerdv.id', array( 'type' => 'hidden' ) );
	}
?>

<fieldset>
	<?php
		echo $xform->input( 'Typerdv.libelle', array( 'label' =>  required( __d( 'rendezvous', 'Rendezvous.lib_rdv', true ) ), 'type' => 'text' ) );
		echo $xform->input( 'Typerdv.modelenotifrdv', array( 'label' =>  required( __d( 'typerdv', 'Typerdv.modelenotifrdv', true ) ), 'type' => 'text' ) );
// 		if ( Configure::read( 'Cg.departement' ) == 58 ) {
// 			echo $xform->input( 'Typerdv.nbabsencesavpassageep', array( 'label' =>  required( __d( 'typerdv', 'Typerdv.nbabsencesavpassageep', true ) ), 'type' => 'text' ) );
// 			echo $xform->input( 'Typerdv.motifpassageep', array( 'label' =>  __d( 'typerdv', 'Typerdv.motifpassageep', true ), 'type' => 'text' ) );
// 		}
		if ( Configure::read( 'Cg.departement' ) == 66 ) {
			echo $xform->input( 'Typerdv.nbabsaveplaudition', array( 'label' =>  required( __d( 'typerdv', 'Typerdv.passageeplaudition', true ) ), 'type' => 'text' ) );
		}
	?>
</fieldset>

	<?php echo $form->submit( 'Enregistrer' );?>
<?php echo $form->end();?>
<?php
	echo $default->button(
		'back',
		array(
			'controller' => 'typesrdv',
			'action'     => 'index'
		),
		array(
			'id' => 'Back'
		)
	);
?>