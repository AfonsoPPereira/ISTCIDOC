<?php
namespace ISTCIDOC\View\Helper;

use Omeka\Form\ConfirmForm;
use Omeka\View\Helper\DeleteConfirm;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * View helper for rendering the delete confirm partial.
 */
class DeleteConfirmCIDOC extends DeleteConfirm
{
    public function __invoke($resource, $resourceLabel = null, $wrapSidebar = true)
    {
        $form = $this->formElementManager->get(ConfirmForm::class);
        $form->setAttribute('action', $resource->url('delete'));

        return $this->getView()->partial(
            'common/delete-confirm',
            [
                'wrapSidebar' => $wrapSidebar,
                'resource' => $resource,
                'resourceLabel' => $resourceLabel,
                'form' => $form,
            ]
        );
    }
}
