<?php
	$this->pageTitle = 'Gestion des sanctions émises par l\'EP';

	if( Configure::read( 'debug' ) > 0 ) {
		echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );
		echo $javascript->link( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}
?>

<h1><?php echo $this->pageTitle;?></h1>

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
		observeDisableFieldsetOnCheckbox( 'SearchCommissionepDateseance', $( 'SearchCommissionepDateseanceFromDay' ).up( 'fieldset' ), false );
		observeDisableFieldsetOnCheckbox( 'SearchDossierDtdemrsa', $( 'SearchDossierDtdemrsaFromDay' ).up( 'fieldset' ), false );
	});
</script>
<?php echo $xform->create( 'Gestionsanctionep58', array( 'type' => 'post', 'action' => 'traitement', 'id' => 'Search', 'class' => ( ( isset( $this->data['Search']['active'] ) && !empty( $this->data['Search']['active'] ) ) ? 'folded' : 'unfolded' ) ) );?>


			<?php echo $xform->input( 'Search.active', array( 'type' => 'hidden', 'value' => true ) );?>
			<fieldset>
				<legend>Filtrer par Equipe Pluridisciplinaire</legend>
				<?php
					echo $default2->subform(
						array(
							'Search.Ep.regroupementep_id' => array('type'=>'select', 'label' => __d( 'ep', 'Ep.regroupementep_id', true )),
							'Search.Commissionep.name' => array( 'label' => __d( 'commissionep', 'Commissionep.name', true )),
							'Search.Commissionep.identifiant' => array( 'label' => __d( 'commissionep', 'Commissionep.identifiant', true )),
							'Search.Structurereferente.ville' => array( 'label' => __d( 'structurereferente', 'Structurereferente.ville', true ) ),
							'Search.Dossierep.themeep' => array( 'label' => __d( 'dossierep', 'Dossierep.themeep', true ), 'type' => 'select' )
						),
						array(
							'options' => $options
						)
					);
					echo $xform->input( 'Search.Commissionep.dateseance', array( 'label' => 'Filtrer par date de Commission', 'type' => 'checkbox' ) );
				?>
				<fieldset>
					<legend>Filtrer par période</legend>
					<?php
						$dateseance_from = Set::check( $this->data, 'Search.Commissionep.dateseance_from' ) ? Set::extract( $this->data, 'Search.Commissionep.datecomite_from' ) : strtotime( '-1 week' );
						$dateseance_to = Set::check( $this->data, 'Search.Commissionep.dateseance_to' ) ? Set::extract( $this->data, 'Search.Commissionep.datecomite_to' ) : strtotime( 'now' );
					?>
					<?php echo $xform->input( 'Search.Commissionep.dateseance_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1, 'minYear' => date( 'Y' ) - 10, 'selected' => $dateseance_from ) );?>
					<?php echo $xform->input( 'Search.Commissionep.dateseance_to', array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1, 'minYear' => date( 'Y' ) - 10, 'selected' => $dateseance_to ) );?>
				</fieldset>
			</fieldset>
			<?php if( $this->action == 'traitement' ): ?>
			<fieldset>
				<legend>Filtrer par décisions de sanction</legend>
				<?php
					echo $form->input( 'Search.Decision.sanction', array( 'label' => 'Suivi de la sanction', 'type' => 'select', 'options' => array( 'N' => 'Non', 'O' => 'Oui' ), 'empty' => true ) );
				?>
			</fieldset>
			<?php endif; ?>
			<fieldset>
            <legend>Filtrer par Dossier</legend>
				<?php echo $xform->input( 'Search.Dossier.dtdemrsa', array( 'label' => 'Filtrer par date de demande', 'type' => 'checkbox' ) );?>
			<fieldset>
				<legend>Date de demande RSA</legend>
				<?php
					$dtdemrsaFromSelected = $dtdemrsaToSelected = array();
					if( !dateComplete( $this->data, 'Search.Dossier.dtdemrsa_from' ) ) {
						$dtdemrsaFromSelected = array( 'selected' => strtotime( '-1 week' ) );
					}
					if( !dateComplete( $this->data, 'Search.Dossier.dtdemrsa_to' ) ) {
						$dtdemrsaToSelected = array( 'selected' => strtotime( 'today' ) );
					}

					echo $xform->input( 'Search.Dossier.dtdemrsa_from', Set::merge( array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 20 ), $dtdemrsaFromSelected ) );

					echo $xform->input( 'Search.Dossier.dtdemrsa_to', Set::merge( array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 5, 'minYear' => date( 'Y' ) - 20), $dtdemrsaToSelected ) );
				?>
			</fieldset>

			<fieldset>
				<?php
					$valueDossierDernier = isset( $this->data['Dossier']['dernier'] ) ? $this->data['Dossier']['dernier'] : true;
					echo $xform->input( 'Search.Dossier.dernier', array( 'label' => 'Uniquement la dernière demande RSA pour un même allocataire', 'type' => 'checkbox', 'checked' => $valueDossierDernier ) );
				?>
			</fieldset>
			<?php
				if( !is_null($etatdosrsa)) {
					echo $search->etatdosrsa( $etatdosrsa, 'Search.Situationdossierrsa.etatdosrsa' );
				}
			?>
            <?php
		echo $default2->subform(
			array(
				'Search.Personne.nom' => array( 'label' => __d( 'personne', 'Personne.nom', true ), 'type' => 'text' ),
				'Search.Personne.prenom' => array( 'label' => __d( 'personne', 'Personne.prenom', true ), 'type' => 'text' ),
				'Search.Personne.nomnai' => array( 'label' => __d( 'personne', 'Personne.nomnai', true ), 'type' => 'text' ),
				'Search.Personne.nir' => array( 'label' => __d( 'personne', 'Personne.nir', true ), 'type' => 'text', 'maxlength' => 15 ),
				'Search.Dossier.matricule' => array( 'label' => __d( 'dossier', 'Dossier.matricule', true ), 'type' => 'text', 'maxlength' => 15 ),
				'Search.Dossier.numdemrsa' => array( 'label' => __d( 'dossier', 'Dossier.numdemrsa', true ), 'type' => 'text', 'maxlength' => 15 ),
				'Search.Adresse.locaadr' => array( 'label' => __d( 'adresse', 'Adresse.locaadr', true ), 'type' => 'text' ),
				'Search.Adresse.numcomptt' => array( 'label' => __d( 'adresse', 'Adresse.numcomptt', true ), 'type' => 'select', 'options' => $mesCodesInsee, 'empty' => true )
			),
			array(
				'options' => $options
			)
		);

		if( Configure::read( 'CG.cantons' ) ) {
			echo $xform->input( 'Search.Canton.canton', array( 'label' => 'Canton', 'type' => 'select', 'options' => $cantons, 'empty' => true ) );
		}
            ?>

    <div class="submit noprint">
        <?php echo $xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
        <?php echo $xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>
