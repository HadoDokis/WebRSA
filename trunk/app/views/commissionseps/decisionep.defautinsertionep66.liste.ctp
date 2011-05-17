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
<th>Origine</th>
<th>Motif saisine</th>
<th>Date de radiation</th>
<th>Motif de radiation</th>
<th colspan="4">Avis EPL</th>
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
				$locale->date( __( 'Locale->date', true ), $dossierep['Defautinsertionep66']['Orientstruct']['date_valid'] ),
				$dossierep['Defautinsertionep66']['Orientstruct']['Typeorient']['lib_type_orient'],
				Set::enum( $dossierep['Defautinsertionep66']['origine'], $options['Defautinsertionep66']['origine'] ),
				Set::enum( @$dossierep['Defautinsertionep66']['Bilanparcours66']['examenaudition'], $options['Defautinsertionep66']['type'] ),
				$locale->date( __( 'Locale->date', true ), @$dossierep['Defautinsertionep66']['Historiqueetatpe']['date'] ),
				@$dossierep['Defautinsertionep66']['Historiqueetatpe']['motif'],

				implode( ' / ', Set::filter( array(
					$options['Decisiondefautinsertionep66']['decision'][Set::classicExtract( $datas, "Decisiondefautinsertionep66.{$i}.decision" )],
					$options['Decisiondefautinsertionep66']['decisionsup'][Set::classicExtract( $datas, "Decisiondefautinsertionep66.{$i}.decisionsup" )]
				) ) ),
				array( $typesorients[Set::classicExtract( $datas, "Decisiondefautinsertionep66.{$i}.typeorient_id" )], array( 'id' => "Decisiondefautinsertionep66{$i}TypeorientId" ) ),
				array( $structuresreferentes[Set::classicExtract( $datas, "Decisiondefautinsertionep66.{$i}.structurereferente_id" )], array( 'id' => "Decisiondefautinsertionep66{$i}StructurereferenteId" ) ),
				array( $referents[Set::classicExtract( $datas, "Decisiondefautinsertionep66.{$i}.referent_id" )], array( 'id' => "Decisiondefautinsertionep66{$i}ReferentId" ) ),
				array( Set::classicExtract( $datas, "Decisiondefautinsertionep66.{$i}.raisonnonpassage" ), array( 'colspan' => '3', 'id' => "Decisiondefautinsertionep66{$i}Raisonnonpassage" ) )
			)
		);
	}
	echo '</tbody></table>';
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>
			afficheRaisonpassage( '<?php echo $options['Decisiondefautinsertionep66']['decision'][Set::classicExtract( $datas, "Decisiondefautinsertionep66.{$i}.decision" )];?>', [ 'Decisiondefautinsertionep66<?php echo $i;?>TypeorientId', 'Decisiondefautinsertionep66<?php echo $i;?>StructurereferenteId', 'Decisiondefautinsertionep66<?php echo $i;?>ReferentId' ], 'Decisiondefautinsertionep66<?php echo $i;?>Raisonnonpassage' );
		<?php endfor;?>
	});
</script>