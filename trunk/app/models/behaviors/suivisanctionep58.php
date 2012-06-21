<?php
	class Suivisanctionep58Behavior extends ModelBehavior
	{
		/**
		 *
		 */
		public function suivisanctions58( &$Model, $data, $dataPath = 'Decision.0' ) {
			$return = array(
				'decision1' => array(
					'decision' => null,
					'sanction' => null,
					'duree' => null,
					'dd' => null,
					'df' => null,
					'etat' => null,
				),
				'decision2' => array(
					'decision' => null,
					'sanction' => null,
					'duree' => null,
					'dd' => null,
					'df' => null,
					'etat' => null,
				),
			);
			
			$dataDecision = Set::classicExtract( $data, $dataPath );

			if( $dataDecision[$Model->alias]['decision'] == 'sanction' ) {
				$return['decision1']['decision'] = 'Sanction 1';
				$return['decision1']['sanction'] = $dataDecision['Listesanctionep58']['sanction'];
				$return['decision1']['duree'] = $dataDecision['Listesanctionep58']['duree'];
				$return['decision1']['dd'] = date( 'Y-m-d', strtotime( $data['Commissionep']['dateseance'] ) );

				if( $dataDecision[$Model->alias]['arretsanction'] == 'finsanction1' ) {
					$return['decision1']['etat'] = 'Fin de sanction';
					$return['decision1']['df'] = $dataDecision[$Model->alias]['datearretsanction'];
				}
				else if( $dataDecision[$Model->alias]['arretsanction'] == 'annulation1' ) {
					$return['decision1']['etat'] = 'Annulé';
				}
				else {
					$return['decision1']['etat'] = 'En cours';
				}
			}
			
			if( $dataDecision[$Model->alias]['decision2'] == 'sanction' ) {
				$return['decision2']['decision'] = 'Sanction 2';
				$return['decision2']['sanction'] = $dataDecision['Autrelistesanctionep58']['sanction'];
				$return['decision2']['duree'] = $dataDecision['Autrelistesanctionep58']['duree'];
				$return['decision2']['dd'] = date( 'Y-m-d', strtotime( "+{$return['decision1']['duree']} month", strtotime( $return['decision1']['dd'] ) ) );

				if( $dataDecision[$Model->alias]['arretsanction'] == 'finsanction2' ) {
					$return['decision2']['etat'] = 'Fin de sanction';
					$return['decision2']['df'] = $dataDecision[$Model->alias]['datearretsanction'];
				}
				else if( $dataDecision[$Model->alias]['arretsanction'] == 'annulation2' ) {
					$return['decision2']['etat'] = 'Annulé';
				}
				else if( in_array( $return['decision1']['etat'], array( 'Fin de sanction', 'Annulé' ) ) ) {
					$return['decision2']['etat'] = 'Annulé';
				}
				else {
					$return['decision2']['etat'] = 'En cours';
				}
			}

			$return = array(
				array( $Model->alias => $return['decision1'] ),
				array( $Model->alias => $return['decision2'] ),
			);
			
			for( $i = 0 ; $i <= 1 ; $i++ ) {
				if( empty( $return[$i][$Model->alias]['decision'] ) ) {
					unset( $return[$i] );
				}
			}
			
			return $return;
		}
	}
?>