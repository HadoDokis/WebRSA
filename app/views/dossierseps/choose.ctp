<div id="tabbedWrapper" class="tabs">
    <div id="dossiers">
    <h1 class="title" class="aere">
        <?php
            echo $this->pageTitle = sprintf(
                'Dossiers à passer dans la commission de l\'EP « %s » du %s',
                $commissionep['Ep']['name'],
                $locale->date( 'Locale->datetime', $commissionep['Commissionep']['dateseance'] )
            );
        ?>
    </h1>
    <br />

        <div id="dossierseps">
        <?php

            if ( isset( $themeEmpty ) && $themeEmpty == true ) {
                echo '<p class="notice">Veuillez attribuer des thèmes à l\'EP gérant la commission avant.</p>';
            }
            else {
// debug($dossiers);
                foreach( $themesChoose as $theme ){

                    echo "<div id=\"$theme\"><h3 class=\"title\">".__d( 'dossierep',  'ENUM::THEMEEP::'.Inflector::tableize( $theme ), true )."</h3>";
                    echo $default2->index(
                        $dossiers[$theme],
                        array(
                            'Dossierep.chosen' => array( 'input' => 'checkbox' ),
                            'Personne.qual',
                            'Personne.nom',
                            'Personne.prenom',
                            'Dossierep.created',
                            'Dossierep.themeep'
                        ),
                        array(
                            'cohorte' => true,
                            'options' => $options,
                            'hidden' => array( 'Dossierep.id', 'Passagecommissionep.id' ),
                            'paginate' => 'Dossierep',
                            'actions' => array( 'Dossierseps::courrierInformation' ),
                            'id' => $theme
                        )
                    );
                    echo "</div>";
        // debug($dossierseps[$theme]);
                }
            }

        ?>
        </div>
    </div>
</div>
<?php

            echo $default->button(
                'back',
                array(
                    'controller' => 'commissionseps',
                    'action'     => 'view',
                    $commissionep_id
                ),
                array(
                    'id' => 'Back'
                )
            );
?>
<?php
    if( Configure::read( 'debug' ) > 0 ) {
        echo $javascript->link( 'prototype.livepipe.js' );
        echo $javascript->link( 'prototype.tabs.js' );
    }
?>

<script type="text/javascript">
    makeTabbed( 'tabbedWrapper', 2 );
    makeTabbed( 'dossierseps', 3 );
</script>
<script type="text/javascript">
    $$( 'td.action a' ).each( function( elmt ) {
        $( elmt ).addClassName( 'external' );
    } );
</script>