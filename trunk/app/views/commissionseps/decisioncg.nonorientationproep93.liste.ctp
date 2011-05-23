<?php
echo '<table id="Decisionnonorientationproep93" class="tooltips"><thead>
<tr>
<th>Dossier EP</th>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Date d\'orientation</th>
<th>Orientation actuelle</th>
<th>Avis EPL</th>
<th colspan="4">Décision CG</th>
<th>Observations</th>
<th class="innerTableHeader noprint">Avis EP</th>
</tr>
</thead><tbody>';
foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		$decisionep = $dossierep['Passagecommissionep'][0]['Decisionnonorientationproep93'][1];
		$decisioncg = $dossierep['Passagecommissionep'][0]['Decisionnonorientationproep93'][0];

		$avisep = $options['Decisionnonorientationproep93']['decision'][Set::classicExtract( $decisionep, "decision" )];
		if ( $decisionep['decision'] == 'maintienref' ) {
			$avisep .= ' - '.$dossierep['Nonorientationproep93']['Orientstruct']['Typeorient']['lib_type_orient'].' - '.$dossierep['Nonorientationproep93']['Orientstruct']['Structurereferente']['lib_struc'];
		}
		else if ( $decisionep['decision'] == 'reorientation' ) {
			$avisep .= ' - '.$decisionep['Typeorient']['lib_type_orient'].' - '.$decisionep['Structurereferente']['lib_struc'];
		}

		$innerTable = "<table id=\"innerTableDecisionnonorientationproep93{$i}\" class=\"innerTable\">
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
				$dossierep['Dossierep']['id'],
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Nonorientationproep93']['Orientstruct']['date_valid'] ),
				implode( ' - ', Set::filter( array( $dossierep['Nonorientationproep93']['Orientstruct']['Typeorient']['lib_type_orient'], $dossierep['Nonorientationproep93']['Orientstruct']['Structurereferente']['lib_struc'], implode( ' ', Set::filter( array( @$dossierep['Nonorientationproep93']['Orientstruct']['Referent']['qual'], @$dossierep['Nonorientationproep93']['Orientstruct']['Referent']['nom'], @$dossierep['Nonorientationproep93']['Orientstruct']['Referent']['prenom'] ) ) ) ) ) ),

				$avisep,

				$options['Decisionnonorientationproep93']['decisionpcg'][Set::classicExtract( $decisioncg, "decisionpcg" )],
				$options['Decisionnonorientationproep93']['decision'][Set::classicExtract( $decisioncg, "decision" )],
				array( @$liste_typesorients[Set::classicExtract( $decisioncg, "typeorient_id" )], array( 'id' => "Decisionnonorientationproep93{$i}TypeorientId" ) ),
				array( @$liste_structuresreferentes[Set::classicExtract( $decisioncg, "structurereferente_id" )], array( 'id' => "Decisionnonorientationproep93{$i}StructurereferenteId" ) ),
				array( Set::classicExtract( $decisioncg, "raisonnonpassage" ), array( 'colspan' => '2', 'id' => "Decisionnonorientationproep93{$i}Raisonnonpassage" ) ),
				Set::classicExtract( $decisioncg, "commentaire" ),
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
			afficheRaisonpassage( '<?php echo Set::classicExtract( $dossiers, "{$theme}.liste.{$i}.Passagecommissionep.0.Decisionnonorientationproep93.0.decision" );?>', [ 'Decisionnonorientationproep93<?php echo $i;?>TypeorientId', 'Decisionnonorientationproep93<?php echo $i;?>StructurereferenteId' ], 'Decisionnonorientationproep93<?php echo $i;?>Raisonnonpassage' );
		<?php endfor;?>
	});
</script>