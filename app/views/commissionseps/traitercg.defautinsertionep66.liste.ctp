<?php
echo '<table id="Decisiondefautinsertionep66" class="tooltips"><thead>
<tr>
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
<th>Observations</th>
<th class="innerTableHeader noprint">Avis EP</th>
</tr>
</thead><tbody>';
	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		$multiple = ( count( $dossiersAllocataires[$dossierep['Personne']['id']] ) > 1 ? 'multipleDossiers' : null );

		$decisionep = $dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][count($dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'])-1];
		$avisEp = implode( ' - ', Set::filter( array( Set::enum( @$decisionep['decision'], $options['Decisiondefautinsertionep66']['decision'] ), Set::enum( @$decisionep['decisionsup'], $options['Decisiondefautinsertionep66']['decisionsup'] ), @$listeTypesorients[@$decisionep['typeorient_id']], @$listeStructuresreferentes[@$decisionep['structurereferente_id']], @$listeReferents[@$decisionep['referent_id']] ) ) );

		$innerTable = "<table id=\"innerTableDecisiondefautinsertionep66{$i}\" class=\"innerTable\">
			<tbody>
				<tr>
					<th>Observations de l'EP</th>
					<td>".Set::classicExtract( $decisionep, "commentaire" )."</td>
				</tr>
			</tbody>
		</table>";

		$hiddenFields = $form->input( "Decisiondefautinsertionep66.{$i}.id", array( 'type' => 'hidden' ) ).
						$form->input( "Decisiondefautinsertionep66.{$i}.etape", array( 'type' => 'hidden', 'value' => 'cg' ) ).
						$form->input( "Decisiondefautinsertionep66.{$i}.passagecommissionep_id", array( 'type' => 'hidden', 'value' ) );

		echo $xhtml->tableCells(
			array(
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

				$form->input( "Defautinsertionep66.{$i}.id", array( 'type' => 'hidden' ) ).
				$form->input( "Defautinsertionep66.{$i}.dossierep_id", array( 'type' => 'hidden', 'value' => $dossierep['Dossierep']['id'] ) ).
				$form->input( "Decisiondefautinsertionep66.{$i}.id", array( 'type' => 'hidden' ) ).
				$form->input( "Decisiondefautinsertionep66.{$i}.etape", array( 'type' => 'hidden', 'value' => 'cg' ) ).
				$form->input( "Decisiondefautinsertionep66.{$i}.passagecommissionep_id", array( 'type' => 'hidden', 'value' ) ).

                $avisEp,

				array(
					$form->input( "Decisiondefautinsertionep66.{$i}.decision", array( 'type' => 'select', 'label' => false, 'empty' => true, 'options' => $options['Decisiondefautinsertionep66']['decision'] ) ).
					$form->input( "Decisiondefautinsertionep66.{$i}.decisionsup", array( 'type' => 'select', 'label' => false, 'empty' => true, 'options' => $options['Decisiondefautinsertionep66']['decisionsup'], 'value' => @$decisionsdefautsinsertionseps66[$i]['decisionsup'] ) ),
					array( 'id' => "Decisiondefautinsertionep66{$i}DecisionColumn", 'class' => ( !empty( $this->validationErrors['Decisiondefautinsertionep66'][$i]['decision'] ) || !empty( $this->validationErrors['Decisiondefautinsertionep66'][$i]['decisionsup'] ) ? 'error' : '' ) )
				),
				array(
					$form->input( "Decisiondefautinsertionep66.{$i}.typeorient_id", array( 'label' => false, 'options' => $typesorients, 'empty' => true ) ),
					( !empty( $this->validationErrors['Decisiondefautinsertionep66'][$i]['typeorient_id'] ) ? array( 'class' => 'error' ) : array() )
				),
				array(
					$form->input( "Decisiondefautinsertionep66.{$i}.structurereferente_id", array( 'label' => false, 'options' => $structuresreferentes, 'empty' => true, 'type' => 'select' ) ),
					( !empty( $this->validationErrors['Decisiondefautinsertionep66'][$i]['structurereferente_id'] ) ? array( 'class' => 'error' ) : array() )
				),
				$form->input( "Decisiondefautinsertionep66.{$i}.referent_id", array( 'label' => false, 'options' => $referents, 'empty' => true, 'type' => 'select' ) ),
// 				array( $form->input( "Decisiondefautinsertionep66.{$i}.raisonnonpassage", array( 'label' => false, 'type' => 'textarea' ) ), array( 'colspan' => '3' ) ),
				$form->input( "Decisiondefautinsertionep66.{$i}.commentaire", array( 'label' =>false, 'type' => 'textarea' ) ).
				$hiddenFields,
				array( $innerTable, array( 'class' => 'innerTableCell noprint' ) )
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
			dependantSelect( 'Decisiondefautinsertionep66<?php echo $i?>StructurereferenteId', 'Decisiondefautinsertionep66<?php echo $i?>TypeorientId' );

			dependantSelect( 'Decisiondefautinsertionep66<?php echo $i?>ReferentId', 'Decisiondefautinsertionep66<?php echo $i?>StructurereferenteId' );

			observeDisableFieldsOnValue(
				'Decisiondefautinsertionep66<?php echo $i;?>Decision',
				[
					'Decisiondefautinsertionep66<?php echo $i;?>TypeorientId',
					'Decisiondefautinsertionep66<?php echo $i;?>StructurereferenteId',
					'Decisiondefautinsertionep66<?php echo $i;?>ReferentId'
				],
				[
					'reorientationprofverssoc',
					'reorientationsocversprof'
				],
				false
			);
			
			$( 'Decisiondefautinsertionep66<?php echo $i;?>Decision' ).observe( 'change', function() {
				if ( $F( 'Decisiondefautinsertionep66<?php echo $i;?>Decision' ) == 'reorientationprofverssoc' || $F( 'Decisiondefautinsertionep66<?php echo $i;?>Decision' ) == 'reorientationsocversprof' ) {
					$( 'Decisiondefautinsertionep66<?php echo $i;?>Decisionsup' ).show();
				}
				else {
					$( 'Decisiondefautinsertionep66<?php echo $i;?>Decisionsup' ).hide();
				}
			} );
			
			if ( $F( 'Decisiondefautinsertionep66<?php echo $i;?>Decision' ) == 'reorientationprofverssoc' || $F( 'Decisiondefautinsertionep66<?php echo $i;?>Decision' ) == 'reorientationsocversprof' ) {
				$( 'Decisiondefautinsertionep66<?php echo $i;?>Decisionsup' ).show();
			}
			else {
				$( 'Decisiondefautinsertionep66<?php echo $i;?>Decisionsup' ).hide();
			}

			$( 'Decisiondefautinsertionep66<?php echo $i;?>Decision' ).observe( 'change', function() {
				changeColspanFormAnnuleReporteEps( 'Decisiondefautinsertionep66<?php echo $i;?>DecisionColumn', 4, 'Decisiondefautinsertionep66<?php echo $i;?>Decision', [ 'Decisiondefautinsertionep66<?php echo $i;?>TypeorientId', 'Decisiondefautinsertionep66<?php echo $i;?>StructurereferenteId', 'Decisiondefautinsertionep66<?php echo $i;?>ReferentId' ] );
			});
			changeColspanFormAnnuleReporteEps( 'Decisiondefautinsertionep66<?php echo $i;?>DecisionColumn', 4, 'Decisiondefautinsertionep66<?php echo $i;?>Decision', [ 'Decisiondefautinsertionep66<?php echo $i;?>TypeorientId', 'Decisiondefautinsertionep66<?php echo $i;?>StructurereferenteId', 'Decisiondefautinsertionep66<?php echo $i;?>ReferentId' ] );
		<?php endfor;?>
	});
</script>