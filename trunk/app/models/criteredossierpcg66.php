<?php
	class Criteredossierpcg66 extends AppModel
	{
		public $name = 'Criteredossierpcg66';

		public $useTable = false;

		public $actsAs = array( 'Conditionnable' );

		/**
		*
		*/

		public function searchDossier( $params ) {
			$conditions = array();

			$conditions = $this->conditionsPersonneFoyerDossier( $conditions, $params );

			/// Critères
			$originepdo = Set::extract( $params, 'Dossierpcg66.originepdo_id' );
			$gestionnaire = Set::extract( $params, 'Dossierpcg66.user_id' );
			$etatdossierpcg = Set::extract( $params, 'Dossierpcg66.etatdossierpcg' );

			$datereceptionpdo = Set::extract( $params, 'Dossierpcg66.datereceptionpdo' );
			$datereceptionpdo_to = Set::extract( $params, 'Dossierpcg66.datereceptionpdo_to' );
			$datereceptionpdo_from = Set::extract( $params, 'Dossierpcg66.datereceptionpdo_from' );

			/// Critères sur les PDOs - date de reception de la PDO
			if( !empty( $datereceptionpdo ) ) {
				$datereceptionpdo_from = "{$datereceptionpdo_from['year']}-{$datereceptionpdo_from['month']}-{$datereceptionpdo_from['day']}";
				$datereceptionpdo_to = "{$datereceptionpdo_to['year']}-{$datereceptionpdo_to['month']}-{$datereceptionpdo_to['day']}";
				$conditions[] = "Dossierpcg66.datereceptionpdo BETWEEN '{$datereceptionpdo_from}' AND '{$datereceptionpdo_to}'";
			}

			// Décision de la PDO
			if( !empty( $decisionpdo ) ) {
				$conditions[] = 'Decisiondossierpcg66.decisionpdo_id = \''.Sanitize::clean( $decisionpdo ).'\'';
			}

			//Etat du dossier PCG - multi-choix
			if( isset( $params['Dossierpcg66']['etatdossierpcg'] ) && !empty( $params['Dossierpcg66']['etatdossierpcg'] ) ) {
				$conditions[] = '( Dossierpcg66.etatdossierpcg IN ( \''.implode( '\', \'', $etatdossierpcg ).'\' ) )';
			}
			
			
			// Origine de la PDO
			if( !empty( $originepdo ) ) {
				$conditions[] = 'Dossierpcg66.originepdo_id = \''.Sanitize::clean( $originepdo ).'\'';
			}
			// Gestionnaire de la PDO
			if( !empty( $gestionnaire ) ) {
				$conditions[] = 'Dossierpcg66.user_id = \''.Sanitize::clean( $gestionnaire ).'\'';
			}

			$query = array(
				'fields' => array(
					'DISTINCT Dossierpcg66.id',
					'Dossierpcg66.foyer_id',
					'Dossierpcg66.datereceptionpdo',
					'Dossierpcg66.typepdo_id',
					'Dossierpcg66.etatdossierpcg',
					'Decisiondossierpcg66.datetransmissionop',
					'Dossierpcg66.originepdo_id',
					'Dossierpcg66.user_id',
					'Dossier.id',
					'Dossier.numdemrsa',
					'Dossier.dtdemrsa',
					'Dossier.matricule',
					'Personne.id',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Personne.nir',
					'Personne.qual',
					'Personne.nomcomnai',
					'Adresse.locaadr',
					'Adresse.codepos',
					'Adresse.numcomptt',
					'Situationdossierrsa.etatdosrsa',
					'Dossierpcg66.nbpropositions'
				),
				'recursive' => -1,
				'joins' => array(
					array(
						'table'      => 'foyers',
						'alias'      => 'Foyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Dossierpcg66.foyer_id = Foyer.id' )
					),
					array(
						'table'      => 'personnespcgs66',
						'alias'      => 'Personnepcg66',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Dossierpcg66.id = Personnepcg66.dossierpcg66_id' ),
					),
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.foyer_id = Foyer.id',
							'Personne.id IN (
								'.ClassRegistry::init( 'Personne' )->sqResponsableDossierUnique('Foyer.id').'
							)'
						)
					),
					array(
						'table'      => 'adressesfoyers',
						'alias'      => 'Adressefoyer',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Foyer.id = Adressefoyer.foyer_id',
							'Adressefoyer.id IN (
								'.ClassRegistry::init( 'Adressefoyer' )->sqDerniereRgadr01('Adressefoyer.foyer_id').'
							)'
						)
					),
					array(
						'table'      => 'adresses',
						'alias'      => 'Adresse',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
					),
					array(
						'table'      => 'dossiers',
						'alias'      => 'Dossier',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Foyer.dossier_id = Dossier.id' )
					),
					array(
						'table'      => 'situationsdossiersrsa',
						'alias'      => 'Situationdossierrsa',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Situationdossierrsa.dossier_id = Dossier.id' )
					),
					array(
						'table'      => 'decisionsdossierspcgs66',
						'alias'      => 'Decisiondossierpcg66',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Decisiondossierpcg66.dossierpcg66_id = Dossierpcg66.id',
							'Decisiondossierpcg66.id IN (
								'.ClassRegistry::init( 'Decisiondossierpcg66' )->sqDatetransmissionOp( 'Dossierpcg66.id' ).'
							)'
						)
					)
				),
				'limit' => 10,
				'conditions' => $conditions
			);

			return $query;
		}


		
		/**
		*
		*/

		public function searchGestionnaire( $params ) {
			$conditions = array();
			$Dossierpcg66 = ClassRegistry::init( 'Dossierpcg66' );
			$gestionnaire = Set::extract( $params, 'Search.Dossierpcg66.user_id' );
			
			// Gestionnaire de la PDO
			if( !empty( $gestionnaire ) ) {
				$conditions[] = 'Dossierpcg66.user_id = \''.Sanitize::clean( $gestionnaire ).'\'';
			}
			
			// Conditions de base pour qu'un allocataire puisse passer en EP
			$conditions['Dossierpcg66.etatdossierpcg'] = array( 'attinstr', 'instrencours', 'dossiertraite', 'decisionvalid', 'decisionnonvalid', 'decisionnonvalidretouravis', 'decisionvalidretouravis', 'attpj', 'transmisop', 'atttransmisop' );
// 			$conditions['Prestation.rolepers'] = array( 'DEM', 'CJT' );
			$conditions[] = array(
				'OR' => array(
					'Adressefoyer.id IS NULL',
					'Adressefoyer.id IN ( '.$Dossierpcg66->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
				)
			);
			$conditions[] = 'Personne.id IN ( '.$Dossierpcg66->Foyer->Personne->sqResponsableDossierUnique('Foyer.id').' )';
			
			$query = array(
				'fields' => array(
					'DISTINCT Dossierpcg66.id',
					'Dossierpcg66.foyer_id',
					'Dossierpcg66.datereceptionpdo',
					'Dossierpcg66.typepdo_id',
					'Dossierpcg66.etatdossierpcg',
					'Dossierpcg66.originepdo_id',
					'Dossierpcg66.user_id',
					'Dossier.id',
					'Dossier.numdemrsa',
					'Dossier.dtdemrsa',
					'Dossier.matricule',
					'Personne.id',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Personne.nir',
					'Personne.qual',
					'Personne.nomcomnai',
					'Adresse.locaadr',
					'Adresse.codepos',
					'Adresse.numcomptt',
					'Situationdossierrsa.etatdosrsa',
					'Dossierpcg66.nbpropositions',
// 					'Personnepcg66.id',
					'Personnepcg66.nbtraitements'
				),
				'recursive' => -1,
				'joins' => array(
					$Dossierpcg66->join( 'Foyer', array( 'type' => 'INNER' ) ),
					$Dossierpcg66->join( 'Personnepcg66', array( 'type' => 'LEFT OUTER' ) ),
					$Dossierpcg66->Personnepcg66->join( 'Traitementpcg66', array( 'type' => 'LEFT OUTER' ) ),
// 					$Dossierpcg66->Personnepcg66->Traitementpcg66->join( 'Descriptionpdo', array( 'type' => 'INNER' ) ),
					$Dossierpcg66->Foyer->join( 'Personne', array( 'type' => 'INNER' ) ),
// 					$Dossierpcg66->Foyer->Personne->join( 'Prestation', array( 'type' => 'INNER' ) ),
					$Dossierpcg66->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
					$Dossierpcg66->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
					$Dossierpcg66->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
					$Dossierpcg66->Foyer->Dossier->join( 'Situationdossierrsa', array( 'type' => 'INNER' ) )
				),
				'limit' => 10,
				'contain' => false,
				'conditions' => $conditions
			);

			return $query;
		}

	}
?>