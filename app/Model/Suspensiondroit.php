<?php
	class Suspensiondroit extends AppModel
	{
		public $name = 'Suspensiondroit';

		public $belongsTo = array(
			'Situationdossierrsa' => array(
				'className' => 'Situationdossierrsa',
				'foreignKey' => 'situationdossierrsa_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		/**
		 * Retourne une sous-requête permettant d'avoir la dernière entrée de la table (avec un tri
		 * descendant sur ddsusdrorsa), pour une entrée de situationsdossiersrsa.
		 * Fonctionne si une entrée existe pour Situationdossierrsa ou pas.
		 *
		 * @param string $situationdossierrsaId
		 * @return string
		 */
		public function sqDerniere( $situationdossierrsaId = 'Situationdossierrsa.id' ) {
			$dbo = $this->getDataSource( $this->useDbConfig );
			$table = $dbo->fullTableName( $this, false );

			return $this->sq(
				array(
					'alias' => $table,
					'fields' => array( "{$table}.id" ),
					'contain' => false,
					'conditions' => array(
						"{$table}.situationdossierrsa_id = {$situationdossierrsaId}"
					),
					'order' => array( "{$table}.ddsusdrorsa DESC" ),
					'limit' => 1
				)
			);
		}
	}
?>
