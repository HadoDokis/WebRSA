<?php
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
			require_once( APPLIBS.'cmis.php' );

			$cmsPath = "/{$this->data[$this->alias]['modele']}/{$this->data[$this->alias]['fk_value']}.pdf";
			$success = Cmis::write( $cmsPath, $this->data[$this->alias]['document'], 'application/pdf' );

			if( $success ) {
				$this->data[$this->alias]['cmspath'] = $cmsPath;
				unset( $this->data[$this->alias]['document'] );
			}

			return parent::save( $data, $validate, $fieldList );
		}
	}
?>