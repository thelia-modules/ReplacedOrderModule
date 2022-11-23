<?php

namespace ReplacedOrderModule\Form;

use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Thelia\Core\Translation\Translator;
use Thelia\Log\Tlog;
use Thelia\Model\Lang;
use Thelia\Model\ModuleQuery;
use TheliaEmailManager\Form\BaseForm;

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

    public function getName(){
        return 'replaced_order_form';
    }

    private function initModulesList(){
        $choices = [];

        $modules = ModuleQuery::create()
            ->filterByType(2)
            ->_or()
            ->filterByType(3)
            ->find();

        foreach ($modules as $module) {
            try {
                $choices[$module->getId()] = $module->getCode();
            } catch (PropelException $e) {
                Tlog::getInstance()->error($e->getMessage());
            }
        }
        return $choices;
    }
}