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
                    array( 'controller' => 'dspfs', 'action' => 'edit', $foyer_id )
                ).' </li>';
            ?>
        </ul>

        <h2>Généralités Données socioprofessionnelles du Foyer</h2>
        <table class="wide bodyHeaders mediumHeader">
            <tbody>
                <tr class="odd">
                    <th><?php __( 'motidemrsa' );?></th>
                    <td><?php echo ( isset( $motidemrsa[$dsp['foyer']['Dspf']['motidemrsa']] ) ? $motidemrsa[$dsp['foyer']['Dspf']['motidemrsa']] : null);?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'sitfam' );?></th>
                    <td><?php echo ( isset( $sitfam[$dsp['foyer']['Foyer']['sitfam']] ) ? $sitfam[$dsp['foyer']['Foyer']['sitfam']] : null );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'typeocclog' );?></th>
                    <td><?php echo ( isset( $typeocclog[$dsp['foyer']['Foyer']['typeocclog']] ) ? $typeocclog[$dsp['foyer']['Foyer']['typeocclog']] : null    );?></td>
                </tr>
            </tbody>
        </table>
        <h2>Accompagnement social familial</h2>
        <table class="wide bodyHeaders mediumHeader">
            <tbody>
                <tr class="even">
                    <th><?php __( 'accosocfam' );?></th>
                    <td><?php echo ( isset( $accosocfam[$dsp['foyer']['Dspf']['accosocfam']] ) ? $accosocfam[$dsp['foyer']['Dspf']['accosocfam']] : null );?></td>
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
                    <td><?php echo ( isset( $natlog[$dsp['foyer']['Dspf']['natlog']] ) ? $natlog[$dsp['foyer']['Dspf']['natlog']] : null );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'libautrdiflog' );?></th>
                    <td><?php echo ( $dsp['foyer']['Dspf']['libautrdiflog'] );?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'demarlog' );?></th>
                    <td><?php echo ( isset( $demarlog[$dsp['foyer']['Dspf']['demarlog']] ) ? $demarlog[$dsp['foyer']['Dspf']['demarlog']] : null );?></td>
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

<hr />

    <h2>DSP Personnes</h2>
    <!--   Données socioprofessionnelles DEM ou CJT -->
    <!--<?php foreach( array( 'DEM', 'CJT' ) as $personne ):?>
        <?php if( !empty( $dsp[$personne] ) && empty( $dsp[$personne]['Dspp'] ) ):?>
            <p class="notice">Cette personne ne possède pas encore de questionnaire socio-professionnel.</p>
        <?php endif;?>
    <?php endforeach;?> -->

    <?php if( empty($dsp['DEM']['Dspp'] ) && empty( $dsp['CJT']['Dspp'] ) ):?>
       <!-- <ul class="actionMenu">
            <?php
                echo '<li>'.$html->addLink(
                'Ajouter un dossier pour le demandeur',
                array( 'controller' => 'dspps', 'action' => 'add', $dsp['DEM']['Personne']['id'] )
                ).' </li>';
            ?>
        </ul> -->
    <?php else:?>
     <!--   <ul class="actionMenu">
            <?php
                echo '<li>'.$html->editLink(
                    'Éditer un dossier pour la personne',
                    array( 'controller' => 'dspps', 'action' => 'edit', $dsp['DEM']['Personne']['id'] )
                ).' </li>';
            ?>
        </ul> -->

<?php
    function thead( $pct = 10 ) {
        return '<thead>
                <tr>
                    <th>&nbsp;</th>
                    <th style="width: '.$pct.'%;">Demandeur</th>
                    <th style="width: '.$pct.'%;">Conjoint</th>
                </tr>
            </thead>';
    }

    function value( $dsp, $personne, $table, $field ) {
        return ( ( isset( $dsp[$personne][$table] ) && isset( $dsp[$personne][$table][$field] ) ) ? ( $dsp[$personne][$table][$field] ) : null );
    }

    function linkedValue( $links, $dsp, $personne, $table, $field ) {
        $value = ( ( isset( $dsp[$personne][$table] ) && isset( $dsp[$personne][$table][$field] ) ) ? ( $dsp[$personne][$table][$field] ) : null );
        return ( isset( $links[$value] ) ? $links[$value] : null );
    }
