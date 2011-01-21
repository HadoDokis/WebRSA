<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<h1><?php echo $this->pageTitle = 'Liste des séances d\'EP';?></h1>

	<ul class="actionMenu">
		<?php
			if( Configure::read( 'Ep.departement' ) != 93 || @$this->params['named']['etapewf'] == 'creationmodification' ) {
				echo '<li>'.$xhtml->addLink(
					'Ajouter',
					array( 'controller' => 'seanceseps', 'action' => 'add' )
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
		observeDisableFieldsetOnCheckbox( 'SeanceepDateseance', $( 'SeanceepDateseanceFromDay' ).up( 'fieldset' ), false );
	});
</script>

<?php echo $xform->create( 'Seanceep', array( 'type' => 'post', 'action' => 'index', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );?>

	<fieldset>
			<?php echo $xform->input( 'Seanceep.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>

			<fieldset>
				<legend>Filtrer par Equipe Pluridisciplinaire</legend>
				<?php echo $default2->subform(
					array(
						'Ep.regroupementep_id' => array('type'=>'select'),
						'Seanceep.name',
						'Seanceep.identifiant'
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
						'Seanceep.structurereferente_id'=>array('type'=>'select'),
						'Structurereferente.ville'
					),
					array(
						'options' => $options
					)
				); ?>
			</fieldset>

			<?php echo $xform->input( 'Seanceep.dateseance', array( 'label' => 'Filtrer par date de Séance', 'type' => 'checkbox' ) );?>
			<fieldset>
				<legend>Filtrer par période</legend>
				<?php
					$dateseance_from = Set::check( $this->data, 'Seanceep.dateseance_from' ) ? Set::extract( $this->data, 'Seanceep.datecomite_from' ) : strtotime( '-1 week' );
					$dateseance_to = Set::check( $this->data, 'Seanceep.dateseance_to' ) ? Set::extract( $this->data, 'Seanceep.datecomite_to' ) : strtotime( 'now' );
				?>
				<?php echo $xform->input( 'Seanceep.dateseance_from', array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $dateseance_from ) );?>
				<?php echo $xform->input( 'Seanceep.dateseance_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 5, 'minYear' => date( 'Y' ) - 120, 'selected' => $dateseance_to ) );?>
			</fieldset>

	</fieldset>

	<div class="submit noprint">
		<?php echo $xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
		<?php echo $xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>

<?php echo $xform->end();
	if (isset($seanceseps)) {
		echo '<table><thead>';
			echo '<tr>
				<th>'.$xpaginator->sort( __d( 'ep', 'Ep.name', true ), 'Ep.name' ).'</th>
				<th>'.$xpaginator->sort( __d( 'structurereferente', 'Structurereferente.lib_struc', true ), 'Structurereferente.lib_struc' ).'</th>
				<th>'.$xpaginator->sort( __d( 'seanceep', 'Seanceep.dateseance', true ), 'Seanceep.dateseance' ).'</th>
				<th>'.$xpaginator->sort( __d( 'seanceep', 'Seanceep.finalisee', true ), 'Seanceep.finalisee' ).'</th>
				<th>'.$xpaginator->sort( __d( 'seanceep', 'Seanceep.observations', true ), 'Seanceep.observations' ).'</th>
				<th>Actions</th>
			</tr></thead><tbody>';
		foreach( $seanceseps as $seanceep ) {
			if( Configure::read( 'Ep.departement' ) != 93 ) {
				$lien = $xhtml->link( 'Voir', array( 'controller' => 'seanceseps', 'action' => 'view', $seanceep['Seanceep']['id'] ) );
			}
			else {
				switch( @$this->params['named']['etapewf'] ) {
					case 'creationmodification':
						$lien = $xhtml->link( 'Modification', array( 'controller' => 'seanceseps', 'action' => 'edit', $seanceep['Seanceep']['id'] ) );
						break;
					case 'attributiondossiers':
						$lien = $xhtml->link( 'Attribution des dossiers à une séance', array( 'controller' => 'dossierseps', 'action' => 'choose', $seanceep['Seanceep']['id'] ) );
						break;
					case 'arbitrage':
						$lien = $xhtml->link( 'Arbitrage', array( 'controller' => 'seanceseps', 'action' => 'view', $seanceep['Seanceep']['id'] ) );
						break;
					default:
						$lien = $xhtml->link( 'Voir', array( 'controller' => 'seanceseps', 'action' => 'view', $seanceep['Seanceep']['id'] ) );
				}
			}

			echo '<tr>
				<td>'.h( $seanceep['Ep']['name'] ).'</td>
				<td>'.h( $seanceep['Structurereferente']['lib_struc'] ).'</td>
				<td>'.h( $locale->date( 'Date::short', $seanceep['Seanceep']['dateseance'] ) ).'</td>
				<td>'.h( $seanceep['Seanceep']['finalisee'] ).'</td>
				<td>'.h( $seanceep['Seanceep']['observations'] ).'</td>
				<td>'.$lien.'</td>
			</tr>';
		}
		echo '</tbody></table>';

		/*echo $default2->index(
			$seanceseps,
			array(
				'Ep.name',
				'Structurereferente.lib_struc',
				'Seanceep.dateseance',
				'Seanceep.finalisee',
				'Seanceep.observations'
			),
			array(
				'actions' => array(
					'Seanceseps::view' => array(
						'visible' => ( ( Configure::read( 'Ep.departement' ) != 93 ) ? '1' : '0' ),
						'url' => array( 'controller' => 'seanceseps', 'action' => 'view', '#Seanceep.id#')
					),
					'Seanceseps::traiterep' => array(
						'disabled' => "'#Seanceep.finalisee#' != ''",
						'url' => array( 'controller' => 'seanceseps', 'action' => 'traiterep', '#Seanceep.id#')
					),
					'Seanceseps::finaliserep' => array(
						'disabled' => "'#Seanceep.finalisee#' != ''",
						'url' => array( 'controller' => 'seanceseps', 'action' => 'finaliserep', '#Seanceep.id#')
					),
					'Seanceseps::traitercg' => array(
						'disabled' => "'#Seanceep.finalisee#' == 'cg' || '#Seanceep.finalisee#' != 'ep'",
						'url' => array( 'controller' => 'seanceseps', 'action' => 'traitercg', '#Seanceep.id#')
					),
					'Seanceseps::finalisercg' => array(
						'disabled' => "'#Seanceep.finalisee#' == 'cg' || '#Seanceep.finalisee#' != 'ep'",
						'url' => array( 'controller' => 'seanceseps', 'action' => 'finalisercg', '#Seanceep.id#')
					),
				)
			)
		);*/
	}
?>