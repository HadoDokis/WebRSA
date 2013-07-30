<?php
	$this->pageTitle = 'Accompagnements du CUI';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		observeDisableFieldsetOnValue(
			'Accompagnementcui66Typeaccompagnementcui66',
			$( 'immersion' ),
			['immersion'],
			false,
			true
		);

		observeDisableFieldsetOnValue(
			'Accompagnementcui66Typeaccompagnementcui66',
			$( 'formations' ),
			['formation'],
			false,
			true
		);

        observeDisableFieldsetOnValue(
			'Accompagnementcui66Typeaccompagnementcui66',
			$( 'bilans' ),
			['bilan'],
			false,
			true
		);

		dependantSelect( 'Periodeimmersioncui66MetieraffectationId', 'Periodeimmersioncui66SecteuraffectationId' );
		try { $( 'Periodeimmersioncui66MetieraffectationId' ).onchange(); } catch(id) { }
        
        dependantSelect( 'Bilancui66Refsuivicui66Id', 'Bilancui66Orgsuivicui66Id' );
		try { $( 'Bilancui66Refsuivicui66Id' ).onchange(); } catch(id) { }

	});
</script>

<?php
	echo $this->Xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'accompagnementcui66', "Accompagnementcui66::{$this->action}" )
	);
?>

<?php
	echo $this->Xform->create( 'Accompagnementcui66', array( 'id' => 'accompagnementcui66form' ) );
	if( Set::check( $this->request->data, 'Accompagnementcui66.id' ) ){
		echo $this->Xform->input( 'Accompagnementcui66.id', array( 'type' => 'hidden' ) );
	}

	echo $this->Xform->input( 'Accompagnementcui66.cui_id', array( 'type' => 'hidden', 'value' => $cui_id ) );
	echo $this->Xform->input( 'Accompagnementcui66.user_id', array( 'type' => 'hidden', 'value' => $userConnected ) );

	echo $this->Xform->input( 'Accompagnementcui66.typeaccompagnementcui66', array( 'required' => true, 'label' => __d( 'accompagnementcui66', 'Accompagnementcui66.typeaccompagnementcui66' ), 'type' => 'select', 'options' => $options['Accompagnementcui66']['typeaccompagnementcui66'], 'empty' => true ) );
?>

