<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\GridBundle\Twig;

use Sylius\Component\Grid\Definition\Action;
use Sylius\Component\Grid\Sorter\SorterInterface;
use Sylius\Component\Grid\View\GridView;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 *
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class GridExtension extends \Twig_Extension
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var PropertyAccesssorInterface
     */
    private $dataAccessor;

    /**
     * @param FormFactoryInterface $formFactory
     */
    function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
        $this->dataAccessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('sylius_grid_render', array($this, 'render'), array(
                'is_safe'           => array('html'),
                'needs_environment' => true
            )),
            new \Twig_SimpleFunction('sylius_grid_render_header', array($this, 'renderHeader'), array(
                'is_safe'           => array('html'),
                'needs_environment' => true
            )),
            new \Twig_SimpleFunction('sylius_grid_render_value', array($this, 'renderValue'), array(
                'is_safe'           => array('html'),
                'needs_environment' => true
            )),
            new \Twig_SimpleFunction('sylius_grid_render_action', array($this, 'renderAction'), array(
                'is_safe'           => array('html'),
                'needs_environment' => true
            )),
            new \Twig_SimpleFunction('sylius_grid_render_filter_form', array($this, 'renderFilterForm'), array(
                'is_safe'           => array('html'),
                'needs_environment' => true
            )),
        );
    }

    /**
     * @param \Twig_Environment $twig
     * @param GridView $gridView
     */
    public function render(\Twig_Environment $twig, GridView $gridView)
    {
        return $twig->render('SyliusGridBundle::_grid.html.twig', array('grid' => $gridView));
    }

    /**
     * @param \Twig_Environment $twig
     * @param GridView $gridView
     * @param $field
     * @return string
     */
    public function renderHeader(\Twig_Environment $twig, GridView $gridView, $field)
    {
        $gridDefinition = $gridView->getDefinition();
        $sorting = $gridView->getParameters()->get('sorting', array());

        $columnDefinition = $gridDefinition->getColumn($field);

        if (!$columnDefinition->isSortable()) {
            return $columnDefinition->getLabel() ?: $columnDefinition->getField();
        }

        $data = array('column' => $columnDefinition, 'sorting' => $sorting);

        if (isset($sorting[$field]) && in_array($sorting[$field], array(SorterInterface::ASC, SorterInterface::DESC))) {
            $data['direction'] = $sorting[$field];
            $data['sorting'] = array($field => SorterInterface::ASC === $sorting[$field] ? SorterInterface::DESC : SorterInterface::ASC);
        } else {
            $data['sorting'] = array($field => SorterInterface::ASC);
        }

        return $twig->render('SyliusGridBundle::_header.html.twig', $data);
    }

    /**
     * @param \Twig_Environment $twig
     * @param $object
     * @param GridView $gridView
     * @param $field
     */
    public function renderValue(\Twig_Environment $twig, $object, GridView $gridView, $field)
    {
        $gridDefinition = $gridView->getDefinition();
        $columnDefinition = $gridDefinition->getColumn($field);

        $value = $this->dataAccessor->getValue($object, $field);

        return $twig->render(sprintf('SyliusGridBundle:Column:_%s.html.twig', $columnDefinition->getType()), array('value' => $value, 'column' => $columnDefinition));
    }

    /**
     * @param \Twig_Environment $twig
     * @param $object
     * @param GridView $gridView
     * @param Action $actionDefinition
     */
    public function renderAction(\Twig_Environment $twig, $object, GridView $gridView, Action $actionDefinition)
    {
        return $twig->render(sprintf('SyliusGridBundle:Action:_%s.html.twig', $actionDefinition->getType()), array('resource' => $object, 'action' => $actionDefinition));
    }

    /**
     * @param \Twig_Environment $twig
     * @param Request $request
     * @param GridView $gridView
     */
    public function renderFilterForm(\Twig_Environment $twig, Request $request, GridView $gridView)
    {
        $gridDefinition = $gridView->getDefinition();
        $filterFormBuilder = $this->formFactory->createNamedBuilder('filters', 'form', array(), array('csrf_protection' => false));

        foreach ($gridDefinition->getFilters() as $field => $filterDefinition) {
            $filterFormBuilder->add($field, sprintf('sylius_filter_%s', $filterDefinition->getType()), $filterDefinition->getOptions());
        }

        $form = $filterFormBuilder->getForm();
        $form->submit($request);

        return $twig->render('SyliusGridBundle::_filterForm.html.twig', array('form' => $form->createView(), 'grid' => $gridView));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sylius_grid';
    }
}
