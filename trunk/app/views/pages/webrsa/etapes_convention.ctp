<?php echo $xhtml->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>

<?php  $this->pageTitle = 'Etapes de la convention N° xxx';?>


<?php
    function thead( $pct = 100, $role = null ) {
        return '<thead>
                <tr>
                    <th colspan="7" style="width: '.$pct.'%;">'.$role.'</th>
                </tr>
            </thead>';
    }

?>


<div class="">
    <h1><?php echo $this->pageTitle;?></h1>

    <form method="post" action="gestion_convention">
    <div id="resumeDossier">

    <table  id="resumeConvention" class="noborder">

        <tbody>
            <tr>
                <td class="noborder">
                    <h2>Création de la convention</h2>
                    <table>
                        <?php echo thead( 100, 'Délibération de la Commission Permanente du CG' );?>
                        <tbody>
                            <tr class="odd">
                                <th>Transmission au SACG</th>
                                <th>Délibération</th>
                                <th>Retour du SACG</th>
                                <th>Notification à la ville</th>
                                <th class="action">Action</th>
                            </tr>
                            <tr>
                                <td><?php echo 'Transmis';?></td>
                                <td><?php echo 'Validation';?></td>
                                <td><?php echo 'En attente de retour';?></td>
                                <td><?php echo 'En attente';?></td>
                                <td><?php echo $xhtml->viewLink(
                                    'Voir le dossier',
                                    array( '#')
                                    );?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td class="noborder">
                    <h2>Etapes - Vie de la convention</h2>
                    <table>
                        <?php echo thead( 50, '<strong>Etape 1 </strong>: Le versement de la 1ère avance de trésorerie de 20%' );?>
                        <tbody>
                            <tr class="odd">
                                <th>Montant (avant avenant)</th>
                                <th>Autorisation de versement</th>
                                <th>Liquidation</th>
                                <th>Lettre d'avis de paiement</th>
                                <th class="action">Action</th>
                            </tr>
                            <tr>
                                <td><?php echo '200 €';?></td>
                                <td><?php echo 'Autorisation';?></td>
                                <td><?php echo '';?></td>
                                <td><?php echo '';?></td>
                                <td><?php echo $xhtml->viewLink(
                                    'Voir le dossier',
                                    array( '#')
                                    );?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="noborder">
                    <table>
                        <?php echo thead( 100, 'Délibération du Conseil municipal et signature du maire' );?>
                        <tbody>
                            <tr class="odd">
                                <th>Remise convention vierge à la ville</th>
                                <th>Délibération</th>
                                <th>Retour de la ville</th>
                                <th class="action">Action</th>
                            </tr>
                            <tr>
                                <td><?php echo 'Remis';?></td>
                                <td><?php echo 'Validation';?></td>
                                <td><?php echo 'En attente de retour';?></td>
                                <td><?php echo $xhtml->viewLink(
                                    'Voir le dossier',
                                    array( '#')
                                    );?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td class="noborder">
                    <table>
                        <?php echo thead( 50, '<strong>Etape 2 </strong>: Le traitement des documents Bilan et Projet d\'activité');?>
                        <tbody>
                            <tr class="odd">
                                <th>Lettre de proposition d'objectif / relance</th>
                                <th>Lettre de non respect du détail de transmission</th>
                                <th>Réception du bilan / projet</th>
                                <th>Lettre accusant réception du bilan / projet</th>
                                <th>Diffusion du bilan / projet</th>
                                <th class="action">Action</th>
                            </tr>
                            <tr>
                                <td><?php echo '';?></td>
                                <td><?php echo 'Autorisation';?></td>
                                <td><?php echo '';?></td>
                                <td><?php echo '';?></td>
                                <td><?php echo '';?></td>
                                <td><?php echo $xhtml->viewLink(
                                    'Voir le dossier',
                                    array( '#')
                                    );?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="noborder">
                    <table>
                        <?php echo thead( 100, 'Convention' );?>
                        <tbody>
                            <tr class="odd">
                                <th>Réception</th>
                                <th class="action">Action</th>
                            </tr>
                            <tr>
                                <td><?php echo 'Reçue';?></td>
                                <td><?php echo $xhtml->viewLink(
                                    'Voir le dossier',
                                    array( '#')
                                    );?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td class="noborder">
                    <table>
                        <?php echo thead( 50, '<strong>Etape 3 </strong>: Le versement de la 2ème avance de trésorerie de 50%' );?>
                        <tbody>
                            <tr class="odd">
                                <th>Montant avant avenant</th>
                                <th>Autorisation de versement</th>
                                <th>Liquidation</th>
                                <th>Lettre d'avis de paiement</th>
                                <th class="action">Action</th>
                            </tr>
                            <tr>
                                <td><?php echo '200 €';?></td>
                                <td><?php echo 'Autorisation';?></td>
                                <td><?php echo '';?></td>
                                <td><?php echo '';?></td>
                                <td><?php echo $xhtml->viewLink(
                                    'Voir le dossier',
                                    array( '#')
                                    );?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="noborder">
                    <table>
                        <?php echo thead( 100, 'Signature du vice président' );?>
                        <tbody>
                            <tr class="odd">
                                <th>Transmission au SACG</th>
                                <th>Retour au SACG</th>
                                <th class="action">Action</th>
                            </tr>
                            <tr>
                                <td><?php echo 'Transmis';?></td>
                                <td><?php echo 'En attente de retour';?></td>
                                <td><?php echo $xhtml->viewLink(
                                    'Voir le dossier',
                                    array( '#')
                                    );?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td class="noborder">
                    <table>
                        <?php echo thead( 50, '<strong>Etape 4 </strong>: Le comité d\'évaluation et de bilan' );?>
                        <tbody>
                            <tr class="odd">
                                <th>Diffusion du fond de dossier</th>
                                <th>Lettre invitation CEB + Accusé de réception bilan projet</th>
                                <th>Lettre d'envoi du compte-rendu du CEB</th>
                                <th class="action">Action</th>
                            </tr>
                            <tr>
                                <td><?php echo '';?></td>
                                <td><?php echo '';?></td>
                                <td><?php echo '';?></td>
                                <td><?php echo $xhtml->viewLink(
                                    'Voir le dossier',
                                    array( '#')
                                    );?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="noborder">
                </td>
                <td class="noborder">
                    <table>
                        <?php echo thead( 50, '<strong>Etape 5 </strong>: L\'avis technique de la Cellule technique et la validation du vice-Président' );?>
                        <tbody>
                            <tr class="odd">
                                <th>Note pour la décision au Vice-Président</th>
                                <th>Retour de la décision au Vice-Président</th>
                                <th class="action">Action</th>
                            </tr>
                            <tr>
                                <td><?php echo '';?></td>
                                <td><?php echo '';?></td>
                                <td><?php echo $xhtml->viewLink(
                                    'Voir le dossier',
                                    array( '#')
                                    );?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="noborder">
                </td>
                <td class="noborder">
                    <table>
                        <?php echo thead( 50, '<strong>Etape 6 </strong>: La signature des annexes annuelles année N et année N-1' );?>
                        <tbody>
                            <tr class="odd">
                                <th>Lettre avis de Cellule technique favorable + des annexes vierges à la ville</th>
                                <th>Lettre d'avis Cellule technique défavorable / sous réserve</th>
                                <th>Retour des annexes signées par la ville</th>
                                <th>Transmission au SACG pour cosignature du PV</th>
                                <th>Retour au SACG des annexes cosignées</th>
                                <th>Attribution à la ville</th>
                                <th class="action">Action</th>
                            </tr>
                            <tr>
                                <td><?php echo '';?></td>
                                <td><?php echo '';?></td>
                                <td><?php echo '';?></td>
                                <td><?php echo '';?></td>
                                <td><?php echo '';?></td>
                                <td><?php echo '';?></td>
                                <td><?php echo $xhtml->viewLink(
                                    'Voir le dossier',
                                    array( '#')
                                    );?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="noborder">
                </td>
                <td class="noborder">
                    <table>
                        <?php echo thead( 50, '<strong>Etape 7 </strong>: Versement du solde de l\'année N-1' );?>
                        <tbody>
                            <tr class="odd">
                                <th>Date de la facture</th>
                                <th>Liquidation</th>
                                <th>Lettre d'avis de paiement</th>
                                <th class="action">Action</th>
                            </tr>
                            <tr>
                                <td><?php echo '';?></td>
                                <td><?php echo '';?></td>
                                <td><?php echo '';?></td>
                                <td><?php echo $xhtml->viewLink(
                                    'Voir le dossier',
                                    array( '#')
                                    );?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="noborder">
                </td>
                <td class="noborder">
                    <table>
                        <?php echo thead( 50, '<strong>Etape 8 </strong>: Le bilan semestriel' );?>
                        <tbody>
                            <tr class="odd">
                                <th>Relance grilles semestrielles et comité de suivi</th>
                                <th>Réception du CR du Comité de suivi</th>
                                <th class="action">Action</th>
                            </tr>
                            <tr>
                                <td><?php echo '';?></td>
                                <td><?php echo '';?></td>
                                <td><?php echo $xhtml->viewLink(
                                    'Voir le dossier',
                                    array( '#')
                                    );?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>


<div class="submit"><input value="Retour" type="submit"></div>
        </form></div>
</div>

<div class="clearer"><hr /></div>
<?php /*debug( $details );*/?>