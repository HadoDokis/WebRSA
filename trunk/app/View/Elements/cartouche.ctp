<?php if( $this->Session->check( 'Auth.User' ) ):?>
<?php $nomuser=$this->Session->read('Auth.User.username');?>
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
					<?php if( Configure::read( 'Apre.complementaire.query' ) === true ):?>
					<td>
						<?php echo $this->Xhtml->link("QUERY", sprintf( Configure::read( 'Apre.complementaire.queryUrl' ), $nomuser ),array("target" => "_blank")); ?>
					</td>
					<?php endif;?>
					<td>
						<?php 
							echo $this->Xhtml->link(
								$this->Session->read('Auth.User.nom' ),
								array(
									'controller'=>'users',
									'action'=>'changepass'
								),
								 array( 'enabled' => $this->Permissions->check( 'users', 'changepass' ) )
							);
						?>
					</td>
                    <td> <?php echo $this->Session->read( 'Auth.User.prenom' ) ;?> </td>
                    <td> <?php echo $this->Session->read( 'Auth.Group.name' ) ;?> </td>
                    <td> <?php echo $this->Session->read( 'Auth.Serviceinstructeur.lib_service' ) ;?> </td>
                    <!--<td>
                        <ul>
                            <?php /*foreach( $this->Session->read( 'Auth.Zonegeographique' ) as $zone ):?>
                                <li><?php echo $zone;?></li>
                            <?php endforeach;*/?>
                        </ul>
                    </td>-->
                </tr>
            </tbody>
        </table>
    </div>
<?php endif;?>
