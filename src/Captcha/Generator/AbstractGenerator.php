<?php

namespace Igoshev\Captcha\Captcha\Generator;

abstract class AbstractGenerator
{
    /**
     * Color converter cache.
     *
     * @var array
     */
    private $colorCache = [];

    /**
     * Color converter - HEX to RGB.
     *
     * @param string $hex Color.
     * @return array
     */
    protected function hexToRgb($hex)
    {
        if (! isset($this->colorCache[$hex])) {
            $this->colorCache[$hex] = [
                'r' => hexdec(substr($hex, 0, 2)),
                'g' => hexdec(substr($hex, 2, 2)),
                'b' => hexdec(substr($hex, 4, 2))
            ];
        }

        return $this->colorCache[$hex];
    }

    /**
     * Draw scratches.
     *
     * @param resource $img
     * @param int $imageWidth
     * @param int $imageHeight
     * @param string $hex
     */
    protected function drawScratch($img, $imageWidth, $imageHeight, $hex)
    {
        $rgb = $this->hexToRgb($hex);

        imageline(
            $img,
            mt_rand(0, floor($imageWidth / 2)),
            mt_rand(1, $imageHeight),
            mt_rand(floor($imageWidth / 2), $imageWidth),
            mt_rand(1, $imageHeight),
            imagecolorallocate($img, $rgb['r'], $rgb['g'], $rgb['b'])
        );
    }
}
