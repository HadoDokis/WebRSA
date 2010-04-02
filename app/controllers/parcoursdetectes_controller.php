<?php
	class ParcoursdetectesController extends AppController
	{

		var $uses = array( 'Parcoursdetecte', 'Option', 'Typeorient', 'Structurereferente', 'Referent', 'Decisionparcours', 'Ep' );

		/**
		*
		*/

		function beforeFilter() {
			$return = parent::beforeFilter();
			$options = array();
            $this->set( 'qual', $this->Option->qual() );

            foreach( $this->{$this->modelClass}->allEnumLists() as $field => $values ) {
                $options = Set::insert( $options, "{$this->modelClass}.{$field}", $values );
            }

			foreach( array( 'Ep' ) as $linkedModel ) {
				$field = Inflector::singularize( Inflector::tableize( $linkedModel ) ).'_id';
				$options = Set::insert( $options, "{$this->modelClass}.{$field}", $this->{$this->modelClass}->{$linkedModel}->find( 'list' ) );
			}

			$this->set( compact( 'options' ) );

			return $return;
		}

		/**
		* FIXME: ne montrer que:
		*	- les parcours pas encore passés en EP
		*	- dont le created date de 10 jours maximum
		*/

		public function index() {
			if( !empty( $this->data ) && Set::check( $this->data, $this->modelClass ) ) {
				$datas = Set::extract( $this->data, "{$this->modelClass}" );
				if( !empty( $datas ) ) {
					if( $this->{$this->modelClass}->saveAll( $datas ) ) {
						$this->data = Set::remove( $this->data, $this->modelClass );
						$this->Session->setFlash( __( 'Save->success', true ), 'flash/success' );
					}
					else {
						$this->Session->setFlash( __( 'Save->error', true ), 'flash/error' );
					}
				}
			}

			$this->Default->index( array( $this->modelClass => array( 'recursive' => 2 ) ) );
		}

		/**
		*
		*/

		protected function _decision( $ep_id, $step ) {
			$options = Set::extract( $this->viewVars, 'options' );
			$options = Set::insert( $options, "Decisionparcours{$step}.typeorient_id", $this->Typeorient->listOptions() );
			$options = Set::insert( $options, "Decisionparcours{$step}.structurereferente_id", $this->Structurereferente->list1Options() );
			$options = Set::insert( $options, "Decisionparcours{$step}.referent_id", $this->Referent->find( 'list' ) );

			// Recherche
			$queryData = array(
				'Parcoursdetecte' => array(
					'conditions' => array( 'Parcoursdetecte.ep_id' => $ep_id )
				)
			);
			$this->Default->index( $queryData );

			// Enregistrement
			if( !empty( $this->data ) && Set::check( $this->data, "Decisionparcours{$step}" ) ) {
				$datas = Set::extract( $this->data, "Decisionparcours{$step}" );
				if( !empty( $datas ) ) {
					if( $this->Decisionparcours->saveAll( $datas, array( 'validate' => 'first' ) ) ) {
						$this->Session->setFlash( __( 'Save->success', true ), 'flash/success' );
						$this->redirect( $this->referer() );
					}
					else {
						// INFO: on travaille avec des alias
						$m = "Decisionparcours{$step}";
						$this->Parcoursdetecte->{$m}->validationErrors = $this->Decisionparcours->validationErrors;
						$this->Session->setFlash( __( 'Save->error', true ), 'flash/error' );
					}
				}
			}
            $ep = $this->Ep->findById( $ep_id, null, null, -1 );
//             debug($ep);

			$this->set( compact( 'step', 'options', 'ep' ) );

            $this->render( $this->action, null, 'decision' );
		}

		/**
		*
		*/

		public function equipe( $ep_id ) {
			$this->_decision( $ep_id, 'equipe' );
		}


		/**
		*
		*/

		public function conseil( $ep_id ) {
			$this->_decision( $ep_id, 'conseil' );
		}

        /**
        *   Script présent dans les scripts shell detectionparcours.php
        *   -Equivalent à : cake/console/cake detectionparcours
        */

        public function detecte() {
            $success = true;
            $compteur = 0;
            $this->Parcoursdetecte->begin();


	    ///FIXME: ajout pour le GTC de la civilité, du nom et du prenom de la Personne
            $sql = 'SELECT orientsstructs.*, age( orientsstructs.date_valid ), personnes.nom, personnes.prenom, personnes.qual
                FROM personnes
                    INNER JOIN prestations ON (
                        prestations.personne_id = personnes.id
                        AND prestations.natprest = \'RSA\'
                        AND ( prestations.rolepers = \'DEM\' OR prestations.rolepers = \'CJT\' )
                    )
                    INNER JOIN orientsstructs ON (
                        orientsstructs.personne_id = personnes.id
                        AND orientsstructs.statut_orient = \'Orienté\'
                        AND orientsstructs.typeorient_id IN (
                            SELECT typesorients.id
                                FROM typesorients
                                WHERE typesorients.lib_type_orient IN (
                                    \'Social\',
                                    \'Socioprofessionnelle\'
                                )
                        )
                        AND age( orientsstructs.date_valid ) > \'6 mons\'
                        AND orientsstructs.id IN (
                            SELECT orientsstructs.id
                                FROM orientsstructs
                                WHERE orientsstructs.date_valid IS NOT NULL
                                    AND orientsstructs.statut_orient = \'Orienté\'
                                GROUP BY orientsstructs.personne_id, orientsstructs.id, orientsstructs.date_valid
                                ORDER BY orientsstructs.date_valid DESC
                                -- LIMIT 1
                        )
                        AND orientsstructs.id NOT IN (
                            SELECT parcoursdetectes.orientstruct_id
                                FROM parcoursdetectes
                        )
                    )
                WHERE personnes.id NOT IN (
                    SELECT contratsinsertion.personne_id
                        FROM contratsinsertion
                        WHERE contratsinsertion.dd_ci <= NOW()
                            AND contratsinsertion.df_ci >= NOW()
                );';

//             $this->hr();

            $orientsstructs = $this->Parcoursdetecte->query( $sql );
            if( !empty( $orientsstructs ) ) {
                foreach( $orientsstructs as $orientstruct ) {
                    $parcoursdetecte = array(
                        'Parcoursdetecte' => array(
                            'orientstruct_id' => $orientstruct[0]['id']
                        )
                    );
                    $this->Parcoursdetecte->create( $parcoursdetecte );
                    $tmpSuccess = $this->Parcoursdetecte->save();
                    if( $tmpSuccess ) {
                        $this->Session->setFlash( __( 'Save->success', true ), 'flash/success' );
                    }
                    else {
                        $this->Session->setFlash( __( 'Save->error', true ), 'flash/error' );
                    }
                    $success = $tmpSuccess && $success;
                    $compteur++;
                }
            }

            /// Fin de la transaction

            if( $success ) {
                $this->Parcoursdetecte->commit();
            }
            else {
                $this->Parcoursdetecte->rollback();
            }

            $parcoursdetectes = $this->Parcoursdetecte->find( 'all', array( 'recursive' => 2 ) );
            $typeorient = $this->Typeorient->listOptions();
            $struct = $this->Structurereferente->find( 'list' );
//             if( !empty( $orientsstructs ) ) {
//                 $pers = $this->Personne->findById( Set::extract( $orientsstructs, "/{n}/0/personne_id" ), null, null, -1 );
//                 $this->set( compact( 'pers' ) );
//             }
// debug($orientsstructs);
            $this->set( compact( 'compteur', 'orientsstructs', 'parcoursdetectes', 'typeorient', 'struct' ) );

        }

	}
?>