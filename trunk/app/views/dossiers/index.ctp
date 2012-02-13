<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Recherche par dossier/allocataire';?>

<h1>Recherche par dossier / allocataire</h1>

<ul class="actionMenu">
	<?php
		if( $permissions->check( 'ajoutdossiers', 'wizard' ) ) {
			echo '<li>'.$xhtml->addLink(
				'Ajouter un dossier',
				array( 'controller' => 'ajoutdossiers', 'action' => 'wizard' )
			).' </li>';
		}

		if( $permissions->check( 'dossierssimplifies', 'add' ) ) {
			if( Configure::read( 'Cg.departement' ) != 58 ) { // FIXME

					echo '<li>'.$xhtml->addSimpleLink(
						'Ajouter une préconisation d\'orientation',
						array( 'controller' => 'dossierssimplifies', 'action' => 'add' )
					).' </li>';
			}
		}

		if( is_array( $this->data ) ) {
			echo '<li>'.$xhtml->link(
				$xhtml->image(
					'icons/application_form_magnify.png',
					array( 'alt' => '' )
				).' Formulaire',
				'#',
				array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
			).'</li>';
		}
	?>
</ul>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		observeDisableFieldsetOnCheckbox( 'DossierDtdemrsa', $( 'DossierDtdemrsaFromDay' ).up( 'fieldset' ), false );
	});
