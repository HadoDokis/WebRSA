<?php

class SearchHelper extends AppHelper {

	public $helpers = array('Html', 'Xform');


	protected function _constuctObserve( $observeId, $updateId, $up = true)
	{
		$stringUp = '';
		if( $up )
			$stringUp = ".up( 'fieldset' )";
		$out = "document.observe('dom:loaded', function() {
			observeDisableFieldsetOnCheckbox( '{$observeId}', $( '{$updateId}' ){$stringUp}, false );
		});";
		return "<script type='text/javascript'>{$out}</script>";

	} 
	
	protected function _domId($modelField) {
		return $this->domId($modelField);
	}
	
	
	public function etatdosrsa($etatdosrsa)
	{	
		reset($etatdosrsa);
		$first = key($etatdosrsa);
		
		$script = $this->_constuctObserve($this->_domId('Situationdossierrsa.etatdosrsa_choice'), $this->_domId('Situationdossierrsa.etatdosrsa'.$first), true);
		
		$input = $this->Xform->input( 'Situationdossierrsa.etatdosrsa_choice', array( 'label' => 'Filtrer par état du dossier', 'type' => 'checkbox' ) );
			
		$etatsCoches = Set::extract( $this->data, 'Situationdossierrsa.etatdosrsa' );
		if( empty( $etatsCoches ) ) {
			$etatsCoches = array_keys( $etatdosrsa );
		}		
		
		$input.= $this->Xform->input( 'Situationdossierrsa.etatdosrsa', array( 'label' => 'État du dossier RSA', 'type' => 'select', 'multiple' => 'checkbox', 'options' => $etatdosrsa, 'value' => $etatsCoches ) );
	
		return $script . $input; 
		
	}

		public function natpf($natpf)
		{	
			reset($natpf);
			$first = key($natpf);
			
			$script = $this->_constuctObserve($this->_domId('Detailcalculdroitrsa.natpf_choice'), $this->_domId('Detailcalculdroitrsa.natpf'.$first), true);

			$input = $this->Xform->input( 'Detailcalculdroitrsa.natpf_choice', array( 'label' => 'Filtrer par nature de prestation (RSA Socle)', 'type' => 'checkbox' ) );
				
			$natpfsCoches = Set::extract( $this->data, 'Detailcalculdroitrsa.natpf' );
			if( empty( $natpfsCoches ) ) {
				$natpfsCoches = array_keys( $natpf );
			}		
			
			$input.= $this->Xform->input( 'Detailcalculdroitrsa.natpf', array( 'label' => 'Nature de la prestation', 'type' => 'select', 'multiple' => 'checkbox', 'options' => $natpf, 'value' => $natpfsCoches ) );
		
			return $script . $input; 
			
		}
	
	
		/**
		*	Filtre sur les états du dossierpcg66
		*
		*/
		public function etatDossierPCG66( $etatdossierpcg )
		{	
			reset($etatdossierpcg);
			$firstValue = key($etatdossierpcg);
			
			// Passage de la première lettre de l'enum en majuscule, sinon ne marche pas
			$first = ucfirst($firstValue);


			$script = $this->_constuctObserve( $this->_domId('Dossierpcg66.etatdossierpcg_choice'), $this->_domId('Dossierpcg66.etatdossierpcg'.$first), true );

			$input = $this->Xform->input( 'Dossierpcg66.etatdossierpcg_choice', array( 'label' => 'Filtrer par état du dossier', 'type' => 'checkbox' ) );
				
			$etatsDossiersPCGCoches = Set::extract( $this->data, 'Dossierpcg66.etatdossierpcg' );
			if( empty( $etatsDossiersPCGCoches ) ) {
				$etatsDossiersPCGCoches = array_keys( $etatdossierpcg );
			}		
			
			$input.= $this->Xform->input( 'Dossierpcg66.etatdossierpcg', array( 'label' => 'État du dossier PCG', 'type' => 'select', 'multiple' => 'checkbox', 'options' => $etatdossierpcg, 'value' => $etatsDossiersPCGCoches ) );
		
			return $script . $input; 
			
		}
	
}