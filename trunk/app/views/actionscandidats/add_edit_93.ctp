<?php
    echo $xhtml->tag(
        'h1',
        $this->pageTitle = __d( 'actioncandidat', "Actionscandidats::{$this->action}", true )
    )
?>
<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
    echo $default->form(
        array(
            'Actioncandidat.name' => array( 'domain' => 'actioncandidat', 'required' => true ),
            'Actioncandidat.themecode' => array( 'domain' => 'actioncandidat', 'required' => true ),
            'Actioncandidat.codefamille' => array( 'domain' => 'actioncandidat', 'required' => true ),
            'Actioncandidat.numcodefamille' => array( 'domain' => 'actioncandidat', 'required' => true ),
			'Actioncandidat.actif' => array( 'label' => 'Active ?', 'type' => 'radio', 'options' => $options['Actioncandidat']['actif'] )
        ),
        array(
            'actions' => array(
                'Actioncandidat.save',
                'Actioncandidat.cancel'
            )
        )
    );
?>
<?php
	echo $default->button(
		'back',
		array('controller' => 'actionscandidats', 'action' => 'index'),
		array('id' => 'Back')
	);
?>