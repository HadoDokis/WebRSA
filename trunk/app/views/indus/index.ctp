<?php $this->pageTitle = 'Liste des indus';?>

<?php echo $this->element( 'dossier_menu', array( 'id' => $dossier_rsa_id, 'personne_id' => $infofinanciere['Personne']['id'] ) );?>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>


    <?php if( empty( $infofinanciere ) ):?>
        <p class="notice">Ce dossier ne possède pas d'indus.</p>

    <?php else:?>
        <table id="searchResults" class="tooltips">
            <thead>
                <tr>
                    <th>NIR</th>
                    <th>Nom de l'allocataire</th>
                    <th>Suivi</th>
                    <th>Situation des droits</th>
                    <th>Date indus</th>
                    <th>Montant initial de l'indu</th>
                    <th>Remise</th>
                    <th>Montant remboursé</th>
                    <th>Solde du</th>
                    <th class="action">Action</th>
                    <th class="innerTableHeader">Informations complémentaires</th>
                </tr>
            </thead>
            <tbody>
                    <?php
                        echo $html->tableCells(
                            array(
                                h( $infofinanciere['Personne']['nir'] ),
                                h( $infofinanciere['Personne']['nom'].' '.$infofinanciere['Personne']['prenom'] ),
                                h( $infofinanciere['Dossier']['typeparte'] ), //h( $typeparte[$indu['Dossier']['typeparte']] ),
                                h( $etatdosrsa[$infofinanciere['Situationdossierrsa']['etatdosrsa']] ),
                                $locale->date( 'Date::short', $infofinanciere['Infofinanciere']['moismoucompta'] ),
//                                 $locale->money( $indu[0]['mt_allocation_comptabilisee'] ),
                                $locale->money( $infofinanciere[0]['mt_indu_constate'] ),
                                $locale->money( $infofinanciere[0]['mt_remises_indus'] ),
                                $locale->money( $infofinanciere[0]['mt_indus_transferes_c_g'] ),
                                $locale->money( $infofinanciere[0]['mt_annulations_faible_montant'] ),
//                                 $locale->money( $indu[0]['mt_autre_annulation'] ),
                                $html->viewLink(
                                    'Détails d\'indu',
                                    array( 'controller' => 'indus', 'action' => 'view', $infofinanciere['Dossier']['id'] )
                                ),
                            ),
                        array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$infofinanciere ),
                        array( 'class' => 'even', 'id' => 'innerTableTrigger'.$infofinanciere )
                        );
                    ?>
            </tbody>
        </table>
    <?php endif;?>
</div>
<div class="clearer"><hr /></div>