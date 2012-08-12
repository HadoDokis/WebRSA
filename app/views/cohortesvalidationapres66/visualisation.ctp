<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
    $this->pageTitle = 'APREs à notifier';
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
            dependantSelect(
                'SearchAideapre66Typeaideapre66Id',
                'SearchAideapre66Themeapre66Id'
            );
	});
</script>
<?php echo $xform->create( 'Cohortevalidationapre66', array( 'type' => 'post', 'action' => $this->action,  'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );?>

        <fieldset>
            <?php /*echo $xform->input( 'Cohortevalidationapre66.validees', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );*/?>
			<?php echo $xform->input( 'Search.active', array( 'type' => 'hidden', 'value' => true ) );?>

            <legend>Filtrer par APRE</legend>
            <?php

                echo $default2->subform(
                    array(
						'Search.Aideapre66.themeapre66_id' => array(  'label' => 'Thème de l\'aide', 'options' => $themes, 'empty' => true ),
						'Search.Aideapre66.typeaideapre66_id' => array(  'label' => 'Type d\'aide', 'options' => $typesaides, 'empty' => true ),
                        'Search.Apre66.numeroapre' => array( 'label' => __d( 'apre', 'Apre.numeroapre', true ), 'type' => 'text' ),
                        'Search.Apre66.referent_id' => array( 'label' => __d( 'apre', 'Apre.referent_id', true ), 'options' => $referents ),
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


<?php if( isset( $cohortevalidationapre66 ) ):?>
    <?php if( empty( $cohortevalidationapre66 ) ):?>
        <?php
            switch( $this->action ) {
                case 'validees':
                    $message = 'Aucune APRE ne correspond à vos critères.';
                    break;
                default:
                    $message = 'Aucune APRE de validée n\'a été trouvée.';
            }
        ?>
        <p class="notice"><?php echo $message;?></p>
    <?php else:?>
<?php $pagination = $xpaginator->paginationBlock( 'Apre66', $this->passedArgs ); ?>
<?php echo $pagination;?>
	<?php
		foreach( Set::flatten( $this->data['Search'] ) as $filtre => $value  ) {
			echo $form->input( "Search.{$filtre}", array( 'type' => 'hidden', 'value' => $value ) );
		}
	?>
    <table id="searchResults" class="tooltips">
        <thead>
            <tr>
                <th>N° Demande APRE</th>
                <th>Nom de l'allocataire</th>
                <th>Référent APRE</th>
                <th>Date demande APRE</th>
                <th>Etat du dossier</th>
                <th>Décision</th>
                <th>Montant accordé</th>
                <th>Motif du rejet</th>
                <th>Date de la décision</th>
                <th colspan="2" class="action">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach( $cohortevalidationapre66 as $index => $validationapre ):?>
            <?php

                    $innerTable = '<table id="innerTablesearchResults'.$index.'" class="innerTable">
                        <tbody>
							<tr>
                                <th>Thème de l\'aide</th>
                                <td>'.h( $validationapre['Themeapre66']['name'] ).'</td>
                            </tr>
                             <tr>
                                <th>Type d\'aide</th>
                                <td>'.h( $validationapre['Typeaideapre66']['name'] ).'</td>
                            </tr>
                            <tr>
                                <th>Date naissance</th>
                                <td>'.h( date_short( $validationapre['Personne']['dtnai'] ) ).'</td>
                            </tr>
                            <tr>
                                <th>Numéro CAF</th>
                                <td>'.h( $validationapre['Dossier']['matricule'] ).'</td>
                            </tr>
                            <tr>
                                <th>NIR</th>
                                <td>'.h( $validationapre['Personne']['nir'] ).'</td>
                            </tr>
                            <tr>
                                <th>Code postal</th>
                                <td>'.h( $validationapre['Adresse']['codepos'] ).'</td>
                            </tr>
                            <tr>
                                <th>Commune</th>
                                <td>'.h( $validationapre['Adresse']['locaadr'] ).'</td>
                            </tr>
                        </tbody>
                    </table>';
                    $title = $validationapre['Dossier']['numdemrsa'];

                    echo $xhtml->tableCells(
						array(
							h( $validationapre['Apre66']['numeroapre'] ),
							h( $validationapre['Personne']['nom_complet'] ),
							h( $validationapre['Referent']['nom_complet'] ),
							h( date_short(  $validationapre['Aideapre66']['datedemande'] ) ),
							h( Set::enum( Set::classicExtract( $validationapre, 'Apre66.etatdossierapre' ), $options['etatdossierapre'] ) ),
							h( Set::enum( Set::classicExtract( $validationapre, 'Aideapre66.decisionapre' ), $optionsaideapre66['decisionapre'] ) ),
							h( (  $validationapre['Aideapre66']['montantaccorde'] ) ),
							h( $validationapre['Aideapre66']['motifrejetequipe'] ),
							h( date_short(  $validationapre['Aideapre66']['datemontantaccorde'] ) ),
							$xhtml->viewLink(
								'Voir le contrat',
								array( 'controller' => 'apres66', 'action' => 'index', $validationapre['Personne']['id'] ),
								$permissions->check( 'apres66', 'index' )
							),
							$xhtml->notificationsApreLink(
								'Notifier la décision',
								array( 'controller' => 'apres66', 'action' => 'notifications', $validationapre['Apre66']['id'] ),
								$permissions->check( 'apres66', 'notifications' )
							),
							array( $innerTable, array( 'class' => 'innerTableCell' ) ),
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
                array( 'controller' => 'cohortesvalidationapres66', 'action' => 'exportcsv' ) + Set::flatten( $this->data, '__' )
            );
        ?></li>
		<li><?php
            echo $xhtml->printCohorteLink(
				'Imprimer la cohorte',
				array( 'controller' => 'cohortesvalidationapres66', 'action' => 'notificationsCohorte', $this->action ) + Set::flatten( $this->data, '__' )
			);
        ?></li>
    </ul>
<?php endif?>
<?php endif?>