</fieldset>
<?php echo $xform->end();?>

<?php if( isset( $gestionsanctionseps58 ) ):?>
    <?php if( empty( $gestionsanctionseps58 ) ):?>
        <p class="notice"><?php echo 'Aucune sanction présente.';?></p>
    <?php else:?>
<?php $pagination = $xpaginator->paginationBlock( 'Personne', $this->passedArgs ); ?>
	<?php echo $pagination;?>
	<?php echo $xform->create( 'Gestionsanctionep58', array( 'url'=> Router::url( null, true ) ) );?>
	<?php
		foreach( Set::flatten( $this->data['Search'] ) as $filtre => $value  ) {
			echo $xform->input( "Search.{$filtre}", array( 'type' => 'hidden', 'value' => $value ) );
		}
	?>
<table id="searchResults">
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
				<th class="action">Modification de la sanction</th>
				<th class="action">Date fin de sanction</th>
				<th class="action">Commentaire</th>
				<th class="action">Action</th>
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

					//Champ permettant la modification de la sanction
					$fieldDecisionSanction = $xform->input( "Decisionsanctionep58.{$index}.id", array( 'type' => 'hidden', 'value' => $gestionanctionep58['Decisionsanctionep58']['id'] ) ).
						$xform->input( "Decisionsanctionep58.{$index}.arretsanction", array( 'type' => 'select', 'options' => $options['Decisionsanctionep58']['arretsanction'], 'label' => false, 'empty' => true ) );
					//Champ permettant de saisir la date de la fin de la sanction
					$dateFinSanction = $xform->input( "Decisionsanctionep58.{$index}.datearretsanction", array( 'label' => false, 'type' => 'date', 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 3, 'maxYear' => date( 'Y' ) + 3 )  );
					//Champ permettant de saisir le commentaire de fin de la sanction
					$commentaireFinSanction = $xform->input( "Decisionsanctionep58.{$index}.commentairearretsanction", array( 'label' => false, 'type' => 'textarea' ) );
				}
				else {
					// Type de sanction
					$decisionSanction1 = Set::enum( $gestionanctionep58['Decisionsanctionrendezvousep58']['decision'], $regularisationlistesanctionseps58['Decisionsanctionrendezvousep58']['decision'] );
					$decisionSanction2 = Set::enum( $gestionanctionep58['Decisionsanctionrendezvousep58']['decision2'], $regularisationlistesanctionseps58['Decisionsanctionrendezvousep58']['decision'] );

					// Libellé de la sanction
					$libelleSanction1 = Set::enum( $gestionanctionep58['Decisionsanctionrendezvousep58']['listesanctionep58_id'], $listesanctionseps58 );
					$libelleSanction2 = Set::enum( $gestionanctionep58['Decisionsanctionrendezvousep58']['autrelistesanctionep58_id'], $listesanctionseps58 );

					//Champ permettant la modification de la sanction
					$fieldDecisionSanction = $xform->input( "Decisionsanctionrendezvousep58.{$index}.id", array( 'type' => 'hidden', 'value' => $gestionanctionep58['Decisionsanctionrendezvousep58']['id'] ) ).
												$xform->input( "Decisionsanctionrendezvousep58.{$index}.arretsanction", array( 'type' => 'select', 'options' => $options['Decisionsanctionrendezvousep58']['arretsanction'], 'label' => false, 'empty' => true ) );
					//Champ permettant de saisir la date de la fin de la sanction
					$dateFinSanction = $xform->input( "Decisionsanctionrendezvousep58.{$index}.datearretsanction", array( 'label' => false, 'type' => 'date', 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 3, 'maxYear' => date( 'Y' ) + 3 ) );

					//Champ permettant de saisir le commentaire de fin de la sanction
					$commentaireFinSanction = $xform->input( "Decisionsanctionrendezvousep58.{$index}.commentairearretsanction", array( 'label' => false, 'type' => 'textarea' ) );
				}

				$tableCells = array(
					$xform->input( "Foyer.{$index}.dossier_id", array( 'label' => false, 'type' => 'hidden', 'value' => $gestionanctionep58['Foyer']['dossier_id'] ) ).
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
					$xhtml->viewLink(
						'Voir le dossier',
						array( 'controller' => 'historiqueseps', 'action' => 'view_passage', $gestionanctionep58['Passagecommissionep']['id'] ),
						$permissions->check( 'historiqueseps', 'view_passage' )
					)
				);

				echo $xhtml->tableCells(
					$tableCells,
					array( 'class' => 'odd' ),
					array( 'class' => 'even' )
				);
			?>
		<?php endforeach;?>
	</tbody>
