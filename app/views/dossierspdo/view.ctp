<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  $this->pageTitle = 'Détails demande PDO';?>

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

    <h1>Détails demande PDO</h1>
    <ul class="actionMenu">
        <?php
            if( $permissions->check( 'dossierspdo', 'edit' ) ) {
                echo '<li>'.$html->editLink(
                    'Éditer PDO',
                    array( 'controller' => 'dossierspdo', 'action' => 'edit', Set::classicExtract( $pdo, 'Propopdo.id' ) )
                ).' </li>';
            }
        ?>
    </ul>

    <div id="fichePers">
        <table>
            <tbody>
                <tr class="odd">
                    <th><?php __( 'typepdo' );?></th>
                    <td><?php echo value( $typepdo, Set::classicExtract( $pdo, 'Propopdo.typepdo_id' ) ) ;?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'Décision du Conseil Général' );?></th>
                    <td><?php echo value( $decisionpdo, Set::classicExtract( $pdo, 'Propopdo.decisionpdo_id' ) ) ;?></td>
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
                    <td><?php echo Set::enum( Set::classicExtract( $pdo, 'Propopdo.typenotifpdo_id' ),  $typenotifpdo ) ;?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'Date de notification' );?></th>
                    <td><?php echo date_short( Set::classicExtract( $pdo, 'PropopdoTypenotifpdo.datenotif' ) );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'commentairepdo' );?></th>
                    <td><?php echo Set::classicExtract( $pdo, 'Propopdo.commentairepdo' );?></td>
                </tr>
            </tbody>
        </table>
    </div>

<hr />

    <div>
    <h1>Pièces jointes</h1>
        <table class="aere">
            <tbody>
                <?php /*foreach( $pdo as $index => $i ):*/?>
                    <tr class="even">
                        <th>Type de la pièce</th>
                        <th>Fournie</th>
                        <th>Date d'ajout</th>
                        <th class="action">Action</th>
                    </tr>
                    <tr>
                        <td><?php echo value( $pieecpres, Set::extract( 'Personne.pieecpres', $pdo ) );?></td>
                        <td><?php echo $html->boolean( !empty( $pdo['Personne']['pieecpres'] ) );?></td>
                        <td><?php echo '';?></td> <!-- FIXME: Voir pour la date à afficher pour les pièces jointes -->
                        <td><?php
                            if( !empty( $pdo['Personne']['pieecpres'] ) ){
                                echo $html->attachLink(
                                    'Voir PDO',
                                    array( 'controller' => 'dossierspdo', 'action' => 'view', $pdo['Propopdo']['id'] )
                                );
                            }
                            ?>
                        </td>
                    </tr>
                <?php /*endforeach;*/?>
            </tbody>
        </table>
    </div>
</div>
<div class="clearer"><hr /></div>