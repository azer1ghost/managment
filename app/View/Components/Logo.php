<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Logo extends Component
{
    public function render(): string
    {
        return /** @lang HTML */
            <<<'blade'
            <table class="mt-3 d-inline-flex">
                <tbody>
                <tr>
                    <td>
                        <svg xmlns="http://www.w3.org/2000/svg" class="animate__animated"  width="80px"
                                 style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd;  clip-rule:evenodd"
                                 viewBox="0 0 50000 50000">
                              <linearGradient id="id0" gradientUnits="userSpaceOnUse" x1="12801.63" y1="24140.65" x2="46649.1" y2="24140.65">
                               <stop offset="0" style="stop-opacity:1; stop-color:#9CCB48"/>
                              </linearGradient>
                              <path style="fill:#2B2F47;fill-rule:nonzero" d="M26231.68 37703.89l-14392.29 0 -5950.31 -7401.31 13130.45 -17416.82 13151.4 17456.39 -5939.25 7361.74zm-7214.06 -29813.07l-16911.23 22345.61 8327.79 10157.23 17203.89 0 8315.7 -10158.74 -16936.15 -22344.1z"/>
                              <polygon style="fill:url(#id0)" points="25271.85,13750.06 27147.35,16221.02 29714.72,12827.49 42866.27,30307.92 36927.02,37703.84 31876.95,37703.84 22534.73,37703.84 21352.16,36132.5 16584.22,30247.65 23466.26,21119.05 21589.68,18626.31 12801.53,30218.95 17567.41,36038 21129.52,40393.61 29505.89,40393.61 38333.4,40393.61 46649.1,30233.3 29712.95,7887.69 "/>
                        </svg>
                   </td>
                    <td class="text-center">
                        <h1 style="font-family: Sequel;">
                            <span class="animate__animated animate__fadeIn" style="color: #2B2F47; font-size: 35px; font-weight: 900">Mobil</span>
                            <span class="animate__animated animate__fadeInDown d-block" style="font-size: 20px; color: #98CF20;letter-spacing: 4px;margin-top: -10px;padding-left: 5px; font-weight: 900 ">GROUP</span>
                        </h1>
                    </td>
                </tr>
                </tbody>
            </table>
        blade;
    }
}
