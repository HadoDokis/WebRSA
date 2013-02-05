<?php
	$domain = 'criterecui';
	$this->pageTitle = __d( 'criterecui', "Criterescuis::{$this->action}" );

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
	}
?>
<?php
	echo $this->Xhtml->tag( 'h1', $this->pageTitle );
?>
<script type="text/javascript">
	document.observe("dom:loaded", function() {
		observeDisableFieldsetOnCheckbox( 'CuiDatecontrat', $( 'CuiDatecontratFromDay' ).up( 'fieldset' ), false );
	});
</script>
<?php
	if( is_array( $this->request->data ) ) {
		echo '<ul class="actionMenu"><li>'.$this->Xhtml->link(
			$this->Xhtml->image(
				'icons/application_form_magnify.png',
				array( 'alt' => '' )
			).' Formulaire',
			'#',
			array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
		).'</li></ul>';
	}

	echo $this->Xform->create( 'Criterescuis', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( ( is_array( $this->request->data ) && !empty( $this->request->data ) ) ? 'folded' : 'unfolded' ) ) );
	echo $this->Form->input( 'Criterecui.active', array( 'type' => 'hidden', 'value' => true ) );
?>
	<?php
		echo $this->Search->blocAllocataire( $trancheage );

		echo $this->Search->blocAdresse( $mesCodesInsee, $cantons );
	?>
	<fieldset>
		<legend>Recherche par dossier</legend>
		<?php
			echo $this->Form->input( 'Dossier.numdemrsa', array( 'label' => 'Numéro de demande RSA' ) );
			echo $this->Form->input( 'Dossier.matricule', array( 'label' => 'N° CAF', 'maxlength' => 15 ) );

			$valueDossierDernier = isset( $this->request->data['Dossier']['dernier'] ) ? $this->request->data['Dossier']['dernier'] : true;
			echo $this->Form->input( 'Dossier.dernier', array( 'label' => 'Uniquement la dernière demande RSA pour un même allocataire', 'type' => 'checkbox', 'checked' => $valueDossierDernier ) );
			echo $this->Search->etatdosrsa($etatdosrsa);
			
			echo $this->Search->date( 'Dossier.dtdemrsa' );
		?>
		<fieldset>
			<legend>Code origine demande Rsa</legend>
			<?php echo $this->Form->input( 'Dossier.oridemrsa', array( 'label' => false, 'type' => 'select', 'multiple' => 'checkbox', 'options' => $oridemrsa, 'empty' => false ) );?>
		</fieldset>

	</fieldset>
<fieldset>
	<legend>Recherche de Contrat Unique d'Insertion</legend>
		<?php echo $this->Form->input( 'Cui.datecontrat', array( 'label' => 'Filtrer par date de saisie du contrat', 'type' => 'checkbox' ) );?>
		<fieldset>
			<legend>Date de saisie du contrat</legend>
			<?php
				$datecontrat_from = Set::check( $this->request->data, 'Cui.datecontrat_from' ) ? Set::extract( $this->request->data, 'Cui.datecontrat_from' ) : strtotime( '-1 week' );
				$datecontrat_to = Set::check( $this->request->data, 'Cui.datecontrat_to' ) ? Set::extract( $this->request->data, 'Cui.datecontrat_to' ) : strtotime( 'now' );

				echo $this->Form->input( 'Cui.datecontrat_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $datecontrat_from ) );
				echo $this->Form->input( 'Cui.datecontrat_to', array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120,  'maxYear' => date( 'Y' ) + 5,  'selected' => $datecontrat_to ) );
			?>
		</fieldset>
		<?php
			echo $this->Form->input( 'Cui.secteur', array( 'label' => __d( 'cui', 'Cui.secteur' ), 'type' => 'select', 'options' => $options['Cui']['secteur'], 'empty' => true ) );
			echo $this->Form->input( 'Cui.handicap', array( 'label' => __d( 'cui', 'Cui.handicap' ), 'type' => 'select', 'options' => $options['Cui']['handicap'], 'empty' => true ) );
			echo $this->Form->input( 'Cui.niveauformation', array( 'label' => __d( 'cui', 'Cui.niveauformation' ), 'type' => 'select', 'options' => $options['Cui']['niveauformation'], 'empty' => true ) );
			echo $this->Form->input( 'Cui.compofamiliale', array( 'label' => __d( 'cui', 'Cui.compofamiliale' ), 'type' => 'select', 'options' => $options['Cui']['compofamiliale'], 'empty' => true ) );
		?>
