<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Gestion des PDOs';?>

<h1>Gestion des PDOs</h1>

<?php
    if( is_array( $this->data ) ) {
        echo '<ul class="actionMenu"><li>'.$html->link(
            $html->image(
                'icons/application_form_magnify.png',
                array( 'alt' => '' )
            ).' Formulaire',
            '#',
            array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
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
    //

    if( isset( $cohortepdo ) ) {
        $paginator->options( array( 'url' => $this->passedArgs ) );
        $params = array( 'format' => 'Résultats %start% - %end% sur un total de %count%.' );
        $pagination = $html->tag( 'p', $paginator->counter( $params ) );

        $pages = $paginator->first( '<<' );
        $pages .= $paginator->prev( '<' );
        $pages .= $paginator->numbers();
        $pages .= $paginator->next( '>' );
        $pages .= $paginator->last( '>>' );

        $pagination .= $html->tag( 'p', $pages );
    }
    else {
        $pagination = '';
    }
?>

<?php require_once( 'filtre.ctp' );?>
<!-- Résultats -->

<?php if( isset( $cohortepdo ) ):?>

    <h2 class="noprint">Résultats de la recherche</h2>

    <?php if( is_array( $cohortepdo ) && count( $cohortepdo ) > 0 ):?>
        <?php echo $form->create( 'GestionPDO', array( 'url'=> Router::url( null, true ) ) );?>
   <!-- <?php /*echo $pagination;*/?> -->
        <table id="searchResults" class="tooltips_oupas">
            <thead>
                <tr>
                    <th><?php echo $paginator->sort( 'N° PDO', 'Derogation.id' );?></th>
                    <th><?php echo $paginator->sort( 'Nom de l\'allocataire', 'Personne.nom'.' '.'Personne.prenom' );?></th>
                    <th><?php echo $paginator->sort( 'Suivi', 'Dossier.typeparte' );?></th>
                    <th><?php echo $paginator->sort( 'Situation des droits', 'Situationdossierrsa.etatdosrsa' );?></th>
                    <th><?php echo $paginator->sort( 'Type de PDO', 'Derogation.typedero' );?></th>
                    <th><?php echo $paginator->sort( 'Date de soumission CAF', 'Derogation.avisdero' );?></th>
                    <th><?php echo $paginator->sort( 'Décision du CG (Droit)', 'Derogation.ddavisdero' );?></th>
                    <!-- <th>Commentaires</th> -->
                    <th class="action">Action</th>
                    <th class="innerTableHeader">Informations complémentaires</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $cohortepdo as $index => $pdo ):?>
                <?php
                    $innerTable = '<table id="innerTable'.$index.'" class="innerTable">
                            <tbody>
                                <tr>
                                    <th>Date naissance</th>
                                    <td>'.h( date_short( $pdo['Personne']['dtnai'] ) ).'</td>
                                </tr>
                                <tr>
                                    <th>Numéro CAF</th>
                                    <td>'.h( $pdo['Dossier']['matricule'] ).'</td>
                                </tr>
                                <tr>
                                    <th>NIR</th>
                                    <td>'.h( $pdo['Personne']['nir'] ).'</td>
                                </tr>
                                <tr>
                                    <th>Code postal</th>
                                    <td>'.h( $pdo['Adresse']['codepos'] ).'</td>
                                </tr>
                            </tbody>
                        </table>';
                        $title = $pdo['Dossier']['numdemrsa'];

                    $statutAvis = Set::extract( $pdo, 'Derogation.'.$index.'.avisdero' );
// debug( $pdo );
                    $avispcgpersonne_id = Set::extract( $pdo, 'Derogation.avispcgpersonne_id');
                    $derogation_id = Set::extract( $pdo, 'Derogation.id');

                    echo $html->tableCells(
                        array(
                            h( $pdo['Derogation']['id'] ),
                            h( $pdo['Personne']['nom'].' '.$pdo['Personne']['prenom'] ),
                            h( $pdo['Dossier']['typeparte'] ),
                            h( value( $etatdosrsa, Set::extract( $pdo, 'Situationdossierrsa.etatdosrsa' ) ) ),
                            h( value( $typedero, Set::extract( 'Derogation.typedero', $pdo ) ) ),
                            h( date_short( Set::extract( 'Derogation.ddavisdero', $pdo ) ) ),
                            //h( value( $avisdero, Set::extract( 'Derogation.avisdero', $pdo ) ) ),
                            $form->input( 'Derogation.'.$index.'.avisdero', array( 'label' => false, 'div' => false, 'legend' => false, 'type' => 'radio', 'options' => array( 'O' => 'Ouvert', 'N' => 'Refusé' ), 'value' => ( !empty( $statutAvis ) ? $statutAvis : 'O' ) ) ).
                            $form->input( 'Derogation.'.$index.'.avispcgpersonne_id', array( 'label' => false, 'div' => false, 'value' => $avispcgpersonne_id, 'type' => 'hidden' ) ).
                            $form->input( 'Derogation.'.$index.'.id', array( 'label' => false, 'div' => false, 'value' => $derogation_id, 'type' => 'hidden' ) ).
                            $form->input( 'Derogation.'.$index.'.dossier_id', array( 'label' => false, 'type' => 'hidden', 'value' => $pdo['Dossier']['id'] ) ),
//                             $form->input( 'Derogation.'.$index.'.commentdero', array( 'label' => false, 'type' => 'text', 'rows' => 2, 'value' => $pdo['Derogation']['commentdero'] ) ),
                            $html->viewLink(
                                'Voir le contrat « '.$title.' »',
                                array( 'controller' => 'dossierspdo', 'action' => 'index', $pdo['Dossier']['id'] )
                            ),
                            array( $innerTable, array( 'class' => 'innerTableCell' ) )
                        ),
                        array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
                        array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
                    );
                ?>
                <?php endforeach;?>
            </tbody>
        </table>
        <?php echo $form->submit( 'Validation de la liste' );?>
        <?php echo $form->end();?>
   <!-- <?php /*echo $pagination;*/?> -->

    <?php else:?>
        <p>Vos critères n'ont retourné aucun dossier.</p>
    <?php endif?>
<?php endif?>