<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  $this->pageTitle = 'Situation PDO';?>

<?php echo $this->element( 'dossier_menu', array( 'id' => $dossier_rsa_id ) );?>

<?php
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
?>

<div class="with_treemenu">

<h1>PDOs</h1>
    <!-- <table id="fichePDO" class=" noborder">
        <tbody>
            <tr>
                <td class="noborder"> -->
                    <h2>Détails PDO</h2>
                    <?php if( empty( $pdos ) ):?>
                        <p class="notice">Cette personne ne possède pas encore de PDO.</p>
                    <?php endif;?>

                    <?php if( $permissions->check( 'dossierspdo', 'add' ) ):?>
                        <ul class="actionMenu">
                            <?php
                                echo '<li>'.$html->addLink(
                                    'Ajouter un dossier PDO',
                                    array( 'controller' => 'dossierspdo', 'action' => 'add', $dossier_rsa_id )
                                ).' </li>';
                            ?>
                        </ul>
                    <?php endif;?>

                    <?php if( !empty( $pdos ) ):?>
                    <table class="tooltips">
                        <thead>
                            <tr>
                                <th>Type de PDO</th>
                                <th>Décision du Conseil Général</th>
                                <th>Motif de la décision</th>
                                <th>Date de la décision CG</th>
                                <th>Commentaire PDO</th>
                                <th colspan="5" class="action">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach( $pdos as $dossierpdo ):?>
                                <?php

                                    echo $html->tableCells(
                                        array(
                                            h( Set::enum( Set::classicExtract( $dossierpdo, 'Propopdo.typepdo_id' ), $typepdo ) ),
                                            h( Set::enum( Set::classicExtract( $dossierpdo, 'Propopdo.decisionpdo_id' ), $decisionpdo ) ),
                                            h( Set::enum( Set::classicExtract( $dossierpdo, 'Propopdo.motifpdo' ), $motifpdo ) ),
                                            h( date_short( Set::classicExtract( $dossierpdo, 'Propopdo.datedecisionpdo' ) ) ),
                                            h( Set::classicExtract( $dossierpdo, 'Propopdo.commentairepdo' ) ),
                                            $html->treatmentLink(
                                                'Traitements sur la PDO',
                                                array( 'controller' => 'propospdos_typesnotifspdos', 'action' => 'index', $dossierpdo['Propopdo']['id'])
                                            ),
                                            $html->viewLink(
                                                'Voir le dossier PDO',
                                                array( 'controller' => 'dossierspdo', 'action' => 'view', $dossierpdo['Propopdo']['id']),
                                                $permissions->check( 'dossierspdo', 'view' )
                                            ),
                                            $html->editLink(
                                                'Éditer le dossier PDO',
                                                array( 'controller' => 'dossierspdo', 'action' => 'edit', $dossierpdo['Propopdo']['id'] ),
                                                $permissions->check( 'dossierspdo', 'edit' )
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
                <!-- /td>
            </tr>
        </tbody>
    </table> -->


<!--
<br />

<?php /*if( !empty( $pdos ) ):?>
    <h1>Liste des traitements</h1>

    <?php if( empty( $notifs ) ):?>
        <p class="notice">Aucun traitement pour les PDOs.</p>
    <?php endif;?>
    <?php if( $permissions->check( 'dossierspdo', 'add' ) ):?>
        <ul class="actionMenu">
            <?php
                echo '<li>'.$html->addLink(
                    'Ajouter PDO',
                    array( 'controller' => 'propospdos_typesnotifspdos', 'action' => 'add', Set::classicExtract( $pdos, 'Propopdo.id' ) )
                ).' </li>';
            ?>
        </ul>
    <?php endif;?>

    <?php if( !empty( $notifs ) ):?>
        <table class="aere">
                <tbody>
                    <tr class="even">
                        <th>Type de notification</th>
                        <th>Date de notification</th>
                        <th class="action" colspan="2" >Action</th>
                    </tr>
                    <?php foreach( $notifs as $index => $notif ):?>
                        <tr>
                            <td><?php echo Set::classicExtract( $typenotifpdo, Set::classicExtract( $notif, 'PropopdoTypenotifpdo.typenotifpdo_id' ) );?></td>
                            <td><?php echo date_short( Set::classicExtract( $notif, 'PropopdoTypenotifpdo.datenotifpdo' ) );?></td>
                            <td><?php
                                    echo $html->editLink(
                                        'Modifier la notification',
                                        array( 'controller' => 'propospdos_typesnotifspdos', 'action' => 'edit', Set::classicExtract( $notif, 'PropopdoTypenotifpdo.id' ) )
                                    );
                                ?>
                            </td>
                            <td><?php
                                    echo $html->printLink(
                                        'Imprimer la notification',
                                        array( 'controller' => 'gedooos', 'action' => 'notifpdo', Set::classicExtract( $pdos, 'PropopdoTypenotifpdo.propopdo_id' ) )
                                    );
                                ?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table> 
    <?php endif;?>

<br />

    <h1>Liste des pièces</h1>
    <?php if( empty( $piecespdos ) ):?>
        <p class="notice">Aucune pièce pour les PDOs.</p>
    <?php endif;?>
    <?php if( $permissions->check( 'dossierspdo', 'add' ) ):?>
        <ul class="actionMenu">
            <?php
                echo '<li>'.$html->addPieceLink(
                    'Ajouter PDO',
                    array( 'controller' => 'piecespdos', 'action' => 'add', Set::classicExtract( $pdos, 'Propopdo.id' ) )
                ).' </li>';
            ?>
        </ul>
    <?php endif;?>
    <?php if( !empty( $piecespdos ) ):?>
    <table class="aere">
        <tbody>
            <tr class="even">
                <th>Type de le pièce</th>
                <th>Date d'ajout</th>
                <th colspan="2" class="action center">Action</th>
            </tr>
            <?php foreach( $piecespdos as $index => $piece ):?> 
            <tr>
                <td><?php echo Set::classicExtract( $piece, 'Piecepdo.libelle' );?></td>
                    <td><?php echo date_short( Set::classicExtract( $piece, 'Piecepdo.dateajout' ) );?></td>
                    <td><?php
                            echo $html->attachLink(
                                'Visualiser la pièce',
                                array( 'controller' => 'gedooos', 'action' => 'notifpdo', Set::classicExtract( $pdos, 'PropopdoTypenotifpdo.propopdo_id' ) )
                            );
                        ?>
                    </td>
                    <td><?php
                            echo $html->printLink(
                                'Imprimer la notification',
                                array( 'controller' => 'gedooos', 'action' => 'notifpdo', Set::classicExtract( $pdos, 'PropopdoTypenotifpdo.propopdo_id' ) )
                            );
                        ?>
                    </td>
                </tr>
            <?php endforeach;?>
        </tbody>
        </table> 
<?php endif;  ?>
<?php endif; */ ?> -->
</div>
<div class="clearer"><hr /></div>