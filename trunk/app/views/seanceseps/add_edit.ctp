<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<h1>
<?php
	if( $this->action == 'add' ) {
		echo $this->pageTitle = 'Ajout d\'une séance d\'EP';
	}
	else {
		echo $this->pageTitle = 'Modification d\'une séance d\'EP';
	}
?>
</h1>

    <script type="text/javascript">
        document.observe("dom:loaded", function() {
			<?php
				echo $ajax->remoteFunction(
				    array(
				        'update' => 'Adresse',
				        'url' => Router::url(
				            array(
				                'action' => 'ajaxadresse',
				                Set::extract( $this->data, 'Seanceep.structurereferente_id' )
				            ),
				            true
				        )
				    )
				);
			?>
		});
    </script>

<?php
	
	echo $form->create( 'Seanceep', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
	
	echo $default->subform(
		array(
			'Seanceep.id' => array('type'=>'hidden'),
			'Seanceep.identifiant',
			'Seanceep.ep_id',
			'Seanceep.name',
			'Seanceep.dateseance' => array( 'dateFormat' => __( 'Locale->dateFormat', true ), 'timeFormat' => __( 'Locale->timeFormat', true ), 'interval'=>15 ), // TODO: à mettre par défaut dans Default2Helper
			'Seanceep.structurereferente_id' => array ('type'=>'select')
		),
		array(
			'options' => $options
		)
	);
	
	echo $ajax->observeField( 'SeanceepStructurereferenteId', array( 'update' => 'Adresse', 'url' => Router::url( array( 'action' => 'ajaxadresse' ) ), true ) );
	
	echo $html->tag(
		'div',
		'',
		array(
			'id' => 'Adresse'
		)
	);
	
	echo $default->subform(
		array(
			'Seanceep.salle',
			'Seanceep.observations' => array('type'=>'textarea')
// 			'Seanceep.finalisee'
		),
		array(
			'options' => $options
		)
	);
	
    echo $form->end( 'Enregistrer' );
    
?>
