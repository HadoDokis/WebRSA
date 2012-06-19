<?php
	require_once( APPLIBS.'cmis.php' );

	class Fichiermodule extends AppModel
	{
		/**
		*
		*/

		public $recursive = -1;

		public $name = 'Fichiermodule';

		public $actAs = array(
			'Formattable',
			'Autovalidate'
		);

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

		public $belongsTo = array(
			'Traitementpdo' => array(
				'className' => 'Traitementpdo',
				'foreignKey' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'Traitementpdo\'',
					'Fichiermodule.fk_value = {$__cakeID__$}'
				),
				'fields' => '',
				'order' => ''
			),
			'Propopdo' => array(
				'className' => 'Propopdo',
				'foreignKey' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'Propopdo\'',
					'Fichiermodule.fk_value = {$__cakeID__$}'
				),
				'fields' => '',
				'order' => ''
			),
			'Orientstruct' => array(
				'className' => 'Orientstruct',
				'foreignKey' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'Orientstruct\'',
					'Fichiermodule.fk_value = {$__cakeID__$}'
				),
				'fields' => '',
				'order' => ''
			),
			'Rendezvous' => array(
				'className' => 'Rendezvous',
				'foreignKey' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'Rendezvous\'',
					'Fichiermodule.fk_value = {$__cakeID__$}'
				),
				'fields' => '',
				'order' => ''
			),
			'Bilanparcours66' => array(
				'className' => 'Bilanparcours66',
				'foreignKey' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'Bilanparcours66\'',
					'Fichiermodule.fk_value = {$__cakeID__$}'
				),
				'fields' => '',
				'order' => ''
			),
			'Contratinsertion' => array(
				'className' => 'Contratinsertion',
				'foreignKey' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'Contratinsertion\'',
					'Fichiermodule.fk_value = {$__cakeID__$}'
				),
				'fields' => '',
				'order' => ''
			),
			'DspRev' => array(
				'className' => 'DspRev',
				'foreignKey' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'Dsp\'',
					'Fichiermodule.fk_value = {$__cakeID__$}'
				),
				'fields' => '',
				'order' => ''
			),
			'PersonneReferent' => array(
				'className' => 'PersonneReferent',
				'foreignKey' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'PersonneReferent\'',
					'Fichiermodule.fk_value = {$__cakeID__$}'
				),
				'fields' => '',
				'order' => ''
			),
			'Entretien' => array(
				'className' => 'Entretien',
				'foreignKey' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'Entretien\'',
					'Fichiermodule.fk_value = {$__cakeID__$}'
				),
				'fields' => '',
				'order' => ''
			),
			'Apre' => array(
				'className' => 'Apre',
				'foreignKey' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'Apre\'',
					'Fichiermodule.fk_value = {$__cakeID__$}'
				),
				'fields' => '',
				'order' => ''
			),
			'Apre66' => array(
				'className' => 'Apre66',
				'foreignKey' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'Apre66\'',
					'Fichiermodule.fk_value = {$__cakeID__$}'
				),
				'fields' => '',
				'order' => ''
			),
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'Personne\'',
					'Fichiermodule.fk_value = {$__cakeID__$}'
				),
				'fields' => '',
				'order' => ''
			),
			'ActioncandidatPersonne' => array(
				'className' => 'ActioncandidatPersonne',
				'foreignKey' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'ActioncandidatPersonne\'',
					'Fichiermodule.fk_value = {$__cakeID__$}'
				),
				'fields' => '',
				'order' => ''
			),
			'Dossierpcg66' => array(
				'className' => 'Dossierpcg66',
				'foreignKey' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'Dossierpcg66\'',
					'Fichiermodule.fk_value = {$__cakeID__$}'
				),
				'fields' => '',
				'order' => ''
			),
			'Traitementpcg66' => array(
				'className' => 'Traitementpcg66',
				'foreignKey' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'Traitementpcg66\'',
					'Fichiermodule.fk_value = {$__cakeID__$}'
				),
				'fields' => '',
				'order' => ''
			),
			'Actioncandidat' => array(
				'className' => 'Actioncandidat',
				'foreignKey' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'Actioncandidat\'',
					'Fichiermodule.fk_value = {$__cakeID__$}'
				),
				'fields' => '',
				'order' => ''
			),
			'Nonoriente66' => array(
				'className' => 'Nonoriente66',
				'foreignKey' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'Nonoriente66\'',
					'Fichiermodule.fk_value = {$__cakeID__$}'
				),
				'fields' => '',
				'order' => ''
			),
		);

		/**
		* Surcharge de la fonction de sauvegarde pour essayer d'enregistrer le PDF sur Alfresco.
		*/

		public function save( $data = null, $validate = true, $fieldList = array() ) {
			$cmsPath = "/{$this->data[$this->alias]['modele']}/{$this->data[$this->alias]['fk_value']}/{$this->data[$this->alias]['name']}";
			$cmsSuccess = Cmis::write( $cmsPath, $this->data[$this->alias]['document'], $this->data[$this->alias]['mime'], true );

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