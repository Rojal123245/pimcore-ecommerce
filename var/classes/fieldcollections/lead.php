<?php

/**
 * Fields Summary:
 * - localizedfields [localizedfields]
 * -- text [wysiwyg]
 * - marginTop [select]
 * - marginBottom [select]
 * - anchor [input]
 * - headingTag [select]
 */

return \Pimcore\Model\DataObject\Fieldcollection\Definition::__set_state(array(
   'dao' => NULL,
   'key' => 'lead',
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
             'title' => 'Text',
             'width' => '',
             'height' => '',
             'collapsible' => false,
             'collapsed' => false,
             'bodyStyle' => '',
             'datatype' => 'layout',
             'children' => 
            array (
              0 => 
              \Pimcore\Model\DataObject\ClassDefinition\Data\Localizedfields::__set_state(array(
                 'name' => 'localizedfields',
                 'title' => '',
                 'tooltip' => NULL,
                 'mandatory' => false,
                 'noteditable' => false,
                 'index' => false,
                 'locked' => false,
                 'style' => NULL,
                 'permissions' => NULL,
                 'fieldtype' => '',
                 'relationType' => false,
                 'invisible' => false,
                 'visibleGridView' => true,
                 'visibleSearch' => true,
                 'blockedVarsForExport' => 
                array (
                ),
                 'children' => 
                array (
                  0 => 
                  \Pimcore\Model\DataObject\ClassDefinition\Data\Wysiwyg::__set_state(array(
                     'name' => 'text',
                     'title' => 'Text',
                     'tooltip' => '',
                     'mandatory' => true,
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
                     'toolbarConfig' => '',
                     'excludeFromSearchIndex' => false,
                     'maxCharacters' => '',
                     'height' => '',
                     'width' => '',
                  )),
                ),
                 'region' => NULL,
                 'layout' => NULL,
                 'maxTabs' => NULL,
                 'border' => false,
                 'provideSplitView' => false,
                 'tabPosition' => 'top',
                 'hideLabelsWhenTabsReached' => NULL,
                 'referencedFields' => 
                array (
                ),
                 'permissionView' => NULL,
                 'permissionEdit' => NULL,
                 'labelWidth' => 100,
                 'labelAlign' => 'left',
                 'fieldDefinitionsCache' => NULL,
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
          1 => 
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
              3 => 
              \Pimcore\Model\DataObject\ClassDefinition\Data\Select::__set_state(array(
                 'name' => 'headingTag',
                 'title' => 'Text Size',
                 'tooltip' => '',
                 'mandatory' => true,
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
                    'key' => 'H1',
                    'value' => 'h1',
                  ),
                  1 => 
                  array (
                    'key' => 'H2',
                    'value' => 'h2',
                  ),
                  2 => 
                  array (
                    'key' => 'H3',
                    'value' => 'h3',
                  ),
                  3 => 
                  array (
                    'key' => 'H4',
                    'value' => 'h4',
                  ),
                  4 => 
                  array (
                    'key' => 'H5',
                    'value' => 'h5',
                  ),
                  5 => 
                  array (
                    'key' => 'Body',
                    'value' => 'body',
                  ),
                ),
                 'defaultValue' => 'h3',
                 'optionsProviderClass' => '',
                 'optionsProviderData' => '',
                 'columnLength' => 190,
                 'dynamicOptions' => false,
                 'defaultValueGenerator' => '',
                 'width' => '',
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
