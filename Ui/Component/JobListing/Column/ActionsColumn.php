<?php

declare(strict_types=1);

namespace Akeneo\Connector\Ui\Component\JobListing\Column;

use Akeneo\Connector\Api\Data\JobInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class ActionsColumn
 *
 * @package   Akeneo\Connector\Ui\Component\JobListing\Column
 * @author    Agence Dn'D <contact@dnd.fr>
 * @copyright 2004-present Agence Dn'D
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://www.dnd.fr/
 */
class ActionsColumn extends Column
{
    /**
     * $urlBuilder field
     *
     * @var UrlInterface $urlBuilder
     */
    private $urlBuilder;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;

        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Description prepareDataSource function
     *
     * @param mixed[][] $dataSource
     *
     * @return mixed[][]
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['entity_id'])) {
                    /** @var string $scheduleJobPath */
                    $scheduleJobPath = $this->getData('config/scheduleJobPath') ?: '#';
                    /** @var string $scheduleJobUrl */
                    $scheduleJobUrl =  $this->urlBuilder->getUrl($scheduleJobPath,['entity_id' => $item['entity_id']]);
                    /** @var string $viewJobLogPath */
                    $viewJobLogPath = $this->getData('config/viewJobLogPath') ?: '#';
                    /** @var string $viewJobLogPathFilter */
                    $viewJobLogPathFilter = base64_encode(JobInterface::CODE . '=' . $item['code']);
                    /** @var string $viewJobLogUrl */
                    $viewJobLogUrl =  $this->urlBuilder->getUrl($viewJobLogPath, ['filter' => $viewJobLogPathFilter]);

                    /** @var string $html */
                    $html = '<a href="' . $scheduleJobUrl . '">' . __('Schedule Job') . '</a> / ';
                    $html .= '<a href="' . $viewJobLogUrl . '">' . __('View Logs') . '</a>';
                    $item['actions'] = $html;
                }
            }
        }

        return $dataSource;
    }

}
