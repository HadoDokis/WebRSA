<?php
	echo '<ul class="actions">';
	echo '<li>'.$xhtml->link(
		__d( 'commissionep','Commissionseps::impressionsDecisions', true ),
		array( 'controller' => 'commissionseps', 'action' => 'impressionsDecisions', $commissionep['Commissionep']['id'] ),
		array( 'class' => 'button impressionsDecisions', 'enabled' => $commissionep['Commissionep']['etatcommissionep'] != 'annule' ),
        'Etes-vous sûr de vouloir imprimer les décisions ?'
	).' </li>';
	echo '</ul>';

echo '<table id="Decisionsaisinebilanparcoursep66" class="tooltips"><thead>
<tr>
<th>Nom du demandeur</th>
<th>Adresse</th>
<th>Date de naissance</th>
<th>Création du dossier EP</th>
<th>Orientation actuelle</th>
<th colspan="4">Proposition référent</th>
<th>Avis EPL</th>
<th>Décision CG</th>
<th colspan="4">Décision coordonnateur/CG</th>
<th>Observations</th>
<th colspan="2">Actions</th>
<th class="innerTableHeader noprint">Avis EP</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		$multiple = ( count( $dossiersAllocataires[$dossierep['Personne']['id']] ) > 1 ? 'multipleDossiers' : null );

		$decisionep = @$dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][1];
		$decisioncg = @$dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][0];

		$innerTable = "<table id=\"innerTableDecisionsaisinebilanparcoursep66{$i}\" class=\"innerTable\">
			<tbody>
				<tr>
					<th>Observations de l'EP</th>
					<td>".Set::classicExtract( $decisionep, "commentaire" )."</td>
				</tr>
			</tbody>
		</table>";

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
				array( @$options['Saisinebilanparcoursep66']['choixparcours'][Set::classicExtract( $dossierep, "Saisinebilanparcoursep66.choixparcours" )], array( 'colspan' => 2 ) ),
				@$options['Saisinebilanparcoursep66']['maintienorientparcours'][Set::classicExtract( $dossierep, "Saisinebilanparcoursep66.maintienorientparcours" )],
				@$options['Saisinebilanparcoursep66']['changementrefparcours'][Set::classicExtract( $dossierep, "Saisinebilanparcoursep66.changementrefparcours" )]
			);
		}
		else {
			$propoReferent = array(
				@$options['Saisinebilanparcoursep66']['choixparcours'][Set::classicExtract( $dossierep, "Saisinebilanparcoursep66.choixparcours" )],
				@$options['Saisinebilanparcoursep66']['reorientation'][Set::classicExtract( $dossierep, "Saisinebilanparcoursep66.reorientation" )],
				$liste_typesorients[Set::classicExtract( $dossierep, "Saisinebilanparcoursep66.typeorient_id" )],
				$liste_structuresreferentes[Set::classicExtract( $dossierep, "Saisinebilanparcoursep66.structurereferente_id" )]
			);
		}

		$listeFields = array_merge(
			$firstsFields,
			$propoReferent
		);

		$listeFields[] = implode( ' - ', Set::filter( array(
			@$options['Saisinebilanparcoursep66']['choixparcours'][Set::classicExtract( $decisionep, "choixparcours" )],
			@$options['Saisinebilanparcoursep66']['maintienorientparcours'][Set::classicExtract( $decisionep, "maintienorientparcours" )],
			@$options['Saisinebilanparcoursep66']['changementrefparcours'][Set::classicExtract( $decisionep, "changementrefparcours" )],
			@$options['Saisinebilanparcoursep66']['reorientation'][Set::classicExtract( $decisionep, "reorientation" )],
			@$liste_typesorients[Set::classicExtract( $decisionep, "typeorient_id" )],
			@$liste_structuresreferentes[Set::classicExtract( $decisionep, "structurereferente_id" )],
			@$liste_referents[Set::classicExtract( $decisionep, "referent_id" )]
		) ) );

		$enabled = ( $commissionep['Commissionep']['etatcommissionep'] != 'annule' );
		if( $decisioncg['decision'] == 'maintien' ){
			$enabled = false;
		}
		else{
			$enabled = $enabled;
		}

		echo $xhtml->tableCells(
			array_merge(
				$listeFields,
				array(
					array( @$options['Decisionsaisinebilanparcoursep66']['decision'][Set::classicExtract( $decisioncg, "decision" )], array( 'id' => "Decisionsaisinebilanparcoursep66{$i}DecisionColumn" ) ),
					array( @$options['Decisionsaisinebilanparcoursep66']['reorientation'][Set::classicExtract( $decisioncg, "reorientation" )], array( 'id' => "Decisionsaisinebilanparcoursep66{$i}Reorientation" ) ),
					array( @$liste_typesorients[Set::classicExtract( $decisioncg, "typeorient_id" )], array( 'id' => "Decisionsaisinebilanparcoursep66{$i}TypeorientId" ) ),
					array( @$liste_structuresreferentes[Set::classicExtract( $decisioncg, "structurereferente_id" )], array( 'id' => "Decisionsaisinebilanparcoursep66{$i}StructurereferenteId" ) ),
					array( @$liste_referents[Set::classicExtract( $decisioncg, "referent_id" )], array( 'id' => "Decisionsaisinebilanparcoursep66{$i}ReferentId" ) ),
					array( @$options['Decisionsaisinebilanparcoursep66']['maintienorientparcours'][Set::classicExtract( $decisioncg, "maintienorientparcours" )], array( 'id' => "Decisionsaisinebilanparcoursep66{$i}Maintienorientparcours" ) ),
					array( @$options['Decisionsaisinebilanparcoursep66']['changementrefparcours'][Set::classicExtract( $decisioncg, "changementrefparcours" )], array( 'id' => "Decisionsaisinebilanparcoursep66{$i}Changementrefparcours" ) ),
					array( Set::classicExtract( $decisioncg, "commentaire" ), array( 'id'  => "Decisionsaisinebilanparcoursep66{$i}Commentaire") ),
					array( $xhtml->link( 'Voir', array( 'controller' => 'historiqueseps', 'action' => 'view_passage', $dossierep['Passagecommissionep'][0]['id'] ), array( 'class' => 'external' ) ), array( 'class' => 'button view' ) ),
					$xhtml->printLink( 'Imprimer', array( 'controller' => 'commissionseps', 'action' => 'impressionDecision', $dossierep['Passagecommissionep'][0]['id'] ), ( $enabled ) ),
					array( $innerTable, array( 'class' => 'innerTableCell noprint' ) )
				)
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
			else if ( '<?php echo Set::classicExtract( $dossiers, "{$theme}.liste.{$i}.Passagecommissionep.0.etatdossierep" );?>' == 'reporte' ) {
				$( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Reorientation' ).hide();
				$( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>TypeorientId' ).hide();
				$( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>StructurereferenteId' ).hide();
				$( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>ReferentId' ).hide();
				$( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Maintienorientparcours' ).show();
				$( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Changementrefparcours' ).show();
				$( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Changementrefparcours' ).writeAttribute( "colspan", 3 );
				$( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>DecisionColumn' ).writeAttribute( "colspan" );
			}
		<?php endfor;?>
	});
</script>