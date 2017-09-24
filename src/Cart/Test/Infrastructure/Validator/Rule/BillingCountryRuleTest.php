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

namespace Shopware\Cart\Test\Infrastructure\Validator\Rule;

use PHPUnit\Framework\TestCase;
use Shopware\AreaCountry\Struct\AreaCountryBasicStruct;
use Shopware\Cart\Cart\CalculatedCart;
use Shopware\CartBridge\Rule\BillingCountryRule;
use Shopware\Address\Struct\Address;
use Shopware\Customer\Struct\CustomerBasicStruct;
use Shopware\CustomerAddress\Struct\CustomerAddressBasicStruct;
use Shopware\Framework\Struct\StructCollection;
use Shopware\Context\Struct\ShopContext;
use Shopware\Country\Struct\Country;
use Shopware\Customer\Struct\Customer;

class BillingCountryRuleTest extends TestCase
{
    public function testWithExactMatch(): void
    {
        $rule = new BillingCountryRule(['SWAG-AREA-COUNTRY-UUID-1']);

        $cart = $this->createMock(CalculatedCart::class);

        $context = $this->createMock(ShopContext::class);

        $country = new AreaCountryBasicStruct();
        $country->setUuid('SWAG-AREA-COUNTRY-UUID-1');
        $country->setAreaUuid('SWAG-AREA-UUID-1');

        $billing = new CustomerAddressBasicStruct();
        $billing->setCountry($country);

        $customer = new CustomerBasicStruct();
        $customer->setDefaultBillingAddress($billing);

        $context->expects($this->any())
            ->method('getCustomer')
            ->will($this->returnValue($customer));

        $this->assertTrue(
            $rule->match($cart, $context, new StructCollection())->matches()
        );
    }

    public function testWithNotMatch(): void
    {
        $rule = new BillingCountryRule(['SWAG-AREA-COUNTRY-UUID-2']);

        $cart = $this->createMock(CalculatedCart::class);

        $context = $this->createMock(ShopContext::class);

        $country = new AreaCountryBasicStruct();
        $country->setUuid('SWAG-AREA-COUNTRY-UUID-1');
        $country->setAreaUuid('SWAG-AREA-UUID-1');

        $billing = new CustomerAddressBasicStruct();
        $billing->setCountry($country);

        $customer = new CustomerBasicStruct();
        $customer->setDefaultBillingAddress($billing);

        $context->expects($this->any())
            ->method('getCustomer')
            ->will($this->returnValue($customer));

        $this->assertFalse(
            $rule->match($cart, $context, new StructCollection())->matches()
        );
    }

    public function testMultipleCountries(): void
    {
        $rule = new BillingCountryRule(['SWAG-AREA-COUNTRY-UUID-1', 'SWAG-AREA-COUNTRY-UUID-3', 'SWAG-AREA-COUNTRY-UUID-2']);

        $cart = $this->createMock(CalculatedCart::class);

        $context = $this->createMock(ShopContext::class);

        $country = new AreaCountryBasicStruct();
        $country->setUuid('SWAG-AREA-COUNTRY-UUID-1');
        $country->setAreaUuid('SWAG-AREA-UUID-1');

        $billing = new CustomerAddressBasicStruct();
        $billing->setCountry($country);

        $customer = new CustomerBasicStruct();
        $customer->setDefaultBillingAddress($billing);

        $context->expects($this->any())
            ->method('getCustomer')
            ->will($this->returnValue($customer));

        $this->assertTrue(
            $rule->match($cart, $context, new StructCollection())->matches()
        );
    }

    public function testWithoutCustomer(): void
    {
        $rule = new BillingCountryRule(['SWAG-AREA-COUNTRY-UUID-1', 'SWAG-AREA-COUNTRY-UUID-3', 'SWAG-AREA-COUNTRY-UUID-2']);

        $cart = $this->createMock(CalculatedCart::class);

        $context = $this->createMock(ShopContext::class);

        $context->expects($this->any())
            ->method('getCustomer')
            ->will($this->returnValue(null));

        $this->assertFalse(
            $rule->match($cart, $context, new StructCollection())->matches()
        );
    }
}
