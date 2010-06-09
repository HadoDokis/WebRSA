<?php  $this->pageTitle = 'Orientation de la personne';?>
<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id) );?>


<div class="with_treemenu">
    <h1>Orientation</h1>

    <?php if( empty( $orientstructs ) ):?>
        <p class="notice">Cette personne ne possède pas encore d'orientation.</p>
    <?php endif;?>

    <?php if( $permissions->check( 'orientsstructs', 'add' ) ):?>
        <ul class="actionMenu">
            <?php
                echo '<li>'.$html->addLink(
                    'Préconiser une orientation',
                    array( 'controller' => 'orientsstructs', 'action' => 'add', $personne_id )
                ).' </li>';
            ?>
        </ul>
    <?php endif;?>

    <?php if( !empty( $orientstructs ) ):?>
    <table class="tooltips">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Date de la demande</th>
                <th>Date d'orientation</th>
                <th>Préconisation d'orientation</th>
                <th>Structure référente</th>
                <th colspan="3" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php

                foreach( $orientstructs as $orientstruct ) {

                    $isOrient = false;
                    if( isset( $orientstruct['Orientstruct']['date_propo'] ) ){
                        $isOrient = true;
                    }

                    echo $html->tableCells(
                        array(
                            h( $orientstruct['Personne']['nom']),
                            h( $orientstruct['Personne']['prenom'] ),
                            h( date_short( $orientstruct['Orientstruct']['date_propo'] ) ),
                            h( date_short( $orientstruct['Orientstruct']['date_valid'] ) ),
                            h( Set::classicExtract( $orientstruct, 'Typeorient.lib_type_orient' ) ) ,
                            h( $orientstruct['Structurereferente']['lib_struc']  ),
                            $html->editLink(
                                'Editer l\'orientation',
                                array( 'controller' => 'orientsstructs', 'action' => 'edit', $orientstruct['Orientstruct']['id'] ),
                                $permissions->check( 'orientsstructs', 'edit' )
                            ),
                            $html->printLink(
                                'Imprimer la notification',
                                array( 'controller' => 'gedooos', 'action' => 'orientstruct', $orientstruct['Orientstruct']['id'] ),
                                $permissions->check( 'gedooos', 'orientstruct' ) && $orientstruct['Orientstruct']['imprime']
                            ),
                            $html->reorientLink( 'Réorientation', array( 'controller' => 'demandesreorient', 'action' => 'add', $orientstruct['Orientstruct']['id'] ), $isOrient )
                        ),
                        array( 'class' => 'odd' ),
                        array( 'class' => 'even' )
                    );
                }
            ?>
        </tbody>
    </table>
    <?php  endif;?>

<br />

<?php  if( !empty( $demandesreorients ) ):?>
    <h2>Réorientation</h2>
    <?php
        echo $default->index(
            $demandesreorients,
            array(
                'VxTypeorient.lib_type_orient',
                'VxStructurereferente.lib_struc',
                'VxReferent.nom_complet',
//                 'Motifdemreorient.name',
//                 'Demandereorient.urgent' => array( 'type' => 'boolean' ),
                'Demandereorient.created',
                'Seanceep.dateseance' => array( 'label' => 'Date de la séance' ),
//                 'NvOrientstruct.id',
            ),
            array(
                'actions' => array(
                    'Demandereorient.view',
                    'Demandereorient.edit',
                    'Demandereorient.delete',
                ),
//                 'add' => array( 'Demandereorient.add' => $this->params['pass'][0] ),
                'options' => $options,
                'domain' => 'demandereorient'
            )
        );
    ?>
<?php endif;?>
</div>
<div class="clearer"><hr /></div>
