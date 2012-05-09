<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php 
	echo $xhtml->tag(
		'h1',
        $this->pageTitle = __d( 'indicateursuivi', "Indicateurssuivis::{$this->action}", true )
    );
?>
    
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

	$xpaginator->options( $this->passedArgs );
	$pagination = $xpaginator->paginationBlock( 'Dossier', $this->passedArgs );
?>

<?php echo $form->create( 'Indicateursuivi', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( ( !empty( $this->data ) && empty( $this->validationErrors ) ) ? 'folded' : 'unfolded' ) ) );?>
	<?php 
		echo $search->etatdosrsa($etatdosrsa); 
		echo $search->natpf($natpf);
	?>
	<fieldset>
		<legend>Recherche par Adresse</legend>
		<?php echo $form->input( 'Adresse.locaadr', array( 'label' => 'Commune de l\'allocataire ', 'type' => 'text' ) );?>
		<?php echo $form->input( 'Adresse.numcomptt', array( 'label' => 'Numéro de commune au sens INSEE', 'type' => 'select', 'options' => $mesCodesInsee, 'empty' => true ) );?>
		<?php //echo $form->input( 'Adresse.codepos', array( 'label' => 'Code postal ', 'type' => 'text' ) );?>
		<?php
			if( Configure::read( 'CG.cantons' ) ) {
				echo $form->input( 'Canton.canton', array( 'label' => 'Canton', 'type' => 'select', 'options' => $cantons, 'empty' => true ) );
			}

		?>
	</fieldset>	
		<?php
			$valueDossierDernier = isset( $this->data['Dossier']['dernier'] ) ? $this->data['Dossier']['dernier'] : true;
			echo $form->input( 'Dossier.dernier', array( 'label' => 'Uniquement la dernière demande RSA pour un même allocataire', 'type' => 'checkbox', 'checked' => $valueDossierDernier ) );
		?>
		<?php 
			echo $form->input( 'Orientstruct.structurereferente_id', array( 'label' => __d( 'structurereferente', 'Structurereferente.lib_struc', true  ), 'type' => 'select', 'options' => $structs, 'empty' => true) );
			echo $form->input( 'Orientstruct.referent_id', array(  'label' => __d( 'structurereferente', 'Structurereferente.nom_referent', true  ), 'type' => 'select', 'options' => $referents, 'empty' => true ) );
		?>		

 <?php 
 echo $form->input( 'Indicateursuivi.annee', array( 'label' => 'Recherche pour l\'année', 'type' => 'select', 'empty' => true, 'options' => array_range( date( 'Y' )-4, date( 'Y' ) +1 ) ) );
?>

	<div class="submit noprint">
		<?php echo $form->button( 'Filtrer', array( 'type' => 'submit' ) );?>
		<?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>
<?php echo $form->end();?>



