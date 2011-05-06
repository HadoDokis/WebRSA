<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
	switch( @$this->action ) {
		case 'creationmodification':
			$this->pageTitle = 'Création / modification d\'une commission d\'EP';
			break;
		case 'attributiondossiers':
			$this->pageTitle = 'Attribution des dossiers à une commission d\'EP';
			break;
		case 'arbitrage':
			$this->pageTitle = 'Arbitrage d\'une commission d\'EP';
			break;
		default:
			$this->pageTitle = 'Liste des commissions d\'EP';
	}
?>

<h1><?php echo $this->pageTitle;?></h1>

	<ul class="actionMenu">
		<?php
			if( @$this->action == 'creationmodification' ) {
				echo '<li>'.$xhtml->addLink(
					'Ajouter',
					array( 'controller' => 'commissionseps', 'action' => 'add' )
				).' </li>';
			}
		?>
	</ul>
<?php
	echo '<ul class="actionMenu"><li>'.$xhtml->link(
		$xhtml->image(
			'icons/application_form_magnify.png',
			array( 'alt' => '' )
		).' Formulaire',
		'#',
		array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
	).'</li></ul>';
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		observeDisableFieldsetOnCheckbox( 'CommissionepDateseance', $( 'CommissionepDateseanceFromDay' ).up( 'fieldset' ), false );
	});
</script>

<?php echo $xform->create( 'Commissionep', array( 'type' => 'post', 'action' => $this->action, 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );?>

	<fieldset>
			<?php echo $xform->input( 'Commissionep.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>

			<fieldset>
				<legend>Filtrer par Equipe Pluridisciplinaire</legend>
				<?php echo $default2->subform(
					array(
						'Ep.regroupementep_id' => array('type'=>'select'),
						'Commissionep.name',
						'Commissionep.identifiant'
					),
					array(
						'options' => $options
					)
				); ?>
			</fieldset>

			<fieldset>
				<legend>Filtrer par adresse</legend>
				<?php echo $default2->subform(
					array(
						//'Commissionep.structurereferente_id'=>array('type'=>'select'),
						'Structurereferente.ville'
					),
					array(
						'options' => $options
					)
				); ?>
			</fieldset>

			<?php echo $xform->input( 'Commissionep.dateseance', array( 'label' => 'Filtrer par date de Commission', 'type' => 'checkbox' ) );?>
			<fieldset>
				<legend>Filtrer par période</legend>
				<?php
					$dateseance_from = Set::check( $this->data, 'Commissionep.dateseance_from' ) ? Set::extract( $this->data, 'Commissionep.datecomite_from' ) : strtotime( '-1 week' );
					$dateseance_to = Set::check( $this->data, 'Commissionep.dateseance_to' ) ? Set::extract( $this->data, 'Commissionep.datecomite_to' ) : strtotime( 'now' );
				?>
				<?php echo $xform->input( 'Commissionep.dateseance_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1, 'minYear' => date( 'Y' ) - 10, 'selected' => $dateseance_from ) );?>
				<?php echo $xform->input( 'Commissionep.dateseance_to', array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1, 'minYear' => date( 'Y' ) - 10, 'selected' => $dateseance_to ) );?>
			</fieldset>

	</fieldset>

	<div class="submit noprint">
		<?php echo $xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
		<?php echo $xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>

<?php echo $xform->end();
	if( isset( $commissionseps ) ) {
		if( empty( $commissionseps ) ) {
			echo $xhtml->tag( 'p', 'Aucun résultat ne correspond aux critères choisis.', array( 'class' => 'notice' ) );
		}
		else {
			$pagination = $xpaginator->paginationBlock( 'Commissionep', $this->passedArgs );

			echo '<p>'.$pagination.'</p>';

			echo '<table><thead>';
				echo '<tr>
					<th>'.$xpaginator->sort( __d( 'ep', 'Ep.identifiant', true ), 'Ep.identifiant' ).'</th>
					<th>'.$xpaginator->sort( __d( 'ep', 'Ep.name', true ), 'Ep.name' ).'</th>
					<th>'.$xpaginator->sort( __d( 'commissionep', 'Commissionep.identifiant', true ), 'Commissionep.identifiant' ).'</th>
					<th>'.$xpaginator->sort( __d( 'commissionep', 'Commissionep.lieuseance', true ), 'Commissionep.lieuseance' ).'</th>
					<th>'.$xpaginator->sort( __d( 'commissionep', 'Commissionep.dateseance', true ), 'Commissionep.dateseance' ).'</th>
					<th>Nombre de participants</th>
					<th>Nombre d\'absents</th>
					<th>'.$xpaginator->sort( __d( 'commissionep', 'Commissionep.etatcommissionep', true ), 'Commissionep.etatcommissionep' ).'</th>
					<th>'.$xpaginator->sort( __d( 'commissionep', 'Commissionep.observations', true ), 'Commissionep.observations' ).'</th>
					<th>Actions</th>
				</tr></thead><tbody>';
			foreach( $commissionseps as $commissionep ) {
// debug($commissionep);
				/*if( Configure::read( 'Cg.departement' ) != 93 ) {
					$lien = $xhtml->link( 'Voir', array( 'controller' => 'commissionseps', 'action' => 'view', $commissionep['Commissionep']['id'] ) );
				}
				else {*/
					switch( @$this->action ) {
						case 'creationmodification':
							$lien = $xhtml->link( 'Modification', array( 'controller' => 'commissionseps', 'action' => 'edit', $commissionep['Commissionep']['id'] ) );
							break;
						case 'attributiondossiers':
							$lien = $xhtml->link( 'Attribution des dossiers à une commission', array( 'controller' => 'dossierseps', 'action' => 'choose', $commissionep['Commissionep']['id'] ) );
							break;
						case 'arbitrage':
							$lien = $xhtml->link( 'Arbitrage', array( 'controller' => 'commissionseps', 'action' => 'view', $commissionep['Commissionep']['id'] ) );
							break;
						default:
							$lien = $xhtml->link( 'Voir', array( 'controller' => 'commissionseps', 'action' => 'view', $commissionep['Commissionep']['id'] ) );
					}
				//}

				echo '<tr>
					<td>'.h( $commissionep['Ep']['identifiant'] ).'</td>
					<td>'.h( $commissionep['Ep']['name'] ).'</td>
					<td>'.h( $commissionep['Commissionep']['identifiant'] ).'</td>
					<td>'.h( @$commissionep['Commissionep']['lieuseance'] ).'</td>
					<td>'.h( $locale->date( '%d/%m/%Y %H:%M', $commissionep['Commissionep']['dateseance'] ) ).'</td>
					<td>'.h( $commissionep['Commissionep']['nbparticipants'] ).'</td>
					<td>'.h( $commissionep['Commissionep']['nbabsents'] ).'</td>
					<td>'.h( Set::enum( $commissionep['Commissionep']['etatcommissionep'], $options['Commissionep']['etatcommissionep'] ) ).'</td>
					<td>'.h( $commissionep['Commissionep']['observations'] ).'</td>
					<td>'.$lien.'</td>
				</tr>';
			}
			echo '</tbody></table>';

			echo '<p>'.$pagination.'</p>';
		}
	}
?>