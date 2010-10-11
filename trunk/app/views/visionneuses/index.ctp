<?php

// Fait par le CG93
// Auteur : Harry ZARKA <hzarka@cg93.fr>, 2010.

	$this->pageTitle = 'Visionneuse';

	echo $html->tag( 'h1', $this->pageTitle );

	if( empty( $visionneuses ) ) {
		echo $html->tag( 'p', 'Aucun fichier intégré pour l\'instant.', array( 'class' => 'notice' ) );
	}
	else {
        $pagination = $xpaginator->paginationBlock( 'Visionneuse', $this->passedArgs );
		
		//----------------------------------------------------------------------

		$headers = array(
			'Flux',
			'Nom',
			'Date début',
			'Date fin',
			'Durée',			
			'Dossiers',			
			'Rejetés',
			'Nouveaux',
			'MAJ',
			'Pers Créé',
			'Pers MAJ',	
			'DSP Créé',
			'DSP MAJ',			
			);

		$thead = $html->tag( 'thead', $html->tableHeaders( $headers ) );
		//$thead = str_replace( '</tr>', '<th colspan="7">Rejet</th></tr>', $thead );


		/// Corps du tableau
		$rows = array();
        
		foreach ($visionneuses as $visionneuse){
		
			$duree = strtotime(Set::classicExtract( $visionneuse, 'Visionneuse.dtfin' ))-
			strtotime(Set::classicExtract( $visionneuse, 'Visionneuse.dtdeb' ));
			
			$dossier = Set::classicExtract( $visionneuse, 'Visionneuse.nbrejete' )+
			Set::classicExtract( $visionneuse, 'Visionneuse.nbinser' )+
			Set::classicExtract( $visionneuse, 'Visionneuse.nbmaj' );
			
			$rejet = Set::classicExtract( $visionneuse, 'Visionneuse.nbrejete' );
			
			$rows[] = array(
				Set::classicExtract( $visionneuse, 'Visionneuse.flux' ),
				Set::classicExtract( $visionneuse, 'Visionneuse.nomfic' ),				
				strftime( '%d/%m/%Y %H:%M:%S' , strtotime( Set::classicExtract( $visionneuse, 'Visionneuse.dtdeb') ) ),
				strftime( '%d/%m/%Y %H:%M:%S' , strtotime( Set::classicExtract( $visionneuse, 'Visionneuse.dtfin') ) ),
				strftime('%H:%M:%S', $duree),							
				$dossier,				
				(0<$rejet)?$html->Link(
                                    $rejet,                                    								
									array( 'controller' => 'rejet_historique', 'action' => 'affrej',$visionneuse['Visionneuse']['nomfic'] ),
									$permissions->check( 'Visionneuses', 'affrej' )
                                ):'0',	
				Set::classicExtract( $visionneuse, 'Visionneuse.nbinser' ),
				Set::classicExtract( $visionneuse, 'Visionneuse.nbmaj' ),					
				Set::classicExtract( $visionneuse, 'Visionneuse.perscree' ),
				Set::classicExtract( $visionneuse, 'Visionneuse.persmaj' ),					
				Set::classicExtract( $visionneuse, 'Visionneuse.dspcree' ),
				Set::classicExtract( $visionneuse, 'Visionneuse.dspmaj' ),
			);
		}
		
		$tbody = $html->tag( 'tbody', $html->tableCells( $rows, array( 'class' => 'odd' ), array( 'class' => 'even' ) ) );
		
		echo $pagination;
		echo $html->tag( 'table', $thead.$tbody );
		echo $pagination;
	
		$options = array(
		'INSTRUCTION' => 'INSTRUCTION', 
		'BENEFICIAIRE' => 'BENEFICIAIRE',
		'FINANCIER' => 'FINANCIER',
		);
		
		?>
		<fieldset>
        <legend>Recherche par fichier</legend>
		
<?php
		echo $default->search(
		array(			
			'Visionneuse.flux' => array( 'type' => 'select','options' => $options,'empty' => 'Choissisez votre flux'),
			'Visionneuse.dtdeb' => array( 'type' => 'date', 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) , 'maxYear' => date( 'Y' ) + 2 ),
			)
		);
		
	}
?>
</fieldset>