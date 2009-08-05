<?php $this->pageTitle = 'Informations financières';?>

<?php echo $this->element( 'dossier_menu', array( 'id' => $dossier_rsa_id ) );?>

<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>


    <?php if( empty( $infosfinancieres ) ):?>
        <p class="notice">Cette personne ne possède pas encore d'informations financières.</p>

    <?php else:?>
    <fieldset>
    <table>
        <tbody>
            <tr>
                <th>Nom / Prénom</th>
                <td> <?php echo $personne['Personne']['qual'].' '.$personne['Personne']['nom'].' '.$personne['Personne']['prenom'];?> </td>
            </tr>
            <tr>
                <th>NIR</th>
                <td> <?php echo $personne['Personne']['nir'];?> </td>
            </tr>
            <tr>
                <th>Date de naissance</th>
                <td> <?php echo  date_short( $personne['Personne']['dtnai'] );?> </td>
            </tr>
            <tr>
                <th>N° CAF</th>
                <td> <?php echo  $infosfinancieres[0]['Dossier'][0]['matricule'];?> </td> <!-- FIXME: Voir si possibilité changer ces 0 -->
            </tr>
        </tbody>
    </table>
    </fieldset>
        <table id="searchResults" class="tooltips_oupas">
            <thead>
                <tr>
                    <th>Nature de la prestation pour la créance</th>
                    <th>Type d'allocation</th>
                    <th>Suivi</th>
                    <th>Situation des droits</th>
                    <th>Date indus</th>
                    <th>Montant initial de l'indu</th>
                   <!-- <th>Remise CG</th>
                    <th>Montant remboursé</th> -->
                    <th class="action">Action</th>
                    <th class="innerTableHeader">Informations complémentaires</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $infosfinancieres as $index => $indu ):?>
                    <?php

                        $title = $indu['Dossier'][0]['numdemrsa'];

                        echo $html->tableCells(
                            array(
                                h( $type_allocation[$indu['Infofinanciere']['type_allocation']] ),
                                h( $natpfcre[$indu['Infofinanciere']['natpfcre']] ),
                                h( $indu['Dossier'][0]['typeparte'] ),
                                h( $etatdosrsa[$indu['Dossier'][0]['Situationdossierrsa']['etatdosrsa']] ),
                                h( date_short( $indu['Infofinanciere']['dttraimoucompta'] ) ),
                                h( $indu['Infofinanciere']['mtmoucompta'] ),
//                                 h( $indu['Infofinanciere']['mtmoucompta'] ),
//                                 h( $indu['Infofinanciere']['mtmoucompta'] ),
                                $html->viewLink(
                                    'Voir le contrat « '.$title.' »',
                                    array( 'controller' => 'infosfinancieres', 'action' => 'view', $indu['Infofinanciere']['id'] )
                                ),
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