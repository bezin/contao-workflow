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

namespace Netzmacht\Workflow\Contao\Condition\Transition;

use ContaoCommunityAlliance\DcGeneral\Data\ModelInterface as Entity;
use Netzmacht\Workflow\Data\ErrorCollection;
use Netzmacht\Workflow\Flow\Condition\Transition\Condition;
use Netzmacht\Workflow\Flow\Context;
use Netzmacht\Workflow\Flow\Item;
use Netzmacht\Workflow\Flow\Transition;
use Netzmacht\Workflow\Util\Comparison;

/**
 * Class PropertyCondition compares an entity property against a defined value.
 *
 * @package Netzmacht\Workflow\Flow\Condition\Transition
 */
class PropertyCondition implements Condition
{
    /**
     * Name of the property.
     *
     * @var string
     */
    private $property;

    /**
     * Comparison operator.
     *
     * @var string
     */
    private $operator = Comparison::EQUALS;

    /**
     * Value to compare with.
     *
     * @var mixed
     */
    private $value;

    /**
     * Get comparison operator.
     *
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Set property name.
     *
     * @param string $operator Comparison operator name.
     *
     * @return $this
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * Get Property name.
     *
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Set comparison property.
     *
     * @param string $property Comparison property.
     *
     * @return $this
     */
    public function setProperty($property)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * Get value to compare agianst.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set Value to compare against.
     *
     * @param mixed $value The comparison value.
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function match(Transition $transition, Item $item, Context $context, ErrorCollection $errorCollection)
    {
        $value = $this->getEntityValue($item->getEntity());

        if (Comparison::compare($value, $this->value, $this->operator)) {
            return true;
        }

        $errorCollection->addError(
            'transition.condition.property.failed',
            array(
                $this->property,
                $value,
                $this->operator,
                $this->value,
            )
        );

        return false;
    }

    /**
     * Get value from the entity.
     *
     * @param Entity $entity The entity.
     *
     * @return mixed
     */
    protected function getEntityValue(Entity $entity)
    {
        // no access to id by the entity
        if ($this->property == 'id') {
            return $entity->getId();
        }

        return $entity->getProperty($this->property);
    }
}
