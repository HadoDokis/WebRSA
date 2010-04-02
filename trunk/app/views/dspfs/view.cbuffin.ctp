<?php $this->pageTitle = 'Données socioprofessionnelles';?>

<?php echo $this->element( 'dossier_menu', array( 'personne_id' => $foyer_id ) );?>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <h2><?php echo 'Foyer';?></h2>
    <?php if( empty( $dsp['foyer'] ) ):?>
        <p class="notice">Ce foyer ne possède pas encore de questionnaire socio-professionnel.</p>
    <?php endif;?>

    <ul class="actionMenu">
        <?php
            echo '<li>'.$html->addLink(
                'Ajouter un dossier pour le Foyer',
                array( 'controller' => 'dspfs', 'action' => 'add', $foyer_id )
            ).' </li>';
        ?>
        <?php if( !empty( $dsp['foyer'] ) ):?>
            <?php
                echo '<li>'.$html->editLink(
                    'Éditer un dossier pour le Foyer',
                    array( 'controller' => 'dspfs', 'action' => 'edit', $foyer_id )
                ).' </li>';
            ?>
        <?php endif;?>
    </ul>

    <?php if( !empty( $dsp['foyer'] ) ):?>
        <div id="ficheDspf">
            <h2>Généralités DSPF</h2>
            <table>
                <tbody>
                    <tr class="odd">
                        <th><?php __( 'motidemrsa' );?></th>
                        <td><?php echo ( $motidemrsa[$dsp['foyer']['Dspf']['motidemrsa']] );?></td>
                    </tr>
                </tbody>
            </table>
            <h2>Accompagnement social familial</h2>
            <table>
                <tbody>
                    <tr class="even">
                        <th><?php __( 'accosocfam' );?></th>
                        <td><?php echo ( $dsp['foyer']['Dspf']['accosocfam'] ? 'Oui' : 'Non' );?></td>
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
            <table>
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
        </div>
    <?php endif;?>
</div>

<div class="clearer"><hr /></div>