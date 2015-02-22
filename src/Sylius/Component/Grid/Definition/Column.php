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
class Column
{
    const TYPE_STRING = 'string';

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
     * @var bool
     */
    private $sortable;

    /**
     * @var string
     */
    private $template;

    /**
     * @var array
     */
    private $options = array();

    /**
     * @param string $field
     * @param string $type
     * @param string $label
     * @param bool $sortable
     * @param string $template
     */
    public function __construct($field, $type = self::TYPE_STRING, $label = null, $sortable = true, $template = null, $options = array())
    {
        $this->field = $field;
        $this->type = $type;
        $this->label = $label;
        $this->sortable = $sortable;
        $this->template = $template;
        $this->options = array();
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
            array_key_exists('sortable', $configuration) ? $configuration['sortable'] : true,
            isset($configuration['template']) ? $configuration['template'] : null,
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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return bool
     */
    public function isSortable()
    {
        return $this->sortable;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
