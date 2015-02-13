<?php
	$this->pageTitle = 'Recherche par CER';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all', 'inline' => false ) );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}
?>
<h1><?php
    echo $this->pageTitle;
//    $this->set('title_for_layout', $this->pageTitle);
?></h1>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		observeDisableFieldsetOnCheckbox( 'ContratinsertionCreated', $( 'ContratinsertionCreatedFromDay' ).up( 'fieldset' ), false );

		observeDisableFieldsetOnCheckbox( 'ContratinsertionDdCi', $( 'ContratinsertionDdCiFromDay' ).up( 'fieldset' ), false );

		observeDisableFieldsetOnCheckbox( 'ContratinsertionDfCi', $( 'ContratinsertionDfCiFromDay' ).up( 'fieldset' ), false );

		dependantSelect( 'ContratinsertionReferentId', 'ContratinsertionStructurereferenteId' );
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
?>
<?php $pagination = $this->Xpaginator->paginationBlock( 'Contratinsertion', $this->passedArgs );?>
<?php echo $this->Form->create( 'Critereci', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( ( is_array( $this->request->data ) && !empty( $this->request->data['Contratinsertion']['recherche'] ) ) ? 'folded' : 'unfolded' ) ) );?>
	<?php
		echo $this->Search->blocAllocataire( $trancheage );
		echo $this->Search->blocAdresse( $mesCodesInsee, $cantons );
	?>
	<fieldset>
		<legend>Recherche par dossier</legend>
		<?php
			echo $this->Form->input( 'Dossier.numdemrsa', array( 'label' => 'Numéro de demande RSA' ) );
			echo $this->Form->input( 'Dossier.matricule', array( 'label' => __d( 'dossier', 'Dossier.matricule' ), 'maxlength' => 15 ) );
			echo $this->Search->natpf( $natpf );

			$valueDossierDernier = isset( $this->request->data['Dossier']['dernier'] ) ? $this->request->data['Dossier']['dernier'] : true;
			echo $this->Form->input( 'Dossier.dernier', array( 'label' => 'Uniquement la dernière demande RSA pour un même allocataire', 'type' => 'checkbox', 'checked' => $valueDossierDernier ) );
			echo $this->Search->etatdosrsa( $etatdosrsa );

			echo $this->Search->referentParcours( $structuresreferentesparcours, $referentsparcours );
		?>
	</fieldset>

	<fieldset>
		<legend>Recherche par CER</legend>
			<?php
				$valueContratinsertionDernier = isset( $this->request->data['Contratinsertion']['dernier'] ) ? $this->request->data['Contratinsertion']['dernier'] : false;
				echo $this->Form->input( 'Contratinsertion.dernier', array( 'label' => 'Uniquement le dernier contrat d\'insertion pour un même allocataire', 'type' => 'checkbox', 'checked' => $valueContratinsertionDernier ) );
			?>
			<?php echo $this->Form->input( 'Contratinsertion.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>
			<?php if(Configure::read( 'Cg.departement' ) != 58 ){
					echo $this->Form->input( 'Contratinsertion.forme_ci', array(  'type' => 'radio', 'options' => $forme_ci, 'legend' => 'Forme du contrat', 'div' => false, ) );
				}
			?>

			<?php echo $this->Form->input( 'Contratinsertion.created', array( 'label' => 'Filtrer par date de saisie du contrat', 'type' => 'checkbox' ) );?>
			<fieldset>
				<legend>Date de saisie du contrat</legend>
				<?php
					$created_from = Set::check( $this->request->data, 'Contratinsertion.created_from' ) ? Set::extract( $this->request->data, 'Contratinsertion.created_from' ) : strtotime( '-1 week' );
					$created_to = Set::check( $this->request->data, 'Contratinsertion.created_to' ) ? Set::extract( $this->request->data, 'Contratinsertion.created_to' ) : strtotime( 'now' );
				?>
				<?php echo $this->Form->input( 'Contratinsertion.created_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $created_from ) );?>
				<?php echo $this->Form->input( 'Contratinsertion.created_to', array( 'label' => 'Au (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120,  'maxYear' => date( 'Y' ) + 5, 'selected' => $created_to ) );?>
			</fieldset>

			<?php echo $this->Form->input( 'Contratinsertion.structurereferente_id', array( 'label' => __d( 'rendezvous', 'Rendezvous.lib_struct' ), 'type' => 'select', 'options' => $struct, 'empty' => true ) ); ?>
			<?php echo $this->Form->input( 'Contratinsertion.referent_id', array( 'label' => __( 'Nom du référent' ), 'type' => 'select', 'options' => $referents, 'empty' => true ) ); ?>
			<?php
				if( Configure::read( 'Cg.departement' ) == 93 ) {
					echo $this->Form->input( 'Cer93.positioncer', array( 'label' => 'Statut du contrat', 'type' => 'select', 'options' => (array)Hash::get( $options, 'Cer93.positioncer' ), 'empty' => true ) );
				}
				else {
					echo $this->Form->input( 'Contratinsertion.decision_ci', array( 'label' => 'Statut du contrat', 'type' => 'select', 'options' => $decision_ci, 'empty' => true ) );
				}

				if( Configure::read( 'Cg.departement' ) == 66 ) {
					echo $this->Form->input( 'Contratinsertion.positioncer', array( 'label' => 'Position du contrat', 'type' => 'select', 'options' => $numcontrat['positioncer'], 'empty' => true ) );
				}

				echo $this->Form->input( 'Contratinsertion.duree_engag', array( 'label' => 'Filtrer par durée du CER', 'type' => 'select', 'empty' => true, 'options' => $duree_engag ) );

				if( Configure::read( 'Cg.departement' ) == 93 ) {
					echo $this->Romev3->fieldset( 'Emptrouvromev3', array( 'options' => array( 'Emptrouvromev3' => $options['Catalogueromev3'] ) ) );

					echo $this->Html->tag(
						'fieldset',
						$this->Html->tag( 'legend', 'Filtrer par emploi trouvé (codes INSEE)' )
						.$this->Form->input( 'Expprocer93.metierexerce_id', array( 'label' => 'Métier exercé', 'type' => 'select', 'options' => (array)Hash::get( $options, 'Expprocer93.metierexerce_id' ), 'empty' => true ) )
						.$this->Form->input( 'Expprocer93.secteuracti_id', array( 'label' => 'Secteur d\'activité', 'type' => 'select', 'options' => (array)Hash::get( $options, 'Expprocer93.secteuracti_id' ), 'empty' => true ) )
					);

					echo $this->Html->tag(
						'fieldset',
						$this->Html->tag( 'legend', 'Filtrer par ce sur quoi le contrat porte' )
						.$this->Form->input( 'Cer93Sujetcer93.sujetcer93_id', array( 'label' => 'Sujet du CER', 'type' => 'select', 'options' => (array)Hash::get( $options, 'Cer93Sujetcer93.sujetcer93_id' ), 'empty' => true ) )
						.$this->Form->input( 'Cer93Sujetcer93.soussujetcer93_id', array( 'label' => 'Sous sujet du CER', 'type' => 'select', 'options' => (array)Hash::get( $options, 'Cer93Sujetcer93.soussujetcer93_id' ), 'empty' => true ) )
						.$this->Form->input( 'Cer93Sujetcer93.valeurparsoussujetcer93_id', array( 'label' => 'Valeur par sous sujet du CER', 'type' => 'select', 'options' => (array)Hash::get( $options, 'Cer93Sujetcer93.valeurparsoussujetcer93_id' ), 'empty' => true ) )
					);
				}

                echo $this->Search->date( 'Contratinsertion.datevalidation_ci', 'Date de validation du contrat' );
            ?>

			<!-- Contratinsertion sur la date de début du CER -->
			<?php echo $this->Form->input( 'Contratinsertion.dd_ci', array( 'label' => 'Filtrer par date de début du contrat', 'type' => 'checkbox' ) );?>
			<fieldset>
				<legend>Date de début du contrat</legend>
				<?php
					$dd_ci_from = Set::check( $this->request->data, 'Contratinsertion.dd_ci_from' ) ? Set::extract( $this->request->data, 'Contratinsertion.dd_ci_from' ) : strtotime( '-1 week' );
					$dd_ci_to = Set::check( $this->request->data, 'Contratinsertion.dd_ci_to' ) ? Set::extract( $this->request->data, 'Contratinsertion.dd_ci_to' ) : strtotime( 'now' );
				?>
				<?php echo $this->Form->input( 'Contratinsertion.dd_ci_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $dd_ci_from ) );?>
				<?php echo $this->Form->input( 'Contratinsertion.dd_ci_to', array( 'label' => 'Au (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120,  'maxYear' => date( 'Y' ) + 5, 'selected' => $dd_ci_to ) );?>
			</fieldset>

			<!-- Contratinsertion sur la date de fin du CER -->
			<?php echo $this->Form->input( 'Contratinsertion.df_ci', array( 'label' => 'Contratinsertionr par date de fin du contrat', 'type' => 'checkbox' ) );?>
			<fieldset>
				<legend>Date de fin du contrat</legend>
				<?php
					$df_ci_from = Set::check( $this->request->data, 'Contratinsertion.df_ci_from' ) ? Set::extract( $this->request->data, 'Contratinsertion.df_ci_from' ) : strtotime( '-1 week' );
					$df_ci_to = Set::check( $this->request->data, 'Contratinsertion.df_ci_to' ) ? Set::extract( $this->request->data, 'Contratinsertion.df_ci_to' ) : strtotime( 'now' );
				?>
				<?php echo $this->Form->input( 'Contratinsertion.df_ci_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $df_ci_from ) );?>
				<?php echo $this->Form->input( 'Contratinsertion.df_ci_to', array( 'label' => 'Au (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120,  'maxYear' => date( 'Y' ) + 5, 'selected' => $df_ci_to ) );?>
			</fieldset>

			<?php echo $this->Search->date( 'Contratinsertion.periode_validite', 'Période de validité' );?>

			<?php
				echo $this->Form->input( 'Contratinsertion.arriveaecheance', array( 'label' => 'Allocataire dont le CER est arrivé à échéance', 'type' => 'checkbox' )  );
				echo $this->Form->input( 'Contratinsertion.echeanceproche', array( 'label' => 'CER arrivant à échéance (par défaut, se terminant sous 1 mois)', 'type' => 'checkbox' )  );
			?>

			<?php if( Configure::read( 'Cg.departement' ) == 66 ) {
					$nbjours = Configure::read( 'Criterecer.delaidetectionnonvalidnotifie' );
					$nbjoursTranslate = str_replace('days','jours', $nbjours);

					echo $this->Form->input( 'Contratinsertion.notifienonvalide', array( 'label' => 'CER non validé et notifié il y a '.$nbjoursTranslate, 'type' => 'checkbox' )  );
					echo $this->Form->input( 'Contratinsertion.istacitereconduction', array( 'label' => 'Hors tacite reconduction', 'type' => 'checkbox' )  );
				}
			?>
	</fieldset>
	<fieldset>
		<legend>Filtrer par dernière orientation</legend>
		<?php
			if( Configure::read( 'Cg.departement' ) == 58 ) {
				echo $this->Form->input( 'Personne.etat_dossier_orientation', array( 'label' => __d( 'personne', 'Personne.etat_dossier_orientation' ), 'type' => 'select', 'options' => (array)Hash::get( $options, 'Personne.etat_dossier_orientation' ), 'empty' => true ) );
			}
			echo $this->Form->input( 'Orientstruct.typeorient', array( 'label' => 'Type d\'orientation', 'type' => 'select', 'empty' => true, 'options' => $typesorients )  );
			if( Configure::read( 'Cg.departement' ) == 66 ) {
				echo $this->Form->input( 'TypeorientAExclure.id', array( 'label' => 'Type d\'orientation à exclure', 'type' => 'select', 'multiple' => 'checkbox', 'options' => $typesorientsNiveau0 )  );
			}
		?>
	</fieldset>
	<?php echo $this->Search->paginationNombretotal( 'Contratinsertion.paginationNombreTotal' );?>
	<div class="submit noprint">
		<?php echo $this->Form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
		<?php echo $this->Form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
	</div>

<?php echo $this->Form->end();?>

<!-- Résultats -->
<?php if( isset( $contrats ) ):?>

	<h2 class="noprint">Résultats de la recherche</h2>

	<?php if( is_array( $contrats ) && count( $contrats ) > 0  ):?>

		<?php echo $pagination;?>
		<table id="searchResults" class="tooltips">
			<thead>
				<tr>
					<th><?php echo $this->Xpaginator->sort( 'Nom de l\'allocataire', 'Personne.nom' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Commune de l\'allocataire', 'Adresse.nomcom' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Référent lié', 'Referent.nom_complet' );?></th>
					<th><?php echo $this->Xpaginator->sort( __d( 'dossier', 'Dossier.matricule' ), 'Dossier.matricule' );?></th>
					<?php if( Configure::read( 'Cg.departement') == 93 ):?>
						<th><?php echo $this->Xpaginator->sort( 'Type d\'orientation', 'Typeorient.lib_type_orient' );?></th>
					<?php endif;?>
					<th><?php echo $this->Xpaginator->sort( 'Date de saisie du contrat', 'Contratinsertion.created' );?></th>
					<?php if( Configure::read( 'Cg.departement') == 93 ):?>
						<th><?php echo $this->Xpaginator->sort( 'Durée du contrat', 'Contratinsertion.duree_engag' );?></th>
					<?php endif;?>
					<th><?php echo $this->Xpaginator->sort( 'Rang du contrat', 'Contratinsertion.rg_ci' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Décision', 'Contratinsertion.decision_ci' ).$this->Xpaginator->sort( ' ', 'Contratinsertion.datevalidation_ci' );?></th>
					<th><?php echo $this->Xpaginator->sort( 'Forme du CER', 'Contratinsertion.forme_ci' );?></th>
					<?php if( Configure::read( 'Cg.departement') != 93 ):?>
                        <th><?php echo $this->Xpaginator->sort( 'Position du CER', 'Contratinsertion.positioncer' );?></th>
                    <?php endif;?>
					<th><?php echo $this->Xpaginator->sort( 'Date de fin du contrat', 'Contratinsertion.df_ci' );?></th>
					<th class="action noprint">Actions</th>
					<th class="innerTableHeader noprint">Informations complémentaires</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$controller = 'contratsinsertion';
					if( Configure::read( 'Cg.departement' ) == 93 ) {
						$controller = 'cers93';
					}
					foreach( $contrats as $index => $contrat ):?>
					<?php
						$title = $contrat['Dossier']['numdemrsa'];
//debug($contrat);
						/***/
						$position = Set::classicExtract( $contrat, 'Contratinsertion.positioncer' );
						$datenotif = Set::classicExtract( $contrat, 'Contratinsertion.datenotification' );
						if( empty( $datenotif ) ) {
							$positioncer = Set::enum( Set::classicExtract( $contrat, 'Contratinsertion.positioncer' ), $numcontrat['positioncer'] );
						}
						else if( !empty( $datenotif ) && in_array( $position, array( 'nonvalid', 'encours' ) ) ){
							$positioncer = Set::enum( Set::classicExtract( $contrat, 'Contratinsertion.positioncer' ), $numcontrat['positioncer'] ).' le '.date_short( $datenotif );
						}
						else {
							$positioncer = Set::enum( Set::classicExtract( $contrat, 'Contratinsertion.positioncer' ), $numcontrat['positioncer'] );
						}

						$innerTableParCg = '';
						if( Configure::read( 'Cg.departement' ) == 58 ) {
							$innerTableParCg .= '<tr>
								<th>'.__d( 'personne', 'Personne.etat_dossier_orientation' ).'</th>
								<td>'.h( value( (array)Hash::get( $options, 'Personne.etat_dossier_orientation' ), Hash::get( $contrat, 'Personne.etat_dossier_orientation' ) ) ).'</td>
							</tr>';
						}

						$innerTable = '<table id="innerTablesearchResults'.$index.'" class="innerTable">
							<tbody>
							<!-- <tr>
									<th>Commune de naissance</th>
									<td>'.$contrat['Personne']['nomcomnai'].'</td>
								</tr> -->
								<tr>
									<th>Date de naissance</th>
									<td>'.date_short( $contrat['Personne']['dtnai'] ).'</td>
								</tr>
								<tr>
									<th>Code INSEE</th>
									<td>'.$contrat['Adresse']['numcom'].'</td>
								</tr>
								<tr>
									<th>NIR</th>
									<td>'.$contrat['Personne']['nir'].'</td>
								</tr>
								<tr>
									<th>Rôle</th>
									<td>'.value( $rolepers, Set::classicExtract( $contrat, 'Prestation.rolepers' ) ).'</td>
								</tr>
								<tr>
									<th>État du dossier</th>
									<td>'.value( $etatdosrsa, Set::classicExtract( $contrat, 'Situationdossierrsa.etatdosrsa' ) ).'</td>
								</tr>
								<tr>
									<th>'.__d( 'search_plugin', 'Structurereferenteparcours.lib_struc' ).'</th>
									<td>'.Hash::get( $contrat, 'Structurereferenteparcours.lib_struc' ).'</td>
								</tr>
								<tr>
									<th>'.__d( 'search_plugin', 'Referentparcours.nom_complet' ).'</th>
									<td>'.Hash::get( $contrat, 'Referentparcours.nom_complet' ).'</td>
								</tr>
								'.$innerTableParCg.'
							</tbody>
						</table>';

                        if( Configure::read( 'Cg.departement' ) != 93 ) {
                            echo $this->Xhtml->tableCells(
                                array(
                                    h( $contrat['Personne']['nom'].' '.$contrat['Personne']['prenom'] ),
                                    h( $contrat['Adresse']['nomcom'] ),
                                    h( @$contrat['Referent']['nom_complet'] ),
                                    h( $contrat['Dossier']['matricule'] ),
                                    h( $this->Locale->date( 'Date::short', Set::extract( $contrat, 'Contratinsertion.date_saisi_ci' ) ) ),
                                    h( $contrat['Contratinsertion']['rg_ci'] ),
                                    h( Set::extract( $decision_ci, Set::extract( $contrat, 'Contratinsertion.decision_ci' ) ).' '.$this->Locale->date( 'Date::short', Set::extract( $contrat, 'Contratinsertion.datevalidation_ci' ) ) ),//date_short($contrat['Contratinsertion']['datevalidation_ci']) ),
                                    h( Set::enum( $contrat['Contratinsertion']['forme_ci'], $forme_ci ) ),
                                    h( $positioncer ),
                                    h( $this->Locale->date( 'Date::short', Set::extract( $contrat, 'Contratinsertion.df_ci' ) ) ),
                                    array(
                                        $this->Xhtml->viewLink(
                                            'Voir le dossier « '.$title.' »',
                                            array( 'controller' => $controller, 'action' => 'index', $contrat['Contratinsertion']['personne_id'] )
                                        ),
                                        array( 'class' => 'noprint' )
                                    ),
                                    array( $innerTable, array( 'class' => 'innerTableCell noprint' ) ),
                                ),
                                array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
                                array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
                            );
                        }
                        else {
							$lib_type_orient = Hash::get( $contrat, 'Typeorient.lib_type_orient' );
							$duree = Hash::get( $contrat, 'Cer93.duree' );
							if( empty( $duree ) ) {
								$duree = Set::enum( $contrat['Contratinsertion']['duree_engag'], $duree_engag );
							}
							else {
								$duree = "{$duree} mois";
							}

							echo $this->Xhtml->tableCells(
                                array(
                                    h( $contrat['Personne']['nom'].' '.$contrat['Personne']['prenom'] ),
                                    h( $contrat['Adresse']['nomcom'] ),
                                    h( @$contrat['Referent']['nom_complet'] ),
                                    h( $contrat['Dossier']['matricule'] ),
									h( empty( $lib_type_orient ) ? 'Non orienté' : $lib_type_orient ),
                                    h( $this->Locale->date( 'Date::short', Set::extract( $contrat, 'Contratinsertion.created' ) ) ),
									h( $duree ),
                                    h( $contrat['Contratinsertion']['rg_ci'] ),
                                    h(
										Hash::get( $options['Cer93']['positioncer'], Hash::get( $contrat, 'Cer93.positioncer' ) )
										.(
											Hash::get( $contrat, 'Contratinsertion.decision_ci' ) == 'V'
											? ' '.$this->Locale->date( 'Date::short', Hash::get( $contrat, 'Contratinsertion.datedecision' ) )
											: ''
										)
									),
                                    h( Set::enum( $contrat['Contratinsertion']['forme_ci'], $forme_ci ) ),
                                    h( $this->Locale->date( 'Date::short', Set::extract( $contrat, 'Contratinsertion.df_ci' ) ) ),
                                    array(
                                        $this->Xhtml->viewLink(
                                            'Voir le dossier « '.$title.' »',
                                            array( 'controller' => $controller, 'action' => 'index', $contrat['Contratinsertion']['personne_id'] )
                                        ),
                                        array( 'class' => 'noprint' )
                                    ),
                                    array( $innerTable, array( 'class' => 'innerTableCell noprint' ) ),
                                ),
                                array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
                                array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
                            );
                        }
					?>
				<?php endforeach;?>
			</tbody>
		</table>
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
					array( 'controller' => 'criteresci', 'action' => 'exportcsv' ) + Hash::flatten( $this->request->data, '__' ),
					( $this->Permissions->check( 'criteresci', 'exportcsv' ) )
				);
			?></li>
		</ul>
	<?php echo $pagination;?>

	<?php else:?>
		<p class="notice">Vos critères n'ont retourné aucun dossier.</p>
	<?php endif?>

<?php endif?>

<?php
	if( Configure::read( 'Cg.departement' ) == 93 ) {
		echo $this->Observer->dependantSelect(
			array(
				'Cer93Sujetcer93.sujetcer93_id' => 'Cer93Sujetcer93.soussujetcer93_id',
				'Cer93Sujetcer93.soussujetcer93_id' => 'Cer93Sujetcer93.valeurparsoussujetcer93_id',
			)
		);
	}

	echo $this->Search->observeDisableFormOnSubmit( 'Search' );
?>