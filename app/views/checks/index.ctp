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
        </table>
    </div>
</div>

<div class="clearer"><hr /></div>
