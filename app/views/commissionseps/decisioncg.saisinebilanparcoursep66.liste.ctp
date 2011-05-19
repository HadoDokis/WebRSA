<?php
echo '<table><thead>
<tr>
<th>Dossier EP</th>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Orientation actuelle</th>
<th>Structure référente actuelle</th>
<th>Type de réorientation</th>
<th>Proposition référent</th>
<th>Avis EPL</th>
<th>Avis CG</th>
<th colspan="3">Décision coordonnateur/CG</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		$decisionep = $dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][1];
		$decisioncg = $dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0];

		echo $xhtml->tableCells(
			array(
				$dossierep['Dossierep']['id'],
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				$dossierep['Saisinebilanparcoursep66']['Bilanparcours66']['Orientstruct']['Typeorient']['lib_type_orient'],
				$dossierep['Saisinebilanparcoursep66']['Bilanparcours66']['Orientstruct']['Structurereferente']['lib_struc'],
				(!empty($dossierep['Saisinebilanparcoursep66']['Bilanparcours66']['reorientation'])) ? __d('bilanparcours66', 'ENUM::REORIENTATION::'.$dossierep['Saisinebilanparcoursep66']['Bilanparcours66']['reorientation'], true) : '',
				@$dossierep['Saisinebilanparcoursep66']['Typeorient']['lib_type_orient'].' - '.
				@$dossierep['Saisinebilanparcoursep66']['Structurereferente']['lib_struc'],

				implode( ' - ', Set::filter( array( $options['Decisionsaisinebilanparcoursep66']['decision'][Set::classicExtract( $decisionep, "decision" )], $liste_typesorients[$decisionep['typeorient_id']], $liste_structuresreferentes[$decisionep['structurereferente_id']] ) ) ),

				$options['Decisionsaisinebilanparcoursep66']['decision'][Set::classicExtract( $decisioncg, "decision" )],
				array( $liste_typesorients[Set::classicExtract( $decisioncg, "typeorient_id" )], array( 'id' => "Decisionsaisinebilanparcoursep66{$i}TypeorientId" ) ),
				array( $liste_structuresreferentes[Set::classicExtract( $decisioncg, "structurereferente_id" )], array( 'id' => "Decisionsaisinebilanparcoursep66{$i}StructurereferenteId" ) ),
				array( $liste_referents[Set::classicExtract( $decisioncg, "referent_id" )], array( 'id' => "Decisionsaisinebilanparcoursep66{$i}ReferentId" ) ),
				array( Set::classicExtract( $decisioncg, "raisonnonpassage" ), array( 'colspan' => '2', 'id' => "Decisionsaisinebilanparcoursep66{$i}Raisonnonpassage" ) )
			)
		);
	}
	echo '</tbody></table>';
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>
			afficheRaisonpassage( '<?php echo Set::classicExtract( $dossiers, "{$theme}.liste.{$i}.Passagecommissionep.0.Decisionsaisinebilanparcoursep66.0.decision" );?>', [ 'Decisionsaisinebilanparcoursep66<?php echo $i;?>TypeorientId', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>StructurereferenteId', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>ReferentId' ], 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Raisonnonpassage' );
		<?php endfor;?>
	});
</script>