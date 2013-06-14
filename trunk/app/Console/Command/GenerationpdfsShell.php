<?php
	/**
	 * Fichier source de la classe GenerationpdfsShell.
	 *
	 * PHP 5.3
	 *
	 * @package app.Console.Command
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'XShell', 'Console/Command' );

	/**
	 * La classe GenerationpdfsShell ...
	 *
	 * @package app.Console.Command
	 */
	class GenerationpdfsShell extends XShell
	{

		public function getOptionParser() {
			$parser = parent::getOptionParser();
			$parser->description( 'Ce script se charge de générer et d\'enregistrer les .pdf en base de données pour les orientations ainsi que pour les relances des personnes n\'ayant pas de contractualisation ou pour non renouvellement de contrat.' );
			$options = array(
				'limit' => array(
					'short' => 'L',
					'help' => 'Limite sur le nombre d\'enregistrements à traiter',
					'default' => 10
				),
				'username' => array(
					'short' => 'u',
					'help' => 'L\'identifiant de l\'utilisateur qui sera utilisé pour la récupération d\'informations lors de l\'impression (pour les orientations seulement)',
					'default' => ''
				)
			);
			$parser->addOptions( $options );
			$subcomands = array(
				'relancenonrespectsanctionep93' => array(
					'help' => 'Génère les impressions des relances pour pour non respect et sanctions (CG 93).'
				),
				'orientsstructs' => array(
					'help' => 'Génère les impressions des orientations (le paramètre --username (-u) est obligatoire).'
				)
			);
			$parser->addSubcommands( $subcomands );
			return $parser;
		}

		protected function _showParams() {
			parent::_showParams();
			$this->out( '<info>Limite sur le nombre d\'enregistrements à traiter : </info><important>'.$this->params['limit'].'</important>' );
			$this->out( '<info>Identifiant de l\'utilisateur : </info><important>'.$this->params['username'].'</important>' );
		}

		/**
		 *
		 */
		public function relancenonrespectsanctionep93() {
			$this->Relancenonrespectsanctionep93 = ClassRegistry::init( 'Relancenonrespectsanctionep93' );

			$queryData = array(
				'fields' => array(
					'Relancenonrespectsanctionep93.id'
				),
				'conditions' => array(
					'Relancenonrespectsanctionep93.id NOT IN (
						SELECT pdfs.fk_value
							FROM pdfs
							WHERE pdfs.modele = \'Relancenonrespectsanctionep93\'
					)'
				)
			);

			if( !empty( $this->params['limit'] ) && is_numeric( $this->params['limit'] ) ) {
				$queryData['limit'] = $this->params['limit'];
			}

			$relances = $this->Relancenonrespectsanctionep93->find( 'all', $queryData );

			$this->_wait( sprintf( "%s impressions à générer", count( $relances ) ) );

			$this->XProgressBar->start( count( $relances ) );
			$success = true;
			foreach( $relances as $i => $relance ) {
				$this->XProgressBar->next( 1, sprintf( "<info>Impression de la relance %s (id %s)</info>", $i + 1, $relance['Relancenonrespectsanctionep93']['id'] ) );
				$this->Relancenonrespectsanctionep93->generatePdf( $relance['Relancenonrespectsanctionep93']['id'] );
			}
		}

		/**
		 *
		 */
		public function orientsstructs() {

			$error = false;
			$out = array( );

			// A-t-on spécifié l'identifiant d'un utilisateur (obligatoire dans ce cas-ci) ?
			if( empty( $this->params['username'] ) ) {
				$out[] = "<error>Veuillez spécifier l'identifiant d'un utilisateur qui sera utilisé pour la récupération d'informations lors de l'impression pour les impressions d'orientations (exemple: -username webrsa).</error>";
				$error = true;
			}
			else {

				// L'utilisateur existe-t'il
				$this->User = ClassRegistry::init( 'User' );
				$user = $this->User->find(
						'first', array(
					'conditions' => array(
						'User.username' => $this->params['username']
					),
					'recursive' => -1
						)
				);

				if( empty( $user ) ) {
					$out[] = "<error>L'identifiant d'utilisateur spécifié n'existe pas.</error>";
					$error = true;
				}
			}

			if( !$error ) {
				$this->Orientstruct = ClassRegistry::init( 'Orientstruct' );

				$queryData = array(
					'fields' => array( 'Orientstruct.id' ),
					'conditions' => array(
						'Orientstruct.statut_orient' => 'Orienté',
						'Orientstruct.id NOT IN ( SELECT pdfs.fk_value FROM pdfs WHERE pdfs.modele = \'Orientstruct\' )'
					),
					'order' => array( 'Orientstruct.date_valid ASC' ),
					'recursive' => -1
				);

				if( !empty( $this->params['limit'] ) && is_numeric( $this->params['limit'] ) ) {
					$queryData['limit'] = $this->params['limit'];
				}

				$orientsstructs = $this->Orientstruct->find( 'all', $queryData );

				$this->_wait( sprintf( "%s impressions à générer", count( $orientsstructs ) ) );

				$this->XProgressBar->start( count( $orientsstructs ) );
				$success = true;
				foreach( $orientsstructs as $i => $orientstruct ) {
					$this->XProgressBar->next( 1, sprintf( "<info>Impression de l'orientation %s (id %s)</info>", $i + 1, $orientstruct['Orientstruct']['id'] ) );
					$success = $this->Orientstruct->generatePdf( $orientstruct['Orientstruct']['id'], $user['User']['id'] ) && $success;
					if( empty( $success ) ) { // FIXME: pour les autres aussi
						$out[] = '<error>'.sprintf( "Erreur lors de l'impression de l'orientation %s (id %s)", $i + 1, $orientstruct['Orientstruct']['id'] ).'</error>';
						$error = true;
					}
				}
			}

			if( $error ) {
				$this->out();
				$this->out( $out );
			}
		}

	}
?>