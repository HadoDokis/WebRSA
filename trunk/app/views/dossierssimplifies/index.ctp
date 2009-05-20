<?php $this->pageTitle = 'Edition Préconisation d\'orientation';?>
<?php echo $this->element( 'dossier_menu', array( 'id' => $dossier['Dossier']['id'] ) );?>


<h1>Edition Préconisation d'orientation</h1>

<div class="with_treemenu">
    
    <ul class="actionMenu">
        <?php 
            echo $html->printLink(
                'Editer une notification pour cette préconisation',
                array( 'controller' => 'gedooos', 'action' => 'notification_structure', $dossier['Dossier']['id']) 
            );
        ?>
    </ul>
</div>
<div class="clearer"><hr /></div>