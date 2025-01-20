<?php

/**
 * ---------------------------------------------------------------------
 *
 * GLPI - Gestionnaire Libre de Parc Informatique
 *
 * http://glpi-project.org
 *
 * @copyright 2015-2025 Teclib' and contributors.
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

namespace tests\units\Glpi\System\Requirement;

use Glpi\System\Requirement\DbConfiguration;

class DbConfigurationTest extends \GLPITestCase
{
    public static function configurationProvider()
    {
        return [
            [
            // Default variables on MySQL 5.7
                'version'   => '5.7.34-standard',
                'variables' => [
                    'innodb_file_format'  => 'Barracuda',
                    'innodb_large_prefix' => 1,
                    'innodb_page_size'    => 16384,
                ],
                'validated' => true,
                'messages'  => [
                    'Database configuration is OK.',
                ]
            ],
            [
            // Incompatible variables on MySQL 5.7
                'version'   => '5.7.34-standard',
                'variables' => [
                    'innodb_file_format'  => 'Antelope', // Not a problem, will enforce Barracuda for Dynamic tables
                    'innodb_large_prefix' => 0,
                    'innodb_page_size'    => 4096,
                ],
                'validated' => false,
                'messages'  => [
                    '"innodb_large_prefix" must be enabled.',
                    '"innodb_page_size" must be >= 8KB.',
                ]
            ],
            [
            // Default variables on MySQL 8.0
                'version'   => '8.0.24-standard',
                'variables' => [
                    'innodb_page_size'    => 16384,
                ],
                'validated' => true,
                'messages'  => [
                    'Database configuration is OK.',
                ]
            ],
            [
            // "innodb_page_size=8k" config is valid too
                'version'   => '8.0.24-standard',
                'variables' => [
                    'innodb_page_size'    => 8192,
                ],
                'validated' => true,
                'messages'  => [
                    'Database configuration is OK.',
                ]
            ],
            [
            // Incompatible variables on MySQL 8.0
                'version'   => '8.0.24-standard',
                'variables' => [
                    'innodb_page_size'    => 4096,
                ],
                'validated' => false,
                'messages'  => [
                    '"innodb_page_size" must be >= 8KB.',
                ]
            ],
            [
            // Default variables on MariaDB 10.1
                'version'   => '10.1.48-MariaDB',
                'variables' => [
                    'innodb_file_format'  => 'Antelope',
                    'innodb_large_prefix' => 0,
                    'innodb_page_size'    => 16384,
                ],
                'validated' => false,
                'messages'  => [
                    '"innodb_large_prefix" must be enabled.',
                ]
            ],
            [
            // Required variables on MariaDB 10.1
                'version'   => '10.1.48-MariaDB',
                'variables' => [
                    'innodb_file_format'  => 'Antelope',
                    'innodb_large_prefix' => 1,
                    'innodb_page_size'    => 16384,
                ],
                'validated' => true,
                'messages'  => [
                    'Database configuration is OK.',
                ]
            ],
            [
            // Incompatible variables on MariaDB 10.1
                'version'   => '10.1.48-MariaDB',
                'variables' => [
                    'innodb_file_format'  => 'Antelope', // Not a problem, will enforce Barracuda for Dynamic tables
                    'innodb_large_prefix' => 0,
                    'innodb_page_size'    => 4096,
                ],
                'validated' => false,
                'messages'  => [
                    '"innodb_large_prefix" must be enabled.',
                    '"innodb_page_size" must be >= 8KB.',
                ]
            ],
            [
            // Default variables on MariaDB 10.2
                'version'   => '10.2.36-MariaDB',
                'variables' => [
                    'innodb_file_format'  => 'Barracuda',
                    'innodb_large_prefix' => 1,
                    'innodb_page_size'    => 16384,
                ],
                'validated' => true,
                'messages'  => [
                    'Database configuration is OK.',
                ]
            ],
            [
            // Incompatible variables on MariaDB 10.2
                'version'   => '10.2.36-MariaDB',
                'variables' => [
                    'innodb_file_format'  => 'Antelope', // Not a problem, will enforce Barracuda for Dynamic tables
                    'innodb_large_prefix' => 0,
                    'innodb_page_size'    => 4096,
                ],
                'validated' => false,
                'messages'  => [
                    '"innodb_large_prefix" must be enabled.',
                    '"innodb_page_size" must be >= 8KB.',
                ]
            ],
            [
            // Default variables on MariaDB 10.3
                'version'   => '10.3.27-MariaDB',
                'variables' => [
                    'innodb_page_size'    => 16384,
                ],
                'validated' => true,
                'messages'  => [
                    'Database configuration is OK.',
                ]
            ],
            [
            // Incompatible variables on MariaDB 10.3
                'version'   => '10.3.27-MariaDB',
                'variables' => [
                    'innodb_page_size'    => 4096,
                ],
                'validated' => false,
                'messages'  => [
                    '"innodb_page_size" must be >= 8KB.',
                ]
            ],
            [
            // Default variables on MariaDB 10.4
                'version'   => '10.4.17-MariaDB',
                'variables' => [
                    'innodb_page_size'    => 16384,
                ],
                'validated' => true,
                'messages'  => [
                    'Database configuration is OK.',
                ]
            ],
            [
            // Incompatible variables on MariaDB 10.4
                'version'   => '10.4.17-MariaDB',
                'variables' => [
                    'innodb_page_size'    => 4096,
                ],
                'validated' => false,
                'messages'  => [
                    '"innodb_page_size" must be >= 8KB.',
                ]
            ],
            [
            // Default variables on MariaDB 10.5
                'version'   => '10.5.8-MariaDB',
                'variables' => [
                    'innodb_page_size'    => 16384,
                ],
                'validated' => true,
                'messages'  => [
                    'Database configuration is OK.',
                ]
            ],
            [
            // Incompatible variables on MariaDB 10.5
                'version'   => '10.5.8-MariaDB',
                'variables' => [
                    'innodb_page_size'    => 4096,
                ],
                'validated' => false,
                'messages'  => [
                    '"innodb_page_size" must be >= 8KB.',
                ]
            ],
        ];
    }

    /**
     * @dataProvider configurationProvider
     */
    public function testCheck(string $version, array $variables, bool $validated, array $messages)
    {

        $that = $this;

        $db = $this->getMockBuilder(\DB::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getVersion', 'doQuery'])
            ->getMock();

        $db->method('getVersion')->willReturn($version);
        $db->method('doQuery')->willReturnCallback(
            function ($query) use ($that, $variables) {
                $matches = [];
                if (preg_match_all('/@@GLOBAL\.`(?<name>[^`]+)`/', $query, $matches) > 0) {
                    $row = [];
                    foreach ($matches['name'] as $name) {
                        $row[$name] = $variables[$name] ?? null;
                    }
                    $res = $this->getMockBuilder(\mysqli_result::class)
                        ->disableOriginalConstructor()
                        ->onlyMethods(['fetch_assoc'])
                        ->getMock();
                    $res->method('fetch_assoc')->willReturn($row);
                    return $res;
                }
                return false;
            }
        );

        $instance = new DbConfiguration($db);
        $this->assertEquals($validated, $instance->isValidated());
        $this->assertEquals(
            $messages,
            $instance->getValidationMessages()
        );
    }
}