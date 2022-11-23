<?php

namespace ReplacedOrderModule\Service;

use Propel\Runtime\Exception\PropelException;
use ReplacedOrderModule\Model\ReplacedModule;
use Thelia\Model\Module;
use Thelia\Model\ModuleQuery;
use Thelia\Model\OrderQuery;

class ReplacedOrderModuleService
{
    const REPLACE_ORDER_MODULE = 'action.replace.order.module.service';

    /**
     * @return Module
     */
    public function getReplacedOrderModule(): Module
    {
        return ModuleQuery::create()
            ->findOneByCode('ReplacedOrderModule');
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
     * @param $module
     * @return void
     * @throws PropelException
     */
    public function saveModule(Module $module)
    {
        $replacedModule = new ReplacedModule();
        $replacedModule->setCode($module->getCode());
        $replacedModule->setTitle($module->getTitle());
        $replacedModule->setCreatedAt(new \DateTime("now"));
        $replacedModule->save();
    }

    /**
     * @param $module
     * @return void
     * @throws PropelException
     */
    public function updateOrder(Module $module)
    {
        $orders = OrderQuery::create();
        $replacedOrderModule = $this->getReplacedOrderModule();

        if ($module->getCategory() == 'payment') {
            $orders->filterByPaymentModuleId($module->getId())
                ->find();
            foreach ($orders as $order) {
                $order->setPaymentModuleId($replacedOrderModule->getId());
                $order->save();
            }
        }

        if ($module->getCategory() == 'delivery') {
            $orders = OrderQuery::create()
                ->filterByDeliveryModuleId($module->getId())
                ->find();

            foreach ($orders as $order) {
                $order->setDeliveryModuleId($replacedOrderModule->getId());
                $order->save();
            }
        }
    }
}