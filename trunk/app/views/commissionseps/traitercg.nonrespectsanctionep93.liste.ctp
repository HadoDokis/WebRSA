<?php
echo '<table id="Decisionnonrespectsanctionep93" class="tooltips"><thead>
<tr>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Origine du dossier</th>
<th>Date d\'orientation</th>
<th>Rang du passage en EP</th>
<th>Situation familiale</th>
<th>Nombre d\'enfants</th>
<th>Avis EP</th>
<th colspan="3">Décision CG</th>
<th>Observations</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		$lineOptions = array();
		foreach( $options['Decisionnonrespectsanctionep93']['decision'] as $key => $label ) {
			if( !in_array( $key[0], array( 1, 2 ) ) || ( $key[0] == min( 2, $dossierep['Nonrespectsanctionep93']['rgpassage'] ) ) ) {
				$lineOptions[$key] = $label;
			}
		}

		$indexDecision = count( $dossierep['Passagecommissionep'][0]['Decisionnonrespectsanctionep93'] ) - 1;

		$innerTable = "<table id=\"innerTableDecisionnonrespectsanctionep93{$i}\" class=\"innerTable\">
			<tbody>
				<tr>
					<th>Observations de l'EP</th>
					<td>".Set::classicExtract( $dossierep, "Passagecommissionep.0.Decisionnonrespectsanctionep93.{$indexDecision}.commentaire" )."</td>
				</tr>
			</tbody>
		</table>";

		$hiddenFields = $form->input( "Decisionnonrespectsanctionep93.{$i}.id", array( 'type' => 'hidden' ) ).
						$form->input( "Decisionnonrespectsanctionep93.{$i}.passagecommissionep_id", array( 'type' => 'hidden' ) ).
						$form->input( "Decisionnonrespectsanctionep93.{$i}.etape", array( 'type' => 'hidden', 'value' => 'cg' ) );

		echo $xhtml->tableCells(
			array(
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				Set::enum( @$dossierep['Nonrespectsanctionep93']['origine'], $options['Nonrespectsanctionep93']['origine'] ),
				$locale->date( __( 'Locale->date', true ), @$dossierep['Nonrespectsanctionep93']['Orientstruct']['date_valid'] ),
				@$dossierep['Nonrespectsanctionep93']['rgpassage'],
				Set::enum( @$dossierep['Personne']['Foyer']['sitfam'], $options['Foyer']['sitfam'] ),
				@$dossierep['Personne']['Foyer']['nbenfants'],
				Set::enum( @$dossierep['Passagecommissionep'][0]['Decisionnonrespectsanctionep93'][$indexDecision]['decision'], $options['Decisionnonrespectsanctionep93']['decision'] ),

				$form->input( "Decisionnonrespectsanctionep93.{$i}.decisionpcg", array( 'legend' => false, 'options' => @$options['Decisionreorientationep93']['decisionpcg'], 'empty' => true, 'type' => 'radio' ) ),
				array(
					$form->input( "Decisionnonrespectsanctionep93.{$i}.decision", array( 'type' => 'select', 'options' => $lineOptions, 'div' => false, 'label' => false ) ),
					array( 'id' => "Decisionnonrespectsanctionep93{$i}ColumnDecision", 'colspan' => 2, 'class' => ( !empty( $this->validationErrors['Decisionnonrespectsanctionep93'][$i]['decision'] ) ? 'error' : '' ) )
				),
// 				$form->input( "Decisionnonrespectsanctionep93.{$i}.raisonnonpassage", array( 'label' => false, 'type' => 'textarea' ) ),
				$form->input( "Decisionnonrespectsanctionep93.{$i}.commentaire", array( 'label' =>false, 'type' => 'textarea' ) ).
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
			$( 'Decisionnonrespectsanctionep93<?php echo $i;?>DecisionpcgEnattente' ).observe( 'click', function() {
				$( 'Decisionnonrespectsanctionep93<?php echo $i;?>Decision' ).setValue( 'reporte' );
				fireEvent( $( 'Decisionnonrespectsanctionep93<?php echo $i;?>Decision' ),'change');
			} );

// 			$( 'Decisionnonrespectsanctionep93<?php echo $i;?>Decision' ).observe( 'change', function() {
// 				changeColspanRaisonNonPassage( 'Decisionnonrespectsanctionep93<?php echo $i;?>ColumnDecision', 'Decisionnonrespectsanctionep93<?php echo $i;?>Decision', [], 'Decisionnonrespectsanctionep93<?php echo $i;?>Raisonnonpassage' );
// 			});
// 			changeColspanRaisonNonPassage( 'Decisionnonrespectsanctionep93<?php echo $i;?>ColumnDecision', 'Decisionnonrespectsanctionep93<?php echo $i;?>Decision', [], 'Decisionnonrespectsanctionep93<?php echo $i;?>Raisonnonpassage' );
		<?php endfor;?>
	});
</script>
