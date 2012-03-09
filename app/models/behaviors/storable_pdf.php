<?php
	/**
	 * Classe StorablePdfBehavior, nécessite le plugin Gedooo.
	 *
	 * Cette classe permet
	 *	- d'automatiser le génération et le stockage de documents PDF à l'enregistrement
	 *	- d'automatiser la suppression de documents PDF stockés à la suppression
	 *	- fournit des fonctions utilitaires permettant de stocker et de récupérer un document PDF
	 *
	 * Il est nécessaire de ne stocker qu'un seul documentdans la table pdfs pour un enregistrement du
	 * modèle lié donné.
	 *
	 * Il est possible de passer une configuration lors de l'attachement du behavior (ici avec les valeurs par
	 * défaut):
	 * $actsAs = array(
	 *	'StorablePdf' => array(
	 *		'afterSave' => 'generatePdf', (valeurs possibles: 'generatePdf', 'deleteAll', null/false)
	 *		'afterDelete' => 'deleteAll', (valeurs possibles: 'deleteAll', null/false)
	 *	)
	 * );
	 *
	 * PHP version 5.3
	 *
	 * @package		app.models.behaviors
	 */
	App::import( 'Behavior', array( 'Gedooo.Gedooo' ) );

	class StorablePdfBehavior extends GedoooBehavior
	{
		/**
		 * Configuration.
		 *
		 * @var array
		 */
		public $settings = array( );

		/**
		 * Valeurs de configuration par défaut.
		 *
		 * @var type
		 */
		public $defaultSettings = array(
			'afterSave' => 'generatePdf',
			'afterDelete' => 'deleteAll',
		);

		/**
		 * Configuration du behavior en fonction du modèle auquel il est attaché.
		 *
		 * @param Model $Model
		 * @param array $settings
		 */
		public function setup( Model &$Model, array $settings ) {
			$this->settings[$Model->name] = Set::merge( $this->defaultSettings, $settings );
		}

		/**
		 * Stocke un PDF (si besoin en écrasant l'ancien enregistrement) dans la table pdfs.
		 *
		 * @param Model $Model
		 * @param integer $id
		 * @param string $modeledoc
		 * @param mixed $pdf
		 * @return boolean
		 */
		public function storePdf( Model &$Model, $id, $modeledoc, $pdf ) {
			$Pdf = ClassRegistry::init( 'Pdf' );

			$oldRecord = $Pdf->find(
				'first',
				array(
					'fields' => array( 'id' ),
					'conditions' => array(
						'modele' => $Model->alias,
						'modeledoc' => $modeledoc,
						'fk_value' => $id
					)
				)
			);

			$oldRecord['Pdf']['modele'] = $Model->alias;
			$oldRecord['Pdf']['modeledoc'] = $modeledoc;
			$oldRecord['Pdf']['fk_value'] = $id;
			$oldRecord['Pdf']['document'] = $pdf;

			$Pdf->create( $oldRecord );
			return $Pdf->save();
		}

		/**
		 * Génère et stocke un PDF pour un enregistrement donné.
		 * Fait appel aux méthodes getDataForPdf et modeleOdt du modèle.
		 *
		 * @param Model $Model
		 * @param integer $id
		 * @return boolean
		 */
		public function generatePdf( Model &$Model, $id ) {
			$success = true;
			$data = $Model->getDataForPdf( $id );

			if( !empty( $data ) ) {
				$modeledoc = $Model->modeleOdt( $data );

				$pdf = $Model->ged( $data, $modeledoc );

				if( $pdf ) {
					$success = $this->storePdf( $Model, $id, $modeledoc, $pdf ) && $success;
				}
				else {
					$success = false;
				}
			}
			else {
				$pdfModel = ClassRegistry::init( 'Pdf' );
				$success = $pdfModel->deleteAll( array( 'modele' => $Model->alias, 'fk_value' => $id ) ) && $success;
			}

			return $success;
		}
		/**
		 * Automatisation de l'enregistrement ou de la suppression du PDF (possibilité de ne pas exécuter d'action).
		 * Le return ne sert à rien: même si on retourne false c'est comme si ça s'était bien passé
		 *
		 * @param Model $Model
		 * @param boolean $created
		 * @return boolean
		 */
		public function afterSave( Model &$Model, $created ) {
			$function = $this->settings[$Model->name][__FUNCTION__];

			if( $function == 'generatePdf' ) {
				return $this->generatePdf( $Model, $Model->id );
			}
			else if( $function == 'deleteAll' ) {
				return ClassRegistry::init( 'Pdf' )->deleteAll( array( 'modele' => $Model->alias, 'fk_value' => $Model->id ) );
			}
			else if( $function == false || is_null( $function ) ) {
				return true;
			}
			else {
				$this->log( "La configuration de ".__FUNCTION__." pour la classe ".__CLASS__." n'est pas correct ( '".var_export( $function, true )."' ).", LOG_ERROR );
				return false;
			}
		}

		/**
		 * Automatisation de la suppression du PDF (possibilité de ne pas exécuter d'action).
		 * Le return ne sert à rien: même si on retourne false c'est comme si ça s'était bien passé
		 *
		 * INFO:
		 * 	- fonctionne avec Model::delete
		 * 	- fonctionne avec Model::deleteAll SSI le paramètre callbacks est à true (false par défaut)
		 *
		 * @param Model $Model
		 * @return boolean
		 */
		public function afterDelete( Model &$Model ) {
			$function = $this->settings[$Model->name][__FUNCTION__];

			if( $function == 'deleteAll' ) {
				return ClassRegistry::init( 'Pdf' )->deleteAll( array( 'modele' => $Model->alias, 'fk_value' => $Model->id ) );
			}
			else if( $function == false || is_null( $function ) ) {
				return true;
			}
			else {
				$this->log( "La configuration de ".__FUNCTION__." pour la classe ".__CLASS__." n'est pas correct ( '".var_export( $function, true )."' ).", LOG_ERROR );
				return false;
			}
		}

		/**
		 * Retourne l'enregistrement de la table PDF correspondant au modèle et
		 * à la clé primaire donnés. Si le document PDF n'est pas dans l'enregistrement,
		 * on essaie de le récupérer sur le serveur CMS.
		 * Il est possible de mettre à jour la date d'impression dans la table liée
		 * au modèle.
		 *
		 * @param &$Model Model Le modèle auquel ce behavior est attaché.
		 * @param $id integer La valeur de la clé primaire de l'enregistrement recherché.
		 * @param $printDateColumn string La colonne qui contient la date d'impression
		 *        devant être mise à jour, null sinon.
		 * @return array
		 */
		public function getStoredPdf( Model &$Model, $id, $printDateColumn = null ) {
			if( !empty( $printDateColumn ) ) {
				$Model->updateAll(
						array( "{$Model->alias}.{$printDateColumn}" => date( "'Y-m-d'" ) ), array(
					"\"{$Model->alias}\".\"{$Model->primaryKey}\"" => $id,
					"\"{$Model->alias}\".\"{$printDateColumn}\" IS NULL"
						)
				);
			}

			$pdf = ClassRegistry::init( 'Pdf' )->find(
					'first', array(
				'conditions' => array(
					'Pdf.modele' => $Model->alias,
					'Pdf.fk_value' => $id,
				)
					)
			);

			if( !empty( $pdf ) && empty( $pdf['Pdf']['document'] ) ) {
				$cmisPdf = Cmis::read( "/{$Model->alias}/{$id}.pdf", true );
				$pdf['Pdf']['document'] = $cmisPdf['content'];
			}

			return $pdf;
		}

	}
?>