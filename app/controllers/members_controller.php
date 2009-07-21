<?php

class MembersController extends AppController
{
    var $helpers = array( 'Xls' );


    function export()
    {
//         $data = $this->Personne->find('all');

        $this->set(compact('data'));

        $filename  = 'export_' . strftime('%Y-%m-%d') . '.xls';

//         $this->autoLayout = false;

//         App::import('Core', 'File');

        $file = new File('..'.DS.'exports'.DS.$filename, true);
        $file->write($this->render());
        $file->close();

//         $this->Session->setFlash("Nouveau fichier disponible.", 'message_ok');
//         $this->redirect($this->referer());
        $this->Session->destroy();
    }

    function exports_index()
    {
        App::import('Core', 'Folder');

        $dir = new Folder('..'.DS.'exports');

        $data = $dir->find('.+\.xls');

        rsort($data);

        $this->set(compact('data'));
    }


    function export_download($filename)
    {
        $this->view = 'Media';

        $params = array(
            'path'      => 'exports' . DS,
            'id'        => $filename,
            'name'      => substr($filename, 0, strpos($filename, '.xls')),
            'extension' => 'xls',
            'download'  => true
        );

        $this->set($params);
    }
/*
    function delete_file($filename)
    {
        App::import('Core', 'File');

        $file = new File('..' . DS . 'exports' . DS . $filename);

        if(!$file->delete())
        {
            $this->Session->setFlash("Impossible de supprimer le fichier '{$filename}'.", 'message_error');
        }
        else
        {
            $this->Session->setFlash("Fichier '{$filename}' supprimé.", 'message_ok');
        }

        $this->redirect($this->referer());
    } */
}
?>