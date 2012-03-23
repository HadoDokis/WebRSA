<?php
    class Proposdecisionscers66Controller extends AppController
    {
        public $name = 'Proposdecisionscers66';
        
        public $uses = array( 'Propodecisioncer66', 'Option' );
        
        public $helpers = array( 'Default2' );
        
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

			$this->Propodecisioncer66->begin();
			
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

			// Retour à la liste en cas d'annulation
			if( !empty( $this->data ) && isset( $this->params['form']['Cancel'] ) ) {
				$this->redirect( array( 'controller' => 'contratsinsertion', 'action' => 'index', $personne_id ) );
			}
			
			$dossier_id = $this->Propodecisioncer66->Contratinsertion->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			if ( !$this->Jetons->check( $dossier_id ) ) {
				$this->Propodecisioncer66->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );
			
			if ( !empty( $this->data ) ) {

				if( $this->Propodecisioncer66->saveAll( $this->data, array( 'validate' => 'only', 'atomic' => false ) ) ) {
					$saved = $this->Propodecisioncer66->save( $this->data );

					if( !isset( $this->data['Motifcernonvalid66'] ) && !empty( $propodecisioncer66 ) ){
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
						$saved = $this->Propodecisioncer66->Contratinsertion->updateAll(
							array(
								'Contratinsertion.positioncer' => '\'attsignature\'',
							),
							array(
								'Contratinsertion.personne_id' => $personne_id,
								'Contratinsertion.id' => $contratinsertion_id
							)
						) && $saved;
					}
					if( $saved ) {
						$this->Jetons->release( $dossier_id );
						$this->Propodecisioncer66->commit();
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
				$this->data = $propodecisioncer66;
			}
			
			$this->_setOptions();
			$this->set( 'urlmenu', '/contratsinsertion/index/'.$personne_id );

        }

    }
?>
