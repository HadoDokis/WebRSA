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
                            <?php
                                // FIXME: plusieurs niveaux
                                $affichage = array();
                                $affichage['dspps'] = $permissions->check( 'dspps', 'view' );
                                $affichage['contratsinsertion'] = $permissions->check( 'contratsinsertion', 'index' );
                                $affichage['ressources'] = $permissions->check( 'ressources', 'index' );
                                $affichage['orientsstructs'] = $permissions->check( 'orientsstructs', 'index' );

                                $affichageSubmenu = false;
                                foreach( $affichage as $affichageTmp ) {
                                    $affichageSubmenu = ( $affichageSubmenu || $affichageTmp );
                                }
                            ?>
                            <?php if( $affichageSubmenu ):?>
                                <ul>
                                    <?php if( $affichage['dspps'] ):?>
                                        <li>
                                            <?php
                                                echo $html->link(
                                                    h( 'Données socio-professionnelles' ),
                                                    array( 'controller' => 'dspps', 'action' => 'view', $personne['id'] )
                                                );?>
                                        </li>
                                    <?php endif;?>

                                    <?php if( $affichage['contratsinsertion'] ):?>
                                        <li>
                                            <?php
                                                echo $html->link(
                                                    'Contrats d\'insertion',
                                                    array( 'controller' => 'contratsinsertion', 'action' => 'index', $personne['id'] )
                                                );
                                            ?>
                                        </li>
                                    <?php endif;?>

                                    <?php if( $affichage['ressources'] ):?>
                                        <li>
                                            <?php
                                                echo $html->link(
                                                    'Ressources',
                                                    array( 'controller' => 'ressources', 'action' => 'index', $personne['id'] )
                                                );
                                            ?>
                                        </li>
                                    <?php endif;?>

                                    <?php if( $affichage['orientsstructs'] ):?>
                                        <li>
                                            <?php
                                                echo $html->link(
                                                    h( 'Orientation' ),
                                                    array( 'controller' => 'orientsstructs', 'action' => 'index', $personne['id'] )
                                                );
                                            ?>
                                        </li>
                                    <?php endif;?>
                                </ul>
                            <?php endif;?>
                        </li>
                    <?php endif;?>
                <?php endforeach;?>
            </ul>
        </li>
        <!-- TODO: permissions à partir d'ici et dans les fichiers concernés -->
        <li><span>Informations foyer</span>
            <ul>
                <?php if( $permissions->check( 'adressesfoyers', 'index' ) ):?>
                    <li><?php echo $html->link( 'Adresses', array( 'controller' => 'adressesfoyers', 'action' => 'index', $dossier['Foyer']['id'] ) );?>
                        <?php if( !empty( $dossier['Foyer']['AdressesFoyer'] ) ):?>
                            <?php if( $permissions->check( 'adressesfoyers', 'view' ) ):?>
                                <ul>
                                    <?php foreach( $dossier['Foyer']['AdressesFoyer'] as $AdressesFoyer ):?>
                                        <li><?php echo $html->link(
                                                h( implode( ' ', array( $AdressesFoyer['Adresse']['numvoie'], $AdressesFoyer['Adresse']['typevoie'], $AdressesFoyer['Adresse']['nomvoie'] ) ) ),
                                                array( 'controller' => 'adressesfoyers', 'action' => 'view', $AdressesFoyer['id'] ) );
                                            ;?></li>
                                    <?php endforeach;?>
                                </ul>
                            <?php endif;?>
                        <?php endif;?>
                    </li>
                <?php endif;?>

                <?php if( $permissions->check( 'infosfinancieres', 'index' ) ):?>
                    <li>
                        <?php
                            echo $html->link(
                                'Informations financières',
                                array( 'controller' => 'infosfinancieres', 'action' => 'index', $dossier['Foyer']['id'] )
                            );
                        ?>
                    </li>
                <?php endif;?>

                <?php if( $permissions->check( 'situationsdossiersrsa', 'index' ) ):?>
                    <li>
                        <?php
                            echo $html->link(
                                'Situation dossier rsa',
                                array( 'controller' => 'situationsdossiersrsa', 'action' => 'index', $dossier['Foyer']['id'] )
                            );
                        ?>
                    </li>
                <?php endif;?>

                <?php if( $permissions->check( 'avispcgdroitrsa', 'index' ) ):?>
                    <li>
                        <?php
                            echo $html->link(
                                'Avis PCG droit rsa',
                                array( 'controller' => 'avispcgdroitrsa', 'action' => 'index', $dossier['Foyer']['id'] )
                            );
                        ?>
                    </li>
                <?php endif;?>

                <!--<?php if( $permissions->check( 'suivisinstructions', 'index' ) ):?>
                    <li>
                        <?php
                            echo $html->link(
                                'Suivi d\'instruction',
                                array( 'controller' => 'suivisinstructions', 'action' => 'index', $dossier['Foyer']['id'] )
                            );
                        ?>
                    </li>
                <?php endif;?>-->

            </ul>
        </li>

        <?php if( $permissions->check( 'dspfs', 'edit' ) ):?>
            <?php
                echo '<li>'.$html->link(
                    'Données socio-professionnelles',
                    array( 'controller' => 'dspfs', 'action' => 'edit', $dossier['Foyer']['id'] )
                ).'</li>';
            ?>
        <?php endif;?>

        <?php if( $permissions->check( 'dossierssimplifies', 'edit' ) ):?>
            <?php
            echo '<li>'.$html->link(
                    'Préconisation d\'orientation',
                    array( 'controller' => 'dossierssimplifies', 'action' => 'edit', $dossier['Foyer']['id'] )
                ).'</li>';
            ?>
        <?php endif;?>
    </ul>
</div>

