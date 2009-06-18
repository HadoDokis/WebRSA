<?php

    /**
    *
    * Usage: cake/console/cake changematrix
    *
    * TODO
    *   * denière révision/date pour
    *       - l'ensemble
    *       - chaque "composant"
    *   * on n'a pas les behaviours, helpers, et components
    *   * continuer la conversion dates fr / dates en ou trouver un autre système
    *
    */

    function datetime_short( $date ) {
        $date = strtolower( $date );
        $date = str_replace(
            array(
                'avr',
                'mai',
            ),
            array(
                'apr',
                'may',
            ),
            $date
        );
        return strftime( '%d/%m/%Y %H:%M', strtotime( $date ) );
    }

    class ChangematrixShell extends Shell
    {
        function main() {
            $svnUrl = 'svn://svn.adullact.net/svnroot/webrsa/trunk/app';
            $lines = array();
            $controllers = array();
            $models = array();
            $views = array();
            // $lines = file( '/home/cbuffin/projets/htdocs/cakephp/default/1.2.3.8166/svnlist.txt' );
            $hasList = @exec( 'svn list -R --verbose '.$svnUrl, &$lines );
            // FIXME: if( !$hasList ) ...

            if( $hasList ) {
                foreach( $lines as $line ) {
                    $extract = preg_match(
                        '/^ *(?P<revision>[0-9]+) +(?P<user>[^ ]+) +(?P<size>[^ ]+) +(?P<date>[a-z]+ [0-9]+ [0-9]+:[0-9]+) +(?P<file>.+)$/i',
                        $line,
                        $matches
                    );

                    if( $extract ) {
                        if( substr( $matches['file'], -1) != '/' ) {
                            // Controller
                            if( ( $matches['file'] != 'controllers/app_controller.php' ) && preg_match( '/^controllers\/([^\/]+)_controller.php$/', $matches['file'], $matches_controllers ) ) {
                                $controllers[$matches_controllers[1]] = array(
                                    'revision' => $matches['revision'],
                                    'date' => $matches['date']
                                );
                            }

                            // Models
                            if( ( $matches['file'] != 'models/app_model.php' ) && preg_match( '/^models\/([^\/]+).php$/', $matches['file'], $matches_models ) ) {
                                $models[Inflector::pluralize( $matches_models[1] )] = array(
                                    'revision' => $matches['revision'],
                                    'date' => $matches['date']
                                );
                            }

                            // Views
                            if( preg_match( '/^views\/([^\/]+)\/([^\/]+).ctp/', $matches['file'], $matches_views ) ) {
                                // Si n'existe pas ou est plus récent
                                if( !isset( $views[$matches_views[1]] )  ) {
                                    $views[$matches_views[1]] = array();
                                }
                                $views[$matches_views[1]][$matches_views[2]] = array(
                                    'revision' => $matches['revision'],
                                    'date' => $matches['date']
                                );
                            }
                        }
                    }
                }

                $indexes = array_unique( Set::merge( array_keys( $controllers ), array_keys( $models ), array_keys( $views ) ) );
                sort( $indexes );

                $data = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
                        <html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">
                            <head>
                                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                                <title>Changematrix</title>
                                <style type="text/css" media="all">
                                    body { font-size: 12px; }
                                    table { border-collapse: collapse; }
                                    th, td { border: 1px solid black; vertical-align: top; padding: 0.125em 0.25em; }
                                    table, thead, tbody, colgroup { border: 3px solid black; }
                                    tbody th { text-align: left; }
                                    h1 { font-weight: normal; }
                                    td.number { text-align: right; }
                                </style>
                            </head>
                            <body>
                                <h1>MVC</h1>
                                <table>
                                    <colgroup span="1" />
                                    <colgroup span="2" />
                                    <colgroup span="2" />
                                    <colgroup span="3" />
                                    <thead>
                                        <tr>
                                            <th>Composant</th>
                                            <th colspan="2">Models</th>
                                            <th colspan="2">Controllers</th>
                                            <th colspan="3">Views</th>
                                        </tr>
                                    </thead>';
                foreach( $indexes as $index ) {
                    $viewsCells = array();
                    if( isset( $views[$index] ) && is_array( $views[$index] ) ) {
                        ksort( $views[$index] );
                        foreach( $views[$index] as $key => $value ) {
                            $viewsCells[] = '<td>'.$key.'</td>
                                            <td class="number">'.$value['revision'].'</td>
                                            <td>'.datetime_short( $value['date'] ).'</td>';
                        }
                    }
                    $rowspan = max( 1, count( $viewsCells ) );

                    if( isset( $models[$index] ) ) {
                        $modelCell = '<td rowspan="'.$rowspan.'" class="number">'.$models[$index]['revision'].'</td>
                                    <td rowspan="'.$rowspan.'">'.datetime_short( $models[$index]['date'] ).'</td>';
                    }
                    else {
                        $modelCell = '<td rowspan="'.$rowspan.'" class="number" colspan="2">N/A</td>';
                    }

                    if( isset( $controllers[$index] ) ) {
                        $controllerCell = '<td rowspan="'.$rowspan.'" class="number">'.$controllers[$index]['revision'].'</td>
                                    <td rowspan="'.$rowspan.'">'.datetime_short( $controllers[$index]['date'] ).'</td>';
                    }
                    else {
                        $controllerCell = '<td rowspan="'.$rowspan.'" class="number" colspan="2">N/A</td>';
                    }

                    $data .= '<tbody>';
                    $data .= '<tr>
                            <th rowspan="'.$rowspan.'">'.$index.'</th>
                            '.$modelCell.'
                            '.$controllerCell.'
                            '.( count( $viewsCells ) > 0 ? $viewsCells[0] : '<td colspan="3" class="number">N/A</td>' ).'
                        </tr>';
                    if( count( $viewsCells ) > 1 ) {
                        for( $i = 1 ; $i < count( $viewsCells ) ; $i++ ) {
                            $data .= '<tr>'.$viewsCells[$i].'</tr>';
                        }
                    }
                    $data .= '</tbody>';
                }
                $data .= '</table></body></html>';
                file_put_contents( 'changematrix.html', $data);
            }
            else {
                echo 'Erreur: impossible d\'obtenir '.$svnUrl;
            }
        }
    }
?>