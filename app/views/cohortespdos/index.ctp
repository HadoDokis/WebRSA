<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php $this->pageTitle = 'Gestion des PDOs';?>

<h1>Gestion des PDOs</h1>

<?php
    if( is_array( $this->data ) ) {
        echo '<ul class="actionMenu"><li>'.$xhtml->link(
            $xhtml->image(
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
        $pagination = $xhtml->tag( 'p', $paginator->counter( $params ) );

        $pages = $paginator->first( '<<' );
        $pages .= $paginator->prev( '<' );
        $pages .= $paginator->numbers();
        $pages .= $paginator->next( '>' );
        $pages .= $paginator->last( '>>' );

        $pagination .= $xhtml->tag( 'p', $pages );
    }
    else {
        $pagination = '';
    }
?>

<?php echo $form->create( 'Cohortepdo', array( 'type' => 'post', 'action' => '/index/', 'id' => 'Search', 'class' => ( ( !empty( $this->data ) && empty( $this->validationErrors ) ) ? 'folded' : 'unfolded' ) ) );?>
    <fieldset>
        <legend>Recherche PDO</legend>
        <?php echo $form->input( 'Cohortepdo.recherche', array( 'label' => false, 'type' => 'hidden', 'value' => true ) );?>
         <?php echo $form->input( 'Cohortepdo.numcomptt', array( 'label' => 'Numéro de commune au sens INSEE', 'type' => 'select', 'options' => $mesCodesInsee, 'empty' => true ) );?>
        <?php echo $form->input( 'Cohortepdo.typepdo_id', array( 'label' => __d( 'propopdo', 'Propopdo.typepdo', true ), 'type' => 'select', 'options' => $typepdo, 'empty' => true ) );?>
        <?php echo $form->input( 'Cohortepdo.decisionpdo_id', array( 'label' => __d( 'propopdo', 'Propopdo.decisionpdo', true ), 'type' => 'select', 'options' => $decisionpdo, 'empty' => true ) );?>
        <?php echo $form->input( 'Cohortepdo.datedecisionpdo', array( 'label' => __d( 'propopdo', 'Propopdo.datedecisionpdo', true ), 'type' => 'date', 'dateFormat' => 'DMY', 'maxYear'=>date('Y'), 'minYear'=>date('Y')-80, 'empty' => true ) );?>
    </fieldset>
    <div class="submit noprint">
        <?php echo $form->button( 'Filtrer', array( 'type' => 'submit' ) );?>
        <?php echo $form->button( 'Réinitialiser', array( 'type' => 'reset' ) );?>
    </div>
<?php echo $form->end();?>

<!-- Résultats -->

<?php if( isset( $cohortepdo ) ):?>

    <h2 class="noprint">Résultats de la recherche</h2>

    <?php if( is_array( $cohortepdo ) && count( $cohortepdo ) > 0 ):?>
        <?php echo $form->create( 'GestionPDO', array( 'url'=> Router::url( null, true ) ) );?>
   <!-- <?php /*echo $pagination;*/?> -->
        <table id="searchResults" class="tooltips">
            <thead>
                <tr>
                    <th><?php echo $paginator->sort( 'N° PDO', 'Pdo.id' );?></th>
                    <th><?php echo $paginator->sort( 'Nom de l\'allocataire', 'Personne.nom'.' '.'Personne.prenom' );?></th>
                    <th><?php echo $paginator->sort( 'Suivi', 'Dossier.typeparte' );?></th>
                    <th><?php echo $paginator->sort( 'Situation des droits', 'Situationdossierrsa.etatdosrsa' );?></th>
                    <th><?php echo $paginator->sort( 'Type de PDO', 'Pdo.typepdo_id' );?></th>
                    <th><?php echo $paginator->sort( 'Date de décision PDO', 'Pdo.decisionpdo_id' );?></th>
                    <th><?php echo $paginator->sort( 'Décision PDO', 'Pdo.datedecisionpdo' );?></th>
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

                    $statut_avis = Set::extract( $pdo, 'Pdo.'.$index.'.avisdero' );
// debug( $statut_avis );
                    echo $xhtml->tableCells(
                        array(
                            h( $pdo['Pdo']['id'] ),
                            h( $pdo['Personne']['nom'].' '.$pdo['Personne']['prenom'] ),
                            h( $pdo['Dossier']['typeparte'] ),
                            h( value( $etatdosrsa, Set::extract( $pdo, 'Situationdossierrsa.etatdosrsa' ) ) ),
                            h( value( $typepdo, Set::extract( 'Pdo.typepdo_id', $pdo ) ) ),
                            h( date_short( Set::extract( 'Pdo.datedecisionpdo', $pdo ) ) ),
                            h( value( $decisionpdo, Set::extract( 'Pdo.decisionpdo_id', $pdo ) ) ),

                            $xhtml->viewLink(
                                'Voir le contrat « '.$title.' »',
                                array( 'controller' => 'propospdos', 'action' => 'index', $pdo['Propopdo']['personne_id'] )
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