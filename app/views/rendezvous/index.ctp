<?php
	$this->pageTitle = 'Rendez-vous de la personne';
	echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id) );
?>

<div class="with_treemenu">
	<h1>Rendez-vous</h1>
	
	<?php
		if( isset( $dossierep ) && !empty( $dossierep ) ) {
			echo '<p class="error">Ce dossier est en cours de passage en EP : '.$dossierep['Sanctionrendezvousep58']['Rendezvous']['Typerdv']['motifpassageep'].'.</p>';
		}
		if ( !isset( $dossierepLie ) ) {
			$dossierepLie = 0;
		}

		echo $default2->index(
			$rdvs,
			array(
				'Personne.nom_complet' => array( 'type' => 'string' ),
				'Structurereferente.lib_struc',
				'Referent.nom_complet' => array( 'type' => 'string' ),
				'Permanence.libpermanence',
				'Typerdv.libelle',
				'Statutrdv.libelle',
				'Rendezvous.daterdv',
				'Rendezvous.heurerdv',
				'Rendezvous.objetrdv',
				'Rendezvous.commentairerdv'
			),
			array(
				'actions' => array(
					'Rendezvous::view' => array( 'disabled' => '( "'.$permissions->check( 'rendezvous', 'view' ).'" != "1" ) ' ),
					'Rendezvous::edit' => array( 'disabled' => '( "'.$permissions->check( 'rendezvous', 'edit' ).'" != "1" ) || ( "#Rendezvous.id#" != '.$lastrdv_id.' ) || ( "'.$dossierepLie.'" == "1" )' ),
					'Rendezvous::print' => array( 'label' => 'Imprimer', 'url' => array( 'action' => 'gedooo' ), 'disabled' =>  '( "'.$permissions->check( 'rendezvous', 'print' ).'" != "1" ) '  ),
					'Rendezvous::delete' => array( 'disabled' => '( "'.$permissions->check( 'rendezvous', 'delete' ).'" != "1" ) || ( "#Rendezvous.id#" != '.$lastrdv_id.' ) || ( "'.$dossierepLie.'" == "1" )' ),
					'Rendezvous::filelink' => array( 'disabled' => '( "'.$permissions->check( 'rendezvous', 'filelink' ).'" != "1" ) ' )
				),
				'add' => array( 'Rendezvous.add' => array( 'controller'=>'rendezvous', 'action'=>'add', $personne_id ) )
			)
		);
	?>
</div>
<div class="clearer"><hr /></div>