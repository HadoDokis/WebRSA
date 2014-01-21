<?php $this->pageTitle = 'Codes ROME V3'; ?>
<h1>Paramétrage des <?php echo $this->pageTitle;?></h1>

<?php echo $this->Form->create('Coderomev3', array()); ?>
<table class="aere">
    <thead>
        <tr>
            <th>Nom de Table</th>
            <th colspan="2" class="action">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach ($links as $label => $link) {
                echo $this->Xhtml->tableCells(
                    array(
                        h($label),
                        $this->Xhtml->viewLink(
                            'Voir la table', $link, $this->Permissions->check($link['controller'], $link['action'])
                        )
                    ),
                    array('class' => 'odd'),
                    array('class' => 'even')
                );
            }
        ?>
    </tbody>
</table>
<?php echo $this->Default->button(
        'back',
        array(
            'controller' => 'parametrages',
            'action'     => 'index'
        ),
        array(
                'id' => 'Back',
        )
    );
?>
