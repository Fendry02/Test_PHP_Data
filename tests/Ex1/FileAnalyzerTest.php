<?php

namespace Socloz\Recruitment\Test\Ex1;

use PHPUnit\Framework\TestCase;
use Socloz\Recruitment\Ex1\FileAnalyzer;

/**
 * @runTestsInSeparateProcesses
 */
class FileAnalyzerTest extends TestCase
{
    public function testIntegrate()
    {
        $tempDir = realpath(dirname(__FILE__) . '/../..') . '/tmp';
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0777);
        }

        $dataFileName = $tempDir . '/data.txt';
        if (!file_exists($dataFileName)) {
            $fp = fopen($dataFileName, 'w+');
            mt_srand(0);
            for ($store = 124; $store < 309; $store++) {
                for ($sku = 12953; $sku < 38364; $sku++) {
                    fwrite($fp, implode(',', [
                        $store,
                        $sku,
                        max(0, (int) self::gaussianRand(5, 3))
                    ]) . "\n");
                }
            }

            fclose($fp);
        }

        $fileAnalyzer = new FileAnalyzer();

        $startMem = memory_get_peak_usage(false);
        $stats = $fileAnalyzer->generateStats($dataFileName);
        $endMem = memory_get_peak_usage(false);

        $expectedStatsFileName = dirname(__FILE__) . '/expected_stats.json';
        $expectedStats = json_decode(file_get_contents($expectedStatsFileName), true);

        $this->assertEquals($expectedStats, $stats, '', 0.04);

        $usedMemMB = ($endMem - $startMem) / (1024 * 1024);

        $memErrThresholdMB = 20;
        $this->assertLessThan(
            $memErrThresholdMB,
            $usedMemMB,
            sprintf(
               'Too much memory used (%.2fMB), memory consumption must be lower than %dMB.',
               $usedMemMB,
               $memErrThresholdMB
            )
        );

        $memWarnThresholdMB = 6;
        if ($usedMemMB >= $memWarnThresholdMB) {
           throw new \PHPUnit_Framework_Warning(sprintf(
               'Too much memory used (%.2fMB), memory consumption should ideally be lower than %dMB.',
               $usedMemMB,
               $memWarnThresholdMB
            ));
        }
    }

    /**
     * @param float $mean
     * @param float $stdDev
     * @return float
     */
    private static function gaussianRand($mean, $stdDev)
    {
        $x = mt_rand() / mt_getrandmax();
        $y = mt_rand() / mt_getrandmax();

        return
            sqrt(-2 * log($x))
                * cos(2 * pi() * $y)
                * $stdDev
                + $mean
        ;
    }
}
