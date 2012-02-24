<?php
	echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );

	echo $this->element( 'dossier_menu', array( 'foyer_id' => $foyer_id ) );
?>

<div class="with_treemenu">
	<?php
		echo $xhtml->tag(
			'h1',
			$this->pageTitle = __d( 'decisiondossierpcg66', "Decisionsdossierspcgs66::{$this->action}", true )
		);

		echo $xform->create( 'Decisiondossierpcg66', array( 'id' => 'decisiondossierpcg66form' ) );
		if( Set::check( $this->data, 'Decisiondossierpcg66.id' ) ){
			echo $xform->input( 'Decisiondossierpcg66.id', array( 'type' => 'hidden' ) );
		}
		echo $xform->input( 'Decisiondossierpcg66.dossierpcg66_id', array( 'type' => 'hidden', 'value' => $dossierpcg66_id ) );
		echo $xform->input( 'Decisiondossierpcg66.user_id', array( 'type' => 'hidden', 'value' => $userConnected ) );
	?>

	<?php if( !empty( $personnespcgs66 ) ):?>

		<table class="tooltips aere"><caption style="caption-side: top;">Informations concernant la (les) personne(s)</caption>
				<thead>
					<tr>
						<th>Personne concernée</th>
						<th>Motif(s)</th>
						<th>Statut(s)</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach( $personnespcgs66 as $personnepcg66 ) {
							//Liste des différentes situations de la personne
							$listeSituations = Set::extract( $personnepcg66, '/Situationpdo/libelle' );
							$differentesSituations = '';
							foreach( $listeSituations as $key => $situation ) {
								if( !empty( $situation ) ) {
									$differentesSituations .= $xhtml->tag( 'h3', '' ).'<ul><li>'.$situation.'</li></ul>';
								}
							}

							//Liste des différents statuts de la personne
							$listeStatuts = Set::extract( $personnepcg66, '/Statutpdo/libelle' );
							$differentsStatuts = '';
							foreach( $listeStatuts as $key => $statut ) {
								if( !empty( $statut ) ) {
									$differentsStatuts .= $xhtml->tag( 'h3', '' ).'<ul><li>'.$statut.'</li></ul>';
								}
							}
							echo $xhtml->tableCells(
								array(
									h( Set::classicExtract( $personnepcg66, 'Personne.qual' ).' '.Set::classicExtract( $personnepcg66, 'Personne.nom' ).' '.Set::classicExtract( $personnepcg66, 'Personne.prenom' ) ),
									$differentesSituations,
									$differentsStatuts
								),
								array( 'class' => 'odd' ),
								array( 'class' => 'even' )
							);
						}
					?>
				</tbody>
			</table>
	<?php else:?>
		<p class="notice">Aucune personne n'est concernée par ce dossier.</p>
	<?php  endif;?>

	<?php 
		echo "<h2>Pièces liées au dossier</h2>";
		echo $fileuploader->results( Set::classicExtract( $dossierpcg66, 'Fichiermodule' ) );
	?>
		
	
		<?php /*else:*/?>
		<fieldset><legend>Proposition du technicien</legend>
			<?php if( !empty( $dossierpcg66['Decisiondossierpcg66'] ) ):?>
				<table class="aere"><caption style="caption-side: top;">Propositions passées</caption>
					<thead>
						<tr>
							<th>Proposition de décision</th>
							<th>Date de la proposition</th>
							<th>Avis technique</th>
							<th>Commentaire de l'avis technique</th>
							<th>Validation proposition</th>
							<th>Commentaire du décideur</th>
							<th>Commentaire technicien</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach( $dossierpcg66['Decisiondossierpcg66'] as $decision ):?>
							<?php
								echo $xhtml->tableCells(
									array(
										h( Set::classicExtract( $decision, 'Decisionpdo.libelle' ) ),
										h( date_short( Set::classicExtract( $decision, 'datepropositiontechnicien' ) ) ),
										h( Set::enum( Set::classicExtract( $decision, 'avistechnique' ), $options['Decisiondossierpcg66']['validationproposition'] ) ),
										h( Set::classicExtract( $decision, 'commentaireavistechnique' ) ),
										h( Set::enum( Set::classicExtract( $decision, 'validationproposition' ), $options['Decisiondossierpcg66']['validationproposition'] ) ),
										h( Set::classicExtract( $decision, 'commentairevalidation' ) ),
										h( Set::classicExtract( $decision, 'commentairetechnicien' ) )
									),
									array( 'class' => 'odd' ),
									array( 'class' => 'even' )
								);
						?>
					<?php endforeach;?>
				</tbody>
			</table>
		<?php else:?>
			<p class="notice">Aucune proposition passée n'a encore été émise par le technicien.</p>
		<?php  endif;?>
		
		<?php if( !empty( $dossierpcg66['Dossierpcg66']['decisiondefautinsertionep66_id'] ) ):?>
		<fieldset>
			<?php

				$decision = Set::enum( $dossierpcg66['Decisiondefautinsertionep66']['decision'], $options['Decisiondefautinsertionep66']['decision'] );
				echo $xform->fieldValue( 'Decisiondossierpcg66.decision', $decision );
