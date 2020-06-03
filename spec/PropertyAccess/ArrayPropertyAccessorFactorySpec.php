<?php

/**
 * This Contao-Workflow extension allows the definition of workflow process for entities from different providers. This
 * extension is a workflow framework which can be used from other extensions to provide their custom workflow handling.
 *
 * @package    workflow
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014-2020 netzmacht David Molineus
 * @license    LGPL 3.0-or-later
 * @filesource
 */

declare(strict_types=1);

namespace spec\Netzmacht\ContaoWorkflowBundle\PropertyAccess;

use ArrayObject;
use Netzmacht\ContaoWorkflowBundle\PropertyAccess\ArrayPropertyAccessor;
use Netzmacht\ContaoWorkflowBundle\PropertyAccess\ArrayPropertyAccessorFactory;
use Netzmacht\ContaoWorkflowBundle\PropertyAccess\PropertyAccessor;
use Netzmacht\ContaoWorkflowBundle\PropertyAccess\PropertyAccessorFactory;
use PhpSpec\ObjectBehavior;

final class ArrayPropertyAccessorFactorySpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ArrayPropertyAccessorFactory::class);
    }

    public function it_is_a_property_accessor_factory(): void
    {
        $this->shouldImplement(PropertyAccessorFactory::class);
    }

    public function it_supports_arrays(): void
    {
        $this->supports([])->shouldReturn(true);
    }

    public function it_supports_array_objects(): void
    {
        $this->supports(new ArrayObject())->shouldReturn(true);
    }

    public function it_does_not_support_objects() : void
    {
        $this->supports((object) [])->shouldReturn(false);
    }

    public function it_creates_an_property_accessor(): void
    {
        $instance = $this->create(['foo' => 'bar']);
        $instance->shouldBeAnInstanceOf(PropertyAccessor::class);
        $instance->shouldBeAnInstanceOf(ArrayPropertyAccessor::class);
    }
}
