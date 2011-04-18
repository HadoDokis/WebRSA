<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  $this->pageTitle = 'Détails demande PDO';?>

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

    <h1>Détails demande PDO</h1>
    <ul class="actionMenu">
        <?php
            if( $permissions->check( 'propospdos', 'edit' ) ) {
                echo '<li>'.$xhtml->editLink(
                    'Éditer PDO',
                    array( 'controller' => 'propospdos', 'action' => 'edit', Set::classicExtract( $pdo, 'Propopdo.id' ) )
                ).' </li>';
            }
        ?>
    </ul>


<?php
        echo $form->create( 'Propopdo', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
// debug($pdo);
        $etatdossierpdo = Set::enum( $pdo['Propopdo']['etatdossierpdo'], $options['etatdossierpdo'] );
        $complet = Set::enum( $pdo['Propopdo']['iscomplet'], $options['iscomplet'] );
        $categoriegeneral = Set::enum( $pdo['Propopdo']['categoriegeneral'], $categoriegeneral );
        $categoriedetail = Set::enum( $pdo['Propopdo']['categoriedetail'], $categoriedetail );
        $service = Set::enum( $pdo['Propopdo']['serviceinstructeur_id'], $serviceinstructeur );
        $user = Set::enum( $pdo['Propopdo']['user_id'], $gestionnaire );
        $origpdo = Set::enum( $pdo['Propopdo']['originepdo_id'], $originepdo );

//         $statutlist = Set::enum( $pdo['Propopdo']['categoriegeneral'], $options['statutlist'] );
//         $situationlist = Set::enum( $pdo['Propopdo']['categoriedetail'], $options['statutlist'] );
        echo $default2->view(
            $pdo,
            array(
                'Propopdo.etatdossierpdo' => array( 'type' => 'text', 'value' => $etatdossierpdo ),
                'Typepdo.libelle',
                'Propopdo.datereceptionpdo',
                'Propopdo.originepdo_id' => array( 'type' => 'text', 'value' => $origpdo ),
                'Propopdo.orgpayeur',
                'Propopdo.serviceinstructeur_id'=> array( 'type' => 'text', 'value' => $service ),
                'Propopdo.user_id' => array( 'type' => 'text', 'value' => $user ),
                'Situationpdo.libelle',
                'Statutpdo.libelle',
                'Propopdo.categoriegeneral' => array( 'type' => 'text', 'value' => $categoriegeneral ),
                'Propopdo.categoriedetail' => array( 'type' => 'text', 'value' => $categoriedetail ),
                'Propopdo.iscomplet' => array( 'type' => 'text', 'value' => $complet ),
            ),
            array(
                'class' => 'aere'
            )
        );
?>


    <?php
        echo "<h2>Pièces jointes</h2>";
        if( !empty( $pdo['Fichiermodule'] ) ){
            $fichiersLies = Set::extract( $pdo, 'Propopdo/Fichiermodule' );
            echo '<table class="aere"><tbody>';
                echo '<tr><th>Nom de la pièce jointe</th><th>Date de l\'ajout</th><th>Action</th></tr>';
                if( isset( $fichiersLies ) ){
                    foreach( $fichiersLies as $i => $fichiers ){
                        echo '<tr><td>'.$fichiers['Fichiermodule']['name'].'</td>';
                        echo '<td>'.date_short( $fichiers['Fichiermodule']['created'] ).'</td>';
                        echo '<td>'.$xhtml->link( 'Télécharger', array( 'action' => 'download', $fichiers['Fichiermodule']['id']    ) ).'</td></tr>';
                    }
                }
            echo '</tbody></table>';
        }
        else{
            echo '<p class="notice aere">Aucun élément.</p>';
        }
    ?>
<hr />
   <div>
        <?php
            echo $xhtml->tag( 'h2', 'Traitements' );

            echo $default2->index(
                $traitements,
                array(
                    'Descriptionpdo.name',
                    'Traitementtypepdo.name',
                    'Traitementpdo.datereception' => array( 'type' => 'date' ),
                    'Traitementpdo.datedepart' => array( 'type' => 'date' )
                ),
                array(
                    'actions' => array(
                        'Traitementspdos::view'
                    ),
                    'options' => $options,
                    'id' => 'traitementpdoview'
                )
            );
        ?>
    </div>

<hr />

    <div>
        <?php
    //         $typeproposition = Set::enum( $propositions['Decisionpropopdo']['decisionpdo_id'], $decisionpdo );

            echo $xhtml->tag( 'h2', 'Propositions de décisions' );

            echo $default2->index(
                $propositions,
                array(
                    'Decisionpdo.libelle',
                    'Decisionpropopdo.datedecisionpdo',
                    'Decisionpropopdo.avistechnique' => array( 'type' => 'boolean' ),
                    'Decisionpropopdo.dateavistechnique' => array( 'type' => 'date' ),
                    'Decisionpropopdo.commentaireavistechnique',
                    'Decisionpropopdo.validationdecision' => array( 'type' => 'boolean' ),
                    'Decisionpropopdo.datevalidationdecision' => array( 'type' => 'date' ),
                    'Decisionpropopdo.commentairedecision'
                ),
                array(
                    'actions' => array(
                        'Decisionspropospdos::edit'
                    ),
                    'options' =>  $options,
                    'id' => 'propositionpdoview'
                )
            );
        ?>
    </div>
</div>
    <div class="submit">
        <?php

            echo $form->submit( 'Retour', array( 'name' => 'Cancel', 'div' => false ) );
        ?>
    </div>
    <?php echo $form->end();?>
<div class="clearer"><hr /></div>