<fieldset id="immersion" class="invisible">
	<fieldset>
		<legend>L'ENTREPRISE D'ACCUEIL</legend>
		<?php
            if( Set::check( $this->request->data, 'Periodeimmersioncui66.id' ) ){
                echo $this->Xform->input( 'Periodeimmersioncui66.id', array( 'type' => 'hidden' ) );
            }
			echo $this->Default->subform(
				array(
                    'Periodeimmersioncui66.accompagnementcui66_id' => array( 'type' => 'hidden' ),
					'Periodeimmersioncui66.nomentaccueil' => array( 'required' => true ),
					'Periodeimmersioncui66.numvoieentaccueil',
					'Periodeimmersioncui66.typevoieentaccueil' => array( 'options' => $options['typevoie'] ),
					'Periodeimmersioncui66.nomvoieentaccueil',
					'Periodeimmersioncui66.compladrentaccueil',
					'Periodeimmersioncui66.codepostalentaccueil',
					'Periodeimmersioncui66.villeentaccueil',
					'Periodeimmersioncui66.activiteentaccueil'
				),
				array(
					'options' => $options
				)
			);
		?>
	</fieldset>
	<fieldset>
		<legend>PÉRIODE D'IMMERSION</legend>
		<?php
			echo $this->Default->subform(
				array(
					'Periodeimmersioncui66.datedebperiode' => array( 'dateFormat' => 'DMY', 'minYear' => date('Y')-2, 'maxYear' => date('Y')+2, 'empty' => false ),
					'Periodeimmersioncui66.datefinperiode' => array( 'dateFormat' => 'DMY', 'minYear' => date('Y')-2, 'maxYear' => date('Y')+2, 'empty' => false )
				),
				array(
					'options' => $options
				)
			);
		?>
		<table class="periodeimmersion wide aere noborder">
			<tr>
				<td class="noborder mediumSize">Soit un nombre de jours équivalent à </td>
				<td class="noborder mediumSize" id="Periodeimmersioncui66Nbjourperiode"></td>
			</tr>
		</table>
		<?php
			echo $this->Default->subform(
				array(
					'Periodeimmersioncui66.secteuraffectation_id' => array( 'empty' => true, 'options' => $secteursactivites ),
					'Periodeimmersioncui66.metieraffectation_id' => array( 'empty' => true, 'options' => $options['Coderomemetierdsp66'] ),
					'Periodeimmersioncui66.objectifimmersion' => array( 'required' => true, 'type' => 'radio', 'separator' => '<br />', 'options' => $options['Periodeimmersioncui66']['objectifimmersion'] ),
					'Periodeimmersioncui66.datesignatureimmersion' => array( 'dateFormat' => 'DMY', 'minYear' => date('Y')-2, 'maxYear' => date('Y')+2, 'empty' => false )
				),
				array(
					'options' => $options
				)
			);
		?>
	</fieldset>


	<script type="text/javascript" >
		document.observe( "dom:loaded", function() {
			<?php $fields = array( 'Periodeimmersioncui66Datedebperiode', 'Periodeimmersioncui66Datefinperiode' ); ?>
			<?php foreach( $fields as $field ):?>
				<?php foreach( array( 'Day', 'Month', 'Year' ) as $suffix ):?>
					Event.observe( $( '<?php echo "{$field}{$suffix}";?>' ), 'change', function() {
						updateFieldFromDatesInterval(  '<?php echo $fields[0];?>', '<?php echo $fields[1];?>', 'Periodeimmersioncui66Nbjourperiode' );
					} );
				<?php endforeach;?>
			<?php endforeach;?>
			
			updateFieldFromDatesInterval( 'Periodeimmersioncui66Datedebperiode', 'Periodeimmersioncui66Datefinperiode', 'Periodeimmersioncui66Nbjourperiode' );
		} );
	</script>
</fieldset>

<fieldset id="formations" class="invisible">
	<p class="notice">En cours de développement .....</p>
</fieldset>

<fieldset id="bilans" class="invisible">
    <fieldset>
        <legend>Bilan d'accompagnement</legend>
        <?php
            if( Set::check( $this->request->data, 'Bilancui66.id' ) ){
                echo $this->Xform->input( 'Bilancui66.id', array( 'type' => 'hidden' ) );
            }
        ?>
        <fieldset>
            <legend>Période du bilan</legend>
            <?php
                echo $this->Default->subform(
                    array(
                        'Bilancui66.accompagnementcui66_id' => array( 'type' => 'hidden' ),
                        'Bilancui66.datedebut' => array( 'dateFormat' => 'DMY', 'minYear' => date('Y')-10, 'maxYear' => date('Y')+2 ),
                        'Bilancui66.datefin' => array( 'dateFormat' => 'DMY', 'minYear' => date('Y')-10, 'maxYear' => date('Y')+2 )
                    ),
                    array(
                        'options' => $options
                    )
                );
            ?>
        </fieldset>
        <fieldset class="invisible">
            <?php
                echo $this->Default->subform(
                    array(
                        'Bilancui66.orgsuivicui66_id' => array( 'options' => $structs, 'empty' => true ),
                        'Bilancui66.refsuivicui66_id' => array( 'options' => $prestataires, 'empty' => true ),
                        'Bilancui66.observation',
                        'Bilancui66.datesignaturebilan' => array( 'dateFormat' => 'DMY', 'minYear' => date('Y')-10, 'maxYear' => date('Y')+2 )
                    ),
                    array(
                        'options' => $options
                    )
                );
            ?>
        </fieldset>
    </fieldset>
</fieldset>

<div class="submit">
	<?php echo $this->Form->submit( 'Enregistrer', array( 'div' => false ) );?>
	<?php echo $this->Form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
</div>
<?php echo $this->Form->end();?>