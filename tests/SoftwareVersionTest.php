<?php
/*
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2015 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */

/* Test for inc/db.function.php */

require_once 'DbTestCase.php';

class SoftwareVersionTest extends DbTestCase {

   /**
    * @covers Computer_SoftwareVersion::countForVersion
    */
   public function testCountInstall() {
      $auth = new Auth();
      $this->assertTrue($auth->Login(TU_USER, TU_PASS, true));

      $c01 = getItemByTypeName('Computer', '_test_pc01', true);
      $c11 = getItemByTypeName('Computer', '_test_pc11', true);
      $c12 = getItemByTypeName('Computer', '_test_pc12', true);
      $ver = getItemByTypeName('SoftwareVersion', '_test_softver_1', true);

      // Do some installations
      $ins = new Computer_SoftwareVersion();
      $this->assertGreaterThan(0, $ins->add([
         'computers_id'        => $c01,
         'softwareversions_id' => $ver,
      ]));
      $this->assertGreaterThan(0, $ins->add([
         'computers_id'        => $c11,
         'softwareversions_id' => $ver,
      ]));
      $this->assertGreaterThan(0, $ins->add([
         'computers_id'        => $c12,
         'softwareversions_id' => $ver,
      ]));

      // Count installations
      $this->assertTrue(Session::changeActiveEntities(getItemByTypeName('Entity', '_test_root_entity',  true), true));
      $this->assertEquals(3, Computer_SoftwareVersion::countForVersion($ver), 'count in all tree');

      $this->assertTrue(Session::changeActiveEntities(getItemByTypeName('Entity', '_test_root_entity',  true), false));
      $this->assertEquals(1, Computer_SoftwareVersion::countForVersion($ver), 'count in root');

      $this->assertTrue(Session::changeActiveEntities(getItemByTypeName('Entity', '_test_child_1',  true), false));
      $this->assertEquals(2, Computer_SoftwareVersion::countForVersion($ver), 'count in child');
   }
}
