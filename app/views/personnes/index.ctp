<?php $this->pageTitle = 'Personnes du foyer';?>

<?php echo $this->element( 'dossier_menu', array( 'foyer_id' => $foyer_id ) );?>

<div class="with_treemenu">
    <h1>Personnes du foyer</h1>

    <ul class="actionMenu">
        <?php
            echo '<li>'.$html->addLink(
                'Ajouter une personne au foyer',
                array( 'controller' => 'personnes', 'action' => 'add', $foyer_id )
            ).' </li>';
        ?>
    </ul>

<?php if( !empty( $personnes ) ):?>
    <table class="tooltips_oupas">
        <thead>
            <tr>
                <th>Qualité</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Date de naissance</th>
                <th colspan="2" class="action">Actions</th>
                <th class="innerTableHeader">Informations complémentaires</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $personnes as $index => $personne ):?>
                <?php
                    $title = implode( ' ', array( $personne['Personne']['qual'], $personne['Personne']['nom'], $personne['Personne']['prenom'] ) );

                    $innerTable = '<table id="innerTable'.$index.'" class="innerTable">
                        <tbody>
                            <tr>
                                <th>Prénom 2</th>
                                <td>'.h( $personne['Personne']['prenom2'] ).'</td>
                            </tr>
                            <tr>
                                <th>Prénom 3</th>
                                <td>'.h( $personne['Personne']['prenom3'] ).'</td>
                            </tr>
                        </tbody>
                    </table>';

                    echo $html->tableCells(
                        array(
                            h( $qual[$personne['Personne']['qual']] ),
                            h( $personne['Personne']['nom'] ),
                            h( $personne['Personne']['prenom'] ),
                            h( strftime( '%d/%m/%Y', strtotime( $personne['Personne']['dtnai'] ) ) ),
                            $html->viewLink(
                                'Voir la personne « '.$title.' »',
                                array( 'controller' => 'personnes', 'action' => 'view', $personne['Personne']['id'] )
                            ),
                            $html->editLink(
                                'Éditer la personne « '.$title.' »',
                                array( 'controller' => 'personnes', 'action' => 'edit', $personne['Personne']['id'] )
                            ),
//                             $html->deleteLink(
//                                 'Supprimer la personne « '.$title.' »',
//                                 array( 'controller' => 'personnes', 'action' => 'delete', $personne['Personne']['id'] )
//                             )
                            array( $innerTable, array( 'class' => 'innerTableCell' ) ),
                        ),
                        array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
                        array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
                    );
                ?>
            <?php endforeach;?>
        </tbody>
    </table>
    <?php else:?>
        <p class="notice">Ce foyer ne possède actuellement aucune personne.</p>
    <?php endif;?>
</div>

<div class="clearer"><hr /></div>