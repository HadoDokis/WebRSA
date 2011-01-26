<?php
	if( Configure::read( 'debug' ) ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>
<h1><?php echo $this->pageTitle = __d( 'relancenonrespectsanctionep93', 'Relancesnonrespectssanctionseps93::cohorte', true );?></h1>

<?php if( is_array( $this->data ) ):?>
	<ul class="actionMenu">
		<?php
			echo '<li>'.$xhtml->link(
				$xhtml->image(
					'icons/application_form_magnify.png',
					array( 'alt' => '' )
				).' Formulaire',
				'#',
				array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "var form = $$( 'form' ); form = form[0]; $( form ).toggle(); return false;" )
			).'</li>';
		?>
	</ul>
<?php endif;?>

<?php
	// Formulaire
	echo $xform->create();

	echo $xhtml->tag( 'fieldset', $xhtml->tag( 'legend', 'Recherche par bénéficiaire' ).
		$default->subform(
			array(
				'Personne.nom' => array( 'type' => 'text', 'required' => false ),
				'Personne.nomnai' => array( 'type' => 'text' ),
				'Personne.prenom' => array( 'type' => 'text', 'required' => false ),
				'Personne.nir' => array( 'type' => 'text' ),
				'Adresse.numcomptt' => array( 'required' => false ),
				'Serviceinstructeur.id' => array( 'domain' => 'relancenonrespectsanctionep93' ),// suiviinstruction
			),
			array(
				'options' => $options
			)
		)
	);

	echo $xhtml->tag( 'fieldset', $xhtml->tag( 'legend', 'Recherche par dossier CAF' ).
		$default->subform(
			array(
				'Dossier.matricule' => array( 'type' => 'text' ),
				'Dossiercaf.nomtitulaire' => array( 'type' => 'text' ),
				'Dossiercaf.prenomtitulaire' => array( 'type' => 'text' ),
			)
		)
	);

	$comparators = array( '<' => '<' ,'>' => '>','<=' => '<=', '>=' => '>=' );

	echo '<fieldset><legend>Présence contrat</legend><div class="input">';
	echo '<fieldset><legend><input name="data[Relance][contrat]" id="RelanceContrat0" value="0" '.( ( @$this->data['Relance']['contrat'] == 0 ) ? 'checked="checked"' : '' ).' type="radio" /><label for="RelanceContrat0">Personne orientée sans contrat</label></legend>'.
		'<div>'.
			$form->input( 'Relance.compare0', array( 'label' => 'Opérateurs', 'type' => 'select', 'options' => $comparators, 'empty' => true ) ).
			$form->input( 'Relance.nbjours0', array( 'label' => 'Nombre de jours depuis l\'orientation<span id="nbjoursmin0"></span>', 'type' => 'text' ) ).
		'</div>'.
		'</fieldset>';
	echo '<fieldset><legend><input name="data[Relance][contrat]" id="RelanceContrat1" value="1" '.( ( @$this->data['Relance']['contrat'] == 1 ) ? 'checked="checked"' : '' ).' type="radio" /><label for="RelanceContrat1">Personne orientée avec contrat</label></legend>'.
		'<div>'.
			$form->input( 'Relance.compare1', array( 'label' => 'Opérateurs', 'type' => 'select', 'options' => $comparators, 'empty' => true ) ).
			$form->input( 'Relance.nbjours1', array( 'label' => 'Nombre de jours depuis la fin du dernier contrat<span id="nbjoursmin1"></span>', 'type' => 'text' ) ).
		'</div>'.
		'</fieldset>';
	echo '</div></fieldset>';

	echo $default2->subform(
		array(
// 			'Relance.contrat' => array( 'label' => 'Présence contrat', 'type' => 'radio', 'options' => array( 0 => 'Personne orientée sans contrat', 1 => 'Personne orientée avec contrat' ), 'value' => ( isset( $this->data['Relance']['contrat'] ) ? @$this->data['Relance']['contrat'] : 0 ) ),
			'Relance.numrelance' => array( 'label' => 'Type de relance à réaliser', 'type' => 'radio', 'options' => array( 1 => 'Première relance', 2 => 'Seconde relance', 3 => 'Troisième relance' ), 'value' => ( isset( $this->data['Relance']['numrelance'] ) ? @$this->data['Relance']['numrelance'] : 1 ) ),
		),
		array(
			'options' => $options
		)
	);

	echo $xform->end( __( 'Rechercher', true ) );
	// Résultats
	if( isset( $results ) ) {
		if( empty( $results ) ) {
			echo $xhtml->tag( 'p', 'Aucun résultat ne correspond à ces critères.', array( 'class' => 'notice' ) );
		}
		else {
			echo $xform->create( null, array( 'id' => 'Relancenonrespectsanctionep93Form' ) );

			foreach( Set::flatten( $this->data ) as $key => $data ) {
				if( !preg_match( '/^Relancenonrespectsanctionep93\./', $key ) && !( trim( $data ) == '' ) ) {
					echo $xform->input( $key, array( 'type' => 'hidden', 'value' => $data ) );
				}
			}

			echo '<table class="tooltips" style="width: 100%;">
				<thead>
					<tr>
						<th>N° CAF</th>
						<th>Nom / Prénom Allocataire</th>
						<th>NIR</th>
						<th>Nom de commune</th>
						'.( ( $this->data['Relance']['contrat'] == 0 ) ? '<th>Date d\'orientation</th>' : '' ).'
						'.( ( $this->data['Relance']['contrat'] == 0 ) ? '<th>Date de notification d\'orientation</th>' : '' ).'
						'.( ( $this->data['Relance']['contrat'] == 0 ) ? '<th>Nombre de jours depuis la notification d\'orientation</th>' : '' ).'
						'.( ( $this->data['Relance']['contrat'] == 1 ) ? '<th>Date de fin du contrat</th>' : '' ).'
						'.( ( $this->data['Relance']['contrat'] == 1 ) ? '<th>Nombre de jours depuis la fin du contrat</th>' : '' ).'
						'.( ( $this->data['Relance']['numrelance'] == 2 ) ? '<th>Date de première relance</th>' : '' ).'
						'.( ( $this->data['Relance']['numrelance'] == 3 ) ? '<th>Date de seconde relance</th>' : '' ).'
						<!--<th>Date d\'échéance minimale</th>-->
						<th style="width: 19em;">'.__d( 'relancenonrespectsanctionep93', 'Relancenonrespectsanctionep93.daterelance', true ).'</th>
						<th style="width: 8em;">'.__d( 'relancenonrespectsanctionep93', 'Relancenonrespectsanctionep93.arelancer', true ).'</th>
						<th class="innerTableHeader">Informations complémentaires</th>
					</tr>
				</thead>
				<tbody>';
				foreach( $results as $index => $result ) {
					$innerTable = '<table id="innerTable'.$index.'" class="innerTable">
						<tbody>
							<tr>
								<th>Date naissance</th>
								<td>'.h( date_short( @$result['Personne']['dtnai'] ) ).'</td>
							</tr>
						</tbody>
					</table>';

					$row = array(
						h( @$result['Dossier']['matricule'] ),
						h( @$result['Personne']['nom'].' '.@$result['Personne']['prenom'] ),
						h( @$result['Personne']['nir'] ),
						h( @$result['Adresse']['locaadr'] )
					);

					if( $this->data['Relance']['contrat'] == 0 ) {
						$row[] = h( date_short( @$result['Orientstruct']['date_valid'] ) );
						$row[] = h( date_short( @$result['Orientstruct']['date_impression'] ) );
						$row[] = h( @$result['Orientstruct']['nbjours'] );
					}
					else {
						$row[] = date_short( @$result['Contratinsertion']['df_ci'] );
						$row[] = h( @$result['Contratinsertion']['nbjours'] );
					}

					if( $this->data['Relance']['numrelance'] >  1 && $this->data['Relance']['numrelance'] <= 3 ) {
						$row[] = date_short( @$result['Relancenonrespectsanctionep93']['daterelance'] );
					}

					$row = Set::merge(
						$row,
						array(
							( ( @$this->data['Relance']['numrelance'] > 1 ) ? $xform->input( "Relancenonrespectsanctionep93.{$index}.nonrespectsanctionep93_id", array( 'type' => 'hidden', 'value' => @$result['Nonrespectsanctionep93']['id'] ) ) : '' ).
							$xform->input( "Relancenonrespectsanctionep93.{$index}.numrelance", array( 'type' => 'hidden', 'value' => @$this->data['Relance']['numrelance'] ) ).
							$xform->input( "Relancenonrespectsanctionep93.{$index}.orientstruct_id", array( 'type' => 'hidden', 'value' => @$result['Orientstruct']['id'] ) ).
							$xform->input( "Relancenonrespectsanctionep93.{$index}.contratinsertion_id", array( 'type' => 'hidden', 'value' => @$result['Contratinsertion']['id'] ) ).
							$xform->input( "Relancenonrespectsanctionep93.{$index}.daterelance", array( 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 5, 'minYear' => date( 'Y' ) - 5, 'label' => false ) ),
							$xform->input( "Relancenonrespectsanctionep93.{$index}.arelancer", array( 'type' => 'radio', 'options' => array( 'R' => 'Relancer', 'E' => 'En attente' ), 'legend' => false, 'div' => false, 'separator' => '<br />', 'value' => ( isset( $this->data['Relancenonrespectsanctionep93'][$index]['arelancer'] ) ? @$this->data['Relancenonrespectsanctionep93'][$index]['arelancer'] : 'E' ) ) ),
							array( $innerTable, array( 'class' => 'innerTableCell' ) )
						)
					);

					echo $xhtml->tableCells(
						$row,
						array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
						array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
					);
				}
			echo '</tbody></table>';
			echo $xform->end( __( 'Save', true ) );
		}
	}
