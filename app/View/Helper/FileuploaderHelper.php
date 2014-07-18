<?php
	/**
	 * Fichier source de la classe FileuploaderHelper.
	 *
	 * PHP 5.3
	 *
	 * @package app.View.Helper
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe FileuploaderHelper ...
	 *
	 * @package app.View.Helper
	 */
	class FileuploaderHelper extends AppHelper
	{
		/**
		*
		*/
		public $helpers = array( 'Xhtml', 'Locale', 'Form', 'Default2', 'Permissions' );

		/**
		* $urls = array(
		* 	'upload' => null,
		* 	'view' => null,
		* 	'delete' => null,
		* )
		*
		* uploadparams <--
		*/

		public function create( $oldfiles, $ajaxurl ) {
			if( is_array( $ajaxurl ) ) {
				$ajaxurl = Router::url( $ajaxurl );
			}

			$tmp = "\n";
			if( !empty( $oldfiles ) ) {
				foreach( $oldfiles as $oldfile ) {
					$tmp .= 'li = new Element( \'li\', {} );
						$( li ).insert( { bottom: new Element( \'span\', { class: \'qq-upload-file\' } ).update( "'.h( $oldfile ).'" ) } );
						$( li ).insert( { bottom: new Element( \'span\', { class: \'qq-upload-size\' } ) } );
						$( ul ).insert( { bottom: li } );
						addAjaxUploadedFileLinks( $( li ).down( \'span.qq-upload-file\' ) );'."\n";
				}
			}

			return '<div id="file-uploader-piecejointe">
						<noscript>
							<p>Please enable JavaScript to use file uploader.</p>
						</noscript>
					</div>

					<script type="text/javascript">
						// <![CDATA[
						function addAjaxUploadedFileLinks( elmt, fileName ) {
							if( typeof fileName === \'undefined\' ) {
								fileName = $( elmt ).innerHTML;
							}

							var link = new Element( \'a\', { href: \''.Router::url( array( 'action' => 'ajaxfiledelete', $this->action, @$this->request->params['pass'][0] ) ).'\' + \'/\' + fileName } ).update( "Supprimer" );
							Event.observe( link, \'click\', function(e){
								Event.stop(e);
								new Ajax.Request(
									$(Event.element(e)).getAttribute(\'href\'),
									{
										method: \'post\',
										onComplete: function( transport ) {
											try {
												response = eval( "(" + transport.responseText + ")" );
											} catch(err){
												response = {};
											}

											if( response.success && response.success == true ) {
												$( elmt ).up( \'li\' ).remove();
											}
											else {
												alert( \'Erreur!\' );
											}
										}
									}
								);
							} );

							$( elmt ).up( \'li\' ).insert( { bottom: link } );

							link = new Element( \'a\', { href: \''.Router::url( array( 'action' => 'fileview', $this->action, @$this->request->params['pass'][0] ) ).'\' + \'/\' + fileName } ).update( "Voir" );
							$( elmt ).up( \'li\' ).insert( { bottom: link } );
						}

						function createUploader( container ){
							new qq.FileUploader( {
								element: document.getElementById( container ),
								action: \''.$ajaxurl.'\',
								debug: false,
								multiple: false,
								params: {
									action: \''.$this->action.'\',
									primaryKey: \''.@$this->request->params['pass'][0].'\'
								},
								onComplete: function( id, fileName, responseJSON ) {
									// 1°) Suppression des messages flash précédents
									$$( \'#\' + container + \' > p.error\', \'#\' + container + \' > p.success\' ).each( function( old ) {
										$(old).remove();
									} );

									var message = \'Erreur inattendue\';
									var className = \'error\';

									// 2°) Traitement du retour de l\'appel ajax
									// 2° 1°) Succès
									if( typeof responseJSON.success !== \'undefined\' && responseJSON.success === true ) {
										// Il s\'agit toujours du dernier élément de la liste, id n\'est pas fiable lorsqu\'on supprime un élément
										var spans = $$( \'.qq-upload-file\' );
										addAjaxUploadedFileLinks( spans[$(spans).length-1], fileName );

										message = \'Fichier &laquo; \' + fileName + \' &raquo; transmis.\';
										className = \'success\';
									}
									// 2° 2°) Erreur
									else if( typeof responseJSON.error !== \'undefined\' ) {
										message = \'Fichier &laquo; \' + fileName + \' &raquo; non transmis: \' + responseJSON.error;
										className = \'error\';
									}

									$( container ).insert( { top: new Element( \'p\', { \'class\': className } ).update( message ) } );
								},
								template: \'<div class="qq-uploader">\' +
										\'<div class="qq-upload-drop-area"><span>Drop files here to upload</span></div>\' +
										\'<div class="qq-upload-button">Parcourir</div>\' +
										\'<ul class="qq-upload-list"></ul>\' +
									\'</div>\',
							} );
						}

						document.observe( "dom:loaded", function() {
							createUploader( \'file-uploader-piecejointe\' );
							var ul = $( \'file-uploader-piecejointe\' ).down( \'ul.qq-upload-list\' );
							'.$tmp.'
						} );
					// ]]>
					</script>';
		}

		/**
		*
		*/
		public function results( $files ) {
			$return = '';
			if( !empty( $files ) ){
				$return .=  '<table class="aere"><tbody>';
				$return .=  '<tr><th>Nom de la pièce jointe</th><th>Date d\'ajout</th><th colspan="2">Action</th></tr>';
				foreach( $files as $i => $fichier ){
					$return .= '<tr><td>'.$fichier['name'].'</td>';
					$return .= '<td>'.$this->Locale->date( __( 'Locale->datetime' ), $fichier['created'] ).'</td>';
					$return .= '<td>'.$this->Xhtml->link(
						'Télécharger',
						array( 'action' => 'download', $fichier['id'] ),
						array( 'enabled' => $this->Permissions->checkDossier( $this->request->params['controller'], 'download', (array)Hash::get( $this->_View->viewVars, 'dossierMenu' ) ), )
					).'</td>';
					$return .= '<td>'.$this->Xhtml->link(
						'Supprimer',
						array( 'controller' => 'fichiersmodules', 'action' => 'delete', $fichier['id'] ),
						array( 'enabled' => $this->Permissions->checkDossier( 'fichiersmodules', 'delete', (array)Hash::get( $this->_View->viewVars, 'dossierMenu' ) ) ),
						'Êtes-vous sûr de vouloir supprimer la pièce ?'
						).'</td></tr>';
				}
				$return .= '</tbody></table>';
			}
			else{
				$return .= '<p class="notice aere">Aucun élément.</p>';
			}
			return $return;
		}

		/**
		 * INFO: si le radio "Ajouter ..." est à false, alors lorsqu'on enregistre et qu'on avait des pièces, on les perd toutes
		 *
		 * @param string $modelName
		 * @param array $fichiers
		 * @param array $datas
		 * @param array $radioOptions
		 * @return string
		 */
		public function element( $modelName, $fichiers, $datas, $radioOptions ) {
			$formId = strtolower( $modelName ).'form';
			$fieldName = "{$modelName}.haspiecejointe";
			$datasFichiermodule = Set::classicExtract( $datas, 'Fichiermodule' );
			$haspiecejointeDefault = ( ( count( $fichiers ) + count( $datasFichiermodule ) ) > 0 );

			$permissionForm = $this->Permissions->checkDossier( $this->request->params['controller'], 'ajaxfileupload', (array)Hash::get( $this->_View->viewVars, 'dossierMenu' ) );

//			$return = $this->Form->create( $modelName, array( 'type' => 'post', 'id' => $formId ) );
			$return = $this->Form->create( $modelName, array( 'type' => 'post', 'id' => $formId/**/ ) );
			if( $permissionForm ) {
				$return .= '<fieldset><legend>'.required( $this->Default2->label( $fieldName ) ).'</legend>';
				$return .= $this->Form->input( $fieldName, array( 'type' => 'radio', 'options' => $radioOptions, 'legend' => false, 'default' => $haspiecejointeDefault ) );
				$return .= '<fieldset id="filecontainer-piecejointe" class="noborder invisible">'
					.$this->create(
						$fichiers,
						Router::url( array( 'action' => 'ajaxfileupload' ) )
					)
				.'</fieldset></fieldset>
				<script type="text/javascript">
				// <![CDATA[
					document.observe( "dom:loaded", function() {
						observeDisableFieldsetOnRadioValue(
							\''.$formId.'\',
							\'data['.$modelName.'][haspiecejointe]\',
							$( \'filecontainer-piecejointe\' ),
							\'1\',
							false,
							true
						);
					} );
				// ]]>
				</script>';
			}

			$return .= '<h2>Pièces déjà présentes</h2>'
			.$this->results( $datasFichiermodule );

			$return .= '<div class="submit">';
			if( $permissionForm ) {
				$return .= $this->Form->submit( 'Enregistrer', array( 'div'=>false ) );
			}
			$return .= $this->Form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) )
			.'</div>'
			.$this->Form->end();

			return $return;
		}
	}
?>