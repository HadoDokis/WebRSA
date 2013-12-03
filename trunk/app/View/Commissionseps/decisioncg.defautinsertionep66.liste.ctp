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
				<!-- <th rowspan="2">Observations</th> -->
				<th colspan="2" rowspan="2">Actions</th>
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
//		if( in_array( $dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][0]['decision'], array( 'reorientationsocversprof', 'reorientationprofverssoc' ) ) ) {
			
			$examenaudition = Set::enum( @$dossierep['Defautinsertionep66']['Bilanparcours66']['examenaudition'], $options['Defautinsertionep66']['type'] );
			if( !empty( $dossierep['Defautinsertionep66']['Bilanparcours66']['examenauditionpe'] ) ){
				$examenaudition = Set::enum( @$dossierep['Defautinsertionep66']['Bilanparcours66']['examenauditionpe'], $options['Bilanparcours66']['examenauditionpe'] );
			}

			$multiple = ( count( $dossiersAllocataires[$dossierep['Personne']['id']] ) > 1 ? 'multipleDossiers' : null );

			$decisionep = @$dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][1];
			$decisioncg = @$dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][0];


            //FIXME: si les décision CG ont écrasé les décision EP on les récupère depuis les CGs
            if( is_null( $decisionep ) ) {
                $decisionep = $decisioncg;
            }
            // Fin du FIXME

            
			$avisEp = implode(
                ' - ',
                Hash::filter(
                    (array)array(
                        Set::enum( @$decisionep['decisionsup'], $options['Decisiondefautinsertionep66']['decisionsup'] ),
                        Set::enum( @$decisionep['decision'], $options['Decisiondefautinsertionep66']['decision'] ),
                        @$listeTypesorients[@$decisionep['typeorient_id']],
                        @$listeStructuresreferentes[@$decisionep['structurereferente_id']],
                        @$listeReferents[@$decisionep['referent_id']]
                    )
                )
            );

            $eplAuditionEnEplParcours = $dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][0]['decision'];
            $enabled = ( $commissionep['Commissionep']['etatcommissionep'] != 'annule' ) && ( $dossierep['Passagecommissionep'][0]['etatdossierep'] != 'annule' ) && ( in_array( $eplAuditionEnEplParcours, array( 'reorientationprofverssoc', 'reorientationsocversprof' ) ) );

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
//					Set::classicExtract( $decisioncg, "commentaire" ),
					array( $this->Xhtml->link( 'Voir', array( 'controller' => 'historiqueseps', 'action' => 'view_passage', $dossierep['Passagecommissionep'][0]['id'] ), array( 'class' => 'external' ) ), array( 'class' => 'button view' ) ),
                    $this->Xhtml->printLink( 'Imprimer', array( 'controller' => 'commissionseps', 'action' => 'impressionDecision', $dossierep['Passagecommissionep'][0]['id'] ), ( $enabled ) ),
				),
				array( 'class' => "odd {$multiple}" ),
				array( 'class' => "even {$multiple}" )
			);
//		}
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