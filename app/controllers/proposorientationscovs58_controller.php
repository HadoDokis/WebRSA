<?php

	class Proposorientationscovs58Controller extends AppController {
		
		public $name = "Proposorientationscovs58";
		
		public $helpers = array( 'Default', 'Default2' );
		
		protected function _setOptions() {
			$this->set( 'referents', $this->Propoorientationcov58->Referent->listOptions() );
			$this->set( 'typesorients', $this->Propoorientationcov58->Typeorient->listOptions() );
			$this->set( 'structuresreferentes', $this->Propoorientationcov58->Structurereferente->list1Options( array( 'orientation' => 'O' ) ) );
		}
		
		public function beforeFilter() {
			return parent::beforeFilter();
		}
		
		public function add( $personne_id = null ) {
			$this->assert( valid_int( $personne_id ), 'invalidParameter' );

			// Retour à l'index en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'orientsstructs', 'action' => 'index', $personne_id ) );
			}

			$dossier_id = $this->Propoorientationcov58->Dossiercov58->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Propoorientationcov58->begin();
			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Propoorientationcov58->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

			if( !empty( $this->data ) ) {
				$saved = true;
				
				$this->data['Propoorientationcov58']['rgorient'] = $this->Propoorientationcov58->Dossiercov58->Personne->Orientstruct->rgorientMax( $personne_id );
				
				if ( $this->Propoorientationcov58->Structurereferente->Orientstruct->isRegression( $personne_id, $this->data['Propoorientationcov58']['typeorient_id'] ) ) {
					$dossierep = array(
						'Dossierep' => array(
							'personne_id' => $personne_id,
							'themeep' => 'regressionsorientationseps58',
						)
					);
					$saved = $this->Propoorientationcov58->Structurereferente->Regressionorientationep58->Dossierep->save( $dossierep ) && $saved;
					
					$regressionorientationep58['Regressionorientationep58'] = $this->data['Propoorientationcov58'];
					$regressionorientationep58['Regressionorientationep58']['personne_id'] = $personne_id;
					$regressionorientationep58['Regressionorientationep58']['dossierep_id'] = $this->Propoorientationcov58->Structurereferente->Regressionorientationep58->Dossierep->id;
					
					if ( isset($regressionorientationep58['Regressionorientationep58']['referent_id']) ) {
						list( $structurereferente_id, $referent_id) = explode( '_', $regressionorientationep58['Regressionorientationep58']['referent_id'] );
						$regressionorientationep58['Regressionorientationep58']['structurereferente_id'] = $structurereferente_id;
						$regressionorientationep58['Regressionorientationep58']['referent_id'] = $referent_id;
					}
					
					$regressionorientationep58['Regressionorientationep58']['datedemande'] = $regressionorientationep58['Regressionorientationep58']['date_propo'];
					
					$saved = $this->Propoorientationcov58->Structurereferente->Regressionorientationep58->save( $regressionorientationep58 ) && $saved;
				}
				else {
					$themecov58 = $this->Propoorientationcov58->Dossiercov58->Themecov58->find(
						'first',
						array(
							'conditions' => array(
								'Themecov58.name' => Inflector::tableize($this->Propoorientationcov58->alias)
							),
							'contain' => false
						)
					);
					$dossiercov58['Dossiercov58']['themecov58_id'] = $themecov58['Themecov58']['id'];
					$dossiercov58['Dossiercov58']['personne_id'] = $personne_id;
					
					$saved = $this->Propoorientationcov58->Dossiercov58->save($dossiercov58) && $saved;
					
					$this->Propoorientationcov58->create();
					
					$this->data['Propoorientationcov58']['dossiercov58_id'] = $this->Propoorientationcov58->Dossiercov58->id;
					
					$saved = $this->Propoorientationcov58->save( $this->data['Propoorientationcov58'] ) && $saved;
				}
				
				if( $saved ) {
					$this->Jetons->release( $dossier_id );
					$this->Propoorientationcov58->commit();
					$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
					$this->redirect( array( 'controller' => 'orientsstructs', 'action' => 'index', $personne_id ) );
				}
				else {
					$this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
					$this->Propoorientationcov58->rollback();
				}
			}
			else {
				$personne = $this->Propoorientationcov58->Dossiercov58->Personne->findByid( $personne_id, null, null, 0 );
			}

			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
			$this->render( $this->action, null, 'add_edit' );
		}
		
	}

?>