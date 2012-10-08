<?php
	$this->pageTitle = 'Accompagnements du CUI';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}

	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );
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


		dependantSelect( 'Accompagnementcui66MetieraffectationId', 'Accompagnementcui66SecteuraffectationId' );
		try { $( 'Accompagnementcui66MetieraffectationId' ).onchange(); } catch(id) { }

	});
</script>

<div class="with_treemenu">
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
				echo $this->Default->subform(
					array(
						'Accompagnementcui66.nomentaccueil' => array( 'required' => true ),
						'Accompagnementcui66.numvoieentaccueil',
						'Accompagnementcui66.typevoieentaccueil' => array( 'options' => $options['typevoie'] ),
						'Accompagnementcui66.nomvoieentaccueil',
						'Accompagnementcui66.compladrentaccueil',
						'Accompagnementcui66.codepostalentaccueil',
						'Accompagnementcui66.villeentaccueil',
						'Accompagnementcui66.numtelentaccueil',
						'Accompagnementcui66.emailentaccueil',
						'Accompagnementcui66.activiteentaccueil',
						'Accompagnementcui66.siretentaccueil'
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
						'Accompagnementcui66.datedebperiode' => array( 'dateFormat' => 'DMY', 'minYear' => date('Y')-2, 'maxYear' => date('Y')+2, 'empty' => false ),
						'Accompagnementcui66.datefinperiode' => array( 'dateFormat' => 'DMY', 'minYear' => date('Y')-2, 'maxYear' => date('Y')+2, 'empty' => false )
					),
					array(
						'options' => $options
					)
				);
			?>
			<table class="periodeimmersion wide aere noborder">
				<tr>
					<td class="noborder mediumSize">Soit un nombre de jours équivalent à </td>
					<td class="noborder mediumSize" id="Accompagnementcui66Nbjourperiode"></td>
				</tr>
			</table>
			<?php
				echo $this->Default->subform(
					array(
						'Accompagnementcui66.secteuraffectation_id' => array( 'empty' => true, 'options' => $secteursactivites ),
						'Accompagnementcui66.metieraffectation_id' => array( 'empty' => true, 'options' => $options['Coderomemetierdsp66'] ),
						'Accompagnementcui66.objectifimmersion' => array( 'required' => true, 'type' => 'radio', 'separator' => '<br />', 'options' => $options['Accompagnementcui66']['objectifimmersion'] ),
						'Accompagnementcui66.datesignatureimmersion' => array( 'dateFormat' => 'DMY', 'minYear' => date('Y')-2, 'maxYear' => date('Y')+2, 'empty' => false )
					),
					array(
						'options' => $options
					)
				);
			?>
		</fieldset>


		<script type="text/javascript" >
			function calculNbDays() {
				var Datedebperiode = $F( 'Accompagnementcui66DatedebperiodeDay' );
				var Datefinperiode = $F( 'Accompagnementcui66DatefinperiodeDay' );
				$( 'Accompagnementcui66Nbjourperiode' ).update( ( Datefinperiode - Datedebperiode ) );
			}

			$( 'Accompagnementcui66DatefinperiodeDay' ).observe( 'blur', function( event ) { calculNbDays(); } );
		</script>
	</fieldset>

	<fieldset id="formations" class="invisible">
		<p class="notice">En cours de développement .....</p>
	</fieldset>


	<div class="submit">
		<?php echo $this->Form->submit( 'Enregistrer', array( 'div' => false ) );?>
		<?php echo $this->Form->submit('Annuler', array( 'name' => 'Cancel', 'div' => false ) );?>
	</div>
	<?php echo $this->Form->end();?>
</div>

<div class="clearer"><hr /></div>