?>


        <h2>Généralités Données socioprofessionnelles</h2>
        <table class="wide bodyHeaders wideHeader">
            <?php echo thead( 10 );?>
            <tbody>
                <tr class="odd">
                    <th ><?php __( 'drorsarmiant' );?></th>
                    <td><?php echo linkedValue( $drorsarmiant, $dsp, 'DEM', 'Dspp', 'drorsarmiant' );?></td>
                    <td><?php echo linkedValue( $drorsarmiant, $dsp, 'CJT', 'Dspp', 'drorsarmiant' );?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'drorsarmianta2' );?></th>
                    <td><?php echo linkedValue( $drorsarmianta2, $dsp, 'DEM', 'Dspp', 'drorsarmianta2' );?></td>
                    <td><?php echo linkedValue( $drorsarmianta2, $dsp, 'CJT', 'Dspp', 'drorsarmianta2' );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'couvsoc' );?></th>
                    <td><?php echo linkedValue( $couvsoc, $dsp, 'DEM', 'Dspp', 'couvsoc' );?></td>
                    <td><?php echo linkedValue( $couvsoc, $dsp, 'CJT', 'Dspp', 'couvsoc' );?></td>
                </tr>
            </tbody>
        </table>

        <h2>Situation sociale</h2>
        <table class="wide bodyHeaders wideHeader">
            <?php echo thead( 10 );?>
            <tbody>
                <tr class="even">
                    <th><?php __( 'elopersdifdisp' );?></th>
                    <td><?php echo linkedValue( $elopersdifdisp, $dsp, 'DEM', 'Dspp', 'elopersdifdisp' );?></td>
                    <td><?php echo linkedValue( $elopersdifdisp, $dsp, 'CJT', 'Dspp', 'elopersdifdisp' );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'obstemploidifdisp' );?></th>
                    <td><?php echo linkedValue( $obstemploidifdisp, $dsp, 'DEM', 'Dspp', 'obstemploidifdisp' );?></td>
                    <td><?php echo linkedValue( $obstemploidifdisp, $dsp, 'CJT', 'Dspp', 'obstemploidifdisp' );?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'soutdemarsoc' );?></th>
                    <td><?php echo linkedValue( $soutdemarsoc, $dsp, 'DEM', 'Dspp', 'soutdemarsoc' );?></td>
                    <td><?php echo linkedValue( $soutdemarsoc, $dsp, 'CJT', 'Dspp', 'soutdemarsoc' );?></td>
                </tr>
            </tbody>
        </table>
        <table class="wide bodyHeaders mediumHeader">
            <?php echo thead( 25 );?>
                <tbody>
                <tr class="odd">
                    <th><?php __( 'libcooraccosocindi' );?></th>
                    <td>
                        <?php if( !empty( $dsp['DEM']['Dspp']['libcooraccosocindi'] ) ):?>
                            <?php echo nl2br( h( $dsp['DEM']['Dspp']['libcooraccosocindi'] ) );?>
                        <?php endif;?>
                    </td>
                    <td>
                        <?php if( !empty( $dsp['CJT']['Dspp']['libcooraccosocindi'] ) ):?>
                            <?php echo nl2br( h( $dsp['CJT']['Dspp']['libcooraccosocindi'] ) );?>
                        <?php endif;?> 
                    </td>
                </tr>
            </tbody>
        </table>
        <h2>Difficultés sociales</h2>
        <table class="wide bodyHeaders mediumHeader">
            <?php echo thead( 25 );?>
            <tbody>
                <tr class="even">
                    <th><?php __( 'difsoc');?></th>
                    <td>
                        <?php if( !empty( $dsp['DEM']['Difsoc'] ) ):?>
                            <ul>
                                <?php foreach( $dsp['DEM']['Difsoc']  as $difsoc ):?>
                                    <li><?php echo h( $difsoc['name'] );?></li>
                                <?php endforeach;?>
                            </ul>
                        <?php endif;?>
                    </td>
                    <td>
                        <?php if( !empty( $dsp['CJT']['Difsoc'] ) ):?>
                            <ul>
                                <?php foreach( $dsp['CJT']['Difsoc'] as $difsoc ):?>
                                    <li><?php echo h( $difsoc['name'] );?></li>
                                <?php endforeach;?>
                            </ul>
                        <?php endif;?>
                    </td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'libautrdifsoc');?></th>
                    <td>
                        <?php if( !empty( $dsp['DEM']['Dspp']['libautrdifsoc'] ) ):?>
                            <?php echo nl2br( h( $dsp['DEM']['Dspp']['libautrdifsoc'] ) );?>
                        <?php endif;?>
                    </td>
                    <td>
                        <?php if( !empty( $dsp['CJT']['Dspp']['libautrdifsoc'] ) ):?>
                            <?php echo nl2br( h( $dsp['CJT']['Dspp']['libautrdifsoc'] ) );?>
                        <?php endif;?>
                    </td>
                </tr>
            </tbody>
        </table>
  
        <h2>Accompagnement individuel</h2>
        <table class="wide bodyHeaders mediumHeader">
            <?php echo thead( 25 );?>
            <tbody>
                <tr class="even">
                    <th><?php __( 'nataccosocindi' );?></th>
                    <td>
                        <?php if( !empty( $dsp['DEM']['Nataccosocindi'] ) ):?>
                            <ul>
                                <?php foreach( $dsp['DEM']['Nataccosocindi'] as $nataccosocindi ):?>
                                    <li><?php echo h( $nataccosocindi['name'] );?></li>
                                <?php endforeach;?>
                            </ul>
                        <?php endif;?>
                    </td>
                    <td>
                        <?php if( !empty( $dsp['CJT']['Nataccosocindi'] ) ):?>
                            <ul>
                                <?php foreach( $dsp['CJT']['Nataccosocindi'] as $nataccosocindi ):?>
                                    <li><?php echo h( $nataccosocindi['name'] );?></li>
                                <?php endforeach;?>
                            </ul>
                        <?php endif;?>
                    </td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'libautraccosocindi' );?></th>
                    <td><?php echo value( $dsp, 'DEM', 'Dspp', 'libautraccosocindi' ) ;?></td>
                    <td><?php echo value( $dsp, 'CJT', 'Dspp', 'libautraccosocindi' ) ;?></td>
                </tr>
            </tbody>
        </table>
        <h2>Difficultés de disponibilité</h2>
        <table class="wide bodyHeaders mediumHeader">
            <?php echo thead( 25 );?>
            <tbody>
                <tr class="even">
                    <th><?php __( 'difdisp' );?></th>
                    <td>
                        <?php if( !empty( $dsp['DEM']['Difdisp'] ) ):?>
                            <ul>
                                <?php foreach( $dsp['DEM']['Difdisp'] as $difdisp ):?>
                                    <li><?php echo h( $difdisp['name'] );?></li>
                                <?php endforeach;?>
                            </ul>
                        <?php endif;?>
                    </td>
                    <td>
                        <?php if( !empty( $dsp['CJT']['Difdisp'] ) ):?>
                            <ul>
                                <?php foreach( $dsp['CJT']['Difdisp'] as $difdisp ):?>
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
            <?php echo thead( 25 );?>
            <tbody>
                <tr class="odd">
                    <th><?php __( 'annderdipobt' );?></th>
                    <td><?php echo date_short( value( $dsp, 'DEM', 'Dspp', 'annderdipobt' ) );?></td>
                    <td><?php echo date_short( value( $dsp, 'CJT', 'Dspp', 'annderdipobt' ) );?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'rappemploiquali' );?></th>
                    <td><?php echo $html->boolean( value( $dsp, 'DEM', 'Dspp', 'rappemploiquali' ) );?></td>
                    <td><?php echo $html->boolean( value( $dsp, 'CJT', 'Dspp', 'rappemploiquali' ) );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'rappemploiform' );?></th>
                    <td><?php echo $html->boolean( value( $dsp, 'DEM', 'Dspp', 'rappemploiform' ) );?></td>
                    <td><?php echo $html->boolean( value( $dsp, 'CJT', 'Dspp', 'rappemploiform' ) );?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'libautrqualipro' );?></th>
                    <td><?php echo value( $dsp, 'DEM', 'Dspp', 'libautrqualipro' ) ;?></td>
                    <td><?php echo value( $dsp, 'CJT', 'Dspp', 'libautrqualipro' ) ;?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'permicondub' );?></th>
                    <td><?php echo $html->boolean( value( $dsp, 'DEM', 'Dspp', 'permicondub' ) );?></td>
                    <td><?php echo $html->boolean( value( $dsp, 'CJT', 'Dspp', 'permicondub' ) );?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'libautrpermicondu' );?></th>
                    <td><?php echo value( $dsp, 'DEM', 'Dspp', 'libautrpermicondu' ) ;?></td>
                    <td><?php echo value( $dsp, 'CJT', 'Dspp', 'libautrpermicondu' ) ;?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'libcompeextrapro' );?></th>
                    <td><?php echo value( $dsp, 'DEM', 'Dspp', 'libcompeextrapro' ) ;?></td>
                    <td><?php echo value( $dsp, 'CJT', 'Dspp', 'libcompeextrapro' ) ;?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'nivetu' );?></th>
                    <td>
                        <?php if( !empty( $dsp['DEM']['Nivetu'] ) ):?>
                            <ul>
                                <?php foreach( $dsp['DEM']['Nivetu'] as $nivetu ):?>
                                    <li><?php echo h( $nivetu['name'] );?></li>
                                <?php endforeach;?>
                            </ul>
                        <?php endif;?>
                    </td>
                    <td>
                        <?php if( !empty( $dsp['CJT']['Nivetu'] ) ):?>
                            <ul>
                                <?php foreach( $dsp['CJT']['Nivetu'] as $nivetu ):?>
                                    <li><?php echo h( $nivetu['name'] );?></li>
                                <?php endforeach;?>
                            </ul>
                        <?php endif;?>
                    </td>
                </tr>
            </tbody>
        </table>
        <h2>Situation professionelle</h2>
        <table class="wide bodyHeaders mediumHeader">
            <?php echo thead( 25 );?>
            <tbody>
                <tr class="odd">
                    <th><?php __( 'accoemploi' );?></th>
                    <td>
                        <?php if( !empty( $dsp['DEM']['Accoemploi'] ) ):?>
                            <ul>
                                <?php foreach( $dsp['DEM']['Accoemploi'] as $accoemploi ):?>
                                    <li><?php echo h( $accoemploi['name'] );?></li>
                                <?php endforeach;?>
                            </ul>
                        <?php endif;?>
                    </td>
                    <td>
                        <?php if( !empty( $dsp['CJT']['Accoemploi'] ) ):?>
                            <ul>
                                <?php foreach( $dsp['CJT']['Accoemploi'] as $accoemploi ):?>
                                    <li><?php echo h( $accoemploi['name'] );?></li>
                                <?php endforeach;?>
                            </ul>
                        <?php endif;?>
                    </td>
                </tr>
                <tr class="even">
                    <th><?php __( 'libcooraccoemploi' );?></th>
                    <td><?php echo value( $dsp, 'DEM', 'Dspp', 'libcooraccoemploi' ) ;?></td>
                    <td><?php echo value( $dsp, 'CJT', 'Dspp', 'libcooraccoemploi' ) ;?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'hispro' );?></th>
                    <td><?php echo linkedValue( $hispro, $dsp, 'DEM', 'Dspp', 'hispro' );?></td>
                    <td><?php echo linkedValue( $hispro, $dsp, 'CJT', 'Dspp', 'hispro' );?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'libderact' );?></th>
                    <td><?php echo value( $dsp, 'DEM', 'Dspp', 'libderact' ) ;?></td>
                    <td><?php echo value( $dsp, 'CJT', 'Dspp', 'libderact' ) ;?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'libsecactderact' );?></th>
                    <td><?php echo value( $dsp, 'DEM', 'Dspp', 'libsecactderact' ) ;?></td>
                    <td><?php echo value( $dsp, 'CJT', 'Dspp', 'libsecactderact' ) ;?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'dfderact' );?></th>
                    <td><?php echo date_short( value( $dsp, 'DEM', 'Dspp', 'dfderact' ) );?></td>
                    <td><?php echo date_short( value( $dsp, 'CJT', 'Dspp', 'dfderact' ) );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'domideract' );?></th>
                    <td><?php echo linkedValue( $domideract, $dsp, 'DEM', 'Dspp', 'domideract' );?></td>
                    <td><?php echo linkedValue( $domideract, $dsp, 'CJT', 'Dspp', 'domideract' );?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'libactdomi' );?></th>
                    <td><?php echo value( $dsp, 'DEM', 'Dspp', 'libactdomi' ) ;?></td>
                    <td><?php echo value( $dsp, 'CJT', 'Dspp', 'libactdomi' ) ;?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'libsecactdomi' );?></th>
                    <td><?php echo value( $dsp, 'DEM', 'Dspp', 'libsecactdomi' ) ;?></td>
                    <td><?php echo value( $dsp, 'CJT', 'Dspp', 'libsecactdomi' ) ;?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'duractdomi' );?></th>
                    <td><?php echo linkedValue( $duractdomi, $dsp, 'DEM', 'Dspp', 'duractdomi' );?></td>
                    <td><?php echo linkedValue( $duractdomi, $dsp, 'CJT', 'Dspp', 'duractdomi' );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'libemploirech' );?></th>
                    <td><?php echo value( $dsp, 'DEM', 'Dspp', 'libemploirech' ) ;?></td>
                    <td><?php echo value( $dsp, 'CJT', 'Dspp', 'libemploirech' ) ;?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'libsecactrech' );?></th>
                    <td><?php echo value( $dsp, 'DEM', 'Dspp', 'libsecactrech' ) ;?></td>
                    <td><?php echo value( $dsp, 'CJT', 'Dspp', 'libsecactrech' ) ;?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'creareprisentrrech' );?></th>
                    <td><?php echo linkedValue( $creareprisentrrech, $dsp, 'CJT', 'Dspp', 'creareprisentrrech' ) ;?></td>
                    <td><?php echo linkedValue( $creareprisentrrech, $dsp, 'CJT', 'Dspp', 'creareprisentrrech' );?></td>
                </tr>
                <tr class="even">
                    <th><?php __( 'moyloco' );?></th>
                    <td><?php echo $html->boolean( value( $dsp, 'DEM', 'Dspp', 'moyloco' ) );?></td>
                    <td><?php echo $html->boolean( value( $dsp, 'CJT', 'Dspp', 'moyloco' ) );?></td>
                </tr>
                <tr class="odd">
                    <th><?php __( 'persisogrorechemploi' );?></th>
                    <td><?php echo $html->boolean( value( $dsp, 'DEM', 'Dspp', 'persisogrorechemploi' ) );?></td>
                    <td><?php echo $html->boolean( value( $dsp, 'CJT', 'Dspp', 'persisogrorechemploi' ) );?></td>
                </tr>
            </tbody>
        </table>
        <h2>Mobilité</h2>
        <table class="wide bodyHeaders mediumHeader">
            <?php echo thead( 25 );?>
            <tbody>
                <tr class="even">
                    <th><?php __( 'natmob' );?></th>
                    <td>
                        <?php if( !empty( $dsp['DEM']['Natmob'] ) ):?>
                            <ul>
                                <?php foreach( $dsp['DEM']['Natmob'] as $natmob ):?>
                                    <li><?php echo h( $natmob['name'] );?></li>
                                <?php endforeach;?>
                            </ul>
                        <?php else:?>
                        <?php endif;?>
                    </td>
                    <td>
                        <?php if( !empty( $dsp['CJT']['Natmob'] ) ):?>
                            <ul>
                                <?php foreach( $dsp['CJT']['Natmob'] as $natmob ):?>
                                    <li><?php echo h( $natmob['name'] );?></li>
                                <?php endforeach;?>
                            </ul>
                        <?php else:?>
                        <?php endif;?>
                    </td>
                </tr>
            </tbody>
        </table>
   <?php endif;?>
</div>

<div class="clearer"><hr /></div>