// debug($decisiondossierpcg66_decision);
// debug( $options );
				$decisiondossierpcg66_decision_traduit = Set::enum( $decisiondossierpcg66_decision, $options['Decisiondossierpcg66']['defautinsertion'] );
				echo $xform->fieldValue( 'Decisiondossierpcg66.defautinsertion', $decisiondossierpcg66_decision_traduit );

				echo $default2->subform(
					array(
						'Decisiondossierpcg66.defautinsertion' => array( 'type' => 'hidden', 'value' => $decisiondossierpcg66_decision	 ),
						'Decisiondossierpcg66.compofoyerpcg66_id' => array( 'type' => 'select', 'empty' => true, 'options' => $compofoyerpcg66 ),
						'Decisiondossierpcg66.recidive' => array(  'type' => 'radio', 'empty' => true ),
						'Decisiondossierpcg66.phase' => array( 'type' => 'select', 'empty' => true )
					),
					array(
						'options' => $options
					)
				); // TODO
			?>
		</fieldset>
		<?php  endif;?>
		
		<fieldset id="Propositionpcg" class="invisible"></fieldset>

			<?php

				echo $default2->subform(
					array(
						'Typersapcg66.Typersapcg66' => array( 'type' => 'select', 'label' => 'Type de RSA', 'multiple' => 'checkbox', 'empty' => false, 'options' => $typersapcg66 ),
						'Decisiondossierpcg66.decisionpdo_id' => array( 'type' => 'select', 'empty' => true, 'options' => $listdecisionpdo ),
						'Decisiondossierpcg66.datepropositiontechnicien' => array( 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear' => date('Y')+5, 'minYear' => date('Y')-1, 'empty' => false ),
						'Decisiondossierpcg66.commentairetechnicien' => array( 'value' => isset( $dossierpcg66['Decisiondossierpcg66'][0]['commentairetechnicien'] ) ? ( $this->data['Decisiondossierpcg66']['commentairetechnicien'] ) : null )
					),
					array(
						'options' => $options
					)
				);
			?>
		</fieldset>
		<?php /*endif;*/?>

	<fieldset><legend><?php echo 'Avis technique'; ?></legend>
			<?php
				echo $default2->subform(
					array(
						'Decisiondossierpcg66.avistechnique' => array( 'label' => false, 'type' => 'radio', 'options' => $options['Decisiondossierpcg66']['avistechnique'] ),
					),
					array(
						'options' => $options
					)
				);
			?>
			<fieldset id="avistech" class="noborder">
				<?php
					echo $default2->subform(
						array(
							'Decisiondossierpcg66.commentaireavistechnique',
							'Decisiondossierpcg66.dateavistechnique' => array( 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear' => date('Y')+5, 'minYear' => date('Y')-1, 'empty' => false )
						),
						array(
							'options' => $options
						)
					);
				?>
			</fieldset>
	</fieldset>

	<fieldset><legend>Validation de la proposition</legend>
			<?php
				echo $default2->subform(
					array(
						'Decisiondossierpcg66.validationproposition' => array( 'label' => false, 'type' => 'radio', 'options' => $options['Decisiondossierpcg66']['validationproposition'] ),
					),
					array(
						'options' => $options
					)
				);
			?>
			<fieldset id="validpropo" class="noborder">
				<?php
					echo $default2->subform(
					array(
						'Decisiondossierpcg66.retouravistechnique' => array( 'type' => 'checkbox' ),
						'Decisiondossierpcg66.vuavistechnique' => array( 'type' => 'checkbox' ),
						'Decisiondossierpcg66.commentairevalidation',
						'Decisiondossierpcg66.datevalidation' => array( 'type' => 'date', 'dateFormat'=>'DMY', 'maxYear' => date('Y')+5, 'minYear' => date('Y')-1, 'empty' => false )
					),
					array(
						'options' => $options
					)
				);
				?>
			</fieldset>

	</fieldset>
			<?php

				echo $default2->subform(
					array(
						'Decisiondossierpcg66.commentaire' => array( 'label' =>  'Commentaire global : ', 'type' => 'textarea', 'rows' => 3 ),
					),
					array(
						'options' => $options
					)
				);
			?>
	<?php
		echo "<div class='submit'>";
			echo $form->submit('Enregistrer', array('div'=>false));
			echo $form->button( 'Retour', array( 'type' => 'button', 'onclick'=>"location.replace('".Router::url( '/dossierspcgs66/edit/'.$dossierpcg66_id, true )."')" ) );
		echo "</div>";

		echo $form->end();
	?>

	<?php echo $xform->end();?>
</div>
<div class="clearer"></div>

<script type="text/javascript">
	document.observe("dom:loaded", function() {

		observeDisableFieldsOnValue(
			'Decisiondossierpcg66Retouravistechnique',
			[
				'Decisiondossierpcg66Vuavistechnique'
			],
			'1',
			false
		);

		observeDisableFieldsetOnRadioValue(
			'decisiondossierpcg66form',
			'data[Decisiondossierpcg66][avistechnique]',
			$( 'avistech' ),
			['O','N'],
			false,
			true
		);

		observeDisableFieldsetOnRadioValue(
			'decisiondossierpcg66form',
			'data[Decisiondossierpcg66][validationproposition]',
			$( 'validpropo' ),
			['O','N'],
			false,
			true
		);

		$( 'decisiondossierpcg66form' ).getInputs( 'radio', 'data[Decisiondossierpcg66][validationproposition]' ).each( function( radio ) {
			$( radio ).observe( 'change', function( event ) {
				disableFieldsOnValue(
					'Decisiondossierpcg66Retouravistechnique',
					[
						'Decisiondossierpcg66Vuavistechnique'
					],
					'1',
					false
				);
			} );
		} );
	} );
</script>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		[ $('Decisiondossierpcg66Compofoyerpcg66Id'), $('Decisiondossierpcg66RecidiveN'), $('Decisiondossierpcg66RecidiveO'), $('Decisiondossierpcg66Phase') ].each(function(field) {
			field.observe('change', function(element, value) {
				fieldUpdater();
			});
		});

		fieldUpdater();
	});
	
	function radioValue( form, radioName ) {
		var v = $( form ).getInputs( 'radio', radioName );

		var currentValue = null;
		$( v ).each( function( radio ) {
			if( radio.checked ) {
				currentValue = radio.value;
			}
		} );
		
		return currentValue;
	}

	function fieldUpdater() {
		new Ajax.Updater(
			'Propositionpcg',
			'<?php echo Router::url( array( "action" => "ajaxproposition" ), true ) ?>',
			{
				asynchronous:true,
				evalScripts:true,
				parameters:
				{
					'defautinsertion' : $F('Decisiondossierpcg66Defautinsertion'),
					'compofoyerpcg66_id' :  $F( 'Decisiondossierpcg66Compofoyerpcg66Id' ),
					'recidive' : radioValue( 'decisiondossierpcg66form', 'data[Decisiondossierpcg66][recidive]' ),
					'phase' : $F('Decisiondossierpcg66Phase'),
					'decisionpcg66_id' : '<?php echo @$this->data['Decisiondossierpcg66']['decisionpcg66_id'];?>'
				},
				requestHeaders:['X-Update', 'Propositionpcg']
			}
		);
	}
</script>