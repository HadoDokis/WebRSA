<?php
echo '<table id="Decisionreorientationep93" class="tooltips"><thead>
<tr>
<th>Personne</th>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Motif de la demande</th>
<th>Orientation actuelle</th>
<th>Structure référente actuelle</th>
<th>Orientation préconisée</th>
<th>Structure référente préconisée</th>
<th>Avis EP</th>
<th colspan=\'4\'>Décision CG</th>
<th>Observations</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		$decisionep = $dossierep['Passagecommissionep'][0]['Decisionreorientationep93'][count($dossierep['Passagecommissionep'][0]['Decisionreorientationep93'])-1];
		if( $decisionep['decision'] != 'accepte' ) {
			$decisionep['Typeorient']['lib_type_orient'] = null;
			$decisionep['Structurereferente']['lib_struc'] = null;
		}
		
		$innerTable = "<table id=\"innerTableDecisionreorientationep93{$i}\" class=\"innerTable\">
			<tbody>
				<tr>
					<th>Observations de l'EP</th>
					<td>".Set::classicExtract( $decisionep, "commentaire" )."</td>
				</tr>
			</tbody>
		</table>";

		$hiddenFields = $form->input( "Decisionreorientationep93.{$i}.id", array( 'type' => 'hidden' ) ).
						$form->input( "Decisionreorientationep93.{$i}.etape", array( 'type' => 'hidden', 'value' => 'cg' ) ).
						$form->input( "Decisionreorientationep93.{$i}.passagecommissionep_id", array( 'type' => 'hidden' ) );

		echo $xhtml->tableCells(
			array(
				$dossierep['Personne']['id'],
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				$dossierep['Reorientationep93']['Motifreorientep93']['name'],
				$dossierep['Reorientationep93']['Orientstruct']['Typeorient']['lib_type_orient'],
				$dossierep['Reorientationep93']['Orientstruct']['Structurereferente']['lib_struc'],
				@$dossierep['Reorientationep93']['Typeorient']['lib_type_orient'],
				@$dossierep['Reorientationep93']['Structurereferente']['lib_struc'],
				implode( ' / ', Set::filter( array( $options['Decisionreorientationep93']['decision'][$decisionep['decision']], @$decisionep['Typeorient']['lib_type_orient'], @$decisionep['Structurereferente']['lib_struc'], $decisionep['raisonnonpassage'] ) ) ),

				$form->input( "Decisionreorientationep93.{$i}.decisionpcg", array( 'legend' => false, 'options' => @$options['Decisionreorientationep93']['decisionpcg'], 'empty' => true, 'type' => 'radio' ) ),
				array(
					$form->input( "Decisionreorientationep93.{$i}.decision", array( 'label' => false, 'options' => @$options['Decisionreorientationep93']['decision'], 'empty' => true ) ),
					array( 'id' => "Decisionreorientationep93{$i}DecisionColumn", 'class' => ( !empty( $this->validationErrors['Decisionreorientationep93'][$i]['decision'] ) ? 'error' : '' ) )
				),
				array(
					$form->input( "Decisionreorientationep93.{$i}.typeorient_id", array( 'label' => false, 'options' => $typesorients, 'empty' => true ) ),
					( !empty( $this->validationErrors['Decisionreorientationep93'][$i]['typeorient_id'] ) ? array( 'class' => 'error' ) : array() )
				),
				array(
					$form->input( "Decisionreorientationep93.{$i}.structurereferente_id", array( 'label' => false, 'options' => $structuresreferentes, 'empty' => true ) ),
					( !empty( $this->validationErrors['Decisionreorientationep93'][$i]['structurereferente_id'] ) ? array( 'class' => 'error' ) : array() )
				),
				$form->input( "Decisionreorientationep93.{$i}.commentaire", array( 'label' =>false, 'type' => 'textarea' ) ).
				$hiddenFields,
				array( $innerTable, array( 'class' => 'innerTableCell noprint' ) )
			),
			array( 'class' => 'odd' ),
			array( 'class' => 'even' )
		);
	}
	echo '</tbody></table>';
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>
			dependantSelect( 'Decisionreorientationep93<?php echo $i?>StructurereferenteId', 'Decisionreorientationep93<?php echo $i?>TypeorientId' );

			observeDisableFieldsOnValue(
				'Decisionreorientationep93<?php echo $i;?>Decision',
				[ 'Decisionreorientationep93<?php echo $i;?>TypeorientId', 'Decisionreorientationep93<?php echo $i;?>StructurereferenteId' ],
				'accepte',
				false
			);

			$( 'Decisionreorientationep93<?php echo $i;?>DecisionpcgEnattente' ).observe( 'click', function() {
				$( 'Decisionreorientationep93<?php echo $i;?>Decision' ).setValue( 'reporte' );
				fireEvent( $( 'Decisionreorientationep93<?php echo $i;?>Decision' ),'change');
			} );

			$( 'Decisionreorientationep93<?php echo $i;?>Decision' ).observe( 'change', function() {
				changeColspanFormAnnuleReporteEps( 'Decisionreorientationep93<?php echo $i;?>DecisionColumn', 3, 'Decisionreorientationep93<?php echo $i;?>Decision', [ 'Decisionreorientationep93<?php echo $i;?>TypeorientId', 'Decisionreorientationep93<?php echo $i;?>StructurereferenteId' ] );
			});
			changeColspanFormAnnuleReporteEps( 'Decisionreorientationep93<?php echo $i;?>DecisionColumn', 3, 'Decisionreorientationep93<?php echo $i;?>Decision', [ 'Decisionreorientationep93<?php echo $i;?>TypeorientId', 'Decisionreorientationep93<?php echo $i;?>StructurereferenteId' ] );
		<?php endfor;?>
	});
</script>