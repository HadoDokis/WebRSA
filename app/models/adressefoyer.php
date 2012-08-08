<?php
	class Adressefoyer extends AppModel
	{
		public $name = 'Adressefoyer';
		public $order = array( '"Adressefoyer"."rgadr" ASC' );

		//*********************************************************************

		/**
			Associations
		*/
		public $belongsTo = array(
			'Adresse' => array(
				'className'     => 'Adresse',
				'foreignKey'    => 'adresse_id'
			),
			'Foyer' => array(
				'className'     => 'Foyer',
				'foreignKey'    => 'foyer_id'
			)
		);

		//*********************************************************************

		/**
			Validation ... TODO
		*/
		public $validate = array(
			'rgadr' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'typeadr' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
		);

		//*********************************************************************

		public function dossierId( $adressefoyer_id ) {
			$adressefoyer = $this->findById( $adressefoyer_id, null, null, 0 );
			$adressefoyer = $this->find( 'first', array( 'conditions' => array( 'Adressefoyer.id' => $adressefoyer_id ), 'recursive' => 0 ) );
			if( !empty( $adressefoyer ) ) {
				return $adressefoyer['Foyer']['dossier_id'];
			}
			else {
				return null;
			}
		}

		/**
		* Foyers avec plusieurs adressesfoyers.rgadr = 01
		* donc on s'assure de n'en prendre qu'un seul dont la dtemm est la plus récente
		*/

		public function sqDerniereRgadr01($field) {
			$dbo = $this->getDataSource( $this->useDbConfig );
			$table = $dbo->fullTableName( $this, false );
			return "
				SELECT {$table}.id
					FROM {$table}
					WHERE
						{$table}.foyer_id = ".$field."
						AND {$table}.rgadr = '01'
					ORDER BY {$table}.dtemm DESC
					LIMIT 1
			";
		}

		/**
		*   Fonction permettant de modifier le rang des adresses d'un foyer:
		*       - Les adresses de rang 01 passent en rang 02
		*       - Les adresses de rang 02 passent en rang 03
		*       - Les adresses de rang 03 sont supprimées
		*       - Les nouvelles adresses sont insérées avec un rang 01
		*/


		public function saveNouvelleAdresse( $datas ){
			$foyer_id = $datas['Adressefoyer']['foyer_id'];

			$success = $this->deleteAll(
				array(
					"\"{$this->alias}\".\"foyer_id\"" => $foyer_id,
					"\"{$this->alias}\".\"rgadr\"" => '03'
				)
			);

			foreach( array( '02' => '03', '01' => '02' ) as $oldRg => $newRg ) {
				$adrtmp = $this->find(
					'first',
					array(
						'conditions' => array(
							"{$this->alias}.foyer_id" => $foyer_id,
							"{$this->alias}.rgadr" => $oldRg
						),
						'contain' => false
					)
				);

				if( !empty( $adrtmp ) ) {
					$adrtmp[$this->alias]['rgadr'] = $newRg;
					$this->create( $adrtmp );
					$success = $this->save() && $success;
				}
			}

			$datas[$this->alias]['rgadr'] = '01';

			return $this->saveAll( $datas, array( 'atomic' => false ) ) && $success;
		}
	}
?>