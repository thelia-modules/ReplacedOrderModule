<?php

namespace ReplacedOrderModule\Controller;

use ReplacedOrderModule\Service\ReplacedOrderModuleService;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;

class ReplacedOrderModule extends BaseAdminController
{
    public function replaceOrderModule()
    {
        if (null !== $response = $this->checkAuth([AdminResources::MODULE], ["CustomerTools"], AccessManager::UPDATE)) {
            return $response;
        }

        $form = $this->createForm('replaced_order_module_form');

        try {
            $data = $this->validateForm($form)->getData();

            /** @var ReplacedOrderModuleService $replacedOrderModuleService */
            $replacedOrderModuleService = $this->getContainer()->get('action.replace.order.module.service');

            $module = $replacedOrderModuleService->getModule($data["modules"]);

            $replacedOrderModuleService->saveModule($module);
            $replacedOrderModuleService->updateOrder($module);

            return $this->generateSuccessRedirect($form);
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
        }

        $form->setErrorMessage($error_message);

        $this->getParserContext()
            ->addForm($form)
            ->setGeneralError($error_message);

        return $this->generateErrorRedirect($form);
    }
}