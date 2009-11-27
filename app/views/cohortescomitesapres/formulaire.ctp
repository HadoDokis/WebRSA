<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Décisions des comités';?>

<h1>Décisions Comité</h1>

<?php

    if( isset( $comitesapres ) ) {
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

<?php if( isset( $comitesapres ) ):?>

    <?php if( is_array( $comitesapres ) && count( $comitesapres ) > 0 ):?>
        <?php echo $pagination;?>
        <?php echo $xform->create( 'Cohortecomiteapre', array( 'url'=> Router::url( null, true ) ) );?>

        <table id="searchResults" class="tooltips_oupas">
            <thead>
                <tr>
                    <th><?php echo $paginator->sort( 'N° demande RSA', 'Dossier.numdemrsa' );?></th>
                    <th><?php echo $paginator->sort( 'Nom de l\'allocataire', 'Personne.nom' );?></th>
                    <th><?php echo $paginator->sort( 'Commune de l\'allocataire', 'Adresse.locaadr' );?></th>
                    <th><?php echo $paginator->sort( 'Date de demande APRE', 'Apre.datedemandeapre' );?></th>
                    <th>Décision comité examen</th>
                    <th><?php echo $paginator->sort( 'Date de décision comité', 'Comiteapre.datecomite' );?></th>
                    <th>Montant demandé</th>
                    <th>Montant attribué</th>
                    <th>Observations</th>
                    <th class="action noprint">Actions</th>
                    <th class="innerTableHeader noprint">Informations complémentaires</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $comitesapres as $index => $comite ):?>
                <?php
                    $innerTable = '<table id="innerTable'.$index.'" class="innerTable">
                            <tbody>
                                <tr>
                                    <th>N° CAF</th>
                                    <td>'.h( $comite['Dossier']['matricule'] ).'</td>
                                </tr>
                                <tr>
                                    <th>Date naissance</th>
                                    <td>'.h( date_short( $comite['Personne']['dtnai'] ) ).'</td>
                                </tr>
                                <tr>
                                    <th>NIR</th>
                                    <td>'.h( $comite['Personne']['nir'] ).'</td>
                                </tr>
                                <tr>
                                    <th>Code postal</th>
                                    <td>'.h( $comite['Adresse']['codepos'] ).'</td>
                                </tr>
                            </tbody>
                        </table>';
                        $title = $comite['Dossier']['numdemrsa'];


                    $apre_id = Set::extract( $comite, 'ApreComiteapre.apre_id');
                    $comiteapre_id = Set::extract( $comite, 'ApreComiteapre.comiteapre_id');
                    $aprecomiteapre_id = Set::extract( $comite, 'ApreComiteapre.id');
// debug($comite);
                    echo $html->tableCells(
                        array(
                            h( Set::classicExtract( $comite, 'Dossier.numdemrsa') ),
                            h( Set::classicExtract( $comite, 'Personne.qual').' '.Set::classicExtract( $comite, 'Personne.nom').' '.Set::classicExtract( $comite, 'Personne.prenom') ),
                            h( Set::classicExtract( $comite, 'Adresse.locaadr') ),
                            h( $locale->date( 'Date::short', Set::extract( $comite, 'Apre.datedemandeapre' ) ) ),

                            $xform->enum( 'ApreComiteapre.'.$index.'.decisioncomite', array( 'label' => false, 'type' => 'select', 'options' => $options['decisioncomite'], 'empty' => true ) ).
                            $xform->input( 'ApreComiteapre.'.$index.'.apre_id', array( 'label' => false, 'div' => false, 'value' => $apre_id, 'type' => 'hidden' ) ).
                            $xform->input( 'ApreComiteapre.'.$index.'.id', array( 'label' => false, 'div' => false, 'value' => $aprecomiteapre_id, 'type' => 'hidden' ) ).
                            $xform->input( 'ApreComiteapre.'.$index.'.comiteapre_id', array( 'label' => false, 'type' => 'hidden', 'value' => $comiteapre_id ) ).
                            $xform->input( 'Comiteapre.'.$index.'.id', array( 'label' => false, 'type' => 'hidden', 'value' => Set::extract( $comite, 'Comiteapre.id' ) ) ).
                            $xform->input( 'Apre.'.$index.'.id', array( 'label' => false, 'type' => 'hidden', 'value' => Set::extract( $comite, 'Apre.id' ) ) ),


                            h( $locale->date( 'Date::short', Set::extract( $comite, 'Comiteapre.datecomite' ) ) ),
                            h( Set::classicExtract( $comite, 'Apre.mtforfait') ),
//                             $xform->input( 'ApreComiteapre.'.$index.'.montantdemande', array( 'label' => false, 'type' => 'text' ) ),
                            $xform->input( 'ApreComiteapre.'.$index.'.montantattribue', array( 'label' => false, 'type' => 'text' ) ),
                            $xform->input( 'ApreComiteapre.'.$index.'.observationcomite', array( 'label' => false, 'type' => 'text', 'rows' => 3 ) ),
                            $html->viewLink(
                                'Voir le comite « '.Set::extract( $comite, 'Comiteapre.id' ).' »',
                                array( 'controller' => 'comitesapres', 'action' => 'view', Set::extract( $comite, 'Comiteapre.id' ) ),
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
        <p>Aucun Comité d'examen dans la cohorte.</p>
    <?php endif?>
<?php endif?>