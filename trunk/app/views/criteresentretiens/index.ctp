<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php
    $this->pageTitle = 'Entretiens';
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
<?php echo $javascript->link( 'dependantselect.js' ); ?>
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        dependantSelect( 'EntretienReferentId', 'EntretienStructurereferenteId' );
    });
</script>

<?php echo $xform->create( 'Critereentretien', array( 'type' => 'post', 'action' => $this->action,  'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );?>

        <fieldset>
            <?php echo $xform->input( 'Critereentretien.index', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>

            <legend>Filtrer par APRE</legend>
            <?php
                $valueDossierDernier = isset( $this->data['Dossier']['dernier'] ) ? $this->data['Dossier']['dernier'] : true;
                echo $default2->subform(
                    array(
                        'Entretien.arevoirle' => array( 'label' => __d( 'entretien', 'Entretien.arevoirle', true ), 'type' => 'date', 'dateFormat' => 'MY', 'empty' => true, 'minYear' => date( 'Y' ) - 1, 'maxYear' => date( 'Y' ) + 1 ),
                        'Entretien.structurereferente_id' => array( 'label' => __d( 'entretien', 'Entretien.structurereferente_id', true ), 'empty' => true, 'options' => $structs ),
                        'Entretien.referent_id' => array( 'label' => __d( 'entretien', 'Entretien.referent_id', true ), 'empty' => true, 'options' => $referents  ),
                        'Personne.nom' => array( 'label' => __d( 'personne', 'Personne.nom', true ), 'type' => 'text' ),
                        'Personne.prenom' => array( 'label' => __d( 'personne', 'Personne.prenom', true ), 'type' => 'text' ),
                        'Personne.nomnai' => array( 'label' => __d( 'personne', 'Personne.nomnai', true ), 'type' => 'text' ),
                        'Personne.nir' => array( 'label' => __d( 'personne', 'Personne.nir', true ), 'type' => 'text', 'maxlength' => 15 ),
                        'Dossier.matricule' => array( 'label' => __d( 'dossier', 'Dossier.matricule', true ), 'type' => 'text', 'maxlength' => 15 ),
                        'Dossier.numdemrsa' => array( 'label' => __d( 'dossier', 'Dossier.numdemrsa', true ), 'type' => 'text', 'maxlength' => 15 ),
                        'Dossier.dernier' => array( 'label' => 'Uniquement la dernière demande RSA pour un même allocataire', 'type' => 'checkbox', 'checked' => $valueDossierDernier )

                    ),
                    array(
                        'options' => $options
                    )
                );
            ?>
        </fieldset>

    <div class="submit noprint">
        <?php echo $xform->button( 'Rechercher', array( 'type' => 'submit' ) );?>
        <?php echo $xform->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>

<?php echo $xform->end();?>

<?php if( isset( $entretiens ) ):?>
    <?php if( empty( $entretiens ) ):?>
        <?php $message = 'Aucun entretien n\'a été trouvée.';?>
        <p class="notice"><?php echo $message;?></p>
    <?php else:?>
<?php $pagination = $xpaginator->paginationBlock( 'Entretien', $this->passedArgs ); ?>
<?php echo $pagination;?>
    <table id="searchResults" class="tooltips">
        <thead>
            <tr>
                <th><?php echo $xpaginator->sort( 'Date de l\'entretien', 'Entretien.dateentretien' );?></th>
                <th><?php echo $xpaginator->sort( 'Nom de l\'allocataire', 'Personne.nom' );?></th>
                <th><?php echo $xpaginator->sort( 'Commune de l\'allocataire', 'Adresse.locaadr' );?></th>
                <th><?php echo $xpaginator->sort( 'Structure référente', 'Structurereferente.lib_struc' );?></th>
                <th><?php echo $xpaginator->sort( 'Référent', 'Referent.nom' );?></th>
                <th><?php echo $xpaginator->sort( 'Type d\'entretien', 'Entretien.typeentretien' );?></th>
                <th><?php echo $xpaginator->sort( 'Objet de l\'entretien', 'Objetentretien.name' );?></th>
                <th><?php echo $xpaginator->sort( 'A revoir le', 'Entretien.arevoirle' );?></th>
                <th class="action">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach( $entretiens as $index => $entretien ):?>
            <?php
                    $innerTable = '<table id="innerTable'.$index.'" class="innerTable">
                        <tbody>
                            <tr>
                                <th>Date naissance</th>
                                <td>'.h( date_short( $entretien['Personne']['dtnai'] ) ).'</td>
                            </tr>
                            <tr>
                                <th>Numéro CAF</th>
                                <td>'.h( $entretien['Dossier']['matricule'] ).'</td>
                            </tr>
                            <tr>
                                <th>NIR</th>
                                <td>'.h( $entretien['Personne']['nir'] ).'</td>
                            </tr>
                            <tr>
                                <th>Code postal</th>
                                <td>'.h( $entretien['Adresse']['codepos'] ).'</td>
                            </tr>
                            <tr>
                                <th>Code INSEE</th>
                                <td>'.h( $entretien['Adresse']['numcomptt'] ).'</td>
                            </tr>
                        </tbody>
                    </table>';
                    $title = $entretien['Dossier']['numdemrsa'];

                    echo $xhtml->tableCells(
                            array(
                                h( date_short(  $entretien['Entretien']['dateentretien'] ) ),
                                h( $entretien['Personne']['qual'].' '.$entretien['Personne']['nom'].' '.$entretien['Personne']['prenom'] ),
                                h( $entretien['Adresse']['locaadr'] ),
                                h( $entretien['Structurereferente']['lib_struc'] ),
                                h( $entretien['Referent']['qual'].' '.$entretien['Referent']['nom'].' '.$entretien['Referent']['prenom'] ),
                                h( Set::enum( $entretien['Entretien']['typeentretien'], $options['typeentretien'] ) ),
                                h( $entretien['Objetentretien']['name'] ),
                                h( $locale->date( 'Date::miniLettre', $entretien['Entretien']['arevoirle'] ) ),
                                $xhtml->viewLink(
                                    'Voir le contrat',
                                    array( 'controller' => 'entretiens', 'action' => 'index', $entretien['Personne']['id'] ),
                                    $permissions->check( 'entretiens', 'index' )
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
                    array( 'controller' => 'criteresentretiens', 'action' => 'exportcsv', implode_assoc( '/', ':', array_unisize( $this->data ) ) )
                );
            ?></li>
        </ul>
    <?php echo $pagination;?>

<?php endif?>
<?php endif?>