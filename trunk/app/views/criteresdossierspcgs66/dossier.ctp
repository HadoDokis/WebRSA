<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php
	$domain = 'dossierpcg66';
	echo $xhtml->tag(
		'h1',
		$this->pageTitle = __d( 'dossierpcg66', "Criteresdossierspcgs66::{$this->action}", true )
	)
?>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		observeDisableFieldsetOnCheckbox( 'Dossierpcg66Datereceptionpdo', $( 'Dossierpcg66DatereceptionpdoFromDay' ).up( 'fieldset' ), false );
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

	echo $xform->create( 'Criteredossierpcg66', array( 'type' => 'post', 'action' => 'dossier', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );
?>
<fieldset>
	<legend>Recherche par allocataire<!--FIXME: personne du foyer--></legend>
	<?php
		echo $form->input( 'Personne.nir', array( 'label' => 'NIR', 'maxlength' => 15 ) );
		echo $form->input( 'Dossier.matricule', array( 'label' => __d( 'dossier', 'Dossier.matricule', true ), 'maxlength' => 15 ) );
		echo $form->input( 'Dossier.numdemrsa', array( 'label' => __d( 'dossier', 'Dossier.numdemrsa', true ), 'maxlength' => 15 ) );
		echo $form->input( 'Personne.nom', array( 'label' => 'Nom' ) );
		echo $form->input( 'Personne.nomnai', array( 'label' => 'Nom de jeune fille' ) );
		echo $form->input( 'Personne.prenom', array( 'label' => 'Prénom' ) );
		echo $form->input( 'Personne.dtnai', array( 'label' => 'Date de naissance', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'empty' => true ) );
	?>
</fieldset>
<fieldset>
	<legend>Recherche par date de réception</legend>
		<?php echo $xform->input( 'Dossierpcg66.datereceptionpdo', array( 'label' => 'Filtrer par date de réception de la PDO', 'type' => 'checkbox' ) );?>
		<fieldset>
			<legend>Date de réception de la PDO</legend>
			<?php
				$datereceptionpdo_from = Set::check( $this->data, 'Dossierpcg66.datereceptionpdo_from' ) ? Set::extract( $this->data, 'Dossierpcg66.datereceptionpdo_from' ) : strtotime( '-1 week' );
				$datereceptionpdo_to = Set::check( $this->data, 'Dossierpcg66.datereceptionpdo_to' ) ? Set::extract( $this->data, 'Dossierpcg66.datereceptionpdo_to' ) : strtotime( 'now' );
			?>
			<?php echo $form->input( 'Dossierpcg66.datereceptionpdo_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $datereceptionpdo_from ) );?>
			<?php echo $form->input( 'Dossierpcg66.datereceptionpdo_to', array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'maxYear' => date( 'Y' ) + 5,  'selected' => $datereceptionpdo_to ) );?>
		</fieldset>
	<?php
		///Formulaire de recherche pour les PDOs
		echo $default2->subform(
			array(
				'Dossierpcg66.originepdo_id' => array( 'label' => __d( 'dossierpcg66', 'Dossierpcg66.originepdo_id', true ), 'type' => 'select', 'options' => $originepdo, 'empty' => true ),
				'Dossierpcg66.user_id' => array( 'label' => __d( 'dossierpcg66', 'Dossierpcg66.user_id', true ), 'type' => 'select', 'options' => $gestionnaire, 'empty' => true )
			),
			array(
				'options' => $options
			)
		);
	?>
	<?php 
		echo $search->etatDossierPCG66( $etatdossierpcg ); 
	?>

</fieldset>
	<div class="submit noprint">
		<?php echo $xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
		<?php echo $xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>
<?php echo $xform->end();?>
<?php $pagination = $xpaginator->paginationBlock( 'Dossierpcg66', $this->passedArgs ); ?>

	<?php if( isset( $criteresdossierspcgs66 ) ):?>
	<br />
	<h2 class="noprint aere">Résultats de la recherche</h2>

	<?php if( is_array( $criteresdossierspcgs66 ) && count( $criteresdossierspcgs66 ) > 0  ):?>
		<?php echo $pagination;?>
		<table class="tooltips">
			<thead>
				<tr>
					<th><?php echo $xpaginator->sort( 'N° dossier', 'Dossier.numdemrsa' );?></th>
					<th><?php echo $xpaginator->sort( 'Nom du demandeur', 'Personne.nom' );?></th>
					<th><?php echo $xpaginator->sort( 'Origine de la PDO', 'Dossierpcg66.originepdo_id' );?></th>
					<th><?php echo $xpaginator->sort( 'Type de dossier', 'Dossierpcg66.typepdo_id' );?></th>
					<th><?php echo $xpaginator->sort( 'Date de réception', 'Dossierpcg66.datereceptionpdo' );?></th>
					<th><?php echo $xpaginator->sort( 'Gestionnaire', 'Dossierpcg66.user_id' );?></th>
					<th><?php echo $xpaginator->sort( 'Nb de propositions de décisions', 'Dossierpcg66.nbpropositions' );?></th>
					<th><?php echo $xpaginator->sort( 'Etat du dossier', 'Dossierpcg66.etatdossierpcg' );?></th>
					<th class="action">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach( $criteresdossierspcgs66 as $index => $criteredossierpcg66 ) {

						$datetransmission = '';
						if( $criteredossierpcg66['Dossierpcg66']['etatdossierpcg'] == 'transmisop' ){
							$datetransmission = ' le '.date_short( Set::classicExtract( $criteredossierpcg66, 'Decisiondossierpcg66.datetransmissionop' ) );
						}
		
						$innerTable = '<table id="innerTable'.$index.'" class="innerTable">
							<tbody>
								<tr>
									<th>Etat du droit</th>
									<td>'.h( Set::classicExtract( $etatdosrsa, Set::classicExtract( $criteredossierpcg66, 'Situationdossierrsa.etatdosrsa' ) ) ).'</td>
								</tr>
								<tr>
									<th>Commune de naissance</th>
									<td>'.h( $criteredossierpcg66['Personne']['nomcomnai'] ).'</td>
								</tr>
								<tr>
									<th>Date de naissance</th>
									<td>'.h( date_short( $criteredossierpcg66['Personne']['dtnai'] ) ).'</td>
								</tr>
								<tr>
									<th>Code INSEE</th>
									<td>'.h( $criteredossierpcg66['Adresse']['numcomptt'] ).'</td>
								</tr>
								<tr>
									<th>NIR</th>
									<td>'.h( $criteredossierpcg66['Personne']['nir'] ).'</td>
								</tr>
								<tr>
									<th>N° CAF</th>
									<td>'.h( $criteredossierpcg66['Dossier']['matricule'] ).'</td>
								</tr>

							</tbody>
						</table>';

						echo $xhtml->tableCells(
							array(
								h( Set::classicExtract( $criteredossierpcg66, 'Dossier.numdemrsa' ) ),
								h( Set::enum( Set::classicExtract( $criteredossierpcg66, 'Personne.qual' ), $qual ).' '.Set::classicExtract( $criteredossierpcg66, 'Personne.nom' ).' '.Set::classicExtract( $criteredossierpcg66, 'Personne.prenom' ) ),
								h( Set::enum( Set::classicExtract( $criteredossierpcg66, 'Dossierpcg66.originepdo_id' ), $originepdo ) ),
								h( Set::enum( Set::classicExtract( $criteredossierpcg66, 'Dossierpcg66.typepdo_id' ), $typepdo ) ),
								h( $locale->date( 'Locale->date',  Set::classicExtract( $criteredossierpcg66, 'Dossierpcg66.datereceptionpdo' ) ) ),
								h( Set::enum( Set::classicExtract( $criteredossierpcg66, 'Dossierpcg66.user_id' ), $gestionnaire ) ),
								h( $criteredossierpcg66['Dossierpcg66']['nbpropositions'] ),
								h( Set::enum( Set::classicExtract( $criteredossierpcg66, 'Dossierpcg66.etatdossierpcg' ), $options['Dossierpcg66']['etatdossierpcg'] ).$datetransmission ),
								$xhtml->viewLink(
									'Voir',
									array( 'controller' => 'dossierspcgs66', 'action' => 'edit', Set::classicExtract( $criteredossierpcg66, 'Dossierpcg66.id' ) )
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
			<li><?php
				echo $xhtml->exportLink(
					'Télécharger le tableau',
					array( 'controller' => 'criteresdossierspcgs66', 'action' => 'exportcsv', implode_assoc( '/', ':', array_unisize( $this->data ) ) )
				);
			?></li>
		</ul>
	<?php else:?>
		<p>Vos critères n'ont retourné aucune PDO.</p>
	<?php endif?>
<?php endif?>