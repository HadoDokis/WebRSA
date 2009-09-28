<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Gestion des PDOs';?>

<h1>Gestion des PDOs</h1>

<?php
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

<?php  require_once( 'filtre.ctp' );?>
<!-- Résultats -->

<?php if( isset( $cohortepdo ) ):?>

    <?php if( is_array( $cohortepdo ) && count( $cohortepdo ) > 0 ):?>
        <?php echo $pagination;?>
        <?php echo $form->create( 'Propopdo', array( 'url'=> Router::url( null, true ) ) );?>

        <table id="searchResults" class="tooltips_oupas">
            <thead>
                <tr>
                    <!-- <th><?php echo $paginator->sort( 'Nom de l\'allocataire', 'Personne.nom'.' '.'Personne.prenom' );?></th>
                    <th><?php echo $paginator->sort( 'Suivi', 'Dossier.typeparte' );?></th>
                     <th>Situation des droits RSA</th>
                    <th><?php echo $paginator->sort( 'Type de PDO', 'Propopdo.typepdo' );?></th>
                    <th><?php echo $paginator->sort( 'Date de décision PDO', 'Propopdo.decisionpdo' );?></th>
                    <th style="width:10em"><?php echo $paginator->sort( 'Décision PDO', 'Propopdo.datedecisionpdo' );?></th> -->
                    <th>Nom de l'allocataire</th>
                    <th>Suivi</th>
                    <th>Type de PDO</th>
                    <th>Date de décision PDO</th>
                    <th>Decision PDO</th>
                    <th>Motif</th>
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
                                    <th>N° Dossier</th>
                                    <td>'.h( $pdo['Dossier']['numdemrsa'] ).'</td>
                                </tr>
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

                    /*$_decisionpdo = Set::extract( $this->data, 'Propopdo.'.$index.'.decisionpdo' );
                    $_typepdo = Set::extract( $this->data, 'Propopdo.'.$index.'.typepdo' );*/

                    $dossier_rsa_id = $pdo['Dossier']['id'];//Set::extract( $pdo, 'Propopdo.dossier_rsa_id');
                    $pdo_id = Set::extract( $pdo, 'Propopdo.id');

                    echo $html->tableCells(
                        array(
                            h( $pdo['Personne']['nom'].' '.$pdo['Personne']['prenom'] ),
                            h( $pdo['Dossier']['typeparte'] ),

                            $form->input( 'Propopdo.'.$index.'.typepdo', array( 'label' => false, 'type' => 'select', 'options' => $typepdo/*, 'value' => $_typepdo*/ ) ),

                            $form->input( 'Propopdo.'.$index.'.datedecisionpdo', array( 'label' => false, 'div' => false, 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 5, 'minYear' => date( 'Y' ) - 5 ) ),

                            $form->input( 'Propopdo.'.$index.'.decisionpdo', array( 'label' => false, 'type' => 'select', 'options' => $decisionpdo, 'empty' => true/*, 'value' => $_decisionpdo*/ ) ).

                            $form->input( 'Propopdo.'.$index.'.dossier_rsa_id', array( 'label' => false, 'div' => false, 'value' => $dossier_rsa_id, 'type' => 'hidden' ) ).

                            $form->input( 'Propopdo.'.$index.'.id', array( 'label' => false, 'div' => false, 'value' => $pdo_id, 'type' => 'hidden' ) ).

                            $form->input( 'Propopdo.'.$index.'.dossier_id', array( 'label' => false, 'type' => 'hidden', 'value' => $pdo['Dossier']['id'] ) ),

                            $form->input( 'Propopdo.'.$index.'.motifpdo', array( 'label' => false, 'type' => 'select', 'options' => $motifpdo, 'empty' => true ) ),

                            $form->input( 'Propopdo.'.$index.'.commentairepdo', array( 'label' => false, 'type' => 'text', 'rows' => 3 ) ),
                            $html->viewLink(
                                'Voir le dossier « '.$pdo['Dossier']['numdemrsa'].' »',
                                array( 'controller' => 'suivisinsertion', 'action' => 'index', $pdo['Dossier']['id'] ),
                                true,
                                true
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
    <?php echo $pagination;?>
        <?php echo $form->submit( 'Validation de la liste' );?>
        <?php echo $form->end();?>


    <?php else:?>
        <p>Aucune PDO dans la cohorte.</p>
    <?php endif?>
<?php endif?>