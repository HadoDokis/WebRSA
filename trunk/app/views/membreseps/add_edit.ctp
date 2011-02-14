<h1>
<?php
	if( $this->action == 'add' ) {
		echo $this->pageTitle = 'Ajout d\'un membre pour une équipe pluridisciplinaire';
	}
	else {
		echo $this->pageTitle = 'Modification d\'un membre pour une équipe pluridisciplinaire';
	}
?>
</h1>

<?php
	echo $default2->form(
		array(
			'Membreep.fonctionmembreep_id' => array('type'=>'select', 'required' => true),
			'Membreep.qual' => array( 'required' => true ),
			'Membreep.nom' => array( 'required' => true ),
			'Membreep.prenom' => array( 'required' => true ),
			'Membreep.tel',
			'Membreep.mail',
			'Membreep.suppleant_id' => array('type'=>'select', 'options'=>$listeSuppleants)
		),
		array(
			'id' => 'MembreepAddEditForm',
			'options' => $options
		)
	);
?>

<script type="text/javascript">
	$( 'MembreepSuppleantId' ).up( 'div' ).id = 'updateMe';
</script>

<?php
    echo $default->button(
        'back',
        array(
            'controller' => 'membreseps',
            'action'     => 'index'
        ),
        array(
            'id' => 'Back'
        )
    );
    
	echo $ajax->observeField(
		'MembreepFonctionmembreepId',
		array(
			'update' => 'updateMe',
			'url' => Router::url( "/membreseps/ajaxfindsuppleant", true ),
			'with' => 'Form.serializeElements( $$("form")[0].getElements() )'
		)
	);
?>