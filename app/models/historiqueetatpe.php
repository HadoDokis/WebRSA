<?php
	class Historiqueetatpe extends AppModel
	{
		public $name = 'Historiqueetatpe';

        public $actsAs = array(
            'Enumerable' => array(
                'fields' => array(
                    'etat'
                )
            )
        );

		// FIXME: validation

        public $recursive = -1;

		public $belongsTo = array(
			'Informationpe' => array(
				'className' => 'Informationpe',
				'foreignKey' => 'informationpe_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		/**
		* Retourne une array à utiliser comme jointure entre la table personnes
		* et la table informationspe.
		*
		* @param boolean $dernier Permet de rechercher uniquement l'information la plus récente
		* @param string $aliasInformationpe Alias pour la table informationspe
		* @param string $aliasHistoriqueetatpe Alias pour la table historiqueetatspe
		* @param string $type Type de jointure à effectuer
		* @return array
		*/

		public function joinInformationpeHistoriqueetatpe( $dernier = true, $aliasInformationpe = 'Informationpe', $aliasHistoriqueetatpe = 'Historiqueetatpe', $type = 'LEFT OUTER' ) {
			$join = array(
				'table'      => 'historiqueetatspe',
				'alias'      => $aliasHistoriqueetatpe,
				'type'       => $type,
				'foreignKey' => false,
				'conditions' => array(
					"{$aliasHistoriqueetatpe}.informationpe_id = {$aliasInformationpe}.id",
				)
			);

			if( $dernier ) {
				$join['conditions'][] = "{$aliasHistoriqueetatpe}.id IN (
					SELECT
							historiqueetatspe.id
						FROM historiqueetatspe
						WHERE historiqueetatspe.informationpe_id = {$aliasInformationpe}.id
						ORDER BY historiqueetatspe.date DESC
						LIMIT 1
				)"; 
			}

			return $join;
		}

		/**
		* Retourne une condition sur l'identifiant Pôle Emploi de la table historiqueetatspe
		* Si Recherche.identifiantpecourt est configuré à true, on ne compare que sur
		* les 8 derniers caractères, sinon on fait une comparaison normale.
		* La comparaison se fait sur la mise en majuscule dans tous les cas.
		*
		* @param string $identifiantpe L'identifiant Pôle Emploi qui est recherché
		* @param string $aliasHistoriqueetatpe Alias pour la table historiqueetatspe
		* @return string
		*/

		public function conditionIdentifiantpe( $identifiantpe, $aliasHistoriqueetatpe = 'Historiqueetatpe' ) {
			if( !empty( $identifiantpe ) ) {
				if( Configure::read( 'Recherche.identifiantpecourt' ) ) {
					return "SUBSTRING(UPPER({$aliasHistoriqueetatpe}.identifiantpe) FROM 4 FOR 8) = '".strtoupper( Sanitize::clean( $identifiantpe ) )."'";
				}
				else {
					return "UPPER({$aliasHistoriqueetatpe}.identifiantpe) = '".strtoupper( Sanitize::clean( $identifiantpe ) )."'";
				}
			}
		}
	}
?>