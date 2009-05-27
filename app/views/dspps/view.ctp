<?php $this->pageTitle = 'Dossier de la personne';?>
<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>

<?php
    if( $this->action == 'add' ) {
        $this->pageTitle = 'Ajout d\'une personne';
    }
    else {
        $this->pageTitle = 'Visualisation de la personne ';
        $foyer_id = $this->data['Personne']['foyer_id'];
    }
?>
<div class="with_treemenu">
    <h1><?php echo 'Visualisation des données  ';?></h1>



    <?php if( empty( $dspp ) ):?>
        <p class="notice">Cette personne ne possède pas encore de questionnaire socio-professionnel.</p>

        <ul class="actionMenu">
            <?php
                echo '<li>'.$html->addLink(
                    'Ajouter un dossier',
                    array( 'controller' => 'dspps', 'action' => 'add', $personne_id )
                ).' </li>';
            ?>
        </ul>
    <?php else:?>
        <ul class="actionMenu">
            <?php
                echo '<li>'.$html->editLink(
                    'Éditer un dossier',
                    array( 'controller' => 'dspps', 'action' => 'edit', $personne_id )
                ).' </li>';
            ?>
        </ul>

<div id="ficheDspp">
            <h2>Généralités DSPP</h2>

<table>
        <tbody>
            <tr class="odd">
                <th ><?php __( 'drorsarmiant' );?></th>
                <td><?php echo ($dspp['Dspp']['drorsarmiant']? 'Oui' : 'Non' );?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'drorsarmianta2' );?></th>
                <td><?php echo ( $dspp['Dspp']['drorsarmianta2'] ? 'Oui' : 'Non' );?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'couvsoc' );?></th>
                <td><?php echo ( $dspp['Dspp']['couvsoc']? 'Oui' : 'Non' );?></td>
            </tr>
        </tbody>
</table>
            <h2>Situation sociale</h2>
<table>
        <tbody>

            <tr class="even">
                <th><?php __( 'elopersdifdisp' );?></th>
                <td><?php echo ( $dspp['Dspp']['elopersdifdisp'] ? 'Oui' : 'Non' );?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'obstemploidifdisp' );?></th>
                <td><?php echo ( $dspp['Dspp']['obstemploidifdisp'] ? 'Oui' : 'Non' );?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'soutdemarsoc' );?></th>
                <td><?php echo ( $dspp['Dspp']['soutdemarsoc'] ? 'Oui' : 'Non' );?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'libcooraccosocindi' );?></th>
                <td><?php echo $dspp['Dspp']['libcooraccosocindi'];?></td>
            </tr>
        </tbody>
</table>
            <h2>Difficultés sociales</h2>
<table>
        <tbody>
            <tr class="even">
                <th><?php __( 'difsoc');?></th>
                <td>
                    <?php if( !empty( $dspp['Difsoc'] ) ):?>
                        <ul>
                            <?php foreach( $dspp['Difsoc'] as $difsoc ):?>
                                <li><?php echo h( $difsoc['name'] );?></li>
                            <?php endforeach;?>
                        </ul>
                    <?php endif;?>
                </td>
            </tr>
            <tr class="odd">
                <th><?php __( 'libautrdifsoc' );?></th>
                <td><?php echo $dspp['Dspp']['libautrdifsoc'];?></td>
            </tr>
        </tbody>
</table>
            <h2>Accompagnement individuel</h2>
<table>
        <tbody>
            <tr class="even">
                <th><?php __( 'nataccosocindi' );?></th>
                    <td>
                        <?php if( !empty( $dspp['Nataccosocindi'] ) ):?>
                            <ul>
                                <?php foreach( $dspp['Nataccosocindi'] as $nataccosocindi ):?>
                                    <li><?php echo h( $nataccosocindi['name'] );?></li>
                                <?php endforeach;?>
                            </ul>
                        <?php endif;?>
                    </td>
            </tr>
            <tr class="odd">
                <th><?php __( 'libautraccosocindi' );?></th>
                <td><?php echo $dspp['Dspp']['libautraccosocindi'];?></td>
            </tr>
        </tbody>
</table>
            <h2>Difficultés de disponibilité</h2>
<table>
        <tbody>
            <tr class="even">
                <th><?php __( 'difdisp' );?></th>
                <td>
                    <?php if( !empty( $dspp['Difdisp'] ) ):?>
                        <ul>
                            <?php foreach( $dspp['Difdisp'] as $difdisp ):?>
                                <li><?php echo h( $difdisp['name'] );?></li>
                            <?php endforeach;?>
                        </ul>
                    <?php endif;?>
                </td>
            </tr>
        </tbody>
