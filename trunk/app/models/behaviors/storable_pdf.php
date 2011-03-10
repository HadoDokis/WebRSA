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

		/**
		* Retourne l'enregistrement de la table PDF correspondant au modèle et
		* à la clé primaire donnés. Si le document PDF n'est pas dans l'enregistrement,
		* on essaie de le récupérer sur le serveur CMS.
		* Il est possible de mettre à jour la date d'impression dans la table liée
		* au modèle.
		*
		* @param &$model AppModel Le modèle auquel ce behavior est attaché.
		* @param $id integer La valeur de la clé primaire de l'enregistrement recherché.
		* @param $printDateColumn string La colonne qui contient la date d'impression
		*        devant être mise à jour, null sinon.
		* @return array
		*/

		public function getStoredPdf( &$model, $id, $printDateColumn = null  ) {
			if( !empty( $printDateColumn ) ) {
				$model->updateAll(
					array( "{$model->alias}.{$printDateColumn}" => date( "'Y-m-d'" ) ),
					array(
						"\"{$model->alias}\".\"{$model->primaryKey}\"" => $id,
						"\"{$model->alias}\".\"{$printDateColumn}\" IS NULL"
					)
				);
			}

			$pdf = ClassRegistry::init( 'Pdf' )->find(
				'first',
				array(
					'conditions' => array(
						'Pdf.modele' => $model->alias,
						'Pdf.fk_value' => $id,
					)
				)
			);

			if( !empty( $pdf ) && empty( $pdf['Pdf']['document'] ) ) {
				$cmisPdf = Cmis::read( "/{$model->alias}/{$id}.pdf", true );
				$pdf['Pdf']['document'] = $cmisPdf['content'];
			}

			return $pdf;
		}
	}
?>