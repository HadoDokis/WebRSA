<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Recherche par critères';?>

<h1>Recherche par critères</h1>

<ul class="actionMenu">
    <?php
        echo '<li>'.$html->addLink(
            'Ajouter un dossier',
            array( 'controller' => 'tests', 'action' => 'wizard' )
        ).' </li>';
        if( $session->read( 'Auth.User.username' ) == 'cg66' ) { // FIXME
            echo '<li>'.$html->addSimpleLink(
                'Ajouter une préconisation d\'orientation',
                array( 'controller' => 'dossierssimplifies', 'action' => 'add' )
            ).' </li>';
        }

        if( is_array( $this->data ) ) {
            echo '<li>'.$html->link(
                $html->image(
                    'icons/application_form_magnify.png',
                    array( 'alt' => '' )
                ).' Formulaire',
                '#',
                array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
            ).'</li>';
        }
    ?>
</ul>
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        observeDisableFieldsetOnCheckbox( 'DossierDtdemrsa', $( 'DossierDtdemrsaFromDay' ).up( 'fieldset' ), false );
    });
</script>

<?php echo $form->create( 'Critere', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( is_array( $this->data ) ? 'folded' : 'unfolded' ) ) );?>
    <fieldset>
        <legend>Recherche par dossier</legend>
        <?php echo $form->input( 'Dossier.numdemrsa', array( 'label' => 'Numéro de dossier RSA' ) );?>
        <?php echo $form->input( 'Dossier.dtdemrsa', array( 'label' => 'Filtrer par date de demande', 'type' => 'checkbox' ) );?>
        <fieldset>
            <legend>Date de demande RSA</legend>
            <?php echo $form->input( 'Dossier.dtdemrsa_from', array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => strtotime( '-1 week' ) ) );?>
            <?php echo $form->input( 'Dossier.dtdemrsa_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120 ) );?>
        </fieldset>
    </fieldset>
    <fieldset>
        <legend>Recherche par types d'orientations</legend>
	    <?php echo $form->input( 'Typeorient.id', array( 'label' =>  __( 'lib_type_orient', true ), 'type' => 'select' , 'options' => $type, 'empty' => true ) );?>
            <?php echo $form->input( 'Typeorient.parentid', array( 'label' => __( 'parentid', true ) )  );?>
            <?php echo $form->input( 'Typeorient.modele_notif', array( 'label' => __( 'modele_notif', true ) )  );?>
    </fieldset>
    <fieldset>
        <legend>Recherche par Structures référentes</legend>
            <?php echo $form->input( 'Structurereferente.lib_struc', array( 'label' =>  __( 'lib_struc', true ) ) );?>
          <!--  <?php echo $form->input( 'Structurereferente.num_voie', array( 'label' =>  __( 'num_voie', true ) ) );?>
            <?php echo $form->input( 'Structurereferente.type_voie', array( 'label' =>  __( 'type_voie', true ) ) );?>
            <?php echo $form->input( 'Structurereferente.nom_voie', array( 'label' =>  __( 'nom_voie', true ) ) );?>  -->
            <?php echo $form->input( 'Structurereferente.code_postal', array( 'label' =>  __( 'code_postal', true ) ) );?> 
            <?php echo $form->input( 'Structurereferente.ville', array( 'label' =>  __( 'ville', true ) ) );?> 
            <?php echo $form->input( 'Structurereferente.code_insee', array( 'label' =>  __( 'code_insee', true ) ) );?> 
    </fieldset>
    <?php echo $form->submit( 'Rechercher' );?>
<?php echo $form->end();?>

<!-- Résultats -->
<?php if( isset( $criteres ) ):?>
    <h2>Résultats de la recherche</h2>

    <?php if( is_array( $criteres ) && count( $criteres ) > 0 ):?>
        <?php require( 'index.pagination.ctp' )?>
        <table id="searchResults" class="tooltips_oupas">
            <thead>
                <tr>
                    <th>Numéro dossier</th>
                    <th>Date de demande</th>
                    <th>NIR</th>
                    <th>Allocataire</th>
                    <th>État du dossier</th>
                    <th class="action">Actions</th>
                    <th class="innerTableHeader">Informations complémentaires</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $criteres as $index => $critere ):?>
                    <?php
                        $title = $critere['Dossier']['numdemrsa'];

                        $innerTable = '<table id="innerTable'.$index.'" class="innerTable">
                            <tbody>
                                <tr>
                                    <th>Commune de naissance</th>
                                    <td>'.$critere['Foyer']['Personne'][0]['nomcomnai'].'</td>
                                </tr>
                                <tr>
                                    <th>Date de naissance</th>
                                    <td>'.date_short( $critere['Foyer']['Personne'][0]['dtnai'] ).'</td>
                                </tr>
                            </tbody>
                        </table>';

                        echo $html->tableCells(
                            array(
                                h( $critere['Dossier']['numdemrsa'] ),
                                h( date_short( $critere['Dossier']['dtdemrsa'] ) ),
                                h( $critere['Foyer']['Personne'][0]['nir'] ), // FIXME: 0
                                implode(
                                    ' ',
                                    array(
                                        $critere['Foyer']['Personne'][0]['qual'],
                                        $critere['Foyer']['Personne'][0]['nom'],
                                        implode( ' ', array( $critere['Foyer']['Personne'][0]['prenom'], $critere['Foyer']['Personne'][0]['prenom2'], $critere['Foyer']['Personne'][0]['prenom3'] ) )
                                    )
                                ),
                                h( $critere['Situationdossierrsa']['etatdosrsa'] ),

                                $html->viewLink(
                                    'Voir le dossier « '.$title.' »',
                                    array( 'controller' => 'dossiers', 'action' => 'view', $critere['Dossier']['id'] )
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

        <?php require( 'index.pagination.ctp' )?>
    <?php else:?>
        <p>Vos critères n'ont retourné aucun dossier.</p>
    <?php endif?>
<?php endif?>
