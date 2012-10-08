<?php
	class Detaildroitrsa extends AppModel
	{
		public $name = 'Detaildroitrsa';

		public $validate = array(
			'topsansdomfixe' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'dtoridemrsa' => array(
				array(
					'rule' => 'date',
					'message' => 'Veuillez vérifier le format de la date.'
				),
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),

			'topfoydrodevorsa' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'nbenfautcha' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'oridemrsa' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'ddelecal' => array(
				array(
					'rule' => 'date',
					'message' => 'Veuillez vérifier le format de la date.'
				),
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'dfelecal' => array(
				array(
					'rule' => 'date',
					'message' => 'Veuillez vérifier le format de la date.'
				),
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			)
		);

		public $belongsTo = array(
			'Dossier' => array(
				'className' => 'Dossier',
				'foreignKey' => 'dossier_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasMany = array(
			'Detailcalculdroitrsa' => array(
				'className' => 'Detailcalculdroitrsa',
				'foreignKey' => 'detaildroitrsa_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Dossier' => array(
				'className' => 'Dossier',
				'foreignKey' => 'detaildroitrsa_id',
				'dependent' => false,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			)
		);
		
		/**
		*	Vérfication et envoi d'un booleen si le dossier est un RSA majoré ou non
		*	On passe en paramètre l'alias du modèle et du champ
		*/

		public function vfRsaMajore( $aliasDossierId = '"Dossier"."id"' ){
			return 'EXISTS(
				SELECT * FROM detailsdroitsrsa
					INNER JOIN detailscalculsdroitsrsa ON ( detailscalculsdroitsrsa.detaildroitrsa_id = detailsdroitsrsa.id )
					WHERE
						detailsdroitsrsa.dossier_id = '.$aliasDossierId.'
						AND detailscalculsdroitsrsa.natpf IN ( \'RCI\', \'RSI\' )
			)';
		}

				
		/**
		*	Vérfication et envoi d'un booleen si le dossier est un RSA socle ou non
		*	On passe en paramètre l'alias du modèle et du champ
		*/

		public function vfRsaSocle( $aliasDossierId = '"Dossier"."id"' ){
			return 'EXISTS(
				SELECT * FROM detailsdroitsrsa
					INNER JOIN detailscalculsdroitsrsa ON ( detailscalculsdroitsrsa.detaildroitrsa_id = detailsdroitsrsa.id )
					WHERE
						detailsdroitsrsa.dossier_id = '.$aliasDossierId.'
						AND detailscalculsdroitsrsa.natpf IN ( \'RSB\', \'RSD\', \'RSI\', \'RSU\' )
			)';
		}
	}
?>
