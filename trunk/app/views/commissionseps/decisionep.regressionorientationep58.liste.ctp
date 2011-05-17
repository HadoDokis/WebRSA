<?php
echo '<table><thead>
<tr>
<th>Dossier EP</th>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th colspan="2">Orientation actuelle</th>
<th colspan="2">Proposition référent</th>
<th colspan="3">Avis EPL</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		echo $xhtml->tableCells(
			array(
				$dossierep['Dossierep']['id'],
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				@$dossierep['Personne']['Orientstruct'][0]['Typeorient']['lib_type_orient'],
				@$dossierep['Personne']['Orientstruct'][0]['Structurereferente']['lib_struc'],
				@$dossierep['Regressionorientationep58']['Typeorient']['lib_type_orient'],
				@$dossierep['Regressionorientationep58']['Structurereferente']['lib_struc'],

				$options['Decisionregressionorientationep58']['decision'][Set::classicExtract( $datas, "Decisionregressionorientationep58.{$i}.decision" )],
				array( $typesorients[Set::classicExtract( $datas, "Decisionregressionorientationep58.{$i}.typeorient_id" )], array( 'id' => "Decisionregressionorientationep58{$i}TypeorientId" ) ),
				array( $structuresreferentes[Set::classicExtract( $datas, "Decisionregressionorientationep58.{$i}.structurereferente_id" )], array( 'id' => "Decisionregressionorientationep58{$i}StructurereferenteId" ) ),
				array( Set::classicExtract( $datas, "Decisionregressionorientationep58.{$i}.raisonnonpassage" ), array( 'colspan' => '2', 'id' => "Decisionregressionorientationep58{$i}Raisonnonpassage" ) )
			)
		);
	}
	echo '</tbody></table>';
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>
			afficheRaisonpassage( '<?php echo $options['Decisionregressionorientationep58']['decision'][Set::classicExtract( $datas, "Decisionregressionorientationep58.{$i}.decision" )];?>', [ 'Decisionregressionorientationep58<?php echo $i;?>TypeorientId', 'Decisionregressionorientationep58<?php echo $i;?>StructurereferenteId' ], 'Decisionregressionorientationep58<?php echo $i;?>Raisonnonpassage' );
		<?php endfor;?>
	});
</script>