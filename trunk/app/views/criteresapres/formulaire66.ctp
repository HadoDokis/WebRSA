<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Recherche d\'APREs';?>

<h1>Recherche de demande APRE</h1>


<?php $pagination = $xpaginator->paginationBlock( 'Apre', $this->passedArgs );?>
<?php
    if( is_array( $this->data ) ) {
        echo '<ul class="actionMenu"><li>'.$xhtml->link(
            $xhtml->image(
                'icons/application_form_magnify.png',
                array( 'alt' => '' )
            ).' Formulaire',
            '#',
            array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'critereapreform' ).toggle(); return false;" )
        ).'</li></ul>';
    }

?>


<?php
	if( Configure::read( 'debug' ) > 0 ) {
		echo $javascript->link( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
	}
?>

<script type="text/javascript">
        document.observe("dom:loaded", function() {
            dependantSelect(
                'FiltreTypeaideapre66Id',
                'FiltreThemeapre66Id'
            );
	});
</script>



<?php /*echo $xform->create( 'Critereapre', array( 'type' => 'post', 'action' => '/formulaire/', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );*/
    echo $xform->create( 'Critereapre', array( 'url'=> Router::url( null, true ), 'id' => 'critereapreform', 'class' => ( !empty( $this->data ) ? 'folded' : 'unfolded' ) ) );?>

		<?php
			echo $search->blocAllocataire(  );
			echo $search->blocAdresse( $mesCodesInsee, $cantons );
		?>
		
	<fieldset>
		<legend>Recherche par dossier</legend>
        <?php 
			echo $form->input( 'Dossier.numdemrsa', array( 'label' => 'Numéro de dossier RSA' ) );
			echo $form->input( 'Dossier.matricule', array( 'label' => 'Numéro CAF', 'maxlength' => 15 ) );
			
			echo $search->etatdosrsa($etatdosrsa);
            
            $valueDossierDernier = isset( $this->data['Dossier']['dernier'] ) ? $this->data['Dossier']['dernier'] : true;
            echo $form->input( 'Dossier.dernier', array( 'label' => 'Uniquement la dernière demande RSA pour un même allocataire', 'type' => 'checkbox', 'checked' => $valueDossierDernier ) );
        ?>
    </fieldset>
		<?php
			if( Configure::read( 'debug' ) > 0 ) {
				echo $javascript->link( array( 'prototype.event.simulate.js', 'dependantselect.js' ) );
			}
		?>
		<script type="text/javascript">
			document.observe("dom:loaded", function() {
				dependantSelect( 'FiltreReferentId', 'FiltreStructurereferenteId' );
			});
		</script>
    <fieldset>
        <legend>Recherche par demande APRE</legend>
            <?php echo $xform->input( 'Filtre.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>

            <?php echo $xform->input( 'Filtre.datedemandeapre', array( 'label' => 'Filtrer par date de demande APRE', 'type' => 'checkbox' ) );?>
            <fieldset>
                <legend>Date de la saisie de la demande</legend>
                <?php
                    $datedemandeapre_from = Set::check( $this->data, 'Filtre.datedemandeapre_from' ) ? Set::extract( $this->data, 'Filtre.datedemandeapre_from' ) : strtotime( '-1 week' );
                    $datedemandeapre_to = Set::check( $this->data, 'Filtre.datedemandeapre_to' ) ? Set::extract( $this->data, 'Filtre.datedemandeapre_to' ) : strtotime( 'now' );
                ?>
                <?php echo $xform->input( 'Filtre.datedemandeapre_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $datedemandeapre_from ) );?>
                <?php echo $xform->input( 'Filtre.datedemandeapre_to', array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 5, 'minYear' => date( 'Y' ) - 120, 'selected' => $datedemandeapre_to ) );?>
            </fieldset>

            <?php ?>
			<?php
				echo $form->input( 'Filtre.structurereferente_id', array( 'label' => 'Structure référente', 'type' => 'select' , 'options' => $structures, 'empty' => true  ) );
				echo $form->input( 'Filtre.referent_id', array( 'label' => 'Référent/prescripteur', 'type' => 'select' , 'options' => $referents, 'empty' => true  ) );
			?>
            <?php
				echo $xform->enum( 'Filtre.activitebeneficiaire', array(  'label' => 'Activité du bénéficiaire', 'options' => array( 'P' => 'Recherche d\'Emploi', 'E' => 'Emploi' , 'F' => 'Formation', 'C' => 'Création d\'Entreprise' ) ) );
				
				echo $xform->enum( 'Filtre.themeapre66_id', array(  'label' => 'Thème de l\'aide', 'options' => $themes, 'empty' => true ) );
				
				echo $xform->enum( 'Filtre.typeaideapre66_id', array(  'label' => 'Type d\'aide', 'options' => $typesaides, 'empty' => true ) );
            
            
            ?>

            <?php
				echo $xform->enum( 'Filtre.etatdossierapre', array(  'label' => 'Etat du dossier APRE', 'options' => 	$options['etatdossierapre'] ) );
				echo $xform->enum( 'Filtre.isdecision', array(  'label' => 'Décision émise concernant le dossier APRE', 'type' => 'radio', 'options' => $options['isdecision'] ) );
					
            ?>
            <fieldset class="noborder" id="avisdecision">
				<?php echo $xform->input( 'Filtre.decisionapre', array( 'label' => 'Accord/Rejet', 'type' => 'radio', 'options' => $options['decisionapre'] ) ); ?>
            </fieldset>

    </fieldset>

    <div class="submit noprint">
        <?php echo $xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
        <?php echo $xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>

<?php echo $xform->end();?>

<!-- Résultats -->
<?php if( isset( $apres ) ):?>

    <?php
        $totalCount = Set::classicExtract( $xpaginator->params, 'paging.Apre.count' );
    ?>


    <h2 class="noprint">Résultats de la recherche</h2>

    <?php echo $pagination;?>
    <?php if( is_array( $apres ) && count( $apres ) > 0  ):?>

        <table id="searchResults" class="tooltips">
            <thead>
                <tr>
                    <th><?php echo $xpaginator->sort( 'N° Dossier RSA', 'Dossier.numdemrsa' );?></th>
                    <th><?php echo $xpaginator->sort( 'N° demande APRE', 'Apre.numeroapre' );?></th>
                    <th><?php echo $xpaginator->sort( 'Nom de l\'allocataire', 'Personne.nom' );?></th>
                    <th><?php echo $xpaginator->sort( 'Commune de l\'allocataire', 'Adresse.locaadr' );?></th>
                    <th><?php echo $xpaginator->sort( 'Date de demande APRE', 'Aideapre66.datedemande' );?></th>
                    <th><?php echo $xpaginator->sort( 'Structure référente', 'Structurereferente.lib_struc' );?></th>
                    <th><?php echo $xpaginator->sort( 'Référent/Prescripteur', 'Referent.nom' );?></th>
                    <th><?php echo $xpaginator->sort( 'Activité du bénéficiaire', 'Apre.activitebeneficiaire' );?></th>
                    <th><?php echo $xpaginator->sort( 'Etat du dossier APRE', 'Apre.etatdossierapre' );?></th>
                    <th><?php echo $xpaginator->sort( 'Décision émise ?', 'Apre.isdecision' );?></th>
                    <th><?php echo $xpaginator->sort( 'Accord ou rejet', 'Aideapre66.decisionapre' );?></th>
                    <th class="action noprint">Actions</th>
                    <th class="innerTableHeader noprint">Informations complémentaires</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $apres as $index => $apre ):?>
                    <?php
                        $title = $apre['Dossier']['numdemrsa'];

                        $innerTable = '<table id="innerTablesearchResults'.$index.'" class="innerTable">
                            <tbody>
                                <tr>
                                    <th>N° CAF</th>
                                    <td>'.$apre['Dossier']['matricule'].'</td>
                                </tr>
                                <tr>
                                    <th>Date de naissance</th>
                                    <td>'.date_short( $apre['Personne']['dtnai'] ).'</td>
                                </tr>
                                <tr>
                                    <th>Code INSEE</th>
                                    <td>'.$apre['Adresse']['numcomptt'].'</td>
                                </tr>
                                <tr>
                                    <th>NIR</th>
                                    <td>'.$apre['Personne']['nir'].'</td>
                                </tr>
                            </tbody>
                        </table>';

						$activites = array( 'P' => 'Recherche d\'Emploi', 'E' => 'Emploi' , 'F' => 'Formation', 'C' => 'Création d\'Entreprise' );
// debug( $apre );
                        echo $xhtml->tableCells(
                            array(
                                h( Set::classicExtract( $apre, 'Dossier.numdemrsa' ) ),
                                h( Set::classicExtract( $apre, 'Apre.numeroapre' ) ),
                                h( $apre['Personne']['nom'].' '.$apre['Personne']['prenom'] ),
                                h( $apre['Adresse']['locaadr'] ),
                                h( $locale->date( 'Date::short', Set::extract( $apre, 'Aideapre66.datedemande' ) ) ),
                                h( $apre['Structurereferente']['lib_struc']),
                                h( $apre['Referent']['nom'].' '.$apre['Referent']['prenom'] ),
                                h( Set::enum( Set::classicExtract( $apre, 'Apre.activitebeneficiaire' ), $activites ) ),
                                h( Set::enum( Set::classicExtract( $apre, 'Apre.etatdossierapre' ), $options['etatdossierapre'] ) ),
                                h( Set::enum( Set::classicExtract( $apre, 'Apre.isdecision' ), $options['isdecision'] ) ),
                                h( Set::enum( Set::classicExtract( $apre, 'Aideapre66.decisionapre' ), $options['decisionapre'] ) ),
                                array(
                                    $xhtml->viewLink(
                                        'Voir le dossier « '.$title.' »',
                                        array( 'controller' => 'apres'.Configure::read( 'Apre.suffixe' ), 'action' => 'index', $apre['Apre']['personne_id'] )
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
                    array( 'controller' => 'criteresapres', 'action' => 'exportcsv', $this->action ) + Set::flatten( $this->data, '__' )
                );
            ?></li>
        </ul>


    <?php else:?>
        <p>Vos critères n'ont retourné aucune demande d'APRE.</p>
    <?php endif?>

<?php endif?>

<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsetOnCheckbox( 'FiltreDatedemandeapre', $( 'FiltreDatedemandeapreFromDay' ).up( 'fieldset' ), false );
//         observeDisableFieldsetOnCheckbox( 'FiltreDateprint', $( 'FiltreDateimpressionapreFromDay' ).up( 'fieldset' ), false );


		observeDisableFieldsetOnRadioValue(
			'critereapreform',
			'data[Filtre][isdecision]',
			$( 'avisdecision' ),
			'O',
			false,
			true
		);
    });
</script>