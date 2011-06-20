<?php
echo '<table><thead>
<tr>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Orientation actuelle</th>
<th colspan="4">Proposition référent</th>
<th colspan="5">Avis EPL</th>
<th>Observations</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		$decisionep = $dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0];

		$firstsFields = array(
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
			) ) )
		);

		if ( $dossierep['Saisinebilanparcoursep66']['choixparcours'] == 'maintien' ) {
			$propoReferent = array(
				array( $options['Saisinebilanparcoursep66']['choixparcours'][Set::classicExtract( $dossierep, "Saisinebilanparcoursep66.choixparcours" )], array( 'colspan' => 2 ) ),
				$options['Saisinebilanparcoursep66']['maintienorientparcours'][Set::classicExtract( $dossierep, "Saisinebilanparcoursep66.maintienorientparcours" )],
				$options['Saisinebilanparcoursep66']['changementrefparcours'][Set::classicExtract( $dossierep, "Saisinebilanparcoursep66.changementrefparcours" )]
			);
		}
		else {
			$propoReferent = array(
				$options['Saisinebilanparcoursep66']['choixparcours'][Set::classicExtract( $dossierep, "Saisinebilanparcoursep66.choixparcours" )],
				$options['Saisinebilanparcoursep66']['reorientation'][Set::classicExtract( $dossierep, "Saisinebilanparcoursep66.reorientation" )],
				$liste_typesorients[Set::classicExtract( $dossierep, "Saisinebilanparcoursep66.typeorient_id" )],
				$liste_structuresreferentes[Set::classicExtract( $dossierep, "Saisinebilanparcoursep66.structurereferente_id" )]
			);
		}

		$listeFields = array_merge(
			$firstsFields,
			$propoReferent
		);

		echo $xhtml->tableCells(
			array_merge(
				$listeFields,
				array(
					array( $options['Decisionsaisinebilanparcoursep66']['decision'][Set::classicExtract( $decisionep, "decision" )], array( 'id' => "Decisionsaisinebilanparcoursep66{$i}DecisionColumn" ) ),
					array( @$options['Decisionsaisinebilanparcoursep66']['reorientation'][Set::classicExtract( $decisionep, "reorientation" )], array( 'id' => "Decisionsaisinebilanparcoursep66{$i}Reorientation" ) ),
					array( @$liste_typesorients[Set::classicExtract( $decisionep, "typeorient_id" )], array( 'id' => "Decisionsaisinebilanparcoursep66{$i}TypeorientId" ) ),
					array( @$liste_structuresreferentes[Set::classicExtract( $decisionep, "structurereferente_id" )], array( 'id' => "Decisionsaisinebilanparcoursep66{$i}StructurereferenteId" ) ),
					array( @$liste_referents[Set::classicExtract( $decisionep, "referent_id" )], array( 'id' => "Decisionsaisinebilanparcoursep66{$i}ReferentId" ) ),
					array( @$options['Decisionsaisinebilanparcoursep66']['maintienorientparcours'][Set::classicExtract( $decisionep, "maintienorientparcours" )], array( 'id' => "Decisionsaisinebilanparcoursep66{$i}Maintienorientparcours" ) ),
					array( @$options['Decisionsaisinebilanparcoursep66']['changementrefparcours'][Set::classicExtract( $decisionep, "changementrefparcours" )], array( 'id' => "Decisionsaisinebilanparcoursep66{$i}Changementrefparcours" ) ),
// 					array( Set::classicExtract( $decisionep, "raisonnonpassage" ), array( 'colspan' => 3, 'id' => "Decisionsaisinebilanparcoursep66{$i}Raisonnonpassage" ) ),
					array( Set::classicExtract( $decisionep, "commentaire" ), array( 'id'  => "Decisionsaisinebilanparcoursep66{$i}Commentaire") )
				)
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
			changeColspanViewInfosEps( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>DecisionColumn', '<?php echo Set::classicExtract( $dossiers, "{$theme}.liste.{$i}.Passagecommissionep.0.Decisionsaisinebilanparcoursep66.0.decision" );?>', 5, [ 'Decisionsaisinebilanparcoursep66<?php echo $i;?>TypeorientId', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>StructurereferenteId', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>ReferentId', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Reorientation', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Maintienorientparcours', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Changementrefparcours' ] );

			if ( '<?php echo Set::classicExtract( $dossiers, "{$theme}.liste.{$i}.Passagecommissionep.0.Decisionsaisinebilanparcoursep66.0.decision" );?>' == 'maintien' ) {
				$( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Reorientation' ).hide();
				$( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>TypeorientId' ).hide();
				$( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>StructurereferenteId' ).hide();
				$( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>ReferentId' ).hide();
				$( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Maintienorientparcours' ).show();
				$( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Changementrefparcours' ).show();
				$( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Changementrefparcours' ).writeAttribute( "colspan", 3 );
				$( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>DecisionColumn' ).writeAttribute( "colspan" );
			}
			else if ( '<?php echo Set::classicExtract( $dossiers, "{$theme}.liste.{$i}.Passagecommissionep.0.Decisionsaisinebilanparcoursep66.0.decision" );?>' == 'reorientation' ) {
				$( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Reorientation' ).show();
				$( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>TypeorientId' ).show();
				$( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>StructurereferenteId' ).show();
				$( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>ReferentId' ).show();
				$( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Maintienorientparcours' ).hide();
				$( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Changementrefparcours' ).hide();
				$( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Changementrefparcours' ).writeAttribute( "colspan" );
				$( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>DecisionColumn' ).writeAttribute( "colspan" );
			}
		<?php endfor;?>
	});
</script>