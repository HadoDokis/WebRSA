<?php
	$this->pageTitle = '4. Décision CG - 4.2 Validation CS';
	
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->script( array( 'prototype.event.simulate.js' ) );
	}
	
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
						<th>N° dossier RSA</th>
						<th>Nom/Prénom</th>
						<th>N° CAF</th>
						<th>Commune</th>
						<th>Date d\'envoi CER</th>
						<th>Date de début CER</th>
						<th>Statut du CER</th>
						<th>Forme du CER (Responsable)</th>
						<th>Commentaire (Responsable)</th>
						<th>Prévalidé</th>
						<th class="action">Forme du CER (CG)</th>
						<th class="action">Commentaire (CG)</th>
						<th class="action">Décision CS</th>
						<th class="action">Date de décision</th>
						<th class="action">Action</th>
						<th class="action" colspan="2">Détails</th>
					</tr>
				</thead>';
			echo '<tbody>';
			foreach( $cers93 as $index => $cer93 ) {
				$innerTable = '<table id="innerTablesearchResults'.$index.'" class="innerTable">
					<tbody>
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
						$cer93['Dossier']['numdemrsa'],
						$cer93['Personne']['nom_complet_court'],
						$cer93['Dossier']['matricule'],
						$cer93['Adresse']['locaadr'],
						date_short( $cer93['Contratinsertion']['created'] ),
						date_short( $cer93['Contratinsertion']['dd_ci'] ),
						Set::enum( $cer93['Cer93']['positioncer'], $options['Cer93']['positioncer'] ),
						Set::enum( $cer93['Histochoixcer93']['formeci'], $options['formeci'] ),
						$cer93['Histochoixcer93']['commentaire'].' (émis par '.Set::enum( $cer93['Histochoixcer93']['user_id'], $options['gestionnaire'] ).' )',
						Set::enum( $cer93['Histochoixcer93']['prevalide'], $options['Histochoixcer93']['prevalide'] ),
						// Choix du Responsable
						array(
							$this->Form->input( "Histochoixcer93.{$index}.dossier_id", array( 'type' => 'hidden' ) )
							.$this->Form->input( "Histochoixcer93.{$index}.cer93_id", array( 'type' => 'hidden' ) )
							.$this->Form->input( "Histochoixcer93.{$index}.user_id", array( 'type' => 'hidden' ) )
							.$this->Form->input( "Histochoixcer93.{$index}.etape", array( 'type' => 'hidden') )
							.$this->Form->input( "Histochoixcer93.{$index}.formeci", array( 'div' => false, 'legend' => false, 'type' => 'radio', 'options' => $options['formeci'], 'separator' => '<br />' ) ),
							array( 'class' => ( isset( $this->validationErrors['Histochoixcer93'][$index]['formeci'] ) ? 'error' : null ) )
						),
						array(
							$this->Form->input( "Histochoixcer93.{$index}.commentaire", array( 'label' => false, 'legend' => false, 'type' => 'textarea' ) ),
							array( 'class' => ( isset( $this->validationErrors['Histochoixcer93'][$index]['commentaire'] ) ? 'error' : null ) )
						),
						array(
							$this->Form->input( "Histochoixcer93.{$index}.decisioncs", array( 'empty' => 'En attente', 'label' => false, 'type' => 'select', 'options' => $options['Histochoixcer93']['decisioncs'] ) ),
							array( 'class' => ( isset( $this->validationErrors['Histochoixcer93'][$index]['decisioncs'] ) ? 'error' : null ) )
						),
						array(
							$this->Form->input( "Histochoixcer93.{$index}.datechoix", array( 'label' => false, 'type' => 'date', 'dateFormat' => 'DMY', 'empty' => false ) ),
							array( 'class' => ( isset( $this->validationErrors['Histochoixcer93'][$index]['datechoix'] ) ? 'error' : null ) )
						),
						// Action
						array(
							$this->Form->input( "Histochoixcer93.{$index}.action", array( 'div' => false, 'legend' => false, 'type' => 'radio', 'options' => $options['actions'], 'separator' => '<br />' ) ),
							array( 'class' => ( isset( $this->validationErrors['Histochoixcer93'][$index]['action'] ) ? 'error' : null ) )
						),
						// Détails
						$this->Xhtml->printLink(
							'Décision',
							array( 'controller' => 'cers93', 'action' => 'impressionDecision', $cer93['Contratinsertion']['id'] ),
							( $this->Permissions->check( 'cers93', 'impressionDecision' ) )
						),
						$this->Xhtml->viewLink( 'Voir', array( 'controller' => 'cers93', 'action' => 'index', $cer93['Personne']['id'] ), true, true ),
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
			echo $this->Form->button( 'Tout Valider', array( 'onclick' => "return toutChoisir( $( 'Personne' ).getInputs( 'radio' ), 'Valider', true );" ) );
			echo $this->Form->button( 'Tout mettre En attente', array( 'onclick' => "return toutChoisir( $( 'Personne' ).getInputs( 'radio' ), 'En attente', true );" ) );
		}
	
		echo '<ul class="actionMenu"><li>';
		echo $this->Xhtml->printCohorteLink(
			'Imprimer la cohorte',
			Set::merge(
				array(
					'controller' => 'cohortescers93',
					'action'     => 'impressionsDecisions',
					'validationcs'
				),
				Set::flatten( $this->request->data )
			),
			$this->Permissions->check( 'cohortescers93', 'impressionsDecisions' )
		);
		echo '</li></ul>';

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
				'Histochoixcer93<?php echo $index;?>Decisioncs',
				'Histochoixcer93<?php echo $index;?>DatechoixDay',
				'Histochoixcer93<?php echo $index;?>DatechoixMonth',
				'Histochoixcer93<?php echo $index;?>DatechoixYear',
				'Histochoixcer93<?php echo $index;?>Commentaire'
			],
			[ 'Valider' ],
			true
		);
		
		observeFilterSelectOptionsFromRadioValue(
			'Personne',
			'data[Histochoixcer93][<?php echo $index;?>][formeci]',
			'Histochoixcer93<?php echo $index;?>Decisioncs',
			{
				'S': ['valide', 'aviscadre'],
				'C': ['aviscadre', 'passageep']
			}
		);
		<?php endforeach;?>
	} );
</script>

<script type="text/javascript">
	
</script>
<?php endif;?>