?>

<?php if( isset( $results ) ):?>
	<script type="text/javascript">
		<?php foreach( $results as $index => $result ):?>
		observeDisableFieldsOnRadioValue(
			'Relancenonrespectsanctionep93Form',
			'data[Relancenonrespectsanctionep93][<?php echo $index;?>][arelancer]',
			[
				'Relancenonrespectsanctionep93<?php echo $index;?>DaterelanceDay',
				'Relancenonrespectsanctionep93<?php echo $index;?>DaterelanceMonth',
				'Relancenonrespectsanctionep93<?php echo $index;?>DaterelanceYear'
			],
			'E',
			false
		);
		<?php endforeach;?>
	</script>
<?php endif;?>

<script type="text/javascript">
	// Ne désactive que la valeur
	function disableFieldsOnRadioValue2( form, radioName, fieldsIds, value, condition ) {
		var v = $( form ).getInputs( 'radio', radioName );
		var currentValue = undefined;
		$( v ).each( function( radio ) {
			if( radio.checked ) {
				currentValue = radio.value;
			}
		} );


		var disabled = !( ( currentValue == value ) == condition );

		fieldsIds.each( function ( fieldId ) {
			var field = $( fieldId );
			if( !disabled ) {
				field.enable();
				/*var label = $$( 'label[for=' + fieldId + ']' );
				$( label ).removeClassName( 'disabled' );*/
			}
			else {
				field.disable();
				/*var label = $$( 'label[for=' + fieldId + ']' );
				$( label ).addClassName( 'disabled' );*/
			}
		} );
	}

	function observeDisableFieldsOnRadioValue2( form, radioName, fieldsIds, value, condition ) {
		disableFieldsOnRadioValue2( form, radioName, fieldsIds, value, condition );

		var v = $( form ).getInputs( 'radio', radioName );
		var currentValue = undefined;
		$( v ).each( function( radio ) {
			$( radio ).observe( 'change', function( event ) {
				disableFieldsOnRadioValue2( form, radioName, fieldsIds, value, condition );
			} );
		} );
	}

	var form = $$( 'form' );
	form = form[0];

	<?php if( isset( $results ) ):?>$( form ).hide();<?php endif;?>

	observeDisableFieldsOnRadioValue2(
		form,
		'data[Relance][contrat]',
		[ 'RelanceNumrelance3' ],
		'1',
		false
	);

	observeDisableFieldsOnRadioValue(
		form,
		'data[Relance][contrat]',
		[ 'RelanceCompare0', 'RelanceNbjours0' ],
		'1',
		false
	);

	observeDisableFieldsOnRadioValue(
		form,
		'data[Relance][contrat]',
		[ 'RelanceCompare1', 'RelanceNbjours1' ],
		'0',
		false
	);

	document.observe("dom:loaded", function() {
		[ $('RelanceContrat0'), $('RelanceContrat1'), $('RelanceNumrelance1'), $('RelanceNumrelance2'), $('RelanceNumrelance3') ].each( function(field) {
			field.observe('change', function() {
				updateNbJours(findContrat(), findRelance());
			} );
		} );
		updateNbJours(findContrat(), findRelance());
	});

	function findRelance() {
		if ($('RelanceNumrelance1').checked==true)
			return 1;
		else if ($('RelanceNumrelance2').checked==true)
			return 2;
		else if ($('RelanceNumrelance3').checked==true)
			return 3;
	}

	function findContrat() {
		if ($('RelanceContrat0').checked==true)
			return 0;
		else if ($('RelanceContrat1').checked==true)
			return 1;
	}

	function updateNbJours(contrat, relance) {
		var nbJoursMin = 0;
		if (contrat == 0) {
			if (relance == 1)
				nbJoursMin = parseInt('<?php echo Configure::read( "Nonrespectsanctionep93.relanceOrientstructCer1" );?>');
			else if (relance == 2)
				nbJoursMin = parseInt('<?php echo Configure::read( "Nonrespectsanctionep93.relanceOrientstructCer1" );?>') + parseInt('<?php echo Configure::read( "Nonrespectsanctionep93.relanceOrientstructCer2" );?>');
			else if (relance == 3)
				nbJoursMin = parseInt('<?php echo Configure::read( "Nonrespectsanctionep93.relanceOrientstructCer1" );?>') + parseInt('<?php echo Configure::read( "Nonrespectsanctionep93.relanceOrientstructCer2" );?>') + parseInt('<?php echo Configure::read( "Nonrespectsanctionep93.relanceOrientstructCer3" );?>');

			$('nbjoursmin0').update(' ('+nbJoursMin+' jours minimum)');
			$('nbjoursmin1').update('');
		}
		else {
			if (relance == 1)
				nbJoursMin = parseInt('<?php echo Configure::read( "Nonrespectsanctionep93.relanceCerCer1" );?>');
			else if (relance == 2)
				nbJoursMin = parseInt('<?php echo Configure::read( "Nonrespectsanctionep93.relanceCerCer1" );?>') + parseInt('<?php echo Configure::read( "Nonrespectsanctionep93.relanceCerCer2" );?>');

			if (nbJoursMin > 0)
				$('nbjoursmin1').update(' ('+nbJoursMin+' jours minimum)');
			else
				$('nbjoursmin1').update(' (merci de choisir un type de relance)');
			$('nbjoursmin0').update('');
		}
	}

</script>