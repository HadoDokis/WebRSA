<?php
	$this->pageTitle = 'Test de l\'application';

	function booleanIcon( $xhtml, $value ) {
		return $xhtml->image( 'icons/'.( $value ? 'tick.png' : 'cross.png' ) ).' ';
	}

	$iconTrue = booleanIcon( $xhtml, true );
	$iconFalse = booleanIcon( $xhtml, false );
?>
<div>
    <h1><?php echo 'Test de l\'application';?></h1>

    <div>
		<table>
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
							echo $xhtml->tableCells(
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
						echo $xhtml->tableHeaders(
							array(
								'Nom',
								'Prénom',
								'Service instructeur',
								__('date_deb_hab', true),
								__('date_fin_hab', true),
								__('lib_service', true),
								__('numdepins', true),
								__('typeserins', true),
								__('numcomins', true),
								__('numagrins', true),
							)
						);
						foreach( $users as $user ) {
							echo $xhtml->tableCells(
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
							echo $xhtml->tableCells(
								array(
									( $service['Serviceinstructeur']['lib_service'] ),
									$xhtml->link( 'Modifier', array( 'controller' => 'servicesinstructeurs', 'action' => 'edit', $service['Serviceinstructeur']['id'] ) )
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
			<h3>Modèles de documents nécessaires pour les impressions</h3>
			<p><?php echo booleanIcon( $xhtml, empty( $checkModelesOdtStatiques ) ).( empty( $checkModelesOdtStatiques ) ? 'Oui' : 'Non' );?></p>

			<?php if( !empty( $checkModelesOdtStatiques ) ):?>
				<p class="notice">Les modèles de documents suivants n'existent pas ou ne peuvent pas être lus.</p>
			<?php endif;?>

			<table>
			<?php
				foreach( $checkModelesOdtStatiques as $file ) {
					echo $xhtml->tableCells( array( h( $file ) ) );
				}
			?>
			</table>

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
						echo $xhtml->tableCells(
							array(
								h( $name ),
								h( $file )
							)
						);
					}
				?>
				</table>
			<?php endforeach;?>

			<!--<table>
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
			</table>-->
        </table>
    </div>
</div>

<div class="clearer"><hr /></div>
