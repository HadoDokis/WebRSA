<?php $this->pageTitle = 'Informations financières';?>

<?php echo $this->element( 'dossier_menu', array( 'id' => $dossier_id ) );?>

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
                <td> <?php echo  $infosfinancieres[0]['Dossier']['matricule'];?> </td> <!-- FIXME: Voir si possibilité changer ces 0 -->
            </tr>
        </tbody>
    </table>
    </fieldset>
        <table id="searchResults" class="tooltips">
            <thead>
                <tr>
                    <th>Mois des mouvements</th>
                    <th>Type d'allocation</th>
                    <th>Nature de la prestation pour la créance</th>
                    <th>Montant</th>
                    <th class="action">Action</th>
                    <th class="innerTableHeader">Informations complémentaires</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $infosfinancieres as $index => $indu ):?>
                    <?php
//                         $even = true;
//                         $rowspan = 1;
//                         for( $i = $index + 1 ; $i < count( $indu ) ; $i++ ) {
//                             if( Set::extract( $indu, 'Infofinanciere.type_allocation' ) == Set::extract( $infosfinancieres, $index.'.Infofinanciere.type_allocation' ) )
//                                 $rowspan++;
//                         }
//                         if( Set::extract( $infosfinancieres, ( $index-1 ).'.Infofinanciere.type_allocation' ) != Set::extract( $infosfinancieres, $index.'.Infofinanciere.type_allocation' ) ) {
//                             if( $rowspan == 1 ) {
//                                 $even = !$even;
//                                 echo $xhtml->tableCells(
//                                     array(
//                                         h( $locale->date( 'Date::miniLettre', $indu['Infofinanciere']['moismoucompta'] ) ),
//                                         h( $type_allocation[$indu['Infofinanciere']['type_allocation']] ),
//                                         h( $natpfcre[$indu['Infofinanciere']['natpfcre']] ),
//                                         h( $locale->money( $indu['Infofinanciere']['mtmoucompta'] ) ),
//                                         $xhtml->viewLink(
//                                             'Voir le contrat',
//                                             array( 'controller' => 'infosfinancieres', 'action' => 'view', $indu['Infofinanciere']['id'] )
//                                         ),
//                                     ),
//                                     array( 'class' => 'odd', 'id' => 'innerTableTrigger'.$index ),
//                                     array( 'class' => 'even', 'id' => 'innerTableTrigger'.$index )
//                                 );
//                             }
//                             else{
// //                              $even = !$even;
//                                 echo '<tr class="'.( $even ? 'even' : 'odd' ).'">
//                                         <td rowspan="'.$rowspan.'">'.h( $locale->date( 'Date::miniLettre', $indu['Infofinanciere']['moismoucompta'] ) ).'</td>
//                                         <td rowspan="'.$rowspan.'">'.h( $type_allocation[$indu['Infofinanciere']['type_allocation']] ).'</td>
//                                         <td>'.h( $natpfcre[$indu['Infofinanciere']['natpfcre']] ).'</td>
//                                         <td>'.h( $locale->money( $indu['Infofinanciere']['mtmoucompta'] ) ).'</td>
//
//                                         <td>'.$xhtml->viewLink(
//                                             'Voir l\'indu',
//                                             array( 'controller' => 'infosfinancieres', 'action' => 'view', $indu['Infofinanciere']['id'] ),
//                                             $permissions->check( 'infosfinancieres', 'view' )
//                                         ).'</td>
//                                     </tr>';
//                             }
//                         }
//                         else{
//                             echo '<tr class="'.( $even ? 'even' : 'odd' ).'">
//                                     <td>'.h( $natpfcre[$indu['Infofinanciere']['natpfcre']] ).'</td>
//                                     <td>'.h( $locale->money( $indu['Infofinanciere']['mtmoucompta'] ) ).'</td>
//
//                                 </tr>';
//                         }

                        echo $xhtml->tableCells(
                            array(
                                h( $locale->date( 'Date::miniLettre', $indu['Infofinanciere']['moismoucompta'] ) ),
                                h( $type_allocation[$indu['Infofinanciere']['type_allocation']] ),
                                h( $natpfcre[$indu['Infofinanciere']['natpfcre']] ),
                                h(  $locale->money( $indu['Infofinanciere']['mtmoucompta'] ) ),
                                $xhtml->viewLink(
                                    'Voir l\'indu',
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