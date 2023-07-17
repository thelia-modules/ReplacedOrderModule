<?php

namespace ReplacedOrderModule\Form;

use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;
use Thelia\Log\Tlog;
use Thelia\Model\ModuleQuery;

class ReplacedOrderModuleForm extends BaseForm
{
    protected function buildForm()
    {
        $this->initModulesList();
        $this->formBuilder
            ->add('modules',
                ChoiceType::class,
                [
                    'required' => true,
                    'choices' => $this->initModulesList(),
                    'label' => Translator::getInstance()->trans('Replaced Module'),
                ]);
    }

    private function initModulesList(): array
    {
        $choices = [];

        $modules = ModuleQuery::create()
            ->filterByType(2)
            ->_or()
            ->filterByType(3)
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