<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo $this->Xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'situationpdo', "Situationspdos::{$this->action}" )
	)
?>

<?php
	echo $this->Xform->create();

	echo $this->Default2->subform(
		array(
			'Situationpdo.id' => array( 'type' => 'hidden' ),
			'Situationpdo.libelle' => array( 'required' => true )
		)
	);
?>
<?php /*if( Configure::read( 'Cg.departement' ) == 66 ):?>
	<fieldset class="col2" id="filtres_zone_geo">
		<legend>Modèles de courrier</legend>
		<?php echo $this->Form->button( 'Tout cocher', array( 'onclick' => "toutCocher( 'input[name=\"data[Modeletypecourrierpcg66][Modeletypecourrierpcg66][]\"]' )" ) );?>
		<?php echo $this->Form->button( 'Tout décocher', array( 'onclick' => "toutDecocher( 'input[name=\"data[Modeletypecourrierpcg66][Modeletypecourrierpcg66][]\"]' )" ) );?>
		<?php echo $this->Xform->input( 'Modeletypecourrierpcg66.Modeletypecourrierpcg66', array( 'fieldset' => false, 'required' => true, 'multiple' => 'checkbox' , 'options' => $modelelist ) );?>
	</fieldset>
<?php endif;*/?>
<?php
	echo $this->Xform->end( 'Save' );

    echo $this->Default->button(
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
