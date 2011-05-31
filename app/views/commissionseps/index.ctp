<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
	switch( @$this->action ) {
		case 'creationmodification':
			$this->pageTitle = 'Création / modification d\'une commission d\'EP';
			break;
		case 'attributiondossiers':
			$this->pageTitle = 'Attribution des dossiers à une commission d\'EP';
			break;
		case 'arbitrageep':
			$this->pageTitle = 'Arbitrage d\'une commission d\'EP (niveau EP)';
			break;
		case 'arbitragecg':
			$this->pageTitle = 'Arbitrage d\'une commission d\'EP (niveau CG)';
			break;
        case 'decisions':
            $this->pageTitle = 'Consultation des décisions';
            break;
		default:
			$this->pageTitle = 'Liste des commissions d\'EP';
	}
?>

<h1><?php echo $this->pageTitle;?></h1>

<?php if( @$this->action == 'creationmodification' ):?>
	<ul class="actionMenu">
		<?php
			echo '<li>'.$xhtml->addLink(
				'Ajouter',
				array( 'controller' => 'commissionseps', 'action' => 'add' ),
				( $compteurs['Ep'] > 0 )
			).' </li>';
		?>
	</ul>
<?php endif;?>

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

			echo $pagination;

			switch( @$this->action ) {
				case 'creationmodification':
					$colspan = 1;
					break;
				case 'attributiondossiers':
					$colspan = 1;
					break;
				case 'arbitrageep':
					$colspan = 3;
					break;
				case 'arbitragecg':
					$colspan = 1;
					break;
                case 'decisions':
                    $colspan = 1;
                    break;
				default:
					$colspan = 1;
			}

			echo '<table><thead>';
				echo '<tr>
					<th>'.$xpaginator->sort( __d( 'ep', 'Ep.identifiant', true ), 'Ep.identifiant' ).'</th>
					<th>'.$xpaginator->sort( __d( 'ep', 'Ep.name', true ), 'Ep.name' ).'</th>
					<th>'.$xpaginator->sort( __d( 'commissionep', 'Commissionep.identifiant', true ), 'Commissionep.identifiant' ).'</th>
					<th>'.$xpaginator->sort( __d( 'commissionep', 'Commissionep.lieuseance', true ), 'Commissionep.lieuseance' ).'</th>
					<th>'.$xpaginator->sort( __d( 'commissionep', 'Commissionep.dateseance', true ), 'Commissionep.dateseance' ).'</th>
					<th>Nombre de participants</th>
					<th>Nombre d\'absents</th>
					<!--<th>'.$xpaginator->sort( __d( 'commissionep', 'Commissionep.etatcommissionep', true ), 'Commissionep.etatcommissionep' ).'</th>-->
					<th>Présence</th>
					<th>Statut de la commission</th>
					<th>'.$xpaginator->sort( __d( 'commissionep', 'Commissionep.observations', true ), 'Commissionep.observations' ).'</th>
					<th colspan=\''.$colspan.'\'>Actions</th>
				</tr></thead><tbody>';
			foreach( $commissionseps as $commissionep ) {
// debug($commissionep);

				switch( @$this->action ) {
					case 'creationmodification':
						$lien = '<td>'.$xhtml->link( 'Voir', array( 'controller' => 'commissionseps', 'action' => 'view', $commissionep['Commissionep']['id'] ) ).'</td>';
						break;
					case 'attributiondossiers':
						$lien = '<td>'.$xhtml->link( 'Attribution des dossiers à une commission', array( 'controller' => 'dossierseps', 'action' => 'choose', $commissionep['Commissionep']['id'] ) ).'</td>';
						break;
					case 'arbitrageep':
						list( $jourCommission, $heureCommission ) = explode( ' ', $commissionep['Commissionep']['dateseance'] );
						$presencesPossible = ( date( 'Y-m-d' ) >= $jourCommission );

						$lien = '<td>'.$xhtml->link( 'Présences', array( 'controller' => 'membreseps', 'action' => 'editpresence', $commissionep['Commissionep']['id'] ), array( 'enabled' => ( ( $commissionep['Commissionep']['etatcommissionep'] == 'associe' || $commissionep['Commissionep']['etatcommissionep'] == 'presence' || $commissionep['Commissionep']['etatcommissionep'] == 'valide' ) && $presencesPossible ) ) ).'</td>';
						
						$lien .= '<td>'.$xhtml->link( 'Arbitrage', array( 'controller' => 'commissionseps', 'action' => 'traiterep', $commissionep['Commissionep']['id'] ), array( 'enabled' => ( $commissionep['Commissionep']['etatcommissionep'] == 'presence' || $commissionep['Commissionep']['etatcommissionep'] == 'decisionep' ) ) ).'</td>';
						
						$lien .= '<td>'.$xhtml->link( 'Avis', array( 'controller' => 'commissionseps', 'action' => 'decisionep', $commissionep['Commissionep']['id'] ), array( 'enabled' => ( $commissionep['Commissionep']['etatcommissionep'] == 'traiteep' || $commissionep['Commissionep']['etatcommissionep'] == 'decisioncg' || $commissionep['Commissionep']['etatcommissionep'] == 'traite' ) ) ).'</td>';
						break;
					case 'arbitragecg':
						$lien = '<td>'.$xhtml->link( 'Arbitrage', array( 'controller' => 'commissionseps', 'action' => 'traitercg', $commissionep['Commissionep']['id'] ), array( 'enabled' => ( $commissionep['Commissionep']['etatcommissionep'] == 'traiteep' || $commissionep['Commissionep']['etatcommissionep'] == 'decisioncg' ) ) ).'</td>';
						break;
                    case 'decisions':
						$lien = '<td>'.$xhtml->link( 'Voir les décisions', array( 'controller' => 'commissionseps', 'action' => 'decisioncg', $commissionep['Commissionep']['id'] ), array( 'enabled' => $commissionep['Commissionep']['etatcommissionep'] == 'traite' ) ).'</td>';
                        break;
					default:
						$lien = '<td>'.$xhtml->link( 'Voir', array( 'controller' => 'commissionseps', 'action' => 'view', $commissionep['Commissionep']['id'] ) ).'</td>';
				}

				echo '<tr>
					<td>'.h( $commissionep['Ep']['identifiant'] ).'</td>
					<td>'.h( $commissionep['Ep']['name'] ).'</td>
					<td>'.h( $commissionep['Commissionep']['identifiant'] ).'</td>
					<td>'.h( @$commissionep['Commissionep']['lieuseance'] ).'</td>
					<td>'.h( $locale->date( '%d/%m/%Y %H:%M', $commissionep['Commissionep']['dateseance'] ) ).'</td>
					<td>'.h( $commissionep['Commissionep']['nbparticipants'] ).'</td>
					<td>'.h( $commissionep['Commissionep']['nbabsents'] ).'</td>
					<!--<td>'.h( Set::enum( $commissionep['Commissionep']['etatcommissionep'], $options['Commissionep']['etatcommissionep'] ) ).'</td>-->
					<td>'.h( ( $commissionep['Commissionep']['etatcommissionep'] == 'cree' || $commissionep['Commissionep']['etatcommissionep']  == 'associe' || $commissionep['Commissionep']['etatcommissionep']  == 'valide' ) ? 'Non validée' : 'Validée' ).'</td>
					<td>'.h( ( $commissionep['Commissionep']['etatcommissionep'] == 'traite') ? 'Traitée' : 'En cours' ).'</td>
					<td>'.h( $commissionep['Commissionep']['observations'] ).'</td>
					'.$lien.'
				</tr>';
			}
			echo '</tbody></table>';

			echo $pagination;
		}
	}
?>