<?php
	$this->pageTitle = '4. Décision CG - 4.3 Validation Cadre';
	
	echo $this->Xhtml->tag( 'h1', $this->pageTitle );

	require_once( dirname( __FILE__ ).DS.'filtre.ctp' );

	if( isset( $cers93 ) ) {
		if( empty( $cers93 ) ) {
			echo $this->Xhtml->tag( 'p', 'Aucun résultat', array( 'class' => 'notice' ) );
		}
		else {
			$pagination = $this->Xpaginator->paginationBlock( 'Personne', $this->passedArgs );
			echo $pagination;

			echo $this->Xform->create( null, array( 'id' => 'Personne' ) );
			
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
						<th>Forme du CER (CPDV)</th>
						<th>Commentaire (CPDV)</th>
						<th>Forme du CER (CG)</th>
						<th>Commentaire (CG)</th>
						<th>Décision Cadre</th>
						<th>Action</th>
						<th>Détails</th>
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
						$cer93['Histochoixcer93']['commentaire'],
						// Choix du CPDV
						array(
							$this->Form->input( "Histochoixcer93.{$index}.dossier_id", array( 'type' => 'hidden' ) )
							.$this->Form->input( "Histochoixcer93.{$index}.cer93_id", array( 'type' => 'hidden' ) )
							.$this->Form->input( "Histochoixcer93.{$index}.user_id", array( 'type' => 'hidden' ) )
							.$this->Form->input( "Histochoixcer93.{$index}.etape", array( 'type' => 'hidden') )
							.$this->Form->input( "Histochoixcer93.{$index}.formeci", array( 'div' => false, 'legend' => false, 'type' => 'radio', 'options' => $options['formeci'], 'separator' => '<br />' ) ),
							array( 'class' => ( isset( $this->validationErrors['Histochoixcer93'][$index]['formeci'] ) ? 'error' : null ) )
						),
						array(
							$this->Form->input( "Histochoixcer93.{$index}.commentaire", array( 'label' => false, 'legend' => false, 'type' => 'textarea' ) )
							.$this->Form->input( "Histochoixcer93.{$index}.datechoix", array( 'type' => 'hidden', 'value' => date( 'Y-m-d' ) ) ),
							array( 'class' => ( isset( $this->validationErrors['Histochoixcer93'][$index]['commentaire'] ) ? 'error' : null ) )
						),
						array(
							$this->Form->input( "Histochoixcer93.{$index}.decisioncadre", array( 'div' => false, 'legend' => false, 'type' => 'radio', 'options' => $options['Histochoixcer93']['decisioncadre'], 'separator' => '<br />' ) ),
							array( 'class' => ( isset( $this->validationErrors['Histochoixcer93'][$index]['decisioncs'] ) ? 'error' : null ) )
						),
						// Action
						array(
							$this->Form->input( "Histochoixcer93.{$index}.action", array( 'div' => false, 'legend' => false, 'type' => 'radio', 'options' => $options['actions'], 'separator' => '<br />' ) ),
							array( 'class' => ( isset( $this->validationErrors['Histochoixcer93'][$index]['action'] ) ? 'error' : null ) )
						),
						// Détails
						$this->Xhtml->viewLink( 'Voir', array( 'controller' => 'cers93', 'action' => 'index', $cer93['Personne']['id'] ) ),
						array( $innerTable, array( 'class' => 'innerTableCell noprint' ) )
					),
					array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
					array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
				);
			}
			echo '</tbody>';
			echo '</table>';
			echo $this->Xform->submit( 'Validation de la liste' );
			echo $this->Xform->end();

			echo $pagination;
		}

	}
?>
<?php if( isset( $cers93 ) && !empty( $cers93 ) ):?>
<script type="text/javascript">
	document.observe( "dom:loaded", function() {
		// On désactive le select du référent si on ne choisit pas de valider
		<?php foreach( array_keys( $cers93 ) as $index ):?>
		observeDisableFieldsOnRadioValue(
			'Personne',
			'data[Histochoixcer93][<?php echo $index;?>][action]',
			[
				'Histochoixcer93<?php echo $index;?>FormeciS',
				'Histochoixcer93<?php echo $index;?>FormeciC',
				'Histochoixcer93<?php echo $index;?>DecisioncadreValide',
				'Histochoixcer93<?php echo $index;?>DecisioncadreRejete',
				'Histochoixcer93<?php echo $index;?>DecisioncadrePassageep',
				'Histochoixcer93<?php echo $index;?>Commentaire'
			],
			[ 'Valider' ],
			true
		);
		<?php endforeach;?>
	} );
</script>
<?php endif;?>