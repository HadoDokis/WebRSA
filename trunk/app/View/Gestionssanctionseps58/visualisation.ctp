<?php
	$this->pageTitle = 'Visualisation des sanctions émises par l\'EP';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $this->Xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $this->Html->script( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}
?>

<h1><?php echo $this->pageTitle;?></h1>

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
		observeDisableFieldsetOnCheckbox( 'SearchCommissionepDateseance', $( 'SearchCommissionepDateseanceFromDay' ).up( 'fieldset' ), false );
		observeDisableFieldsetOnCheckbox( 'SearchDossierDtdemrsa', $( 'SearchDossierDtdemrsaFromDay' ).up( 'fieldset' ), false );
	});
</script>
<?php echo $this->Xform->create( 'Gestionsanctionep58', array( 'type' => 'post', 'action' => 'visualisation', 'id' => 'Search', 'class' => ( ( isset( $this->request->data['Search']['active'] ) && !empty( $this->request->data['Search']['active'] ) ) ? 'folded' : 'unfolded' ) ) );?>


			<?php echo $this->Xform->input( 'Search.active', array( 'type' => 'hidden', 'value' => true ) );?>
			<fieldset>
				<legend>Filtrer par Equipe Pluridisciplinaire</legend>
				<?php
					echo $this->Default2->subform(
						array(
							'Search.Ep.regroupementep_id' => array('type'=>'select', 'label' => __d( 'ep', 'Ep.regroupementep_id' )),
							'Search.Commissionep.name' => array( 'label' => __d( 'commissionep', 'Commissionep.name' )),
							'Search.Commissionep.identifiant' => array( 'label' => __d( 'commissionep', 'Commissionep.identifiant' )),
							'Search.Structurereferente.ville' => array( 'label' => __d( 'structurereferente', 'Structurereferente.ville' ) ),
							'Search.Dossierep.themeep' => array( 'label' => __d( 'dossierep', 'Dossierep.themeep' ), 'type' => 'select' )
						),
						array(
							'options' => $options
						)
					);
					echo $this->Xform->input( 'Search.Commissionep.dateseance', array( 'label' => 'Filtrer par date de Commission', 'type' => 'checkbox' ) );
				?>
				<fieldset>
					<legend>Filtrer par période</legend>
					<?php
						$dateseance_from = Set::check( $this->request->data, 'Search.Commissionep.dateseance_from' ) ? Set::extract( $this->request->data, 'Search.Commissionep.datecomite_from' ) : strtotime( '-1 week' );
						$dateseance_to = Set::check( $this->request->data, 'Search.Commissionep.dateseance_to' ) ? Set::extract( $this->request->data, 'Search.Commissionep.datecomite_to' ) : strtotime( 'now' );
					?>
					<?php echo $this->Xform->input( 'Search.Commissionep.dateseance_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1, 'minYear' => date( 'Y' ) - 10, 'selected' => $dateseance_from ) );?>
					<?php echo $this->Xform->input( 'Search.Commissionep.dateseance_to', array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1, 'minYear' => date( 'Y' ) - 10, 'selected' => $dateseance_to ) );?>
				</fieldset>
			</fieldset>

			<fieldset>
            <legend>Filtrer par Dossier</legend>
				<?php echo $this->Xform->input( 'Search.Dossier.dtdemrsa', array( 'label' => 'Filtrer par date de demande', 'type' => 'checkbox' ) );?>
			<fieldset>
				<legend>Date de demande RSA</legend>
				<?php
					$dtdemrsaFromSelected = $dtdemrsaToSelected = array();
					if( !dateComplete( $this->request->data, 'Search.Dossier.dtdemrsa_from' ) ) {
						$dtdemrsaFromSelected = array( 'selected' => strtotime( '-1 week' ) );
					}
					if( !dateComplete( $this->request->data, 'Search.Dossier.dtdemrsa_to' ) ) {
						$dtdemrsaToSelected = array( 'selected' => strtotime( 'today' ) );
					}

					echo $this->Xform->input( 'Search.Dossier.dtdemrsa_from', Set::merge( array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 20 ), $dtdemrsaFromSelected ) );

					echo $this->Xform->input( 'Search.Dossier.dtdemrsa_to', Set::merge( array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 5, 'minYear' => date( 'Y' ) - 20), $dtdemrsaToSelected ) );
				?>
			</fieldset>
			<fieldset>
				<?php
					$valueDossierDernier = isset( $this->request->data['Dossier']['dernier'] ) ? $this->request->data['Dossier']['dernier'] : true;
					echo $this->Xform->input( 'Search.Dossier.dernier', array( 'label' => 'Uniquement la dernière demande RSA pour un même allocataire', 'type' => 'checkbox', 'checked' => $valueDossierDernier ) );
				?>
			</fieldset>
			<?php
				if( !is_null($etatdosrsa)) {
					echo $this->Search->etatdosrsa( $etatdosrsa, 'Search.Situationdossierrsa.etatdosrsa' );
				}
			?>
            <?php

                echo $this->Default2->subform(
                    array(
                        'Search.Personne.nom' => array( 'label' => __d( 'personne', 'Personne.nom' ), 'type' => 'text' ),
                        'Search.Personne.prenom' => array( 'label' => __d( 'personne', 'Personne.prenom' ), 'type' => 'text' ),
                        'Search.Personne.nomnai' => array( 'label' => __d( 'personne', 'Personne.nomnai' ), 'type' => 'text' ),
                        'Search.Personne.nir' => array( 'label' => __d( 'personne', 'Personne.nir' ), 'type' => 'text', 'maxlength' => 15 ),
                        'Search.Dossier.matricule' => array( 'label' => __d( 'dossier', 'Dossier.matricule' ), 'type' => 'text', 'maxlength' => 15 ),
                        'Search.Dossier.numdemrsa' => array( 'label' => __d( 'dossier', 'Dossier.numdemrsa' ), 'type' => 'text', 'maxlength' => 15 ),
						'Search.Adresse.locaadr' => array( 'label' => __d( 'adresse', 'Adresse.locaadr' ), 'type' => 'text' ),
						'Search.Adresse.numcomptt' => array( 'label' => __d( 'adresse', 'Adresse.numcomptt' ), 'type' => 'select', 'options' => $mesCodesInsee, 'empty' => true )
                    ),
                    array(
                        'options' => $options
                    )
                );

				if( Configure::read( 'CG.cantons' ) ) {
					echo $this->Xform->input( 'Search.Canton.canton', array( 'label' => 'Canton', 'type' => 'select', 'options' => $cantons, 'empty' => true ) );
				}
            ?>

    <div class="submit noprint">
        <?php echo $this->Xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
        <?php echo $this->Xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>
