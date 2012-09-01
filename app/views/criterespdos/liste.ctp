<?php
	$domain = 'propopdo';
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'propopdo', "Criterespdos::{$this->action}", true )
	)
?>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		observeDisableFieldsetOnCheckbox( 'SearchDossierDtdemrsa', $( 'SearchDossierDtdemrsaFromDay' ).up( 'fieldset' ), false );
		observeDisableFieldsetOnCheckbox( 'SearchDossierDatedecisionpdo', $( 'SearchDossierDatedecisionpdoFromDay' ).up( 'fieldset' ), false );
	});
</script>
<?php
	if( is_array( $this->data ) ) {
		echo '<ul class="actionMenu"><li>'.$xhtml->link(
			$xhtml->image(
				'icons/application_form_magnify.png',
				array( 'alt' => '' )
			).' Formulaire',
			'#',
			array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
		).'</li></ul>';
	}

	echo $xform->create( 'Criterespdos', array( 'type' => 'post', 'action' => '/nouvelles/', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );
?>

<fieldset>
	<legend>Recherche par date de demande RSA</legend>
		<?php echo $form->input( 'Search.Dossier.dtdemrsa', array( 'name' => 'data[Search][Dossier][dtdemrsa]', 'label' => 'Filtrer par date de demande RSA', 'type' => 'checkbox' ) );?>
		<fieldset>
			<legend>Date de demande RSA</legend>
			<?php
				$dtdemrsa_from = Set::check( $this->data, 'Search.Dossier.dtdemrsa_from' ) ? Set::extract( $this->data, 'Search.Dossier.dtdemrsa_from' ) : strtotime( '-1 week' );
				$dtdemrsa_to = Set::check( $this->data, 'Search.Dossier.dtdemrsa_to' ) ? Set::extract( $this->data, 'Search.Dossier.dtdemrsa_to' ) : strtotime( 'now' );

				echo $default->search(
					array(
						'Dossier.dtdemrsa_from' => array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $dtdemrsa_from ),
						'Dossier.dtdemrsa_to' => array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120,  'maxYear' => date( 'Y' ) + 5, 'selected' => $dtdemrsa_to ),
					),
					array(
						'options' => $options,
						'form' => false
					)
				);
			?>
		</fieldset>
</fieldset>
	<?php
		///Formulaire de recherche pour les CUIs
		echo $default->search(
			array(
				'Personne.nom' => array( 'label' => __d( 'personne', 'Personne.nom', true ), 'type' => 'text' ),
				'Personne.prenom' => array( 'label' => __d( 'personne', 'Personne.prenom', true ), 'type' => 'text' ),
				'Personne.nir' => array( 'label' => __d( 'personne', 'Personne.nir', true ), 'type' => 'text', 'maxlength' => 15 ),
				'Dossier.matricule' => array( 'label' => __d( 'dossier', 'Dossier.matricule', true ), 'type' => 'text', 'maxlength' => 15 ),
				'Dossier.numdemrsa' => array( 'label' => __d( 'dossier', 'Dossier.numdemrsa', true ), 'type' => 'text', 'maxlength' => 15 )
			),
			array(
				'options' => $options,
				'form' => false
			)
		);

		$valueDossierDernier = isset( $this->data['Dossier']['dernier'] ) ? $this->data['Dossier']['dernier'] : true;
		echo $form->input( 'Dossier.dernier', array( 'label' => 'Uniquement la dernière demande RSA pour un même allocataire', 'type' => 'checkbox', 'checked' => $valueDossierDernier ) );

		echo $search->etatdosrsa($etatdosrsa);

		echo $xform->submit( __( 'Search', true ) );
		echo $xform->end();
?>
<?php $pagination = $xpaginator->paginationBlock( 'Personne', $this->passedArgs ); ?>

	<?php if( isset( $criterespdos ) ):?>
	<br />
	<h2 class="noprint aere">Résultats de la recherche</h2>

	<?php if( is_array( $criterespdos ) && count( $criterespdos ) > 0  ):?>
		<?php echo $pagination;?>
		<table class="tooltips">
			<thead>
				<tr>
					<th><?php echo $xpaginator->sort( 'N° dossier', 'Dossier.numdemrsa' );?></th>
					<th><?php echo $xpaginator->sort( 'Nom du demandeur', 'Personne.nom' );?></th>
					<th><?php echo $xpaginator->sort( 'Etat du droit', 'Situationdossierrsa.etatdosrsa' );?></th>
					<th class="action">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach( $criterespdos as $index => $criterepdo ) {
						$innerTable = '<table id="innerTable'.$index.'" class="innerTable">
							<tbody>
								<tr>
									<th>Commune de naissance</th>
									<td>'.h( $criterepdo['Personne']['nomcomnai'] ).'</td>
								</tr>
								<tr>
									<th>Date de naissance</th>
									<td>'.h( date_short( $criterepdo['Personne']['dtnai'] ) ).'</td>
								</tr>
								<tr>
									<th>Code INSEE</th>
									<td>'.h( $criterepdo['Adresse']['numcomptt'] ).'</td>
								</tr>
								<tr>
									<th>NIR</th>
									<td>'.h( $criterepdo['Personne']['nir'] ).'</td>
								</tr>
								<tr>
									<th>N° CAF</th>
									<td>'.h( $criterepdo['Dossier']['matricule'] ).'</td>
								</tr>
								<tr>
									<th>Rôle</th>
									<td>'.h( $rolepers[$criterepdo['Prestation']['rolepers']] ).'</td>
								</tr>
							</tbody>
						</table>';

						echo $xhtml->tableCells(
							array(
								h( Set::classicExtract( $criterepdo, 'Dossier.numdemrsa' ) ),
								h( Set::enum( Set::classicExtract( $criterepdo, 'Personne.qual' ), $qual ).' '.Set::classicExtract( $criterepdo, 'Personne.nom' ).' '.Set::classicExtract( $criterepdo, 'Personne.prenom' ) ),
								h( value( $etatdosrsa, Set::classicExtract( $criterepdo, 'Situationdossierrsa.etatdosrsa' ) ) ),
								$xhtml->viewLink(
									'Voir',
									array( 'controller' => 'propospdos', 'action' => 'index', Set::classicExtract( $criterepdo, 'Personne.id' ) )
								),
								array( $innerTable, array( 'class' => 'innerTableCell noprint' ) ),
							),
							array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
							array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
						);
					}
				?>
			</tbody>
		</table>
		<?php echo $pagination;?>
		<ul class="actionMenu">
			<li><?php
				echo $xhtml->printLinkJs(
					'Imprimer le tableau',
					array( 'onclick' => 'printit(); return false;', 'class' => 'noprint' )
				);
			?></li>
		</ul>
	<?php else:?>
		<p>Vos critères n'ont retourné aucune PDO.</p>
	<?php endif?>
<?php endif?>

<!-- *********************************************************************** -->