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
<th class="action">Action</th>
<th class="innerTableHeader noprint">Avis EP</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		$decisionep = $dossierep['Passagecommissionep'][0]['Decisionnonrespectsanctionep93'][1];
		$decisioncg = $dossierep['Passagecommissionep'][0]['Decisionnonrespectsanctionep93'][0];

		$lineOptions = array();
		foreach( $options['Decisionnonrespectsanctionep93']['decision'] as $key => $label ) {
			if( !in_array( $key[0], array( 1, 2 ) ) || ( $key[0] == min( 2, $dossierep['Nonrespectsanctionep93']['rgpassage'] ) ) ) {
				$lineOptions[$key] = $label;
			}
		}
		
		$innerTable = "<table id=\"innerTableDecisionnonrespectsanctionep93{$i}\" class=\"innerTable\">
			<tbody>
				<tr>
					<th>Observations de l'EP</th>
					<td>".Set::classicExtract( $decisionep, "commentaire" )."</td>
				</tr>";
		
		if ( $decisionep['decision'] == 'reporte' || $decisionep['decision'] == 'annule' ) {
			$innerTable .= " <tr>
				<th>Raison du non passage de l'EP</th>
				<td>".Set::classicExtract( $decisionep, "raisonnonpassage" )."</td>
			</tr>";
		}
		
		$innerTable .= "</tbody></table>";

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
				
				Set::enum( @$decisionep['decision'], $options['Decisionnonrespectsanctionep93']['decision'] ),

				$options['Decisionnonrespectsanctionep93']['decisionpcg'][Set::classicExtract( $decisioncg, "decisionpcg" )],
				array(
					$options['Decisionnonrespectsanctionep93']['decision'][Set::classicExtract( $decisioncg, "decision" )],
					array( 'id' => "Decisionnonrespectsanctionep93{$i}ColumnDecision", 'colspan' => 2 )
				),
 				array( Set::classicExtract( $decisioncg, "raisonnonpassage" ), array( 'id' => "Decisionnonrespectsanctionep93{$i}Raisonnonpassage" ) ),
				Set::classicExtract( $decisioncg, "commentaire" ),
				$xhtml->printLink( 'Imprimer', array( 'controller' => 'commissionseps', 'action' => 'impressionDecision', 'cg', $dossierep['Passagecommissionep'][0]['id'] ) ),
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
			changeColspanRaisonNonPassage( 'Decisionnonrespectsanctionep93<?php echo $i;?>ColumnDecision', '<?php echo Set::classicExtract( $dossiers, "{$theme}.liste.{$i}.Passagecommissionep.0.Decisionnonrespectsanctionep93.0.decision" );?>', [], 'Decisionnonrespectsanctionep93<?php echo $i;?>Raisonnonpassage' );
		<?php endfor;?>
	});
</script>
