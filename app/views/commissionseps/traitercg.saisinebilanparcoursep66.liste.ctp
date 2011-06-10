<?php
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
<th class="innerTableHeader noprint">Avis EP</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		$decisionep = $dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'][count($dossierep['Passagecommissionep'][0]['Decisionsaisinebilanparcoursep66'])-1];

		$innerTable = "<table id=\"innerTableDecisionsaisinebilanparcoursep66{$i}\" class=\"innerTable\">
			<tbody>
				<tr>
					<th>Observations de l'EP</th>
					<td>".Set::classicExtract( $decisionep, "commentaire" )."</td>
				</tr>
			</tbody>
		</table>";

		$hiddenFields = $form->input( "Decisionsaisinebilanparcoursep66.{$i}.id", array( 'type' => 'hidden' ) ).
						$form->input( "Decisionsaisinebilanparcoursep66.{$i}.etape", array( 'type' => 'hidden', 'value' => 'cg' ) ).
						$form->input( "Decisionsaisinebilanparcoursep66.{$i}.passagecommissionep_id", array( 'type' => 'hidden' ) );

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

		$listeFields[] = implode( ' - ', Set::filter( array(
			@$options['Saisinebilanparcoursep66']['choixparcours'][Set::classicExtract( $decisionep, "choixparcours" )],
			@$options['Saisinebilanparcoursep66']['maintienorientparcours'][Set::classicExtract( $decisionep, "maintienorientparcours" )],
			@$options['Saisinebilanparcoursep66']['changementrefparcours'][Set::classicExtract( $decisionep, "changementrefparcours" )],
			@$options['Saisinebilanparcoursep66']['reorientation'][Set::classicExtract( $decisionep, "reorientation" )],
			@$liste_typesorients[Set::classicExtract( $decisionep, "typeorient_id" )],
			@$liste_structuresreferentes[Set::classicExtract( $decisionep, "structurereferente_id" )],
			@$liste_referents[Set::classicExtract( $decisionep, "referent_id" )]
		) ) );

		echo $xhtml->tableCells(
			array_merge(
				$listeFields,
				array(
					array(
						$form->input( "Decisionsaisinebilanparcoursep66.{$i}.decision", array( 'label' => false, 'options' => @$options['Decisionsaisinebilanparcoursep66']['decision'] ) ),
						array( 'id' => "Decisionsaisinebilanparcoursep66{$i}DecisionColumn", 'class' => ( !empty( $this->validationErrors['Decisionsaisinebilanparcoursep66'][$i]['decision'] ) ? 'error' : '' ) )
					),
					array(
						$form->input( "Decisionsaisinebilanparcoursep66.{$i}.reorientation", array( 'label' => false, 'options' => $options['Decisionsaisinebilanparcoursep66']['reorientation'], 'empty' => true, 'type' => 'select' ) ),
						( !empty( $this->validationErrors['Decisionsaisinebilanparcoursep66'][$i]['reorientation'] ) ? array( 'class' => 'error' ) : array() )
					),
					array(
						$form->input( "Decisionsaisinebilanparcoursep66.{$i}.typeorient_id", array( 'label' => false, 'options' => $typesorients, 'empty' => true ) ),
						( !empty( $this->validationErrors['Decisionsaisinebilanparcoursep66'][$i]['typeorient_id'] ) ? array( 'class' => 'error' ) : array() )
					),
					array(
						$form->input( "Decisionsaisinebilanparcoursep66.{$i}.structurereferente_id", array( 'label' => false, 'options' => $structuresreferentes, 'empty' => true, 'type' => 'select' ) ),
						( !empty( $this->validationErrors['Decisionsaisinebilanparcoursep66'][$i]['structurereferente_id'] ) ? array( 'class' => 'error' ) : array() )
					),
					$form->input( "Decisionsaisinebilanparcoursep66.{$i}.referent_id", array( 'label' => false, 'options' => $referents, 'empty' => true, 'type' => 'select' ) ),
					array(
						$form->input( "Decisionsaisinebilanparcoursep66.{$i}.maintienorientparcours", array( 'label' => false, 'options' => $options['Decisionsaisinebilanparcoursep66']['maintienorientparcours'], 'empty' => true ) ),
						( !empty( $this->validationErrors['Decisionsaisinebilanparcoursep66'][$i]['maintienorientparcours'] ) ? array( 'class' => 'error' ) : array() )
					),
					array(
						$form->input( "Decisionsaisinebilanparcoursep66.{$i}.changementrefparcours", array( 'label' => false, 'options' => $options['Decisionsaisinebilanparcoursep66']['changementrefparcours'], 'empty' => true, 'type' => 'select' ) ),
						( !empty( $this->validationErrors['Decisionsaisinebilanparcoursep66'][$i]['changementrefparcours'] ) ? array( 'class' => 'error' ) : array() )
					),
// 					array( $form->input( "Decisionsaisinebilanparcoursep66.{$i}.raisonnonpassage", array( 'label' => false, 'empty' => true, 'type' => 'textarea' ) ), array( 'colspan' => 3 ) ),
					$form->input( "Decisionsaisinebilanparcoursep66.{$i}.checkcomm", array( 'label' =>false, 'type' => 'checkbox' ) ).
					$form->input( "Decisionsaisinebilanparcoursep66.{$i}.commentaire", array( 'label' =>false, 'type' => 'textarea' ) ).
					$hiddenFields
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
			dependantSelect( 'Decisionsaisinebilanparcoursep66<?php echo $i?>StructurereferenteId', 'Decisionsaisinebilanparcoursep66<?php echo $i?>TypeorientId' );
			try { $( 'Decisionsaisinebilanparcoursep66<?php echo $i?>StructurereferenteId' ).onchange(); } catch(id) { }

			dependantSelect( 'Decisionsaisinebilanparcoursep66<?php echo $i?>ReferentId', 'Decisionsaisinebilanparcoursep66<?php echo $i?>StructurereferenteId' );
			try { $( 'Decisionsaisinebilanparcoursep66<?php echo $i?>ReferentId' ).onchange(); } catch(id) { }

			$('Decisionsaisinebilanparcoursep66<?php echo $i?>Checkcomm').observe( 'change', function() {
				if ($('Decisionsaisinebilanparcoursep66<?php echo $i?>Checkcomm').checked==true) {
					$('Decisionsaisinebilanparcoursep66<?php echo $i?>Commentaire').show();
				}
				else {
					$('Decisionsaisinebilanparcoursep66<?php echo $i?>Commentaire').hide();
				}
			} );

			if ($('Decisionsaisinebilanparcoursep66<?php echo $i?>Checkcomm').checked==true) {
				$('Decisionsaisinebilanparcoursep66<?php echo $i?>Commentaire').show();
			}
			else {
				$('Decisionsaisinebilanparcoursep66<?php echo $i?>Commentaire').hide();
			}

			$( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Decision' ).observe( 'change', function() {
				changeColspanAnnuleReporte( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>DecisionColumn', 5, 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Decision', [ 'Decisionsaisinebilanparcoursep66<?php echo $i;?>TypeorientId', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>StructurereferenteId', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>ReferentId', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Reorientation', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Maintienorientparcours', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Changementrefparcours' ] );
				checkValue( '<?php echo $i;?>' );
			});
			changeColspanAnnuleReporte( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>DecisionColumn', 5, 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Decision', [ 'Decisionsaisinebilanparcoursep66<?php echo $i;?>TypeorientId', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>StructurereferenteId', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>ReferentId', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Reorientation', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Maintienorientparcours', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Changementrefparcours' ] );
			checkValue( '<?php echo $i;?>' );

// 			$( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Decision' ).observe( 'change', function() {
// 				afficheRaisonpassage( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Decision', [ 'Decisionsaisinebilanparcoursep66<?php echo $i;?>TypeorientId', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>StructurereferenteId', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>ReferentId' ], 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Raisonnonpassage' );
// 			});
// 			afficheRaisonpassage( 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Decision', [ 'Decisionsaisinebilanparcoursep66<?php echo $i;?>TypeorientId', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>StructurereferenteId', 'Decisionsaisinebilanparcoursep66<?php echo $i;?>ReferentId' ], 'Decisionsaisinebilanparcoursep66<?php echo $i;?>Raisonnonpassage' );
		<?php endfor;?>
	});

	function checkValue( i ) {
		if ( $F( 'Decisionsaisinebilanparcoursep66'+i+'Decision' ) == 'maintien' ) {
			$( 'Decisionsaisinebilanparcoursep66'+i+'Reorientation' ).up(1).hide();
			$( 'Decisionsaisinebilanparcoursep66'+i+'TypeorientId' ).up(1).hide();
			$( 'Decisionsaisinebilanparcoursep66'+i+'StructurereferenteId' ).up(1).hide();
			$( 'Decisionsaisinebilanparcoursep66'+i+'ReferentId' ).up(1).hide();
			$( 'Decisionsaisinebilanparcoursep66'+i+'Maintienorientparcours' ).up(1).show();
			$( 'Decisionsaisinebilanparcoursep66'+i+'Changementrefparcours' ).up(1).show();
			$( 'Decisionsaisinebilanparcoursep66'+i+'Changementrefparcours' ).up(1).writeAttribute( "colspan", 3 );
		}
		else if ( $F( 'Decisionsaisinebilanparcoursep66'+i+'Decision' ) == 'reorientation' ) {
			$( 'Decisionsaisinebilanparcoursep66'+i+'Reorientation' ).up(1).show();
			$( 'Decisionsaisinebilanparcoursep66'+i+'TypeorientId' ).up(1).show();
			$( 'Decisionsaisinebilanparcoursep66'+i+'StructurereferenteId' ).up(1).show();
			$( 'Decisionsaisinebilanparcoursep66'+i+'ReferentId' ).up(1).show();
			$( 'Decisionsaisinebilanparcoursep66'+i+'Maintienorientparcours' ).up(1).hide();
			$( 'Decisionsaisinebilanparcoursep66'+i+'Changementrefparcours' ).up(1).hide();
			$( 'Decisionsaisinebilanparcoursep66'+i+'Changementrefparcours' ).up(1).writeAttribute( "colspan" );
		}
	}
</script>