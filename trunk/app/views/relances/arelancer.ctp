<h1><?php echo $this->pageTitle = $pageTitle;?></h1>

<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php if( isset( $orientsstructs ) ):?>
    <script type="text/javascript">
        document.observe("dom:loaded", function() {

            <?php foreach( $orientsstructs as $index => $orientstruct ):?>
                observeDisableFieldsOnRadioValue(
                    'relanceform',
                    'data[Orientstruct][<?php echo $index;?>][statutrelance]',
                    [
                        'Orientstruct<?php echo $index;?>DaterelanceDay',
                        'Orientstruct<?php echo $index;?>DaterelanceMonth',
                        'Orientstruct<?php echo $index;?>DaterelanceYear'
                    ],
                    'R',
                    true
                );
            <?php endforeach;?>
        });
    </script>
<?php endif;?>


<?php
    if( isset( $orientsstructs ) ) {
        $paginator->options( array( 'url' => $this->passedArgs ) );
        $params = array( 'format' => 'Résultats %start% - %end% sur un total de %count%.' );
        $pagination = $html->tag( 'p', $paginator->counter( $params ) );

//         $pages = $paginator->first( '<<' );
//         $pages .= $paginator->prev( '<' );
//         $pages .= $paginator->numbers();
//         $pages .= $paginator->next( '>' );
//         $pages .= $paginator->last( '>>' );
//
//         $pagination .= $html->tag( 'p', $pages );
    }
    else {
        $pagination = '';
    }
?>
<?php echo $form->create( 'Relance', array( 'url'=> Router::url( null, true ) ) );?>
    <fieldset>
        <?php echo $form->input( 'Relance.compare', array( 'label' => 'Opérateurs', 'type' => 'select', 'options' =>             $comparators = array( '<' => '<' ,'>' => '>','<=' => '<=', '>=' => '>=' ), 'empty' => true ) );?>
        <?php echo $form->input( 'Relance.nbjours', array( 'label' => 'Nombre de jours depuis l\'orientation', 'type' => 'text' ) );?>
    </fieldset>

    <div class="submit">
        <?php echo $form->button( 'Filtrer', array( 'type' => 'submit' ) );?>
        <?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>
<?php echo $form->end();?>

<!-- Résultats -->

<?php if( isset( $orientsstructs ) ):?>

    <h2 class="noprint">Résultats de la recherche</h2>

    <?php if( is_array( $orientsstructs ) && count( $orientsstructs ) > 0 ):?>
        <?php echo $form->create( 'RelanceOrient', array( 'url'=> Router::url( null, true ), 'id' => 'relanceform' ) );?>
        <?php
            echo '<div>';
            echo $form->input( 'Relance.compare', array( 'type' => 'hidden', 'id' => 'RelanceCompare2' ) );
            echo $form->input( 'Relance.nbjours', array( 'type' => 'hidden', 'id' => 'RelanceNbjours2' ) );
            echo '</div>';
        ?>
        <?php echo $pagination;?>
        <table class="tooltips" style="width: 100%;">
            <thead>
                <tr>
                    <th>N° Dossier</th>
                    <th>N° CAF</th>
                    <th>Nom / Prénom Allocataire</th>
                    <th>Date orientation</th>
                    <th style="width:2em">Nb jours depuis orientation</th>
                    <th style="width:20em">Date de relance</th>
                    <th>Statut relance</th>
                    <!--<th class="action">Action</th>-->
                    <th class="innerTableHeader">Informations complémentaires</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $orientsstructs as $index => $orientstruct ):?>
                    <?php
                        $innerTable = '<table id="innerTable'.$index.'" class="innerTable">
                            <tbody>
                                <tr>
                                    <th>N° de dossier</th>
                                    <td>'.h( $orientstruct['Dossier']['numdemrsa'] ).'</td>
                                </tr>
                                <tr>
                                    <th>Date naissance</th>
                                    <td>'.h( date_short( $orientstruct['Personne']['dtnai'] ) ).'</td>
                                </tr>
                                <tr>
                                    <th>Numéro CAF</th>
                                    <td>'.h( $orientstruct['Dossier']['matricule'] ).'</td>
                                </tr>
                                <tr>
                                    <th>NIR</th>
                                    <td>'.h( $orientstruct['Personne']['nir'] ).'</td>
                                </tr>
                                <tr>
                                    <th>Code postal</th>
                                    <td>'.h( $orientstruct['Adresse']['codepos'] ).'</td>
                                </tr>

                            </tbody>
                        </table>';
// debug( $orientstruct );
                        $statutRelance = Set::extract( $orientstruct, 'Orientstruct.'.$index.'.statutrelance' );
                        $orientstruct_id = Set::extract( $orientstruct, 'Orientstruct.id');

                        echo $html->tableCells(
                            array(
                                h( $orientstruct['Dossier']['numdemrsa'] ),
                                h( $orientstruct['Dossier']['matricule'] ),
                                h( $orientstruct['Personne']['nom'].' '.$orientstruct['Personne']['prenom'] ),
                                h( date_short( $orientstruct['Orientstruct']['date_valid'] ) ),
                                h( $orientstruct['Orientstruct']['nbjours'] ),
                                $form->input( 'Orientstruct.'.$index.'.daterelance', array( 'label' => false, 'div' => false, 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear' => date( 'Y' ) + 5, 'minYear' => date( 'Y' ) - 5 ) ).
                                $form->input( 'Orientstruct.'.$index.'.id', array( 'label' => false, 'div' => false, 'value' => $orientstruct_id, 'type' => 'hidden' ) ).
                                $form->input( 'Orientstruct.'.$index.'.personne_id', array( 'label' => false, 'div' => false, 'value' => $orientstruct['Orientstruct']['personne_id'], 'type' => 'hidden' ) ).
                                $form->input( 'Orientstruct.'.$index.'.typeorient_id', array( 'label' => false, 'div' => false, 'value' => $orientstruct['Orientstruct']['typeorient_id'], 'type' => 'hidden' ) ).
                                $form->input( 'Orientstruct.'.$index.'.structurereferente_id', array( 'label' => false, 'div' => false, 'value' => $orientstruct['Orientstruct']['structurereferente_id'], 'type' => 'hidden' ) ).
                                $form->input( 'Orientstruct.'.$index.'.dossier_id', array( 'label' => false, 'type' => 'hidden', 'value' => $orientstruct['Dossier']['id'] ) ),
                                $form->input( 'Orientstruct.'.$index.'.statutrelance', array( 'label' => false, 'div' => false, 'legend' => false, 'type' => 'radio', 'separator' => '<br />', 'options' => array( 'R' => 'Relancer', 'E' => 'En attente' ), 'value' => ( !empty( $statutRelance ) ? $statutRelance : 'E' ) ) ),
                                array( $innerTable, array( 'class' => 'innerTableCell' ) ),
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
        <p>Vos critères n'ont retourné aucun dossier.</p>
    <?php endif?>
<?php endif?>