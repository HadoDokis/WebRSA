<?php  $this->pageTitle = 'Informations ETI de la personne';?>

<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id ) );?>


<div class="with_treemenu">
    <h1><?php echo $this->pageTitle;?></h1>

    <?php if( empty( $informationeti ) ):?>
        <p class="notice">Cette personne ne possède pas encore d'informations ETI.</p>

    <?php else:?>
            <table class="tooltips">
                <thead>
                    <tr>
                    <th colspan="3">Activité ETI</th>
        <!--                        <th>Bénéficiaire ACCRE</th>
                                <th>Activité eti</th>-->
                    <th colspn="3">Employés</th>
        <!--			<th>Date</th>
                    <th>Date</th>
                    <th>Date</th>-->
                    <th colspn="3">Chiffre d'affaire</th>
        <!--			<th>Date</th>
                    <th>Date</th>
                    <th>Date</th>
                    <th>Date</th>-->
                    <th colspn="9">Eléménts fiscaux</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                        echo $html->tableCells(
                            array(
                                h( $topcreaentre[$informationeti['Informationeti']['topcreaentre']]),	
                                h( $topaccre[$informationeti['Informationeti']['topaccre']]),
                                h( $acteti[$informationeti['Informationeti']['acteti']]),
                                h( $topempl1ax[$informationeti['Informationeti']['topempl1ax']] ) ,
                                h( $topstag1ax[$informationeti['Informationeti']['topstag1ax']] ) ,
                                h( $topsansempl[$informationeti['Informationeti']['topsansempl']] ) ,
                                h( $informationeti['Informationeti']['ddchiaffaeti'] ) ,
                                h( $informationeti['Informationeti']['dfchiaffaeti'] ) ,
                                h( $informationeti['Informationeti']['mtchiaffaeti'] ) ,
                                h( $regfiseti[$informationeti['Informationeti']['regfiseti']] ) ,
                                h( $topbeneti[$informationeti['Informationeti']['topbeneti']] ) ,
                                h( $regfisetia1[$informationeti['Informationeti']['regfisetia1']] ) ,
                                h( $informationeti['Informationeti']['mtbenetia1'] ) ,
                                h( $informationeti['Informationeti']['mtamoeti'] ) ,
                                h( $informationeti['Informationeti']['mtplusvalueti'] ) ,
                                h( $topevoreveti[$informationeti['Informationeti']['topevoreveti']] ) ,
                                h( $informationeti['Informationeti']['libevoreveti'] ) ,
                                h( $topressevaeti[$informationeti['Informationeti']['topressevaeti']] ),
                            ),
                            array( 'class' => 'odd' ),
                            array( 'class' => 'even' )
                        );
                    ?>
                </tbody>
            </table>
    <?php endif;?>
</div>
<div class="clearer"><hr /></div>
