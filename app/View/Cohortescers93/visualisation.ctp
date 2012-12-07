<?php
	$this->pageTitle = '5. Tableau de suivi';
	echo $this->Xhtml->tag( 'h1', $this->pageTitle );

	require_once( dirname( __FILE__ ).DS.'filtre.ctp' );

	if( isset( $cers93 ) ) {
		if( empty( $cers93 ) ) {
			echo $this->Xhtml->tag( 'p', 'Aucun résultat', array( 'class' => 'notice' ) );
		}
		else {
			$pagination = $this->Xpaginator->paginationBlock( 'Personne', $this->passedArgs );
			echo $pagination;
// - Non orienté PDV : avec comme domaine de valeurs ( visible uniquement des profils CG)
//
// 	- si allocataire non orienté alors : lien hypertexte Orientation vers le dossier allocataire rubrique Orientation
// 	- si allocataire est orienté PE ou Social : lien hypertexte Réorientation vers le dossier allocataire rubrique Orientation
// 	- si allocataire orienté vers PDV : vide
//
			echo '<table id="searchResults" class="tooltips">';
			echo '<colgroup />
					<!-- <colgroup /> -->
					<colgroup />
					<colgroup />
					<colgroup />
					<colgroup />
					<colgroup span="4" style="border-right: 5px solid #235F7D;border-left: 5px solid #235F7D;" />
					<colgroup span="2" style="border-right: 5px solid #235F7D;border-left: 5px solid #235F7D;" />
					<colgroup />
					<colgroup span="2" style="border-right: 5px solid #235F7D;border-left: 5px solid #235F7D;" />
					<colgroup />
					<colgroup />
				<thead>
					<tr>
						<th rowspan="2">Commune</th>
 						<!-- <th rowspan="2">Non orienté PDV</th> -->
						<th rowspan="2">Nom/Prénom</th>
						<th rowspan="2">Structure référente</th>
						<th rowspan="2">Référent</th>
						<th rowspan="2">Saisie du CER</th>
						<th colspan="4">Etape CPDV</th>
						<th colspan="2">Etape CG</th>
						<th rowspan="2">Validation CS</th>
						<th colspan="2">Etape Cadre</th>
						<th colspan="2" rowspan="2">Actions</th>
					</tr>
					<tr>
						<th>Validation CPDV</th>
						<th>Forme du CER</th>
						<th>Commentaire du CPDV</th>
						<th>Date de transfert au CG</th>
						<th>Validation CG (1ère lecture)</th>
						<th>Commentaire du CG</th>
						<th>Validation Cadre</th>
						<th>Forme CER</th>
					</tr>
				</thead>';
			echo '<tbody>';
			foreach( $cers93 as $index => $cer93 ) {

				if( isset( $cer93['Histochoixcer93etape03']['isrejet'] ) && ( $cer93['Histochoixcer93etape03']['isrejet'] == '1' ) ) {
					$validationcpdv = 'Rejeté';
				}
				else if( isset( $cer93['Histochoixcer93etape03']['isrejet'] ) && ( $cer93['Histochoixcer93etape03']['isrejet'] == '0' ) ) {
					$validationcpdv = 'Oui';//Set::enum( $cer93['Histochoixcer93etape03']['etape'], $options['Cer93']['positioncer'] );
				}
				else {
					$validationcpdv = '';
				}

				echo $this->Html->tableCells(
					array(
						$cer93['Adresse']['locaadr'],
// 						'e',
						$cer93['Personne']['nom_complet_court'],
						$cer93['Structurereferente']['lib_struc'],
						$cer93['Referent']['nom_complet'],
						Set::enum( $cer93['Cer93']['positioncer'], $options['Cer93']['positioncer'] ),
						$validationcpdv,
						Set::enum( $cer93['Histochoixcer93etape03']['formeci'], $options['formeci'] ),
						$cer93['Histochoixcer93etape03']['commentaire'],
						date_short( $cer93['Histochoixcer93etape03']['datechoix'] ),
						Set::enum( $cer93['Histochoixcer93etape04']['prevalide'], $options['Histochoixcer93']['prevalide'] ),
						$cer93['Histochoixcer93etape04']['commentaire'],
						Set::enum( $cer93['Histochoixcer93etape05']['decisioncs'], $options['Histochoixcer93']['decisioncs'] ),
						Set::enum( $cer93['Histochoixcer93etape06']['decisioncadre'], $options['Histochoixcer93']['decisioncadre'] ),
						Set::enum( $cer93['Histochoixcer93etape06']['formeci'], $options['formeci'] ),
						$this->Xhtml->viewLink( 'Voir', array( 'controller' => 'cers93', 'action' => 'index', $cer93['Personne']['id'] ) ),
						$this->Xhtml->printLink( 'Imprimer', array( 'controller' => 'cers93', 'action' => 'impression', $cer93['Contratinsertion']['id'] ) )
					),
					array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
					array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
				);
			}
			echo '</tbody>';
			echo '</table>';

			echo $pagination;

		}
	}
?>
<?php if( isset( $cers93 ) ):?>
<ul class="actionMenu">
	<li><?php
		echo $this->Xhtml->exportLink(
			'Télécharger le tableau',
			array( 'action' => 'exportcsv', 'visualisation' ) + Set::flatten( $this->request->data, '__' ),
			( $this->Permissions->check( 'cohortescers93', 'exportcsv' ) && count( $cers93 ) > 0 )
		);
	?></li>
</ul>
<?php endif;?>