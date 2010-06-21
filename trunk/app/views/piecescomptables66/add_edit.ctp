<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'piececomptable66', "Piecescomptables66::{$this->action}", true )
    )
?>
<?php
    echo $default->form(
        array(
            'Piececomptable66.name' => array('required' => true)
        )
    );
?>