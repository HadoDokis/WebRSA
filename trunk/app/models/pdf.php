<?php
	require_once( APPLIBS.'cmis.php' );

	class Pdf extends AppModel
	{
		public $name = 'Pdf';

		public $validate = array(
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
		);

		/**
		* Surcharge de la fonction de sauvegarde pour essayer d'enregistrer le PDF sur Alfresco.
		*/

		public function save( $data = null, $validate = true, $fieldList = array() ) {
			$cmsPath = "/{$this->data[$this->alias]['modele']}/{$this->data[$this->alias]['fk_value']}.pdf";
			$cmsSuccess = Cmis::write( $cmsPath, $this->data[$this->alias]['document'], 'application/pdf', true );

			if( $cmsSuccess ) {
				$this->data[$this->alias]['cmspath'] = $cmsPath;
				$this->data[$this->alias]['document'] = null;
			}

			$success = parent::save( $data, $validate, $fieldList );
			if( !$success && $cmsSuccess ) {
				$success = Cmis::delete( $cmsPath, true ) && $success;
			}

			return $success;
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
					'fields' => array( 'id', 'modele', 'fk_value', 'cmspath' ),
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
					'fields' => array( 'id', 'modele', 'fk_value', 'cmspath' ),
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