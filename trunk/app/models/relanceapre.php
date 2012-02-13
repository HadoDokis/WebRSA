<?php
	class Relanceapre extends AppModel
	{
		public $name = 'Relanceapre';

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'etatdossierapre' => array( 'type' => 'etatdossierapre', 'domain' => 'apre' )
				)
			)
		);

		public $validate = array(
			'etatdossierapre' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'apre_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
		);

		public $belongsTo = array(
			'Apre' => array(
				'className' => 'Apre',
				'foreignKey' => 'apre_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		/**
		*
		*/

		public function afterFind( $results, $primary = false ) {
			$resultset = parent::afterFind( $results, $primary );

			if( !empty( $resultset ) ) {
				foreach( $resultset as $i => $results ) {

					$isArray = true;
					if( isset( $results['Relanceapre']['id'] ) ) {
						$results['Relanceapre'] = array( $results['Relanceapre'] );
						$isArray = false;
					}

					foreach( $results['Relanceapre'] as $key => $result ) {
						$conditions = array();
						if( isset( $result['apre_id'] ) &&  !empty( $result['apre_id'] ) ) {
							$conditions = array( 'AprePieceapre.apre_id' => $result['apre_id'] );
						}

						$piecesPresentes = $this->Apre->AprePieceapre->find(
							'all',
							array(
								'conditions' => $conditions,
								'recursive' => -1
							)
						);

						$conditions = array();
						$piecesApreIds = Set::extract( $piecesPresentes, '/AprePieceapre/pieceapre_id' );
						if( !empty( $piecesApreIds ) ) {
							$conditions = array( 'NOT' => array( 'Pieceapre.id' => $piecesApreIds ) );
						}
						$piecesAbsentes = $this->Apre->Pieceapre->find( 'all', array( 'conditions' => $conditions, 'recursive' => -1 ) );

						$results['Relanceapre'][$key]['Piecemanquante'] = Set::classicExtract( $piecesAbsentes, '{n}.Pieceapre' );
					}

					if( !$isArray ) {
						$results['Relanceapre'] = $results['Relanceapre'][0];
					}

					$resultset[$i] = $results;
				}
			}

			return $resultset;
		}
	}
?>