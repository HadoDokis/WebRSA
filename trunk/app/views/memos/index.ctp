<?php  $this->pageTitle = 'Mémos concernant la personne';?>
<?php  echo $this->element( 'dossier_menu', array( 'personne_id' => $personne_id) );?>

<div class="with_treemenu">
    <h1>Mémos</h1>

        <?php
            echo $default->index(
                $memos,
                array(
                    'Memo.name',
                    'Memo.created',
                    'Memo.modified'
                ),
                array(
                    'actions' => array(
                        'Memo.edit',
                        'Memo.delete'
                    ),
                    'add' => array( 'Memo.add' => $personne_id )
                )
            )
        ?>

</div>
<div class="clearer"><hr /></div>