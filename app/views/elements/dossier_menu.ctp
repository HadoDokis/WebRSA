<?php
    if( isset( $personne_id ) ) {
        $dossier = $this->requestAction( array( 'controller' => 'dossiers', 'action' => 'menu' ), array( 'personne_id' => $personne_id ) );
    }
    else if( isset( $foyer_id ) ) {
        $dossier = $this->requestAction( array( 'controller' => 'dossiers', 'action' => 'menu' ), array( 'foyer_id' => $foyer_id ) );
    }
    else if( isset( $id ) ) {
        $dossier = $this->requestAction( array( 'controller' => 'dossiers', 'action' => 'menu' ), array( 'id' => $id ) );
    }
?>

<div class="treemenu">
    <h2><?php echo $html->link( 'Dossier RSA '.$dossier['Dossier']['numdemrsa'], array( 'controller' => 'dossiers', 'action' => 'view', $dossier['Dossier']['id'] ) );?></h2>
    <ul>
        <li><?php echo $html->link( 'Composition du foyer', array( 'controller' => 'personnes', 'action' => 'index', $dossier['Foyer']['id'] ) );?>
            <ul>
                <?php foreach( $dossier['Foyer']['Personne'] as $personne ):?>
                    <li><?php
                            echo $html->link(
                                h( implode( ' ', array( $personne['qual'], $personne['nom'], $personne['prenom'] ) ) ),
                                array( 'controller' => 'personnes', 'action' => 'view', $personne['id'] )
                            );
                        ?>
                        <?php if( $personne['rolepers'] == 'DEM' || $personne['rolepers'] == 'CJT' ):?>
                        <ul>
                            <li>
                                <?php
                                    echo $html->link(
                                        h( 'Données socio-professionnelles' ),
                                        array( 'controller' => 'dspps', 'action' => 'view', $personne['id'] )
                                    );?>
                            </li>
                            <li>
                                <?php
                                    echo $html->link(
                                        'Contrats d\'insertion',
                                        array( 'controller' => 'contratsinsertion', 'action' => 'index', $personne['id'] )
                                    );
                                ?>
                            </li>
                            <li>
                                <?php
                                    echo $html->link(
                                        'Ressources',
                                        array( 'controller' => 'ressources', 'action' => 'index', $personne['id'] )
                                    );
                                ?>
                             </li>
                            <li>
                                <?php
                                    echo $html->link(
                                        h( 'Orientation' ),
                                        array( 'controller' => 'orientsstructs', 'action' => 'index', $personne['id'] )
                                    );?>
                            </li>
                        </ul>
                        <?php endif;?>
                    </li>
                <?php endforeach;?>
            </ul>
        </li>
        <li><span>Informations foyer</span>
            <ul>
                <li><?php echo $html->link( 'Adresses', array( 'controller' => 'adressesfoyers', 'action' => 'index', $dossier['Foyer']['id'] ) );?>
                    <?php if( !empty( $dossier['Foyer']['AdressesFoyer'] ) ):?>
                        <ul>
                            <?php foreach( $dossier['Foyer']['AdressesFoyer'] as $AdressesFoyer ):?>
                                <li><?php echo $html->link(
                                        h( implode( ' ', array( $AdressesFoyer['Adresse']['numvoie'], $AdressesFoyer['Adresse']['typevoie'], $AdressesFoyer['Adresse']['nomvoie'] ) ) ),
                                        array( 'controller' => 'adressesfoyers', 'action' => 'view', $AdressesFoyer['id'] ) );
                                    ;?></li>
                            <?php endforeach;?>
                        </ul>
                    <?php endif;?>
                </li>
                <li>
                    <?php
                        echo $html->link(
                            'Informations financières',
                            array( 'controller' => 'infosfinancieres', 'action' => 'index', $dossier['Foyer']['id'] )
                        );
                    ?>
                </li>
                <li>
                    <?php
                        echo $html->link(
                            'Situation dossier rsa',
                            array( 'controller' => 'situationsdossiersrsa', 'action' => 'index', $dossier['Foyer']['id'] )
                        );
                    ?>

                </li>
                 <li>
                     <?php
                        echo $html->link(
                            'Avis PCG droit rsa',
                            array( 'controller' => 'avispcgdroitrsa', 'action' => 'index', $dossier['Foyer']['id'] )
                        );
                    ?>
                </li>
    <!--            <li>
                    <?php
                        echo $html->link(
                            'Suivi d\'instruction',
                            array( 'controller' => 'suivisinstructions', 'action' => 'index', $dossier['Foyer']['id'] )
                        );
                    ?>
                </li>-->
            </ul>
        </li>
    <?php
        echo '<li>'.$html->link(
            'Données socio-professionnelles',
            array( 'controller' => 'dspfs', 'action' => 'view', $dossier['Foyer']['id'] )
        ).'</li>';

    ?>
   <?php
        echo '<li>'.$html->link(
            'Préconisation d\'orientation',
            array( 'controller' => 'dossierssimplifies', 'action' => 'edit', $dossier['Foyer']['id'] )
        ).'</li>';
    echo '</ul>';
    ?>
</div>

