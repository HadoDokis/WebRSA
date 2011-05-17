<?php
echo '<table><thead>
<tr>
<th>Dossier EP</th>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Date d\'orientation</th>
<th>Orientation actuelle</th>
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
				$locale->date( __( 'Locale->date', true ), $dossierep['Nonorientationproep58']['Orientstruct']['date_valid'] ),
				implode(
					' - ',
					array(
						$dossierep['Nonorientationproep58']['Orientstruct']['Typeorient']['lib_type_orient'],
						$dossierep['Nonorientationproep58']['Orientstruct']['Structurereferente']['lib_struc'],
						implode(
							' ',
							array(
								@$dossierep['Nonorientationproep58']['Orientstruct']['Referent']['qual'],
								@$dossierep['Nonorientationproep58']['Orientstruct']['Referent']['nom'],
								@$dossierep['Nonorientationproep58']['Orientstruct']['Referent']['prenom']
							)
						)
					)
				),
				
				$options['Decisionnonorientationproep58']['decision'][Set::classicExtract( $datas, "Decisionnonorientationproep58.{$i}.decision" )],
				array( $typesorients[Set::classicExtract( $datas, "Decisionnonorientationproep58.{$i}.typeorient_id" )], array( 'id' => "Decisionnonorientationproep58{$i}TypeorientId" ) ),
				array( $structuresreferentes[Set::classicExtract( $datas, "Decisionnonorientationproep58.{$i}.structurereferente_id" )], array( 'id' => "Decisionnonorientationproep58{$i}StructurereferenteId" ) ),
				array( Set::classicExtract( $datas, "Decisionnonorientationproep58.{$i}.raisonnonpassage" ), array( 'colspan' => '2', 'id' => "Decisionnonorientationproep58{$i}Raisonnonpassage" ) ))
			)
		);
	}
	echo '</tbody></table>';
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>
			afficheRaisonpassage( '<?php echo $options['Decisionnonorientationproep58']['decision'][Set::classicExtract( $datas, "Decisionnonorientationproep58.{$i}.decision" )];?>', [ 'Decisionnonorientationproep58<?php echo $i;?>TypeorientId', 'Decisionnonorientationproep58<?php echo $i;?>StructurereferenteId' ], 'Decisionnonorientationproep58<?php echo $i;?>Raisonnonpassage' );
		<?php endfor;?>
	});
</script>