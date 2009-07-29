<?php $this->pageTitle = 'Liste des indus';?>

<?php echo $this->element( 'dossier_menu', array( 'id' => $dossier_rsa_id ) );?>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>


    <?php if( empty( $infosfinancieres ) ):?>
        <p class="notice">Cette personne ne possède pas encore d'informations financières.</p>

    <?php else:?>
        <table id="searchResults" class="tooltips_oupas">
            <thead>
                <tr>
                    <th>NIR</th>
                    <th>Nom de l'allocataire</th>
                    <th>Suivi</th>
                    <th>Situation des droits</th>
                    <th>Date indus</th>
                    <th>Montant initial de l'indu</th>
                    <th>Remise CG</th>
                    <th>Montant remboursé</th>
                    <th class="action">Action</th>
                    <th class="innerTableHeader">Informations complémentaires</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $infosfinancieres as $index => $indu ):?>
                    <?php
                    $innerTable = '<table id="innerTable'.$index.'" class="innerTable">
                        <tbody>
                            <tr>
                                <th>Date naissance</th>
                                <td>'.h( date_short( $personne['Personne']['dtnai'] ) ).'</td>
                            </tr>
                            <tr>
                                <th>Numéro CAF</th>
                                <td>'.h( $indu['Dossier'][0]['matricule'] ).'</td>
                            </tr>
                        </tbody>
                    </table>';
                        $title = $indu['Dossier'][0]['numdemrsa'];

                        echo $html->tableCells(
                            array(
                                h( $personne['Personne']['nir'] ),
                                h( $personne['Personne']['nom'].' '.$personne['Personne']['prenom'] ),
                                h( $indu['Dossier'][0]['typeparte'] ), //h( $typeparte[$indu['Dossier']['typeparte']] ),
                                h( $etatdosrsa[$indu['Dossier'][0]['Situationdossierrsa']['etatdosrsa']] ),
                                h( date_short( $indu['Infofinanciere']['dttraimoucompta'] ) ),
                                h( $indu['Infofinanciere']['mtmoucompta'] ),
                                h( $indu['Infofinanciere']['mtmoucompta'] ),
                                h( $indu['Infofinanciere']['mtmoucompta'] ),
                                $html->viewLink(
                                    'Voir le contrat « '.$title.' »',
                                    array( 'controller' => 'infosfinancieres', 'action' => 'view', $indu['Infofinanciere']['id'] )
                                ),
                                array( $innerTable, array( 'class' => 'innerTableCell' ) )
                            ),
                        array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
                        array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
                        );
                    ?>
                <?php endforeach;?>
            </tbody>
        </table>
    <?php endif?>

</div>
<div class="clearer"><hr /></div>