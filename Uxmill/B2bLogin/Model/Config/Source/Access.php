<?php
/**
 * @package Uxmill_B2bLogin
 * @copyright uxmill.co ${date}
 * @author durga
 */
namespace Uxmill\B2bLogin\Model\Config\Source;

class Access implements \Magento\Framework\Option\ArrayInterface
{

    /**
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 1,
                'label' => __('Via Login and Register')
            ],
            [
                'value' => 0,
                'label' => __('Via Login')
            ]
        ];
    } // end toOptionArray()
}//end class
