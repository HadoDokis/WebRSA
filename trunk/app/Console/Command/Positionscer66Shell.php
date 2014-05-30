<?php
	/**
	 * Fichier source de la classe Positionscer66Shell.
	 *
	 * PHP 5.3
	 *
	 * @package app.Console.Command
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'XShell', 'Console/Command' );
	App::uses( 'Contratinsertion', 'Model' );

	/**
	 * La classe Positionscer66Shell effectue la mise à jour de la position des CER qui en ont besoin :
	 * 	- les CER sont positionnés « En cours:Bilan à réaliser » lorsque la date de fin
	 * 	  du CER n'est pas encore dépassée, mais que celle-ci est plus petite que l'intervalle
	 * 	  spécifié par la configuration  Contratinsertion.Cg66.updateEncoursbilan.
	 * 	- les CER sont positionnés « Périmé » lorsque la date de fin du CER est dépassée, et
	 * 	  qu'il n'existe pas de bilan de parcours non annulé concernant ce CER.
	 * 	- les CER sont positionnés « XXXX » lorsque l'allocataire auxquel ils
	 * 	  se trouvent dans un dossier dont les droits sont clos et que la position
	 * 	  du CER n'est ni "Annulé", ni "Fin de contrat", ni "Périmé".
	 *
	 * Voir le document app/docs/Documentation administrateurs.odt, partie
	 * "Intervalles PostgreSQL"
	 *
	 * @package app.Console.Command
	 */
	class Positionscer66Shell extends XShell
	{

		/**
		 *
		 * @var type
		 */
		public $uses = array( 'Contratinsertion' );

		/**
		 *
		 * @var type
		 */
		public $Contratinsertion;

		/**
		 *
		 */
		public function startup() {
			parent::startup();
			$this->checkDepartement( 66 );

			$result = $this->Contratinsertion->checkConfigUpdateEncoursbilanCg66();
			if( $result !== true ) {
				$this->err( "Mauvaise configuration de Contratinsertion.Cg66.updateEncoursbilan dans le fichier webrsa.inc\n{$check}" );
				$this->_stop( 0 );
			}
		}

		/**
		 *
		 */
		protected function _update( $fields, $conditions ) {
			$sample = $this->Contratinsertion->find( 'first', array( 'conditions' => $conditions, 'contain' => false ) );
			return (
					empty( $sample )
					|| $this->Contratinsertion->updateAllUnBound(
							$fields, $conditions
					)
					);
		}

		/**
		 * Le CER est périmé, sa date de fin est inférieure à la date du jour
         * et un bilan de parcours est lié à ce CER
		 */
		protected function _updatePerime() {
			$this->_wait( 'Mise à jour de la position "'.__d( 'contratinsertion', "ENUM::POSITIONCER::perime" ).'" pour les contrats d\'engagement réciproque le nécessitant.' );

			$conditions = array(
				'Contratinsertion.df_ci < NOW()',
				'OR' => array(
// 					array(
					'Contratinsertion.positioncer IS NULL',
// 						'Contratinsertion.decision_ci' => 'V'
// 					),
					'Contratinsertion.positioncer' => array( 'encours', 'encoursbilan' ),
                    'Contratinsertion.positioncer <>' => 'perimebilanarealiser'
				),
				'Contratinsertion.id IN (
					'.$this->Contratinsertion->Bilanparcours66->sq(
						array(
							'fields' => array( 'bilansparcours66.contratinsertion_id' ),
							'alias' => 'bilansparcours66',
							'conditions' => array(
								'bilansparcours66.contratinsertion_id = Contratinsertion.id',
								'bilansparcours66.positionbilan <>' => 'annule'
							)
						)
				).'
				)'
			);

			return $this->_update( array( 'Contratinsertion.positioncer' => "'perime'" ), $conditions );
		}

        /**
		 * Le CER est périmé, sa date de fin est inférieure à la date du jour
         * et aucun bilan de parcours n'est lié à ce CER
		 */
		protected function _updatePerimeBilanARealiser() {
			$this->_wait( 'Mise à jour de la position "'.__d( 'contratinsertion', "ENUM::POSITIONCER::perimebilanarealiser" ).'" pour les contrats d\'engagement réciproque le nécessitant.' );

			$conditions = array(
				'Contratinsertion.df_ci < NOW()',
				'OR' => array(
// 					array(
					'Contratinsertion.positioncer IS NULL',
// 						'Contratinsertion.decision_ci' => 'V'
// 					),
					'Contratinsertion.positioncer' => array( 'encours', 'encoursbilan' ),
                    'Contratinsertion.positioncer <>' => 'perime'
				),
				'Contratinsertion.id NOT IN (
					'.$this->Contratinsertion->Bilanparcours66->sq(
						array(
							'fields' => array( 'bilansparcours66.contratinsertion_id' ),
							'alias' => 'bilansparcours66',
							'conditions' => array(
								'bilansparcours66.contratinsertion_id = Contratinsertion.id',
								'bilansparcours66.positionbilan <>' => 'annule'
							)
						)
				).'
				)'
			);

			return $this->_update( array( 'Contratinsertion.positioncer' => "'perimebilanarealiser'" ), $conditions );
		}

		/**
		 *
		 */
		protected function _updateEncoursbilan() {
			$this->_wait( 'Mise à jour de la position "'.__d( 'contratinsertion', "ENUM::POSITIONCER::encoursbilan" ).'" pour les contrats d\'engagement réciproque le nécessitant.' );

			$conditions = array(
				'Contratinsertion.df_ci >= NOW()',
				'Contratinsertion.df_ci <= ( NOW() + interval \''.Configure::read( 'Contratinsertion.Cg66.updateEncoursbilan' ).'\' )',
				'OR' => array(
					array(
						'Contratinsertion.positioncer IS NULL',
						'Contratinsertion.decision_ci' => 'V'
					),
					'Contratinsertion.positioncer' => 'encours'
				)
			);

			return $this->_update( array( 'Contratinsertion.positioncer' => "'encoursbilan'" ), $conditions );
		}

		/**
		 *
		 */
		protected function _updateRadiationsCaf() {
			$this->_wait( 'Mise à jour de la position "'.__d( 'contratinsertion', "ENUM::POSITIONCER::fincontrat" ).'" pour les contrats d\'engagement réciproque le nécessitant.' );

			$conditions = array(
				'Contratinsertion.personne_id IN (
					'.$this->Contratinsertion->Personne->sq(
						array(
							'fields' => array( 'personnes.id' ),
							'alias' => 'personnes',
							'conditions' => array(
								'personnes.id = Contratinsertion.personne_id',
								'situationsdossiersrsa.etatdosrsa' => array( '5', '6' )
							),
							'joins' => array(
								array(
									'table' => 'foyers',
									'alias' => 'foyers',
									'type' => 'INNER',
									'foreignKey' => false,
									'conditions' => array( 'foyers.id = personnes.foyer_id' )
								),
								array(
									'table' => 'dossiers',
									'alias' => 'dossiers',
									'type' => 'INNER',
									'foreignKey' => false,
									'conditions' => array( 'dossiers.id = foyers.dossier_id' )
								),
								array(
									'table' => 'situationsdossiersrsa',
									'alias' => 'situationsdossiersrsa',
									'type' => 'INNER',
									'foreignKey' => false,
									'conditions' => array( 'dossiers.id = situationsdossiersrsa.dossier_id' )
								),
							)
						)
				).'
				)',
				'OR' => array(
					'Contratinsertion.positioncer IS NULL',
					'Contratinsertion.positioncer' => array( 'encours', 'attvalid', 'encoursbilan', 'attrenouv' )
				)
			);

			return $this->_update( array( 'Contratinsertion.positioncer' => "'fincontrat'" ), $conditions );
		}

		/**
		 *
		 */
		protected function _updateRangsContrats() {
			$this->_wait( 'Mise à jour des rangs des contrats d\'engagement réciproque.' );

			return $this->Contratinsertion->updateRangsContrats();
		}

		/**
		 *
		 */
		protected function _updateAttvalid() {
			$this->_wait( 'Mise à jour de la position "'.__d( 'contratinsertion', "ENUM::POSITIONCER::attvalid" ).'" pour les contrats d\'engagement réciproque le nécessitant.' );

			$conditions = array(
				'Contratinsertion.df_ci >= NOW()',
// 				'Contratinsertion.forme_ci' => 'C',
				'Contratinsertion.decision_ci' => 'E'
			);

			return $this->_update( array( 'Contratinsertion.positioncer' => "'attvalid'" ), $conditions );
		}

		/**
		 *
		 */
		protected function _updateEncours() {
			$this->_wait( 'Mise à jour de la position "'.__d( 'contratinsertion', "ENUM::POSITIONCER::encours" ).'" pour les contrats d\'engagement réciproque le nécessitant.' );

			// Combien de personnes différentes ont un CER actif, dont la positioncer est NULL et validé ?
			$count = $this->Contratinsertion->find(
					'first', array(
				'fields' => array(
					'COUNT ( DISTINCT ( "Contratinsertion"."personne_id" ) ) AS "count"'
				),
				'conditions' => array(
					'Contratinsertion.df_ci >= NOW()',
					'Contratinsertion.positioncer IS NULL',
					'Contratinsertion.decision_ci' => 'V'
				),
				'contain' => false
					)
			);

			// Recherche des CER dont la date de fin n'est pas encore passée, dont la positioncer est NULL, validé et qui se trouvent dans la liste des CER classés par date de création inverse puis par personne_id en limitant au résultat obtenu précédemment
			$conditions = array(
				'Contratinsertion.df_ci >= NOW()',
				'Contratinsertion.positioncer IS NULL',
				'Contratinsertion.decision_ci' => 'V',
				'Contratinsertion.id IN ( '.$this->Contratinsertion->sq(
						array(
							'alias' => 'contratsinsertions',
							'fields' => array(
								'contratsinsertions.id'
							),
							'order' => array(
								'contratsinsertions.created DESC',
								'contratsinsertions.personne_id ASC'
							),
							'limit' => $count[0]['count']
						)
				).' )'
			);

			return $this->_update( array( 'Contratinsertion.positioncer' => "'encours'" ), $conditions );
		}

		/**
		 * Mise à jour des valeurs (dans cet ordre-là):
		 * 	"fincontrat" (radiations CAF)
		 * 	"perime"
		 * 	"encoursbilan"
		 * 	"encours"
		 * Résultats ( cg66_20110706_eps ):
		 * 	Original:
		 * 		encours			1259
		 * 		attvalid		40
		 * 		annule			19
		 * 		NULL			6259
		 * 	Final:
		 * 		encours			3103
		 * 		attvalid		28
		 * 		annule			19
		 * 		fincontrat		524
		 * 		encoursbilan	850
		 * 		perime			3032
		 * 		NULL			21
		 */
		public function main() {
			$success = true;
			$this->Contratinsertion->begin();

			if( count( $this->args ) != 0 ) {
				if( in_array( 'attvalid', $this->args ) || in_array( 'all', $this->args ) ) {
					$success = $this->_updateAttvalid() && $success;
				}
				if( in_array( 'fincontrat', $this->args ) || in_array( 'all', $this->args ) ) {
					$success = $this->_updateRadiationsCaf() && $success;
				}
                if( in_array( 'perimebilanarealiser', $this->args ) || in_array( 'all', $this->args ) ) {
					$success = $this->_updatePerimeBilanARealiser() && $success;
				}
				if( in_array( 'perime', $this->args ) || in_array( 'all', $this->args ) ) {
					$success = $this->_updatePerime() && $success;
				}
				if( in_array( 'encoursbilan', $this->args ) || in_array( 'all', $this->args ) ) {
					$success = $this->_updateEncoursbilan() && $success;
				}
				if( in_array( 'encours', $this->args ) || in_array( 'all', $this->args ) ) {
					$success = $this->_updateEncours() && $success;
				}
				if( in_array( 'majrangs', $this->args ) || in_array( 'all', $this->args ) ) {
					$success = $this->_updateRangsContrats() && $success;
				}
			}
			else {
				$this->out( $this->OptionParser->help() );
				$this->_stop( 0 );
			}


			if( $success ) {
				$this->Contratinsertion->commit();
				$msg = "<success>La mise à jour des positions du CER a été effectuée avec succès.</success>";
			}
			else {
				$this->Contratinsertion->rollback();
				$msg = "<error>Erreur lors de la mise à jour des positions du CER.</error>";
			}
			$this->out( $msg );
		}

		/**
		 *
		 * @return type
		 */
		public function getOptionParser() {
			$parser = parent::getOptionParser();
			$parser->description( 'Effectue la mise à jour de la position des CER qui en ont besoin ' );
			$arguments = array(
				'all' => array(
					'help' => 'Effectue toutes les actions'
				),
				'fincontrat' => array(
					'help' => 'Met à jour les CER dont la position devrait être \'Fin de contrat\''
				),
                'perimebilanarealiser' => array(
					'help' => 'Met à jour les CER dont la position devrait être \'Contrat Périmé, Bilan à réaliser\''
				),
				'perime' => array(
					'help' => 'Met à jour les CER dont la position devrait être \'Contrat Périmé\''
				),
				'encoursbilan' => array(
					'help' => 'Met à jour les CER dont la position devrait être \'En cours: Bilan à réaliser\''
				),
				'attvalid' => array(
					'help' => 'Met à jour les CER dont la position devrait être \'En attente de validation\''
				),
				'encours' => array(
					'help' => 'Met à jour les CER dont la position devrait être \'En cours\''
				),
				'majrangs' => array(
					'help' => 'Met à jour les rangs des CERs'
				)
			);
			$parser->addArguments( $arguments );
			return $parser;
		}

	}
?>