<?php
	$title_for_layout = 'Recherche par allocataires transférés';
	$this->set( compact( 'title_for_layout' ) );
	echo $this->Html->tag( 'h1', $title_for_layout );

	// Filtre
	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}

	echo '<ul class="actionMenu"><li>'.$this->Xhtml->link(
		$this->Xhtml->image(
			'icons/application_form_magnify.png',
			array( 'alt' => '' )
		).' Formulaire',
		'#',
		array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "var form = $$( 'form' ); form = form[0]; $( form ).toggle(); return false;" )
	).'</li></ul>';

	// Filtre
	echo $this->Form->create( null, array( 'type' => 'post', 'url' => array( 'controller' => $this->request->params['controller'], 'action' => $this->request->params['action'] ), 'id' => 'Search', 'class' => ( ( is_array( $this->request->data ) && !empty( $this->request->data ) && isset( $this->request->data['Search']['active'] ) ) ? 'folded' : 'unfolded' ) ) );

	echo $this->Form->input( 'Search.active', array( 'type' => 'hidden', 'value' => true ) );

	echo $this->Search->blocAllocataire( array(), array(), 'Search' );
	echo $this->Search->toppersdrodevorsa( $options['toppersdrodevorsa'], 'Search.Calculdroitrsa.toppersdrodevorsa' );

	echo $this->Search->date( 'Search.Orientstruct.date_valid' );
	echo $this->Form->input( 'Search.Dossier.dernier', array( 'label' => 'Uniquement la dernière demande RSA pour un même allocataire', 'type' => 'checkbox' ) );
	echo $this->Search->blocAdresse( $options['mesCodesInsee'], $options['cantons'], 'Search' );

	echo $this->Search->etatdosrsa( $options['etatdosrsa'], 'Search.Situationdossierrsa.etatdosrsa' );

	echo $this->Form->input( 'Search.Orientstruct.typeorient_id', array( 'label' => 'Type d\'orientation', 'type' => 'select', 'empty' => true, 'options' => $options['typesorients'] ) );

	echo $this->Form->input( 'Search.NvOrientstruct.structurereferente_id', array( 'label' => 'Structure référente cible', 'type' => 'select', 'empty' => true, 'options' => $options['structuresreferentes'] ) );

	echo $this->Search->referentParcours( $structuresreferentesparcours, $referentsparcours, 'Search' );
	echo $this->Search->paginationNombretotal( 'Search.Pagination.nombre_total' );

	echo $this->Form->submit( __( 'Search' ) );
	echo $this->Form->end();

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
					'Adresse précédente',
					'Allocataire',
					__d( 'prestation', 'Prestation.rolepers' ),
					'Date de transfert',
					'Structure référente source',
					'Structure référente cible',
					'Actions',
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
						$result['NvStructurereferente']['lib_struc'],
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