<?php

namespace ReplacedOrderModule\Service;

use DateTime;
use Propel\Runtime\Exception\PropelException;
use ReplacedOrderModule\Model\ReplacedModule;
use Thelia\Model\Module;
use Thelia\Model\ModuleQuery;
use Thelia\Model\OrderQuery;

class ReplacedOrderModuleService
{
    /**
     * return the ReplacedOrderModule or Selected Module
     *
     * @param string|null $code
     * @return Module
     */
    public function getReplacedOrderModule(string $code = null): Module
    {
        if ($code){
            return ModuleQuery::create()->findOneByCode($code);
        }

        return ModuleQuery::create()->findOneByCode('ReplacedOrderModule');
    }

    /**
     * @param int $id
     * @return Module
     */
    public function getModule(int $id): Module
    {
        return ModuleQuery::create()
            ->useI18nQuery()
            ->endUse()
            ->groupById()
            ->findOneById($id);
    }

    /**
     * Add a line with all replaced Modules
     *
     * @param Module $module
     * @param Module|null $module2
     * @return void
     * @throws PropelException
     */
    public function saveModule(Module $module, Module $module2 = null): void
    {
        $replacedModule = new ReplacedModule();
        $replacedModule->setCode($module->getCode());
        $replacedModule->setTitle($module->getTitle());
        $replacedModule->setCreatedAt(new DateTime("now"));
        $replacedModule->setNewModule($module2?->getCode());
        $replacedModule->save();
    }

    /**
     * update order payment_id or delivery_id
     *
     * @param Module $module
     * @param Module|null $module2
     * @return void
     * @throws PropelException
     */
    public function updateOrder(Module $module, Module $module2 = null): void
    {
        $orders = OrderQuery::create();

        $replacedOrderModule = $this->getReplacedOrderModule($module2?->getCode());

        if ($module->getCategory() === 'payment')
        {
            $orders->filterByPaymentModuleId($module->getId())->find();

            foreach ($orders as $order)
            {
                $order->setPaymentModuleId($replacedOrderModule->getId());
                $order->save();
            }
        }

        if ($module->getCategory() === 'delivery')
        {
            $orders = OrderQuery::create()
                ->filterByDeliveryModuleId($module->getId())
                ->find();

            foreach ($orders as $order)
            {
                $order->setDeliveryModuleId($replacedOrderModule->getId());
                $order->save();
            }
        }
    }
}