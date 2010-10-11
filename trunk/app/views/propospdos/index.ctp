<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  $this->pageTitle = 'PDO';?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

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

        <h2>Détails PDO</h2>
        <?php if( empty( $pdos ) ):?>
            <p class="notice">Cette personne ne possède pas encore de PDO.</p>
        <?php endif;?>

        <?php if( $permissions->check( 'propospdos', 'add' ) ):?>
            <ul class="actionMenu">
                <?php
                    echo '<li>'.$html->addLink(
                        'Ajouter un dossier PDO',
                        array( 'controller' => 'propospdos', 'action' => 'add', $personne_id )
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
                    <th>Etat du dossier PDO</th>
                    <th colspan="5" class="action">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $pdos as $pdo ):?>
                    <?php
// debug($pdo);
                        echo $html->tableCells(
                            array(
                                h( Set::enum( Set::classicExtract( $pdo, 'Propopdo.typepdo_id' ), $typepdo ) ),
                                h( Set::enum( Set::classicExtract( $pdo, 'Propopdo.decisionpdo_id' ), $decisionpdo ) ),
                                h( Set::enum( Set::classicExtract( $pdo, 'Propopdo.motifpdo' ), $motifpdo ) ),
                                h( date_short( Set::classicExtract( $pdo, 'Propopdo.datedecisionpdo' ) ) ),
                                h( Set::classicExtract( $pdo, 'Propopdo.commentairepdo' ) ),
                                h( Set::enum( Set::classicExtract( $pdo, 'Propopdo.etatdossierpdo' ), $options['etatdossierpdo'] ) ),
                                $html->treatmentLink(
                                    'Traitements sur la PDO',
                                    array( 'controller' => 'traitementspdos', 'action' => 'index', $pdo['Propopdo']['id'])
                                ),
                                $html->viewLink(
                                    'Voir le dossier PDO',
                                    array( 'controller' => 'propospdos', 'action' => 'view', $pdo['Propopdo']['id']),
                                    $permissions->check( 'propospdos', 'view' )
                                ),
                                $html->editLink(
                                    'Éditer le dossier PDO',
                                    array( 'controller' => 'propospdos', 'action' => 'edit', $pdo['Propopdo']['id'] ),
                                    $permissions->check( 'propospdos', 'edit' )
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
</div>
<div class="clearer"><hr /></div>