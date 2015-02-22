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
class Action
{
    const TYPE_LINK = 'link';
    const TYPE_FORM = 'form';

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $route;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $label;

    /**
     * @var array
     */
    private $options = array();

    /**
     * @param string $type
     * @param string $route;
     * @param string $path;
     * @param string $label
     * @param array  $options
     */
    public function __construct($type = self::TYPE_LINK, $route = null, $path = null, $label = null, array $options = array())
    {
        $this->type = $type;
        $this->route = $route;
        $this->path = $path;
        $this->label = $label;
        $this->options = $options;
    }

    /**
     * @param array $configuration
     */
    public static function createFromArray(array $configuration)
    {
        return new self(
            $configuration['type'],
            isset($configuration['route']) ? $configuration['route'] : null,
            isset($configuration['path']) ? $configuration['path'] : null,
            isset($configuration['label']) ? $configuration['label'] : null,
            isset($configuration['options']) ? $configuration['options'] : array()
        );
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
