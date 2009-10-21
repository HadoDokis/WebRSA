<h1><?php echo $this->pageTitle = $pageTitle;?></h1>

<?php

    if( !empty( $this->data ) ) {
        echo '<ul class="actionMenu"><li>'.$html->link(
            $html->image(
                'icons/application_form_magnify.png',
                array( 'alt' => '' )
            ).' Formulaire',
            '#',
            array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Filtre' ).toggle(); return false;" )
        ).'</li></ul>';
    }


    function value( $array, $index ) {
        $keys = array_keys( $array );
        $index = ( ( $index == null ) ? '' : $index );
        if( @in_array( $index, $keys ) && isset( $array[$index] ) ) {
            return $array[$index];
        }
        else {
            return null;
        }
    }

?>

<?php require_once( 'filtre.ctp' );?>

<?php if( !empty( $this->data ) ):?>
    <?php if( empty( $cohorte ) ):?>
        <?php
            switch( $this->action ) {
                case 'orientees':
                    $message = 'Aucun allocataire orienté ne correspond à vos critères.';
                    break;
                default:
                    $message = 'Tous les allocataires ont été orientés.';
            }
        ?>
        <p class="notice"><?php echo $message;?></p>
    <?php else:?>
        <p><?php echo sprintf( 'Nombre de pages: %s - Nombre de résultats: %s.', $locale->number( $pages ), $locale->number( $count ) );?></p>
        <?php /*echo $pagination;*/ ?>
        <table class="tooltips_oupas">
            <thead>
                <tr>
                    <!-- <th><?php echo $paginator->sort( 'Commune', 'Adresse.locaadr' );?></th>
                    <th><?php echo $paginator->sort( 'Nom de l\'allocataire', 'Personne.nom' );?></th>
                    <th><?php echo $paginator->sort( 'Date demande', 'Dossier.dtdemrsa' );?></th>
                    <th><?php echo $paginator->sort( 'Date ouverture des droits', 'Dossier.dtdemrsa' );?></th>
                    <th><?php echo $paginator->sort( 'Service instructeur', 'Suiviinstruction.typeserins' );?></th>
                    <th><?php echo $paginator->sort( 'PréOrientation', 'Orientstruct.propo_algo' );?></th>
                    <th><?php echo $paginator->sort( 'Orientation', 'Orientstruct.typeorient_id' );?></th>
                    <th><?php echo $paginator->sort( 'Structure', 'Structurereferente.lib_struc' );?></th>
                    <th><?php echo $paginator->sort( 'Décision', 'Orientstruct.statut_orient' );?></th>
                    <th><?php echo $paginator->sort( 'Date proposition', 'Orientstruct.date_propo' );?></th>
                    <th><?php echo $paginator->sort( 'Date dernier CI', 'Contratinsertion.dd_ci' );?></th> -->

                    <th>Commune</th>
                    <th>Nom prenom</th>
                    <th>Date demande</th>
                    <th>Présence DSP</th>
                    <th>Service instructeur</th>
                    <th>PréOrientation</th>
                    <th>Orientation</th>
                    <th>Structure</th>
                    <th>Décision</th>
                    <th>Date proposition</th>
                    <th>Date dernier CI</th> 
                    <th class="action">Action</th>
                    <th class="innerTableHeader">Informations complémentaires</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $cohorte as $index => $personne ):?>
                    <?php
                        // FIXME: date ouverture de droits -> voir flux instruction
                        $innerTable = '<table id="innerTable'.$index.'" class="innerTable">
                            <tbody>
                                <tr>
                                    <th>N° de dossier</th>
                                    <td>'.h( $personne['Dossier']['numdemrsa'] ).'</td>
                                </tr>
                                <tr>
                                    <th>Date ouverture de droit</th>
                                    <td>'.h( date_short( $personne['Dossier']['dtdemrsa'] ) ).'</td>
                                </tr>
                                <tr>
                                    <th>Date naissance</th>
                                    <td>'.h( date_short( $personne['Personne']['dtnai'] ) ).'</td>
                                </tr>
                                <tr>
                                    <th>Numéro CAF</th>
                                    <td>'.h( $personne['Dossier']['matricule'] ).'</td>
                                </tr>
                                <tr>
                                    <th>NIR</th>
                                    <td>'.h( $personne['Personne']['nir'] ).'</td>
                                </tr>
                                <tr>
                                    <th>Code postal</th>
                                    <td>'.h( $personne['Adresse']['codepos'] ).'</td>
                                </tr>
                                <tr>
                                    <th>Canton</th>
                                    <td>'.h( $personne['Adresse']['canton'] ).'</td>
                                </tr>
                                <tr>
                                    <th>Date de fin de droit</th>
                                    <td>'.h( date_short( $personne['Situationdossierrsa']['dtclorsa'] ) ).'</td>
                                </tr>
                                <tr>
                                    <th>Motif de fin de droit</th>
                                    <td>'.h( $personne['Situationdossierrsa']['moticlorsa'] ).'</td>
                                </tr>
                            </tbody>
                        </table>';

                        echo $html->tableCells(
                            array(
                                h( $personne['Adresse']['locaadr'] ),
                                h( $personne['Personne']['nom'].' '.$personne['Personne']['prenom'] ),
                                h( date_short( $personne['Dossier']['dtdemrsa'] ) ),
                                h( $personne['Dspp'] ? 'Oui' : 'Non' ),
                                h( value( $typeserins, Set::classicExtract( $personne, 'Suiviinstruction.typeserins') ) ),
                                h( isset( $typesOrient[$personne['Orientstruct']['propo_algo']] ) ? $typesOrient[$personne['Orientstruct']['propo_algo']] : null),
                                h( $typesOrient[$personne['Orientstruct']['typeorient_id']] ),
                                h( $personne['Orientstruct']['Structurereferente']['lib_struc'] ),
                                h( $personne['Orientstruct']['statut_orient'] ),
                                h( date_short( $personne['Orientstruct']['date_propo'] ) ),
                                h( date_short( $personne['Contratinsertion']['dd_ci'] ) ),
                                $html->printLink(
                                    'Imprimer la notification',
                                    array( 'controller' => 'gedooos', 'action' => 'notification_structure', $personne['Personne']['id'] ),
                                    $permissions->check( 'gedooos', 'notification_structure' )
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
        <?php /*echo $pagination;*/ ?>
        <ul class="actionMenu">
            <li><?php
                echo $html->printCohorteLink(
                    'Imprimer la cohorte',
                    Set::merge(
                        array(
                            'controller' => 'gedooos',
                            'action'     => 'notifications_cohortes'
                        ),
                        array_unisize( $this->data )
                    )
                );
            ?></li>

            <!--<li><?php
                echo $html->exportLink(
                    'Télécharger le tableau',
                    Set::merge(
                        array( 'controller' => 'cohortes', 'action' => 'exportcsv', implode_assoc( '/', ':', array_unisize( $this->data ) ) )
                    )
                );
            ?></li>-->
        </ul>
    <?php endif;?>
<?php endif;?>