<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Avis commission APREs';?>

<h1>Avis commission APREs</h1>

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

    if( isset( $commissionapre ) ) {
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

<?php if( isset( $commissionapre ) ):?>

    <?php if( is_array( $commissionapre ) && count( $commissionapre ) > 0 ):?>
        <?php echo $pagination;?>
        <?php echo $form->create( 'Apre', array( 'url'=> Router::url( null, true ) ) );?>

        <table id="searchResults" class="tooltips_oupas">
            <thead>
                <tr>
                    <th>Nom de l'allocataire</th>
                    <th>Date de demande RSA</th>
                    <th>Type d'APRE</th>
                    <th>Decision APRE</th>
                    <th>Date de demande APRE</th>
                    <th>Motif</th>
                    <th>Commentaires</th>
                    <th class="action noprint">Action</th>
                    <th class="innerTableHeader noprint">Informations complémentaires</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $commissionapre as $index => $apre ):?>
                <?php
                    $innerTable = '<table id="innerTable'.$index.'" class="innerTable">
                            <tbody>
                                <tr>
                                    <th>N° Dossier</th>
                                    <td>'.h( $apre['Dossier']['numdemrsa'] ).'</td>
                                </tr>
                                <tr>
                                    <th>Date naissance</th>
                                    <td>'.h( date_short( $apre['Personne']['dtnai'] ) ).'</td>
                                </tr>
                                <tr>
                                    <th>Numéro CAF</th>
                                    <td>'.h( $apre['Dossier']['matricule'] ).'</td>
                                </tr>
                                <tr>
                                    <th>NIR</th>
                                    <td>'.h( $apre['Personne']['nir'] ).'</td>
                                </tr>
                                <tr>
                                    <th>Code postal</th>
                                    <td>'.h( $apre['Adresse']['codepos'] ).'</td>
                                </tr>
                            </tbody>
                        </table>';
                        $title = $apre['Dossier']['numdemrsa'];


                    $dossier_rsa_id = $apre['Dossier']['id'];//Set::extract( $apre, 'Apre.dossier_rsa_id');
                    $apre_id = Set::extract( $apre, 'Apre.id');

                    echo $html->tableCells(
                        array(
                            h( $apre['Personne']['nom'].' '.$apre['Personne']['prenom'] ),
                            h( date_short( $apre['Dossier']['dtdemrsa'] ) ),

                            $form->input( 'Apre.'.$index.'.typepdo_id', array( 'label' => false, 'type' => 'select', 'options' => $typepdo ) ),

                            $form->input( 'Apre.'.$index.'.typecontrat', array( 'label' => false, 'type' => 'select', 'options' => $typecontrat, 'empty' => true ) ).

                            $form->input( 'Apre.'.$index.'.dossier_rsa_id', array( 'label' => false, 'div' => false, 'value' => $dossier_rsa_id, 'type' => 'hidden' ) ).

                            $form->input( 'Apre.'.$index.'.id', array( 'label' => false, 'div' => false, 'value' => $apre_id, 'type' => 'hidden' ) ).

                            $form->input( 'Apre.'.$index.'.dossier_id', array( 'label' => false, 'type' => 'hidden', 'value' => $apre['Dossier']['id'] ) ),


                            $form->input( 'Apre.'.$index.'.datedemandeapre', array( 'label' => false, 'div' => false, 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 5, 'minYear' => date( 'Y' ) - 5 ) ),

                            $form->input( 'Apre.'.$index.'.motifpdo', array( 'label' => false, 'type' => 'select', 'options' => $motifpdo, 'empty' => true ) ),

                            $form->input( 'Apre.'.$index.'.commentairepdo', array( 'label' => false, 'type' => 'text', 'rows' => 3 ) ),
                            $html->viewLink(
                                'Voir le dossier « '.$apre['Dossier']['numdemrsa'].' »',
                                array( 'controller' => 'suivisinsertion', 'action' => 'index', $apre['Dossier']['id'] ),
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