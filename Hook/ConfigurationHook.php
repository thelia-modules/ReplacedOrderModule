<?php

namespace ReplacedOrderModule\Hook;

use ReplacedOrderModule\Form\ReplacedOrderModuleForm;
use ReplacedOrderModule\Form\SimpleReplacedOrderModuleForm;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Form\TheliaFormFactory;
use Thelia\Core\Hook\BaseHook;
use Thelia\Core\Template\Parser\ParserResolver;

class ConfigurationHook extends BaseHook
{
    public function __construct(
        private readonly TheliaFormFactory $formFactory,
        ?EventDispatcherInterface $dispatcher = null,
        ?ParserResolver $parserResolver = null,
    ) {
        parent::__construct($dispatcher, $parserResolver);
    }

    public function onModuleConfiguration(HookRenderEvent $event): void
    {
        $simpleForm = $this->formFactory->createForm(SimpleReplacedOrderModuleForm::getName());
        $simpleForm->createView();

        $replacedForm = $this->formFactory->createForm(ReplacedOrderModuleForm::getName());
        $replacedForm->createView();

        $event->add($this->render('ReplacedOrderModule/module_configuration.html.twig', [
            'simpleForm' => $simpleForm->getView(),
            'replacedForm' => $replacedForm->getView(),
        ]));
    }

    public static function getSubscribedHooks(): array
    {
        return [
            'module.configuration' => [
                [
                    'type' => 'back',
                    'method' => 'onModuleConfiguration',
                ],
            ],
        ];
    }
}
