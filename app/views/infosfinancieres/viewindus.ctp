<?php $this->pageTitle = 'Détails des indus';?>

<?php echo $this->element( 'dossier_menu', array( 'id' => $dossier_rsa_id ) );?>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>


    <?php if( empty( $infofinanciere ) ):?>
        <p class="notice">Ce dossier ne possède pas d'indus.</p>

    <?php else:?>
    <h2>Généralités</h2>
        <table class="wide">
            <tbody>
                    <tr class="odd">
                        <th class="aere">Type de l'indu</th>
                        <td><?php echo $natpfcre[$infofinanciere['Infofinanciere']['natpfcre']];?></td>
                    </tr>
                    <tr class="even">
                        <th class="aere">Motif de l'indu</th>
                        <td><?php echo $typeopecompta[$infofinanciere['Infofinanciere']['typeopecompta']];?></td>
                    </tr>
                    <tr class="odd">
                        <th class="aere">Date de l'indu</th>
                        <td><?php echo $locale->date( 'Date::short', $infofinanciere['Infofinanciere']['moismoucompta'] );?></td>
                    </tr>
                    <tr class="even">
                        <th class="aere">Date transfert CG</th>
                        <td><?php echo $locale->date( 'Date::short', $infofinanciere['Infofinanciere']['dttraimoucompta'] );?></td>
                    </tr>
            </tbody>
        </table>

    <h2>Montants</h2>
        <table class="aere">
            <thead>
                <tr class="odd">
                    <th></th>
                    <th>RSA "socle"</th>
                    <th>RSA "chapeau"</th>
                </tr>
            </thead>
            <tbody>
                <tr class="even">
                    <th class="aere">Montant initial de l'indu</th>
                    <td class="number"><?php echo $locale->money( $infofinanciere[0]['mt_indu_constate'] );?></td>
                    <td><?php /*echo $locale->money( $infofinanciere[0]['mt_indu_constate'] );*/?></td>
                </tr>
                <tr class="odd">
                    <th class="aere">Recouvrement par la CAF</th>
                    <td class="number"><?php echo $locale->money( $infofinanciere[0]['mt_remises_indus'] );?></td>
                    <td><?php /*echo $locale->money( $infofinanciere[0]['mt_indu_constate'] );*/?></td>
                </tr>
                <tr class="even">
                    <th class="aere">Remise accordée par la CAF</th>
                    <td class="number"><?php echo $locale->money( $infofinanciere[0]['mt_remises_indus'] );?></td>
                    <td><?php /*echo $locale->money( $infofinanciere[0]['mt_indu_constate'] );*/?></td>
                </tr>
                <tr class="odd">
                    <th class="aere">Montant transféré</th>
                    <td class="number"><?php echo $locale->money( $infofinanciere[0]['mt_indus_transferes_c_g'] );?></td>
                    <td><?php /*echo $locale->money( $infofinanciere[0]['mt_indu_constate'] );*/?></td>
                </tr>
                <tr class="even">
                    <th class="aere">Remise CG</th>
                    <td class="number"><?php echo $locale->money( $infofinanciere[0]['mt_remises_indus'] );?></td>
                    <td></td>
                </tr>
                <tr class="odd">
                    <th class="aere">Solde final de l'indu</th>
                    <td class="number"><?php echo $locale->money( $infofinanciere[0]['mt_indus_transferes_c_g'] - $infofinanciere[0]['mt_remises_indus'] );?></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    <?php endif;?>
        <ul class="actionMenu">
            <?php
                echo $html->remiseLink(
                    'Enregistrer les remises',
                    array( 'controller' => 'gedooos', 'action' => 'notifications_cohortes' )
                );
            ?>

            <?php
                echo $html->recgraLink(
                    'Recours gracieux',
                    array( 'controller' => 'cohortes', 'action' => 'exportcsv' )
                );
            ?>

            <?php
                echo $html->recconLink(
                    'Recours contentieux',
                    array( 'controller' => 'cohortes', 'action' => 'exportcsv' )
                );
            ?>
        </ul>
</div>
<div class="clearer"><hr /></div>