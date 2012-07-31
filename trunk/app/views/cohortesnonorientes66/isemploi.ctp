<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
    $this->pageTitle = 'Allocataires inscrits au Pôle Emploi';
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

<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $javascript->link( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}
?>

<script type="text/javascript">
	document.observe("dom:loaded", function() {
		observeDisableFieldsetOnCheckbox( 'SearchDossierDtdemrsa', $( 'SearchDossierDtdemrsaFromDay' ).up( 'fieldset' ), false );
	});
</script>
<?php echo $xform->create( 'Cohortenonoriente66', array( 'type' => 'post', 'action' => 'isemploi', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );?>


        <fieldset>
			<?php echo $xform->input( 'Search.active', array( 'type' => 'hidden', 'value' => true ) );?>

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
        </fieldset>

    <div class="submit noprint">
        <?php echo $xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
        <?php echo $xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>

<?php echo $xform->end();?>

<?php if( isset( $cohortesnonorientes66 ) ):?>
    <?php if( empty( $cohortesnonorientes66 ) ):?>
        <p class="notice"><?php echo 'Aucun allocataire non orienté.';?></p>
    <?php else:?>

	<?php echo $xform->create( 'Nonoriente', array( 'url'=> Router::url( null, true ) ) );?>
	<?php
		foreach( Set::flatten( $this->data['Search'] ) as $filtre => $value  ) {
			echo $xform->input( "Search.{$filtre}", array( 'type' => 'hidden', 'value' => $value ) );
		}
	?>
	<?php $pagination = $xpaginator->paginationBlock( 'Personne', $this->passedArgs ); ?>
	<?php echo $pagination;?>
    <table id="searchResults">
        <thead>
            <tr>
                <th>N° Dossier</th>
                <th>Date de demande</th>
                <th>Allocataire principal</th>
                <th>Etat du droit</th>
				<th>Commune de l'allocataire</th>
				<th>Alerte composition du foyer ?</th>
				<th>Sélectionner</th>
				<th class="action">Type d'orientation</th>
				<th class="action">Structure référente</th>
				<th class="action">Date d'orientation</th>
				<th class="action">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach( $cohortesnonorientes66 as $index => $cohortenonoriente66 ):?>
            <?php

//             debug($typeorient_id);
				$tableCells = array(
						h( $cohortenonoriente66['Dossier']['numdemrsa'] ),
						h( date_short( $cohortenonoriente66['Dossier']['dtdemrsa'] ) ),
						h( $cohortenonoriente66['Personne']['nom'].' '.$cohortenonoriente66['Personne']['prenom'] ),
						h( Set::classicExtract( $etatdosrsa, Set::classicExtract( $cohortenonoriente66, 'Situationdossierrsa.etatdosrsa' ) ) ),
						h( $cohortenonoriente66['Adresse']['locaadr'] ),
						$gestionanomaliebdd->foyerErreursPrestationsAllocataires( $cohortenonoriente66, false ),
						$xform->input( 'Orientstruct.'.$index.'.atraiter', array( 'label' => false, 'legend' => false, 'type' => 'checkbox', 'class' => 'atraiter' ) ),
						$xform->input( 'Orientstruct.'.$index.'.typeorient_id', array( 'label' => false, 'type' => 'select', 'options' => $typesOrient/*, 'empty' => true*/ ) ).
						$xform->input( 'Orientstruct.'.$index.'.origine', array( 'label' => false, 'type' => 'hidden', 'value' => 'cohorte' ) ).
						$xform->input( 'Orientstruct.'.$index.'.id', array( 'label' => false, 'type' => 'hidden', 'value' => $cohortenonoriente66['Orientstruct']['id'] ) ).
						$xform->input( 'Orientstruct.'.$index.'.dossier_id', array( 'label' => false, 'type' => 'hidden', 'value' => $cohortenonoriente66['Foyer']['dossier_id'] ) ).
						$xform->input( 'Orientstruct.'.$index.'.codeinsee', array( 'label' => false, 'type' => 'hidden', 'value' => $cohortenonoriente66['Adresse']['numcomptt'] ) ).
						$xform->input( 'Orientstruct.'.$index.'.personne_id', array( 'label' => false, 'type' => 'hidden', 'value' => $cohortenonoriente66['Personne']['id'] ) ).
						$xform->input( 'Orientstruct.'.$index.'.statut_orient', array( 'label' => false, 'type' => 'hidden', 'value' => 'Orienté' ) ).
						$xform->input( 'Nonoriente66.'.$index.'.id', array( 'label' => false, 'type' => 'hidden', 'value' => $cohortenonoriente66['Nonoriente66']['id'] ) ).
						$xform->input( 'Nonoriente66.'.$index.'.origine', array( 'label' => false, 'type' => 'hidden', 'value' => 'isemploi' ) ).
						$xform->input( 'Nonoriente66.'.$index.'.personne_id', array( 'label' => false, 'type' => 'hidden', 'value' => $cohortenonoriente66['Personne']['id'] ) ).
						$xform->input( 'Nonoriente66.'.$index.'.historiqueetatpe_id', array( 'label' => false, 'type' => 'hidden', 'value' => $cohortenonoriente66['Historiqueetatpe']['id'] ) ).
						$xform->input( 'Nonoriente66.'.$index.'.user_id', array( 'label' => false, 'type' => 'hidden', 'value' => $session->read( 'Auth.User.id' ) ) ),

						$xform->input( 'Orientstruct.'.$index.'.structurereferente_id', array( 'label' => false, 'type' => 'select', 'options' => $structuresReferentes/*,  'empty' => true*/ ) ),

						$xform->input( 'Orientstruct.'.$index.'.date_valid', array( 'label' => false, 'type' => 'date', 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 2, 'maxYear' => date( 'Y' ) + 2  ) ),

						$xhtml->viewLink(
							'Voir le dossier',
							array( 'controller' => 'dossiers', 'action' => 'view', $cohortenonoriente66['Dossier']['id'] ),
							$permissions->check( 'dossiers', 'view' )
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
<?php
	echo $xform->button( 'Tout cocher', array( 'onclick' => "toutCocher( 'input.atraiter', true )" ) );
	echo $xform->button( 'Tout décocher', array( 'onclick' => "toutDecocher( 'input.atraiter', true )" ) );

?>
		<?php echo $xform->submit( 'Validation de la liste' );?>
		<?php echo $xform->end();?>
	<?php endif;?>
<?php endif;?>

<?php if( !empty( $cohortesnonorientes66 ) ):?>
	<?php foreach( $cohortesnonorientes66 as $key => $cohortenonoriente66 ):?>
		<script type="text/javascript">
			document.observe("dom:loaded", function() {

				dependantSelect( 'Orientstruct<?php echo $key;?>StructurereferenteId', 'Orientstruct<?php echo $key;?>TypeorientId' );
				try { $( 'OrientstructStructurereferenteId' ).onchange(); } catch(id) { }

				observeDisableFieldsOnCheckbox(
					'Orientstruct<?php echo $key;?>Atraiter',
					[
						'Orientstruct<?php echo $key;?>TypeorientId',
						'Orientstruct<?php echo $key;?>PersonneId',
						'Orientstruct<?php echo $key;?>Origine',
						'Orientstruct<?php echo $key;?>StatutOrient',
						'Orientstruct<?php echo $key;?>StructurereferenteId',
						'Orientstruct<?php echo $key;?>DateValidYear',
						'Orientstruct<?php echo $key;?>DateValidMonth',
						'Orientstruct<?php echo $key;?>DateValidDay',
						'Nonoriente66<?php echo $key;?>PersonneId',
						'Nonoriente66<?php echo $key;?>Origine',
						'Nonoriente66<?php echo $key;?>HistoriqueetatpeId',
						'Nonoriente66<?php echo $key;?>Id',
						'Nonoriente66<?php echo $key;?>UserId',
					],
					false
				);

			});
		</script>
	<?php endforeach;?>
<?php endif;?>