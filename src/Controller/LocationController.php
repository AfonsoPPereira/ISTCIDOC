<?php
namespace ISTCIDOC\Controller;


use ISTCIDOC\Form\ISTCIDOCFormLocation;

use Omeka\Form\ConfirmForm;
use Omeka\Form\ResourceForm;
use Omeka\Form\ResourceBatchUpdateForm;
use Omeka\Media\Ingester\Manager;
use Omeka\Stdlib\Message;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class LocationController extends AbstractActionController
{
    public function searchAction()
    {
        $view = new ViewModel;
        $view->setVariable('query', $this->params()->fromQuery());
        return $view;
    }

    public function showAction()
    {
        $response = $this->api()->read('locations', $this->params('id'));
        $view = new ViewModel;
        $item = $response->getContent();
        $view->setVariable('item', $item);
        return $view;
    }

    public function browseAction()
    {
        $this->setBrowseDefaults('created');
        $response = $this->api()->search('locations' , $this->params()->fromQuery());
        $this->paginator($response->getTotalResults(), $this->params()->fromQuery('page'));

        $view = new ViewModel;
        $locations = $response->getContent();
        $view->setVariable('locations', $locations);
        return $view;
    }

    public function addLocationAction()
    {
        $action = $this->params('action');
        $form = $this->getForm(ISTCIDOCFormLocation::class);

        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());
            if ($form->isValid()) {
                $formData = $form->getData();
                $response = $this->api($form)->create('locations', $formData);
                if ($response) {
                    $this->messenger()->addSuccess('Location created.'); // @translate
                    return $this->redirect()->toRoute('admin/ist-cidoc/add-location'); // @translate
                }
            } else {
                $this->messenger()->addError('There was an error during validation'); // @translate
            }
        }

        $view = new ViewModel;
        $view->setVariable('form', $form);
        return $view;
    }

    public function editAction()
    {
        $action = $this->params('action');
        $form = $this->getForm(ISTCIDOCFormLocation::class);
        $response = $this->api()->read('locations', $this->params('id'));
        $vocab = $response->getContent();
        $form->setData($vocab->jsonSerialize());

        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());
            if ($form->isValid()) {
                $formData = $form->getData();
                $response = $this->api($form)->update('locations', $vocab->id(), $formData);
                if ($response) {
                    $this->messenger()->addSuccess('Location successfully updated.');
                    return $this->redirect()->toRoute('admin/ist-cidoc');
                }
            } else {
                $this->messenger()->addError('There was an error during validation'); // @translate
            }
        }

        $view = new ViewModel;
        $view->setVariable('form', $form);
        $view->setVariable('vocab', $vocab);
        return $view;
    }

    public function showDetailsAction()
    {
        $linkTitle = (bool) $this->params()->fromQuery('link-title', true);
        $response = $this->api()->read('locations', $this->params('id'));
        $item = $response->getContent();
        $values = $item->valueRepresentation();

        $view = new ViewModel;
        $view->setTerminal(true);
        $view->setVariable('linkTitle', $linkTitle);
        $view->setVariable('resource', $item);
        $view->setVariable('values', json_encode($values));
        return $view;
    }

    public function deleteConfirmAction()
    {
        $resource = $this->api()->read('locations', $this->params('id'))->getContent();

        $view = new ViewModel;
        $view->setTerminal(true);
        $view->setTemplate('common/delete-confirm-details');
        $view->setVariable('resource', $resource);
        $resourceLabel = 'locations'; // @translate
        $view->setVariable('resourceLabel', $resourceLabel);
        $view->setVariable('partialPath', 'istcidoc/location/show-details');
        return $view;
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isPost()) {
            $form = $this->getForm(ConfirmForm::class);
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $response = $this->api($form)->delete('locations', $this->params('id'));
                if ($response) {
                    $this->messenger()->addSuccess('Location successfully deleted'); // @translate
                }
            } else {
                $this->messenger()->addError('Location could not be deleted'); // @translate
            }
        }
        return $this->redirect()->toRoute('admin/ist-cidoc/browse-location');
    }
}