</fieldset>

	<div class="submit noprint">
		<?php echo $this->Form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
		<?php echo $this->Form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>

<?php echo $this->Form->end();?>

<?php $pagination = $this->Xpaginator->paginationBlock( 'Cui', $this->passedArgs ); ?>

	<?php if( isset( $criterescuis ) ):?>
	<br />
	<h2 class="noprint aere">Résultats de la recherche</h2>

	<?php if( is_array( $criterescuis ) && count( $criterescuis ) > 0  ):?>
		<?php echo $pagination;?>
		<table class="tooltips">
			<thead>
				<tr>
					<th><?php echo $this->Xpaginator->sort( 'N° dossier', 'Dossier.numdemrsa' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Nom du demandeur', 'Personne.nom' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Secteur', 'Cui.secteur' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Date du contrat', 'Cui.datecontrat' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Nom de l\'employeur', 'Cui.nomemployeur' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Date de début de prise en charge', 'Cui.datedebprisecharge' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Date de fin de prise en charge', 'Cui.datefinprisecharge' );?></th>
					<th colspan="4" class="action">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach( $criterescuis as $index => $criterecui ) {
						$innerTable = '<table id="innerTable'.$index.'" class="innerTable">
							<tbody>
								<tr>
									<th>Etat du droit</th>
									<td>'.value( $etatdosrsa, Set::classicExtract( $criterecui, 'Situationdossierrsa.etatdosrsa' ) ).'</td>
								</tr>
								<tr>
									<th>Commune de l\'allocataire</th>
									<td>'. $criterecui['Adresse']['locaadr'].'</td>
								</tr>
								<tr>
									<th>Date de naissance</th>
									<td>'.date_short( $criterecui['Personne']['dtnai']).'</td>
								</tr>
								<tr>
									<th>Code INSEE</th>
									<td>'.$criterecui['Adresse']['numcomptt'].'</td>
								</tr>
								<tr>
									<th>NIR</th>
									<td>'.$criterecui['Personne']['nir'].'</td>
								</tr>
								<tr>
									<th>N° CAF</th>
									<td>'.$criterecui['Dossier']['matricule'].'</td>
								</tr>
								<tr>
									<th>Rôle</th>
									<td>'.Set::enum( $criterecui['Prestation']['rolepers'], $rolepers ).'</td>
								</tr>

							</tbody>
						</table>';
						echo $this->Xhtml->tableCells(
							array(
								h( Set::classicExtract( $criterecui, 'Dossier.numdemrsa' ) ),
								h( Set::enum( Set::classicExtract( $criterecui, 'Personne.qual' ), $qual ).' '.Set::classicExtract( $criterecui, 'Personne.nom' ).' '.Set::classicExtract( $criterecui, 'Personne.prenom' ) ),
								h( Set::enum( Set::classicExtract( $criterecui, 'Cui.secteur' ), $options['Cui']['secteur'] ) ),
								h( $this->Locale->date( 'Locale->date',  Set::classicExtract( $criterecui, 'Cui.datecontrat' ) ) ),
								h( Set::classicExtract( $criterecui, 'Cui.nomemployeur' ) ),
								h( $this->Locale->date( 'Locale->date',  Set::classicExtract( $criterecui, 'Cui.datedebprisecharge' ) ) ),
								h( $this->Locale->date( 'Locale->date',  Set::classicExtract( $criterecui, 'Cui.datefinprisecharge' ) ) ),
								$this->Xhtml->viewLink(
									'Voir',
									array( 'controller' => 'cuis', 'action' => 'index', Set::classicExtract( $criterecui, 'Cui.personne_id' ) )
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
				echo $this->Xhtml->printLinkJs(
					'Imprimer le tableau',
					array( 'onclick' => 'printit(); return false;', 'class' => 'noprint' )
				);
			?></li>
			<li><?php
				echo $this->Xhtml->exportLink(
					'Télécharger le tableau',
					array( 'controller' => 'criterescuis', 'action' => 'exportcsv' ) + Hash::flatten( $this->request->data, '__' ),
					$this->Permissions->check( 'criterescuis', 'exportcsv' )
				);
			?></li>
		</ul>
	<?php else:?>
		<p class="notice">Vos critères n'ont retourné aucun contrat unique d'insertion.</p>
	<?php endif?>
<?php endif?>
<!-- *********************************************************************** -->