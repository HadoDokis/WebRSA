<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'typeaideapre66', "Typesaidesapres66::{$this->action}", true )
    )
?>

<script type="text/javascript">
    function toutCocher() {
        $$( 'input[name="data[Pieceaide66][Pieceaide66][]"]' ).each( function( checkbox ) {
            $( checkbox ).checked = true;
        });
    }

    function toutDecocher() {
        $$( 'input[name="data[Pieceaide66][Pieceaide66][]"]' ).each( function( checkbox ) {
            $( checkbox ).checked = false;
        });
    }

    document.observe("dom:loaded", function() {
        Event.observe( 'toutCocher', 'click', toutCocher );
        Event.observe( 'toutDecocher', 'click', toutDecocher );
    });
</script>

<?php
    echo $default->form(
        array(
            'Typeaideapre66.themeapre66_id',
            'Typeaideapre66.name',
            'Typeaideapre66.plafond' => array( 'type' => 'text' ),
            'Pieceaide66.Pieceaide66' => array( 'label' => 'Pièces à fournir', 'multiple' => 'checkbox' , 'options' => $pieceliste, 'empty' => false )
        ),
        array(
            'options' => $options
        )
    );
    echo $form->button( 'Tout cocher', array( 'id' => 'toutCocher' ) );
    echo $form->button( 'Tout décocher', array( 'id' => 'toutDecocher' ) );
?>
