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
        <?php echo $form->input( 'Dossier.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>
        <?php echo $form->input( 'Dossier.numdemrsa', array( 'label' => 'Numéro de dossier RSA' ) );?>
        <?php echo $form->input( 'Dossier.matricule', array( 'label' => 'Numéro CAF', 'maxlength' => 15 ) );?>

        <!--<?php echo $form->input( 'Dossier.numero_dossier_caf', array( 'label' => 'Numéro de dossier CAF' ) );?>-->
        <?php echo $form->input( 'Dossier.dtdemrsa', array( 'label' => 'Filtrer par date de demande', 'type' => 'checkbox' ) );?>
        <fieldset>
            <legend>Date de demande RSA</legend>
            <?php
                $dtdemrsa_from = Set::check( $this->data, 'Dossier.dtdemrsa_from' ) ? Set::extract( $this->data, 'Dossier.dtdemrsa_from' ) : strtotime( '-1 week' );
                $dtdemrsa_to = Set::check( $this->data, 'Dossier.dtdemrsa_to' ) ? Set::extract( $this->data, 'Dossier.dtdemrsa_to' ) : strtotime( 'now' );
            ?>
            <?php echo $form->input( 'Dossier.dtdemrsa_from', array( 'label' => 'Du', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $dtdemrsa_from ) );?>
            <?php echo $form->input( 'Dossier.dtdemrsa_to', array( 'label' => 'Au', 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ), 'minYear' => date( 'Y' ) - 120, 'selected' => $dtdemrsa_to ) );?>
        </fieldset>
    </fieldset>
    <fieldset>
        <legend>Recherche par Adresse</legend>
        <?php echo $form->input( 'Adresse.numcomptt', array( 'label' => 'Numéro de commune au sens INSEE' ) );?>
    </fieldset>
    <fieldset>
        <legend>Recherche par allocataire<!--FIXME: personne du foyer--></legend>
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
                    <th><?php echo $paginator->sort( 'Numéro de dossier', 'Dossier.numdemrsa' );?></th>
                    <th><?php echo $paginator->sort( 'Date de demande', 'Dossier.dtdemrsa' );?></th>
                    <th><?php echo $paginator->sort( 'NIR', 'Personne.nir' );?></th>
                    <th><?php echo $paginator->sort( 'Allocataire', 'Personne.nom' );?></th><!-- FIXME: qual/nom/prénom -->
                    <th><?php echo $paginator->sort( 'Commune de l\'allocataire', 'Adresse.locaadr' );?></th>
                    <!-- <th>Numéro dossier</th>
                    <th>Date de demande</th> -->
                    <!--<th>NIR</th>
                    <th>Allocataire</th>
                    <th>Commune de l'Allocataire</th>-->
                    <th class="action">Actions</th>
                    <th class="action">Verrouillé</th>
                    <th class="innerTableHeader">Informations complémentaires</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $dossiers as $index => $dossier ):?>
                    <?php
                        $title = $dossier['Dossier']['numdemrsa'];
// debug( $dossier );
                        $innerTable = '<table id="innerTable'.$index.'" class="innerTable">
                            <tbody>
                               <tr>
                                    <th>Numéro CAF</th>
                                    <td>'.$dossier['Dossier']['matricule'].'</td>
                                </tr>
                                <tr>
                                    <th>Date de naissance</th>
                                    <td>'.date_short( $dossier['Personne']['dtnai'] ).'</td>
                                </tr>
                                <tr>
                                    <th>Etat du dossier</th>
                                    <td>'.(array_key_exists( $dossier['Situationdossierrsa']['etatdosrsa'] ,$etatdosrsa ) ? $etatdosrsa[$dossier['Situationdossierrsa']['etatdosrsa']] : null ).'</td>
                                </tr>
                                <tr>
                                    <th>Code INSEE</th>
                                    <td>'.$dossier['Adresse']['numcomptt'].'</td>
                                </tr>
                            </tbody>
                        </table>';
// debug( $dossier['Personne'] );
                        echo $html->tableCells(
                            array(
                                h( $dossier['Dossier']['numdemrsa'] ),
                                h( date_short( $dossier['Dossier']['dtdemrsa'] ) ),
                                h( $dossier['Personne']['nir'] ),
                                implode(
                                    ' ',
                                    array(
                                        $dossier['Personne']['qual'],
                                        $dossier['Personne']['nom'],
                                        implode( ' ', array( $dossier['Personne']['prenom'], $dossier['Personne']['prenom2'], $dossier['Personne']['prenom3'] ) )
                                    )
                                ),
                                h( Set::extract(  $dossier, 'Adresse.locaadr' ) ),
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
       <ul class="actionMenu">
            <li><?php
                /*echo $html->exportLink(
                    'Télécharger le tableau',
                    array( 'controller' => 'dossiers', 'action' => 'exportcsv', implode_assoc( '/', ':', array_unisize( $this->data ) ) )
                );*/
            ?></li>
        </ul>
        <?php require( 'index.pagination.ctp' )?>
    <?php else:?>
        <p>Vos critères n'ont retourné aucun dossier.</p>
    <?php endif?>
<?php endif?>