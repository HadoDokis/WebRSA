<?php
	/**
	* FIXME: ne fonctionne actuellement qu'avec traitementspdos_controller car les URL sont en dur.
	*/

	class FileuploaderHelper extends AppHelper
	{
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
						function addAjaxUploadedFileLinks( elmt ) {
							var link = new Element( \'a\', { href: \''.Router::url( array( 'action' => 'ajaxfiledelete', $this->action, $this->params['pass'][0] ), true ).'\' + \'/\' + $( elmt ).innerHTML } ).update( "Supprimer" );
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

							link = new Element( \'a\', { href: \''.Router::url( array( 'action' => 'fileview', $this->action, $this->params['pass'][0] ), true ).'\' + \'/\' + $( elmt ).innerHTML } ).update( "Voir" );
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
									primaryKey: \''.$this->params['pass'][0].'\'
								},
								onComplete: function( id, fileName, responseJSON ) {
									$$( \'.qq-upload-file\' ).each( function( elmt ) {
										if( elmt.innerHTML == fileName ) {
											addAjaxUploadedFileLinks( elmt );
										}
									} );
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
					</script>';
		}
	}
?>