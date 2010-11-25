<?php $this->pageTitle = 'Test de l\'application';?>
<div>
    <h1><?php echo 'Test de l\'application';?></h1>

    <div>
		<table>
			<h3>Fichier webrsa.inc présent ?</h3>
			<?php
				if ($webrsaIncExist==true) echo "Oui";
				else echo "Non";
			?>

			<h3>Logiciel pdftk installé ?</h3>
			<?php
				if ($pdftkInstalled==true) echo "Oui";
				else echo "Non";
			?>

			<h3>Toutes les structures référentes sont renseignées ?</h3>
			<?php
				if (empty($structs)) echo "Oui";
				else {
					echo "Non";
					?><table>
					<?php
						foreach( $structs as $struct ) {
							echo $xhtml->tableCells(
								array(
									( empty( $struct['Structurereferente']['lib_struc'] ) ? 'FIXME' : $struct['Structurereferente']['lib_struc'] ),
									( empty( $struct['Structurereferente']['apre'] ) ? 'FIXME' : $struct['Structurereferente']['apre'] ),
									( empty( $struct['Structurereferente']['contratengagement'] ) ? 'FIXME' : $struct['Structurereferente']['contratengagement'] )
								)
							);
						}
					?>
					</table><?php
				}
			?>

			<h3>Données présentes dans la table users ?</h3>
			<?php
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
									( empty( $user['User']['nom'] ) ? 'FIXME' : $user['User']['nom'] ),
									( empty( $user['User']['prenom'] ) ? 'FIXME' : $user['User']['prenom'] ),
									( empty( $user['User']['serviceinstructeur_id'] ) ? 'FIXME' : $user['User']['serviceinstructeur_id'] ),
									( empty( $user['User']['date_deb_hab'] ) ? 'FIXME' : $user['User']['date_deb_hab'] ),
									( empty( $user['User']['date_fin_hab'] ) ? 'FIXME' : $user['User']['date_fin_hab'] ),
									( empty( $user['Serviceinstructeur']['lib_service'] ) ? 'FIXME' : $user['Serviceinstructeur']['lib_service'] ),
									( empty( $user['Serviceinstructeur']['numdepins'] ) ? 'FIXME' : $user['Serviceinstructeur']['numdepins'] ),
									( empty( $user['Serviceinstructeur']['typeserins'] ) ? 'FIXME' : $user['Serviceinstructeur']['typeserins'] ),
									( empty( $user['Serviceinstructeur']['numcomins'] ) ? 'FIXME' : $user['Serviceinstructeur']['numcomins'] ),
									( empty( $user['Serviceinstructeur']['numagrins'] ) ? 'FIXME' : $user['Serviceinstructeur']['numagrins'] )
								)
							);
						}
					?>
					</table><?php
				}
			?>

			<h3>Données des apres correctement renseignées dans le fichier webrsa.inc ?</h3>
			<?php
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
				if ($checkWritePdfDirectory==true) echo "Oui";
				else echo "Non";
			?>

			<h3>Fichiers Javascript et css concaténés et minifiés ?</h3>
			<ul>
				<li>webrsa.css: <?php echo ( $compressedAssets['webrsa.css'] ? 'Oui' : 'Non' );?></li>
				<li>webrsa.js: <?php echo ( $compressedAssets['webrsa.js'] ? 'Oui' : 'Non' );?></li>
			</ul>
        </table>
    </div>
</div>

<div class="clearer"><hr /></div>
