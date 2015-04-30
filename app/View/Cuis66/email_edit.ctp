<?php
	echo $this->FormValidator->generateJavascript();
	echo $this->Default3->titleForLayout();

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ), array( 'inline' => false ) );
	}
		
	echo $this->Default3->DefaultForm->create( null, array( 'novalidate' => 'novalidate', 'id' => 'CuiEmailAddEditForm' ) );

/***********************************************************************************
 * Formulaire E-mail
/***********************************************************************************/
	
	echo '<fieldset><legend id="Cui66Choixformulaire">' . __d('cuis66', 'Emailcui.entete_email') . '</legend>'
		. $this->Default3->subform(
			array(
				'Emailcui.id' => array( 'type' => 'hidden' ),
				'Emailcui.cui_id' => array( 'type' => 'hidden' ),
				'Emailcui.cui66_id' => array( 'type' => 'hidden' ),
				'Emailcui.personne_id' => array( 'type' => 'hidden' ),
				'Emailcui.partenairecui_id' => array( 'type' => 'hidden' ),
				'Emailcui.partenairecui66_id' => array( 'type' => 'hidden' ),
				'Emailcui.adressecui_id' => array( 'type' => 'hidden' ),
				'Emailcui.emailredacteur',
				'Emailcui.emailemployeur',
				'Emailcui.insertiondate' => array( 'dateFormat' => 'DMY', 'minYear' => '2009', 'maxYear' => date('Y')+1 ),
				'Emailcui.commentaire',
			) ,
			array( 'options' => $options )
		)
		. '<fieldset><legend>' . __d( 'cuis66', 'Emailcui.chargermodel' ) . '</legend>'
		. $this->Default3->subform(
			array(
				'Emailcui.textmailcui66_id'
			),
			array( 'options' => $options )
		)
		. '<div class="submit"><input type="button" id="LoadEmailModel" value="Générer l\'e-mail" /></div></fieldset></fieldset><fieldset><legend>' . __d('cuis66', 'Emailcui.email') . '</legend>'
		. $this->Default3->subform(
			array(
				'Emailcui.titre',
				'Emailcui.message',
				'Emailcui.pj' => array( 'type' => 'select', 'multiple' => 'checkbox', 'options' => $options['Piecemailcui66'] ),
			),
			array( 'options' => $options )
		) . '</fieldset>'
	;

	echo $this->Default3->DefaultForm->buttons( array( 'Save', 'Cancel' ) );
	echo $this->Default3->DefaultForm->end();
	echo $this->Observer->disableFormOnSubmit( 'CuiEmailAddEditForm' );
	
?>
<script>
	/**
	 * Bouton Charger de Textmaicui66.id
	 * @returns {void}
	 */
	$('LoadEmailModel').onclick = function(){
		var insertDate = $F('EmailcuiInsertiondateYear') + '-' + $F('EmailcuiInsertiondateMonth') + '-' + $F('EmailcuiInsertiondateDay');
		new Ajax.Request('/cuis66/ajax_generate_email/', {
			asynchronous:true, 
			evalScripts:true, 
			parameters: {
				'Emailcui.id': $F('EmailcuiId'),
				'Cui.id': $F('EmailcuiCuiId'),
				'Cui66.id': $F('EmailcuiCui66Id'),
				'Emailcui.textmailcui66_id': $F('EmailcuiTextmailcui66Id'),
				'Emailcui.insertiondate': insertDate,
				'Emailcui.commentaire': $F('EmailcuiCommentaire')
			}, 
			requestHeaders: {Accept: 'application/json'},
			onComplete:function(request, json) {
				$('EmailcuiTitre').value = json.EmailcuiTitre;
				$('EmailcuiMessage').value = json.EmailcuiMessage;
				if ( json.EmailcuiMessage.indexOf('[[[----------ERREURS----------]]]') >= 0 ){
					$$('input[type="submit"][name="Save"]').each(function( button ){
						button.disabled = true;
					});
				}
				else{
					$$('input[type="submit"][name="Save"]').each(function( button ){
						button.disabled = false;
					});
				}
			}
		});
	};
	
</script>