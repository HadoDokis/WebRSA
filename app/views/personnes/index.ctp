<?php $this->pageTitle = 'Personnes du foyer';?>

<?php echo $this->element( 'dossier_menu', array( 'foyer_id' => $foyer_id ) );?>

<div class="with_treemenu">
    <h1>Personnes du foyer</h1>

    <?php if( $permissions->check( 'personnes', 'add' ) ) :?>
        <ul class="actionMenu">
            <?php
                echo '<li>'.$html->addLink(
                    'Ajouter une personne au foyer',
                    array( 'controller' => 'personnes', 'action' => 'add', $foyer_id )
                ).' </li>';
            ?>
        </ul>
    <?php endif;?>

    <?php if( !empty( $personnes ) ):?>
        <table class="tooltips_oupas">
            <thead>
                <tr>
                    <th>Rôle</th>
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
                                h( $rolepers[$personne['Prestation']['rolepers']] ),
                                h( ( Set::extract( $personne, 'Personne.qual' ) != '' ) ? $qual[$personne['Personne']['qual']] : null ),
                                h( $personne['Personne']['nom'] ),
                                h( $personne['Personne']['prenom'] ),
                                h( $locale->date( 'Date::short', $personne['Personne']['dtnai'] ) ),
                                $html->viewLink(
                                    'Voir la personne « '.$title.' »',
                                    array( 'controller' => 'personnes', 'action' => 'view', $personne['Personne']['id'] ),
                                    $permissions->check( 'personnes', 'view' )
                                ),
                                $html->editLink(
                                    'Éditer la personne « '.$title.' »',
                                    array( 'controller' => 'personnes', 'action' => 'edit', $personne['Personne']['id'] ),
                                    $permissions->check( 'personnes', 'edit' )
                                ),
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