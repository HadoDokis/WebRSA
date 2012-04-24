<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<h1><?php echo $this->pageTile = 'Indicateurs de suivi';?></h1>

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
	$pagination = $xpaginator->paginationBlock( 'Personne', $this->passedArgs );
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

	<?php if( is_array( $indicateurs ) && count( $indicateurs ) > 0 ):?>
		<table id="searchResults" class="tooltips">
			<thead>
				<tr>
					<th rowspan="2"><?php echo $xpaginator->sort( 'Numéro CAF/MSA', 'Dossier.matricule' );?></th>
					<th colspan="3">Demandeur</th>
					<th rowspan="2">Adresse</th>
					<th colspan="2">Conjoint</th>
					<th rowspan="2"><?php echo $xpaginator->sort( 'Date ouverture de droits', 'Dossier.dtdemrsa' );?></th>
					<th rowspan="2"><?php echo $xpaginator->sort( 'Réf. chargé de l\'évaluation', 'Orientstruct.referent_id' );?></th>
					<th colspan="2">COV</th>
					<th rowspan="2"><?php echo $xpaginator->sort( 'Référent unique', 'PersonneReferent.referent_id' );?></th>
					<th colspan="3">CER</th>
					<th rowspan="2">Date inscription Pôle Emploi<?php //echo $xpaginator->sort( 'Date inscription Pôle Emploi', '' );?></th>
					<th colspan="2">Passage en EP</th>
				</tr>
				<tr>
					<th><?php echo $xpaginator->sort( 'Nom', 'Personne.nom' );?></th>
					<th><?php echo $xpaginator->sort( 'Prénom', 'Personne.prenom' );?></th>
					<th><?php echo $xpaginator->sort( 'Date de naissance', 'Personne.dtnai' );?></th>
					<th>Nom</th>
					<th>Prénom</th>
					<th>Date orientation</th>
					<th><?php echo $xpaginator->sort( 'Rang orientation', 'Orientstruct.rgorient' );?></th>
					<th><?php echo $xpaginator->sort( 'Date début', 'Contratinsertion.dd_ci' );?></th>
					<th><?php echo $xpaginator->sort( 'Date fin', 'Contratinsertion.df_ci' );?></th>
					<th><?php echo $xpaginator->sort( 'Rang', 'Contratinsertion.rg_ci' );?></th>
					<th><?php echo $xpaginator->sort( 'Date', 'Commissionep.dateseance' );?></th>
					<th><?php echo $xpaginator->sort( 'Motif', 'Dossierep.themeep' );?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach( $indicateurs as $index => $indicateur ):?>
					<?php 
						$adresse = Set::extract( $indicateur, 'Adresse.numvoie' ).' '.Set::extract( $typevoie, Set::extract( $indicateur, 'Adresse.typevoie' ) ).' '.Set::extract( $indicateur, 'Adresse.nomvoie' ).'<br /> '.Set::extract( $indicateur, 'Adresse.compladr' ).'<br /> '.Set::extract( $indicateur, 'Adresse.codepos' ).' '.Set::extract( $indicateur, 'Adresse.locaadr' );
					
						echo $xhtml->tableCells(
							array(
								h( $indicateur['Dossier']['matricule'] ),
								h( $indicateur['Personne']['nom']),
								h( $indicateur['Personne']['prenom']),
								h( $indicateur['Personne']['dtnai']),
								$adresse,
								h( $indicateur['Personne']['nomcjt']),
								h( $indicateur['Personne']['prenomcjt']),
								h( date_short( $indicateur['Dossier']['dtdemrsa'] ) ),
								h( Set::extract( $referents, Set::extract( $indicateur, 'Orientstruct.referent_id' ) ) ),
								h( date_short( $indicateur['Cov58']['datecommission'])),
								h( $indicateur['Orientstruct']['rgorient']),
								h( Set::extract( $referents, Set::extract( $indicateur, 'PersonneReferent.referent_id' ) ) ),
								h( date_short( $indicateur['Contratinsertion']['dd_ci'] ) ),
								h( date_short( $indicateur['Contratinsertion']['df_ci'] ) ),
								h( $indicateur['Contratinsertion']['rg_ci']),
								'-',	//h( date_short( $indicateur[''][''] ) ),
								h( date_short( $indicateur['Commissionep']['dateseance'] ) ),
								h( Set::extract( $options['themeep'], $indicateur['Dossierep']['themeep'])),								
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
					array( 'controller' => 'indicateurssuivis', 'action' => 'exportcsv', implode_assoc( '/', ':', array_unisize( $this->data ) ) )
				);
			?></li>
		</ul>
	<?php else:?>
		<p>Vos critères n'ont retourné aucun résultat.</p>
	<?php endif?>
<?php endif?>








