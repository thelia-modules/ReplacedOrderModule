<?php

namespace ReplacedOrderModule\Form;

use Propel\Runtime\Exception\PropelException;
use ReplacedOrderModule\ReplacedOrderModule;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;
use Thelia\Log\Tlog;
use Thelia\Model\ModuleQuery;

class SimpleReplacedOrderModuleForm extends BaseForm
{
    protected function buildForm()
    {
        $this->initModulesList();
        $this->formBuilder
            ->add('module',
                ChoiceType::class,
                [
                    'required' => true,
                    'choices' => $this->initModulesList(),
                    'label' => Translator::getInstance()->trans('Replaced Module', [], ReplacedOrderModule::DOMAIN_NAME),
                ]);
    }

    private function initModulesList(): array
    {
        $choices = [];

        $modules = ModuleQuery::create()
            ->filterByType(ReplacedOrderModule::DELIVERY_MODULE_TYPE)
            ->_or()
            ->filterByType(ReplacedOrderModule::PAYMENT_MODULE_TYPE)
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