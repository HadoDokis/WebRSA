<?php
	/**
	 * Code source de la classe Proposdecisionscers66Controller.
	 *
	 * PHP 5.3
	 *
	 * @package app.Controller
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Proposdecisionscers66Controller ... (CG 66).
	 *
	 * @package app.Controller
	 */
	class Proposdecisionscers66Controller extends AppController
	{
		public $name = 'Proposdecisionscers66';

		public $uses = array( 'Propodecisioncer66', 'Option' );

		public $helpers = array( 'Default2' );

		public $components = array( 'Jetons2' );

		protected function _setOptions() {
			$options = $this->Propodecisioncer66->allEnumLists();


			$forme_ci = array( 'S' => 'Simple', 'C' => 'Particulier' );
			$this->set( 'forme_ci', $forme_ci );

			$this->set( 'qual', $this->Option->qual() );
			$this->set( 'duree_engag_cg66', $this->Option->duree_engag_cg66() );
			$options = array_merge(
				$this->Propodecisioncer66->Contratinsertion->enums(),
				$options
			);

			$listMotifs = $this->Propodecisioncer66->Motifcernonvalid66->find( 'list' );
			$this->set( compact( 'listMotifs' ) );
			$this->set( 'options', $options );
		}

		public function proposition( $contratinsertion_id ) {
			$this->assert( valid_int( $contratinsertion_id ), 'invalidParameter' );

			//Proposition de décision pour le CER
			$propodecisioncer66 = $this->Propodecisioncer66->find(
				'first',
				array(
					'conditions' => array(
						'Propodecisioncer66.contratinsertion_id' => $contratinsertion_id
					),
					'order' => array( 'Propodecisioncer66.created DESC' ),
					'contain' => array(
						'Motifcernonvalid66'
					)
				)
			);

			// CER lié à la décision
			$contratinsertion = $this->Propodecisioncer66->Contratinsertion->find(
				'first',
				array(
					'conditions' => array(
						'Contratinsertion.id' => $contratinsertion_id
					),
					'contain' => array(
						'Referent'
					)
				)
			);
			$this->set( 'contratinsertion', $contratinsertion );

			$personne_id = Set::classicExtract( $contratinsertion, 'Contratinsertion.personne_id' );
			$this->set( 'personne_id', $personne_id );
			$this->set( 'contratinsertion_id', $contratinsertion_id );

			$dossier_id = $this->Propodecisioncer66->Contratinsertion->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Jetons2->get( $dossier_id );

			// Retour à la liste en cas d'annulation
			if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
				$this->Jetons2->release( $dossier_id );
				$this->redirect( array( 'controller' => 'contratsinsertion', 'action' => 'index', $personne_id ) );
			}

			if ( !empty( $this->request->data ) ) {
				$this->Propodecisioncer66->begin();

				if( $this->Propodecisioncer66->saveAll( $this->request->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
					$saved = $this->Propodecisioncer66->save( $this->request->data );

					if( !isset( $this->request->data['Motifcernonvalid66'] ) && !empty( $propodecisioncer66 ) ){
						$saved = $this->Propodecisioncer66->Motifcernonvalid66Propodecisioncer66->deleteAll(
							array(
								'Motifcernonvalid66Propodecisioncer66.propodecisioncer66_id' => $this->Propodecisioncer66->id
							)
						) && $saved;

						$saved = $this->Propodecisioncer66->updateAll(
							array(
								'Propodecisioncer66.motifficheliaison' => null,
								'Propodecisioncer66.motifnotifnonvalid' => null
							),
							array(
								'Propodecisioncer66.id' => $this->Propodecisioncer66->id
							)
						) && $saved;

					}

					if( $saved ){
						$dateDecision = date_cakephp_to_sql( $this->request->data['Propodecisioncer66']['datevalidcer'] );
						if( $this->request->data['Propodecisioncer66']['decisionfinale'] == 'O' ) {
							if( $this->request->data['Propodecisioncer66']['isvalidcer'] == 'O' ) {
								$saved = $this->Propodecisioncer66->Contratinsertion->updateAll(
									array(
										'Contratinsertion.decision_ci' => '\'V\'',
										'Contratinsertion.datevalidation_ci' => "'".$dateDecision."'",
										'Contratinsertion.datedecision' => "'".$dateDecision."'",
										'Contratinsertion.positioncer' => '\'encours\'',
									),
									array(
										'Contratinsertion.personne_id' => $personne_id,
										'Contratinsertion.id' => $contratinsertion_id
									)
								) && $saved;
							}
							else if( $this->request->data['Propodecisioncer66']['isvalidcer'] == 'N' ) {
								$saved = $this->Propodecisioncer66->Contratinsertion->updateAll(
									array(
										'Contratinsertion.decision_ci' => '\'N\'',
										'Contratinsertion.datevalidation_ci' => null,
										'Contratinsertion.datedecision' => "'".$dateDecision."'",
										'Contratinsertion.positioncer' => '\'nonvalid\'',
									),
									array(
										'Contratinsertion.personne_id' => $personne_id,
										'Contratinsertion.id' => $contratinsertion_id
									)
								) && $saved;

							}
						}

					}
					if( $saved ) {
						$this->Propodecisioncer66->commit();
						$this->Jetons2->release( $dossier_id );
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'contratsinsertion', 'action' => 'index', $personne_id ) );
					}
					else {
						$this->Propodecisioncer66->rollback();
						$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					}

				}
			}
			else{
				$this->request->data = $propodecisioncer66;
			}

			$this->_setOptions();
			$this->set( 'urlmenu', '/contratsinsertion/index/'.$personne_id );
			$this->render( 'proposition' );
		}

		/**
		 * Fonction de validation pour les CERs Simples du CG66
		 *
		 * @param type $contratinsertion_id
		 */
		public function propositionsimple( $contratinsertion_id = null ) {
			$this->Propodecisioncer66->Contratinsertion->id = $contratinsertion_id;
			$forme_ci = $this->Propodecisioncer66->Contratinsertion->field( 'forme_ci' );
			$this->assert( ( $forme_ci == 'S' ), 'error500' );

			$this->proposition( $contratinsertion_id );
		}

		/**
		 * Fonction de validation pour les CERs Particuliers du CG66
		 * 
		 * @param type $contratinsertion_id
		 */
		public function propositionparticulier( $contratinsertion_id = null ) {
			$this->Propodecisioncer66->Contratinsertion->id = $contratinsertion_id;
			$forme_ci = $this->Propodecisioncer66->Contratinsertion->field( 'forme_ci' );
			$this->assert( ( $forme_ci == 'C' ), 'error500' );

			$this->proposition( $contratinsertion_id );
		}
	}
?>
