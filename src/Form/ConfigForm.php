<?php
namespace ISTCIDOC\Form;

use Zend\Form\Form;
use Zend\Validator\Callback;

class ConfigForm extends Form
{
    public function init()
    {
        $this->add([
            'type' => 'checkbox',
            'name' => 'delete_vocabulary',
            'options' => [
                'label' => 'Delete Module Vocabulary', // @translate
				'info' => 'Delete ISTCIDOC Vocabulary when uninstalling module? Warning: if this option is checked, ISTCIDOC Items might not function properly after the uninstall.', // @translate
                'use_hidden_element' => true,
                'checked_value' => 'yes',
                'unchecked_value' => 'no',
            ],
            'attributes' => [
                'id' => 'delete_vocabulary',
            ],
        ]);
        $this->add([
            'type' => 'checkbox',
            'name' => 'delete_locations',
            'options' => [
                'label' => 'Delete ISTCIDOC Locations', // @translate
                'info' => 'Delete ISTCIDOC Location Items when uninstalling module? Warning: if this option is checked, all Locations are going to be erased permanently.', // @translate
                'use_hidden_element' => true,
                'checked_value' => 'yes',
                'unchecked_value' => 'no',
            ],
            'attributes' => [
                'id' => 'delete_locations',
            ],
        ]);
    }

    public function directoryIsValid($dir, $context)
    {
        $dir = new \SplFileInfo($dir);
        $valid = $dir->isDir() && $dir->isExecutable() && $dir->isReadable();
        if (isset($context['delete_file']) && 'yes' === $context['delete_file']) {
            $valid = $valid && $dir->isWritable();
        }
        return $valid;
    }
}
