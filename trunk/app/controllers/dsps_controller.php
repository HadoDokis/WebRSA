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

			$options = $this->Dsp->allEnumOptions( 'dsp' );
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
				$this->data['Dsp'] = nullify_empty_values( $this->data['Dsp'] ); // FIXME

				$this->Dsp->create( $this->data );
				if( $this->Dsp->save() ) {
					$this->Session->setFlash( 'Enregistrement effectué' );
				}
			}
			else if( !empty( $id ) ) {
				$this->data = $dsp;
			}

			$this->set( 'dsp', $dsp );
		}
	}
?>