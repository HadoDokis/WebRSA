<?php $this->pageTitle = 'Dossier de la personne';?>
<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout de ressources';
    }
    else {
        $this->pageTitle = 'Visualisation des ressources ';
        $foyer_id = $this->data['Personne']['foyer_id'];
    }
?>
<div class="with_treemenu">
    <h1><?php echo 'Visualisation des ressources  ';?></h1>



    <?php if( empty( $ressource ) ):?>
        <p class="notice">Cette personne ne possède pas encore de ressources.</p>

        <?php if( $permissions->check( 'ressources', 'add' ) ):?>
            <ul class="actionMenu">
                <?php
                    echo '<li>'.$html->addLink(
                        'Déclarer des ressources',
                        array( 'controller' => 'ressources', 'action' => 'add', $personne_id )
                    ).' </li>';
                ?>
            </ul>
        <?php endif;?>

    <?php else:?>
<!--        <ul class="actionMenu">
            <?php
                echo '<li>'.$html->editLink(
                    'Éditer des ressources',
                    array( 'controller' => 'ressources', 'action' => 'edit', $personne_id )
                ).' </li>';
            ?>
        </ul>-->

<div id="ficheDspp">
            <h2>Généralités concernant les ressources du trimestre</h2>

<table>
        <tbody>
            <tr class="odd">
                <th ><?php __( 'topressnul' );?></th>
                <td><?php echo ($ressource['Ressource']['topressnul']? 'Oui' : 'Non' );?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'mtpersressmenrsa' );?></th>
                <td><?php echo ( $ressource['Ressource']['mtpersressmenrsa']  );?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'ddress' );?></th>
                <td><?php echo date_short( $ressource['Ressource']['ddress'] );?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'dfress' );?></th>
                <td><?php echo date_short( $ressource['Ressource']['dfress'] );?></td>
            </tr>
        </tbody>
</table>
            <h2>Ressources mensuelles</h2>
                <h3>Généralités des ressources mensuelles</h3>
                <table>
                    <thead>
                        <tr>
                            <th><abbr title="<?php __( 'moisress' );?>">Mois</abbr></th>
                            <th><abbr title="<?php __( 'nbheumentra' );?>">Nb heures</abbr></th>
                            <th><abbr title="<?php __( 'mtabaneu' );?>">Montant A/N</abbr></th>
                            <th><abbr title="<?php __( 'natress' );?>">Nature</abbr></th>
                            <th><abbr title="<?php __( 'mtnatressmen' );?>">Montant ressource</abbr></th>
                            <th><abbr title="<?php __( 'abaneu' );?>">A/N</abbr></th>
                            <th><abbr title="<?php __( 'dfpercress' );?>">Date fin</abbr></th>
                            <th><abbr title="<?php __( 'topprevsubsress' );?>">Revenus de substitution?</abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach( $ressource['Ressourcemensuelle'] as $ressourcemensuelle ):?>
                            <?php foreach( $ressourcemensuelle['Detailressourcemensuelle'] as $detailressourcemensuelle):?>
                                <?php
                                $indexNatress = trim( $detailressourcemensuelle['natress'] );
                                //echo '<h4>'.strftime( '%B %Y', strtotime( $ressourcemensuelle['moisress'] ) ).'</h4>';
                                echo $html->tableCells(
                                    array(
                                        h( strftime( '%B %Y', strtotime( $ressourcemensuelle['moisress'] ) ) ),
                                        h( $ressourcemensuelle['nbheumentra'] ),
                                        h( $ressourcemensuelle['mtabaneu'] ),
                                        h( ( !empty( $indexNatress ) ) ? $natress[$indexNatress] : null ),
                                        h( $detailressourcemensuelle['mtnatressmen'] ),
                                        h( $detailressourcemensuelle['abaneu'] ),
                                        h( $detailressourcemensuelle['dfpercress'] ),
                                        h( $detailressourcemensuelle['topprevsubsress']? 'Oui' : 'Non' )

                                    ),
                                    array( 'class' => 'odd' ),
                                    array( 'class' => 'even' )
                                );

                                ?>
                    <?php endforeach;?>
                <?php endforeach;?>
                    </tbody>
                </table>

</div>
    <?php endif;?>
</div>
<div class="clearer"><hr /></div>
