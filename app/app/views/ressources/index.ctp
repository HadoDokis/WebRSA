<?php  $this->pageTitle = 'Ressources de la personne';?>
<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>


<div class="with_treemenu">
    <h1>Ressources</h1>

    <?php if( empty( $ressources ) ):?>
        <p class="notice">aucune information relative aux ressources de cette personne.</p>

        <?php if( $permissions->check( 'ressources', 'add' ) ) :?>
            <ul class="actionMenu">
                <?php
                    echo '<li>'.$html->addLink(
                        'Déclarer une ressource',
                        array( 'controller' => 'ressources', 'action' => 'add', $personne_id )
                    ).' </li>';
                ?>
            </ul>
        <?php endif;?>

    <?php  else:?>

        <?php if( $permissions->check( 'ressources', 'add' ) ) :?>
            <ul class="actionMenu">
                <?php
                    echo '<li>'.$html->addLink(
                        'Déclarer une ressource',
                        array( 'controller' => 'ressources', 'action' => 'add', $personne_id )
                    ).' </li>';
                ?>
            </ul>
        <?php endif;?>

    <table class="tooltips">
        <thead>
            <tr>
                <!--<th>N° ressource</th>-->
                <th>Percevez-vous des ressources ?</th>
                <th>Montant DTR RSA</th>
                <th>Date de début </th>
                <th>Date de fin</th>
                <th colspan="2" class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $ressources as $ressource ):?>
                <?php
                    $title = implode( ' ', array(
//                         $ressource['Ressource']['id'] ,
                        $ressource['Ressource']['topressnotnul'] ,
                        $ressource['Ressource']['mtpersressmenrsa'] ,
                        $ressource['Ressource']['ddress'] ,
                        $ressource['Ressource']['dfress'] ,
                     ));

                    echo $html->tableCells(
                        array(
//                             h( $ressource['Ressource']['id']),
                            h( $ressource['Ressource']['topressnotnul']  ? 'Oui' : 'Non'),
                            h( $ressource['Ressource']['mtpersressmenrsa'] ),
                            h( date_short( $ressource['Ressource']['ddress'] ) ),
                            h( date_short( $ressource['Ressource']['dfress'] ) ),
                            $html->viewLink(
                                'Voir la ressource',
                                array( 'controller' => 'ressources', 'action' => 'view', $ressource['Ressource']['id'] ),
                                $permissions->check( 'ressources', 'view' )
                            ),
                            $html->editLink(
                                'Éditer la ressource ',
                                array( 'controller' => 'ressources', 'action' => 'edit', $ressource['Ressource']['id'] ),
                                $permissions->check( 'ressources', 'edit' )
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
