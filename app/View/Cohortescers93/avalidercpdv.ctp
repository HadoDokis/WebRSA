<?php
	$this->pageTitle = '3. Validation CPDV';
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
			
			echo '<table>';
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
						<th>Rejeté ?</th>
						<th>Commentaire</th>
						<th>Action</th>
						<th>Détails</th>
					</tr>
				</thead>';
			echo '<tbody>';
			foreach( $cers93 as $index => $cer93 ) {

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
						// Choix du CPDV
						array(
							$this->Form->input( "Histochoixcer93.{$index}.dossier_id", array( 'type' => 'hidden' ) )
							.$this->Form->input( "Histochoixcer93.{$index}.cer93_id", array( 'type' => 'hidden' ) )
							.$this->Form->input( "Histochoixcer93.{$index}.user_id", array( 'type' => 'hidden' ) )
							.$this->Form->input( "Histochoixcer93.{$index}.etape", array( 'type' => 'hidden') )
							.$this->Form->input( "Histochoixcer93.{$index}.formeci", array( 'div' => false, 'legend' => false, 'type' => 'radio', 'options' => $options['formeci'], 'separator' => '<br />' ) ),
							array( 'class' => ( isset( $this->validationErrors['Histochoixcer93'][$index]['formeci'] ) ? 'error' : null ) )
						),
						$this->Form->input( "Histochoixcer93.{$index}.isrejet", array( 'div' => false, 'label' => false, 'type' => 'checkbox' ) ),
						array(
							$this->Form->input( "Histochoixcer93.{$index}.commentaire", array( 'label' => false, 'legend' => false, 'type' => 'textarea' ) )
							.$this->Form->input( "Histochoixcer93.{$index}.datechoix", array( 'type' => 'hidden', 'value' => date( 'Y-m-d' ) ) ),
							array( 'class' => ( isset( $this->validationErrors['Histochoixcer93'][$index]['commentaire'] ) ? 'error' : null ) )
						),
						// Action
						array(
							$this->Form->input( "Histochoixcer93.{$index}.action", array( 'div' => false, 'legend' => false, 'type' => 'radio', 'options' => $options['actions'], 'separator' => '<br />' ) ),
							array( 'class' => ( isset( $this->validationErrors['Histochoixcer93'][$index]['action'] ) ? 'error' : null ) )
						),
						// Détails
						$this->Xhtml->viewLink( 'Voir', array( 'controller' => 'cers93', 'action' => 'index', $cer93['Personne']['id'] ) ),
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
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
				'Histochoixcer93<?php echo $index;?>Isrejet',
				'Histochoixcer93<?php echo $index;?>Commentaire'
			],
			[ 'Valider' ],
			true
		);
		<?php endforeach;?>
	} );
</script>
<?php endif;?>