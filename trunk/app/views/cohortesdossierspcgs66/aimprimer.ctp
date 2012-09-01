<?php
	$this->pageTitle = 'Dossiers PCGs à imprimer';

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
		observeDisableFieldsetOnCheckbox( 'SearchDossierpcg66Datereceptionpdo', $( 'SearchDossierpcg66DatereceptionpdoFromDay' ).up( 'fieldset' ), false );
	});
</script>

<?php echo $xform->create( 'Cohortedossierpcg66', array( 'type' => 'post', 'action' => 'aimprimer', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );?>


        <fieldset>
			<?php echo $xform->input( 'Search.active', array( 'type' => 'hidden', 'value' => true ) );?>

            <legend>Filtrer par Dossier PCG</legend>
			<?php echo $xform->input( 'Search.Dossierpcg66.datereceptionpdo', array( 'label' => 'Filtrer par date de réception du dossier', 'type' => 'checkbox' ) );?>
			<fieldset>
				<legend>Filtrer par période</legend>
				<?php
					$datereceptionpdo_from = Set::check( $this->data, 'Search.Dossierpcg66.datereceptionpdo_from' ) ? Set::extract( $this->data, 'Search.Dossierpcg66.datereceptionpdo_from' ) : strtotime( '-1 week' );
					$datereceptionpdo_to = Set::check( $this->data, 'Search.Dossierpcg66.datereceptionpdo_to' ) ? Set::extract( $this->data, 'Search.Dossierpcg66.datereceptionpdo_to' ) : strtotime( 'now' );
				?>
				<?php echo $xform->input( 'Search.Dossierpcg66.datereceptionpdo_from', array( 'label' => 'Du (inclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1, 'minYear' => date( 'Y' ) - 10, 'selected' => $datereceptionpdo_from ) );?>
				<?php echo $xform->input( 'Search.Dossierpcg66.datereceptionpdo_to', array( 'label' => 'Au (exclus)', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 1, 'minYear' => date( 'Y' ) - 10, 'selected' => $datereceptionpdo_to ) );?>
			</fieldset>
            <?php

                echo $default2->subform(
                    array(
						'Search.Originepdo.libelle' => array( 'label' => __d( 'dossierpcg66', 'Dossierpcg66.originepdo_id', true ), 'type' => 'select', 'options' => $originepdo, 'empty' => true ),
						'Search.Dossierpcg66.serviceinstructeur_id' => array(  'label' => 'Service instructeur', 'options' => $serviceinstructeur, 'empty' => true ),
                        'Search.Typepdo.libelle' => array( 'label' => __d( 'dossierpcg66', 'Dossierpcg66.typepdo_id', true ), 'type' => 'select', 'options' => $typepdo, 'empty' => true ),
                        'Search.Dossierpcg66.orgpayeur' => array( 'label' =>  __d( 'dossierpcg66', 'Dossierpcg66.orgpayeur', true ), 'type' => 'select', 'options' => $orgpayeur, 'empty' => true ),
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

<?php if( isset( $cohortedossierpcg66 ) ):?>
    <?php if( empty( $cohortedossierpcg66 ) ):?>
        <?php
            switch( $this->action ) {
                case 'aimprimer':
                    $message = 'Aucun Dossier PCG ne correspond à vos critères.';
                    break;
                default:
                    $message = 'Aucun Dossier PCG à imprimer n\'a été trouvé.';
            }
        ?>
        <p class="notice"><?php echo $message;?></p>
    <?php else:?>
<?php $pagination = $xpaginator->paginationBlock( 'Dossierpcg66', $this->passedArgs ); ?>
<?php echo $pagination;?>
	<?php
		foreach( Set::flatten( $this->data['Search'] ) as $filtre => $value  ) {
			echo $form->input( "Search.{$filtre}", array( 'type' => 'hidden', 'value' => $value ) );
		}
	?>
    <table id="searchResults" class="tooltips">
        <thead>
            <tr>
                <th>N° Dossier</th>
                <th>Alloctaire principal</th>
                <th>Commune de l'allocataire</th>
                <th>Date de réception DO</th>
                <th>Type de dossier</th>
                <th>Origine du dossier</th>
                <th>Organisme payeur</th>
                <th>Service instructeur</th>
                <th>Gestionnaire</th>
                <th colspan="2" class="action">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach( $cohortedossierpcg66 as $index => $dossierpcg66aimprimer ):?>
            <?php
// debug($dossierpcg66aimprimer);
				echo $xhtml->tableCells(
						array(
							h( $dossierpcg66aimprimer['Dossier']['numdemrsa'] ),
							h( $dossierpcg66aimprimer['Personne']['nom'].' '.$dossierpcg66aimprimer['Personne']['prenom'] ),
							h( $dossierpcg66aimprimer['Adresse']['locaadr'] ),
							h( date_short( $dossierpcg66aimprimer['Dossierpcg66']['datereceptionpdo'] ) ),
							h( $dossierpcg66aimprimer['Typepdo']['libelle'] ),
							h( $dossierpcg66aimprimer['Originepdo']['libelle'] ),
							h( $dossierpcg66aimprimer['Dossierpcg66']['orgpayeur'] ),
							h( $dossierpcg66aimprimer['Serviceinstructeur']['lib_service'] ),
							h( Set::enum( Set::classicExtract( $dossierpcg66aimprimer, 'Dossierpcg66.user_id' ), $gestionnaire ) ),
							$xhtml->viewLink(
								'Voir le dossier',
								array( 'controller' => 'dossierspcgs66', 'action' => 'index', $dossierpcg66aimprimer['Dossierpcg66']['foyer_id'] ),
								$permissions->check( 'dossierspcgs66', 'index' )
							),
							$xhtml->printLink(
								'Imprimer le dossier',
								array( 'controller' => 'decisionsdossierspcgs66', 'action' => 'decisionproposition', $dossierpcg66aimprimer['Decisiondossierpcg66']['id'], 'save' => true ),
								$permissions->check( 'dossierspcgs66', 'print' )
							),
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
                array( 'controller' => 'cohortesdossierspcgs66', 'action' => 'exportcsv' ) + Set::flatten( $this->data, '__' )
            );
        ?></li>
		<li><?php
            echo $xhtml->printCohorteLink(
				'Imprimer la cohorte',
				array( 'controller' => 'cohortesdossierspcgs66', 'action' => 'notificationsCohorte' ) + Set::flatten( $this->data, '__' )
			);
        ?></li>
    </ul>
<?php endif?>

<?php endif?>