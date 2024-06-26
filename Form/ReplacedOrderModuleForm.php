<?php

namespace ReplacedOrderModule\Form;

use Propel\Runtime\Exception\PropelException;
use ReplacedOrderModule\ReplacedOrderModule;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;
use Thelia\Log\Tlog;
use Thelia\Model\ModuleQuery;
use Thelia\Module\BaseModule;

class ReplacedOrderModuleForm extends BaseForm
{
    protected function buildForm(): void
    {
        $this->initModulesList();
        $this->formBuilder
            ->add('module', ChoiceType::class,
                [
                    'required' => true,
                    'choices' => $this->initModulesList(),
                    'label' => Translator::getInstance()->trans('Replaced Module', [], ReplacedOrderModule::DOMAIN_NAME),
                ])
            ->add('newModule', ChoiceType::class,
                [
                    'required' => true,
                    'choices' => $this->initModulesList(),
                    'label' => Translator::getInstance()->trans('New Module', [], ReplacedOrderModule::DOMAIN_NAME),
                ])
        ;
    }

    private function initModulesList(): array
    {
        $choices = [];

        $modules = ModuleQuery::create()
            ->filterByType(BaseModule::DELIVERY_MODULE_TYPE)
            ->_or()
            ->filterByType(BaseModule::PAYMENT_MODULE_TYPE)
            ->orderByCode()
            ->find();

        foreach ($modules as $module) {
            try {
                $choices[$module->getCode()] = $module->getId();
            } catch (PropelException $e) {
                Tlog::getInstance()->error($e->getMessage());
            }
        }
        return $choices;
    }
}