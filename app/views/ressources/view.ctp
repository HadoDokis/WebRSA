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
                    echo '<li>'.$xhtml->addLink(
                        'Déclarer des ressources',
                        array( 'controller' => 'ressources', 'action' => 'add', $personne_id )
                    ).' </li>';
                ?>
            </ul>
        <?php endif;?>

    <?php else:?>
<!--        <ul class="actionMenu">
            <?php
                echo '<li>'.$xhtml->editLink(
                    'Éditer des ressources',
                    array( 'controller' => 'ressources', 'action' => 'edit', $personne_id )
                ).' </li>';
            ?>
        </ul>-->

<div id="ficheRessource">
            <h2>Généralités concernant les ressources du trimestre</h2>

<table>
        <tbody>
            <tr class="odd">
                <th ><?php __( 'topressnotnul' );?></th>
                <td><?php echo ($ressource['Ressource']['topressnotnul']? 'Oui' : 'Non' );?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'mtpersressmenrsa' );?></th>
                <td><?php
					// FIXME: abaneu ?
					$mtnatressmens = Set::extract( $ressource, '/Ressourcemensuelle/Detailressourcemensuelle/mtnatressmen' );
					$nb = count( $mtnatressmens );
					$mtnatressmens = Set::filter( $mtnatressmens );
					if( !empty( $mtnatressmens ) ) {
						echo $locale->money( array_sum( $mtnatressmens ) / $nb  );
					}
					else {
						echo $locale->money( 0  );
					}
				?></td>
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
                        
                            <?php /*foreach( $ressourcemensuelle['Detailressourcemensuelle'] as $detailressourcemensuelle):*/?>
                                <?php

                                echo $xhtml->tableCells(
                                    array(
                                        h( strftime( '%B %Y', strtotime( $ressourcemensuelle['moisress'] ) ) ),
                                        h( $ressourcemensuelle['nbheumentra'] ),
                                        h( $ressourcemensuelle['mtabaneu'] ),
                                        '',
                                        '',
                                        '',
                                        '',
                                        ''
                                    ),
                                    array( 'class' => 'odd parent' ),
                                    array( 'class' => 'even parent' )
                                );

//                                 $count = min( 1, count( $ressourcemensuelle['Detailressourcemensuelle'] ) );
//                                 echo '<tr>
//                                         <td rowspan="'.$count.'">
//                                             '.h( strftime( '%B %Y', strtotime( $ressourcemensuelle['moisress'] ) ) ).'
//                                         </td>
//                                         <td rowspan="'.$count.'">
//                                             '.h( $ressourcemensuelle['nbheumentra'] ).'
//                                         </td>
//                                         <td rowspan="'.$count.'">
//                                             '.h( $ressourcemensuelle['mtabaneu'] ).'
//                                         </td>
//                                     </tr>';
                                

                                foreach( $ressourcemensuelle['Detailressourcemensuelle'] as $detailressourcemensuelle){
                                    $indexNatress = trim( $detailressourcemensuelle['natress'] );
                                    echo $xhtml->tableCells(
                                        array(
                                            '',
                                            '',
                                            '',
                                            h( ( !empty( $indexNatress ) ) ? $natress[$indexNatress] : null ),
                                            $locale->money( $detailressourcemensuelle['mtnatressmen'] ),
                                            h( $detailressourcemensuelle['abaneu'] ),
                                            h( $detailressourcemensuelle['dfpercress'] ),
                                            h( $detailressourcemensuelle['topprevsubsress']? 'Oui' : 'Non' )
                                        ),
                                        array( 'class' => 'odd' ),
                                        array( 'class' => 'even' )
                                    );
                                }

                                ?>
                    <?php /*endforeach;*/?>
                <?php endforeach;?>
                    </tbody>
                </table>

</div>
    <?php endif;?>
</div>
<div class="clearer"><hr /></div>