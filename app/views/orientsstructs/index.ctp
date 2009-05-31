<?php  $this->pageTitle = 'Orientation de la personne';?>
<?php  echo $this->element( 'dossier_menu', array( 'id' => $personne_id) );?>


<div class="with_treemenu">
    <h1>Orientation</h1>

    <?php if( empty( $orientstruct ) ):?>
        <p class="notice">Cette personne ne possède pas encore d'orientation.</p>

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

    <?php else:?>

    <table class="tooltips">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Date de la demande</th>
                <th>Date d'orientation</th>
                <th>Préconisation d'orientation</th>
                <th>Structure référente</th>
                <th colspan="2" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
                echo $html->tableCells(
                    array(
                        h( $orientstruct['Personne']['nom']),
                        h( $orientstruct['Personne']['prenom'] ),
                        h( date_short( $orientstruct['Orientstruct']['date_propo'] ) ),
                        h( date_short( $orientstruct['Orientstruct']['date_valid'] ) ),
                        h( isset( $orientstruct['Structurereferente']['Typeorient']['lib_type_orient'] ) ? $orientstruct['Structurereferente']['Typeorient']['lib_type_orient'] : null ) ,
                        h( $orientstruct['Structurereferente']['lib_struc']  ),
                        $html->editLink(
                            'Editer l\'orientation',
                            array( 'controller' => 'orientsstructs', 'action' => 'edit', $orientstruct['Orientstruct']['id'] ),
                            $permissions->check( 'orientsstructs', 'edit' )
                        ),
                        $html->printLink(
                            'Imprimer la notification',
                            array( 'controller' => 'gedooos', 'action' => 'orientstruct', $orientstruct['Orientstruct']['id'] ),
                            $permissions->check( 'gedooos', 'orientstruct' )
                        ),
                    ),
                    array( 'class' => 'odd' ),
                    array( 'class' => 'even' )
                );
            ?>
        </tbody>
    </table>
    <?php  endif;?>


</div>
<div class="clearer"><hr /></div>
