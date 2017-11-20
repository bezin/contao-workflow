<?php

/**
 * This Contao-Workflow extension allows the definition of workflow process for entities from different providers. This
 * extension is a workflow framework which can be used from other extensions to provide their custom workflow handling.
 *
 * @package    workflow
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014-2017 netzmacht David Molineus
 * @license    LGPL 3.0
 * @filesource
 */

namespace Netzmacht\Contao\Workflow\Action;

use Netzmacht\Workflow\Flow\Action;
use Netzmacht\Workflow\Flow\Transition;
use Netzmacht\Workflow\Flow\Workflow;
use Symfony\Component\Form\FormBuilderInterface as FormBuilder;

/**
 * Interface ActionFactory
 *
 * @package Netzmacht\Contao\Workflow\Action
 */
interface ActionTypeFactory
{
    /**
     * Get the category.
     *
     * @return string
     */
    public function getCategory(): string;

    /**
     * Get name of the action.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Check if workflow is supported.
     *
     * @param Workflow $workflow The workflow in which the action should be handled.
     *
     * @return bool
     */
    public function supports(Workflow $workflow): bool;

    /**
     * Create an action.
     *
     * @param array      $config     Action config.
     * @param Transition $transition Transition to which the action belongs.
     *
     * @return Action
     */
    public function create(array $config, Transition $transition): Action;

    /**
     * Build the form.
     *
     * @param FormBuilder $formBuilder The form builder.
     * @param array       $config      The action config.
     * @param Transition  $transition  Transition to which the action belongs.
     *
     * @return void
     */
    public function buildForm(FormBuilder $formBuilder, array $config, Transition $transition): void;
}
