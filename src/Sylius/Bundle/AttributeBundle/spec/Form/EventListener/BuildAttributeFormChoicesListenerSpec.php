<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\AttributeBundle\Form\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Attribute\Model\AttributeTypes;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * @author Leszek Prabucki <leszek.prabucki@gmail.com>
 */
class BuildAttributeFormChoicesListenerSpec extends ObjectBehavior
{
    function let(FormFactoryInterface $formFactory)
    {
        $this->beConstructedWith($formFactory);
    }

    function it_subscribes_to_pre_set_data_and_pre_submit_events()
    {
        self::getSubscribedEvents()->shouldReturn(array(
            'form.pre_set_data' => 'buildChoices',
            'form.pre_bind'     => 'buildConfiguration',
        ));
    }

    function it_does_no_not_build_choices_collection_for_null(
        FormEvent $event,
        Form $form,
        $formFactory
    ) {
        $event->getData()->willReturn(null);
        $event->getForm()->willReturn($form);

        $formFactory
            ->createNamed(Argument::any())
            ->shouldNotBeCalled()
        ;
        $form->add(Argument::any())->shouldNotBeCalled();

        $this->buildChoices($event);
    }

    function it_builds_choices_collection_for_new_object_without_type(
        FormEvent $event,
        Form $form,
        AttributeInterface $attribute,
        Form $collectionField,
        $formFactory
    ) {
        $event->getData()->willReturn($attribute);
        $event->getForm()->willReturn($form);

        $attribute->getType()->willReturn(null);
        $attribute->getConfiguration()->willReturn(array());

        $formFactory
            ->createNamed('choices', 'collection', null, array(
                'type'              => 'text',
                'allow_add'         => true,
                'allow_delete'      => true,
                'by_reference'      => false,
                'auto_initialize'   => false,
                'mapped'            => false,
                'data'              => null,
            ))
            ->willReturn($collectionField)
            ->shouldBeCalled()
        ;
        $form->add($collectionField)->shouldBeCalled()->willReturn($form);

        $this->buildChoices($event);
    }

    function it_builds_choices_collection_for_choice_attribute(
        FormEvent $event,
        Form $form,
        AttributeInterface $attribute,
        Form $collectionField,
        $formFactory
    ) {
        $event->getData()->willReturn($attribute);
        $event->getForm()->willReturn($form);

        $attribute->getType()->willReturn(AttributeTypes::CHOICE);
        $attribute->getConfiguration()->willReturn(array());

        $formFactory
            ->createNamed('choices', 'collection', null, array(
                'type'              => 'text',
                'allow_add'         => true,
                'allow_delete'      => true,
                'by_reference'      => false,
                'auto_initialize'   => false,
                'mapped'            => false,
                'data'              => null,
            ))
            ->willReturn($collectionField)
            ->shouldBeCalled()
        ;
        $form->add($collectionField)->shouldBeCalled()->willReturn($form);

        $this->buildChoices($event);
    }

    function it_does_not_build_choices_collection_for_other_than_choice_attribute_types(
        FormEvent $event,
        Form $form,
        AttributeInterface $attribute,
        Form $collectionField,
        $formFactory
    ) {
        $attribute->getType()->willReturn(AttributeTypes::TEXT);

        $event->getData()->willReturn($attribute);
        $event->getForm()->willReturn($form);

        $formFactory
            ->createNamed('choices', 'collection', null, Argument::any())
            ->willReturn($collectionField)
            ->shouldNotBeCalled()
        ;
        $form->add(Argument::any())->shouldNotBeCalled();

        $this->buildChoices($event);
    }

    function it_build_configuration_collection_type(
        FormEvent $event,
        Form $form,
        Form $collectionField,
        $formFactory
    ) {
        $event->getData()->willReturn(array('type' => 'foo'));
        $event->getForm()->willReturn($form);
        $form->has('configuration')->willReturn(false);

        $formFactory
            ->createNamed('configuration', 'collection', null, array(
                'allow_add'         => true,
                'allow_delete'      => true,
                'by_reference'      => false,
                'auto_initialize'   => false,
            ))
            ->willReturn($collectionField)
            ->shouldBeCalled()
        ;

        $form->add($collectionField)->shouldBeCalled()->willReturn($form);
        $event->setData(Argument::any())->shouldBeCalled();

        $this->buildConfiguration($event);
    }
}
