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

    <h1>Situation du droit</h1>
    <table>
        <tbody>
            <tr>
                <th>Situation du droit</th>
                <th>Date de refus (le cas échéant)</th>
                <th>Date de fin de droit (le cas échéant)</th>
                <th class="action">Action</th>
            </tr>
            <tr>
                <td><?php echo value( $etatdosrsa, Set::extract( 'Situationdossierrsa.etatdosrsa', $details ) );?></td>
                <td><?php echo Set::extract( 'Situationdossierrsa.dtrefursa', $details );?></td>
                <td><?php echo Set::extract( 'Situationdossierrsa.dtclorsa', $details );?></td>
                <td><?php echo $html->viewLink( 
                        'Voir le dossier',
                        array( 'controller' => 'dossiers', 'action' => 'view', $details['Dossier']['id'] )
                        );?>
                </td>
            </tr>
        </tbody>
    </table>

<hr />

<h1>Liste des PDO</h1>
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
        <?php  endif;?>
    <?php endif;?>

<?php if( !empty( $pdo ) ):?>
<div>
    <table class="aere">
        <thead>
            <tr>
                <th>Type de PDO</th>
                <th>Date soumission CAF</th>
                <th>Décision du Conseil Général</th>
                <th>Commentaires PDO</th>
                <th colspan="2" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
                <?php
                    echo $html->tableCells(
                        array(
                            h( value( $typepdo, Set::extract( 'Propopdo.typepdo', $pdo ) ) ),
                            h( date_short( Set::extract( 'Propopdo.datedecisionpdo', $pdo ) ) ),
                            h( value( $decisionpdo, Set::extract( 'Propopdo.decisionpdo', $pdo ) ) ),
                            h( Set::extract( 'Propopdo.commentairepdo', $pdo ) ),
                            $html->viewLink(
                                'Voir PDO',
                                array( 'controller' => 'dossierspdo', 'action' => 'view', $pdo['Propopdo']['id'] )
                            ),
                            $html->editLink(
                                'Modifier PDO',
                                array( 'controller' => 'dossierspdo', 'action' => 'edit', $dossier_rsa_id )
                            )
                        ),
                        array( 'class' => 'odd' ),
                        array( 'class' => 'even' )
                    );
                ?>
        </tbody>
    </table>
</div>
<?php endif;?>

</div>
<div class="clearer"><hr /></div>