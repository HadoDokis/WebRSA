<?php
	echo $this->Default3->titleForLayout( $personne );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	$url = array( 'controller' => $this->request->params['controller'], 'action' => $this->request->params['action'], $this->request->params['pass'][0] );

	echo $this->Default3->DefaultForm->create( 'Questionnaired2pdv93', array( 'novalidate' => 'novalidate', 'url' => $url ) );

	echo $this->Default3->subform(
		array(
			'Questionnaired2pdv93.id' => array( 'type' => 'hidden' ),
			'Questionnaired2pdv93.personne_id' => array( 'type' => 'hidden' ),
			'Questionnaired2pdv93.structurereferente_id' => array( 'type' => 'hidden' ),
			'Questionnaired2pdv93.questionnaired1pdv93_id' => array( 'type' => 'hidden' ),
			'Questionnaired2pdv93.isajax' => array( 'type' => 'hidden' ),
			'Questionnaired2pdv93.date_validation' => array( 'type' => 'hidden' ),
			'Questionnaired2pdv93.situationaccompagnement' => array(
				'options' => $options['Questionnaired2pdv93']['situationaccompagnement'],
				'empty' => true,
				'required' => true
			),
			'Questionnaired2pdv93.sortieaccompagnementd2pdv93_id' => array(
				'options' => $options['Questionnaired2pdv93']['sortieaccompagnementd2pdv93_id'],
				'empty' => true,
				'required' => true
			),
			'Questionnaired2pdv93.chgmentsituationadmin' => array(
				'options' => $options['Questionnaired2pdv93']['chgmentsituationadmin'],
				'empty' => true,
				'required' => true
			),
		)
	);

	if( $isAjax ) {
		$onComplete = 'try {
var json = request.responseText.evalJSON(true);
	if( json.success === true ) {
		var ajaxCohorteUrl = document.URL.replace( /^(https{0,1}:\/\/[^\/]+)\/.*$/gi, \'$1\' ) + cohorteUrl;
		new Ajax.Updater(
			\'Cohortesd2pdv93IndexAjaxContainer\',
			ajaxCohorteUrl,
			{
				evalScripts: true,
				onComplete: function( response ) {
					$( \'Questionnaired2pdv93ModalForm\' ).hide();
				}
			}
		);

		$( \'popup-content1\' ).update(\'\');
	}
}
catch(e) {
	console.log( e );
}';
		$submit = $this->Ajax->submit(
				__( 'Validate' ),
				array(
					'url'=> $url,
					'update' => 'popup-content1',
					'div' => false,
					'name' => 'Validate',
					// INFO: sinon, le premier bouton submit est utilisé, @see https://prototype.lighthouseapp.com/projects/8886/tickets/672-formserialize-and-multiple-submit-buttons
					'before' => '$$( "input[name=Cancel]" ).each( function( button ) { $(button).disable(); $(button).hide(); } );',
					'complete' => $onComplete,
				)
			)
			.' '
			.$this->Ajax->submit(
				__( 'Cancel' ),
				array(
					'url'=> $url,
					'update' => 'popup-content1',
					'div' => false,
					'name' => 'Cancel',
					// INFO: sinon, le premier bouton submit est utilisé, @see https://prototype.lighthouseapp.com/projects/8886/tickets/672-formserialize-and-multiple-submit-buttons
					'before' => '$$( "input[name=Validate]" ).each( function( button ) { $(button).disable(); $(button).hide(); } );',
					'complete' => $onComplete,
				)
		);

		echo $this->Html->tag( 'div', $submit, array( 'class' => 'submit' ) );
	}
	else {
		echo $this->Default3->DefaultForm->buttons( array( 'Validate', 'Cancel' ) );
	}
?>
<script type="text/javascript">
	//<![CDATA[
	observeDisableFieldsOnValue(
		'Questionnaired2pdv93Situationaccompagnement',
		[ 'Questionnaired2pdv93Sortieaccompagnementd2pdv93Id' ],
		[ 'sortie_obligation' ],
		false,
		false
	);
	observeDisableFieldsOnValue(
		'Questionnaired2pdv93Situationaccompagnement',
		[ 'Questionnaired2pdv93Chgmentsituationadmin' ],
		[ 'changement_situation' ],
		false,
		false
	);
	//]]>
</script>