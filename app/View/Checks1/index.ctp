<?php
	$this->pageTitle = 'Vérification de l\'application';

	function booleanIcon( $xhtml, $value ) {
		return $this->Xhtml->image( 'icons/'.( $value ? 'tick.png' : 'cross.png' ) ).' ';
	}

	$iconTrue = booleanIcon( $xhtml, true );
	$iconFalse = booleanIcon( $xhtml, false );
?>
<div>
	<h1><?php echo $this->pageTitle;?></h1>
	<h2>Paramétrage de l'application</h2>
	<table>
		<tr>
			<td>Durée du timeout</td>
			<td><?php echo sec2hms( readTimeout(), true );?></td>
		</tr>
		<tr>
			<td>Paramétrage actuellement utilisé</td>
			<td>
				<?php if( Configure::read( 'Session.save' ) == 'php' ):?>
				<code>session.gc_maxlifetime</code> dans le <code>php.ini</code> (valeur actuelle: <em><?php echo ini_get( 'session.gc_maxlifetime' );?></em> secondes)
				<?php elseif( Configure::read( 'Session.save' ) == 'cake' ):?>
				<code>Configure::write( 'Session.timeout', '<?php echo Configure::read( 'Session.timeout' );?>' )</code> dans <code>app/config/core.php</code><br/>
				<code>Configure::write( 'Security.level', '<?php echo Configure::read( 'Security.level' );?>' )</code> dans <code>app/config/core.php</code>
				<?php endif;?>
			</td>
		</tr>
	</table>

	<?php if( Configure::read() ):?>
	<?php endif;?>

	<h2>Test de l'application</h2>
	<div>
		<table>
			<h3>Paramétrage du php.ini ?</h3>
			<table>
				<tbody>
					<?php foreach( $checkInis as $param => $value ):?>
					<tr>
						<td><?php echo $param;?></td>
						<td><?php echo booleanIcon( $xhtml, $value ).( $value ? 'Oui' : 'Non' );?></td>
					</tr>
					<?php endforeach;?>
				</tbody>
			</table>

			<h3>Extensions PHP chargées ?</h3>
			<table>
				<tbody>
					<?php foreach( $checkExtensions as $param => $value ):?>
					<tr>
						<td><?php echo $param;?></td>
						<td><?php echo booleanIcon( $xhtml, $value ).( $value ? 'Oui' : 'Non' );?></td>
					</tr>
					<?php endforeach;?>
				</tbody>
			</table>

			<h3>Fichier webrsa.inc présent ?</h3>
			<?php
				echo booleanIcon( $xhtml, ( $webrsaIncExist == true ) );
				if ($webrsaIncExist==true) echo "Oui";
				else echo "Non";
			?>

			<h3>Logiciel pdftk installé ?</h3>
			<?php
				echo booleanIcon( $xhtml, ( $pdftkInstalled == true ) );
				if ($pdftkInstalled==true) echo "Oui";
				else echo "Non";
			?>

			<h3>Toutes les structures référentes sont renseignées ?</h3>
			<?php
				echo booleanIcon( $xhtml, empty( $structs ) );
				if (empty($structs)) echo "Oui";
				else {
					echo "Non";
					?><table>
					<?php
						foreach( $structs as $struct ) {
							echo $this->Xhtml->tableCells(
								array(
									( empty( $struct['Structurereferente']['lib_struc'] ) ? $iconFalse.'FIXME' : $struct['Structurereferente']['lib_struc'] ),
									( empty( $struct['Structurereferente']['apre'] ) ? $iconFalse.'FIXME' : $struct['Structurereferente']['apre'] ),
									( empty( $struct['Structurereferente']['contratengagement'] ) ? $iconFalse.'FIXME' : $struct['Structurereferente']['contratengagement'] )
								)
							);
						}
					?>
					</table><?php
				}
			?>

			<h3>Données présentes dans la table users ?</h3>
			<?php
				echo booleanIcon( $xhtml, empty( $users ) );
				if (empty($users)) echo "Oui";
				else {
					echo "Non";
					?><table>
					<?php
						echo $this->Xhtml->tableHeaders(
							array(
								'Nom',
								'Prénom',
								'Service instructeur',
								__( 'date_deb_hab' ),
								__( 'date_fin_hab' ),
								__( 'lib_service' ),
								__( 'numdepins' ),
								__( 'typeserins' ),
								__( 'numcomins' ),
								__( 'numagrins' ),
							)
						);
						foreach( $users as $user ) {
							echo $this->Xhtml->tableCells(
								array(
									( empty( $user['User']['nom'] ) ? $iconFalse.'FIXME' : $user['User']['nom'] ),
									( empty( $user['User']['prenom'] ) ? $iconFalse.'FIXME' : $user['User']['prenom'] ),
									( empty( $user['User']['serviceinstructeur_id'] ) ? $iconFalse.'FIXME' : $user['User']['serviceinstructeur_id'] ),
									( empty( $user['User']['date_deb_hab'] ) ? $iconFalse.'FIXME' : $user['User']['date_deb_hab'] ),
									( empty( $user['User']['date_fin_hab'] ) ? $iconFalse.'FIXME' : $user['User']['date_fin_hab'] ),
									( empty( $user['Serviceinstructeur']['lib_service'] ) ? $iconFalse.'FIXME' : $user['Serviceinstructeur']['lib_service'] ),
									( empty( $user['Serviceinstructeur']['numdepins'] ) ? $iconFalse.'FIXME' : $user['Serviceinstructeur']['numdepins'] ),
									( empty( $user['Serviceinstructeur']['typeserins'] ) ? $iconFalse.'FIXME' : $user['Serviceinstructeur']['typeserins'] ),
									( empty( $user['Serviceinstructeur']['numcomins'] ) ? $iconFalse.'FIXME' : $user['Serviceinstructeur']['numcomins'] ),
									( empty( $user['Serviceinstructeur']['numagrins'] ) ? $iconFalse.'FIXME' : $user['Serviceinstructeur']['numagrins'] )
								)
							);
						}
					?>
					</table><?php
				}
			?>

			<h3>Données des apres correctement renseignées dans le fichier webrsa.inc ?</h3>
			<?php
				echo booleanIcon( $xhtml, ( $donneesApreExist == null ) );
				if ($donneesApreExist==null) echo "Oui";
				else {
					echo "Non";
					foreach($donneesApreExist as $donnee) {
						echo "<p>La donnée ".$donnee." manquante</p>";
					}
				}
			?>

			<h3>Dossier temporaire des pdfs inscriptible ?</h3>
			<?php
				echo booleanIcon( $xhtml, ( $checkWritePdfDirectory == true ) );
				if ($checkWritePdfDirectory==true) echo "Oui";
				else echo "Non";
			?>

			<h3>Fichiers Javascript et css concaténés et minifiés ?</h3>
			<ul>
				<li>webrsa.css: <?php echo booleanIcon( $xhtml, $compressedAssets['webrsa.css'] ).( $compressedAssets['webrsa.css'] ? 'Oui' : 'Non' );?></li>
				<li>webrsa.js: <?php echo booleanIcon( $xhtml, $compressedAssets['webrsa.js'] ).( $compressedAssets['webrsa.js'] ? 'Oui' : 'Non' );?></li>
			</ul>

			<h3>Paramétrage des équipes pluridisciplinaires</h3>
			<?php
				if( !empty( $checkWebrsaIncEps ) ) {
					echo '<p>'.booleanIcon( $xhtml, empty( $checkWebrsaIncEps ) ).'Paramétrage incorrect, valeur non renseignée ou type de valeur incorrect dans le webrsa.inc:</p>';
					echo '<ul>';
					foreach( $checkWebrsaIncEps as $key => $type ) {
						echo "<li>{$key} (type {$type})</li>";
					}
					echo '</ul>';
				}
				else {
					echo '<p>'.booleanIcon( $xhtml, empty( $checkWebrsaIncEps ) ).'Paramétrage correct.</p>';
				}
			?>

			<?php if( Configure::read( 'Recherche.qdFilters.Serviceinstructeur' ) ):?>
			<h3>Fragments SQL pour les moteurs de recherche ?</h3>
			<?php
				echo booleanIcon( $xhtml, empty( $checkSqrecherche ) );
				if (empty($checkSqrecherche)) echo "Oui";
				else {
					echo "Non";
					?><table>
					<?php
						foreach( $checkSqrecherche as $service ) {
							echo $this->Xhtml->tableCells(
								array(
									( $service['Serviceinstructeur']['lib_service'] ),
									$this->Xhtml->link( 'Modifier', array( 'controller' => 'servicesinstructeurs', 'action' => 'edit', $service['Serviceinstructeur']['id'] ) )
								)
							);
						}
					?>
					</table><?php
				}
			?>
			<?php endif;?>

			<h3>Configuration de l'accès au système de gestion de contenu (Alfresco)</h3>
			<table>
				<tbody>
					<tr>
						<td>Librairie cURL</td>
						<td><?php echo booleanIcon( $xhtml, $checkCmis['curl'] ).( $checkCmis['curl'] ? 'Oui' : 'Non' );?></td>
					</tr>
					<tr>
						<td>Extension DOM</td>
						<td><?php echo booleanIcon( $xhtml, $checkCmis['dom'] ).( $checkCmis['dom'] ? 'Oui' : 'Non' );?></td>
					</tr>
					<tr>
						<td>Connexion au serveur</td>
						<td><?php echo booleanIcon( $xhtml, $checkCmis['connection'] ).( $checkCmis['connection'] ? 'Oui' : 'Non' );?></td>
					</tr>
					<tr>
						<td>Version 1.0 du protocole CMIS</td>
						<td><?php echo booleanIcon( $xhtml, $checkCmis['version'] ).( $checkCmis['version'] ? 'Oui' : 'Non' );?></td>
					</tr>
				</tbody>
			</table>

			<?php if( !$checkCmis['curl'] ):?>
				<p class="notice">Pour installer la librairie cURL sous Ubuntu: <code>sudo aptitude install php5-curl;/etc/init.d/apache2 restart</code></p>
			<?php endif;?>

			<br/>
			<h3>Serveur Gedooo nécessaires pour les impressions</h3>
			<?php if( !$checkGedooo['file_exists'] ):?>
				<p class="error">Le fichier de test <code><?php echo GEDOOO_TEST_FILE;?></code> n'est pas présent, impossible de tester l'impression avec Gedooo.</p>
			<?php else:?>
				<p><?php echo booleanIcon( $xhtml, ( $checkGedooo['status'] && $checkGedooo['content-type'] && $checkGedooo['print'] ) ).( $checkGedooo['status'] && $checkGedooo['content-type'] && $checkGedooo['print'] ? 'Oui' : 'Non' );?></p>
				<table>
					<tr>
						<td>Accès au service</td>
						<td><?php echo booleanIcon( $xhtml, $checkGedooo['status'] );?></td>
					</tr>
					<tr>
						<td>Retour du service</td>
						<td><?php echo booleanIcon( $xhtml, $checkGedooo['content-type'] );?></td>
					</tr>
					<tr>
						<td>Test d'impression</td>
						<td><?php echo booleanIcon( $xhtml, $checkGedooo['print'] );?></td>
					</tr>
				</table>
			<?php endif;?>

			<br/>

			<h3>Modèles de documents nécessaires pour les impressions</h3>
			<p><?php echo booleanIcon( $xhtml, empty( $checkModelesOdtStatiques['errors'] ) ).( empty( $checkModelesOdtStatiques['errors'] ) ? 'Oui' : 'Non' );?></p>

			<?php if( !empty( $checkModelesOdtStatiques['errors'] ) ):?>
				<p class="notice">Les modèles de documents suivants n'existent pas ou ne peuvent pas être lus.</p>
			<?php endif;?>

			<table>
			<?php
				foreach( $checkModelesOdtStatiques['errors'] as $file ) {
					echo $this->Xhtml->tableCells( array( h( $file ) ) );
				}
			?>
			</table>

			<?php ksort( $checkModelesOdtStatiques['files'] );?>
			<br/>
			<h4>Modèles de documents vérifiés pour les <abbr title="équipes pluridisciplinaires">EPs</abbr> (<?php echo $checkModelesOdtStatiques['count'];?>)</h4>
			<ul>
			<?php foreach( $checkModelesOdtStatiques['files'] as $dir => $odt ):?>
				<li><?php echo h( $dir );?> (<?php echo count( $odt );?>)
					<table><tbody>
						<?php ksort( $odt );?>
						<?php foreach( $odt as $filename => $present ):?>
							<tr>
								<td><?php echo h( $filename );?></td>
								<td><?php echo booleanIcon( $xhtml, $present );?></td>
							</tr>
						<?php endforeach;?>
					</tbody></table>
				</li>
			<?php endforeach;?>
			<br/>
			<h3>Paramétrages des modèles de document pour les impressions</h3>
			<p><?php echo booleanIcon( $xhtml, empty( $checkModelesOdtParametrables ) ).( empty( $checkModelesOdtParametrables ) ? 'Oui' : 'Non' );?></p>

			<?php if( !empty( $checkModelesOdtParametrables ) ):?>
				<p class="notice">Les modèles de documents suivants n'existent pas ou ne peuvent pas être lus.</p>
			<?php endif;?>

			<?php foreach( array_keys( $checkModelesOdtParametrables ) as $typedoc ):?>
				<h4><?php echo $typedoc;?></h4>
				<table>
				<?php
					foreach( $checkModelesOdtParametrables[$typedoc] as $name => $file ) {
						echo $this->Xhtml->tableCells(
							array(
								h( $name ),
								h( $file )
							)
						);
					}
				?>
				</table>
			<?php endforeach;?>
			<br/>
			<h3>Installation et configuration du serveur PostgreSQL</h3>
			<?php
				$success = true;
				foreach( $checkPostgresql as $c ) {
					if( !empty( $c ) ) {
						$success = false;
					}
				}
			?>
			<p><?php echo booleanIcon( $xhtml, $success ).( $success ? 'Oui' : 'Non' );?></p>

			<?php if( !$success ):?>
				<table>
				<?php
					foreach( $checkPostgresql as $key => $message ) {
						echo $this->Xhtml->tableCells(
							array(
								h( $key ),
								$message
							)
						);
					}
				?>
				</table>
			<?php endif;?>

			<br />
			<h3>Vérification du format de délai pour les détections de CER arrivant à échéance</h3>
			<?php
				if( !empty( $checkCritereCerDelaiAvantEcheance ) ) {
					echo '<p>'.booleanIcon( $xhtml, empty( $checkCritereCerDelaiAvantEcheance ) ).'Paramétrage incorrect, valeur non renseignée ou type de valeur incorrect dans le webrsa.inc:</p>';
					echo '<ul>';
					foreach( $checkCritereCerDelaiAvantEcheance as $key => $type ) {
						echo "<li>{$key} (type {$type})</li>";
					}
					echo '</ul>';
				}
				else {
					echo '<p>'.booleanIcon( $xhtml, empty( $checkCritereCerDelaiAvantEcheance ) ).'Paramétrage correct.</p>';
				}
			?>

			<br />
			<h3>Vérification des valeurs par défaut des filtres dans le fichier <code>webrsa.inc</code></h3>
			<?php
				if( !empty( $checkFiltresdefaut ) ) {
					echo '<p>'.booleanIcon( $xhtml, empty( $checkFiltresdefaut ) ).'Paramétrage incorrect, valeurs non renseignées:</p>';
					echo '<ul>';
					foreach( $checkFiltresdefaut as $filtre ) {
						echo "<li>{$filtre}</li>";
					}
					echo '</ul>';
				}
				else {
					echo '<p>'.booleanIcon( $xhtml, empty( $checkFiltresdefaut ) ).'Paramétrage correct.</p>';
				}
			?>
		</table>
	</div>
</div>

<div class="clearer"><hr /></div>
