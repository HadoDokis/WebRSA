<?php
	class ActioncandidatPersonne extends AppModel
	{
		public $name = 'ActioncandidatPersonne';

		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Actioncandidat' => array(
				'className' => 'Actioncandidat',
				'foreignKey' => 'actioncandidat_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Referent' => array(
				'className' => 'Referent',
				'foreignKey' => 'referent_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Motifsortie' => array(
				'className' => 'Motifsortie',
				'foreignKey' => 'motifsortie_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $actsAs = array (
			'Nullable',
			'ValidateTranslate',
			'Enumerable' => array(
				'fields' => array(
					'enattente' => array(
						'values' => array( 'O', 'N' )
					),
					'bilanvenu' => array(
						'values' => array( 'VEN', 'NVE' ),
						'domain' => 'actioncandidat_personne'
					),
					'bilanretenu' => array(
						'values' => array( 'RET', 'NRE' ),
						'domain' => 'actioncandidat_personne'
					),
					'bilanrecu' => array(
						'values' => array( 'O', 'N' ),
						'domain' => 'actioncandidat_personne'
					),
					'presencecontrat' => array(
						'values' => array( 'O', 'N' ),
						'domain' => 'actioncandidat_personne'
					),
					'pieceallocataire' => array(
						'values' => array( 'CER', 'NCA', 'CV', 'AUT' ),
						'domain' => 'actioncandidat_personne'
					),
					'integrationaction' => array(
						'values' => array( 'O', 'N' ),
						'domain' => 'actioncandidat_personne'
					),
                    'positionfiche' => array(
                        'domain' => 'actioncandidat_personne'
                    )
				)
			),
			'Formattable',
			'Gedooo',
			'Autovalidate'
		);


		public $validate = array(
			'personne_id' => array(
				array( 'rule' => 'notEmpty' )
			),
			'referent_id' => array(
				array( 'rule' => 'notEmpty' )
			),
			'actioncandidat_id' => array(
				array( 'rule' => 'notEmpty' )
			),
//			'enattente'  => array(
//				'rule' => 'notEmpty',
//				'message' => 'Champ obligatoire'
//			),
			'nivetu'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'bilanvenu'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'bilanretenu'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'bilanrecu'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'integrationaction'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'pieceallocataire' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'horairerdvpartenaire' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire',
				'required' => false
			),
			'ddaction' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'motifdemande' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'motifsortie_id' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            )
		);


        /**
        *   BeforeSave
        */

        public function beforeSave( $options = array() ) {
            $return = parent::beforeSave( $options );
            //  Calcul de la position de la fiche de calcul
            $this->data[$this->alias]['positionfiche'] = $this->_calculPosition( $this->data );

            return $return;
        }


        protected function _calculPosition( $data ){

            $bilanrecu = Set::classicExtract( $data, 'ActioncandidatPersonne.bilanvenu' );
            $bilanretenu = Set::classicExtract( $data, 'ActioncandidatPersonne.bilanretenu' );
            $issortie = Set::classicExtract( $data, 'ActioncandidatPersonne.issortie' );

            $positionfiche = null;
            // 'encours', 'attvalid', 'annule', 'fincontrat', 'encoursbilan', 'attrenouv', 'perime'


            if ( empty( $bilanrecu ) && empty( $bilanretenu ) && empty( $motifsortie ) ){
                $positionfiche = 'enattente';
            }
            elseif ( !empty( $bilanrecu ) && ( $bilanretenu == 'NRE' ) && empty( $issortie ) ){
                $positionfiche = 'nonretenue';
            }
            elseif ( !empty( $bilanrecu ) && ( $bilanretenu != 'NRE' ) && empty( $issortie ) ){
                $positionfiche = 'encours';
            }
            elseif ( !empty( $bilanrecu ) && ( $bilanretenu != 'NRE' ) && !empty( $issortie ) ){
                $positionfiche = 'sortie';
            }

            return $positionfiche;
        }

	}
?>