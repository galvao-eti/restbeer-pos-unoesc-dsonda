<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\Beer as BeerForm;
use Application\Model\Beer as BeerModel;

class IndexController extends AbstractActionController
{
    private $tableGateway;

    private function getTableGateway()
    {
        if (!$this->tableGateway) {
            $this->tableGateway = $this->getServiceLocator()->get('Application\Model\BeerTableGateway');
        }
        return $this->tableGateway;
    }

    public function indexAction()
    {
        $tableGateway = $this->getTableGateway();
        $beers = $tableGateway->fetchAll();
        return new ViewModel(array('beers' => $beers));
    }

    public function saveAction()
    {
        $form = new BeerForm();
        $tableGateway = $this->getTableGateway();
        $request = $this->getRequest();
        /* se a requisição é post os dados foram enviados via formulário*/
        if ($request->isPost()) {
            $beer = new BeerModel;
            /* configura a validação do formulário com os filtros e validators da entidade*/
            $form->setInputFilter($beer->getInputFilter());
            /* preenche o formulário com os dados que o usuário digitou na tela*/
            $form->setData($request->getPost());
            /* faz a validação do formulário*/
            if ($form->isValid()) {
                /* pega os dados validados e filtrados */
                $data = $form->getData(); 
                /* preenche os dados do objeto Beer com os dados do formulário*/
                $beer->exchangeArray($data);
                /* salva o novo beer */
                $tableGateway->save($beer);
                /* redireciona para a página inicial que mostra todos os beers */
                return $this->redirect()->toUrl('/');
            }
        }

        /* recupera parâmetro da URL */
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id > 0) {/* é uma atualização */
            /* busca a entidade no banco de dados*/ 
            $beer = $tableGateway->get($id);
            /* preenche o formulário com os dados do banco de dados*/
            $form->bind($beer);
        }
        return new ViewModel(array('form' => $form));
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id == 0) {
            throw new Exception("ID obrigatório");
        }
        /* remove o registro e redireciona para a página inicial*/
        $tableGateway = $this->getTableGateway()->delete($id);
        return $this->redirect()->toUrl('/');
    }
}
