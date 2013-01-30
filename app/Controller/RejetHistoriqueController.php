<?php
	// Fait par le CG93
	// Auteur : Harry ZARKA <hzarka@cg93.fr>, 2010.

	class RejetHistoriqueController extends AppController
	{
		public $name = 'RejetHistorique';

		public $scaffold;

		public function affrej( $fichier = null ) {
			$rejetHistorique = $this->RejetHistorique->findByFic( $fichier );
			$this->paginate = array( 'conditions' => array( 'RejetHistorique.fic' => $fichier ),
				'fields' => array( 'RejetHistorique.numdemrsa', 'RejetHistorique.matricule', 'RejetHistorique.log' ) );
			$this->set( 'rejetHistoriques', $this->paginate() );
			$this->set( 'fichier', $fichier );
		}

		public function affxml( $fichier = null, $nrsa = null ) {
			$rejet = $this->RejetHistorique->findByNumdemrsa( $nrsa );

			$xml = ($rejet['RejetHistorique']['balisededonnee']);

			$this->set( 'rejet', $xml );
			$this->set( 'nrsa', $nrsa );
		}
	}
?>