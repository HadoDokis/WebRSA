<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'situationpdo', "Situationspdos::{$this->action}", true )
	)
?>

<?php
	echo $xform->create();

	echo $default2->subform(
		array(
			'Situationpdo.id' => array( 'type' => 'hidden' ),
			'Situationpdo.libelle' => array( 'required' => true )
		)
	);
?>
<?php /*if( Configure::read( 'Cg.departement' ) == 66 ):?>
	<fieldset class="col2" id="filtres_zone_geo">
		<legend>Modèles de courrier</legend>
		<?php echo $form->button( 'Tout cocher', array( 'onclick' => "toutCocher( 'input[name=\"data[Modeletypecourrierpcg66][Modeletypecourrierpcg66][]\"]' )" ) );?>
		<?php echo $form->button( 'Tout décocher', array( 'onclick' => "toutDecocher( 'input[name=\"data[Modeletypecourrierpcg66][Modeletypecourrierpcg66][]\"]' )" ) );?>
		<?php echo $xform->input( 'Modeletypecourrierpcg66.Modeletypecourrierpcg66', array( 'fieldset' => false, 'required' => true, 'multiple' => 'checkbox' , 'options' => $modelelist ) );?>
	</fieldset>
<?php endif;*/?>
<?php
	echo $xform->end( 'Save' );

    echo $default->button(
        'back',
        array(
            'controller' => 'situationspdos',
            'action'     => 'index'
        ),
        array(
            'id' => 'Back'
        )
    );
?>
