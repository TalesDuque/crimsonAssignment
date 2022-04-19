<?php

declare(strict_types=1);

namespace CrimsonAgility\CustomerSortProducts\Controller\Account;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Result\PageFactory;

class ProductsByRange implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    private PageFactory $resultPageFactory;

    /**
     * @var Session 
     */
    private Session $session;

    /**
     * @var UrlInterface 
     */
    private UrlInterface $url;

    /**
     * Products By Range Controller constructor
     *
     * @param PageFactory $resultPageFactory
     * @param Session $session
     * @param UrlInterface $url
     */
    public function __construct(
        PageFactory $resultPageFactory,
        Session $session,
        UrlInterface $url
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->session = $session;
        $this->url = $url;
    }

    /**
     * @return ResultInterface
     * @throws \Magento\Framework\Exception\SessionException
     */
    public function execute(): ResultInterface
    {
        if (!$this->session->isLoggedIn()) {
            $this->session->setAfterAuthUrl($this->url->getCurrentUrl());
            $this->session->authenticate();
        }
        return $this->resultPageFactory->create();
    }
}
