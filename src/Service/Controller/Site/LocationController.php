<?php
namespace ISTCIDOC\Controller\Site;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class LocationController extends AbstractActionController
{
    public function showAction()
    {
        $site = $this->currentSite();
        $response = $this->api()->read('locations', $this->params('id'));
        $item = $response->getContent();

        $view = new ViewModel;
        $view->setVariable('site', $site);
        $view->setVariable('item', $item);
        $view->setVariable('resource', $item);
        return $view;
    }
}