</fieldset>
<?php echo $this->Xform->end();?>

<?php if( isset( $gestionsanctionseps58 ) ):?>
    <?php if( empty( $gestionsanctionseps58 ) ):?>
        <p class="notice"><?php echo 'Aucune sanction présente.';?></p>
    <?php else:?>
<?php $pagination = $this->Xpaginator->paginationBlock( 'Personne', $this->passedArgs ); ?>
	<?php echo $pagination;?>
	<?php echo $this->Xform->create( 'Gestionsanctionep58', array( 'url'=> Router::url( null, true ) ) );?>
	<?php
		foreach( Hash::flatten( $this->request->data['Search'] ) as $filtre => $value  ) {
			echo $this->Xform->input( "Search.{$filtre}", array( 'type' => 'hidden', 'value' => $value ) );
		}
	?>
    <table id="searchResults" class="default2">
        <thead>
            <tr>
                <th>Allocataire</th>
                <th>Commune allocataire</th>
                <th>Identifiant EP</th>
                <th>Identifiant commission</th>
                <th>Date de la commission</th>
                <th>Thématique</th>
				<th>Sanction 1</th>
				<th>Sanction 2</th>
				<th>Modification de la sanction</th>
				<th>Date fin de sanction</th>
				<th>Commentaire</th>
				<th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach( $gestionsanctionseps58 as $index => $gestionanctionep58 ):?>
            <?php

				if( $gestionanctionep58['Dossierep']['themeep'] == 'sanctionseps58' ) {
					// Type de sanction
					$decisionSanction1 = Set::enum( $gestionanctionep58['Decisionsanctionep58']['decision'], $regularisationlistesanctionseps58['Decisionsanctionep58']['decision'] );
					$decisionSanction2 = Set::enum( $gestionanctionep58['Decisionsanctionep58']['decision2'], $regularisationlistesanctionseps58['Decisionsanctionep58']['decision'] );
					// Libellé de la sanction
					$libelleSanction1 = Set::enum( $gestionanctionep58['Decisionsanctionep58']['listesanctionep58_id'], $listesanctionseps58 );
					$libelleSanction2 = Set::enum( $gestionanctionep58['Decisionsanctionep58']['autrelistesanctionep58_id'], $listesanctionseps58 );

					$fieldDecisionSanction = Set::enum( $gestionanctionep58['Decisionsanctionep58']['arretsanction'], $options['Decisionsanctionep58']['arretsanction'] );
					$dateFinSanction = date_short( $gestionanctionep58['Decisionsanctionep58']['datearretsanction'] );
					$commentaireFinSanction = $gestionanctionep58['Decisionsanctionep58']['commentairearretsanction'];
				}
				else {
					// Type de sanction
					$decisionSanction1 = Set::enum( $gestionanctionep58['Decisionsanctionrendezvousep58']['decision'], $regularisationlistesanctionseps58['Decisionsanctionrendezvousep58']['decision'] );
					$decisionSanction2 = Set::enum( $gestionanctionep58['Decisionsanctionrendezvousep58']['decision2'], $regularisationlistesanctionseps58['Decisionsanctionrendezvousep58']['decision'] );

					// Libellé de la sanction
					$libelleSanction1 = Set::enum( $gestionanctionep58['Decisionsanctionrendezvousep58']['listesanctionep58_id'], $listesanctionseps58 );
					$libelleSanction2 = Set::enum( $gestionanctionep58['Decisionsanctionrendezvousep58']['autrelistesanctionep58_id'], $listesanctionseps58 );

					//Champ permettant la modification de la sanction
					$fieldDecisionSanction = Set::enum( $gestionanctionep58['Decisionsanctionrendezvousep58']['arretsanction'], $options['Decisionsanctionep58']['arretsanction'] );
					$dateFinSanction = date_short( $gestionanctionep58['Decisionsanctionrendezvousep58']['datearretsanction'] );
					$commentaireFinSanction = $gestionanctionep58['Decisionsanctionrendezvousep58']['commentairearretsanction'];
				}

				$tableCells = array(
						h( $gestionanctionep58['Personne']['qual'].' '.$gestionanctionep58['Personne']['nom'].' '.$gestionanctionep58['Personne']['prenom'] ),
						nl2br( h( Set::classicExtract(  $gestionanctionep58, 'Adresse.numvoie' ).' '.Set::classicExtract(  $typevoie, Set::classicExtract( $gestionanctionep58, 'Adresse.typevoie' ) ).' '.Set::classicExtract(  $gestionanctionep58, 'Adresse.nomvoie' )."\n".Set::classicExtract(  $gestionanctionep58, 'Adresse.codepos' ).' '.Set::classicExtract(  $gestionanctionep58, 'Adresse.locaadr' ) ) ),
						h( $gestionanctionep58['Ep']['identifiant'] ),
						h( $gestionanctionep58['Commissionep']['identifiant'] ),
						h( date_short( $gestionanctionep58['Commissionep']['dateseance'] ) ),
						h( Set::classicExtract( $options['Dossierep']['themeep'], ( $gestionanctionep58['Dossierep']['themeep'] ) ) ),
						nl2br( $decisionSanction1."\n".$libelleSanction1 ),
						nl2br( $decisionSanction2."\n".$libelleSanction2 ),
						$fieldDecisionSanction,
						$dateFinSanction,
						$commentaireFinSanction,
						$this->Xhtml->viewLink(
							'Voir le dossier',
							array( 'controller' => 'historiqueseps', 'action' => 'view_passage', $gestionanctionep58['Passagecommissionep']['id'] ),
							$this->Permissions->check( 'historiqueseps', 'view_passage' )
						),
						$this->Default2->button(
							'suivisanction1',
							array( 'controller' => 'gestionssanctionseps58', 'action' => 'impressionSanction1', '1',
							$gestionanctionep58['Passagecommissionep']['id'], $gestionanctionep58['Dossierep']['themeep'] ),
							array( 'enabled' =>( $this->Permissions->check( 'gestionssanctionseps58', 'impressionSanction1' ) == 1 ) )
						),
						$this->Default2->button(
							'suivisanction2',
							array( 'controller' => 'gestionssanctionseps58', 'action' => 'impressionSanction2', '2',
							$gestionanctionep58['Passagecommissionep']['id'], $gestionanctionep58['Dossierep']['themeep'] ),
							array(
								'enabled' =>(
									$this->Permissions->check( 'gestionssanctionseps58', 'impressionSanction2' ) == 1 )
									&& !empty( $decisionSanction2 )
								)
						)

					);

					echo $this->Xhtml->tableCells(
						$tableCells,
						array( 'class' => 'odd' ),
						array( 'class' => 'even' )
					);

                ?>
				<?php endforeach;?>
			</tbody>
		</table>
		<?php echo $pagination;?>
		<ul class="actionMenu">
			<li><?php
				echo $this->Xhtml->exportLink(
					'Télécharger le tableau',
					array( 'controller' => 'gestionssanctionseps58', 'action' => 'exportcsv' ) + Hash::flatten( $this->request->data, '__' )
				);
			?></li>

			<li><?php
				echo $this->Default2->button(
					'printcohorte1',
					Set::merge(
						array(
							'controller' => 'gestionssanctionseps58', 'action' => 'impressionsSanctions1'
						),
						Hash::flatten( $this->request->params['named'], '__' )
					)
				);

			?></li>

			<li><?php
				echo $this->Default2->button(
					'printcohorte2',
					Set::merge(
						array(
							'controller' => 'gestionssanctionseps58', 'action' => 'impressionsSanctions2'
						),
						Hash::flatten( $this->request->params['named'], '__' )
					)
				);
			?></li>

		</ul>
	<?php endif;?>
<?php endif;?>