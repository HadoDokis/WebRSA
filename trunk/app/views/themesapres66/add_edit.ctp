<?php
    echo $xhtml->tag(
        'h1',
        $this->pageTitle = __d( 'themeapre66', "Themesapres66::{$this->action}", true )
    )
?>
<?php
    echo $default->form(
        array(
            'Themeapre66.name' => array('required' => true),
        )
    );
?>