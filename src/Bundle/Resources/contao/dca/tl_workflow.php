<?php

/**
 * This Contao-Workflow extension allows the definition of workflow process for entities from different providers. This
 * extension is a workflow framework which can be used from other extensions to provide their custom workflow handling.
 *
 * @package    workflow
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 */

$GLOBALS['TL_DCA']['tl_workflow'] = array
(
    'config' => array
    (
        'dataContainer' => 'Table',
        'ctable'        => array(
            'tl_workflow_step',
            'tl_workflow_transition'
        ),
        'sql'           => array
        (
            'keys' => array
            (
                'id' => 'primary',
            )
        ),
    ),

    'list' => array
    (
        'sorting' => array
        (
            'mode'   => 1,
            'fields' => array('type', 'name'),
            'panelLayout' => 'sorting,filter,search'
        ),
        'label' => array
        (
            'fields'         => array('name', 'type', 'description'),
            'label_callback' => array('Netzmacht\Contao\Workflow\Backend\Common', 'generateRow'),
        ),

        'operations' => array
        (
            'edit' => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_workflow']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif',
            ),
            'steps' => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_workflow']['steps'],
                'href'  => 'table=tl_workflow_step',
                'icon'  => 'bundles/netzmachtcontaoworkflow/img/step.png',
            ),
            'transitions' => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_workflow']['transitions'],
                'href'  => 'table=tl_workflow_transition',
                'icon'  => 'bundles/netzmachtcontaoworkflow/img/transition.png',
            ),
            'delete' => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_workflow']['delete'],
                'href'  => 'act=delete',
                'icon'  => 'delete.gif',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
            ),
            'toggle' => array
            (
                'label'      => &$GLOBALS['TL_LANG']['tl_workflow']['toggle'],
                'icon'       => 'visible.gif',
                'attributes' => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback' => \Netzmacht\Contao\Toolkit\Dca::createToggleIconCallback(
                    'tl_workflow',
                    'active'
                )
            ),
            'show' => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_workflow']['show'],
                'href'  => 'act=show',
                'icon'  => 'show.gif',
            ),
        ),
    ),

    'palettes' => array(
        '__selector__' => array('type')
    ),

    'metapalettes' => array
    (
        'default' => array
        (
            'name'        => array('label', 'name', 'type', 'providerName'),
            'description' => array(':hide', 'description'),
            'permissions' => array('permissions'),
            'process'     => array('process', 'logChanges'),
            'config'      => array(),
            'activation'  => array('active')
        ),
    ),

    'fields' => array
    (
        'id'             => array
        (
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp'         => array
        (
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'name'           => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_workflow']['name'],
            'inputType' => 'text',
            'search'    => true,
            'exclude'   => true,
            'save_callback' => array(
                array('Netzmacht\Contao\Workflow\Backend\Common', 'createName'),
            ),
            'eval'      => array(
                'tl_class'           => 'w50',
                'mandatory' => true,
            ),
            'sql'       => "varchar(64) NOT NULL default ''",
        ),
        'label'           => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_workflow']['label'],
            'inputType' => 'text',
            'exclude'   => true,
            'eval'      => array(
                'tl_class'           => 'w50',
                'maxlength' => 64,
            ),
            'sql'       => "varchar(64) NOT NULL default ''",
        ),
        'type'           => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_workflow']['type'],
            'inputType' => 'select',
            'filter'    => true,
            'reference' => &$GLOBALS['TL_LANG']['workflow']['types'],
            'options_callback' => array
            (
                'Netzmacht\Contao\Workflow\Backend\Dca\Workflow',
                'getTypes'
            ),
            'exclude'   => true,
            'eval'      => array(
                'tl_class'           => 'w50',
                'mandatory' => true,
                'submitOnChange' => true,
            ),
            'sql'       => "varchar(64) NOT NULL default ''",
        ),
        'providerName'           => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_workflow']['providerName'],
            'inputType' => 'select',
            'search'    => true,
            'exclude'   => true,
            'options_callback' => array(
                'Netzmacht\Contao\Workflow\Backend\Dca\Workflow',
                'getProviderNames'
            ),
            'eval'      => array(
                'tl_class'  => 'w50',
                'mandatory' => true,
            ),
            'sql'       => "varchar(64) NOT NULL default ''",
        ),
        'description'           => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_workflow']['description'],
            'inputType' => 'text',
            'exclude'   => true,
            'eval'      => array(
                'tl_class'           => 'clr long',
            ),
            'sql'       => "varchar(255) NOT NULL default ''",
        ),
        'process'           => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_workflow']['process'],
            'inputType' => 'multiColumnWizard',
            'exclude'   => true,
            'save_callback' => array(
                array('Netzmacht\Contao\Workflow\Backend\Dca\Workflow', 'validateProcess'),
            ),
            'eval'      => array(
                'tl_class'           => 'clr',
                'columnFields' => array
                (
                    'step' => array
                    (
                        'label'     => &$GLOBALS['TL_LANG']['tl_workflow']['step'],
                        'inputType' => 'select',
                        'options_callback' => array
                        (
                            'Netzmacht\Contao\Workflow\Backend\Dca\Workflow',
                            'getStartSteps'
                        ),
                        'reference' => &$GLOBALS['TL_LANG']['tl_workflow']['process'],
                        'eval'      => array
                        (
                            'style' => 'width:200px',
                            'includeBlankOption' => true,
                            'chosen' => true,
                        ),
                    ),
                    'transition' => array
                    (
                        'label'     => &$GLOBALS['TL_LANG']['tl_workflow']['transition'],
                        'inputType' => 'select',
                        'options_callback' => array
                        (
                            'Netzmacht\Contao\Workflow\Backend\Dca\Workflow',
                            'getTransitions',
                        ),
                        'eval'      => array
                        (
                            'style' => 'width:400px',
                            'includeBlankOption' => true,
                            'chosen' => true,
                        ),
                    ),
                )
            ),
            'sql'       => "mediumblob NULL",
        ),
        'permissions'           => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_workflow']['permissions'],
            'inputType' => 'multiColumnWizard',
            'exclude'   => true,
            'save_callback' => array(
                array('Netzmacht\Contao\Workflow\Backend\Dca\Workflow', 'validatePermissions'),
            ),
            'eval'      => array(
                'tl_class'           => 'clr',
                'columnFields' => array
                (
                    'label' => array
                    (
                        'label'     => &$GLOBALS['TL_LANG']['tl_workflow']['permission_label'],
                        'inputType' => 'text',
                        'options_callback' => array
                        (
                            'Netzmacht\Contao\Workflow\Backend\Dca\Workflow',
                            'getStartSteps'
                        ),
                        'eval'      => array
                        (
                            'style' => 'width:350px',
                        ),
                    ),
                    'name' => array
                    (
                        'label'     => &$GLOBALS['TL_LANG']['tl_workflow']['permission_name'],
                        'inputType' => 'text',
                        'options_callback' => array
                        (
                            'Netzmacht\Contao\Workflow\Backend\Dca\Workflow',
                            'getStartSteps'
                        ),
                        'eval'      => array
                        (
                            'style' => 'width:230px',
                        ),
                    ),
                )
            ),
            'sql'       => "mediumblob NULL",
        ),
        'logChanges'      => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_workflow']['logChanges'],
            'inputType' => 'checkbox',
            'default'   => '1',
            'eval'      => array(
                'tl_class'       => 'w50',
            ),
            'sql'       => "char(8) NOT NULL default '1'"
        ),
        'active'      => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_workflow']['active'],
            'inputType' => 'checkbox',
            'eval'      => array(
                'tl_class'       => 'clr w50',
                'submitOnChange' => true,
            ),
            'sql'       => "char(1) NOT NULL default ''"
        ),
    ),
);