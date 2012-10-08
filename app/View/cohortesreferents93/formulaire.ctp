<?php
	$this->pageTitle = '1. Affectation d\'un référent - référents à affecter';
	echo $html->tag( 'h1', $this->pageTitle );

	require_once( dirname( __FILE__ ).DS.'filtre.ctp' );

	if( isset( $personnes_referents ) ) {
		if( empty( $personnes_referents ) ) {
			echo $html->tag( 'p', 'Aucun résultat', array( 'class' => 'notice' ) );
		}
		else {
			$pagination = $xpaginator->paginationBlock( 'Personne', $this->passedArgs );
			echo $pagination;

			echo $xform->create( null, array( 'id' => 'PersonneReferent' ) );
			echo '<table>';
			echo '<thead>
					<tr>
						<th>Commune</th>
						<th>Date de demande</th>
						<th>Date d\'orientation</th>
						<th>Date de naissance</th>
						<th>Soumis à droits et devoirs</th>
						<th>Présence d\'une DSP</th>
						<th>Rang CER</th>
						<th>Nom, prénom</th>
						<th>Affectation</th>
						<th>Action</th>
						<th>Détails</th>
					</tr>
				</thead>';
			echo '<tbody>';
			foreach( $personnes_referents as $index => $personne_referent ) {
				echo $html->tableCells(
					array(
						$personne_referent['Adresse']['locaadr'],
						date_short( $personne_referent['Dossier']['dtdemrsa'] ),
						date_short( $personne_referent['Orientstruct']['date_valid'] ),
						date_short( $personne_referent['Personne']['dtnai'] ),
						$xhtml->boolean( $personne_referent['Calculdroitrsa']['toppersdrodevorsa'] ),
						$xhtml->boolean( $personne_referent['Dsp']['exists'] ),
						$personne_referent['Contratinsertion']['rg_ci'],
						$personne_referent['Personne']['nom_complet_court'],
						// Choix du référent
						array(
							$form->input( "PersonneReferent.{$index}.dossier_id", array( 'type' => 'hidden', 'value' => $personne_referent['Dossier']['id'] ) )
							.$form->input( "PersonneReferent.{$index}.personne_id", array( 'type' => 'hidden', 'value' => $personne_referent['Personne']['id'] ) )
							.$form->input( "PersonneReferent.{$index}.structurereferente_id", array( 'type' => 'hidden', 'value' => $structurereferente_id ) )
							.$form->input( "PersonneReferent.{$index}.referent_id", array( 'label' => false, 'div' => false, 'legend' => false, 'type' => 'select', 'options' => $options['referents'], 'empty' => true ) )
							.$form->input( "PersonneReferent.{$index}.dddesignation", array( 'type' => 'hidden', 'value' => date( 'Y-m-d' ) ) ),
							array( 'class' => ( isset( $this->validationErrors['PersonneReferent'][$index]['referent_id'] ) ? 'error' : null ) )
						),
						// Action
						array(
							$form->input( "PersonneReferent.{$index}.action", array( 'div' => false, 'legend' => false, 'type' => 'radio', 'options' => $options['actions'] ) ),
							array( 'class' => ( isset( $this->validationErrors['PersonneReferent'][$index]['action'] ) ? 'error' : null ) )
						),
						$xhtml->viewLink( 'Voir', array( 'controller' => 'personnes_referents', 'action' => 'index', $personne_referent['Personne']['id'] ) ),
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
			}
			echo '</tbody>';
			echo '</table>';
			echo $xform->submit( 'Validation de la liste' );
			echo $xform->end();

			echo $pagination;
		}
	}
?>
<?php if( isset( $personnes_referents ) && !empty( $personnes_referents ) ):?>
<script type="text/javascript">
	document.observe( "dom:loaded", function() {
		// On désactive le select du référent si on ne choisit pas de valider
		<?php foreach( array_keys( $personnes_referents ) as $index ):?>
		observeDisableFieldsOnRadioValue(
			'PersonneReferent',
			'data[PersonneReferent][<?php echo $index;?>][action]',
			[ 'PersonneReferent<?php echo $index;?>ReferentId' ],
			[ 'Valider' ],
			true
		);
		<?php endforeach;?>
	} );
</script>
<?php endif;?>