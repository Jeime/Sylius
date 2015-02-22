<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Component\Grid\Definition;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class Filter
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $options = array();

    /**
     * @param string $field
     * @param string $label
     * @param string $type
     * @param array $options
     */
    public function __construct($field, $type, $label = null, array $options = array())
    {
        $this->field = $field;
        $this->type = $type;
        $this->label = $label;
        $this->options = $options;
    }

    /**
     * @param array $configuration
     */
    public static function createFromArray($field, array $configuration)
    {
        return new self(
            $field,
            $configuration['type'],
            isset($configuration['label']) ? $configuration['label'] : null,
            isset($configuration['options']) ? $configuration['options'] : array()
        );
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
