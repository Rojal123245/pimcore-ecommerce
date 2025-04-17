<?php

/**
 * Fields Summary:
 * - marginTop [select]
 * - marginBottom [select]
 * - anchor [input]
 */

return \Pimcore\Model\DataObject\Fieldcollection\Definition::__set_state(array(
   'dao' => NULL,
   'key' => 'share',
   'parentClass' => '',
   'implementsInterfaces' => '',
   'title' => '',
   'group' => '',
   'layoutDefinitions' => 
  \Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
     'name' => NULL,
     'type' => NULL,
     'region' => NULL,
     'title' => NULL,
     'width' => 0,
     'height' => 0,
     'collapsible' => false,
     'collapsed' => false,
     'bodyStyle' => NULL,
     'datatype' => 'layout',
     'children' => 
    array (
      0 => 
      \Pimcore\Model\DataObject\ClassDefinition\Layout\Tabpanel::__set_state(array(
         'name' => 'Layout',
         'type' => NULL,
         'region' => NULL,
         'title' => '',
         'width' => '',
         'height' => '',
         'collapsible' => false,
         'collapsed' => false,
         'bodyStyle' => '',
         'datatype' => 'layout',
         'children' => 
        array (
          0 => 
          \Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
             'name' => 'Layout',
             'type' => NULL,
             'region' => NULL,
             'title' => 'Configuration',
             'width' => '',
             'height' => '',
             'collapsible' => false,
             'collapsed' => false,
             'bodyStyle' => '',
             'datatype' => 'layout',
             'children' => 
            array (
              0 => 
              \Pimcore\Model\DataObject\ClassDefinition\Data\Select::__set_state(array(
                 'name' => 'marginTop',
                 'title' => 'Margin Top',
                 'tooltip' => '',
                 'mandatory' => false,
                 'noteditable' => false,
                 'index' => false,
                 'locked' => false,
                 'style' => '',
                 'permissions' => NULL,
                 'fieldtype' => '',
                 'relationType' => false,
                 'invisible' => false,
                 'visibleGridView' => false,
                 'visibleSearch' => false,
                 'blockedVarsForExport' => 
                array (
                ),
                 'options' => 
                array (
                  0 => 
                  array (
                    'key' => 'None',
                    'value' => ' ',
                    'id' => 'extModel11513-1',
                  ),
                  1 => 
                  array (
                    'key' => 'Small',
                    'value' => 'sm',
                    'id' => 'extModel11513-2',
                  ),
                  2 => 
                  array (
                    'key' => 'Medium',
                    'value' => 'md',
                    'id' => 'extModel11513-3',
                  ),
                  3 => 
                  array (
                    'key' => 'Large',
                    'value' => 'lg',
                    'id' => 'extModel11513-4',
                  ),
                  4 => 
                  array (
                    'key' => 'XL',
                    'value' => 'xl',
                    'id' => 'extModel11513-5',
                  ),
                  5 => 
                  array (
                    'key' => 'XXL',
                    'value' => 'xxl',
                    'id' => 'extModel11513-6',
                  ),
                  6 => 
                  array (
                    'key' => 'XXXL',
                    'value' => 'xxxl',
                    'id' => 'extModel11513-7',
                  ),
                ),
                 'defaultValue' => ' ',
                 'optionsProviderClass' => '',
                 'optionsProviderData' => '',
                 'columnLength' => 190,
                 'dynamicOptions' => false,
                 'defaultValueGenerator' => '',
                 'width' => '',
              )),
              1 => 
              \Pimcore\Model\DataObject\ClassDefinition\Data\Select::__set_state(array(
                 'name' => 'marginBottom',
                 'title' => 'Margin Bottom',
                 'tooltip' => '',
                 'mandatory' => false,
                 'noteditable' => false,
                 'index' => false,
                 'locked' => false,
                 'style' => '',
                 'permissions' => NULL,
                 'fieldtype' => '',
                 'relationType' => false,
                 'invisible' => false,
                 'visibleGridView' => false,
                 'visibleSearch' => false,
                 'blockedVarsForExport' => 
                array (
                ),
                 'options' => 
                array (
                  0 => 
                  array (
                    'key' => 'None',
                    'value' => ' ',
                    'id' => 'extModel11576-1',
                  ),
                  1 => 
                  array (
                    'key' => 'Small',
                    'value' => 'sm',
                    'id' => 'extModel11576-2',
                  ),
                  2 => 
                  array (
                    'key' => 'Medium',
                    'value' => 'md',
                    'id' => 'extModel11576-3',
                  ),
                  3 => 
                  array (
                    'key' => 'Large',
                    'value' => 'lg',
                    'id' => 'extModel11576-4',
                  ),
                  4 => 
                  array (
                    'key' => 'XL',
                    'value' => 'xl',
                    'id' => 'extModel11576-5',
                  ),
                  5 => 
                  array (
                    'key' => 'XXL',
                    'value' => 'xxl',
                    'id' => 'extModel11576-6',
                  ),
                  6 => 
                  array (
                    'key' => 'XXXL',
                    'value' => 'xxxl',
                    'id' => 'extModel11576-7',
                  ),
                ),
                 'defaultValue' => ' ',
                 'optionsProviderClass' => '',
                 'optionsProviderData' => '',
                 'columnLength' => 190,
                 'dynamicOptions' => false,
                 'defaultValueGenerator' => '',
                 'width' => '',
              )),
              2 => 
              \Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                 'name' => 'anchor',
                 'title' => 'Anchor',
                 'tooltip' => '',
                 'mandatory' => false,
                 'noteditable' => false,
                 'index' => false,
                 'locked' => false,
                 'style' => '',
                 'permissions' => NULL,
                 'fieldtype' => '',
                 'relationType' => false,
                 'invisible' => false,
                 'visibleGridView' => false,
                 'visibleSearch' => false,
                 'blockedVarsForExport' => 
                array (
                ),
                 'defaultValue' => NULL,
                 'columnLength' => 190,
                 'regex' => '',
                 'regexFlags' => 
                array (
                ),
                 'unique' => false,
                 'showCharCount' => false,
                 'width' => '',
                 'defaultValueGenerator' => '',
              )),
            ),
             'locked' => false,
             'blockedVarsForExport' => 
            array (
            ),
             'fieldtype' => 'panel',
             'layout' => NULL,
             'border' => false,
             'icon' => '',
             'labelWidth' => 100,
             'labelAlign' => 'left',
          )),
        ),
         'locked' => false,
         'blockedVarsForExport' => 
        array (
        ),
         'fieldtype' => 'tabpanel',
         'border' => false,
         'tabPosition' => 'top',
      )),
    ),
     'locked' => false,
     'blockedVarsForExport' => 
    array (
    ),
     'fieldtype' => 'panel',
     'layout' => NULL,
     'border' => false,
     'icon' => NULL,
     'labelWidth' => 100,
     'labelAlign' => 'left',
  )),
   'fieldDefinitionsCache' => NULL,
   'blockedVarsForExport' => 
  array (
  ),
));
