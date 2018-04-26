<?php
/**
 * Shopware 5
 * Copyright (c) shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

namespace Shopware\Bundle\BenchmarkBundle\Service;

use Shopware\Models\Benchmark\BenchmarkConfig;
use Shopware\Models\Benchmark\Repository as BenchmarkRepository;

class BenchmarkStatisticsService
{
    /**
     * @var StatisticsService
     */
    private $benchmark;

    /**
     * @var BenchmarkRepository
     */
    private $benchmarkRepository;

    /**
     * @var \DateInterval
     */
    private $interval;

    /**
     * @var BusinessIntelligenceService
     */
    private $statistics;

    /**
     * @param StatisticsService           $benchmark
     * @param BenchmarkRepository         $benchmarkRepository
     * @param BusinessIntelligenceService $statistics
     * @param \DateInterval|null          $interval
     *
     * @throws \Exception
     */
    public function __construct(
        BenchmarkRepository $benchmarkRepository,
        StatisticsService $benchmark,
        BusinessIntelligenceService $statistics,
        \DateInterval $interval = null)
    {
        $this->benchmarkRepository = $benchmarkRepository;
        $this->benchmark = $benchmark;
        $this->statistics = $statistics;
        $this->interval = $interval ?: new \DateInterval('P1D');
    }

    public function sendBenchmarkData()
    {
        /** @var BenchmarkConfig $benchmarkConfig */
        $benchmarkConfig = $this->benchmarkRepository->getMainConfig();

        $now = new \DateTime('now', new \DateTimeZone('UTC'));

        if ($benchmarkConfig->isActive() &&
            $benchmarkConfig->getLastReceived()->add($this->interval) > $now
        ) {
            $this->statistics->transmit();
        }

        if ($benchmarkConfig->isActive() &&
            $benchmarkConfig->getLastSent()->add($this->interval) > $now
        ) {
            $this->benchmark->transmit();
        }
    }
}
