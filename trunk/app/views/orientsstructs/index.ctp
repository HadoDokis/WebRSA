<?php  $this->pageTitle = 'Orientation de la personne';?>
<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id) );?>

<div class="with_treemenu">
	<h1>Orientation</h1>

	<?php
		if ( empty( $orientstructs ) ) {
			echo '<p class="notice">Cette personne ne possède pas encore d\'orientation.</p>';
		}

		if( isset( $nbdossiersnonfinalisescovs ) && !empty( $nbdossiersnonfinalisescovs ) ) {
			echo '<p class="notice">Ce dossier va passer en COV.</p>';
		}
		elseif( !$ajout_possible ) {
			echo '<p class="notice">Impossible d\'ajouter une nouvelle orientation à ce dossier (passage en EP ou dossier ne pouvant être orienté).</p>';
		}
		elseif( !empty( $en_procedure_relance ) ) {
			echo '<p class="notice">Cette personne est en cours de procédure de relance.</p>';
		}
	?>

	<!-- Pour le CG 93, les orientations de rang >= 1 doivent passer en EP, donc il faut utiliser Reorientationseps93Controller::add -->
	<?php if( Configure::read( 'Cg.departement' ) == 93 && $rgorient_max >= 1 ):?>
		<ul class="actionMenu">
			<?php
				echo '<li>'.
					$xhtml->addLink(
						'Préconiser une orientation',
						array( 'controller' => 'reorientationseps93', 'action' => 'add', $last_orientstruct_id ),
						$ajout_possible && $permissions->check( 'reorientationseps93', 'add' )
					).
				' </li>';
			?>
		</ul>
	<?php elseif( Configure::read( 'Cg.departement' ) == 58 ):?>
		<ul class="actionMenu">
			<?php
				echo '<li>'.
					$xhtml->addLink(
						'Préconiser une orientation',
						array( 'controller' => 'proposorientationscovs58', 'action' => 'add', $personne_id ),
						$ajout_possible && $permissions->check( 'proposorientationscovs58', 'add' )
					).
				' </li>';
			?>
		</ul>
	<?php else:?>
		<ul class="actionMenu">
			<?php
				echo '<li>'.
					$xhtml->addLink(
						'Préconiser une orientation',
						array( 'controller' => 'orientsstructs', 'action' => 'add', $personne_id ),
						!$force_edit && $ajout_possible && $permissions->check( 'orientsstructs', 'add' )
					).
				' </li>';
			?>
		</ul>
	<?php endif;?>

	<?php if( Configure::read( 'Cg.departement' ) == 93 && isset( $reorientationep93 ) && !empty( $reorientationep93 ) ):?>
		<?php
			$etatdossierep = Set::enum( $reorientationep93['Passagecommissionep']['etatdossierep'], $optionsdossierseps['Passagecommissionep']['etatdossierep'] );
			if( empty( $etatdossierep ) ) {
				$etatdossierep = 'En attente';
			}
		?>
		<h2>Demande de Réorientation</h2>
		<table>
			<thead>
				<tr>
					<th>Nom</th>
					<th>Prénom</th>
					<th>Date de la demande</th>
					<th>Type d'orientation</th>
					<th>Type de structure</th>
					<th>Rang d'orientation</th>
					<th>État du dossier d'EP</th>
					<th colspan="2" class="action">Actions</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo h( $reorientationep93['Personne']['nom'] );?></td>
					<td><?php echo h( $reorientationep93['Personne']['prenom'] );?></td>
					<td><?php echo $locale->date( __( 'Date::short', true ), $reorientationep93['Reorientationep93']['datedemande'] );?></td>
					<td><?php echo h( $reorientationep93['Typeorient']['lib_type_orient'] );?></td>
					<td><?php echo h( $reorientationep93['Structurereferente']['lib_struc'] );?></td>
					<td class="number"><?php echo h( $reorientationep93['Orientstruct']['rgorient'] + 1 );?></td>
					<td><?php echo h( $etatdossierep );?></td>
					<td><?php echo $default->button( 'edit', array( 'controller' => 'reorientationseps93', 'action' => 'edit', $reorientationep93['Reorientationep93']['id'] ), array( 'enabled' => ( empty( $reorientationep93['Passagecommissionep']['etatdossierep'] ) ) ) );?></td>
					<td><?php echo $default->button( 'delete', array( 'controller' => 'reorientationseps93', 'action' => 'delete', $reorientationep93['Reorientationep93']['id'] ), array( 'enabled' => ( empty( $reorientationep93['Passagecommissionep']['etatdossierep'] ) ), 'confirm' => 'Êtes-vous sûr de vouloir supprimer la demande de réorientation ?' ) );?></td>
				</tr>
			</tbody>
		</table>
	<?php endif;?>

	<?php if( Configure::read( 'Cg.departement' ) == 58 && isset( $propoorientationcov58 ) && !empty( $propoorientationcov58 ) ):?>
		<h2>Nouvelle orientation en cours de validation par la commission d'orientation et de validation</h2>
		<table class="aere">
			<thead>
				<tr>
					<th>Nom</th>
					<th>Prénom</th>
					<th>Date de la demande</th>
					<th>Type d'orientation</th>
					<th>Type de structure</th>
					<th>Rang d'orientation</th>
					<th>État du dossier en COV</th>
					<th colspan="2" class="action">Actions</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo h( $propoorientationcov58['Personne']['nom'] );?></td>
					<td><?php echo h( $propoorientationcov58['Personne']['prenom'] );?></td>
					<td><?php echo $locale->date( __( 'Date::short', true ), $propoorientationcov58['Propoorientationcov58']['datedemande'] );?></td>
					<td><?php echo h( $propoorientationcov58['Typeorient']['lib_type_orient'] );?></td>
					<td><?php echo h( $propoorientationcov58['Structurereferente']['lib_struc'] );?></td>
					<td class="number"><?php echo h( $propoorientationcov58['Propoorientationcov58']['rgorient'] );?></td>
					<td><?php echo h( Set::enum( $propoorientationcov58['Passagecov58']['etatdossiercov'], $optionsdossierscovs58['Passagecov58']['etatdossiercov'] ) );?></td>
					<td><?php echo $default->button( 'edit', array( 'controller' => 'proposorientationscovs58', 'action' => 'edit', $propoorientationcov58['Personne']['id'] ), array( 'enabled' => ( $propoorientationcov58['Passagecov58']['etatdossiercov'] != 'associe' ) ) );?></td>
					<td><?php echo $default->button( 'delete', array( 'controller' => 'proposorientationscovs58', 'action' => 'delete', $propoorientationcov58['Personne']['id'] ), array( 'enabled' => ( $propoorientationcov58['Passagecov58']['etatdossiercov'] != 'associe' ) ), 'Confirmer ?' );?></td>
				</tr>
			</tbody>
		</table>
	<?php endif;?>

	<?php if( Configure::read( 'Cg.departement' ) == 58 && isset( $regressionorientaionep58 ) && !empty( $regressionorientaionep58 ) ):?>
		<h2>Réorientation du professionel de l'Emploi vers le Social en étude par l'équipe pluridisciplinaire</h2>
		<table class="aere">
			<thead>
				<tr>
					<th>Nom</th>
					<th>Prénom</th>
					<th>Date de la demande</th>
					<th>Type d'orientation</th>
					<th>Type de structure</th>
					<th>État du dossier d'EP</th>
					<th class="action">Actions</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo h( $regressionorientaionep58['Personne']['nom'] );?></td>
					<td><?php echo h( $regressionorientaionep58['Personne']['prenom'] );?></td>
					<td><?php echo $locale->date( __( 'Date::short', true ), $regressionorientaionep58['Regressionorientationep58']['datedemande'] );?></td>
					<td><?php echo h( $regressionorientaionep58['Typeorient']['lib_type_orient'] );?></td>
					<td><?php echo h( $regressionorientaionep58['Structurereferente']['lib_struc'] );?></td>
					<td><?php echo h( Set::enum( $regressionorientaionep58['Passagecommissionep']['etatdossierep'], $optionsdossierseps['Passagecommissionep']['etatdossierep'] ) );?></td>
					<td><?php echo $default->button( 'delete', array( 'controller' => 'regressionsorientationseps', 'action' => 'delete', $regressionorientaionep58['Regressionorientationep58']['id'] ), array( 'enabled' => empty( $regressionorientaionep58['Passagecommissionep']['etatdossierep'] ) ), 'Confirmer ?' );?></td>
				</tr>
			</tbody>
		</table>
	<?php endif;?>

	<?php if( !empty( $orientstructs ) ):?>
		<h2>Orientations effectives</h2>
		<table class="tooltips default2">
			<thead>
				<tr>
					<th>Nom</th>
					<th>Prénom</th>
					<?php if( Configure::read( 'Cg.departement' ) == 93 ):?>
						<th>Date de préOrientation</th>
						<th>Date d'orientation</th>
						<th>PréOrientation</th>
						<th><?php __d( 'orientstruct', 'Orientstruct.origine' );?></th>
						<th>Orientation</th>
					<?php else:?>
						<th>Date de la demande</th>
						<th>Date d'orientation</th>
						<th>Type d'orientation</th>
					<?php endif;?>
					<th>Structure référente</th>
					<th>Rang d'orientation</th>
					<?php if( Configure::read( 'nom_form_ci_cg' ) == 'cg58' ):?>
						<th>COV ayant traitée le dossier</th>
						<th>Observations de la COV</th>
					<?php endif;?>
					<?php if( Configure::read( 'nom_form_ci_cg' ) == 'cg66' ):?>
						<th colspan="6" class="action">Actions</th>
					<?php else:?>
						<th colspan="5" class="action">Actions</th>
					<?php endif;?>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach( $orientstructs as $i => $orientstruct ) {
						$nbFichiersLies = 0;
						$nbFichiersLies = ( isset( $orientstruct['Fichiermodule'] ) ? count( $orientstruct['Fichiermodule'] ) : 0 );

						$isOrient = false;
						if( isset( $orientstruct['Orientstruct']['date_propo'] ) ){
							$isOrient = true;
						}

						if( !empty( $orientstruct['Orientstruct']['rgorient'] ) ) {
							$rgorient = ( $orientstruct['Orientstruct']['rgorient'] > 1 ) ? 'Réorientation' : 'Première orientation';
						}
						else {
							$rgorient = null;
						}

						$cells = array(
							h( $orientstruct['Personne']['nom']),
							h( $orientstruct['Personne']['prenom'] ),
							h( date_short( $orientstruct['Orientstruct']['date_propo'] ) ),
							h( date_short( $orientstruct['Orientstruct']['date_valid'] ) ),
						);

						if( Configure::read( 'Cg.departement' ) == 93 ) {
							$cells[] = h( Set::enum( $orientstruct['Orientstruct']['propo_algo'], $typesorients ) );
							$cells[] = h( Set::enum( $orientstruct['Orientstruct']['origine'], $options['origine'] ) );
						}

						array_push(
							$cells,
							h( Set::classicExtract( $orientstruct, 'Typeorient.lib_type_orient' ) ),
							h( $orientstruct['Structurereferente']['lib_struc']  ),
							h( $rgorient )
						);

						if( Configure::read( 'Cg.departement' ) == 58 ) {
							$infoscov = '';
							if( !empty( $orientstruct['Cov58'] ) ){
								$infoscov = 'Site "'.Set::classicExtract( $orientstruct, 'Sitecov58.name' ).'", le '.$locale->date( "Datetime::full", Set::classicExtract( $orientstruct, 'Cov58.datecommission' ) );
							}
							$cells[] = h( $infoscov );
							$cells[] = h( Set::classicExtract( $orientstruct, 'Cov58.observation' ) );
						}
						

						if( Configure::read( 'Cg.departement' ) == 66 ) {
							array_push(
								$cells,
								$default2->button(
									'edit',
									array( 'controller' => 'orientsstructs', 'action' => 'edit', $orientstruct['Orientstruct']['id'] ),
									array(
										'enabled' => (
											$permissions->check( 'orientsstructs', 'edit' ) && ( $orientstruct['Orientstruct']['rgorient'] == $rgorient_max )
											&& !( Configure::read( 'Cg.departement' ) == 93 && isset( $reorientationep93 ) && !empty( $reorientationep93 ) )
											&& $ajout_possible
										)
									)
								),
								$default2->button(
									'print',
									array( 'controller' => 'orientsstructs', 'action' => 'impression', $orientstruct['Orientstruct']['id'] ),
									array(
										'enabled' => (
											( $permissions->check( 'orientsstructs', 'impression' ) == 1 )
											&& $orientstruct['Orientstruct']['imprime']
										)
									)
								),
								$default2->button(
									'notifbenef',
									array( 'controller' => 'orientsstructs', 'action' => 'printChangementReferent',
									$orientstruct['Orientstruct']['id'] ),
									array(
										'enabled' => (
											( $permissions->check( 'orientsstructs', 'printChangementReferent' ) == 1 )
											&& $orientstruct['Orientstruct']['notifbenefcliquable']
										)
									)
								),
								$default2->button(
									'delete',
									array( 'controller' => 'orientsstructs', 'action' => 'delete', $orientstruct['Orientstruct']['id'] ),
									array(
										'enabled' => (
											$permissions->check( 'orientsstructs', 'delete' )
											&& ( $orientstruct['Orientstruct']['rgorient'] == $rgorient_max )
											&& $last_orientstruct_suppressible
										)
									)
								),
								$default2->button(
									'filelink',
									array( 'controller' => 'orientsstructs', 'action' => 'filelink', $orientstruct['Orientstruct']['id'] ),
									array(
										'enabled' => (
											$permissions->check( 'orientsstructs', 'filelink' )
										)
									)
								),
								h( '('.$nbFichiersLies.')' )
							);
						}
						else{
							array_push(
								$cells,
								$xhtml->editLink(
									'Editer l\'orientation',
									array( 'controller' => 'orientsstructs', 'action' => 'edit', $orientstruct['Orientstruct']['id'] ),
									$permissions->check( 'orientsstructs', 'edit' ) && ( $orientstruct['Orientstruct']['rgorient'] == $rgorient_max )
									&& !( Configure::read( 'Cg.departement' ) == 93 && isset( $reorientationep93 ) && !empty( $reorientationep93 ) )
									&& $ajout_possible
								),
								$xhtml->printLink(
									'Imprimer la notification',
									array( 'controller' => 'orientsstructs', 'action' => 'impression', $orientstruct['Orientstruct']['id'] ),
									$permissions->check( 'orientsstructs', 'impression' ) && $orientstruct['Orientstruct']['imprime']
								),
								$xhtml->deleteLink(
									'Supprimer l\'orientation',
									array( 'controller' => 'orientsstructs', 'action' => 'delete', $orientstruct['Orientstruct']['id'] ),
									( 
										$permissions->check( 'orientsstructs', 'delete' )
										&& ( $orientstruct['Orientstruct']['rgorient'] == $rgorient_max )
										&& $last_orientstruct_suppressible
									)
								),
								$xhtml->fileLink(
									'Fichiers liés',
									array( 'controller' => 'orientsstructs', 'action' => 'filelink', $orientstruct['Orientstruct']['id'] ),
									$permissions->check( 'orientsstructs', 'filelink' )
								),
								h( '('.$nbFichiersLies.')' )
							);
						}
						echo $xhtml->tableCells( $cells, array( 'class' => 'odd' ), array( 'class' => 'even' ) );
					}
				?>
			</tbody>
		</table>
	<?php  endif;?>
</div>
<div class="clearer"><hr /></div>