<?php
	$title_for_layout = 'Transferts PDV - Allocataires transférés';
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

			echo '<table class="cohortestransfertspdvs93 transferes">';
			echo '<thead>';
			echo $this->Html->tableHeaders(
				array(
					__d( 'dossier', 'Dossier.numdemrsa' ),
					__d( 'dossier', 'Dossier.matricule' ),
					'Adresse actuelle',
					'Allocataire',
					__d( 'prestation', 'Prestation.rolepers' ),
					'Date de transfert',
					'Structure référente source',
					'Structure référente cible',
					array( 'Actions' => array( 'colspan' => 2 ) ),
				)
			);
			echo '</thead>';
			echo '<tbody>';
			foreach( $results as $index => $result ) {
				echo $this->Html->tableCells(
					array(
						h( $result['Dossier']['numdemrsa'] ),
						h( $result['Dossier']['matricule'] ),
						h( "{$result['Adresse']['codepos']} {$result['Adresse']['locaadr']}" ),
						h( "{$options['qual'][$result['Personne']['qual']]} {$result['Personne']['nom']} {$result['Personne']['prenom']}" ),
						$options['rolepers'][$result['Prestation']['rolepers']],
						$this->Locale->date( __( 'Date::short' ), $result['Transfertpdv93']['created'] ),
						$result['VxStructurereferente']['lib_struc'],
						$result['Structurereferente']['lib_struc'],
						$this->Xhtml->printLink(
							'Voir',
							array( 'controller' => 'cohortestransfertspdvs93', 'action' => 'impression', $result['Orientstruct']['id'] ),
							$this->Permissions->check( 'cohortestransfertspdvs93', 'impression' ),
							true
						),
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

			echo $pagination;
		}
	}
?>
<?php if( isset( $results ) ):?>
<ul class="actionMenu">
	<li><?php
		echo $this->Xhtml->printCohorteLink(
			'Imprimer la cohorte',
			Set::merge(
				array(
					'controller' => 'cohortestransfertspdvs93',
					'action'     => 'impressions',
				),
				Set::flatten( $this->request->data )
			),
			( $this->Permissions->check( 'cohortestransfertspdvs93', 'impressions' ) && count( $results ) > 0 )
		);
	?></li>
	<li><?php
		echo $this->Xhtml->exportLink(
			'Télécharger le tableau',
			array( 'action' => 'exportcsv' ) + Set::flatten( $this->request->data, '__' ),
			( $this->Permissions->check( 'cohortestransfertspdvs93', 'exportcsv' ) && count( $results ) > 0 )
		);
	?></li>
</ul>
<?php endif;?>