<?php  $this->pageTitle = 'Avis du Président du Conseil Général sur les droits RSA';?>

<?php  echo $this->element( 'dossier_menu', array( 'id' => $dossier_rsa_id ) );?>


<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php if( empty( $avispcgdroitrsa ) ):?>
        <p class="notice">Ce dossier ne possède pas encore d'avis.</p>

    <?php else:?>
            <table class="aere">
                <thead>
                    <tr>
                        <th>Avis  </th>
                        <th>Date avis  </th>
                        <th>Nom agent </th>
                        <th>Type personne</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        echo $html->tableCells(
                            array(
                                h( ( trim( Set::extract( $avispcgdroitrsa, 'Avispcgdroitrsa.avisdestpairsa' ) )  != '' ) ? $avisdestpairsa[$avispcgdroitrsa['Avispcgdroitrsa']['avisdestpairsa']] : null ),
                                h( $locale->date( 'Date::short', $avispcgdroitrsa['Avispcgdroitrsa']['dtavisdestpairsa'] ) ),
                                h( ( Set::extract( $avispcgdroitrsa, 'Avispcgdroitrsa.nomtie' ) != '' ) ? $avispcgdroitrsa['Avispcgdroitrsa']['nomtie'] : null ),
                                h( ( Set::extract( $avispcgdroitrsa, 'Avispcgdroitrsa.typeperstie' ) != '' ) ?$typeperstie[$avispcgdroitrsa['Avispcgdroitrsa']['typeperstie']] : null ),
                            ),
                            array( 'class' => 'odd' ),
                            array( 'class' => 'even' )
                        );
                    ?>
                </tbody>
            </table>

            <h2>Condition administrative</h2>
            <table class="aere">
                <thead>
                    <tr>
                        <th>Avis  </th>
                        <th>Avis cond admin  </th>
                        <th>Motif </th>
                        <th>Commentaire 1</th>
                        <th>Commentaire 2</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach( $avispcgdroitrsa['Condadmin'] as $condadmin ):?>
                        <?php
                            echo $html->tableCells(
                                array(
                                    h( ( trim( Set::extract( $avispcgdroitrsa, 'Avispcgdroitrsa.avisdestpairsa' ) ) != '' ) ?$avisdestpairsa[$avispcgdroitrsa['Avispcgdroitrsa']['avisdestpairsa']] : null ),
                                    h( $aviscondadmrsa[$condadmin['aviscondadmrsa']]),
                                    h( $condadmin['moticondadmrsa']),
                                    h( $condadmin['comm1condadmrsa']),
                                    h( ( Set::extract( $condadmin, 'Condadmin.comm2condadmrsa' ) != '' ) ? $condadmin['comm2condadmrsa'] : null ),
                                    h( $condadmin['dteffaviscondadmrsa']),
                                ),
                                array( 'class' => 'odd' ),
                                array( 'class' => 'even' )
                            );
                        ?>
                    <?php endforeach;?>
                </tbody>
            </table>
            <h2>Réduction RSA</h2>
            <?php if ( empty( $avispcgdroitrsa['Reducrsa'] ) ):?>
                <p>Pas de réduction RSA.</p>
            <?php else:?>
                <table class="aere">
                    <thead>
                        <tr>
                            <th>Montant de la réduction</th>
                            <th>Date de début</th>
                            <th>Date de fin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach( $avispcgdroitrsa['Reducrsa'] as $reducrsa ):?>

                                <?php
                                    echo $html->tableCells(
                                        array(
                                            h( $reducrsa['mtredrsa']),
                                            h( $reducrsa['ddredrsa']),
                                            h( $reducrsa['dfredrsa']),
                                        ),
                                        array( 'class' => 'odd' ),
                                        array( 'class' => 'even' )
                                    );
                                ?>
                        <?php endforeach;?>
                    </tbody>
                </table>
            <?php endif;?>
    <?php endif;?>
</div>
<div class="clearer"><hr /></div>
