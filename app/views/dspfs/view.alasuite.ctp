<?php $this->pageTitle = 'Données socioprofessionnelles';?>

<?php echo $this->element( 'dossier_menu', array( 'foyer_id' => $foyer_id ) );?>

<!--
    FIXME
        * niveaux de titres
        * réutilisation d'un autre view ou _view ?
-->
<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <!--   Données socioprofessionnelles du Foyer   -->
    <?php if( empty( $dsp['foyer'] ) ):?>
        <h2>Foyer</h2>
        <p class="notice">Ce foyer ne possède pas encore de questionnaire socio-professionnel.</p>
        <ul class="actionMenu">
            <?php
                echo '<li>'.$html->addLink(
                    'Ajouter un dossier pour le Foyer',
                    array( 'controller' => 'dspfs', 'action' => 'add', $foyer_id )
                ).' </li>';
            ?>
        </ul>
    <?php else:?>
        <ul class="actionMenu">
            <?php
                echo '<li>'.$html->editLink(
                    'Éditer un dossier pour le Foyer',
                    array( 'controller' => 'dspfs', 'action' => 'edit', $dsp['foyer']['Dspf']['id'] )
                ).' </li>';
            ?>
        </ul>

        <h2>Généralités Données socioprofessionnelles du Foyer</h2>
        <table class="wide bodyHeaders mediumHeader">
            <tbody>
                <tr class="odd">
                    <th><?php __( 'motidemrsa' );?></th>
                    <td><?php echo ( $motidemrsa[$dsp['foyer']['Dspf']['motidemrsa']] );?></td>
                </tr>
            </tbody>
        </table>
        <h2>Accompagnement social familial</h2>
        <table class="wide bodyHeaders mediumHeader">
            <tbody>
                <tr class="even">
                    <th><?php __( 'accosocfam' );?></th>
                    <td><?php echo $html->boolean( $dsp['foyer']['Dspf']['accosocfam'] );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'libautraccosocfam' );?></th>
                    <td><?php echo ( $dsp['foyer']['Dspf']['libautraccosocfam'] );?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'libcooraccosocfam' );?></th>
                    <td><?php echo ( $dsp['foyer']['Dspf']['libcooraccosocfam'] );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'nataccosocfam');?></th>
                    <td>
                        <?php if( !empty( $dsp['foyer']['Nataccosocfam'] ) ):?>
                            <ul>
                                <?php foreach( $dsp['foyer']['Nataccosocfam'] as $nataccosocfams ):?>
                                    <li><?php echo h( $nataccosocfams['name'] );?></li>
                                <?php endforeach;?>
                            </ul>
                        <?php endif;?>
                    </td>
                </tr>
            </tbody>
        </table>
        <h2>Difficultés de logement</h2>
        <table class="wide bodyHeaders mediumHeader">
            <tbody>
                <tr class="even">
                    <th><?php __( 'natlog' );?></th>
                    <td><?php echo ( $natlog[$dsp['foyer']['Dspf']['natlog']] );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'libautrdiflog' );?></th>
                    <td><?php echo ( $dsp['foyer']['Dspf']['libautrdiflog'] );?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'demarlog' );?></th>
                    <td><?php echo $demarlog[$dsp['foyer']['Dspf']['demarlog']];?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'diflog');?></th>
                    <td>
                        <?php if( !empty( $dsp['foyer']['Diflog'] ) ):?>
                            <ul>
                                <?php foreach( $dsp['foyer']['Diflog'] as $diflog ):?>
                                    <li><?php echo h( $diflog['name'] );?></li>
                                <?php endforeach;?>
                            </ul>
                        <?php endif;?>
                    </td>
                </tr>
            </tbody>
        </table>
    <?php endif;?>

    <!--   Données socioprofessionnelles DEM ou CJT -->
    <?php foreach( array( 'DEM', 'CJT' ) as $pers ):?>
        <?php if( !empty( $dsp[$pers] ) && !empty(  $dsp[$pers]['Personne'] ) ):?>
            <hr />

            <h2><?php echo $rolepers[$dsp[$pers]['Personne']['rolepers']]?></h2>

            <?php if( empty(  $dsp[$pers]['Dspp'] ) ):?>
                <p class="notice">Cette personne ne possède pas encore de questionnaire socio-professionnel.</p>
                <ul class="actionMenu">
                    <?php
                        echo '<li>'.$html->addLink(
                        'Ajouter un dossier pour la personne',
                        array( 'controller' => 'dspps', 'action' => 'add', $dsp[$pers]['Personne']['id'] )
                        ).' </li>';
                    ?>
                </ul>
            <?php else:?>
                <ul class="actionMenu">
                    <?php
                        echo '<li>'.$html->editLink(
                            'Éditer un dossier pour la personne',
                            array( 'controller' => 'dspps', 'action' => 'edit', $dsp[$pers]['Personne']['id'] )
                        ).' </li>';
                    ?>
                </ul>

                <h2>Généralités Données socioprofessionnelles</h2>
                <table class="wide bodyHeaders widerHeader">
                    <tbody>
                        <tr class="odd">
                            <th ><?php __( 'drorsarmiant' );?></th>
                            <td><?php echo $html->boolean( $dsp[$pers]['Dspp']['drorsarmiant'] );?></td>
                        </tr>
                        <tr class="even">
                            <th><?php __( 'drorsarmianta2' );?></th>
                            <td><?php echo $html->boolean( $dsp[$pers]['Dspp']['drorsarmianta2'] );?></td>
                        </tr>
                        <tr class="odd">
                            <th><?php __( 'couvsoc' );?></th>
                            <td><?php echo $html->boolean( $dsp[$pers]['Dspp']['couvsoc'] );?></td>
                        </tr>
                    </tbody>
                </table>
                <h2>Situation sociale</h2>
                <table class="wide bodyHeaders widerHeader">
                    <tbody>
                        <tr class="even">
                            <th><?php __( 'elopersdifdisp' );?></th>
                            <td><?php echo $html->boolean( $dsp[$pers]['Dspp']['elopersdifdisp'] );?></td>
                        </tr>
                        <tr class="odd">
                            <th><?php __( 'obstemploidifdisp' );?></th>
                            <td><?php echo $html->boolean( $dsp[$pers]['Dspp']['obstemploidifdisp'] );?></td>
                        </tr>
                        <tr class="even">
                            <th><?php __( 'soutdemarsoc' );?></th>
                            <td><?php echo $html->boolean( $dsp[$pers]['Dspp']['soutdemarsoc'] );?></td>
                        </tr>
                    </tbody>
                </table>
                <?php if( !empty( $dsp[$pers]['Dspp']['libcooraccosocindi'] ) ):?>
                    <h3><?php __( 'libcooraccosocindi' );?></h3>
                    <p><?php echo nl2br( h( $dsp[$pers]['Dspp']['libcooraccosocindi'] ) );?></p>
                <?php endif;?>
                <h2>Difficultés sociales</h2>
                <table class="wide bodyHeaders mediumHeader">
                    <tbody>
                        <tr class="odd">
                            <th><?php __( 'difsoc');?></th>
                            <td>
                                <?php if( !empty( $dsp[$pers]['Difsoc'] ) ):?>
                                    <ul>
                                        <?php foreach( $dsp[$pers]['Difsoc'] as $difsoc ):?>
                                            <li><?php echo h( $difsoc['name'] );?></li>
                                        <?php endforeach;?>
                                    </ul>
                                <?php endif;?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php if( !empty( $dsp[$pers]['Dspp']['libautrdifsoc'] ) ):?>
                    <h3><?php __( 'libautrdifsoc' );?></h3>
                    <p><?php echo nl2br( h( $dsp[$pers]['Dspp']['libautrdifsoc'] ) );?></p>
                <?php endif;?>
                <h2>Accompagnement individuel</h2>
                <table class="wide bodyHeaders widerHeader">
                    <tbody>
                        <tr class="even">
                            <th><?php __( 'nataccosocindi' );?></th>
                            <td>
                                <?php if( !empty( $dsp[$pers]['Nataccosocindi'] ) ):?>
                                    <ul>
                                        <?php foreach( $dsp[$pers]['Nataccosocindi'] as $nataccosocindi ):?>
                                            <li><?php echo h( $nataccosocindi['name'] );?></li>
                                        <?php endforeach;?>
                                    </ul>
                                <?php endif;?>
                            </td>
                        </tr>
                        <tr class="odd">
                            <th><?php __( 'libautraccosocindi' );?></th>
                            <td><?php echo $dsp[$pers]['Dspp']['libautraccosocindi'];?></td>
                        </tr>
                    </tbody>
                </table>
                <h2>Difficultés de disponibilité</h2>
                <table class="wide bodyHeaders widerHeader">
                    <tbody>
                        <tr class="even">
                            <th><?php __( 'difdisp' );?></th>
                            <td>
                                <?php if( !empty( $dsp[$pers]['Difdisp'] ) ):?>
                                    <ul>
                                        <?php foreach( $dsp[$pers]['Difdisp'] as $difdisp ):?>
                                            <li><?php echo h( $difdisp['name'] );?></li>
                                        <?php endforeach;?>
                                    </ul>
                                <?php endif;?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <h2>Niveau d'étude</h2>
                <table class="wide bodyHeaders mediumHeader">
                    <tbody>
                        <tr class="odd">
                            <th><?php __( 'annderdipobt' );?></th>
                            <td><?php echo date_short( $dsp[$pers]['Dspp']['annderdipobt'] );?></td>
                        </tr>
                        <tr class="even">
                            <th><?php __( 'rappemploiquali' );?></th>
                            <td><?php echo $html->boolean( $dsp[$pers]['Dspp']['rappemploiquali'] );?></td>
                        </tr>
                        <tr class="odd">
                            <th><?php __( 'rappemploiform' );?></th>
                            <td><?php echo $html->boolean( $dsp[$pers]['Dspp']['rappemploiform'] );?></td>
                        </tr>
                        <tr class="even">
                            <th><?php __( 'libautrqualipro' );?></th>
                            <td><?php echo $dsp[$pers]['Dspp']['libautrqualipro'];?></td>
                        </tr>
                        <tr class="odd">
                            <th><?php __( 'permicondub' );?></th>
                            <td><?php echo $html->boolean( $dsp[$pers]['Dspp']['permicondub'] );?></td>
                        </tr>
                        <tr class="even">
                            <th><?php __( 'libautrpermicondu' );?></th>
                            <td><?php echo $dsp[$pers]['Dspp']['libautrpermicondu'];?></td>
                        </tr>
                        <tr class="odd">
                            <th><?php __( 'libcompeextrapro' );?></th>
                            <td><?php echo $dsp[$pers]['Dspp']['libcompeextrapro'];?></td>
                        </tr>
                        <tr class="even">
                            <th><?php __( 'nivetu' );?></th>
                            <td><?php var_dump($dsp[$pers]['Dspp']['nivetu']);?><?php echo ( !empty( $dsp[$pers]['Dspp']['nivetu'] ) ? $nivetu[$dsp[$pers]['Dspp']['nivetu']] : null );?></td>
                        </tr>
                    </tbody>
                </table>
                <h2>Situation professionelle</h2>
                <table class="wide bodyHeaders mediumHeader">
                    <tbody>
                        <tr class="odd">
                            <th><?php __( 'accoemploi' );?></th>
                            <td><?php echo $accoemploi[$dsp[$pers]['Dspp']['accoemploi']];?></td>
                        </tr>
                        <tr class="even">
                            <th><?php __( 'libcooraccoemploi' );?></th>
                            <td><?php echo $dsp[$pers]['Dspp']['libcooraccoemploi'];?></td>
                        </tr>
                        <tr class="odd">
                            <th><?php __( 'hispro' );?></th>
                            <td><?php echo $hispro[$dsp[$pers]['Dspp']['hispro']];?></td>
                        </tr>
                        <tr class="even">
                            <th><?php __( 'libderact' );?></th>
                            <td><?php echo $dsp[$pers]['Dspp']['libderact'];?></td>
                        </tr>
                        <tr class="odd">
                            <th><?php __( 'libsecactderact' );?></th>
                            <td><?php echo $dsp[$pers]['Dspp']['libsecactderact'];?></td>
                        </tr>
                        <tr class="even">
                            <th><?php __( 'dfderact' );?></th>
                            <td><?php echo date_short( $dsp[$pers]['Dspp']['dfderact'] );?></td>
                        </tr>
                        <tr class="odd">
                            <th><?php __( 'domideract' );?></th>
                            <td><?php echo $html->boolean( $dsp[$pers]['Dspp']['domideract'] );?></td>
                        </tr>
                        <tr class="even">
                            <th><?php __( 'libactdomi' );?></th>
                            <td><?php echo $dsp[$pers]['Dspp']['libactdomi'];?></td>
                        </tr>
                        <tr class="odd">
                            <th><?php __( 'libsecactdomi' );?></th>
                            <td><?php echo $dsp[$pers]['Dspp']['libsecactdomi'];?></td>
                        </tr>
                        <tr class="even">
                            <th><?php __( 'duractdomi' );?></th>
                            <td><?php echo ( !empty( $dsp[$pers]['Dspp']['duractdomi'] ) ? $duractdomi[$dsp[$pers]['Dspp']['duractdomi']] : null );?></td>
                        </tr>
                        <tr class="odd">
                            <th><?php __( 'libemploirech' );?></th>
                            <td><?php echo $dsp[$pers]['Dspp']['libemploirech'];?></td>
                        </tr>
                        <tr class="even">
                            <th><?php __( 'libsecactrech' );?></th>
                            <td><?php echo $dsp[$pers]['Dspp']['libsecactrech'];?></td>
                        </tr>
                        <tr class="odd">
                            <th><?php __( 'creareprisentrrech' );?></th>
                            <td><?php echo ( $dsp[$pers]['Dspp']['creareprisentrrech'] );?></td>
                        </tr>
                        <tr class="even">
                            <th><?php __( 'moyloco' );?></th>
                            <td><?php echo ( $dsp[$pers]['Dspp']['moyloco'] );?></td>
                        </tr>
                        <tr class="odd">
                            <th><?php __( 'persisogrorechemploi' );?></th>
                            <td><?php echo ( $dsp[$pers]['Dspp']['persisogrorechemploi'] );?></td>
                        </tr>
                    </tbody>
                </table>
                <h2>Mobilité</h2>
                <table class="wide bodyHeaders mediumHeader">
                    <tbody>
                        <tr class="even">
                            <th><?php __( 'natmob' );?></th>
                            <td>
                                <?php if( !empty( $dsp[$pers]['Natmob'] ) ):?>
                                    <ul>
                                        <?php foreach( $dsp[$pers]['Natmob'] as $natmob ):?>
                                            <li><?php echo h( $natmob['name'] );?></li>
                                        <?php endforeach;?>
                                    </ul>
                                <?php else:?>
                                    Non
                                <?php endif;?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            <?php endif;?>
        <?php endif;?>
    <?php endforeach;?>
</div>

<div class="clearer"><hr /></div>