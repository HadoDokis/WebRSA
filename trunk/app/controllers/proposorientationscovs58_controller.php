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
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			// Retour à l'index s'il n'est pas possible d'ajouter une orientation
			if( !$this->Propoorientationcov58->ajoutPossible( $personne_id ) ) {
				$this->Session->setFlash( 'Impossible d\'ajouter une orientation pour cette personne.', 'flash/error' );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}

			// Pour le CG 93, les orientations de rang > 1 doivent passer en EP, donc il faut utiliser Saisinesepsreorientsrs93Controller::add
			// FIXME
			/*if( Configure::read( 'Ep.departement' ) == 93 && $this->Orientstruct->rgorientMax( $personne_id ) > 1 ) {
				$this->Session->setFlash( 'L\'orientation de cette personne doit se faire via un passage en EP', 'flash/error' );
				$this->redirect( array( 'action' => 'index', $personne_id ) );
			}*/

			$dossier_id = $this->Propoorientationcov58->Personne->dossierId( $personne_id );
			$this->assert( !empty( $dossier_id ), 'invalidParameter' );

			$this->Propoorientationcov58->begin();
			if( !$this->Jetons->check( $dossier_id ) ) {
				$this->Propoorientationcov58->rollback();
			}
			$this->assert( $this->Jetons->get( $dossier_id ), 'lockedDossier' );

//             $this->set( 'options', $this->Typeorient->listOptions() );
//             $this->set( 'options2', $this->Structurereferente->list1Options( array( 'orientation' => 'O' ) ) );
//             $this->set( 'referents', $this->Referent->listOptions() );


			if( !empty( $this->data ) ) {
// debug( $this->data );
				$this->Propoorientationcov58->set( $this->data );
//                 $this->Typeorient->set( $this->data );
//                 $this->Structurereferente->set( $this->data );

				$validates = $this->Propoorientationcov58->validates();
//                 $validates = $this->Typeorient->validates() && $validates;
//                 $validates = $this->Structurereferente->validates() && $validates;


				if( $validates ) {
					// Orientation
					$this->Propoorientationcov58->create();

					$this->data['Propoorientationcov58']['personne_id'] = $personne_id;
					$this->data['Propoorientationcov58']['valid_cg'] = true;
					$this->data['Propoorientationcov58']['date_propo'] = date( 'Y-m-d' );
					$this->data['Propoorientationcov58']['date_valid'] = date( 'Y-m-d' );
					$this->data['Propoorientationcov58']['statut_orient'] = 'Orienté';

					$saved = $this->Propoorientationcov58->Personne->Calculdroitrsa->save( $this->data );
					$saved = $this->Propoorientationcov58->save( $this->data['Propoorientationcov58'] ) && $saved;

					if( $saved ) {
						$this->Jetons->release( $dossier_id );
						$this->Propoorientationcov58->commit();
						$this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
						$this->redirect( array( 'controller' => 'orientsstructs', 'action' => 'index', $personne_id ) );
					}
					else {
						$this->Propoorientationcov58->rollback();
					}
				}
			}
			else {
				$personne = $this->Propoorientationcov58->Personne->findByid( $personne_id, null, null, 0 );
				$this->data['Calculdroitrsa'] = $personne['Calculdroitrsa'];
			}

			$this->_setOptions();
			$this->set( 'personne_id', $personne_id );
			$this->render( $this->action, null, 'add_edit' );
		}
		
	}

?>