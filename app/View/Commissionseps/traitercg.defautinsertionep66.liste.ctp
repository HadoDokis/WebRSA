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

	$hiddenDossiers = array( 'Decisiondefautinsertionep66' => array() );

	foreach( $dossiers[$theme]['liste'] as $i => $dossierep ) {
		if( isset( $this->request->data['Decisiondefautinsertionep66'][$i]['field_type'] ) && $this->request->data['Decisiondefautinsertionep66'][$i]['field_type'] == 'hidden' ) {
			$this->request->data['Decisiondefautinsertionep66'][$i]['etape'] = 'cg';
			$this->request->data['Decisiondefautinsertionep66'][$i]['user_id'] = $this->Session->read( 'Auth.User.id' );

			$hiddenDossiers['Decisiondefautinsertionep66'][$i] = $this->request->data['Decisiondefautinsertionep66'][$i];
		}
		else {

			$examenaudition = Set::enum( @$dossierep['Defautinsertionep66']['Bilanparcours66']['examenaudition'], $options['Defautinsertionep66']['type'] );
			if( !empty( $dossierep['Defautinsertionep66']['Bilanparcours66']['examenauditionpe'] ) ){
				$examenaudition = Set::enum( @$dossierep['Defautinsertionep66']['Bilanparcours66']['examenauditionpe'], $options['Bilanparcours66']['examenauditionpe'] );
			}

			$multiple = ( count( $dossiersAllocataires[$dossierep['Personne']['id']] ) > 1 ? 'multipleDossiers' : null );

			$decisionep = $dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][count($dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'])-1];
			$avisEp = implode( ' - ', Hash::filter( (array)array( Set::enum( @$decisionep['decisionsup'], $options['Decisiondefautinsertionep66']['decisionsup'] ), Set::enum( @$decisionep['decision'], $options['Decisiondefautinsertionep66']['decision'] ), @$listeTypesorients[@$decisionep['typeorient_id']], @$listeStructuresreferentes[@$decisionep['structurereferente_id']], @$listeReferents[@$decisionep['referent_id']] ) ) );

			$innerTable = "<table id=\"innerTableDecisiondefautinsertionep66{$i}\" class=\"innerTable\">
				<tbody>
					<tr>
						<th>Observations de l'EP</th>
						<td>".Set::classicExtract( $decisionep, "commentaire" )."</td>
					</tr>
				</tbody>
			</table>";

			$hiddenFields = $this->Form->input( "Decisiondefautinsertionep66.{$i}.id", array( 'type' => 'hidden' ) ).
							$this->Form->input( "Decisiondefautinsertionep66.{$i}.etape", array( 'type' => 'hidden', 'value' => 'cg' ) ).
							$this->Form->input( "Decisiondefautinsertionep66.{$i}.passagecommissionep_id", array( 'type' => 'hidden' ) ).
							$this->Form->input( "Decisiondefautinsertionep66.{$i}.user_id", array( 'type' => 'hidden', 'value' => $this->Session->read( 'Auth.User.id' ) ) );

			echo $this->Xhtml->tableCells(
				array(
					implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
					implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'], $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['codepos'], $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['locaadr'] ) ),
					$this->Locale->date( __( 'Locale->date' ), $dossierep['Personne']['dtnai'] ),
					$this->Locale->date( __( 'Locale->date' ), @$dossierep['Defautinsertionep66']['Orientstruct']['date_valid'] ),
					@$dossierep['Defautinsertionep66']['Orientstruct']['Typeorient']['lib_type_orient'],
					@$dossierep['Defautinsertionep66']['Orientstruct']['Structurereferente']['lib_struc'],

					$examenaudition,

					$this->Form->input( "Defautinsertionep66.{$i}.id", array( 'type' => 'hidden' ) ).
					$this->Form->input( "Defautinsertionep66.{$i}.dossierep_id", array( 'type' => 'hidden', 'value' => $dossierep['Dossierep']['id'] ) ).
					$this->Form->input( "Decisiondefautinsertionep66.{$i}.id", array( 'type' => 'hidden' ) ).
					$this->Form->input( "Decisiondefautinsertionep66.{$i}.etape", array( 'type' => 'hidden', 'value' => 'cg' ) ).
					$this->Form->input( "Decisiondefautinsertionep66.{$i}.passagecommissionep_id", array( 'type' => 'hidden', 'value' ) ).

					$avisEp,

					array(
						$this->Form->input( "Decisiondefautinsertionep66.{$i}.decision", array( 'type' => 'select', 'label' => false, 'empty' => true, 'options' => $options['Decisiondefautinsertionep66']['decision'] ) )/*.
						$this->Form->input( "Decisiondefautinsertionep66.{$i}.decisionsup", array( 'type' => 'select', 'label' => false, 'empty' => true, 'options' => $options['Decisiondefautinsertionep66']['decisionsup'], 'value' => @$decisionsdefautsinsertionseps66[$i]['decisionsup'] ) )*/,
						array( 'id' => "Decisiondefautinsertionep66{$i}DecisionColumn", 'class' => ( !empty( $this->validationErrors['Decisiondefautinsertionep66'][$i]['decision'] ) || !empty( $this->validationErrors['Decisiondefautinsertionep66'][$i]['decisionsup'] ) ? 'error' : '' ) )
					),
					array(
						$this->Form->input( "Decisiondefautinsertionep66.{$i}.typeorient_id", array( 'label' => false, 'options' => $typesorients, 'empty' => true ) ),
						( !empty( $this->validationErrors['Decisiondefautinsertionep66'][$i]['typeorient_id'] ) ? array( 'class' => 'error' ) : array() )
					),
					array(
						$this->Form->input( "Decisiondefautinsertionep66.{$i}.structurereferente_id", array( 'label' => false, 'options' => $structuresreferentes, 'empty' => true, 'type' => 'select' ) ),
						( !empty( $this->validationErrors['Decisiondefautinsertionep66'][$i]['structurereferente_id'] ) ? array( 'class' => 'error' ) : array() )
					),
					$this->Form->input( "Decisiondefautinsertionep66.{$i}.referent_id", array( 'label' => false, 'options' => $referents, 'empty' => true, 'type' => 'select' ) ),
	// 				array( $this->Form->input( "Decisiondefautinsertionep66.{$i}.raisonnonpassage", array( 'label' => false, 'type' => 'textarea' ) ), array( 'colspan' => '3' ) ),
					$this->Form->input( "Decisiondefautinsertionep66.{$i}.commentaire", array( 'label' =>false, 'type' => 'textarea' ) ).
					$hiddenFields,
					array( $this->Xhtml->link( 'Voir', array( 'controller' => 'dossiers', 'action' => 'view', $dossierep['Personne']['Foyer']['dossier_id'] ), array( 'class' => 'external' ) ), array( 'class' => 'button view' ) )
				),
				array( 'class' => "odd {$multiple}" ),
				array( 'class' => "even {$multiple}" )
			);
		}
	}
	echo '</tbody></table>';

	if( !empty( $hiddenDossiers ) ) {
		echo '<div>';
		foreach( $hiddenDossiers['Decisiondefautinsertionep66'] as $i => $hiddenDossier ) {
			foreach( $hiddenDossier as $field => $value ) {
				echo $this->Xform->input( "Decisiondefautinsertionep66.{$i}.{$field}", array( 'type' => 'hidden' ) );
			}
		}
		echo '</div>';
	}
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
