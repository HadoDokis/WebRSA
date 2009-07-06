<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Recherche par dossier/allocataire';?>

<h1>Recherche par dossier / allocataire</h1>

<ul class="actionMenu">
    <?php
        if( $permissions->check( 'ajoutdossiers', 'wizard' ) ) {
            echo '<li>'.$html->addLink(
                'Ajouter un dossier',
                array( 'controller' => 'ajoutdossiers', 'action' => 'wizard' )
            ).' </li>';
        }

        if( $permissions->check( 'dossierssimplifies', 'add' ) ) {
//        if( $session->read( 'Auth.User.username' ) == 'cg66' ) { // FIXME

            echo '<li>'.$html->addSimpleLink(
                'Ajouter une préconisation d\'orientation',
                array( 'controller' => 'dossierssimplifies', 'action' => 'add' )
            ).' </li>';
//        }
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
<!-- FIXME le repasser en post ? -->
<?php echo $form->create( 'Dossier', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( !empty( $this->data ) ? 'folded' : 'unfolded' ) ) );?>

    <fieldset>
        <legend>Recherche par dossier</legend>
        <?php echo $form->input( 'Dossier.numdemrsa', array( 'label' => 'Numéro de dossier RSA' ) );?>
        <!--<?php echo $form->input( 'Dossier.numero_dossier_caf', array( 'label' => 'Numéro de dossier CAF' ) );?>-->
        <?php echo $form->input( 'Dossier.dtdemrsa', array( 'label' => 'Filtrer par date de demande', 'type' => 'checkbox' ) );?>
        <fieldset>
            <legend>Date de demande RSA</legend>
            <?php echo $form->input( 'Dossier.dtdemrsa_from', array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => strtotime( '-1 week' ) ) );?>
            <?php echo $form->input( 'Dossier.dtdemrsa_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120 ) );?>
        </fieldset>
    </fieldset>
    <fieldset>
        <legend>Recherche par personne du foyer</legend>
        <?php echo $form->input( 'Personne.dtnai', array( 'label' => 'Date de naissance', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'empty' => true ) );?>
        <?php echo $form->input( 'Personne.nom', array( 'label' => 'Nom' ) );?>
        <?php echo $form->input( 'Personne.nomnai', array( 'label' => 'Nom de jeune fille' ) );?>
        <?php echo $form->input( 'Personne.prenom', array( 'label' => 'Prénom' ) );?>
    </fieldset>

    <div class="submit">
        <?php echo $form->button( 'Rechercher', array( 'type' => 'submit' ) );?>
        <?php echo $form->button( 'Réinitialiser', array( 'type'=>'reset' ) );?>
    </div>
<?php echo $form->end();?>

<!-- Résultats -->
<?php if( isset( $dossiers ) ):?>
    <h2>Résultats de la recherche</h2>

    <?php if( is_array( $dossiers ) && count( $dossiers ) > 0 ):?>
        <?php require( 'index.pagination.ctp' )?>
        <table id="searchResults" class="tooltips_oupas">
            <thead>
                <tr>
                    <th>Numéro dossier</th>
                    <th>Date de demande</th>
                    <th>NIR</th>
                    <th>Allocataire</th>
                    <th>Commune de l'Allocataire</th>
                    <!--<th>État du dossier</th>-->
                    <th class="action">Actions</th>
                    <th class="action">Verrouillé</th>
                    <th class="innerTableHeader">Informations complémentaires</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $dossiers as $index => $dossier ):?>
                    <?php
                        $title = $dossier['Dossier']['numdemrsa'];

                        $innerTable = '<table id="innerTable'.$index.'" class="innerTable">
                            <tbody>
                               <!-- <tr>
                                    <th>Commune de naissance</th>
                                    <td>'.$dossier['Foyer']['Personne'][0]['nomcomnai'].'</td>
                                </tr> -->
                                <tr>
                                    <th>Date de naissance</th>
                                    <td>'.date_short( $dossier['Foyer']['Personne'][0]['dtnai'] ).'</td>
                                </tr>
                                <tr>
                                    <th>Etat du dossier</th>
                                    <td>'.(array_key_exists( $dossier['Situationdossierrsa']['etatdosrsa'] ,$etatdosrsa ) ? $etatdosrsa[$dossier['Situationdossierrsa']['etatdosrsa']] : null ).'</td>
                                </tr>
                            </tbody>
                        </table>';
// debug( $dossier['Foyer']['Personne'] );
                        echo $html->tableCells(
                            array(
                                h( $dossier['Dossier']['numdemrsa'] ),
                                h( date_short( $dossier['Dossier']['dtdemrsa'] ) ),
                                h( $dossier['Foyer']['Personne'][0]['nir'] ), // FIXME: 0
                                implode(
                                    ' ',
                                    array(
                                        $dossier['Foyer']['Personne'][0]['qual'],
                                        $dossier['Foyer']['Personne'][0]['nom'],
                                        implode( ' ', array( $dossier['Foyer']['Personne'][0]['prenom'], $dossier['Foyer']['Personne'][0]['prenom2'], $dossier['Foyer']['Personne'][0]['prenom3'] ) )
                                    )
                                ),
                                h(Set::extract(  $dossier, 'Derniereadresse.Adresse.locaadr' ) ),
                                //h( isset( $etatdosrsa[$dossier['Situationdossierrsa']['etatdosrsa']] ) ? $etatdosrsa[$dossier['Situationdossierrsa']['etatdosrsa']] : null ),

                                $html->viewLink(
                                    'Voir le dossier « '.$title.' »',
                                    array( 'controller' => 'dossiers', 'action' => 'view', $dossier['Dossier']['id'] )
                                ),
                                ( $dossier['Dossier']['locked'] ?
                                    $html->image(
                                        'icons/lock.png',
                                        array( 'alt' => '', 'title' => 'Dossier verrouillé' )
                                    ) : null
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