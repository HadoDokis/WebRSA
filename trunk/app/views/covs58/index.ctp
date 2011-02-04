<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<h1><?php echo 'Commision de COV'; ?></h1>

	<ul class="actionMenu">
		<?php
			echo '<li>'.$xhtml->addLink(
				'Ajouter',
				array( 'controller' => 'covs58', 'action' => 'add' )
			).' </li>';
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
		observeDisableFieldsetOnCheckbox( 'Cov58Datecommission', $( 'Cov58DatecommissionFromDay' ).up( 'fieldset' ), false );
	});
</script>

<?php echo $xform->create( 'Cov58', array( 'type' => 'post', 'action' => $this->action, 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );?>

	<fieldset>
			<?php echo $xform->input( 'Cov58.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>

			<fieldset>
				<legend>Filtrer par Commission</legend>
				<?php echo $default2->subform(
					array(
						'Cov58.name'
					)
				); ?>
			</fieldset>

			<?php echo $xform->input( 'Cov58.datecommission', array( 'label' => 'Filtrer par date de Commission', 'type' => 'checkbox' ) );?>
			<fieldset>
				<legend>Filtrer par période</legend>
				<?php
					$datecommission_from = Set::check( $this->data, 'Cov58.datecommission_from' ) ? Set::extract( $this->data, 'Cov58.datecommission_from' ) : strtotime( '-1 week' );
					$datecommission_to = Set::check( $this->data, 'Cov58.datecommission_to' ) ? Set::extract( $this->data, 'Cov58.datecommission_to' ) : strtotime( 'now' );
				?>
				<?php echo $xform->input( 'Cov58.datecommission_from', array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1, 'minYear' => date( 'Y' ) - 10, 'selected' => $datecommission_from ) );?>
				<?php echo $xform->input( 'Cov58.datecommission_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1, 'minYear' => date( 'Y' ) - 10, 'selected' => $datecommission_to ) );?>
			</fieldset>

	</fieldset>

	<div class="submit noprint">
		<?php echo $xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
		<?php echo $xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>

<?php echo $xform->end();
	if( isset( $covs58 ) ) {
		if( empty( $covs58 ) ) {
			echo $xhtml->tag( 'p', 'Aucun résultat ne correspond aux critères choisis.', array( 'class' => 'notice' ) );
		}
		else {
			echo '<table><thead>';
				echo '<tr>
					<th>'.$xpaginator->sort( __d( 'cov58', 'Cov58.name', true ), 'Cov58.name' ).'</th>
					<th>'.$xpaginator->sort( __d( 'cov58', 'Cov58.datecommission', true ), 'Cov58.datecommission' ).'</th>
					<th>'.$xpaginator->sort( __d( 'cov58', 'Cov58.etatcov', true ), 'Cov58.finalisee' ).'</th>
					<th>'.$xpaginator->sort( __d( 'cov58', 'Cov58.observation', true ), 'Cov58.observations' ).'</th>
					<th colspan="2">Actions</th>
				</tr></thead><tbody>';
			foreach( $covs58 as $cov58 ) {
				echo '<tr>
					<td>'.h( $cov58['Cov58']['name'] ).'</td>
					<td>'.h( $locale->date( 'Date::short', $cov58['Cov58']['datecommission'] ) ).' à '.h( $locale->date( 'Time::short', $cov58['Cov58']['datecommission'] ) ).'</td>
					<td>'.h( $cov58['Cov58']['etatcov'] ).'</td>
					<td>'.h( $cov58['Cov58']['observation'] ).'</td>
					<td>'.$xhtml->link( 'Voir', array( 'controller' => 'covs58', 'action' => 'view', $cov58['Cov58']['id'] ) ).'</td>
				</tr>';
			}
			echo '</tbody></table>';
		}
	}
?>