<?php if( isset( $indicateurs ) ):?>
	<h2 class="noprint">Résultats de la recherche</h2>
	<?php echo $pagination;?>
	<?php if( is_array( $indicateurs ) && count( $indicateurs ) > 0 ):?>
		<table id="searchResults" class="tooltips">
			<thead>
				<tr>
					<th rowspan="2"><?php echo $xpaginator->sort( 'Numéro CAF/MSA', 'Dossier.matricule' );?></th>
					<th colspan="2">Demandeur</th>
					<th rowspan="2">Adresse</th>
					<th>Nom / Prénom du Conjoint</th>
					<th rowspan="2"><?php echo $xpaginator->sort( 'Date ouverture de droits', 'Dossier.dtdemrsa' );?></th>
					<th rowspan="2">Référent orientant</th>
					<th><?php echo $xpaginator->sort( 'Date d\'orientation par la COV', 'Orientstruct.date_valid' );?></th>
					<th><?php echo $xpaginator->sort( 'Rang orientation', 'Orientstruct.rgorient' );?></th>
					<th rowspan="2">Référent unique</th>
					<th colspan="3">CER</th>
					<th rowspan="2">Dernière information Pôle Emploi</th>
					<th colspan="2">Passage en EP</th>
					<th>Action</th>
				</tr>
				<tr>
					<th><?php echo $xpaginator->sort( 'Nom/Prénom', 'Personne.nom' );?></th>
					<th><?php echo $xpaginator->sort( 'Date de naissance', 'Personne.dtnai' );?></th>
					<th></th>
					<th></th>
					<th></th>
					<th><?php echo $xpaginator->sort( 'Date début', 'Contratinsertion.dd_ci' );?></th>
					<th><?php echo $xpaginator->sort( 'Date fin', 'Contratinsertion.df_ci' );?></th>
					<th><?php echo $xpaginator->sort( 'Rang', 'Contratinsertion.rg_ci' );?></th>
					<th><?php echo $xpaginator->sort( 'Date', 'Commissionep.dateseance' );?></th>
					<th><?php echo $xpaginator->sort( 'Motif', 'Dossierep.themeep' );?></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $indicateurs as $index => $indicateur ):?>
					<?php 
						$adresse = Set::classicExtract( $indicateur, 'Adresse.numvoie' ).' '.Set::classicExtract( $typevoie, Set::classicExtract( $indicateur, 'Adresse.typevoie' ) ).' '.Set::classicExtract( $indicateur, 'Adresse.nomvoie' ).'<br /> '.Set::classicExtract( $indicateur, 'Adresse.compladr' ).'<br /> '.Set::classicExtract( $indicateur, 'Adresse.codepos' ).' '.Set::classicExtract( $indicateur, 'Adresse.locaadr' );
					
						echo $xhtml->tableCells(
							array(
								h( $indicateur['Dossier']['matricule'] ),
								h( $indicateur['Personne']['nom_complet'] ),
// 								h( $indicateur['Personne']['qual'].' '.$indicateur['Personne']['nom'].' '.$indicateur['Personne']['prenom']),
								h( date_short( $indicateur['Personne']['dtnai'] ) ),
								$adresse,
								h( $indicateur['Personne']['qualcjt'].' '.$indicateur['Personne']['nomcjt'].' '.$indicateur['Personne']['prenomcjt']),
								h( date_short( $indicateur['Dossier']['dtdemrsa'] ) ),
								h( $indicateur['Referentorient']['nom_complet'] ),
								h( date_short( $indicateur['Orientstruct']['date_valid'])),
								h( $indicateur['Orientstruct']['rgorient']),
								h( $indicateur['Referentunique']['nom_complet'] ),
								h( date_short( $indicateur['Contratinsertion']['dd_ci'] ) ),
								h( date_short( $indicateur['Contratinsertion']['df_ci'] ) ),
								h( $indicateur['Contratinsertion']['rg_ci']),
								h( Set::enum( $indicateur['Historiqueetatpe']['etat'], $etatpe['etat'] ).' '.date_short( $indicateur['Historiqueetatpe']['date'] ) ),
								h( date_short( $indicateur['Commissionep']['dateseance'] ) ),
								h( !empty( $indicateur['Dossierep']['themeep'] ) ? Set::classicExtract( $options['themeep'], $indicateur['Dossierep']['themeep'] ) : null ),
								$xhtml->link(
									'Voir',
									array(
										'controller' => 'dossiers',
										'action' => 'view',
										$indicateur['Dossier']['id']
									),
									array(
										'class' => 'external'
									)
								)
							),
							array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
							array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
						);
					?>
				<?php endforeach;?>
			</tbody>
		</table>
		<?php /*debug($indicateurs);*/?>
		<?php echo $pagination;?>
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
					array( 'controller' => 'indicateurssuivis', 'action' => 'exportcsv', implode_assoc( '/', ':', array_unisize( $this->data ) ) )
				);
			?></li>
		</ul>
	<?php else:?>
		<p>Vos critères n'ont retourné aucun résultat.</p>
	<?php endif?>
<?php endif?>








