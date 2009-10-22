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
<!--                <td class="noborder">
                    <h2>Informations dossier</h2>
                    <table>
                        <tbody>
                            <tr class="odd">
                                <th><?php __( 'Numéro de Dossier RSA' );?></th>
                                <td><?php echo Set::classicExtract( $details, 'Dossier.numdemrsa' ) ;?></td>
                            </tr>
                            <tr class="even">
                                <th><?php __( 'Numéro CAF' );?></th>
                                <td><?php echo Set::extract( $details, 'Dossier.matricule' ) ;?></td>
                            </tr>
                            <tr class="odd">
                                <th><?php __( 'Date de demande RSA' );?></th>
                                <td><?php echo date_short( Set::classicExtract( $details, 'Dossier.dtdemrsa' ) );?></td>
                            </tr>
                            <tr class="even">
                                <th><?php __( 'Etat du droit' );?></th>
                                <td><?php echo  Set::classicExtract( $etatdosrsa, Set::classicExtract( $details, 'Situationdossierrsa.etatdosrsa' ) );?></td>
                            </tr>
                            <tr class="odd">
                                <th><?php __( 'Service instructeur' );?></th>
                                <td><?php echo  Set::classicExtract( $typeserins, Set::classicExtract( $details, 'Suiviinstruction.typeserins' ) );?></td>
                            </tr>
                        </tbody>
                    </table>
                </td>-->
                <td class="noborder">
                <h2>Détails PDO</h2>
                    <?php if( $permissions->check( 'dossierspdo', 'add' ) ):?>
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
                                <tr class="odd">
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
                                </tr>
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
    <h1>Liste des traitements</h1>
<?php if( !empty( $notif ) ):?>
    <?php if( $permissions->check( 'dossierspdo', 'add' ) ):?>
        <ul class="actionMenu">
            <?php
                echo '<li>'.$html->addLink(
                    'Ajouter PDO',
                    array( 'controller' => 'dossierspdo', 'action' => 'add', $dossier_rsa_id )
                ).' </li>';
            ?>
        </ul>
    <?php endif;?>
    <table class="aere">
            <tbody>
                <?php foreach( $notif as $index => $i ):?>
                    <tr class="even">
                        <th>Type de notification</th>
                        <th>Date de notification</th>
                        <th class="action">Action</th>
                    </tr>
                    <tr>
                        <td><?php echo Set::classicExtract( $typenotifpdo, Set::classicExtract( $pdo, 'Propopdo.typenotifpdo_id' ) );?></td>
                        <td><?php echo date_short( Set::classicExtract( $pdo, 'Propopdo.datenotif' ) );?></td>
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




</div>
<div class="clearer"><hr /></div>