</script>
<!-- FIXME le repasser en post ? -->
<?php echo $form->create( 'Dossier', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( !empty( $this->data ) ? 'folded' : 'unfolded' ) ) );?>

	<fieldset>
		<legend>Recherche par dossier</legend>
		<?php echo $form->input( 'Dossier.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>
		<?php echo $form->input( 'Dossier.numdemrsa', array( 'label' => 'Numéro de dossier RSA' ) );?>
		<?php echo $form->input( 'Dossier.matricule', array( 'label' => 'Numéro CAF', 'maxlength' => 15 ) );?>
		<?php echo $form->input( 'Detailcalculdroitrsa.natpf', array( 'label' => 'Nature de la prestation', 'type' => 'select', 'options' => $natpf, 'empty' => true ) );?>
		<?php echo $form->input( 'Calculdroitrsa.toppersdrodevorsa', array( 'label' => 'Soumis à Droit et Devoir', 'type' => 'select', 'options' => $toppersdrodevorsa, 'empty' => true ) );?>
		<?php echo $form->input( 'Serviceinstructeur.id', array( 'label' => __( 'lib_service', true ), 'type' => 'select' , 'options' => $typeservice, 'empty' => true ) );?>

		<?php echo $form->input( 'Dossier.dtdemrsa', array( 'label' => 'Filtrer par date de demande', 'type' => 'checkbox' ) );?>
		<fieldset>
			<legend>Date de demande RSA</legend>
			<?php
				$dtdemrsa_from = Set::check( $this->data, 'Dossier.dtdemrsa_from' ) ? Set::extract( $this->data, 'Dossier.dtdemrsa_from' ) : strtotime( '-1 week' );
				$dtdemrsa_to = Set::check( $this->data, 'Dossier.dtdemrsa_to' ) ? Set::extract( $this->data, 'Dossier.dtdemrsa_to' ) : strtotime( 'now' );
			?>
			<?php echo $form->input( 'Dossier.dtdemrsa_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $dtdemrsa_from ) );?>
			<?php echo $form->input( 'Dossier.dtdemrsa_to', array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'maxYear' => date( 'Y' ) + 5, 'selected' => $dtdemrsa_to ) );?>
		</fieldset>
		<?php
			$valueDossierDernier = isset( $this->data['Dossier']['dernier'] ) ? $this->data['Dossier']['dernier'] : true;
			echo $form->input( 'Dossier.dernier', array( 'label' => 'Uniquement la dernière demande RSA pour un même allocataire', 'type' => 'checkbox', 'checked' => $valueDossierDernier ) );
			
		?>
		<?php echo $search->etatdosrsa($etatdosrsa); ?>
		
	</fieldset>
	<fieldset>
		<legend>Recherche par Adresse</legend>
		<?php echo $form->input( 'Adresse.locaadr', array( 'label' => 'Commune de l\'allocataire ', 'type' => 'text' ) );?>
		<?php echo $form->input( 'Adresse.numcomptt', array( 'label' => 'Numéro de commune au sens INSEE', 'type' => 'select', 'options' => $mesCodesInsee, 'empty' => true ) );?>
		<?php
			if( Configure::read( 'CG.cantons' ) ) {
				echo $form->input( 'Canton.canton', array( 'label' => 'Canton', 'type' => 'select', 'options' => $cantons, 'empty' => true ) );
			}
		?>
	</fieldset>
	<fieldset>
		<legend>Recherche par allocataire<!--FIXME: personne du foyer--></legend>
		<?php echo $form->input( 'Personne.dtnai', array( 'label' => 'Date de naissance', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'empty' => true ) );?>
		<?php echo $form->input( 'Personne.nom', array( 'label' => 'Nom' ) );?>
		<?php echo $form->input( 'Personne.nomnai', array( 'label' => 'Nom de jeune fille' ) );?>
		<?php echo $form->input( 'Personne.prenom', array( 'label' => 'Prénom' ) );?>
		<?php echo $form->input( 'Personne.nir', array( 'label' => 'NIR', 'maxlength' => 15 ) );?>
		<!-- FIXME -->
		<?php echo $form->input( 'Personne.trancheAge', array( 'label' => 'Tranche d\'âge', 'options' => $trancheAge, 'empty' => true ) );
			echo $form->input( 'Personne.hascontrat', array( 'label' => 'Possède un CER ? ', 'type' => 'select', 'options' => array( 'O' => 'Oui', 'N' => 'Non'), 'empty' => true ) );
		?>
		<?php
			if( Configure::read( 'Cg.departement' ) != 93 ){
				$valueSansOrientation = isset( $this->data['Orientstruct']['sansorientation'] ) ? $this->data['Orientstruct']['sansorientation'] : false;
				echo $form->input( 'Orientstruct.sansorientation', array( 'label' => 'Personne sans orientation', 'type' => 'checkbox', 'checked' => $valueSansOrientation ) );
			}
		?>
	</fieldset>

	<div class="submit noprint">
		<?php echo $form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
		<?php echo $form->button( 'Réinitialiser', array( 'type'=>'reset' ) );?>
	</div>
<?php echo $form->end();?>

<!-- Résultats -->
<?php if( isset( $dossiers ) ):?>
	<h2 class="noprint">Résultats de la recherche</h2>

	<?php if( is_array( $dossiers ) && count( $dossiers ) > 0 ):?>
		<?php require( 'index.pagination.ctp' )?>
		<table id="searchResults" class="tooltips">
			<thead>
				<tr>
					<th><?php echo $xpaginator->sort( 'Numéro de dossier', 'Dossier.numdemrsa' );?></th>
					<th><?php echo $xpaginator->sort( 'Date de demande', 'Dossier.dtdemrsa' );?></th>
					<th><?php echo $xpaginator->sort( 'NIR', 'Personne.nir' );?></th>
					<th><?php echo $xpaginator->sort( 'Etat du droit', 'Situationdossierrsa.etatdosrsa' );?></th>
					<th><?php echo $xpaginator->sort( 'Allocataire', 'Personne.nom' );?></th><!-- FIXME: qual/nom/prénom -->
					<th><?php echo $xpaginator->sort( 'Commune de l\'allocataire', 'Adresse.locaadr' );?></th>

					<th class="action noprint">Actions</th>
					<th class="action noprint">Verrouillé</th>
					<th class="innerTableHeader noprint">Informations complémentaires</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $dossiers as $index => $dossier ):?>
					<?php 
						$title = $dossier['Dossier']['numdemrsa'];
						$innerTable = '<table id="innerTablesearchResults'.$index.'" class="innerTable">
							<tbody>
							<tr>
									<th>Numéro CAF</th>
									<td>'.$dossier['Dossier']['matricule'].'</td>
								</tr>
								<tr>
									<th>Date de naissance</th>
									<td>'.date_short( $dossier['Personne']['dtnai'] ).'</td>
								</tr>

								<tr>
									<th>Code INSEE</th>
									<td>'.$dossier['Adresse']['numcomptt'].'</td>
								</tr>
								
								<tr>
									<th>Rôle</th>
									<td>'.$rolepers[$dossier['Prestation']['rolepers']].'</td>
								</tr>
							</tbody>
						</table>';

						echo $xhtml->tableCells(
							array(
								h( $dossier['Dossier']['numdemrsa'] ),
								h( date_short( $dossier['Dossier']['dtdemrsa'] ) ),
								h( $dossier['Personne']['nir'] ),
								h( isset( $etatdosrsa[Set::classicExtract( $dossier, 'Situationdossierrsa.etatdosrsa' )] ) ? $etatdosrsa[Set::classicExtract( $dossier, 'Situationdossierrsa.etatdosrsa' )] : '' ),

								implode(
									' ',
									array(
										$dossier['Personne']['qual'],
										$dossier['Personne']['nom'],
										implode( ' ', array( $dossier['Personne']['prenom'], $dossier['Personne']['prenom2'], $dossier['Personne']['prenom3'] ) )
									)
								),
								h( Set::extract(  $dossier, 'Adresse.locaadr' ) ),
								array(
									$xhtml->viewLink(
										'Voir le dossier « '.$title.' »',
										array( 'controller' => 'dossiers', 'action' => 'view', $dossier['Dossier']['id'] )
									),
									array( 'class' => 'noprint' )
								),
								array(
									( $dossier['Dossier']['locked'] ?
										$xhtml->image(
											'icons/lock.png',
											array( 'alt' => '', 'title' => 'Dossier verrouillé' )
										) : null
									),
									array( 'class' => 'noprint' )
								),
								array( $innerTable, array( 'class' => 'innerTableCell noprint' ) ),
							),
							array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
							array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
						);
					?>
				<?php endforeach;?>
			</tbody>
		</table>
		<?php if( Set::extract( $paginator, 'params.paging.Dossier.count' ) > 65000 ):?>
			<p class="noprint" style="border: 1px solid #556; background: #ffe;padding: 0.5em;"><?php echo $xhtml->image( 'icons/error.png' );?> <strong>Attention</strong>, il est possible que votre tableur ne puisse pas vous afficher les résultats au-delà de la 65&nbsp;000ème ligne.</p>
		<?php endif;?>
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
					array( 'controller' => 'dossiers', 'action' => 'exportcsv', implode_assoc( '/', ':', array_unisize( $this->data ) ) )
				);
			?></li>
		</ul>
		<?php require( 'index.pagination.ctp' )?>
	<?php else:?>
		<p>Vos critères n'ont retourné aucun dossier.</p>
	<?php endif?>
<?php endif?>