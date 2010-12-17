<h1>
<?php
	echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );

	echo $this->pageTitle = 'Ajout d\'un participant à l\'EP';
?>
</h1>

<?php

	echo $default->form(
		array(
			'EpMembreep.ep_id' => array('type'=>'hidden', 'value'=>$ep_id),
			'EpMembreep.membreep_id' => array('required' => true, 'type'=>'select', 'options'=>$listeParticipants)
		)
	);

    echo $default->button(
		'back',
        array(
        	'controller' => 'eps',
        	'action'     => 'edit',
        	$ep_id
        ),
        array(
        	'id' => 'Back'
        )
	);
	
?>
