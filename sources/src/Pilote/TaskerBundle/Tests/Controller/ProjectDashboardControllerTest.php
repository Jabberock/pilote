<?php

/*
 * Copyright (C) 2017 Mathieu Boutolleau
 *
 * ________________________________
 *
 * This file is part of Pilote.
 *
 * Pilote is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Pilote is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Pilote.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Pilote\TaskerBundle\Controller;

use Pilote\TaskerBundle\Controller\ProjectDashboardController;
use Pilote\TaskerBundle\Entity\Task;
use PHPUnit\Framework\TestCase;

class ProjectDashboardControllerTest extends TestCase
{

    /**
     * Teste la comparaison de deux tâches (\Pilote\TaskerBundle\Entity\Task) suivant leur date de fin
     */
    public function testCompareTwoTasksByEndDate() {
        $taskWithAnOldEndDate = new Task();
        $taskWithAnOldEndDate->setEndDate(new DateTime('1970-01-01')); // task with an end date set to 1st january 1970

        $taskWithTodayAsEndDate = new Task();
        $taskWithTodayAsEndDate->setEndDate(new DateTime()); // task with an end date set to today

        // $taskWithAnOldEndDate's end date < $taskWithTodayAsEndDate's end date
        $this->assertEquals(
            -1,
            ProjectDashboardController::compareTwoTasksByEndDate($taskWithAnOldEndDate, $taskWithTodayAsEndDate)
        );

        // $taskWithAnOldEndDate's end date = $taskWithAnOldEndDate's end date
        $this->assertEquals(
            0,
            ProjectDashboardController::compareTwoTasksByEndDate($taskWithAnOldEndDate, $taskWithAnOldEndDate)
        );

        // $taskWithTodayAsEndDate's end date = $taskWithTodayAsEndDate's end date
        $this->assertEquals(
            0,
            ProjectDashboardController::compareTwoTasksByEndDate($taskWithTodayAsEndDate, $taskWithTodayAsEndDate)
        );

        // $taskWithTodayAsEndDate's end date > $taskWithAnOldEndDate's end date
        $this->assertEquals(
            1,
            ProjectDashboardController::compareTwoTasksByEndDate($taskWithTodayAsEndDate, $taskWithAnOldEndDate)
        );

        // null is not a valid task, comparision return null
        $this->assertEquals(
            null,
            ProjectDashboardController::compareTwoTasksByEndDate(null, null)
        );

        // a string and null are not valid tasks, comparision return null
        $this->assertEquals(
            null,
            ProjectDashboardController::compareTwoTasksByEndDate("This is not a task", null)
        );
    }

}
