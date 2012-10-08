<?php
	$this->pageTitle = 'Envoi des notifications';

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
		observeDisableFieldsetOnCheckbox( 'SearchDossierDtdemrsa', $( 'SearchDossierDtdemrsaFromDay' ).up( 'fieldset' ), false );
	});
</script>
<?php echo $this->Xform->create( 'Cohortenonoriente66', array( 'type' => 'post', 'action' => 'notifaenvoyer', 'id' => 'Search', 'class' => ( ( is_array( $this->request->data ) && !empty( $this->request->data ) ) ? 'folded' : 'unfolded' ) ) );?>


        <fieldset>
			<?php echo $this->Xform->input( 'Search.active', array( 'type' => 'hidden', 'value' => true ) );?>

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
				if( !is_null($etatdosrsa) ) {
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
						'Search.Adresse.numcomptt' => array( 'label' => __d( 'adresse', 'Adresse.numcomptt' ), 'type' => 'select', 'options' => $mesCodesInsee, 'empty' => true ),
						'Search.Nonoriente66.user_id' => array( 'label' => 'Utilisateur ayant réalisé l\'orientation', 'type' => 'select', 'options' => $users, 'empty' => true )
					),
                    array(
                        'options' => $options
                    )
                );

				if( Configure::read( 'CG.cantons' ) ) {
					echo $this->Xform->input( 'Search.Canton.canton', array( 'label' => 'Canton', 'type' => 'select', 'options' => $cantons, 'empty' => true ) );
				}
            ?>
        </fieldset>
        <fieldset>
			<legend>Comptage des résultats</legend>
			<?php echo $this->Form->input( 'Search.paginationNombreTotal', array( 'label' => 'Obtenir le nombre total de résultats (plus lent)', 'type' => 'checkbox' ) );?>
		</fieldset>

    <div class="submit noprint">
        <?php echo $this->Xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
        <?php echo $this->Xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>

<?php echo $this->Xform->end();?>

<?php if( isset( $cohortesnonorientes66 ) ):?>
    <?php if( empty( $cohortesnonorientes66 ) ):?>
        <p class="notice"><?php echo 'Aucun allocataire à orienter.';?></p>
    <?php else:?>

	<?php echo $this->Xform->create( 'Nonoriente', array( 'url'=> Router::url( null, true ) ) );?>
	<?php
		foreach( Set::flatten( $this->request->data['Search'] ) as $filtre => $value  ) {
			echo $this->Xform->input( "Search.{$filtre}", array( 'type' => 'hidden', 'value' => $value ) );
		}
	?>
	<?php $pagination = $this->Xpaginator->paginationBlock( 'Personne', $this->passedArgs ); ?>
	<?php echo $pagination;?>
    <table id="searchResults">
        <thead>
            <tr>
                <th>N° Dossier</th>
                <th>Date de demande</th>
                <th>Allocataire principal</th>
                <th>Etat du droit</th>
				<th>Commune de l'allocataire</th>
				<th>Orientation effective</th>
				<th>Alerte composition du foyer ?</th>
				<th class="action" colspan="2">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach( $cohortesnonorientes66 as $index => $cohortenonoriente66 ):?>
            <?php

				$tableCells = array(
						h( $cohortenonoriente66['Dossier']['numdemrsa'] ),
						h( date_short( $cohortenonoriente66['Dossier']['dtdemrsa'] ) ),
						h( $cohortenonoriente66['Personne']['nom'].' '.$cohortenonoriente66['Personne']['prenom'] ),
						h( $etatdosrsa[$cohortenonoriente66['Situationdossierrsa']['etatdosrsa']] ),
						h( $cohortenonoriente66['Adresse']['locaadr'] ),
						h( $cohortenonoriente66['Typeorient']['lib_type_orient'].' - '.$cohortenonoriente66['Structurereferente']['lib_struc'] ),
						$this->Gestionanomaliebdd->foyerErreursPrestationsAllocataires( $cohortenonoriente66, false ),
						$this->Xhtml->viewLink(
							'Voir le dossier',
							array( 'controller' => 'orientsstructs', 'action' => 'index', $cohortenonoriente66['Personne']['id'] ),
							$this->Permissions->check( 'dossiers', 'view' )
						),
						$this->Xhtml->printLink(
							'Imprimer le courrier d\'orientation',
							array( 'controller' => 'cohortesnonorientes66', 'action' => 'impressionOrientation', $cohortenonoriente66['Orientstruct']['id'] ),
							$this->Permissions->check( 'cohortesnonorientes66', 'impressionOrientation' )
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
		<?php echo $pagination?>

		<ul class="actionMenu">
			<li><?php
				echo $this->Xhtml->printCohorteLink(
					'Imprimer la cohorte',
					Set::merge(
						array(
							'controller' => 'cohortesnonorientes66',
							'action'     => 'impressionsOrientation'
						),
						Set::flatten( $this->request->data )
					)
				);
			?></li>
		</ul>
	<?php endif;?>
<?php endif;?>