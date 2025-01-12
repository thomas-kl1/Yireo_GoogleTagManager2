<?php declare(strict_types=1);

namespace Yireo\GoogleTagManager2\SessionDataProvider;

use Magento\Customer\Model\Session as CustomerSession;
use Yireo\GoogleTagManager2\Api\CustomerSessionDataProviderInterface;
use Yireo\GoogleTagManager2\Logger\Debugger;
use Yireo\GoogleTagManager2\ViewModel\DataLayer;

class CustomerSessionDataProvider implements CustomerSessionDataProviderInterface
{
    private CustomerSession $customerSession;
    private Debugger $debugger;
    private DataLayer $dataLayer;

    public function __construct(
        CustomerSession $customerSession,
        Debugger $debugger,
        DataLayer $dataLayer
    ) {
        $this->customerSession = $customerSession;
        $this->debugger = $debugger;
        $this->dataLayer = $dataLayer;
    }

    public function add(string $identifier, array $data)
    {
        $gtmData = $this->get();
        $gtmData[$identifier] = $data;
        $this->debugger->debug('CustomerSessionDataProvider::add(): ' . $identifier, $data);
        $this->customerSession->setYireoGtmData($gtmData);
    }

    public function get(): array
    {
        $gtmData = $this->customerSession->getYireoGtmData();
        if (is_array($gtmData)) {
            return $gtmData;
        }

        return [];
    }

    public function clear()
    {
        $this->customerSession->setYireoGtmData([]);
    }
}
