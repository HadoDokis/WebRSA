<?php  $this->pageTitle = 'Evènements liés au foyer';?>
<?php echo $this->element( 'dossier_menu', array( 'foyer_id' => $foyer_id ) );?>

<div class="with_treemenu">
    <h1>Evènements</h1>

    <?php if( empty( $foyers_evenements ) ):?>
        <p class="notice">Ce foyer ne possède pas encore d'évènements.</p>
    <?php endif;?>
    <?php if( !empty( $foyers_evenements ) ):?>
        <table class="tooltips">
            <thead>
                <tr>
                    <th>Date de liquidation</th>
                    <th>Heure de liquidation</th>
                    <th>Fait générateur</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach( $foyers_evenements as $foyer_evenement ) {

                        echo $html->tableCells(
                            array(
                                h( $locale->date( 'Date::short', Set::classicExtract( $foyer_evenement, 'Evenement.dtliq' ) ) ),
                                h( $locale->date( 'Time::short', Set::classicExtract( $foyer_evenement, 'Evenement.heuliq' ) ) ),
                                h( Set::enum( Set::classicExtract( $foyer_evenement, 'Evenement.fg' ), $fg ) )
                            ),
                            array( 'class' => 'odd' ),
                            array( 'class' => 'even' )
                        );
                    }
                ?>
            </tbody>
        </table>
    <?php  endif;?>

</div>
<div class="clearer"><hr /></div>