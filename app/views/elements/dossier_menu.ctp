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
    <h2><?php echo $html->link( 'Dossier RSA '.$dossier['Dossier']['numdemrsa'], array( 'controller' => 'dossiers', 'action' => 'view', $dossier['Dossier']['id'] ) ).( $dossier['Dossier']['locked'] ? $html->image( 'icons/lock.png', array( 'alt' => '', 'title' => 'Dossier verrouillé' ) ) : null );?></h2>
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
                            <!-- Début "Partie du sous-menu concernant uniquement le demandeur et son conjoint" -->
                            <?php if( $personne['Prestation']['rolepers'] == 'DEM' || $personne['Prestation']['rolepers'] == 'CJT' ):?>
                                <ul>
                                <?php if( $permissions->check( 'dspps', 'view' ) ):?>
                                    <li>
                                        <?php
                                            echo $html->link(
                                                h( 'Données socio-professionnelles' ),
                                                array( 'controller' => 'dspps', 'action' => 'view', $personne['id'] )
                                            );?>
                                    </li>
                                <?php endif;?>

                                <?php if( $permissions->check( 'contratsinsertion', 'index' ) ):?>
                                    <li>
                                        <?php
                                            echo $html->link(
                                                'Contrats d\'insertion',
                                                array( 'controller' => 'contratsinsertion', 'action' => 'index', $personne['id'] )
                                            );
                                        ?>
                                    </li>
                                <?php endif;?>

                                <?php if( $permissions->check( 'ressources', 'index' ) ):?>
                                    <li>
                                        <?php
                                            echo $html->link(
                                                'Ressources',
                                                array( 'controller' => 'ressources', 'action' => 'index', $personne['id'] )
                                            );
                                        ?>
                                    </li>
                                <?php endif;?>

                                <?php if( $permissions->check( 'orientsstructs', 'index' ) ):?>
                                    <li>
                                        <?php
                                            echo $html->link(
                                                h( 'Orientation' ),
                                                array( 'controller' => 'orientsstructs', 'action' => 'index', $personne['id'] )
                                            );
                                        ?>
                                    </li>
                                <?php endif;?>
                                <?php if( $permissions->check( 'rendezvous', 'index' ) ):?>
                                    <li>
                                        <?php
                                            echo $html->link(
                                                h( 'Rendez-vous' ),
                                                array( 'controller' => 'rendezvous', 'action' => 'index', $personne['id'] )
                                            );
                                        ?>
                                    </li>
                                <?php endif;?>
                            </ul>
                            <?php endif;?>
                            <!-- Fin "Partie du sous-menu concernant uniquement le demandeur et son conjoint" -->

                            <!-- Début "Partie du sous-menu concernant toutes les personnes du foyer" -->
                            <!--<?php if( $permissions->check( 'dossierscaf', 'view' ) ):?>
                                <li>
                                    <?php
                                        echo $html->link(
                                            h( 'Dossier CAF' ),
                                            array( 'controller' => 'dossierscaf', 'action' => 'view', $personne['id'] )
                                        );
                                    ?>
                                </li>
                            <?php endif;?>-->
                            <!-- Fin "Partie du sous-menu concernant toutes les personnes du foyer" -->
                    </li>
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
                                                h( implode( ' ', array( $AdressesFoyer['Adresse']['numvoie'], isset( $typevoie[$AdressesFoyer['Adresse']['typevoie']] ) ? $typevoie[$AdressesFoyer['Adresse']['typevoie']] : null, $AdressesFoyer['Adresse']['nomvoie'] ) ) ),
                                                array( 'controller' => 'adressesfoyers', 'action' => 'view', $AdressesFoyer['id'] ) );
                                            ;?></li>
                                    <?php endforeach;?>
                                </ul>
                            <?php endif;?>
                        <?php endif;?>
                    </li>
                <?php endif;?>

                <?php if( $permissions->check( 'modescontact', 'index' ) ):?>
                    <li>
                        <?php
                            echo $html->link(
                                'Modes de contact',
                                array( 'controller' => 'modescontact', 'action' => 'index', $dossier['Foyer']['id'] )
                            );
                        ?>
                    </li>
                <?php endif;?>

                <?php if( $permissions->check( 'avispcgdroitrsa', 'index' ) ):?>
                    <li>
                        <?php
                            echo $html->link(
                                'Avis PCG droit rsa',
                                array( 'controller' => 'avispcgdroitrsa', 'action' => 'index', $dossier['Dossier']['id'] )
                            );
                        ?>
                    </li>
                <?php endif;?>

                <?php if( $permissions->check( 'infosfinancieres', 'index' ) ):?>
                    <li>
                        <?php
                            echo $html->link(
                                'Informations financières',
                                array( 'controller' => 'infosfinancieres', 'action' => 'index', $dossier['Dossier']['id'] )
                            );
                        ?>
                    </li>
                <?php endif;?>

                <?php if( $permissions->check( 'situationsdossiersrsa', 'index' ) ):?>
                    <li><span>Situation dossier rsa</span>
                        <ul>
                            <li>
                                <?php
                                    echo $html->link(
                                        'Historique du droit',
                                        array( 'controller' => 'situationsdossiersrsa', 'action' => 'index', $dossier['Dossier']['id'] )
                                    );
                                ?>
                            </li>
                            <li>
                                <?php
                                    echo $html->link(
                                        'Liste des indus',
                                        array( 'controller' => 'indus', 'action' => 'index', $dossier['Dossier']['id'] )
                                    );
                                ?>
                            </li>
                            <li>
                                <?php
                                    echo $html->link(
                                        'Consultation dossier PDO',
                                        array( 'controller' => 'dossierspdo', 'action' => 'index', $dossier['Dossier']['id'] )
                                    );
                                ?>
                            </li>
                        </ul>
                    </li>
                <?php endif;?>

               <?php if( $permissions->check( 'suivisinstruction', 'index' ) ):?>
                    <li>
                        <?php
                            echo $html->link(
                                'Suivi instruction du dossier',
                                array( 'controller' => 'suivisinstruction', 'action' => 'index', $dossier['Dossier']['id'] )
                            );
                        ?>
                    </li>
                <?php endif;?>
               <?php if( $permissions->check( 'detailsdroitsrsa', 'index' ) ):?>
                    <li>
                        <?php
                            echo $html->link(
                                'Détails du droit RSA',
                                array( 'controller' => 'detailsdroitsrsa', 'action' => 'index', $dossier['Dossier']['id'] )
                            );
                        ?>
                    </li>
                <?php endif;?>
            </ul>
        </li>

        <?php if( $permissions->check( 'dspfs', 'edit' ) ):?>
            <?php
                echo '<li>'.$html->link(
                    'Données socio-professionnelles',
                    array( 'controller' => 'dspfs', 'action' => 'view', $dossier['Foyer']['id'] )
                ).'</li>';
            ?>
        <?php endif;?>

        <?php if( $permissions->check( 'suivisinsertion', 'index' ) ):?>
            <?php
                echo '<li>'.$html->link(
                    'Suivis du parcours d\'insertion',
                    array( 'controller' => 'suivisinsertion', 'action' => 'index', $dossier['Foyer']['id'] )
                ).'</li>';
            ?>
        <?php endif;?>

        <?php if( $permissions->check( 'dossierssimplifies', 'edit' ) ):?>
            <li><span>Préconisation d'orientation</span>
                <ul>
                    <?php if( !empty( $dossier['Foyer']['Personne'] ) ):?>
                        <li>
                            <?php foreach( $dossier['Foyer']['Personne'] as $personnes ):?>
                                <?php if( $personnes['Prestation']['rolepers'] == 'DEM' || $personnes['Prestation']['rolepers'] == 'CJT' ):?>
                                    <?php
                                        echo $html->link(
                                            $personnes['qual'].' '.$personnes['nom'].' '.$personnes['prenom'],
                                            array( 'controller' => 'dossierssimplifies', 'action' => 'edit', $personnes['id'] )
                                        );
                                    ?>
                                <?php endif ?>
                            <?php endforeach?>
                        </li>
                    <?php endif?>
                </ul>
            </li>
        <?php endif;?>
    </ul>
</div>
