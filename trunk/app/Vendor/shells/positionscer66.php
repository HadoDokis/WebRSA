<?php
	/**
	* Effectue la mise à jour de la position des CER qui en ont besoin :
	*	- les CER sont positionnés « En cours:Bilan à réaliser » lorsque la date de fin
	*	  du CER n'est pas encore dépassée, mais que celle-ci est plus petite que l'intervalle
	*	  spécifié par la configuration  Contratinsertion.Cg66.updateEncoursbilan.
	*	- les CER sont positionnés « Périmé » lorsque la date de fin du CER est dépassée, et
	*	  qu'il n'existe pas de bilan de parcours non annulé concernant ce CER.
	*	- les CER sont positionnés « XXXX » lorsque l'allocataire auxquel ils
	*	  se trouvent dans un dossier dont les droits sont clos et que la position
	*	  du CER n'est ni "Annulé", ni "Fin de contrat", ni "Périmé".
	*
	* Voir le document app/docs/Documentation administrateurs.odt, partie
	* "Intervalles PostgreSQL"
	*/

	class Positionscer66Shell extends Shell
	{
		public $uses = array( 'Contratinsertion' );

		/**
		* Vérification du CG
		*/

		public function initialize() {
			parent::initialize();

			if( Configure::read( 'Cg.departement' ) != 66 ) {
				$this->err( 'Ce shell n\'est utile qu\'au CG 66.' );
				$this->_stop( 1 );
			}

			$result = $this->Contratinsertion->checkConfigUpdateEncoursbilanCg66();
			if( $result !== true ) {
				$this->err( "Mauvaise configuration de Contratinsertion.Cg66.updateEncoursbilan dans le fichier webrsa.inc\n{$check}" );
				$this->_stop( 1 );
			}
		}

		/**
		*
		*/

		protected function _update( $fields, $conditions ) {
			$sample = $this->Contratinsertion->find( 'first', array( 'conditions' => $conditions, 'contain' => false ) );
			return (
				empty( $sample )
				|| $this->Contratinsertion->updateAll(
					$fields,
					$conditions
				)
			);
		}

		/**
		*
		*/

		protected function _updatePerime() {
			$this->out( 'Mise à jour de la position "'.__d( 'contratinsertion', "ENUM::POSITIONCER::perime", true ).'" pour les contrats d\'insertion le nécessitant.' );

			$conditions = array(
				'Contratinsertion.df_ci < NOW()',
				'OR' => array(
// 					array(
						'Contratinsertion.positioncer IS NULL',
// 						'Contratinsertion.decision_ci' => 'V'
// 					),
					'Contratinsertion.positioncer' => array( 'encours', 'encoursbilan' )
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

			return $this->_update( array( 'Contratinsertion.positioncer' => "'perime'" ), $conditions );
		}

		/**
		*
		*/

		protected function _updateEncoursbilan() {
			$this->out( 'Mise à jour de la position "'.__d( 'contratinsertion', "ENUM::POSITIONCER::encoursbilan", true ).'" pour les contrats d\'insertion le nécessitant.' );

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
			$this->out( 'Mise à jour de la position "'.__d( 'contratinsertion', "ENUM::POSITIONCER::fincontrat", true ).'" pour les contrats d\'insertion le nécessitant.' );

			$conditions = array(
				'Contratinsertion.personne_id IN (
					'.$this->Contratinsertion->Personne->sq(
						array(
							'fields' => array( 'personnes.id' ),
							'alias' => 'personnes',
							'conditions' => array(
								'personnes.id = Contratinsertion.personne_id',
								'situationsdossiersrsa.etatdosrsa' => array( 5, 6 )
							),
							'joins' => array(
								array(
									'table'      => 'foyers',
									'alias'      => 'foyers',
									'type'       => 'INNER',
									'foreignKey' => false,
									'conditions' => array( 'foyers.id = personnes.foyer_id' )
								),
								array(
									'table'      => 'dossiers',
									'alias'      => 'dossiers',
									'type'       => 'INNER',
									'foreignKey' => false,
									'conditions' => array( 'dossiers.id = foyers.dossier_id' )
								),
								array(
									'table'      => 'situationsdossiersrsa',
									'alias'      => 'situationsdossiersrsa',
									'type'       => 'INNER',
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
			$this->out( 'Mise à jour des rangs des contrats d\'insertion.' );

			return $this->Contratinsertion->updateRangsContrats();
		}

		/**
		*
		*/

		protected function _updateAttvalid() {
			$this->out( 'Mise à jour de la position "'.__d( 'contratinsertion', "ENUM::POSITIONCER::attvalid", true ).'" pour les contrats d\'insertion le nécessitant.' );

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
			$this->out( 'Mise à jour de la position "'.__d( 'contratinsertion', "ENUM::POSITIONCER::encours", true ).'" pour les contrats d\'insertion le nécessitant.' );

			// Combien de personnes différentes ont un CER actif, dont la positioncer est NULL et validé ?
			$count = $this->Contratinsertion->find(
				'first',
				array(
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
		*	"fincontrat" (radiations CAF)
		*	"perime"
		*	"encoursbilan"
		*	"encours"
		* Résultats ( cg66_20110706_eps ):
		*	Original:
		*		encours			1259
		*		attvalid		40
		*		annule			19
		*		NULL			6259
		*	Final:
		*		encours			3103
		*		attvalid		28
		*		annule			19
		*		fincontrat		524
		*		encoursbilan	850
		*		perime			3032
		*		NULL			21
		*/

		public function main() {
			$success = true;
			$start = microtime( true );
			$this->Contratinsertion->begin();

			if ( count( $this->args ) == 0 ) {
				$this->help();
			}
			else {
				if ( in_array( 'attvalid', $this->args ) || in_array( 'all', $this->args ) ) {
					$success = $this->_updateAttvalid() && $success;
				}
				if ( in_array( 'fincontrat', $this->args ) || in_array( 'all', $this->args ) ) {
					$success = $this->_updateRadiationsCaf() && $success;
				}
				if ( in_array( 'perime', $this->args ) || in_array( 'all', $this->args ) ) {
					$success = $this->_updatePerime() && $success;
				}
				if ( in_array( 'encoursbilan', $this->args ) || in_array( 'all', $this->args ) ) {
					$success = $this->_updateEncoursbilan() && $success;
				}
				if ( in_array( 'encours', $this->args ) || in_array( 'all', $this->args ) ) {
					$success = $this->_updateEncours() && $success;
				}
				if ( in_array( 'majrangs', $this->args ) || in_array( 'all', $this->args ) ) {
					$success = $this->_updateRangsContrats() && $success;
				}
			}

			$this->hr();

			$temps = sprintf( "Shell exécuté en %s secondes.", number_format( microtime( true ) - $start, 2, ',', ' ' ) );

			if( $success ) {
				$this->Contratinsertion->commit();
				$this->out( "La mise à jour des positions du CER a été effectuée avec succès. {$temps}" );
				$this->_stop( 0 );
			}
			else {
				$this->Contratinsertion->rollback();
				$this->err( "Erreur lors de la mise à jour des positions du CER. {$temps}" );
				$this->_stop( 1 );
			}
		}

		/**
		* Aide
		*/

		public function help() {
			$this->out("Usage: cake/console/cake positioncer66 <action1> <action2> ...");
			$this->hr();
			$this->out('');
			$this->out('Actions:');
			$this->out("\n\t{$this->shell} all\n\t\tEffectue toutes les actions");
			$this->out("\n\t{$this->shell} help\n\t\tAffiche cette aide.");
			$this->out("\n\t{$this->shell} fincontrat\n\t\tMet à jour les CER dont la position devrait être 'Fin de contrat'");
			$this->out("\n\t{$this->shell} perime\n\t\tMet à jour les CER dont la position devrait être 'Contrat Périmé'");
			$this->out("\n\t{$this->shell} encoursbilan\n\t\tMet à jour les CER dont la position devrait être 'En cours: Bilan à réaliser'");
			$this->out("\n\t{$this->shell} attvalid\n\t\tMet à jour les CER dont la position devrait être 'En attente de validation'");
			$this->out("\n\t{$this->shell} encours\n\t\tMet à jour les CER dont la position devrait être 'En cours'");
			$this->out("\n\t{$this->shell} majrangs\n\t\tMet à jour les rangs des CERs");
			$this->out('');

			$this->_stop( 0 );
		}
	}
?>