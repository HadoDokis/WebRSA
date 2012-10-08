<?php
echo '<table>
		<colgroup />
		<colgroup />
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
				<th rowspan="2">Création du dossier EP</th>
				<th rowspan="2">Date d\'orientation</th>
				<th rowspan="2">Orientation actuelle</th>
				<th rowspan="2">Origine</th>
				<th rowspan="2">Motif saisine</th>
				<th rowspan="2">Date de radiation Pôle Emploi</th>
				<th rowspan="2">Motif de radiation Pôle Emploi</th>
				<th colspan="4">Avis EPL</th>
				<th rowspan="2">Observations</th>
                                <th rowspan="2">Actions</th>
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
		$multiple = ( count( $dossiersAllocataires[$dossierep['Personne']['id']] ) > 1 ? 'multipleDossiers' : null );

		$decisionep = $dossierep['Passagecommissionep'][0]['Decisiondefautinsertionep66'][0];

		echo $this->Xhtml->tableCells(
			array(
				implode( ' ', array( $dossierep['Personne']['qual'], $dossierep['Personne']['nom'], $dossierep['Personne']['prenom'] ) ),
				implode( ' ', array( $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['numvoie'], isset( $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] ) ? $typevoie[$dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['typevoie']] : null, $dossierep['Personne']['Foyer']['Adressefoyer'][0]['Adresse']['nomvoie'] ) ),
				$this->Locale->date( __( 'Locale->date' ), $dossierep['Personne']['dtnai'] ),
				$this->Locale->date( __( 'Locale->date' ), $dossierep['Dossierep']['created'] ),
				$this->Locale->date( __( 'Locale->date' ), @$dossierep['Defautinsertionep66']['Orientstruct']['date_valid'] ),
				@$dossierep['Defautinsertionep66']['Orientstruct']['Typeorient']['lib_type_orient'],
				Set::enum( $dossierep['Defautinsertionep66']['origine'], $options['Defautinsertionep66']['origine'] ),
				Set::enum( @$dossierep['Defautinsertionep66']['Bilanparcours66']['examenaudition'], $options['Defautinsertionep66']['type'] ),
				$this->Locale->date( __( 'Locale->date' ), @$dossierep['Defautinsertionep66']['Historiqueetatpe']['date'] ),
				@$dossierep['Defautinsertionep66']['Historiqueetatpe']['motif'],

				array( implode( ' / ', Set::filter( array(
					$options['Decisiondefautinsertionep66']['decision'][Set::classicExtract( $decisionep, "decision" )],
					@$options['Decisiondefautinsertionep66']['decisionsup'][Set::classicExtract( $decisionep, "decisionsup" )]
				) ) ), array( 'id' => "Decisiondefautinsertionep66{$i}DecisionColumn" ) ),

				array( @$liste_typesorients[Set::classicExtract( $decisionep, "typeorient_id" )], array( 'id' => "Decisiondefautinsertionep66{$i}TypeorientId" ) ),
				array( @$liste_structuresreferentes[Set::classicExtract( $decisionep, "structurereferente_id" )], array( 'id' => "Decisiondefautinsertionep66{$i}StructurereferenteId" ) ),
				array( @$liste_referents[Set::classicExtract( $decisionep, "referent_id" )], array( 'id' => "Decisiondefautinsertionep66{$i}ReferentId" ) ),
				Set::classicExtract( $decisionep, "commentaire" ),
                                array( $this->Xhtml->link( 'Voir', array( 'controller' => 'historiqueseps', 'action' => 'view_passage', $dossierep['Passagecommissionep'][0]['id'] ), array( 'class' => 'external' ) ), array( 'class' => 'button view' ) ),
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
			changeColspanViewInfosEps( 'Decisiondefautinsertionep66<?php echo $i;?>DecisionColumn', '<?php echo Set::classicExtract( $dossiers, "{$theme}.liste.{$i}.Passagecommissionep.0.Decisiondefautinsertionep66.0.decision" );?>', 4, [ 'Decisiondefautinsertionep66<?php echo $i;?>TypeorientId', 'Decisiondefautinsertionep66<?php echo $i;?>StructurereferenteId', 'Decisiondefautinsertionep66<?php echo $i;?>ReferentId' ] );
		<?php endfor;?>
	});
</script>