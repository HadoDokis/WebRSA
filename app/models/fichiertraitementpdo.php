<?php
	require_once( APPLIBS.'cmis.php' );

	class Fichiertraitementpdo extends AppModel
	{
		public $name = 'Fichiertraitementpdo';

		public $actsAs = array(
			'Enumerable' => array( 'fields' => array( 'type', ) ),
			'Autovalidate'
		);

		public $belongsTo = array(
			'Traitementpdo' => array(
				'className' => 'Traitementpdo',
				'foreignKey' => 'traitementpdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		/*public $validate = array(
			'modele' => array(
				'notempty' => array(
					'rule' => array('notempty'),
				),
			),
			'modeledoc' => array(
				'notempty' => array(
					'rule' => array('notempty'),
				),
			),
			'fk_value' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
		);*/

		/**
		* Surcharge de la fonction de sauvegarde pour essayer d'enregistrer le PDF sur Alfresco.
		*/

		public function save( $data = null, $validate = true, $fieldList = array() ) {
			$cmsPath = "/{$this->alias}/{$this->data[$this->alias]['traitementpdo_id']}/{$this->data[$this->alias]['name']}";
			$cmsSuccess = Cmis::write( $cmsPath, $this->data[$this->alias]['document'], $this->data[$this->alias]['mime'], true );

			if( $cmsSuccess ) {
				$this->data[$this->alias]['cmspath'] = $cmsPath;
				$this->data[$this->alias]['document'] = null;
			}

			$success = parent::save( $data, $validate, $fieldList );
			if( !$success && $cmsSuccess ) {
				$cmsSuccess = Cmis::delete( $cmsPath, true );
			}

			return ( $success && $cmsSuccess );
		}

		/**
		*
		*/

		public function delete( $id = NULL, $cascade = true ) {
			$conditions = array();
			if( empty( $id ) && !empty( $this->id ) ) {
				$conditions["{$this->alias}.{$this->primaryKey}"] = $this->id;
			}
			else {
				$conditions["{$this->alias}.{$this->primaryKey}"] = $id;
			}

			$records = $this->find(
				'all',
				array(
					'fields' => array( 'id', 'name', 'cmspath' ),
					'conditions' => $conditions
				)
			);

			$success = parent::delete( $id, $cascade );
			$cmspaths = Set::filter( Set::extract( $records, "/{$this->alias}/cmspath" ) );

			if( $success && !empty( $cmspaths ) ) {
				foreach( $cmspaths as $cmspath ) {
					$success = Cmis::delete( $cmspath, true ) && $success;
				}
			}

			return $success;
		}

		/**
		*
		*/

		public function deleteAll( $conditions, $cascade = true, $callbacks = false ) {
			$records = $this->find(
				'all',
				array(
					'fields' => array( 'id', 'name', 'cmspath' ),
					'conditions' => $conditions
				)
			);

			$success = parent::deleteAll( $conditions, $cascade, $callbacks );
			$cmspaths = Set::filter( Set::extract( $records, "/{$this->alias}/cmspath" ) );

			if( $success && !empty( $cmspaths ) ) {
				foreach( $cmspaths as $cmspath ) {
					$success = Cmis::delete( $cmspath, true ) && $success;
				}
			}

			return $success;
		}
	}
?>