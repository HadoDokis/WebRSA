<?php
	$this->pageTitle = 'Commission d\'orientation et de validation';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
	}
?>

<h1><?php echo $this->pageTitle; ?></h1>

	<ul class="actionMenu">
		<?php
			echo '<li>'.$this->Xhtml->addLink(
				'Ajouter',
				array( 'controller' => 'covs58', 'action' => 'add' ),
				$this->Permissions->check( 'covs58', 'add' )
			).' </li>';
		?>
	</ul>

<?php
	echo '<ul class="actionMenu"><li>'.$this->Xhtml->link(
		$this->Xhtml->image(
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

<?php echo $this->Xform->create( 'Cov58', array( 'type' => 'post', 'action' => $this->action, 'id' => 'Search', 'class' => ( ( is_array( $this->request->data ) && !empty( $this->request->data ) ) ? 'folded' : 'unfolded' ) ) );?>

	<fieldset>
			<?php echo $this->Xform->input( 'Cov58.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>

			<fieldset>
				<legend>Filtrer par Commission</legend>
				<?php echo $this->Default2->subform(
					array(
						'Cov58.sitecov58_id' => array( 'type' => 'select', 'option' => $sitescovs58, 'empty' => true )
					)
				); ?>
			</fieldset>

			<?php echo $this->Xform->input( 'Cov58.datecommission', array( 'label' => 'Filtrer par date de Commission', 'type' => 'checkbox' ) );?>
			<fieldset>
				<legend>Filtrer par période</legend>
				<?php
					$datecommission_from = Set::check( $this->request->data, 'Cov58.datecommission_from' ) ? Set::extract( $this->request->data, 'Cov58.datecommission_from' ) : strtotime( '-1 week' );
					$datecommission_to = Set::check( $this->request->data, 'Cov58.datecommission_to' ) ? Set::extract( $this->request->data, 'Cov58.datecommission_to' ) : strtotime( 'now' );
				?>
				<?php echo $this->Xform->input( 'Cov58.datecommission_from', array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1, 'minYear' => date( 'Y' ) - 10, 'selected' => $datecommission_from ) );?>
				<?php echo $this->Xform->input( 'Cov58.datecommission_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1, 'minYear' => date( 'Y' ) - 10, 'selected' => $datecommission_to ) );?>
			</fieldset>

	</fieldset>

	<div class="submit noprint">
		<?php echo $this->Xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
		<?php echo $this->Xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>

<?php echo $this->Xform->end();?>

<?php
	if( isset( $covs58 ) ) {
		echo $this->Default2->index(
			$covs58,
			array(
                'Sitecov58.name',
				'Cov58.datecommission',
				'Cov58.etatcov',
				'Cov58.observation'
			),
			array(
				'actions' => array(
					'Covs58::view'
				),
				'options' => $options
			)
		);
	}
?>