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
                                    array( 'controller' => 'traitementspdos', 'action' => 'index', $dossierpdo['Propopdo']['id'])
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
</div>
<div class="clearer"><hr /></div>