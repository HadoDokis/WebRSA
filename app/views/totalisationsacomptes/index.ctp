<?php  $this->pageTitle = 'Totalisations des acomptes';?>

<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>


<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php if( empty( $totsacoms ) ):?>
        <p class="notice">Ce foyer ne possède pas encore de totalisations d'acomptes.</p>

    <?php else: ?>
        <table class="tooltips">
            <thead>
                <tr>
                    <th>Type de totalisation </th>
                    <th>Montant total Rsa socle</th>
                    <th>Montant total Rsa socle majoré</th>
                    <th>Montant total Rsa local</th>
                    <th>Montant total</th>
<!--                    <th colspan="2" class="action">Actions</th>-->
                </tr>
            </thead>
            <tbody>
                <?php foreach( $totsacoms as $totacom ) :?>
                    <?php
                        $title = implode( ' ', array(
                            $totacom['Totalisationacompte']['type_totalisation'] ,
                            $totacom['Totalisationacompte']['mttotsoclrsa'] ,
                            $totacom['Totalisationacompte']['mttotsoclmajorsa'] ,
                            $totacom['Totalisationacompte']['mttotlocalrsa'] ,
                            $totacom['Totalisationacompte']['mttotrsa'] ,
                        ));

                        echo $html->tableCells(
                            array(
                                h( $type_totalisation[$totacom['Totalisationacompte']['type_totalisation']] ),
                                h( $totacom['Totalisationacompte']['mttotsoclrsa'] ),
                                h( $totacom['Totalisationacompte']['mttotsoclmajorsa']),
                                h( $totacom['Totalisationacompte']['mttotlocalrsa'] ),
                                h( $totacom['Totalisationacompte']['mttotrsa'] ) ,
//                                 $html->viewLink(
//                                     'Voir les informations financières',
//                                     array( 'controller' => 'totalisationsacomptes', 'action' => 'view', $totacom['Totalisationacompte']['id'] ) )
                                ),
                            array( 'class' => 'odd' ),
                            array( 'class' => 'even' )
                        );
                    ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php  endif;?>
</div>
<div class="clearer"><hr /></div>
