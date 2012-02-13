<?php
echo '<table id="Decisionnonorientationproep93" class="tooltips"><thead>
<tr>
<th>Personne</th>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Date d\'orientation</th>
<th>Orientation actuelle</th>
<th>Avis EPL</th>
<th colspan="4">Décision CG</th>
<th>Observations</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		$multiple = ( count( $dossiersAllocataires[$dossierep['Personne']['id']] ) > 1 ? 'multipleDossiers' : null );

		$niveau = count( $dossierep['Passagecommissionep'][0]['Decisionnonorientationproep93'] ) - 1;

		$innerTable = "<table id=\"innerTableDecisionnonorientationproep93{$i}\" class=\"innerTable\">
			<tbody>
				<tr>
					<th>Observations de l'EP</th>
					<td>".Set::classicExtract( $dossierep, "Passagecommissionep.0.Decisionnonorientationproep93.{$niveau}.commentaire" )."</td>
				</tr>
			</tbody>
		</table>";

		$avisep = $options['Decisionnonorientationproep93']['decision'][Set::classicExtract( $dossierep, "Passagecommissionep.0.Decisionnonorientationproep93.{$niveau}.decision" )];
		if ( $dossierep['Passagecommissionep'][0]['Decisionnonorientationproep93'][$niveau]['decision'] == 'maintienref' ) {
			$avisep .= ' - '.implode( ' - ', Set::filter( array( $dossierep['Nonorientationproep93']['Orientstruct']['Typeorient']['lib_type_orient'], $dossierep['Nonorientationproep93']['Orientstruct']['Structurereferente']['lib_struc'] ) ) );
		}
		else if ( $dossierep['Passagecommissionep'][0]['Decisionnonorientationproep93'][$niveau]['decision'] == 'reorientation' ) {
			$avisep .= ' - '.implode( ' - ', Set::filter( array( $dossierep['Passagecommissionep'][0]['Decisionnonorientationproep93'][$niveau]['Typeorient']['lib_type_orient'], $dossierep['Passagecommissionep'][0]['Decisionnonorientationproep93'][$niveau]['Structurereferente']['lib_struc'] ) ) );
		}

		$hiddenFields = $form->input( "Decisionnonorientationproep93.{$i}.id", array( 'type' => 'hidden' ) ).
						$form->input( "Decisionnonorientationproep93.{$i}.passagecommissionep_id", array( 'type' => 'hidden' ) ).
						$form->input( "Decisionnonorientationproep93.{$i}.etape", array( 'type' => 'hidden', 'value' => 'cg' ) ).
						$form->input( "Decisionnonorientationproep93.{$i}.user_id", array( 'type' => 'hidden', 'value' => $session->read( 'Auth.User.id' ) ) );

		echo $xhtml->tableCells(
			array(
				$dossierep['Personne']['id'],
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Nonorientationproep93']['Orientstruct']['date_valid'] ),

				implode( ' - ', Set::filter( array( $dossierep['Nonorientationproep93']['Orientstruct']['Typeorient']['lib_type_orient'], $dossierep['Nonorientationproep93']['Orientstruct']['Structurereferente']['lib_struc'], implode( ' ', array( @$dossierep['Nonorientationproep93']['Orientstruct']['Referent']['qual'], @$dossierep['Nonorientationproep93']['Orientstruct']['Referent']['nom'], @$dossierep['Nonorientationproep93']['Orientstruct']['Referent']['prenom'] ) ) ) ) ),

				$avisep,

				$form->input( "Decisionnonorientationproep93.{$i}.decisionpcg", array( 'legend' => false, 'options' => @$options['Decisionreorientationep93']['decisionpcg'], 'empty' => true, 'type' => 'radio' ) ),

				array(
					$form->input( "Decisionnonorientationproep93.{$i}.decision", array( 'type' => 'select', 'label' => false, 'empty' => true, 'options' => $options['Decisionnonorientationproep93']['decision'] ) ),
					array( 'id' => "Decisionnonorientationproep93{$i}DecisionColumn", 'class' => ( !empty( $this->validationErrors['Decisionnonorientationproep93'][$i]['decision'] ) ? 'error' : '' ) )
				),
				array(
					$form->input( "Decisionnonorientationproep93.{$i}.typeorient_id", array( 'type' => 'select', 'label' => false, 'options' => $typesorients, 'empty' => true ) ),
					( !empty( $this->validationErrors['Decisionnonorientationproep93'][$i]['typeorient_id'] ) ? array( 'class' => 'error' ) : array() )
				),
				array(
					$form->input( "Decisionnonorientationproep93.{$i}.structurereferente_id", array( 'label' => false, 'options' => $structuresreferentes, 'empty' => true, 'type' => 'select' ) ),
					( !empty( $this->validationErrors['Decisionnonorientationproep93'][$i]['structurereferente_id'] ) ? array( 'class' => 'error' ) : array() )
				),
				$form->input( "Decisionnonorientationproep93.{$i}.commentaire", array( 'label' =>false, 'type' => 'textarea' ) ).
				$hiddenFields,
				array( $innerTable, array( 'class' => 'innerTableCell noprint' ) )
			),
			array( 'class' => "odd {$multiple}" ),
			array( 'class' => "even {$multiple}" )
		);
	}
	echo '</tbody></table>';
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>
			dependantSelect( 'Decisionnonorientationproep93<?php echo $i?>StructurereferenteId', 'Decisionnonorientationproep93<?php echo $i?>TypeorientId' );
			try { $( 'Decisionnonorientationproep93<?php echo $i?>StructurereferenteId' ).onchange(); } catch(id) { }

			observeDisableFieldsOnValue(
				'Decisionnonorientationproep93<?php echo $i;?>Decision',
				[
					'Decisionnonorientationproep93<?php echo $i;?>TypeorientId',
					'Decisionnonorientationproep93<?php echo $i;?>StructurereferenteId'
				],
				'reorientation',
				false
			);

			$( 'Decisionnonorientationproep93<?php echo $i;?>DecisionpcgEnattente' ).observe( 'click', function() {
				$( 'Decisionnonorientationproep93<?php echo $i;?>Decision' ).setValue( 'reporte' );
				fireEvent( $( 'Decisionnonorientationproep93<?php echo $i;?>Decision' ),'change');
			} );

			$( 'Decisionnonorientationproep93<?php echo $i;?>Decision' ).observe( 'change', function() {
				changeColspanFormAnnuleReporteEps( 'Decisionnonorientationproep93<?php echo $i;?>DecisionColumn', 3, 'Decisionnonorientationproep93<?php echo $i;?>Decision', [ 'Decisionnonorientationproep93<?php echo $i;?>TypeorientId', 'Decisionnonorientationproep93<?php echo $i;?>StructurereferenteId' ] );
			});
			changeColspanFormAnnuleReporteEps( 'Decisionnonorientationproep93<?php echo $i;?>DecisionColumn', 3, 'Decisionnonorientationproep93<?php echo $i;?>Decision', [ 'Decisionnonorientationproep93<?php echo $i;?>TypeorientId', 'Decisionnonorientationproep93<?php echo $i;?>StructurereferenteId' ] );
		<?php endfor;?>
	});
</script>