</table>
		<?php echo $pagination;?>
		<?php echo $xform->submit( 'Validation de la liste' );?>
		<?php echo $xform->end();?>
	<?php endif;?>
<?php endif;?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		<?php foreach( $gestionsanctionseps58 as $index => $gestionanctionep58 ):?>
			<?php if( $gestionanctionep58['Dossierep']['themeep'] == 'sanctionseps58' ):?>
				observeDisableFieldsOnValue(
					'Decisionsanctionep58<?php echo $index;?>Arretsanction',
					[
						'Decisionsanctionep58<?php echo $index;?>DatearretsanctionDay',
						'Decisionsanctionep58<?php echo $index;?>DatearretsanctionMonth',
						'Decisionsanctionep58<?php echo $index;?>DatearretsanctionYear'
					],
					[ '', 'annulation1', 'annulation2' ],
					true
				);
			<?php else:?>
				observeDisableFieldsOnValue(
					'Decisionsanctionrendezvousep58<?php echo $index;?>Arretsanction',
					[
						'Decisionsanctionrendezvousep58<?php echo $index;?>DatearretsanctionDay',
						'Decisionsanctionrendezvousep58<?php echo $index;?>DatearretsanctionMonth',
						'Decisionsanctionrendezvousep58<?php echo $index;?>DatearretsanctionYear'
					],
					'',
					true
				);
			<?php endif;?>
		<?php endforeach;?>
	});
</script>