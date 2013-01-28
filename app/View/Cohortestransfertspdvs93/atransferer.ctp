<?php
	$title_for_layout = 'Transferts PDV - Allocataires à transférer';
	$this->set( compact( 'title_for_layout' ) );
	echo $this->Html->tag( 'h1', $title_for_layout );

	require_once( dirname( __FILE__ ).DS.'filtre.ctp' );

	// Résultats
	if( isset( $results ) ) {
		if( empty( $results ) ) {
			echo $this->Html->tag( 'p', 'Aucun résultat', array( 'class' => 'notice' ) );
		}
		else {
			$pagination = $this->Xpaginator2->paginationBlock( 'Dossier', $this->passedArgs );
			echo $pagination;

			echo $this->Form->create( null, array( 'type' => 'post', 'url' => Router::url( null, true ), 'id' => 'Cohortestransfertspdvs93Index' ) );
			echo '<table class="cohortestransfertspdvs93 index">';
			echo '<thead>';
			echo $this->Html->tableHeaders(
				array(
					__d( 'dossier', 'Dossier.numdemrsa' ),
					__d( 'dossier', 'Dossier.matricule' ),
					'Adresse actuelle',
					'Allocataire',
					__d( 'prestation', 'Prestation.rolepers' ),
					'Position CER',
					'Structure référente source',
					'Structure référente cible',
					array( 'Actions' => array( 'colspan' => 2 ) ),
				)
			);
			echo '</thead>';
			echo '<tbody>';
			foreach( $results as $index => $result ) {
// 			debug($options);
				$hidden = $this->Form->inputs(
					array(
						'fieldset' => false,
						'legend' => false,
						"Transfertpdv93.{$index}.vx_orientstruct_id" => array( 'type' => 'hidden' ),
						"Transfertpdv93.{$index}.personne_id" => array( 'type' => 'hidden' ),
						"Transfertpdv93.{$index}.typeorient_id" => array( 'type' => 'hidden' ),
						"Transfertpdv93.{$index}.vx_adressefoyer_id" => array( 'type' => 'hidden' ),
						"Transfertpdv93.{$index}.nv_adressefoyer_id" => array( 'type' => 'hidden' ),
					)
				);

				echo $this->Html->tableCells(
					array(
						$hidden.h( $result['Dossier']['numdemrsa'] ),
						h( $result['Dossier']['matricule'] ),
						h( "{$result['Adresse']['codepos']} {$result['Adresse']['locaadr']}" ),
						h( "{$options['qual'][$result['Personne']['qual']]} {$result['Personne']['nom']} {$result['Personne']['prenom']}" ),
						$options['rolepers'][$result['Prestation']['rolepers']],
						Set::enum( $result['Cer93']['positioncer'], $options['Cer93']['positioncer'] ),
						$result['Structurereferente']['lib_struc'],
						$this->Form->input( "Transfertpdv93.{$index}.structurereferente_dst_id", array( 'type' => 'select', 'empty' => true, 'options' => $options['structuresreferentes'][$result['Orientstruct']['typeorient_id']], 'label' => false, 'div' => false ) ),
						$this->Form->input( "Transfertpdv93.{$index}.action", array( 'type' => 'radio', 'options' => $options['action'], 'fieldset' => false, 'legend' => false ) ),
						$this->Xhtml->viewLink(
							'Voir',
							array( 'controller' => 'dossiers', 'action' => 'view', $result['Dossier']['id'] ),
							$this->Permissions->check( 'dossiers', 'view' ),
							true
						)
					),
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
			}
			echo '</tbody>';
			echo '</table>';

			echo $this->Form->submit( __( 'Save' ) );
			echo $this->Form->end();

			echo $pagination;
		}
	}
?>
<?php if( isset( $results ) && !empty( $results ) ):?>
<script type="text/javascript">
	document.observe( "dom:loaded", function() {
		<?php foreach( array_keys( $results ) as $index ):?>
		observeDisableFieldsOnRadioValue(
			'Cohortestransfertspdvs93Index',
			'data[Transfertpdv93][<?php echo $index;?>][action]',
			[
				'Transfertpdv93<?php echo $index;?>VxOrientstructId',
				'Transfertpdv93<?php echo $index;?>PersonneId',
				'Transfertpdv93<?php echo $index;?>TypeorientId',
				'Transfertpdv93<?php echo $index;?>StructurereferenteDstId',

//				'Transfertpdv93<?php echo $index;?>DossierId',
				/*'Transfertpdv93<?php echo $index;?>StructurereferenteSrcId',
				'Transfertpdv93<?php echo $index;?>Codeinsee',
				'Transfertpdv93<?php echo $index;?>Dtemm',
				'Transfertpdv93<?php echo $index;?>AdressefoyerId',
				'Transfertpdv93<?php echo $index;?>OrientstructId',
				'Transfertpdv93<?php echo $index;?>PersonneId',
				'Transfertpdv93<?php echo $index;?>TypeorientId',
				'Transfertpdv93<?php echo $index;?>StructurereferenteDstId',*/
			],
			[ '1' ],
			true
		);
		<?php endforeach;?>
	} );

	observeDisableFormOnSubmit( 'Cohortestransfertspdvs93Index', 'Enregistrement en cours ...' );
</script>
<?php endif;?>