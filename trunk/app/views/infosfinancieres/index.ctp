<?php  $this->pageTitle = 'Informations financières du foyer';?>

<?php  echo $this->element( 'dossier_menu', array( 'id' => $dossier_rsa_id ) );?>


<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php if( empty( $infosfinancieres ) ):?>
        <p class="notice">Ce foyer ne possède pas encore d'informations financières.</p>
    <?php endif;?>

<!--    <ul class="actionMenu">
        <?php
            echo '<li>'.$html->addLink(
                'Ajouter une information financière',
                array( 'controller' => 'infosfinancieres', 'action' => 'add', $personne_id )
            ).' </li>';
        ?>
    </ul>-->

    <?php if( !empty( $infosfinancieres ) ):?>
        <table class="tooltips">
            <thead>
                <tr>
                    <th>Mois des mouvements </th>
                    <th>Type d'allocation</th>
                    <th>Nature de la prestation</th>
                    <th>Montant</th>
<!--                    <th>Date </th>-->
                    <th colspan="2" class="action">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $infosfinancieres as $infofinanciere ):?>
                    <?php
                        $title = implode( ' ', array(
                            $infofinanciere['Infofinanciere']['moismoucompta'] ,
                            $infofinanciere['Infofinanciere']['type_allocation'] ,
                            $natpfcre[$infofinanciere['Infofinanciere']['natpfcre']] ,
                            $infofinanciere['Infofinanciere']['mtmoucompta'] ,
//                             $infofinanciere['Infofinanciere']['dttraimoucompta'] ,
                        ));

                        echo $html->tableCells(
                            array(
                                h( strftime('%B %Y', strtotime( $infofinanciere['Infofinanciere']['moismoucompta'] ) ) ) ,
                                h( $type_allocation[$infofinanciere['Infofinanciere']['type_allocation']]),
                                h( $natpfcre[$infofinanciere['Infofinanciere']['natpfcre']]),
                                h( $infofinanciere['Infofinanciere']['mtmoucompta'] ),
//                                 h( date_short( $infofinanciere['Infofinanciere']['dttraimoucompta'] ) ),
                                $html->viewLink(
                                    'Voir les informations financières',
                                    array( 'controller' => 'infosfinancieres', 'action' => 'view', $infofinanciere['Infofinanciere']['id'])
                                ),
//                                 $html->editLink(
//                                     'Éditer les informations financières ',
//                                     array( 'controller' => 'infosfinancieres', 'action' => 'edit', $infofinanciere['Infofinanciere']['id'] )
//                                 )

                            ),
                            array( 'class' => 'odd' ),
                            array( 'class' => 'even' )
                        );
                    ?>
                <?php endforeach;?>
            </tbody>
        </table>
    <?php  endif;?>
</div>
<div class="clearer"><hr /></div>
