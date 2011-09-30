<?php
echo '<table><thead>
<tr>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Orientation actuelle</th>
<th colspan="3">Proposition référent</th>
<th colspan="3">Avis EPL</th>
<th>Observations</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		$multiple = ( count( $dossiersAllocataires[$dossierep['Personne']['id']] ) > 1 ? 'multipleDossiers' : null );

		$decisionep = $dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0];

		echo $xhtml->tableCells(
			array(
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Personne']['dtnai'] ),
				$locale->date( __( 'Locale->date', true ), $dossierep['Dossierep']['created'] ),
				implode( ' - ', Set::filter( array(
					@$dossierep['Saisinebilanparcoursep66']['Bilanparcours66']['Orientstruct']['Typeorient']['lib_type_orient'],
					@$dossierep['Saisinebilanparcoursep66']['Bilanparcours66']['Orientstruct']['Structurereferente']['lib_struc'],
					Set::filter( array(
						@$dossierep['Saisinebilanparcoursep66']['Bilanparcours66']['Orientstruct']['Referent']['qual'],
						@$dossierep['Saisinebilanparcoursep66']['Bilanparcours66']['Orientstruct']['Referent']['nom'],
						@$dossierep['Saisinebilanparcoursep66']['Bilanparcours66']['Orientstruct']['Referent']['prenom']
					) )
				) ) ),
				$options['Saisinebilanparcoursep66']['choixparcours'][Set::classicExtract( $dossierep, "Saisinebilanparcoursep66.choixparcours" )],
				@$liste_typesorients[Set::classicExtract( $dossierep, "Saisinebilanparcoursep66.typeorient_id" )],
				@$liste_structuresreferentes[Set::classicExtract( $dossierep, "Saisinebilanparcoursep66.structurereferente_id" )],
				array( $options['Decisionsaisinebilanparcoursep66']['decision'][Set::classicExtract( $decisionep, "decision" )], array( 'id' => "Decisionsaisinebilanparcoursep66{$i}DecisionColumn" ) ),
				array( @$liste_typesorients[Set::classicExtract( $decisionep, "typeorient_id" )], array( 'id' => "Decisionsaisinebilanparcoursep66{$i}TypeorientId" ) ),
				array( @$liste_structuresreferentes[Set::classicExtract( $decisionep, "structurereferente_id" )], array( 'id' => "Decisionsaisinebilanparcoursep66{$i}StructurereferenteId" ) ),
				array( Set::classicExtract( $decisionep, "commentaire" ), array( 'id'  => "Decisionsaisinebilanparcoursep66{$i}Commentaire") )
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
			changeColspanViewInfosEps( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>DecisionColumn', '<?php echo Set::classicExtract( $dossiers, "{$theme}.liste.{$i}.Passagecommissionep.0.Decisionsaisinebilanparcoursep66.0.decision" );?>', 3, [ 'Decisionsaisinebilanparcoursep66<?php echo $i;?>TypeorientId', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>StructurereferenteId' ] );
		<?php endfor;?>
	});
</script>