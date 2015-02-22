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
class Grid
{
    /**
     * @var string
     */
    private $applicationName;

    /**
     * @var string
     */
    private $resourceName;

    /**
     * @var string
     */
    private $driver;

    /**
     * @var array
     */
    private $columns = array();

    /**
     * @var array
     */
    private $filters = array();

    /**
     * @var array
     */
    private $actions = array();

    /**
     * @var array
     */
    private $sorting = array();

    /**
     * @var array
     */
    private $massActions = array();

    /**
     * @param string $applicationName
     * @param string $resourceName
     * @param string $driver
     * @param array $columns
     * @param array $filters
     * @param array $actions
     * @param array $sorting
     */
    public function __construct($applicationName, $resourceName, $driver, array $columns, array $filters, array $actions, array $sorting)
    {
        $this->applicationName = $applicationName;
        $this->resourceName = $resourceName;
        $this->driver = $driver;

        foreach ($columns as $column) {
            if (!$column instanceof Column) {
                throw new \InvalidArgumentException('Expected Column definition instance.');
            }
        }

        foreach ($filters as $filter) {
            if (!$filter instanceof Filter) {
                throw new \InvalidArgumentException('Expected Filter definition instance.');
            }
        }

        foreach ($actions as $action) {
            if (!$action instanceof Action) {
                throw new \InvalidArgumentException('Expected Action definition instance.');
            }
        }

        $this->columns = $columns;
        $this->filters = $filters;
        $this->actions = $actions;
        $this->sorting = $sorting;
    }

    /**
     * @param array $configuration
     */
    public static function createFromArray(array $configuration)
    {
        $columns = array();
        $filters = array();
        $actions = array();

        foreach ($configuration['columns'] as $field => $columnConfiguration) {
            $columns[$field] = Column::createFromArray($field, $columnConfiguration);
        }
        foreach ($configuration['filters'] as $field => $filterConfiguration) {
            $filters[$field] = Filter::createFromArray($field, $filterConfiguration);
        }
        foreach ($configuration['actions'] as $actionConfiguration) {
            $actions[] = Action::createFromArray($actionConfiguration);
        }

        $sorting = array_key_exists('sorting', $configuration) ? $configuration['sorting'] : array();

        list($applicationName, $resourceName) = explode('.', $configuration['resource']);

        return new self($applicationName, $resourceName, $configuration['driver'], $columns, $filters, $actions, $sorting);
    }

    /**
     * @return string
     */
    public function getApplicationName()
    {
        return $this->applicationName;
    }

    /**
     * @return string
     */
    public function getResourceName()
    {
        return $this->resourceName;
    }

    /**
     * @return string
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @param string $column
     *
     * @return Column
     */
    public function getColumn($column)
    {
        return $this->columns[$column];
    }

    /**
     * @param $column
     *
     * @return bool
     */
    public function hasColumn($column)
    {
        return isset($this->columns[$column]);
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param string $column
     */
    public function getFilter($column)
    {
        return $this->filters[$column];
    }

    /**
     * @param $column
     *
     * @return bool
     */
    public function hasFilter($column)
    {
        return isset($this->filters[$column]);
    }

    /**
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @return array
     */
    public function getMassActions()
    {
        return $this->massActions;
    }

    /**
     * @return array
     */
    public function getSorting()
    {
        return $this->sorting;
    }
}