</table>
            <h2>Niveau d'étude</h2>
<table>
        <tbody>
            <tr class="odd">
                <th><?php __( 'annderdipobt' );?></th>
                <td><?php echo date_short( $dspp['Dspp']['annderdipobt'] );?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'rappemploiquali' );?></th>
                <td><?php echo ( $dspp['Dspp']['rappemploiquali'] ? 'Oui' : 'Non' );?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'rappemploiform' );?></th>
                <td><?php echo ( $dspp['Dspp']['rappemploiform'] ? 'Oui' : 'Non' );?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'libautrqualipro' );?></th>
                <td><?php echo $dspp['Dspp']['libautrqualipro'];?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'permicondub' );?></th>
                <td><?php echo ( $dspp['Dspp']['permicondub'] ? 'Oui' : 'Non' );?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'libautrpermicondu' );?></th>
                <td><?php echo $dspp['Dspp']['libautrpermicondu'];?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'libcompeextrapro' );?></th>
                <td><?php echo $dspp['Dspp']['libcompeextrapro'];?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'nivetu' );?></th>
                <td>
                    <?php if( !empty( $dspp['Nivetu'] ) ):?>
                        <ul>
                            <?php foreach( $dspp['Nivetu'] as $nivetu ):?>
                                <li><?php echo h( $nivetu['name'] );?></li>
                            <?php endforeach;?>
                        </ul>
                    <?php endif;?>
                </td>
            </tr>
        </tbody>
</table>
            <h2>Situation professionelle</h2>
<table>
        <tbody>
            <tr class="odd">
                <th><?php __( 'accoemploi' );?></th>
                <td>
                    <?php if( !empty( $dspp['Accoemploi'] ) ):?>
                        <ul>
                            <?php foreach( $dspp['Accoemploi'] as $accoemploi ):?>
                                <li><?php echo h( $accoemploi['name'] );?></li>
                            <?php endforeach;?>
                        </ul>
                    <?php endif;?>
                </td>
            </tr>
            <tr class="even">
                <th><?php __( 'libcooraccoemploi' );?></th>
                <td><?php echo $dspp['Dspp']['libcooraccoemploi'];?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'hispro' );?></th>
                <td><?php echo $hispro[$dspp['Dspp']['hispro']];?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'libderact' );?></th>
                <td><?php echo $dspp['Dspp']['libderact'];?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'libsecactderact' );?></th>
                <td><?php echo $dspp['Dspp']['libsecactderact'];?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'dfderact' );?></th>
                <td><?php echo date_short( $dspp['Dspp']['dfderact'] );?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'domideract' );?></th>
                <td><?php echo ( $dspp['Dspp']['domideract']? 'Oui' : 'Non' );?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'libactdomi' );?></th>
                <td><?php echo $dspp['Dspp']['libactdomi'];?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'libsecactdomi' );?></th>
                <td><?php echo $dspp['Dspp']['libsecactdomi'];?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'duractdomi' );?></th>
                <td><?php echo isset( $duractdomi[$dspp['Dspp']['duractdomi']] ) ? $duractdomi[$dspp['Dspp']['duractdomi']] : null ;?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'libemploirech' );?></th>
                <td><?php echo $dspp['Dspp']['libemploirech'];?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'libsecactrech' );?></th>
                <td><?php echo $dspp['Dspp']['libsecactrech'];?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'creareprisentrrech' );?></th>
                <td><?php echo ( $dspp['Dspp']['creareprisentrrech'] ? 'Oui' : 'Non' );?></td>
            </tr>
            <tr class="even">
                <th><?php __( 'moyloco' );?></th>
                <td><?php echo ( $dspp['Dspp']['moyloco'] ? 'Oui' : 'Non' );?></td>
            </tr>
            <tr class="odd">
                <th><?php __( 'persisogrorechemploi' );?></th>
                <td><?php echo ( $dspp['Dspp']['persisogrorechemploi'] ? 'Oui' : 'Non' );?></td>
            </tr>
        </tbody>
</table>
            <h2>Mobilité</h2>
<table>
        <tbody>
            <tr class="even">
                <th><?php __( 'natmob' );?></th>
                <td>
                    <?php if( !empty( $dspp['Natmob'] ) ):?>
                        <ul>
                            <?php foreach( $dspp['Natmob'] as $natmob ):?>
                                <li><?php echo h( $natmob['name'] );?></li>
                            <?php endforeach;?>
                        </ul>
                    <?php endif;?>
                </td>
           </tr>
        </tbody>
    </table>
</div>
    <?php endif;?>
</div>
<div class="clearer"><hr /></div>
