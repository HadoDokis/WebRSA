<h1>
<?php
	echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );

	echo $this->pageTitle = 'Ajout d\'un participant à l\'EP';
?>
</h1>

<?php
	if ( !empty( $listeParticipants ) ) {
		echo $default->form(
			array(
				'EpMembreep.ep_id' => array( 'type' => 'hidden', 'value' => $ep_id ),
				'EpMembreep.membreep_id' => array( 'required' => true, 'type' => 'select', 'options' => $listeParticipants )
			)
		);
	}
	else {
		echo $xhtml->tag(
			'p',
			'Aucun participant pour cette fonction ne peut être ajouté.',
			array( 'class' => 'notice' )
		);
	}

	if ( $ep_id == 0 ) {
		echo $default->button(
			'back',
			array(
				'controller' => 'eps',
				'action'     => 'add'
			),
			array(
				'id' => 'Back'
			)
		);
	}
	else {
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
	}
	
?>
