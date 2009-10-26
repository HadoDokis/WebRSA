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
    <table id="fichePDO" class=" noborder">
        <tbody>
            <tr>
                <td class="noborder">
                <?php if( $permissions->check( 'dossierspdo', 'add' ) ):?>
                    <h2>Détails PDO</h2>
                        <?php if( empty( $pdo ) ):?>
                            <p class="notice">Ce dossier ne possède pas encore de PDO.</p>
                            <ul class="actionMenu">
                                <?php
                                    echo '<li>'.$html->addLink(
                                        'Ajouter PDO',
                                        array( 'controller' => 'dossierspdo', 'action' => 'add', $dossier_rsa_id )
                                    ).' </li>';
                                ?>
                            </ul>
                        <?php else:?>
                            <ul class="actionMenu">
                                <?php
                                    echo '<li>'.$html->editLink(
                                        'Modifier PDO',
                                        array( 'controller' => 'dossierspdo', 'action' => 'edit', Set::classicExtract( $pdo, 'Propopdo.id' ) )
                                    ).' </li>';
                                ?>
                            </ul>
                        <?php  endif;?>
                <?php endif;?>

                    <?php if( !empty( $pdo ) ):?>
                        <table>
                            <tbody>
                                <tr class="odd">
                                    <th><?php __( 'typepdo' );?></th>
                                    <td><?php echo value( $typepdo, Set::extract( $pdo, 'Propopdo.typepdo_id' ) ) ;?></td>
                                </tr>
                                <tr class="even">
                                    <th><?php __( 'Décision du Conseil Général' );?></th>
                                    <td><?php echo value( $decisionpdo, Set::extract( $pdo, 'Propopdo.decisionpdo_id' ) ) ;?></td>
                                </tr>
                                <tr class="odd">
                                    <th><?php __( 'Motif de la décision' );?></th>
                                    <td><?php echo Set::classicExtract( $motifpdo, Set::classicExtract( $pdo, 'Propopdo.motifpdo' ) );?></td>
                                </tr>
                                <tr class="even">
                                    <th><?php __( 'Date de la décision CG' );?></th>
                                    <td><?php echo date_short( Set::classicExtract( $pdo, 'Propopdo.datedecisionpdo' ) );?></td>
                                </tr>
                                <!-- <tr class="odd">
                                    <th><?php __( 'Type de notification' );?></th>
                                    <td><?php echo Set::classicExtract( $typenotifpdo, Set::classicExtract( $pdo, 'Propopdo.typenotifpdo_id' ) ) ;?></td>
                                </tr>
                                <tr class="even">
                                    <th><?php __( 'Pièces jointes' );?></th>
                                    <td><?php echo Set::classicExtract( $pdo, 'Piecesjointes.propopdo_id' );?></td>
                                </tr>
                                <tr class="odd">
                                    <th><?php __( 'Date de notification' );?></th>
                                     <td><?php echo date_short( Set::classicExtract( $pdo, 'PropopdoTypenotifpdo.datenotif' ) );?></td>
                                </tr> -->
                                <tr class="even">
                                    <th><?php __( 'commentairepdo' );?></th>
                                    <td><?php echo Set::classicExtract( $pdo, 'Propopdo.commentairepdo' );?></td>
                                </tr>
                            </tbody>
                        </table>
                    <?php endif;?>
                </td>
            </tr>
        </tbody>
    </table>

<br />
<!--  AJOUT DE TRAITEMENTS POUR LA PDO   -->
<?php if( !empty( $pdo ) ):?>
    <h1>Liste des traitements</h1>

    <?php if( $permissions->check( 'dossierspdo', 'add' ) ):?>
        <ul class="actionMenu">
            <?php
                echo '<li>'.$html->addLink(
                    'Ajouter PDO',
                    array( 'controller' => 'propospdos_typesnotifspdos', 'action' => 'add', Set::classicExtract( $pdo, 'Propopdo.id' ) )
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
                                        array( 'controller' => 'gedooos', 'action' => 'notifpdo', $pdo['Propopdo']['id'] )
                                    );
                                ?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table> 
    <?php endif;?>

<br />
<!--  AJOUT DE PIECES JOINTES POUR LA PDO   -->
    <h1>Liste des pièces</h1>

    <?php if( $permissions->check( 'dossierspdo', 'add' ) ):?>
        <ul class="actionMenu">
            <?php
                echo '<li>'.$html->addPieceLink(
                    'Ajouter PDO',
                    array( 'controller' => 'piecespdos', 'action' => 'add', Set::classicExtract( $pdo, 'Propopdo.id' ) )
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
                                array( 'controller' => 'gedooos', 'action' => 'notifpdo', $pdo['Propopdo']['id'] )
                            );
                        ?>
                    </td>
                    <td><?php
                            echo $html->printLink(
                                'Imprimer la notification',
                                array( 'controller' => 'gedooos', 'action' => 'notifpdo', $pdo['Propopdo']['id'] )
                            );
                        ?>
                    </td>
                </tr>
            <?php endforeach;?>
        </tbody>
        </table> 
<?php endif;?>
<?php endif;?>
</div>
<div class="clearer"><hr /></div>