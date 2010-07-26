<h1><?php
        echo $html->tag(
            'h1',
            $this->pageTitle = __d( 'ep', "Eps::{$this->action}", true )
        );
    ?>
</h1>
<?php echo $html->css( array( 'all.form' ), 'stylesheet', array( 'media' => 'all' ), false );?>
<?php
    if( $this->action == 'add' ) {
        echo $xform->create( 'Ep', array( 'type' => 'post', 'url' => Router::url( null, true ) ) ); 
        echo '<div>';
        echo $xform->input( 'Ep.id', array( 'type' => 'hidden' ) );
        echo '</div>';
    }
    else {
        echo $xform->create( 'Ep', array( 'type' => 'post', 'url' => Router::url( null, true ) ) );
        echo '<div>';
        echo $xform->input( 'Ep.id', array( 'type' => 'hidden' ) );
        echo '</div>';
    }

    echo $xform->input( 'Ep.name', array( 'label' => __d( 'ep', 'Ep.name', true ) ) );

?>
<div>
    <?php
        echo $form->input( 'Ep.filtre_zone_geo', array( 'label' => 'Restreindre les zones géographiques', 'type' => 'checkbox' ) );
    ?>
</div>
<fieldset class="col2" id="filtres_zone_geo">
    <legend>Zones géographiques</legend>
        <script type="text/javascript">
            function toutCocher() {
                $$( 'input[name="data[Zonegeographique][Zonegeographique][]"]' ).each( function( checkbox ) {
                    $( checkbox ).checked = true;
                });
            }

            function toutDecocher() {
                $$( 'input[name="data[Zonegeographique][Zonegeographique][]"]' ).each( function( checkbox ) {
                    $( checkbox ).checked = false;
                });
            }

            document.observe("dom:loaded", function() {
                Event.observe( 'toutCocher', 'click', toutCocher );
                Event.observe( 'toutDecocher', 'click', toutDecocher );
                observeDisableFieldsetOnCheckbox( 'EpFiltreZoneGeo', 'filtres_zone_geo', false );
            });
        </script>
    <?php echo $form->button( 'Tout cocher', array( 'id' => 'toutCocher' ) );?>
    <?php echo $form->button( 'Tout décocher', array( 'id' => 'toutDecocher' ) );?>

    <?php
        echo $default->subform(
            array(
                'Zonegeographique.Zonegeographique' => array( 'label' => __d( 'ep', 'Ep.zonegeographique', true ), 'multiple' => 'checkbox', 'options' => $zglist, 'empty' => false ),
            )
        );

    ?>
</fieldset>
<div class="submit">
    <?php
        echo $xform->submit( 'Enregistrer', array( 'div' => false ) );
        echo $xform->submit( 'Annuler', array( 'name' => 'Cancel', 'div' => false ) );
    ?>
</div>
<?php echo $xform->end();?>
