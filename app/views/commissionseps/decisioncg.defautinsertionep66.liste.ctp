<?php
echo '<table class="tooltips"><thead>
<tr>
<th>Dossier EP</th>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Date d\'orientation</th>
<th>Orientation actuelle</th>
<!--<th colspan="2">Flux PE</th>-->
<th>Origine</th>
<th>Motif saisine</th>
<th>Date de radiation</th>
<th>Motif de radiation</th>
<th>Avis EPL</th>
<th colspan="4">Décision CG</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		$decisionep = $dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][1];
		$decisioncg = $dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][0];

		$avisEp = implode( ' - ', Set::filter( array( Set::enum( @$dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][1]['decision'], $options['Decisiondefautinsertionep66']['decision'] ), Set::enum( @$dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][1]['decisionsup'], $options['Decisiondefautinsertionep66']['decisionsup'] ), @$listeTypesorients[@$dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][1]['typeorient_id']], @$listeStructuresreferentes[@$dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][1]['structurereferente_id']], @$listeReferents[@$dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][1]['referent_id']] ) ) );

		if ( $decisioncg['decision'] == 'reorientationprofverssoc' || $decisioncg['decision'] == 'reorientationsocversprof' ) {
			$avisCg = array(
				implode( ' / ', Set::filter( array(
					$options['Decisiondefautinsertionep66']['decision'][Set::classicExtract( $decisioncg, "decision" )],
					$options['Decisiondefautinsertionep66']['decisionsup'][Set::classicExtract( $decisioncg, "decisionsup" )]
				) ) ),
				array( $liste_typesorients[Set::classicExtract( $decisioncg, "typeorient_id" )], array( 'id' => "Decisiondefautinsertionep66{$i}TypeorientId" ) ),
				array( $liste_structuresreferentes[Set::classicExtract( $decisioncg, "structurereferente_id" )], array( 'id' => "Decisiondefautinsertionep66{$i}StructurereferenteId" ) ),
				array( $liste_referents[Set::classicExtract( $decisioncg, "referent_id" )], array( 'id' => "Decisiondefautinsertionep66{$i}ReferentId" ) ),
				array( Set::classicExtract( $decisioncg, "raisonnonpassage" ), array( 'colspan' => '3', 'id' => "Decisiondefautinsertionep66{$i}Raisonnonpassage" ) )
			);
		}
		else {
			$avisCg = array(
				array( $options['Decisiondefautinsertionep66']['decision'][Set::classicExtract( $decisioncg, "decision" )], array( 'colspan' => 4 ) ),
				array( Set::classicExtract( $decisioncg, "raisonnonpassage" ), array( 'id' => "Decisiondefautinsertionep66{$i}Raisonnonpassage" ) )
			);
		}

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

				$form->input( "Defautinsertionep66.{$i}.id", array( 'type' => 'hidden', 'value' => $dossierep['Defautinsertionep66']['id'] ) ).
				$form->input( "Defautinsertionep66.{$i}.dossierep_id", array( 'type' => 'hidden', 'value' => $dossierep['Dossierep']['id'] ) ).
				$form->input( "Decisiondefautinsertionep66.{$i}.id", array( 'type' => 'hidden' ) ).
				$form->input( "Decisiondefautinsertionep66.{$i}.etape", array( 'type' => 'hidden', 'value' => 'cg' ) ).
				$form->input( "Decisiondefautinsertionep66.{$i}.passagecommissionep_id", array( 'type' => 'hidden', 'value' ) ).

				$avisEp,

				implode( ' / ', Set::filter( array(
					$options['Decisiondefautinsertionep66']['decision'][Set::classicExtract( $decisioncg, "decision" )],
					@$options['Decisiondefautinsertionep66']['decisionsup'][Set::classicExtract( $decisioncg, "decisionsup" )]
				) ) ),
				array( @$liste_typesorients[Set::classicExtract( $decisioncg, "typeorient_id" )], array( 'id' => "Decisiondefautinsertionep66{$i}TypeorientId" ) ),
				array( @$liste_structuresreferentes[Set::classicExtract( $decisioncg, "structurereferente_id" )], array( 'id' => "Decisiondefautinsertionep66{$i}StructurereferenteId" ) ),
				array( @$liste_referents[Set::classicExtract( $decisioncg, "referent_id" )], array( 'id' => "Decisiondefautinsertionep66{$i}ReferentId" ) ),
				array( Set::classicExtract( $decisioncg, "raisonnonpassage" ), array( 'colspan' => '3', 'id' => "Decisiondefautinsertionep66{$i}Raisonnonpassage" ) )
			)
		);
	}
	echo '</tbody></table>';
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>
			afficheRaisonpassage( '<?php echo Set::classicExtract( $dossiers, "{$theme}.liste.{$i}.Passagecommissionep.0.Decisiondefautinsertionep66.0.decision" );?>', [ 'Decisiondefautinsertionep66<?php echo $i;?>TypeorientId', 'Decisiondefautinsertionep66<?php echo $i;?>StructurereferenteId', 'Decisiondefautinsertionep66<?php echo $i;?>ReferentId' ], 'Decisiondefautinsertionep66<?php echo $i;?>Raisonnonpassage' );
		<?php endfor;?>
	});
</script>