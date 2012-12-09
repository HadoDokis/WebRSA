<?php
	$this->pageTitle = '2. Saisie d\'un CER';
	echo $this->Xhtml->tag( 'h1', $this->pageTitle );
	
	require_once( dirname( __FILE__ ).DS.'filtre.ctp' );

	if( isset( $cers93 ) ) {
		if( empty( $cers93 ) ) {
			echo $this->Xhtml->tag( 'p', 'Aucun résultat', array( 'class' => 'notice' ) );
		}
		else {
			$pagination = $this->Xpaginator->paginationBlock( 'Personne', $this->passedArgs );
			echo $pagination;

			echo '<table id="searchResults" class="tooltips">';
			echo '<thead>
					<tr>
						<th>Commune</th>
						<th>Nom/Prénom</th>
						<th>Date d\'orientation</th>
						<th>Date de désignation</th>
						<th>Référent</th>
						<th>Rang CER</th>
						<th>Dernier RDV</th>
						<th>Statut CER</th>
						<th>Forme du CER</th>
						<!-- <th>Commentaire</th> -->
						<th>Date de fin CER</th>
						<th>Actions</th>
					</tr>
				</thead>';
			echo '<tbody>';
			foreach( $cers93 as $index => $cer93 ) {
				$innerTable = '<table id="innerTablesearchResults'.$index.'" class="innerTable">
						<tbody>
							<tr>
								<th>N° de dossier</th>
								<td>'.$cer93['Dossier']['numdemrsa'].'</td>
							</tr>
							<tr>
								<th>Date ouverture de droit</th>
								<td>'.date_short( $cer93['Dossier']['dtdemrsa'] ).'</td>
							</tr>
							<tr>
								<th>Date de naissance</th>
								<td>'.date_short( $cer93['Personne']['dtnai'] ).'</td>
							</tr>
							<tr>
								<th>N° CAF</th>
								<td>'.$cer93['Dossier']['matricule'].'</td>
							</tr>
							<tr>
								<th>NIR</th>
								<td>'.$cer93['Personne']['nir'].'</td>
							</tr>
							<tr>
								<th>Code postal</th>
								<td>'.$cer93['Adresse']['codepos'].'</td>
							</tr>
							<tr>
								<th>Date de fin de droit</th>
								<td>'.$cer93['Situationdossierrsa']['dtclorsa'].'</td>
							</tr>
							<tr>
								<th>Motif de fin de droit</th>
								<td>'.$cer93['Situationdossierrsa']['moticlorsa'].'</td>
							</tr>
							<tr>
								<th>Rôle</th>
								<td>'.Set::enum( $cer93['Prestation']['rolepers'], $options['rolepers'] ).'</td>
							</tr>
							<tr>
								<th>Etat du dossier</th>
								<td>'.Set::classicExtract( $options['etatdosrsa'], $cer93['Situationdossierrsa']['etatdosrsa'] ).'</td>
							</tr>
							<tr>
								<th>Présence DSP</th>
								<td>'.$this->Xhtml->boolean( $cer93['Dsp']['exists'] ).'</td>
							</tr>
							<tr>
								<th>Adresse</th>
								<td>'.$cer93['Adresse']['numvoie'].' '.Set::enum( $cer93['Adresse']['typevoie'], $options['typevoie'] ).' '.$cer93['Adresse']['nomvoie'].' '.$cer93['Adresse']['codepos'].' '.$cer93['Adresse']['locaadr'].'</td>
							</tr>
						</tbody>
					</table>';
					
				echo $this->Html->tableCells(
					array(
						$cer93['Adresse']['locaadr'],
						$cer93['Personne']['nom_complet_court'],
						date_short( $cer93['Orientstruct']['date_valid'] ),
						date_short( $cer93['PersonneReferent']['dddesignation'] ),
						$cer93['Referent']['nom_complet'],
						$cer93['Contratinsertion']['rg_ci'],
						date_short( $cer93['Rendezvous']['daterdv'] ),
						Set::enum( $cer93['Cer93']['positioncer'], $options['Cer93']['positioncer'] ),
						Set::enum( $cer93['Histochoixcer93']['formeci'], $options['formeci'] ),
// 						$cer93['Cer93']['observbenef'],
						date_short( $cer93['Contratinsertion']['df_ci'] ),
						$this->Xhtml->viewLink( 'Voir', array( 'controller' => 'cers93', 'action' => 'index', $cer93['Personne']['id'] ) ),
						array( $innerTable, array( 'class' => 'innerTableCell noprint' ) )
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
			array( 'action' => 'exportcsv', 'saisie' ) + Set::flatten( $this->request->data, '__' ),
			( $this->Permissions->check( 'cohortescers93', 'exportcsv' ) && count( $cers93 ) > 0 )
		);
	?></li>
</ul>
<?php endif;?>