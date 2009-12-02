<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Demandes de recours';?>


<h1>Demandes de Recours</h1>

<?php
    if( isset( $recoursapres ) ) {
        $paginator->options( array( 'url' => $this->params['named'] ) );
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

<?php if( isset( $recoursapres ) ):?>

    <?php if( is_array( $recoursapres ) && count( $recoursapres ) > 0 ):?>
        <?php echo $pagination;?>
        <?php echo $xform->create( 'Recoursapre', array( 'url'=> Router::url( null, true ) ) );?>

        <table id="searchResults" class="tooltips_oupas">
            <thead>
                <tr>
                    <th><?php echo $paginator->sort( 'N° demande APRE', 'Apre.numeroapre' );?></th>
                    <th><?php echo $paginator->sort( 'Nom de l\'allocataire', 'Personne.nom' );?></th>
                    <th><?php echo $paginator->sort( 'Commune de l\'allocataire', 'Adresse.locaadr' );?></th>
                    <th><?php echo $paginator->sort( 'Date demande APRE', 'Apre.datedemandeapre' );?></th>
                    <th>Décision comité examen</th>
                    <th><?php echo $paginator->sort( 'Date décision comité', 'Comiteapre.datecomite' );?></th>
                    <th>Demande de recours</th>
                    <th>Date recours</th>
                    <th>Observations</th>
                    <th class="action noprint">Actions</th>
                    <th class="innerTableHeader noprint">Informations complémentaires</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $recoursapres as $index => $recours ):?>
                <?php
                    $innerTable = '<table id="innerTable'.$index.'" class="innerTable">
                            <tbody>
                                <tr>
                                    <th>N° CAF</th>
                                    <td>'.h( $recours['Dossier']['matricule'] ).'</td>
                                </tr>
                                <tr>
                                    <th>Date naissance</th>
                                    <td>'.h( date_short( $recours['Personne']['dtnai'] ) ).'</td>
                                </tr>
                                <tr>
                                    <th>NIR</th>
                                    <td>'.h( $recours['Personne']['nir'] ).'</td>
                                </tr>
                                <tr>
                                    <th>Code postal</th>
                                    <td>'.h( $recours['Adresse']['codepos'] ).'</td>
                                </tr>
                            </tbody>
                        </table>';
                        $title = $recours['Dossier']['numdemrsa'];


                    $apre_id = Set::extract( $recours, 'ApreComiteapre.apre_id');
                    $recoursapre_id = Set::extract( $recours, 'ApreComiteapre.comiteapre_id');
                    $aprecomiteapre_id = Set::extract( $recours, 'ApreComiteapre.id');
// debug($recours);

                    $valueRecourapre = Set::classicExtract( $this->data, 'ApreComiteapre.'.$index.'.recoursapre' );
                    echo $html->tableCells(
                        array(
                            h( Set::classicExtract( $recours, 'Apre.numeroapre') ),
                            h( Set::classicExtract( $recours, 'Personne.qual').' '.Set::classicExtract( $recours, 'Personne.nom').' '.Set::classicExtract( $recours, 'Personne.prenom') ),
                            h( Set::classicExtract( $recours, 'Adresse.locaadr') ),
                            h( $locale->date( 'Date::short', Set::extract( $recours, 'Apre.datedemandeapre' ) ) ),
                            h( Set::classicExtract( $recours, 'ApreComiteapre.decisioncomite') ),
                            h( $locale->date( 'Date::short', Set::extract( $recours, 'Comiteapre.datecomite' ) ) ),
                            $xform->enum( 'ApreComiteapre.'.$index.'.recoursapre', array( 'legend' => false, 'type' => 'radio', 'separator' => '<br />', 'options' => $options['recoursapre'], 'value' => ( !empty( $valueRecourapre ) ? $valueRecourapre : 'N' ) ) ).
                            $xform->input( 'ApreComiteapre.'.$index.'.apre_id', array( 'label' => false, 'div' => false, 'value' => $apre_id, 'type' => 'hidden' ) ).
                            $xform->input( 'ApreComiteapre.'.$index.'.id', array( 'label' => false, 'div' => false, 'value' => $aprecomiteapre_id, 'type' => 'hidden' ) ).
                            $xform->input( 'ApreComiteapre.'.$index.'.comiteapre_id', array( 'label' => false, 'type' => 'hidden', 'value' => $recoursapre_id ) ).
                            $xform->input( 'Comiteapre.'.$index.'.id', array( 'label' => false, 'type' => 'hidden', 'value' => Set::extract( $recours, 'Comiteapre.id' ) ) ).
                            $xform->input( 'Apre.'.$index.'.id', array( 'label' => false, 'type' => 'hidden', 'value' => Set::extract( $recours, 'Apre.id' ) ) ),

                            $xform->input( 'ApreComiteapre.'.$index.'.daterecours', array( 'label' => false, 'type' => 'date', 'dateFormat' => 'DMY' ) ),
                            $xform->input( 'ApreComiteapre.'.$index.'.observationrecours', array( 'label' => false, 'type' => 'text', 'rows' => 3 ) ),
                            $html->viewLink(
                                'Voir le comite « '.Set::extract( $recours, 'Comiteapre.id' ).' »',
                                array( 'controller' => 'comitesapres', 'action' => 'view', Set::extract( $recours, 'Comiteapre.id' ) ),
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
        <?php echo $xform->submit( 'Validation de la liste' );?>
        <?php echo $xform->end();?>


    <?php else:?>
        <p>Aucune demande de recours présente dans la cohorte.</p>
    <?php endif?>
<?php endif?>