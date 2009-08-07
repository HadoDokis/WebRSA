<?php  $this->pageTitle = 'Détails des droits RSA';?>

<?php  echo $this->element( 'dossier_menu', array( 'id' => $dossier_rsa_id ) );?>


<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php if( empty( $detaildroitrsa ) ):?>
        <p class="notice">Ce dossier ne possède pas encore de détails sur les droits.</p>

    <?php else:?>
            <table class="aere">
                <thead>
                    <tr>
                        <th>Domicile fixe</th>
                        <th>Code origine de la demande</th>
                        <th>Date début calcul</th>
                        <th>Date de fin calcul</th>
                        <th>Montant revenu minimum</th>
                        <th>Montant revenu garanti</th>
                        <th>Montant ressources mensuelles</th>
                        <th>Montant total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        echo $html->tableCells(
                            array(
                                h( $topsansdomfixe[$detaildroitrsa['Detaildroitrsa']['topsansdomfixe']]),
                                h( $oridemrsa[$detaildroitrsa['Detaildroitrsa']['oridemrsa']]),
                                h( $locale->date( 'Date::short', $detaildroitrsa['Detaildroitrsa']['ddelecal'] ) ),
                                h( $locale->date( 'Date::short', $detaildroitrsa['Detaildroitrsa']['dfelecal'] ) ),
                                h( $detaildroitrsa['Detaildroitrsa']['mtrevminigararsa'] ),
                                h( $detaildroitrsa['Detaildroitrsa']['mtrevgararsa'] ),
                                h( $detaildroitrsa['Detaildroitrsa']['mtressmenrsa'] ),
                                h( $detaildroitrsa['Detaildroitrsa']['mttotdrorsa'] ),
                            ),
                            array( 'class' => 'odd' ),
                            array( 'class' => 'even' )
                        );
                    ?>
                </tbody>
            </table>

            <h2>Détails des calculs</h2>
            <table class="tooltips">
                <thead>
                    <tr>
                        <th>Nature de la prestation</th>
                        <th>Sous nature de la prestation</th>
                        <th>Date de la nature</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach( $detaildroitrsa['Detailcalculdroitrsa'] as $detailcalcul ):?>
                        <?php
                            echo $html->tableCells(
                                array(
                                    h( $natpf[$detailcalcul['natpf']]),
                                    h( $sousnatpf[$detailcalcul['sousnatpf']]),
                                    h( $locale->date( 'Date::short', $detailcalcul['ddnatdro'] ) ),
                                ),
                                array( 'class' => 'odd' ),
                                array( 'class' => 'even' )
                            );
                        ?>
                    <?php endforeach;?>
                </tbody>
            </table>
    <?php endif;?>
</div>
<div class="clearer"><hr /></div>
