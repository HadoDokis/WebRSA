<?php
echo '<table id="Decisiondefautinsertionep66" class="tooltips">
		<colgroup />
		<colgroup />
		<colgroup />
		<colgroup />
		<colgroup />
		<colgroup />
		<colgroup />
		<colgroup />
		<colgroup span="4" style="border-right: 5px solid #235F7D;border-left: 5px solid #235F7D;" />
		<colgroup />
		<colgroup />
		<thead>
			<tr>
				<th rowspan="2">Nom du demandeur</th>
				<th rowspan="2">Adresse</th>
				<th rowspan="2">Date de naissance</th>
				<th rowspan="2">Date d\'orientation</th>
				<th rowspan="2">Orientation actuelle</th>
				<th rowspan="2">Structure</th>
				<th rowspan="2">Motif saisine</th>
				<th rowspan="2">Avis EPL</th>
				<th colspan="4">Décision CG</th>
				<th rowspan="2">Observations</th>
				<th rowspan="2">Action</th>
				<th class="innerTableHeader noprint">Avis EP</th>
			</tr>
			<tr>
				<th>Décision</th>
				<th>Type d\'orientation</th>
				<th>Structure référente</th>
				<th>Référent</th>
			</tr>
		</thead>
	<tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		if( in_array( $dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][0]['decision'], array( 'reorientationsocversprof', 'reorientationprofverssoc' ) ) ) {
			
			$examenaudition = Set::enum( @$dossierep['Defautinsertionep66']['Bilanparcours66']['examenaudition'], $options['Defautinsertionep66']['type'] );
			if( !empty( $dossierep['Defautinsertionep66']['Bilanparcours66']['examenauditionpe'] ) ){
				$examenaudition = Set::enum( @$dossierep['Defautinsertionep66']['Bilanparcours66']['examenauditionpe'], $options['Bilanparcours66']['examenauditionpe'] );
			}

			$multiple = ( count( $dossiersAllocataires[$dossierep['Personne']['id']] ) > 1 ? 'multipleDossiers' : null );

			$decisionep = @$dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][1];
			$decisioncg = @$dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][0];

			$avisEp = implode( ' - ', Hash::filter( (array)array( Set::enum( @$dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][1]['decisionsup'], $options['Decisiondefautinsertionep66']['decisionsup'] ), Set::enum( @$dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][1]['decision'], $options['Decisiondefautinsertionep66']['decision'] ), @$listeTypesorients[@$dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][1]['typeorient_id']], @$listeStructuresreferentes[@$dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][1]['structurereferente_id']], @$listeReferents[@$dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][1]['referent_id']] ) ) );

			$innerTable = "<table id=\"innerTableDecisiondefautinsertionep66{$i}\" class=\"innerTable\">
				<tbody>
					<tr>
						<th>Observations de l'EP</th>
						<td>".Set::classicExtract( $decisionep, "commentaire" )."</td>
					</tr>
				</tbody>
			</table>";

			echo $this->Xhtml->tableCells(
				array(
					implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
					implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'], $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['codepos'], $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['locaadr'] ) ),
					$this->Locale->date( __( 'Locale->date' ), $dossierep['Personne']['dtnai'] ),
					$this->Locale->date( __( 'Locale->date' ), @$dossierep['Defautinsertionep66']['Orientstruct']['date_valid'] ),
					@$dossierep['Defautinsertionep66']['Orientstruct']['Typeorient']['lib_type_orient'],

					@$dossierep['Defautinsertionep66']['Orientstruct']['Structurereferente']['lib_struc'],

					$examenaudition,
					$avisEp,

					array( implode( ' / ', Hash::filter( (array)array(
						@$options['Decisiondefautinsertionep66']['decision'][Set::classicExtract( $decisioncg, "decision" )],
						@$options['Decisiondefautinsertionep66']['decisionsup'][Set::classicExtract( $decisioncg, "decisionsup" )]
					) ) ), array( 'id' => "Decisiondefautinsertionep66{$i}DecisionColumn" ) ),

					array( @$liste_typesorients[Set::classicExtract( $decisioncg, "typeorient_id" )], array( 'id' => "Decisiondefautinsertionep66{$i}TypeorientId" ) ),
					array( @$liste_structuresreferentes[Set::classicExtract( $decisioncg, "structurereferente_id" )], array( 'id' => "Decisiondefautinsertionep66{$i}StructurereferenteId" ) ),
					array( @$liste_referents[Set::classicExtract( $decisioncg, "referent_id" )], array( 'id' => "Decisiondefautinsertionep66{$i}ReferentId" ) ),
					Set::classicExtract( $decisioncg, "commentaire" ),
					array( $this->Xhtml->link( 'Voir', array( 'controller' => 'historiqueseps', 'action' => 'view_passage', $dossierep['Passagecommissionep'][0]['id'] ), array( 'class' => 'external' ) ), array( 'class' => 'button view' ) ),
// 					array( $innerTable, array( 'class' => 'innerTableCell noprint' ) )
				),
				array( 'class' => "odd {$multiple}" ),
				array( 'class' => "even {$multiple}" )
			);
		}
	}
	echo '</tbody></table>';
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php for( $i = 0 ; $i < count( $dossiers[$theme]['liste'] ) ; $i++ ):?>
			changeColspanViewInfosEps( 'Decisiondefautinsertionep66<?php echo $i;?>DecisionColumn', '<?php echo Set::classicExtract( $dossiers, "{$theme}.liste.{$i}.Passagecommissionep.0.Decisiondefautinsertionep66.0.decision" );?>', 4, [ 'Decisiondefautinsertionep66<?php echo $i;?>TypeorientId', 'Decisiondefautinsertionep66<?php echo $i;?>StructurereferenteId', 'Decisiondefautinsertionep66<?php echo $i;?>ReferentId' ] );
		<?php endfor;?>
	});
</script>