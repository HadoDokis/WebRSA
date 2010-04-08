<?php
    class DsphmHelper extends AppHelper
    {
        var $helpers = array( 'Xform', 'Xhtml' );

        /** ********************************************************************
        *
        *** *******************************************************************/

        function fieldset( $model, $code, $libdetails, $dsp_id, $code_autre, $options ) {
			$i = 0;
			$return = '';
			foreach( $options as $value => $label ) {
				// FIXME: checked
				$checked = Set::extract( $this->data, "/{$model}[{$code}={$value}]" );

				$item_id = Set::classicExtract( $checked, "0.{$model}.id" );
				if( !empty( $item_id ) ) {
					$return .= $this->Xform->input( "{$model}.{$i}.id", array( 'type' => 'hidden', 'value' => $item_id ) );
				}

				if( !empty( $dsp_id ) ) {
					$return .= $this->Xform->input( "{$model}.{$i}.dsp_id", array( 'type' => 'hidden', 'value' => $dsp_id ) );
				}

				$return .= $this->Xform->input( "{$model}.{$i}.{$code}", array( 'label' => $label, 'value' => $value, 'domain' => 'dsp', 'type' => 'checkbox', 'checked' => !empty( $checked ) ) );

				if( $value == $code_autre ) {
					$value = Set::extract( $this->data, "/{$model}[{$code}={$code_autre}]/{$libdetails}" );
					$return .= $this->Xform->input( "{$model}.{$i}.{$libdetails}", array( 'domain' => 'dsp', 'type' => 'textarea', 'value' => implode( "\n\n", $value ) ) );
					$return .= "<script type=\"text/javascript\">document.observe( 'dom:loaded', function() { observeDisableFieldsOnCheckbox( '{$model}{$i}".ucfirst( $code )."', [ '{$model}{$i}".ucfirst( $libdetails )."' ], false ); } );</script>";
				}
				$i++;
			}

			return $this->Xhtml->tag( 'fieldset', $this->Xhtml->tag( 'legend', __d( 'dsp', "{$model}.{$code}", true ) ).$return );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function details( $dsp, $model, $code, $libdetails, $options ) {
			$answers = array();

			$items = Set::extract( $dsp, "/{$model}/{$code}" );
			$libautrdifsocs = Set::filter( Set::extract( $dsp, "/{$model}/{$libdetails}" ) );

			if( !empty( $items ) ) {
				$ul = array();
				$libs = array();

				foreach( $items as $key => $item ) {
					$ul[] = $this->Xhtml->tag( 'li', Set::enum( $item , $options ) );
				}

				$answers[] = array(
					__d( 'dsp', "{$model}.{$code}", true ),
					$this->Xhtml->tag( 'ul', implode( '', $ul ) )
				);

				if( !empty( $libdetails ) ) {
					$answers[] = array(
						__d( 'dsp', "{$model}.{$libdetails}", true ),
						h( implode( '', $libautrdifsocs ) )
					);
				}
			}
			else {
				$answers = array(
					array( __d( 'dsp', "{$model}.{$code}", true ), null )
				);

				if( !empty( $libdetails ) ) {
					$answers[] = array( __d( 'dsp', "{$model}.{$libdetails}", true ), null );
				}
			}

			return $this->Xhtml->details( $answers, array( 'type' => 'list', 'empty' => true ) );
        }
    }
?>