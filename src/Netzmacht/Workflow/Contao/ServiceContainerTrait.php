<?php

/**
 * @package    workflow
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Workflow\Contao;

/**
 * Class ServiceProviderTrait.
 *
 * Injecting the container or use the Container as a service locator is a bad thing. This is used because Contao
 * does not provide dependency injection.
 *
 * @package Netzmacht\Workflow\Contao
 */
trait ServiceContainerTrait
{
    /**
     * Get the service provider.
     *
     * @return ServiceProvider
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    protected function getServiceProvider()
    {
        return $GLOBALS['container']['workflow.service-provider'];
    }

    /**
     * Get a service from the service provider
     *
     * @param string $name Name of the service.
     *
     * @return mixed
     */
    protected function getService($name)
    {
        return $this->getServiceProvider()->getService($name);
    }
}