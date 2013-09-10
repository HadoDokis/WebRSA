<?php
/**
 * Code source de la classe Rupturescuis66Controller.
 *
 * PHP 5.3
 *
 * @package app.Controller
 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
 */
App::import( 'Behaviors', 'Occurences' );

/**
 * La classe Rupturescuis66Controller ...
 *
 * @package app.Controller
 */
class Rupturescuis66Controller extends AppController
{
    /**
     * Nom du contrôleur.
     *
     * @var string
     */
    public $name = 'Rupturescuis66';

    /**
     * Components utilisés par ce contrôleur.
     *
     * @var array
     */
    public $components = array( 'Default', 'DossiersMenus', 'Jetons2' );

    /**
     * Correspondances entre les méthodes publiques correspondant à des
     * actions accessibles par URL et le type d'action CRUD.
     *
     * @var array
     */
    public $crudMap = array(
        'add' => 'create',
        'edit' => 'update'
    );

    public $commeDroit = array(
        'view' => 'Rupturescuis66:index',
        'add' => 'Rupturescuis66:edit'
    );


    /**
     *  Liste des options envoyées à la vue
     */
    protected function _setOptions() {
        $options = array();
        $options = $this->Rupturecui66->enums();

        $listeMotifsrupturescuis66 = $this->Rupturecui66->Motifrupturecui66->find(
            'list',
            array(
                'order' => array( 'Motifrupturecui66.name ASC' )
            )
        );

        $this->set( compact( 'options', 'listeMotifsrupturescuis66' ) );
    }

    /**
     * Formulaire d'ajout d'un élémént.
     *
     * @return void
     */
    public function add() {
        $args = func_get_args();
        call_user_func_array( array( $this, 'edit' ), $args );
    }

    /**
     * Formulaire de modification d'un <élément>.
     *
     * @throws NotFoundException
     */
    public function edit( $cui_id = null ) {

        $personne_id = $this->Rupturecui66->Cui->personneId( $cui_id );
        $this->set( 'dossierMenu', $this->DossiersMenus->getAndCheckDossierMenu( array( 'personne_id' => $personne_id ) ) );

        $cui = $this->Rupturecui66->Cui->find(
            'first',
            array(
                'conditions' => array(
                    'Cui.id' => $cui_id
                ),
                'contain' => false
            )
        );

        $rupturecui66 = $this->Rupturecui66->find(
            'first',
            array(
                'conditions' => array(
                    'Rupturecui66.cui_id' => $cui_id
                ),
                'contain' => array(
                    'Motifrupturecui66'
                )
            )
        );

        // On récupère l'utilisateur connecté et qui exécute l'action
        $userConnected = $this->Session->read( 'Auth.User.id' );
        $this->set( compact( 'userConnected' ) );

        $dossier_id = $this->Rupturecui66->Cui->Personne->dossierId( $personne_id );
        $this->assert( !empty( $dossier_id ), 'invalidParameter' );

        $this->Jetons2->get( $dossier_id );

        // Retour à la liste en cas d'annulation
        if( !empty( $this->request->data ) && isset( $this->request->data['Cancel'] ) ) {
            $this->Jetons2->release( $dossier_id );
            $this->redirect( array( 'controller' => 'cuis', 'action' => 'index', $personne_id ) );
        }

        if( !empty( $this->request->data ) ) {
            $this->{$this->modelClass}->begin();
            $this->{$this->modelClass}->create( $this->request->data );

            $decisioncui = $cui['Cui']['decisioncui'];
            if( $decisioncui == 'enattente' ) {
                $decisioncui = 'rupture';
            }
            $saved = $this->Rupturecui66->Cui->updateAllUnBound(
                array(
                    'Cui.positioncui66' => '\'rupture\'',
                    'Cui.decisioncui' => "'".$decisioncui."'"
                ),
                array(
                    'Cui.id' => $cui_id
                )
            );

            if( $saved) {
                if( $this->{$this->modelClass}->save() ) {
                    $this->{$this->modelClass}->commit();
                    $this->Session->setFlash( 'Enregistrement effectué', 'flash/success' );
                    $this->redirect( array( 'controller' => 'cuis', 'action' => 'index', $personne_id ) );
                }
                else {
                    $this->{$this->modelClass}->rollback();
                    $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
                }
            }
            else {
                $this->{$this->modelClass}->rollback();
                $this->Session->setFlash( 'Erreur lors de l\'enregistrement', 'flash/error' );
            }
        }
        else {
            $this->request->data = $rupturecui66;
        }


        $this->_setOptions();
        $this->set( compact( 'cui_id' ) );
        $this->render( 'edit' );
    }
}
?>
