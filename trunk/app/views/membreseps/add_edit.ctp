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
			//'Membreep.ep_id' => array('type'=>'select'),
			'Membreep.fonctionmembreep_id' => array('type'=>'select'),
			'Membreep.qual',
			'Membreep.nom',
			'Membreep.prenom',
			'Membreep.tel',
			'Membreep.mail',
			'Membreep.suppleant_id' => array('type'=>'select', 'options'=>$listeSuppleants)
		),
		array(
			'id' => 'MembreepAddEditForm',
			'options' => $options
		)
	);

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
?>

<!--<script type="text/javascript">
	function updateSuppleant () {
		var ep_id = $F('MembreepEpId');
		var suppleant_id=0;
		if ($('MembreepSuppleantId') != undefined)
			suppleant_id = $F('MembreepSuppleantId');
		var membreep_id=0;
		if ($('MembreepId') != undefined)
			membreep_id = $F('MembreepId');
		new Ajax.Updater(
			$( 'MembreepSuppleantId' ).up(),
			'<?php echo Router::url( "/membreseps/ajaxfindsuppleant", true );?>'+'/'+ep_id+'/'+suppleant_id+'/'+membreep_id,
			 {
			 	asynchronous:true,
			 	evalScripts:true,
//			 	parameters:Form.Element.serialize('MembreepEpId'),
			 	requestHeaders:['X-Update', $( 'MembreepSuppleantId' ).up()]
		 	}
		 )
	 }

	document.observe("dom:loaded", function() {
		$('MembreepEpId').observe('change', function(event) {
			updateSuppleant();
		});
		updateSuppleant();
	});

/*new Form.Element.EventObserver(
	'MembreepEpId', function(element, value) {   updateSuppleant(); }
);*/
</script>-->
