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

			// FIXME -> dans le modèle ?
			$enums = array(
				'sitpersdemrsa',
				'topisogroouenf',
				'topdrorsarmiant',
				'drorsarmianta2',
				'topcouvsoc',
				'accosocfam',
				'accosocindi',
				'soutdemarsoc',
				'nivetu',
				'nivdipmaxobt',
				'topqualipro',
				'topcompeextrapro',
				'topengdemarechemploi',
				'hispro',
				'cessderact',
				'topdomideract',
				'duractdomi',
				'inscdememploi',
				'topisogrorechemploi',
				'accoemploi',
				'topprojpro',
				'topcreareprientre',
				'concoformqualiemploi',
				'topmoyloco',
				'toppermicondub',
				'topautrpermicondu',
				'natlog',
				'demarlog'
			);

			$options = $this->Dsp->allEnumOptions( $enums, 'dsp' );
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
				$this->data['Dsp'] = array_set_null_on_empty( $this->data['Dsp'] ); // FIXME

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