<?php
	/*
		http://localhost/adullact/webrsa/trunk/dspps/view/174537
		http://localhost/adullact/webrsa/trunk/dsps/view/174537
	*/
	class DspsController extends AppController
	{
		var $name = 'Dsps';

		var $helpers = array( 'Xform', 'Xhtml', 'Dsphm' );

		var $components = array( 'Jetons' );

		var $specialHasMany = array(
			'Detaildifsoc' => 'difsoc',
			'Detailaccosocfam' => 'nataccosocfam',
			'Detailaccosocindi' => 'nataccosocindi',
			'Detaildifdisp' => 'difdisp',
			'Detailnatmob' => 'natmob',
			'Detaildiflog' => 'diflog'
		);

		// FIXME: dans les modèles ?
		var $valuesNone = array(
			'Detaildifsoc' => '0401',
			'Detailaccosocfam' => null,
			'Detailaccosocindi' => null,
			'Detaildifdisp' => '0501',
			'Detailnatmob' => '2504',
			'Detaildiflog' => '1001'
		);


		/**
		*
		*/

		function beforeFilter() {
			$return = parent::beforeFilter();

			$options = $this->Dsp->enums();

			foreach( array_keys( $this->specialHasMany ) as $model ) {
				$options = Set::merge( $options, $this->Dsp->{$model}->enums() );
			}
			$this->set( 'options', $options );

			return $return;
		}

        /**
        *
        */

        public function add() {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }

        /**
        *
        */

        public function edit() {
            $args = func_get_args();
            call_user_func_array( array( $this, '_add_edit' ), $args );
        }

        /**
        *
        */

        public function view( $id = null ) {
			$dsp = $this->Dsp->findByPersonneId( $id );
			if( empty( $dsp ) ) {
				$dsp = $this->Dsp->Personne->findById( $id );
			}
			$this->assert( !empty( $dsp ), 'invalidParameter' );
			$this->set( 'dsp', $dsp );
        }


		/**
		*
		*/

		function _add_edit( $id = null ) {
			// Début de la transaction
			$this->Dsp->begin();

			// On cherche soit la dsp directement, soit la personne liée
			$dsp = null;
			if( ( $this->action == 'edit' ) && !empty( $id ) ) {
				$dsp = $this->Dsp->findById( $id );
			}
			else if( ( $this->action == 'add' ) && !empty( $id ) ) {
				$dsp = $this->Dsp->Personne->findById( $id, null, null, 1 );
			}

			// Vérification indirecte de l'id
			$this->assert( !empty( $dsp ), 'invalidParameter' );

			// Assertion: on doit pouvoir mettre un jeton sur le dossier
			$dossier_id = $this->Dsp->Personne->dossierId( Set::classicExtract( $dsp, 'Personne.id' ) );
			$hasJeton = $this->Jetons->get( $dossier_id );
			$this->assert( $hasJeton, 'lockedDossier' );

			// Tentative d'enregistrement
			if( !empty( $this->data ) ) {
				$success = true;

				// Nettoyage des Dsp
				$keys = array_keys( $this->Dsp->schema() );
				$defaults = array_combine( $keys, array_fill( 0, count( $keys ), null ) );
				unset( $defaults['id'] );

				$this->data['Dsp'] = Set::merge( $defaults, $this->data['Dsp'] );
				foreach( $this->data['Dsp'] as $key => $value ) {
					if( strlen( trim( $value ) ) == 0 ) {
						$this->data['Dsp'][$key] = null;
					}
				}

				// Modèles liés, début hasMany spéciaux
				$deleteConditions = array();
				foreach( $this->specialHasMany as $model => $checkbox ) {
					$values = Set::classicExtract( $this->data, "{$model}" );

					if( isset( $this->valuesNone[$model] ) ) {
						$tmpValues = Set::extract( $values, "/{$this->specialHasMany['Detaildifsoc']}" );
						$cKey = array_search( $this->valuesNone[$model], $tmpValues );
						$tmpValues = $values;
						if( $cKey !== false ) {
							unset( $tmpValues[$cKey] );// FIXME
							$ids = Set::extract( $tmpValues, '/id' );
							foreach( $ids as $id ) {
								$deleteConditions[$model][] = "{$model}.id = {$id}";
							}
						}
						// FIXME: s'assurer que les autres soient à 0 ?
					}


					foreach( $values as $key => $value ) {
						$val = Set::classicExtract( $value, $checkbox );
						if( empty( $val ) ) {
							if( isset( $value['id'] ) ) {
								$deleteConditions[$model][] = "{$model}.id = {$value['id']}";
							}
							unset( $this->data[$model][$key] );
						}
					}
				}

				foreach( $deleteConditions as $model => $values ) {
					if( !empty( $values ) ) {
						$this->Dsp->{$model}->deleteAll( array( 'or' => $values ) );
					}
				}
				// fin hasMany spéciaux

				$dsp_id = Set::classicExtract( $this->data, 'Dsp.id' );
				$this->data = Set::filter( $this->data );

				if( $success = $this->Dsp->saveAll( $this->data, array( 'atomic' => false, 'validate' => 'first' ) ) && $success ) {
					if( $success ) {
						$this->Session->setFlash( __( 'Enregistrement effectué', true ), 'flash/success' );
						// On enlève le jeton du dossier
						$this->Jetons->release( array( 'Dossier.id' => $dossier_id ) ); // FIXME: if -> error
						// Fin de la transaction
						$this->Dsp->commit();
						$this->redirect( array( 'action' => 'view', Set::classicExtract( $this->data, 'Dsp.personne_id' ) ) );
					}
					else {
						$this->Session->setFlash( __( 'Erreur lors de l\'enregistrement', true ), 'flash/error' );
						$this->Dsp->rollback();
					}
				}
			}
			// Affectation au formulaire
			else if( $this->action == 'edit' ) {
				$this->data = $dsp;
			}

			// Fin de la transaction
			$this->Dsp->commit();

			// Affectation à la vue
			$this->set( 'dsp', $dsp );
            $this->render( $this->action, null, '_add_edit' );
		}
	}
?>