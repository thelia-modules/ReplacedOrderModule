<?php

namespace ReplacedOrderModule\Controller;

use ReplacedOrderModule\Form\ReplacedOrderModuleForm;
use ReplacedOrderModule\Form\SimpleReplacedOrderModuleForm;
use ReplacedOrderModule\Service\ReplacedOrderModuleService;
use Symfony\Component\Routing\Annotation\Route;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Template\ParserContext;

#[Route('/admin/module/ReplacedOrderModule', name: 'replaced_order_')]
class ReplacedOrderModule extends BaseAdminController
{
    #[Route('/replacedordermodule', name: 'replace_order_module')]
    public function replaceOrderModule(
        ParserContext $parserContext,
        ReplacedOrderModuleService $replacedOrderModuleService
    )
    {
        if (null !== $response = $this->checkAuth([AdminResources::MODULE], ["CustomerTools"], AccessManager::UPDATE)) {
            return $response;
        }

        $form = $this->createForm(ReplacedOrderModuleForm::getName());

        try {
            $data = $this->validateForm($form)->getData();

            $module = $replacedOrderModuleService->getModule($data["modules"]);
            $module2 = $replacedOrderModuleService->getModule($data["modules2"]);

            $replacedOrderModuleService->saveModule($module, $module2);

            $replacedOrderModuleService->updateOrder($module, $module2);

            return $this->generateSuccessRedirect($form);
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
        }

        $form->setErrorMessage($error_message);

        $parserContext
            ->addForm($form)
            ->setGeneralError($error_message);

        return $this->generateErrorRedirect($form);
    }

    #[Route('/simplereplacedordermodule', name: 'simple_replace_order_module')]
    public function simpleReplaceOrderModule(
        ParserContext $parserContext,
        ReplacedOrderModuleService $replacedOrderModuleService
    )
    {
        if (null !== $response = $this->checkAuth([AdminResources::MODULE], ["CustomerTools"], AccessManager::UPDATE)) {
            return $response;
        }

        $form = $this->createForm(SimpleReplacedOrderModuleForm::getName());

        try {
            $data = $this->validateForm($form)->getData();

            $module = $replacedOrderModuleService->getModule($data["modules"]);

            $replacedOrderModuleService->saveModule($module);

            $replacedOrderModuleService->updateOrder($module);

            return $this->generateSuccessRedirect($form);
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
        }

        $form->setErrorMessage($error_message);

        $parserContext
            ->addForm($form)
            ->setGeneralError($error_message);

        return $this->generateErrorRedirect($form);
    }
}