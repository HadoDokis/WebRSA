<?php $this->pageTitle = 'Adresses du foyer';?>

<?php echo $this->element( 'dossier_menu', array( 'foyer_id' => $foyer_id ) );?>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php if( $permissions->check( 'adressesfoyers', 'add' ) ):?>
        <ul class="actionMenu">
            <?php
                echo '<li>'.$xhtml->addLink(
                    'Ajouter une adresse au foyer',
                    array( 'controller' => 'adressesfoyers', 'action' => 'add', $foyer_id )
                ).' </li>';
            ?>
        </ul>
    <?php endif;?>

    <?php if( !empty( $adresses ) ):?>
        <table class="tooltips">
            <thead>
                <tr>
                    <th><?php __d( 'adressefoyer', 'Adressefoyer.rgadr' );?></th>
                    <th>Adresse</th>
                    <th>Localité</th>
                    <th colspan="2" class="action">Actions</th>
                    <th class="innerTableHeader">Informations complémentaires</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $adresses as $index => $adresse ):?>
                    <?php
                        $title = implode( ' ', array( $adresse['Adresse']['numvoie'], $adresse['Adresse']['typevoie'], $adresse['Adresse']['nomvoie'] ) );

                        $innerTable = '<table id="innerTable'.$index.'" class="innerTable">
                            <tbody>
                                <tr>
                                    <th>'.__d( 'adressefoyer', 'Adressefoyer.dtemm', true ).'</th>
                                    <td>'.h( date_short( $adresse['Adressefoyer']['dtemm'] ) ).'</td>
                                </tr>
                                <tr>
                                    <th>'.__d( 'adressefoyer', 'Adressefoyer.typeadr', true ).'</th>
                                    <td>'.h( $typeadr[$adresse['Adressefoyer']['typeadr']] ).'</td>
                                </tr>
                                <tr>
                                    <th>Pays</th>
                                    <td>'.h( $pays[$adresse['Adresse']['pays']] ).'</td>
                                </tr>
                            </tbody>
                        </table>';
                        echo $xhtml->tableCells(
                            array(
                                h( !empty( $adresse['Adressefoyer']['rgadr'] ) ? $rgadr[$adresse['Adressefoyer']['rgadr']] : null ),
                                h( implode( ' ', array( $adresse['Adresse']['numvoie'], isset( $typevoie[$adresse['Adresse']['typevoie']] ) ? $typevoie[$adresse['Adresse']['typevoie']] : null, $adresse['Adresse']['nomvoie'] ) ) ),
                                h( implode( ' ', array( $adresse['Adresse']['codepos'], $adresse['Adresse']['locaadr'] ) ) ),
                                $xhtml->viewLink(
                                    'Voir l\'adresse « '.$title.' »',
                                    array( 'controller' => 'adressesfoyers', 'action' => 'view', $adresse['Adressefoyer']['id'] ),
                                    $permissions->check( 'adressesfoyers', 'view' )
                                ),
                                $xhtml->editLink(
                                    'Éditer l\'adresse « '.$title.' »',
                                    array( 'controller' => 'adressesfoyers', 'action' => 'edit', $adresse['Adressefoyer']['id'] ),
                                    $permissions->check( 'adressesfoyers', 'edit' )
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
        <p class="notice">Ce foyer ne possède actuellement aucune adresse.</p>
    <?php endif;?>
</div>

<div class="clearer"><hr /></div>