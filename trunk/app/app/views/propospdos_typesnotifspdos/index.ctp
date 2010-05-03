<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  $this->pageTitle = 'Situation PDO';?>


<?php echo $this->element( 'dossier_menu', array( 'id' => $dossierId ) );?>

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

<?php echo $form->create( 'TraitementsPDOs', array( 'url'=> Router::url( null, true ) ) );?>
    <h1>Liste des traitements</h1>

    <?php if( empty( $notifs ) ):?>
        <p class="notice">Aucun traitement pour les PDOs.</p>
    <?php endif;?>

        <ul class="actionMenu">
            <?php
                echo '<li>'.$html->addLink(
                    'Ajouter PDO',
                    array( 'controller' => 'propospdos_typesnotifspdos', 'action' => 'add', Set::classicExtract( $notifs, 'PropopdoTypenotifpdo.propopdo_id' ) )
                ).' </li>';
            ?>
        </ul>


    <?php if( !empty( $notifs ) ):?>
        <table class="aere">
                <tbody>
                    <tr class="even">
                        <th>Type de notification</th>
                        <th>Date de notification</th>
                        <!-- <th class="action" colspan="2" >Action</th> -->
                        <th class="action">Action</th>
                    </tr>
                    <?php foreach( $notifs as $index => $notif ):?>
                        <tr>
                            <td>
                                <?php
                                    echo Set::enum( Set::classicExtract( $notif, 'PropopdoTypenotifpdo.typenotifpdo_id' ), $typenotifpdo );
                                ?>
                            </td>
                            <td>
                                <?php
                                    echo date_short( Set::classicExtract( $notif, 'PropopdoTypenotifpdo.datenotifpdo' ) );
                                ?>
                            </td>
                            <td><?php
                                    echo $html->editLink(
                                        'Modifier la notification',
                                        array( 'controller' => 'propospdos_typesnotifspdos', 'action' => 'edit', Set::classicExtract( $notif, 'PropopdoTypenotifpdo.id' ) )
                                    );
                                ?>
                            </td>
                            <!-- <td><?php
//                                     echo $html->printLink(
//                                         'Imprimer la notification',
//                                         array( 'controller' => 'gedooos', 'action' => 'notifpdo', Set::classicExtract( $pdos, 'PropopdoTypenotifpdo.propopdo_id' ) )
//                                     );
                                ?>
                            </td> -->
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table> 
    <?php endif;?>
    <div class="submit">
        <?php
            echo $form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
        ?>
    </div>
    <?php echo $form->end();?>
    </div>
<div class="clearer"><hr /></div>