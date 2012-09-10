<h1><?php echo $this->pageTitle = $pageTitle;?></h1>

<?php
	if( !empty( $this->data ) ) {
		echo '<ul class="actionMenu"><li>'.$xhtml->link(
			$xhtml->image(
				'icons/application_form_magnify.png',
				array( 'alt' => '' )
			).' Formulaire',
			'#',
			array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Filtre' ).toggle(); return false;" )
		).'</li></ul>';
	}
?>

<?php require_once( 'filtre.ctp' );?>

<?php if( !empty( $this->data ) && $formSent ):?>
	<?php if( empty( $cohorte ) ):?>
		<?php
			switch( $this->action ) {
				case 'orientees':
					$message = 'Aucun allocataire orienté ne correspond à vos critères.';
					break;
				default:
					$message = 'Tous les allocataires ont été orientés.';
			}
		?>
		<p class="notice"><?php echo $message;?></p>
	<?php else:?>
		<?php
			$formatPagination = 'Results %start% - %end% out of %count%.';
			if( isset( $this->data['Filtre']['paginationNombreTotal'] ) && !$this->data['Filtre']['paginationNombreTotal'] ) {
				$page = Set::classicExtract( $this->params, "paging.Personne.page" );
				$count = Set::classicExtract( $this->params, "paging.Personne.count" );
				$limit = Set::classicExtract( $this->params, "paging.Personne.options.limit" );
				if( ( $count > ( $limit * $page ) ) ) {
					$formatPagination = 'Résultats %start% - %end% sur au moins %count% résultats.';
				}
			}

			$xpaginator->options( array('url' => $this->passedArgs ) );
			$pagination = $xpaginator->paginationBlock( 'Personne', $this->passedArgs, $formatPagination );
		?>
		<?php echo $pagination;?>
		<table class="tooltips">
			<thead>
				<tr>
					<th><?php echo $xpaginator->sort( 'Commune', 'Adresse.locaadr' );?></th>
					<th><?php echo $xpaginator->sort( 'Nom, prenom', 'Personne.nom' );?></th>
					<th><?php echo $xpaginator->sort( 'Date demande', 'Dossier.dtdemrsa' );?></th>
					<th><?php echo $xpaginator->sort( 'Présence DSP', 'Dsp.id' );?></th>
					<th><?php echo $xpaginator->sort( 'Type de service instructeur', 'Suiviinstruction.typeserins' );?></th>
					<?php if( Configure::read( 'Cg.departement' ) == 93 ):?><th><?php echo $xpaginator->sort( __d( 'orientstruct', 'Orientstruct.origine', true ), 'Orientstruct.origine' );?></th><?php endif;?>
					<th><?php echo $xpaginator->sort( 'PréOrientation', 'Orientstruct.propo_algo' );?></th>
					<th><?php echo $xpaginator->sort( 'Orientation', 'Typeorient.lib_type_orient' );?></th>
					<th><?php echo $xpaginator->sort( 'Structure', 'Structurereferente.lib_struc' );?></th>
					<th><?php echo $xpaginator->sort( 'Décision', 'Orientstruct.statut_orient' );?></th>
					<th><?php echo $xpaginator->sort( 'Date préOrientation', 'Orientstruct.date_propo' );?></th>
					<th><?php echo $xpaginator->sort( 'Date d\'Orientation', 'Orientstruct.date_valid' );?></th>

					<th class="action">Action</th>
					<th class="innerTableHeader">Informations complémentaires</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $cohorte as $index => $personne ):?>
					<?php
						// FIXME: date ouverture de droits -> voir flux instruction
						$innerTable = '<table id="innerTable'.$index.'" class="innerTable">
							<tbody>
								<tr>
									<th>N° de dossier</th>
									<td>'.h( $personne['Dossier']['numdemrsa'] ).'</td>
								</tr>
								<tr>
									<th>Date ouverture de droit</th>
									<td>'.h( date_short( $personne['Dossier']['dtdemrsa'] ) ).'</td>
								</tr>
								<tr>
									<th>Date naissance</th>
									<td>'.h( date_short( $personne['Personne']['dtnai'] ) ).'</td>
								</tr>
								<tr>
									<th>Numéro CAF</th>
									<td>'.h( $personne['Dossier']['matricule'] ).'</td>
								</tr>
								<tr>
									<th>NIR</th>
									<td>'.h( $personne['Personne']['nir'] ).'</td>
								</tr>
								<tr>
									<th>Code postal</th>
									<td>'.h( $personne['Adresse']['codepos'] ).'</td>
								</tr>
								<tr>
									<th>Canton</th>
									<td>'.h( $personne['Adresse']['canton'] ).'</td>
								</tr>
								<tr>
									<th>Date de fin de droit</th>
									<td>'.h( date_short( $personne['Situationdossierrsa']['dtclorsa'] ) ).'</td>
								</tr>
								<tr>
									<th>Motif de fin de droit</th>
									<td>'.h( @$moticlorsa[$personne['Situationdossierrsa']['moticlorsa']] ).'</td>
								</tr>
								<tr>
									<th>Rôle</th>
									<td>'.h( @$rolepers[$personne['Prestation']['rolepers']] ).'</td>
								</tr>
							</tbody>
						</table>';

						$cells = array(
							h( $personne['Adresse']['locaadr'] ),
							h( $personne['Personne']['nom'].' '.$personne['Personne']['prenom'] ),
							h( date_short( $personne['Dossier']['dtdemrsa'] ) ),
							h( !empty( $personne['Dsp']['id'] ) ? 'Oui' : 'Non' ),
							h( value( $typeserins, Set::classicExtract( $personne, 'Suiviinstruction.typeserins') ) ),
						);

						if( Configure::read( 'Cg.departement' ) == 93 ) {
							array_push(
								$cells,
								h( Set::enum( $personne['Orientstruct']['origine'], $options['Orientstruct']['origine'] ) )
							);
						}

						array_push(
							$cells,
							h( Set::enum( $personne['Orientstruct']['propo_algo'], $typesOrient ) ),
							h( $personne['Typeorient']['lib_type_orient'] ),
							h( $personne['Structurereferente']['lib_struc'] ),
							h( $personne['Orientstruct']['statut_orient'] ),
							h( date_short( $personne['Orientstruct']['date_propo'] ) ),
							h( date_short( $personne['Orientstruct']['date_valid'] ) ),
							$xhtml->printLink(
								'Imprimer la notification',
								array( 'controller' => 'orientsstructs', 'action' => 'impression', $personne['Orientstruct']['id'] ),
								$permissions->check( 'orientsstructs', 'impression' )
							),
							array( $innerTable, array( 'class' => 'innerTableCell' ) )
						);

						echo $xhtml->tableCells(
							$cells,
							array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
							array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
						);
					?>
				<?php endforeach;?>
			</tbody>
		</table>
		<?php echo $pagination;?>

		<ul class="actionMenu">
			<li><?php
				echo $xhtml->printCohorteLink(
					'Imprimer la cohorte',
					Set::merge(
						array(
							'controller' => 'cohortes',
							'action'     => 'cohortegedooo',
							'id' => 'Cohorteoriente'
						),
						Set::flatten( $this->data )
					)
				);
			?></li>
		</ul>

	<?php endif;?>
<?php endif;?>