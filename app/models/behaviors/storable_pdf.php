<?php
	/**
	* StorablePdf behavior class.
	*
	* ...
	*
	* PHP version 5
	*
	* @package		app
	* @subpackage	app.app.models.behaviors
	*/

	App::import( 'Behavior', array( 'Gedooo' ) );

	/**
	* ...
	*
	* @package		app
	* @subpackage	app.app.model.behaviors
	*/

	class StorablePdfBehavior extends GedoooBehavior // FIXME: extends Gedooo ?
	{
		/**
		* FIXME: afterDelete ...
		*/

		public function generatePdf( &$model, $id ) {
			$success = true;
			$data = $model->getDataForPdf( $id );

			if( !empty( $data ) ) {
				$modeledoc = $model->modeleOdt( $data );

				$pdf = $model->ged( $data, $modeledoc );

				if( $pdf ) {
					$pdfModel = ClassRegistry::init( 'Pdf' );

					$oldRecord = $pdfModel->find(
						'first',
						array(
							'fields' => array( 'id' ),
							'conditions' => array(
								'modele' => $model->alias,
								'modeledoc' => $modeledoc,
								'fk_value' => $id
							)
						)
					);

					$oldRecord['Pdf']['modele'] = $model->alias;
					$oldRecord['Pdf']['modeledoc'] = $modeledoc;
					$oldRecord['Pdf']['fk_value'] = $id;
					$oldRecord['Pdf']['document'] = $pdf;

					$pdfModel->create( $oldRecord );
					$success = $pdfModel->save() && $success;
				}
				else {
					$success = false;
				}
			}
			else {
				$pdfModel = ClassRegistry::init( 'Pdf' );
				$success = $pdfModel->deleteAll( array( 'modele' => $model->alias, 'fk_value' => $id ) ) && $success;
			}

			return $success;
		}

		/**
		* Enregistrement du Pdf
		* FIXME: le return ne sert à rien: même si on retourne false c'est comme si ça s'était bien passé
		*/

		public function afterSave( &$model, $created ) {
			return $model->generatePdf( $model->id );
		}

		/**
		* INFO:
		*	- fonctionne avec Model::delete
		*	- fonctionne avec Model::deleteAll SSI le paramètre callbacks est à true (false par défaut)
		*/

		public function afterDelete( &$model ) {
			return ClassRegistry::init( 'Pdf' )->deleteAll( array( 'modele' => $model->alias, 'fk_value' => $model->id ) );
		}
	}
?>