<?php
	class DspsController extends AppController
	{
		var $name = 'Dsps';

		var $helpers = array( 'Xform' );

		/** ********************************************************************
		*
		*** *******************************************************************/

		function beforeFilter() {
			$return = parent::beforeFilter();

			$options = $this->Dsp->allEnumLists();
			$this->set( 'options', $options );

			return $return;
		}

		/** ********************************************************************
		*	FIXME: En fait, c'est un add_edit
		*** *******************************************************************/

		function view( $id = null ) {
			if( !empty( $id ) ) {
				$dsp = $this->Dsp->findById( $id );
				$this->assert( !empty( $dsp ), 'invalidParameter' );
			}
			else {
				$dsp = $this->Dsp->Personne->findById( 1 ); // FIXME
			}

			///
			if( !empty( $this->data ) ) {
				// FIXME: faire une fonction
				$fields = array_keys( $this->Dsp->schema() );
				$fields = array_combine( $fields, array_fill( 0, count( $fields ), null ) );
				$this->data['Dsp'] = Set::merge( $fields, nullify_empty_values( $this->data['Dsp'] ) );

				$this->Dsp->create( $this->data );
				if( $this->Dsp->save() ) {
					$this->Session->setFlash( __( 'Enregistrement effectué', true ) );
				}
			}
			else if( !empty( $id ) ) {
				$this->data = $dsp;
			}

			$this->set( 'dsp', $dsp );
		}
	}
?>