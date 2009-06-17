<?php

    /**
    *
    * Usage: cake/console/cake changelog
    *
    */

    class ChangelogShell extends Shell
    {
        var $_xmlString;

        function main() {
            $lines = array();
            $controllers = array();
            $models = array();
            $views = array();
            exec( 'svn list -R --verbose svn://svn.adullact.net/svnroot/webrsa/trunk/app', &$lines );

            // FIXME: on n'a pas les behaviours, helpers, et components

            foreach( $lines as $line ) {
                $extract = preg_match(
                    '/^ *(?P<revision>[0-9]+) +(?P<user>[^ ]+) +(?P<size>[^ ]+) +(?P<date>[a-z]+ [0-9]+ [0-9]+:[0-9]+) +(?P<file>.+)$/',
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
                            if( !isset( $views[$matches_views[1]] ) || $views[$matches_views[1]]['revision'] < $matches['revision']  ) {
                                $views[$matches_views[1]] = array(
                                    'revision' => $matches['revision'],
                                    'date' => $matches['date']
                                );
                            }
                        }
                    }
                }
            }

            $changelog = array();

            foreach( array( $controllers, $models, $views ) as $parts ) {
                foreach( $parts as $key => $part ) {
                    if( !isset( $changelog[$key] ) || $changelog[$key]['revision'] < $part['revision']  ) {
                        $changelog[$key] = $part;
                    }
                }
            }

            $data = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> <html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr"> <head> <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>Changelog</title></head><body><table><thead><tr><th>Composant</th><th>Révision</th><th>Date</th></tr></thead><tbody>';
            foreach( $changelog as $logpart => $log ) {
                $data .= '<tr><th>'.$logpart.'</th><td>'.$log['revision'].'</td><td>'.$log['date'].'</td></tr>';
            }
            $data .= '</tbody></table></body></html>';
            file_put_contents( 'changelog.html', $data);
        }
    }
?>