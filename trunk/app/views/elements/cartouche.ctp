<?php if( $session->check( 'Auth.User' ) ):?>
    <div id="pageCartouche">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Groupe</th>
                    <th>Service instructeur</th>
                    <!-- <th>Zones géographiques</th> -->
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td> <?php echo $session->read( 'Auth.User.nom' ) ;?> </td>
                    <td> <?php echo $session->read( 'Auth.User.prenom' ) ;?> </td>
                    <td> <?php echo $session->read( 'Auth.Group.name' ) ;?> </td>
                    <td> <?php echo $session->read( 'Auth.Serviceinstructeur.lib_service' ) ;?> </td>
                    <!--<td>
                        <ul>
                            <?php /*foreach( $session->read( 'Auth.Zonegeographique' ) as $zone ):?>
                                <li><?php echo $zone;?></li>
                            <?php endforeach;*/?>
                        </ul>
                    </td>-->
                </tr>
            </tbody>
        </table>
    </div>
<?php endif;?>