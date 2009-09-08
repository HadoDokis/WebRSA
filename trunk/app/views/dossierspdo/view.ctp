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
        <li>
            <?php
                if( $permissions->check( 'dossierspdo', 'edit' ) ) {
                    echo '<li>'.$html->editLink(
                        'Éditer PDO',
                        array( 'controller' => 'dossierspdo', 'action' => 'edit', $dossier_rsa_id )
                    ).' </li>';
                }

            ?>
        </li>
    </ul>

    <div id="fichePers">
        <table>
            <tbody>
                <tr class="even">
                    <th><?php __( 'numdero' );?></th>
                    <td><?php echo Set::extract( 'Propopdo.id', $pdo );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'typedero' );?></th>
                    <td><?php echo value( $typepdo, Set::extract( 'Propopdo.typepdo', $pdo ) ) ;?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'ddavisdero' );?></th>
                    <td><?php echo date_short( Set::extract( 'Propopdo.datedecisionpdo', $pdo ) ) ;?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'avisdero' );?></th>
                    <td><?php echo value( $decisionpdo, Set::extract( 'Propopdo.decisionpdo', $pdo ) ) ;?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'ressdero' );?></th>
                    <td><?php echo '' ;?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'motideccg' );?></th>
                    <td><?php echo '';?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'commentairepdo' );?></th>
                    <td><?php echo Set::extract( 'Propopdo.commentairepdo', $pdo ) ;?></td>
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
                        <td><?php echo $html->attachLink(
                                'Voir PDO',
                                array( 'controller' => 'dossierspdo', 'action' => 'view', $pdo['Propopdo']['id'] )
                            );?></td>
                    </tr>
                <?php /*endforeach;*/?>
            </tbody>
        </table>
    </div>
</div>
<div class="clearer"><hr /></div>