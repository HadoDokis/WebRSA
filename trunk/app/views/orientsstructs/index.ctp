<?php  $this->pageTitle = 'Orientation de la personne';?>
<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id) );?>


<div class="with_treemenu">
	<h1>Orientation</h1>

	<?php if( !empty( $en_procedure_relance ) ):?>
		<p class="notice">Cette personne est en cours de procédure de relance.</p>
	<?php endif;?>

	<?php if( empty( $orientstructs ) ):?>
		<p class="notice">Cette personne ne possède pas encore d'orientation.</p>
	<?php endif;?>

	<!-- Pour le CG 93, les orientations de rang >= 1 doivent passer en EP, donc il faut utiliser Saisinesepsreorientsrs93Controller::add -->
	<?php if( Configure::read( 'Cg.departement' ) == 93 && $rgorient_max >= 1 ):?>
		<?php if( $permissions->check( 'saisinesepsreorientsrs93', 'add' ) ):?>
			<ul class="actionMenu">
				<?php
					echo '<li>'.
						$xhtml->addLink(
							'Préconiser une orientation',
							array( 'controller' => 'saisinesepsreorientsrs93', 'action' => 'add', $last_orientstruct_id ),
							$ajout_possible
						).
					' </li>';
				?>
			</ul>
		<?php endif;?>
	<?php elseif( Configure::read( 'Cg.departement' ) == 58 ):?>
		<?php if( $permissions->check( 'proposorientationscovs58', 'add' ) ):?>
			<ul class="actionMenu">
				<?php
					echo '<li>'.
						$xhtml->addLink(
							'Préconiser une orientation',
							array( 'controller' => 'proposorientationscovs58', 'action' => 'add', $personne_id ),
							$ajout_possible
						).
					' </li>';
				?>
			</ul>
		<?php endif;?>
	<?php else:?>
		<?php if( $permissions->check( 'orientsstructs', 'add' ) ):?>
			<ul class="actionMenu">
				<?php
					echo '<li>'.
						$xhtml->addLink(
							'Préconiser une orientation',
							array( 'controller' => 'orientsstructs', 'action' => 'add', $personne_id ),
							$ajout_possible
						).
					' </li>';
				?>
			</ul>
		<?php endif;?>
	<?php endif;?>

	<?php if( Configure::read( 'Cg.departement' ) == 93 && isset( $saisineepreorientsr93 ) && !empty( $saisineepreorientsr93 ) ):?>
		<h2>Réorientation par l'équipe pluridisciplinaire</h2>

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
					<td><?php echo h( $saisineepreorientsr93['Dossierep']['Personne']['nom'] );?></td>
					<td><?php echo h( $saisineepreorientsr93['Dossierep']['Personne']['prenom'] );?></td>
					<td><?php echo $locale->date( __( 'Date::short', true ), $saisineepreorientsr93['Saisineepreorientsr93']['datedemande'] );?></td>
					<td><?php echo h( $saisineepreorientsr93['Typeorient']['lib_type_orient'] );?></td>
					<td><?php echo h( $saisineepreorientsr93['Structurereferente']['lib_struc'] );?></td>
					<td class="number"><?php echo h( $saisineepreorientsr93['Orientstruct']['rgorient'] + 1 );?></td>
					<td><?php echo h( Set::enum( $saisineepreorientsr93['Dossierep']['etapedossierep'], $optionsdossierseps['Dossierep']['etapedossierep'] ) );?></td>
					<td><?php echo $default->button( 'edit', array( 'controller' => 'saisinesepsreorientsrs93', 'action' => 'edit', $saisineepreorientsr93['Saisineepreorientsr93']['id'] ), array( 'enabled' => ( $saisineepreorientsr93['Dossierep']['etapedossierep'] == 'cree' ) ) );?></td>
					<td><?php echo $default->button( 'delete', array( 'controller' => 'saisinesepsreorientsrs93', 'action' => 'delete', $saisineepreorientsr93['Saisineepreorientsr93']['id'] ), array( 'enabled' => ( $saisineepreorientsr93['Dossierep']['etapedossierep'] == 'cree' ) ) );?></td>
				</tr>
			</tbody>
		</table>

		<h2>Orientations effectives</h2>
	<?php endif;?>

	<?php if( !empty( $orientstructs ) ):?>
		<table class="tooltips">
			<thead>
				<tr>
					<th>Nom</th>
					<th>Prénom</th>
					<th>Date de la demande</th>
					<th>Date d'orientation</th>
					<th>Préconisation d'orientation</th>
					<th>Structure référente</th>
					<th>Rang d'orientation</th>
 					<!--<?php if( Configure::read( 'nom_form_ci_cg' ) == 'cg58' ):?><th>Etat de l'orientation</th><?php endif;?>-->
					<th colspan="2" class="action">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach( $orientstructs as $i => $orientstruct ) {
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
							h( Set::classicExtract( $orientstruct, 'Typeorient.lib_type_orient' ) ),
							h( $orientstruct['Structurereferente']['lib_struc']  ),
							h( $rgorient ),
						);

						/*if( Configure::read( 'nom_form_ci_cg' ) == 'cg58' ) {
							$cells[] = h( Set::enum( $orientstruct['Orientstruct']['etatorient'], $options['etatorient'] ) ) ;
						}*/

						array_push(
							$cells,
							$xhtml->editLink(
								'Editer l\'orientation',
								array( 'controller' => 'orientsstructs', 'action' => 'edit', $orientstruct['Orientstruct']['id'] ),
								$permissions->check( 'orientsstructs', 'edit' ) && ( $orientstruct['Orientstruct']['rgorient'] == $rgorient_max )
								&& !( Configure::read( 'Cg.departement' ) == 93 && isset( $saisineepreorientsr93 ) && !empty( $saisineepreorientsr93 ) )
							),
							$xhtml->printLink(
								'Imprimer la notification',
								array( 'controller' => 'gedooos', 'action' => 'orientstruct', $orientstruct['Orientstruct']['id'] ),
								$permissions->check( 'gedooos', 'orientstruct' ) && $orientstruct['Orientstruct']['imprime']
							)
						);

						echo $xhtml->tableCells( $cells, array( 'class' => 'odd' ), array( 'class' => 'even' ) );
					}
				?>
			</tbody>
		</table>
	<?php  endif;?>
</div>
<div class="clearer"><hr /></div>
