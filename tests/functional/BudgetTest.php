<?php

/**
 * ---------------------------------------------------------------------
 *
 * GLPI - Gestionnaire Libre de Parc Informatique
 *
 * http://glpi-project.org
 *
 * @copyright 2015-2026 Teclib' and contributors.
 * @copyright 2003-2014 by the INDEPNET Development Team.
 * @licence   https://www.gnu.org/licenses/gpl-3.0.html
 *
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of GLPI.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * ---------------------------------------------------------------------
 */

namespace tests\units;

use Glpi\Tests\DbTestCase;

class BudgetTest extends DbTestCase
{
    public function testCountForBudget()
    {
        $this->login();

        $budget = new \Budget();
        $budgets_id = $budget->add([
            'name'        => 'Test Budget ' . $this->getUniqueString(),
            'entities_id' => $this->getTestRootEntity(true),
            'value'       => 10000,
        ]);
        $this->assertGreaterThan(0, $budgets_id);
        $this->assertTrue($budget->getFromDB($budgets_id));

        $count = \Budget::countForBudget($budget);
        $this->assertEquals(0, $count);

        $computer = new \Computer();
        $computers_id = $computer->add([
            'name'        => 'Test Computer ' . $this->getUniqueString(),
            'entities_id' => $this->getTestRootEntity(true),
        ]);
        $this->assertGreaterThan(0, $computers_id);

        $infocom = new \Infocom();
        $infocoms_id = $infocom->add([
            'itemtype'    => 'Computer',
            'items_id'    => $computers_id,
            'budgets_id'  => $budgets_id,
            'buy_date'    => date('Y-m-d'),
            'value'       => 1000,
        ]);
        $this->assertGreaterThan(0, $infocoms_id);

        $this->assertTrue($budget->getFromDB($budgets_id));
        $count = \Budget::countForBudget($budget);
        $this->assertEquals(1, $count);

        $computer2 = new \Computer();
        $computers_id2 = $computer2->add([
            'name'        => 'Test Computer 2 ' . $this->getUniqueString(),
            'entities_id' => $this->getTestRootEntity(true),
        ]);
        $this->assertGreaterThan(0, $computers_id2);

        $infocoms_id2 = $infocom->add([
            'itemtype'    => 'Computer',
            'items_id'    => $computers_id2,
            'budgets_id'  => $budgets_id,
            'buy_date'    => date('Y-m-d'),
            'value'       => 2000,
        ]);
        $this->assertGreaterThan(0, $infocoms_id2);

        $this->assertTrue($budget->getFromDB($budgets_id));
        $count = \Budget::countForBudget($budget);
        $this->assertEquals(2, $count);
    }

    public function testGetTabNameWithCounter()
    {
        $this->login();

        $budget = new \Budget();
        $budgets_id = $budget->add([
            'name'        => 'Test Budget ' . $this->getUniqueString(),
            'entities_id' => $this->getTestRootEntity(true),
            'value'       => 10000,
        ]);
        $this->assertGreaterThan(0, $budgets_id);
        $this->assertTrue($budget->getFromDB($budgets_id));

        $computer = new \Computer();
        $computers_id = $computer->add([
            'name'        => 'Test Computer ' . $this->getUniqueString(),
            'entities_id' => $this->getTestRootEntity(true),
        ]);
        $this->assertGreaterThan(0, $computers_id);

        $infocom = new \Infocom();
        $infocoms_id = $infocom->add([
            'itemtype'    => 'Computer',
            'items_id'    => $computers_id,
            'budgets_id'  => $budgets_id,
            'buy_date'    => date('Y-m-d'),
            'value'       => 1000,
        ]);
        $this->assertGreaterThan(0, $infocoms_id);

        $_SESSION['glpishow_count_on_tabs'] = 1;

        $this->assertTrue($budget->getFromDB($budgets_id));
        $tabs = $budget->getTabNameForItem($budget);

        $this->assertIsArray($tabs);
        $this->assertArrayHasKey(2, $tabs);

        $tab_html = $tabs[2];
        $this->assertStringContainsString('badge', $tab_html);
        $this->assertStringContainsString('1', $tab_html);

        $_SESSION['glpishow_count_on_tabs'] = 0;
        $tabs = $budget->getTabNameForItem($budget);

        $this->assertIsArray($tabs);
        $this->assertArrayHasKey(2, $tabs);

        $tab_html = $tabs[2];
        $this->assertStringNotContainsString('glpi-badge', $tab_html);
    }

    public function testCountForBudgetWithContract()
    {
        $this->login();

        $budget = new \Budget();
        $budgets_id = $budget->add([
            'name'        => 'Test Budget ' . $this->getUniqueString(),
            'entities_id' => $this->getTestRootEntity(true),
            'value'       => 50000,
        ]);
        $this->assertGreaterThan(0, $budgets_id);
        $this->assertTrue($budget->getFromDB($budgets_id));

        $contract = new \Contract();
        $contracts_id = $contract->add([
            'name'        => 'Test Contract ' . $this->getUniqueString(),
            'entities_id' => $this->getTestRootEntity(true),
        ]);
        $this->assertGreaterThan(0, $contracts_id);

        $contractCost = new \ContractCost();
        $cost_id = $contractCost->add([
            'contracts_id' => $contracts_id,
            'budgets_id'   => $budgets_id,
            'cost'         => 5000,
            'name'         => 'Test cost',
        ]);
        $this->assertGreaterThan(0, $cost_id);

        $this->assertTrue($budget->getFromDB($budgets_id));
        $count = \Budget::countForBudget($budget);
        $this->assertEquals(1, $count);
    }

    public function testCountForBudgetWithoutPermissions()
    {
        $this->login();

        $budget = new \Budget();
        $budgets_id = $budget->add([
            'name'        => 'Test Budget ' . $this->getUniqueString(),
            'entities_id' => $this->getTestRootEntity(true),
            'value'       => 10000,
        ]);
        $this->assertGreaterThan(0, $budgets_id);

        $computer = new \Computer();
        $computers_id = $computer->add([
            'name'        => 'Test Computer ' . $this->getUniqueString(),
            'entities_id' => $this->getTestRootEntity(true),
        ]);
        $this->assertGreaterThan(0, $computers_id);

        $infocom = new \Infocom();
        $infocoms_id = $infocom->add([
            'itemtype'    => 'Computer',
            'items_id'    => $computers_id,
            'budgets_id'  => $budgets_id,
            'buy_date'    => date('Y-m-d'),
            'value'       => 1000,
        ]);
        $this->assertGreaterThan(0, $infocoms_id);

        $budget_no_read = new \Budget();
        $budget_no_read->fields = ['id' => 999999];

        $count = \Budget::countForBudget($budget_no_read);
        $this->assertEquals(0, $count);
    }
}
