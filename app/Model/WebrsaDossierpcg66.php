<?php
	/**
	 * Code source de la classe WebrsaDossierpcg66.
	 *
	 * @package app.Model
	 * @license Expression license is undefined on line 11, column 23 in Templates/CakePHP/CakePHP Model.php.
	 */
	App::uses( 'WebrsaAbstractLogic', 'Model' );

	/**
	 * La classe WebrsaDossierpcg66 ...
	 *
	 * @package app.Model
	 */
	class WebrsaDossierpcg66 extends WebrsaAbstractLogic
	{
		/**
		 * Nom du modèle.
		 *
		 * @var string
		 */
		public $name = 'WebrsaDossierpcg66';

		/**
		 * Les modèles qui seront utilisés par ce modèle.
		 *
		 * @var array
		 */
		public $uses = array( 'Dossierpcg66' );
		
		
		/**
		 * Permet d'obtenir un data pour affichage d'un index de dossierpcg
		 * 
		 * @param integer $foyer_id
		 * @return array
		 */
		public function getIndexData( $foyer_id ) {
			$query = $this->queryIndexByConditions( array( 'Dossierpcg66.foyer_id' => $foyer_id ) );
			$results = $this->Dossierpcg66->Foyer->find('all', $query);

			foreach ( $results as $key => $result ) {
				$results[$key]['Dossierpcg66']['etatdossierpcg_full'] = __d(
					'dossierpcg66', 
					'ENUM::ETATDOSSIERPCG::'.Hash::get($result, 'Dossierpcg66.etatdossierpcg')
				);

				if ( Hash::get($result, 'Decisiondossierpcg66.orgtransmis_list_name') ) {
					$results[$key]['Dossierpcg66']['etatdossierpcg_full'] .= ' à '
						. Hash::get($result, 'Decisiondossierpcg66.orgtransmis_list_name')
					;
				}

				if ( Hash::get($result, 'Decisiondossierpcg66.datetransmissionop') ) {
					$results[$key]['Dossierpcg66']['etatdossierpcg_full'] .= ' le '
						. date_format(date_create(Hash::get($result, 'Decisiondossierpcg66.datetransmissionop')), 'd/m/Y')
					;
				}

				if ( Hash::get($result, 'Personnepcg66.situationpdo_list_libelle') ) {
					$results[$key]['Personnepcg66']['situationpdo_list_libelle_ulli'] = 
						'<ul><li>'
						. implode( '</li><li>', explode( '__', Hash::get($result, 'Personnepcg66.situationpdo_list_libelle') ) )
						. '</li></ul>'
					;
				}

				if ( Hash::get($result, 'Bilanparcours66.personne_nom_complet') ) {
					$results[$key]['Dossierpcg66']['bilan_de'] = 'Bilan de parcours de&nbsp;: '
						. Hash::get($result, 'Bilanparcours66.personne_nom_complet')
					;
				}
			}

			return $results;
		}

		/**
		 * Permet d'obtenir un query d'index des dossiers PCGs
		 * 
		 * @param mixed $conditions
		 * @return array
		 */
		public function queryIndexByConditions( $conditions = array() ) {
			$sqLastDecision = $this->Dossierpcg66->Decisiondossierpcg66->sq(
				array(
					'alias' => 'decisionsdossierspcgs66',
					'fields' => 'decisionsdossierspcgs66.id',
					'conditions' => array(
						'decisionsdossierspcgs66.dossierpcg66_id = Dossierpcg66.id'
					),
					'contain' => false,
					'order' => array(
						'decisionsdossierspcgs66.created' => 'DESC'
					),
					'limit' => 1
				)
			);

			$joinDecision = $this->Dossierpcg66->join('Decisiondossierpcg66');
			$joinDecision['conditions'] = "Decisiondossierpcg66.id IN ( {$sqLastDecision} )";

			$sqTransmisOp = $this->Dossierpcg66->Decisiondossierpcg66->Decdospcg66Orgdospcg66->sq(
				array(
					'fields' => 'Orgtransmisdossierpcg66.name',
					'joins' => array(
						$this->Dossierpcg66->Decisiondossierpcg66->Decdospcg66Orgdospcg66->join('Orgtransmisdossierpcg66', array('type' => 'INNER'))
					),
					'conditions' => array(
						'Decdospcg66Orgdospcg66.decisiondossierpcg66_id = Decisiondossierpcg66.id',
					),
					'order' => 'Orgtransmisdossierpcg66.name'
				)
			);

			$sqMotifPersonne = $this->Dossierpcg66->Personnepcg66->Personnepcg66Situationpdo->sq(
				array(
					'fields' => "Situationpdo.libelle",
					'joins' => array(
						$this->Dossierpcg66->Personnepcg66->Personnepcg66Situationpdo->join('Situationpdo', array('type' => 'INNER'))
					),
					'conditions' => array(
						'Personnepcg66Situationpdo.personnepcg66_id = Personnepcg66.id'
					)
				)
			);

			$sqBilanPersonne = $this->Dossierpcg66->Bilanparcours66->Personne->sq(
				array(
					'alias' => 'personnes',
					'fields' => array(
						"personnes.qual || ' ' || personnes.nom || ' ' || personnes.prenom AS \"personnes__nom_complet\""
					),
					'joins' => array(
						array(
							'alias' => 'Bilanparcours66',
							'table' => 'bilansparcours66',
							'type' => 'INNER',
							'conditions' => array(
								'Bilanparcours66.personne_id = personnes.id',
								'Bilanparcours66.id = Dossierpcg66.bilanparcours66_id'
							)
						)
					),
					'limit' => 1
				)
			);

			$query = array(
				'fields' => array(
					'Dossierpcg66.id',
					'Typepdo.libelle',
					'Dossierpcg66.datereceptionpdo',
					'Dossierpcg66.etatdossierpcg',
					'Decisiondossierpcg66.datetransmissionop',
					'Decisionpdo.libelle',
					'Poledossierpcg66.name',
					'("Poledossierpcg66"."name" || \' / \' || "User"."nom" || \' \' || "User"."prenom") AS "Pole__user"',
					"( {$sqBilanPersonne} ) AS \"Bilanparcours66__personne_nom_complet\"",
					"( ARRAY_TO_STRING(ARRAY(({$sqTransmisOp})), ', ') ) AS \"Decisiondossierpcg66__orgtransmis_list_name\"",
					"( ARRAY_TO_STRING(ARRAY(({$sqMotifPersonne})), '__') ) AS \"Personnepcg66__situationpdo_list_libelle\"",
				),
				'joins' => array(
					$this->Dossierpcg66->Foyer->join('Dossierpcg66', array('type' => 'INNER')),
					$this->Dossierpcg66->Foyer->join('Personne', array('type' => 'INNER')),
					$this->Dossierpcg66->Foyer->Personne->join('Prestation', 
						array(
							'type' => 'INNER', 
							'conditions' => array(
								'Prestation.rolepers' => 'DEM'
							)
						)
					),
					$this->Dossierpcg66->join('Personnepcg66', 
						array(
							'conditions' => array(
								'Personnepcg66.personne_id = Personne.id'
							)
						)
					),
					$this->Dossierpcg66->join('Typepdo'),
					$this->Dossierpcg66->join('User'),
					$this->Dossierpcg66->User->join('Poledossierpcg66'),
					$joinDecision,
					$this->Dossierpcg66->Decisiondossierpcg66->join('Decisionpdo'),
				),
				'conditions' => $conditions,
				'contain' => false,
				'order' => array(
					'Decisiondossierpcg66.datetransmissionop IS NULL' => 'DESC',
					'Decisiondossierpcg66.datetransmissionop' => 'DESC',
					'Decisiondossierpcg66.datevalidation IS NULL' => 'DESC',
					'Decisiondossierpcg66.datevalidation' => 'DESC',
					'Dossierpcg66.datereceptionpdo' => 'DESC',
					'Dossierpcg66.id' => 'DESC',
				)
			);

			return $query;
		}

		/**
		 * Permet d'obtenir les informations sur le demandeur du RSA pour affichage dans le dossier pcg
		 * 
		 * @param integer $foyer_id
		 * @return array
		 */
		public function findPersonneDem( $foyer_id ) {
			return $this->Dossierpcg66->Foyer->Personne->find(
				'first',
				array(
					'fields' => array(
						'Personne.id',
						'Prestation.rolepers',
						$this->Dossierpcg66->Foyer->Personne->sqVirtualField('nom_complet')
					),
					'conditions' => array(
						'Personne.foyer_id' => $foyer_id,
						'Prestation.rolepers' => array( 'DEM' )
					),
					'joins' => array(
						$this->Dossierpcg66->Foyer->Personne->join( 'Prestation' )
					),
					'contain' => false
				)
			);
		}

		/**
		 * Permet d'obtenir les informations nécéssaire à l'edition d'un dossier pcg
		 * 
		 * @param integer $id
		 * @return array
		 */
		public function findDossierpcg( $id ) {
			return $this->Dossierpcg66->find(
				'first',
				array(
					'conditions' => array(
						'Dossierpcg66.id' => $id
					),
					'contain' => array(
						'Personnepcg66' => array(
							'Personne',
							'Statutpdo',
							'Situationpdo'
						),
						'Decisiondefautinsertionep66' => array(
							'Passagecommissionep'
						),
						'Decisiondossierpcg66' => array(
                            'order' => array( 'Decisiondossierpcg66.created DESC' ),
                            'Notificationdecisiondossierpcg66',
							'Useravistechnique' => array(
								'fields' => 'nom_complet'
							),
							'Userproposition' => array(
								'fields' => 'nom_complet'
							),
						),
						'Fichiermodule',
						'Typepdo',
						'Originepdo',
						'Serviceinstructeur',
						'User',
						'Poledossierpcg66',
					)
				)
			);
		}

		/**
		 * Permet d'obtenir la liste des personnes liés à un dossier pcg
		 * 
		 * @param integer $dossierpcg66_id
		 * @return array
		 */
		public function findPersonnepcg( $dossierpcg66_id ) {
			return $this->Dossierpcg66->Personnepcg66->find(
				'all',
				array(
					'conditions' => array(
						'Personnepcg66.dossierpcg66_id' => $dossierpcg66_id
					),
					'contain' => array(
						'Statutpdo',
						'Situationpdo',
						'Personne',
						'Traitementpcg66'
					)
				)
			);
		}

		/**
		 * Permet d'obtenir la liste des propositions d'un dossier pcg
		 * 
		 * @param integer $dossierpcg66_id
		 * @return array
		 */
		public function findDecisiondossierpcg( $dossierpcg66_id ) {
			//Gestion des décisions pour le dossier au niveau foyer
			$joins = array(
				array(
					'table'      => 'pdfs',
					'alias'      => 'Pdf',
					'type'       => 'LEFT OUTER',
					'foreignKey' => false,
					'conditions' => array(
						'Pdf.modele' => 'Decisiondossierpcg66',
						'Pdf.fk_value = Decisiondossierpcg66.id'
					)
				),
				array(
					'table'      => 'decisionspdos',
					'alias'      => 'Decisionpdo',
					'type'       => 'LEFT OUTER',
					'foreignKey' => false,
					'conditions' => array(
						'Decisionpdo.id = Decisiondossierpcg66.decisionpdo_id'
					)
				),
			);

			return $this->Dossierpcg66->Decisiondossierpcg66->find(
				'all',
				array(
					'fields' => array(
						'Decisiondossierpcg66.id',
						'Decisiondossierpcg66.dossierpcg66_id',
						'Decisiondossierpcg66.decisionpdo_id',
						'Decisiondossierpcg66.datepropositiontechnicien',
						'Decisiondossierpcg66.commentairetechnicien',
						'Decisiondossierpcg66.commentaire',
						'Decisiondossierpcg66.avistechnique',
						'Decisiondossierpcg66.commentaireavistechnique',
						'Decisiondossierpcg66.dateavistechnique',
						'Decisiondossierpcg66.etatdossierpcg',
						'Decisiondossierpcg66.validationproposition',
						'Decisiondossierpcg66.motifannulation',
						'Decisiondossierpcg66.commentairevalidation',
						'Decisiondossierpcg66.datevalidation',
						'Decisionpdo.libelle',
						'Pdf.fk_value',
						$this->Dossierpcg66->Decisiondossierpcg66->Fichiermodule->sqNbFichiersLies( 
							$this->Dossierpcg66->Decisiondossierpcg66, 
							'nb_fichiers_lies' 
						)
					),
					'conditions' => array(
						'dossierpcg66_id' => $dossierpcg66_id
					),
					'joins' => $joins,
					'order' => array(
						'Decisiondossierpcg66.modified DESC'
					),
					'recursive' => -1
				)
			);
		}

		/**
		 * Permet d'obtenir la liste des fichiers liés à un dossier pcg
		 * 
		 * @param integer $dossierpcg66_id
		 * @return array
		 */
		public function findFichiers( $dossierpcg66_id ) {
			return $this->Dossierpcg66->Fichiermodule->find(
				'all',
				array(
					'fields' => array(
						'Fichiermodule.id',
						'Fichiermodule.name',
						'Fichiermodule.fk_value',
						'Fichiermodule.modele',
						'Fichiermodule.cmspath',
						'Fichiermodule.mime',
						'Fichiermodule.created',
						'Fichiermodule.modified',
					),
					'conditions' => array(
						'Fichiermodule.modele' => 'Dossierpcg66',
						'Fichiermodule.fk_value' => $dossierpcg66_id,
					),
					'contain' => false
				)
			);
		}
	}
?>