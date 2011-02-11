<?php  $this->pageTitle = 'Dossier de la personne';?>
<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout d\'un CER';
    }
    else {
        $this->pageTitle = 'CER ';
        $foyer_id = $this->data['Personne']['foyer_id'];
    }
?>
<div class="with_treemenu">
    <h1><?php  echo 'CER  ';?></h1>

        <?php if( $nbdossiersnonfinalisescovs > 0 ):?>
            <p class="notice">Cette personne possède un contrat d'engagement réciproque en attente de passage en COV.</p>
        <?php endif;?>
        
        <?php if( empty( $contratsinsertion ) ):?>
            <p class="notice">Cette personne ne possède pas encore de contrat d'engagement réciproque.</p>
        <?php endif;?>

        <?php if( $permissions->check( 'proposcontratsinsertioncovs58', 'add' ) && $nbdossiersnonfinalisescovs == 0 ):?>
            <ul class="actionMenu">
                <?php
                    echo '<li>'.$xhtml->addLink(
                        'Ajouter un CER',
                        array( 'controller' => 'proposcontratsinsertioncovs58', 'action' => 'add', $personne_id )
                    ).' </li>';
                ?>
            </ul>
        <?php endif;?>

    <!-- <?php /*if( !empty( $contratsinsertion ) ): */?> -->
    <?php if( !empty( $contratsinsertion ) ):?>
        <table class="tooltips">
            <thead>
                <tr>
                    <!-- <th>N° Contrat</th> -->
                    <th>Rang contrat</th>
                    <th>Date début</th>
                    <th>Date fin</th>
                    <th>Décision</th>
                    <th colspan="4" class="action">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $contratsinsertion as $contratinsertion ):?>
                    <?php
                        $isValid = Set::extract( $contratinsertion, 'Contratinsertion.decision_ci' );
                        $block = true;
                        if( $isValid == 'V'  ){
                            $block = false;
                        }
                        else{
                            $block;
                        }

                        echo $xhtml->tableCells(
                            array(
                                h( Set::enum( Set::classicExtract( $contratinsertion, 'Contratinsertion.num_contrat' ),  $options['num_contrat'] ) ),
                                h( date_short( isset( $contratinsertion['Contratinsertion']['dd_ci'] ) ) ? date_short( $contratinsertion['Contratinsertion']['dd_ci']  ) : null ),
                                h( date_short( isset( $contratinsertion['Contratinsertion']['df_ci'] ) ) ? date_short( $contratinsertion['Contratinsertion']['df_ci'] ) : null ),
                                h( Set::enum( Set::extract( $contratinsertion, 'Contratinsertion.decision_ci' ), $decision_ci ).' '.$locale->date( 'Date::short', Set::extract( $contratinsertion, 'Contratinsertion.datevalidation_ci' ) ) ),
//                                 $xhtml->validateLink(
//                                     'Valider le CER ',
//                                     array( 'controller' => 'contratsinsertion', 'action' => 'valider', $contratinsertion['Contratinsertion']['id'] )
//                                 ),
                                $xhtml->viewLink(
                                    'Voir le CER',
                                    array( 'controller' => 'contratsinsertion', 'action' => 'view', $contratinsertion['Contratinsertion']['id']),
                                    $permissions->check( 'contratsinsertion', 'view' )
                                ),
                                $xhtml->editLink(
                                    'Éditer le CER ',
                                    array( 'controller' => 'contratsinsertion', 'action' => 'edit', $contratinsertion['Contratinsertion']['id'] ),
//                                     array(
                                        $block,
                                        $permissions->check( 'contratsinsertion', 'edit' )
//                                     )
                                ),
                                $xhtml->printLink(
                                    'Imprimer le CER',
                                    array( 'controller' => 'gedooos', 'action' => 'contratinsertion', $contratinsertion['Contratinsertion']['id'] ),
                                    $permissions->check( 'gedooos', 'contratinsertion' )
                                ),
                                $xhtml->deleteLink(
                                    'Supprimer le CER ',
                                    array( 'controller' => 'contratsinsertion', 'action' => 'delete', $contratinsertion['Contratinsertion']['id'] ),
                                    $permissions->check( 'contratsinsertion', 'delete' )
                                )

                            ),
                            array( 'class' => 'odd' ),
                            array( 'class' => 'even' )
                        );
                    ?>
                <?php endforeach;?>
            </tbody>
        </table>
        <?php  endif;?>
    <?php /*endif;*/?>
</div>
<div class="clearer"><hr /></div>
