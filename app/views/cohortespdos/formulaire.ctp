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
    <?php echo $pagination;?> 
        <table id="searchResults" class="tooltips_oupas">
            <thead>
                <tr>
                    <th><?php echo $paginator->sort( 'Nom de l\'allocataire', 'Personne.nom'.' '.'Personne.prenom' );?></th>
                    <th><?php echo $paginator->sort( 'Suivi', 'Dossier.typeparte' );?></th>
                    <th><?php echo $paginator->sort( 'Situation des droits', 'Situationdossierrsa.etatdosrsa' );?></th>
                    <th><?php echo $paginator->sort( 'Type de PDO', 'Propopdo.typepdo' );?></th>
                    <th><?php echo $paginator->sort( 'Date de décision PDO', 'Propopdo.decisionpdo' );?></th>
                    <th style="width:10em"><?php echo $paginator->sort( 'Décision PDO', 'Propopdo.datedecisionpdo' );?></th>
                     <th>Commentaires</th> 
                    <th class="action noprint">Action</th>
                    <th class="innerTableHeader noprint">Informations complémentaires</th>
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

                    $statutAvis = Set::extract( $pdo, 'Propopdo.'.$index.'.decisionpdo' );
// debug( $pdo );
                    $dossier_rsa_id = Set::extract( $pdo, 'Propopdo.dossier_rsa_id');
                    $pdo_id = Set::extract( $pdo, 'Propopdo.id');

                    echo $html->tableCells(
                        array(
                            h( $pdo['Personne']['nom'].' '.$pdo['Personne']['prenom'] ),
                            h( $pdo['Dossier']['typeparte'] ),
                            h( value( $etatdosrsa, Set::extract( $pdo, 'Situationdossierrsa.etatdosrsa' ) ) ),
                            h( value( $typepdo, Set::extract( 'Propopdo.typepdo', $pdo ) ) ),
                            h( date_short( Set::extract( 'Propopdo.datedecisionpdo', $pdo ) ) ),

                            $form->input( 'Propopdo.'.$index.'.decisionpdo', array( 'label' => false, 'div' => false, 'legend' => false, 'separator' => '<br />' , 'type' => 'radio', 'options' => array( 'P' => 'Pas de réponse', 'A' => 'Accord', 'R' => 'Refus', 'J' => 'Ajourné' ), 'value' => ( !empty( $statutAvis ) ? $statutAvis : 'P' ) ) ).
                            $form->input( 'Propopdo.'.$index.'.dossier_rsa_id', array( 'label' => false, 'div' => false, 'value' => $dossier_rsa_id, 'type' => 'hidden' ) ).
                            $form->input( 'Propopdo.'.$index.'.id', array( 'label' => false, 'div' => false, 'value' => $pdo_id, 'type' => 'hidden' ) ).
                            $form->input( 'Propopdo.'.$index.'.dossier_id', array( 'label' => false, 'type' => 'hidden', 'value' => $pdo['Dossier']['id'] ) ),

                            $form->input( 'Propopdo.'.$index.'.commentairepdo', array( 'label' => false, 'type' => 'text', 'rows' => 3, 'value' => $pdo['Propopdo']['commentairepdo'] ) ),
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
    <?php echo $pagination;?> 

    <?php else:?>
        <p>Vos critères n'ont retourné aucun dossier.</p>
    <?php endif?>
<?php endif?>