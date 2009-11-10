<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<h1><?php echo $this->pageTitle='APRE: Reporting bi-mensuel DDTEFP';?></h1>

<?php
    echo $form->create( 'Repddtefp', array( 'type' => 'post', 'url' => Router::url( null, true ), 'class' => 'noprint' ) );
?>

<?php
    echo $form->input( 'Repddtefp.annee', array( 'label' => 'Année', 'type' => 'select', 'options' => array_range( date( 'Y' ), 1900 ) ) );

    echo $form->input( 'Repddtefp.semestre', array( 'label' => 'Semestre', 'type' => 'select', 'options' => array_range( 1, 2 ) ) );
?>

<?php
    echo $form->input( 'Repddtefp.ville', array( 'label' => __d( 'apre', 'Repddtefp.ville', true ) ) );

    echo $form->submit( 'Calculer' );
    echo $form->end();
?>
<?php if( !empty( $this->data ) && isset( $listeSexe ) && isset( $listeAge ) ) :?>
    <div class="submit noprint">
        <?php echo $form->button( 'Imprimer cette page', array( 'onclick' => 'printit();' ) );?>
    </div>
<?php endif;?>
<?php
    if( !empty( $this->data ) && isset( $listeSexe ) && isset( $listeAge ) ) {

        $annee = Set::extract( $this->data, 'Repddtefp.annee' );
        $semestre = Set::extract( $this->data, 'Repddtefp.semestre' );
        $ville = Set::extract( $this->data, 'Repddtefp.ville' );

        //**************************************************************************

        function lastday($month = '', $year = '') {
            if (empty($month)) {
                $month = date('m');
            }
            if (empty($year)) {
                $year = date('Y');
            }
            $result = strtotime("{$year}-{$month}-01");
            $result = strtotime('-1 second', strtotime('+1 month', $result));
            return date('d', $result);
        }


        function lines( $results, $thisData, $locale ) {
            $rows = array();
            foreach( $results as $key => $repddtefp ) {
                $row = array( '<td>'.__d( 'apre', 'Repddtefp.'.$key, true ).'</td>' );
                /*$type = Set::extract( $types, $key.'.type' );
                $result = Set::extract( $types, $key.'.result' );*/
                for( $i = 1 ; $i <= 6 ; $i++ ) {
                    $mois = ( ( Set::classicExtract( $thisData, 'Repddtefp.semestre' ) - 1 ) * 6  ) + $i;

                    $total = 0;
                    foreach( array( 1, 2 ) as $quinzaine ) {
                        $indicateur = Set::extract( $results, "/{$key}[mois=$mois][quinzaine=$quinzaine]/indicateur" );
                        if( !empty( $indicateur ) ) {
                            $indicateur = $indicateur[0];
                        }
                        else {
                            $indicateur = 0;
                        }
                        $total += $indicateur;
                        $row[] = '<td class="number">'.$locale->number( $indicateur ).'</td>';
                    }
                    $row[] = '<td class="number">'.$locale->number( $total ).'</td>';
                }

                $rows[] = '<tr class="'.( ( ( count( $rows ) + 1 ) % 2 ) == 0 ? 'even' : 'odd' ).'">'.implode( '', $row ).'</tr>';
            }
            return $rows;
        }

        //**************************************************************************
        if( !empty( $ville ) ){
            echo '<h2>Données pour la ville : '.$ville.'</h2>';
        }
        //**************************************************************************

        $headers1 = array();
        $headers2 = array( null );
        for( $i = 1 ; $i <= 6 ; $i++ ) {
            $mois = ( ( Set::classicExtract( $this->data, 'Repddtefp.semestre' ) - 1 ) * 6  ) + $i;
            $headers1[] = ucfirst( $locale->date( '%b %Y', $annee.'-'.( $mois < 10 ? '0'.$mois : $mois ).'-'.'01' ) );
            $headers2[] = '1er au 14';
            $headers2[] = '15 au '.lastday( $mois, $annee );
            $headers2[] = 'Total';
        }

        $headers1 = str_replace( '<th>', '<th colspan="3">', $html->tableHeaders( $headers1 ) );
        $headers1 = str_replace( '<tr>', '<tr><th></th>', $headers1 );

        $headers2 = $html->tableHeaders( $headers2 );
        $headers2 = str_replace( '<tr>', '<tr id="datesApre">', $headers2 );

        //**************************************************************************


        $thead = $html->tag( 'thead', $headers1.$headers2 );
        $tbody = $html->tag( 'tbody', '<tr><th colspan="19">Sexe</th></tr>'.implode( '', lines( $listeSexe, $this->data, $locale ) ) );
        $tbody .= $html->tag( 'tbody', '<tr><th colspan="19">Âge</th></tr>'.implode( '', lines( $listeAge, $this->data, $locale ) ) );
        echo $html->tag( 'table', $thead.$tbody, array( 'id' => 'repddtefp' ) );
    }
?>