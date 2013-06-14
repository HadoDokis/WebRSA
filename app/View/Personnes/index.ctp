<?php $this->pageTitle = 'Personnes du foyer';?>

<h1>Personnes du foyer</h1>

<?php if( $this->Permissions->check( 'personnes', 'add' ) ) :?>
	<ul class="actionMenu">
		<?php
			echo '<li>'.$this->Xhtml->addLink(
				'Ajouter une personne au foyer',
				array( 'controller' => 'personnes', 'action' => 'add', $foyer_id ),
				$this->Permissions->checkDossier( 'personnes', 'add', $dossierMenu )
			).' </li>';
		?>
	</ul>
<?php endif;?>

<?php if( !empty( $personnes ) ):?>
	<table class="tooltips">
		<thead>
			<tr>
				<th>Rôle</th>
				<th>Qualité</th>
				<th>Nom</th>
				<th>Prénom</th>
				<th>Date de naissance</th>
				<th>Soumis à droit et devoir</th>
				<th colspan="4" class="action">Actions</th>
				<th class="innerTableHeader">Informations complémentaires</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach( $personnes as $index => $personne ):?>
				<?php
					$title = implode( ' ', array( $personne['Personne']['qual'], $personne['Personne']['nom'], $personne['Personne']['prenom'] ) );

					$innerTable = '<table id="innerTable'.$index.'" class="innerTable">
						<tbody>
							<tr>
								<th>Prénom 2</th>
								<td>'.h( $personne['Personne']['prenom2'] ).'</td>
							</tr>
							<tr>
								<th>Prénom 3</th>
								<td>'.h( $personne['Personne']['prenom3'] ).'</td>
							</tr>
						</tbody>
					</table>';

					if( is_null( $personne['Calculdroitrsa']['toppersdrodevorsa'] ) ) {
						$toppersdrodevorsa = 'Non défini';
					}
					else if( $personne['Calculdroitrsa']['toppersdrodevorsa'] == 1 ) {
						$toppersdrodevorsa = 'Oui';
					}
					else {
						$toppersdrodevorsa = 'Non';
					}

					echo $this->Xhtml->tableCells(
						array(
							h( Set::enum( $personne['Prestation']['rolepers'], $rolepers ) ),
							h( ( Set::extract( $personne, 'Personne.qual' ) != '' ) ? $qual[$personne['Personne']['qual']] : null ),
							h( $personne['Personne']['nom'] ),
							h( $personne['Personne']['prenom'] ),
							h( $this->Locale->date( 'Date::short', $personne['Personne']['dtnai'] ) ),
							h( $toppersdrodevorsa ),
							$this->Xhtml->viewLink(
								'Voir la personne « '.$title.' »',
								array( 'controller' => 'personnes', 'action' => 'view', $personne['Personne']['id'] ),
								$this->Permissions->checkDossier( 'personnes', 'view', $dossierMenu )
							),
							$this->Xhtml->editLink(
								'Éditer la personne « '.$title.' »',
								array( 'controller' => 'personnes', 'action' => 'edit', $personne['Personne']['id'] ),
								$this->Permissions->checkDossier( 'personnes', 'edit', $dossierMenu )
							),
							$this->Xhtml->fileLink(
								'Lier des fichiers',
								array( 'controller' => 'personnes', 'action' => 'filelink', $personne['Personne']['id'] ),
								$this->Permissions->checkDossier( 'personnes', 'filelink', $dossierMenu )
							),
							h( '('.Set::classicExtract( $personne, 'Fichiermodule.nb_fichiers_lies' ).')' ),
							array( $innerTable, array( 'class' => 'innerTableCell' ) ),
						),
						array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
						array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
					);
				?>
			<?php endforeach;?>
		</tbody>
	</table>
<?php else:?>
	<p class="notice">Ce foyer ne possède actuellement aucune personne.</p>
<?php endif;?>