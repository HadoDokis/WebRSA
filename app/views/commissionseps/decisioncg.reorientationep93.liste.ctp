<?php
echo '<table id="Decisionreorientationep93" class="tooltips"><thead>
<tr>
<th>Dossier EP</th>
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
<th class="innerTableHeader noprint">Avis EP</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		$decisionep = $dossierep['Passagecommissionep'][0]['Decisionreorientationep93'][1];
		$decisioncg = $dossierep['Passagecommissionep'][0]['Decisionreorientationep93'][0];
		
		$innerTable = "<table id=\"innerTableDecisionreorientationep93{$i}\" class=\"innerTable\">
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
				$dossierep['Reorientationep93']['Motifreorientep93']['name'],
				$dossierep['Reorientationep93']['Orientstruct']['Typeorient']['lib_type_orient'],
				$dossierep['Reorientationep93']['Orientstruct']['Structurereferente']['lib_struc'],
				@$dossierep['Reorientationep93']['Typeorient']['lib_type_orient'],
				@$dossierep['Reorientationep93']['Structurereferente']['lib_struc'],
				implode( ' / ', Set::filter( array( $options['Decisionreorientationep93']['decision'][$decisionep['decision']], @$decisionep['Typeorient']['lib_type_orient'], @$decisionep['Structurereferente']['lib_struc'], $decisionep['raisonnonpassage'] ) ) ),

				$options['Decisionreorientationep93']['decisionpcg'][Set::classicExtract( $decisioncg, "decisionpcg" )],
				$options['Decisionreorientationep93']['decision'][Set::classicExtract( $decisioncg, "decision" )],
				array( @$liste_typesorients[Set::classicExtract( $decisioncg, "typeorient_id" )], array( 'id' => "Decisionreorientationep93{$i}TypeorientId" ) ),
				array( @$liste_structuresreferentes[Set::classicExtract( $decisioncg, "structurereferente_id" )], array( 'id' => "Decisionreorientationep93{$i}StructurereferenteId" ) ),
				array( Set::classicExtract( $decisioncg, "raisonnonpassage" ), array( 'colspan' => '2', 'id' => "Decisionreorientationep93{$i}Raisonnonpassage" ) ),
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
			afficheRaisonpassage( '<?php echo Set::classicExtract( $dossiers, "{$theme}.liste.{$i}.Passagecommissionep.0.Decisionreorientationep93.0.decision" );?>', [ 'Decisionreorientationep93<?php echo $i;?>TypeorientId', 'Decisionreorientationep93<?php echo $i;?>StructurereferenteId' ], 'Decisionreorientationep93<?php echo $i;?>Raisonnonpassage' );
		<?php endfor;?>
	});
</script>