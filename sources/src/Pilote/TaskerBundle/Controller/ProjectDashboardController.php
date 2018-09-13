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

use DateTime;

class ProjectDashboardController extends TaskerController
{
    // Constant used for the key name of the general properties of an indicator
    const KEY_NAME_GENERAL = 'general';
    const KEY_NAME_PROJECT = 'project';

    /**
     * @var array Tableau contenant tous les éléments d'un projet (domaines, étapes, tâches).
     * Sous la forme :
     * <pre>
     * - Domain1
     *   - Step1
     *     - TList1
     *       - Task1
     *       - Task2
     *     - TList2
     *   - Step2
     *     - TList3
     * - Domain2
     *   - Step3
     * <pre>
     */
    private $boardArray = array();

    /**
     * @var array étiquette correspondant à une tâche faite
     */
    private static $doneLabel = array();

    /**
     * Récupère toutes les tâches d'un projet depuis la BDD et les stocke dans un tableau pour être lues plus facilement
     * @param string $boardId
     */
    private function retrieveAllTasks(string $boardId) {
        $em = $this->getDoctrine()->getManager();
        $board = $this->findOr404($em, 'PiloteTaskerBundle', 'Board', $boardId);

        // Récupère les différents domaines du projet depuis la BDD
        $domains = $em->getRepository('PiloteTaskerBundle:Domain')->findBy(array('board' => $board));

        // Pour chaque domaines du projet
        foreach ($domains as $domain) {
            // Récupère les étapes d'un domaine depuis la BDD
            $steps = $em->getRepository('PiloteTaskerBundle:Step')->findBy(array('domain' => $domain));
            $stepsArray = array();

            // Pour chaque étapes du domaine
            foreach ($steps as $step) {
                // Récupère les listes de tâches d'une étape depuis la BDD
                $taskLists = $em->getRepository('PiloteTaskerBundle:TList')->findBy(array('step' => $step));
                $taskListsArray = array();

                // Pour chaque liste de tâches de l'étape
                foreach ($taskLists as $taskList) {
                    // Récupère les tâches d'une liste de tâches depuis la BDD
                    $tasks = $em->getRepository('PiloteTaskerBundle:Task')->findBy(array('tList' => $taskList));
                    $tasksArray = array();

                    // Pour chaque tâches de la liste
                    foreach ($tasks as $task) {
                        // Construit un tableau de tâches, contenu dans une liste de tâches
                        $tasksArray[$task->getName()] = $task;
                    }
                    // Construit un tableau de liste de tâches, contenu dans une étape
                    $taskListsArray[$taskList->getName()] = $tasksArray;
                }
                // Construit un tableau d'étapes, contenu dans un domaine
                $stepsArray[$step->getName()] = $taskListsArray;
            }
            // Construit un tableau de domaines du projet
            $this->boardArray[$domain->getName()] = $stepsArray;
        }
    }

    /**
     * Récupère l'avancement de toutes les tâches du projet et calcule l'avancement général du projet (moyenne de tous
     * les avancements), et ceux des étapes (moyenne de l'avancement des tâches d'une étape).
     * @param string $boardId L'identifiant du tableau correspondant au projet.
     * @return array un tableau contenant le pourcentage d'avancement du projet et ceux des différentes étapes
     */
    private function computeGeneralAndDetailedProgress(string $boardId)
    {
        // Tableau de l'avancement du projet (1ère entrée: l'avancement général, les suivantes sont pour l'avancement
        // d'une étape d'un domaine)
        $progressArray = array(self::KEY_NAME_GENERAL => null);
        // Nombre de tâches du projet prises en compte pour le calcul de l'avancement du projet
        $numberValidTasks = 0;

        if ($this->boardArray === array()) {
            $this->retrieveAllTasks($boardId);
        }

        // Parcours des éléments du projet pour lire les données de toutes les tâches
        foreach ($this->boardArray as $domainName => $steps) {
            $progressArray[$domainName] = array();

            foreach ($this->boardArray[$domainName] as $stepName => $taskLists) {
                $stepProgress = null;
                // Nombre de tâches de l'étape prises en compte pour le calcul de l'avancement du projet
                $numberValidTasksForCurrentStep = 0;

                foreach ($this->boardArray[$domainName][$stepName] as $taskListName => $tasks) {
                    foreach ($this->boardArray[$domainName][$stepName][$taskListName] as $taskObject) {
                        // Calcule l'avancement général en calculant la somme des progressions de chaque tâche, on ne
                        // prends en compte que les tâches qui ont une progression et qui ne sont pas marquées finies
                        if ($taskObject->isProgressActivated() && $taskObject->getLabel() !== self::$doneLabel) {
                            $numberValidTasks ++;
                            $numberValidTasksForCurrentStep ++;

                            // General progress
                            $progressArray[self::KEY_NAME_GENERAL] += $taskObject->getProgress();
                            // Step specific progress
                            $stepProgress += $taskObject->getProgress();
                        }
                    }
                }

                // Formate le pourcentage d'avancement d'une étape pour enlever les chiffres après la virgule
                if ($numberValidTasksForCurrentStep > 0) {
                    $stepProgress = number_format($stepProgress / $numberValidTasksForCurrentStep);
                }
                $progressArray[$domainName][$stepName] = $stepProgress;
            }
        }

        // Formate le pourcentage d'avancement général pour enlever les chiffres après la virgule
        if ($numberValidTasks > 0) {
            $progressArray[self::KEY_NAME_GENERAL]  = number_format($progressArray[self::KEY_NAME_GENERAL] / $numberValidTasks);
        }

        return $progressArray;
    }

    /**
     * Récupère les dates de fin de toutes les tâches et calcule la date de fin au plus tard du projet (la date de fin
     * de la tâche qui se termine le plus tard), et les dates de fin des étapes du projets (la date de fin de la tâche
     * d'une étape qui se termine le plus tard).
     * @param string $boardId l'identifiant du tableau du projet
     * @return array un tableau contenant les dates de fins du projet et de ses étapes
     */
    private function computeGeneralAndDetailedEndDates(string $boardId)
    {
        // Tableau des dates de fin du projet (1ère entrée: date de fin du projet, les suivantes sont les dates de fin
        // d'une étape d'un domaine)
        $endDateArray = array(self::KEY_NAME_PROJECT => null);

        if ($this->boardArray === array()) {
            $this->retrieveAllTasks($boardId);
        }

        // Parcours des éléments du projet pour lire les données de toutes les tâches
        foreach ($this->boardArray as $domainName => $steps) {
            $endDateArray[$domainName] = array();

            foreach ($this->boardArray[$domainName] as $stepName => $taskLists) {
                $stepEndDate = null;

                foreach ($this->boardArray[$domainName][$stepName] as $taskListName => $tasks) {
                    foreach ($this->boardArray[$domainName][$stepName][$taskListName] as $taskObject) {
                        // Calcule l'avancement général en calculant la somme des progressions de chaque tâche, on ne
                        // prends en compte que les tâches qui ont une date de fin et qui ne sont pas marquées finies
                        if ($taskObject->getEndDate() && $taskObject->getLabel() !== self::$doneLabel) {
                            // Date de fin du project
                            if ($taskObject->getEndDate() > $endDateArray[self::KEY_NAME_PROJECT]) {
                                $endDateArray[self::KEY_NAME_PROJECT] = $taskObject->getEndDate();
                            }
                            // Date de fin d'une étape
                            if ($taskObject->getEndDate() > $stepEndDate) {
                                $stepEndDate = $taskObject->getEndDate();
                            }
                        }
                    }
                }

                $endDateArray[$domainName][$stepName] = $stepEndDate;
            }
        }

        return $endDateArray;
    }

    /**
     * Récupère toutes les tâches d'un projet et les ajoute à un tableu si elles ont une date de fin supérieure à aujourd'hui.
     * Le tableau est ensuite trié par ordre croissante des dates de fin.
     * @param string $boardId l'identifiant du tableau du projet
     * @return array un tableau contenant les tâches du projet triés par ordre croissant de leurs dates de fin
     */
    private function computeNextDeadlines(string $boardId) {
        // Tableau des tâches du projet qui ont une date de fin supérieure à aujourd'hui
        $deadlinesArray = array();

        if ($this->boardArray === array()) {
            $this->retrieveAllTasks($boardId);
        }

        // Parcours des éléments du projet pour ajouter chaque tâche qui possède une date de fin dans un tableau
        foreach ($this->boardArray as $domainName => $steps) {
            foreach ($this->boardArray[$domainName] as $stepName => $taskLists) {
                foreach ($this->boardArray[$domainName][$stepName] as $taskListName => $tasks) {
                    foreach ($this->boardArray[$domainName][$stepName][$taskListName] as $taskObject) {
                        // Calcule l'avancement général en calculant la somme des progressions de chaque tâche
                        if ($taskObject->getEndDate() && $taskObject->getEndDate() > new DateTime('now')) {
                            $deadlinesArray[] = $taskObject;
                        }
                    }
                }
            }
        }

        // Trie toutes les tâches qui ont une date de fin par par ordre croissant de leurs dates de fin
        usort($deadlinesArray, "\\Pilote\\TaskerBundle\\Controller\\ProjectDashboardController::compareTwoTasksByEndDate");

        return $deadlinesArray;
    }

    /**
     * Compare deux tâches (\Pilote\TaskerBundle\Entity\Task) suivant leur date de fin,
     * retourne -1, 1 ou 0 si la date de fin de $task1 est respectivement avant, après ou égale à celle de $task2
     * @param $task1 \Pilote\TaskerBundle\Entity\Task la tâche de référence
     * @param $task2 \Pilote\TaskerBundle\Entity\Task la tâche à comparer
     * @return int|null le résultat de la comparaison ou null si les paramètres ne sont pas des tâches valides
     */
    public static function compareTwoTasksByEndDate($task1, $task2) {
        if ($task1 !== null && $task2 !== null) {
            // Si les dates de fin sont égales (date et heure)
            if ($task1->getEndDate() == $task2->getEndDate()) {
                return 0;
            }

            // Sinon, retourne -1 si la date de fin 1 et plus petite que la date de fin 2, 1 pour l'inverse
            return ($task1->getEndDate() < $task2->getEndDate()) ? -1 : 1;
        }

        return null;
    }

    private function computeOverdueTasks (string $boardId) {
        // Tableau contenant les tâches du projet actuellement et retard
        $overdueArray = array();

        // La date d'aujourd'hui au format AAAA-MM-JJ
        $today = date('Y-m-d ');

        if ($this->boardArray === array()) {
            $this->retrieveAllTasks($boardId);
        }

        // Parcours des éléments du projet pour ajouter chaque tâche en retard dans le tableau dédié
        foreach ($this->boardArray as $domainName => $steps) {
            foreach ($this->boardArray[$domainName] as $stepName => $taskLists) {
                foreach ($this->boardArray[$domainName][$stepName] as $taskListName => $tasks) {
                    foreach ($this->boardArray[$domainName][$stepName][$taskListName] as $taskObject) {

                        // La tâche possède une date de fin
                        if ($taskObject->getEndDate() !== null) {
                            // Elle n'est pas marquée comme faite ou ne possède pas une progression à 100%
                            if ($taskObject->getLabel() !== self::$doneLabel && $taskObject->getProgress() !== 100) {
                                // Si la tâche a une date de fin qui est passée (définie comme avant la date d'aujourd'hui)
                                if (strtotime($taskObject->getEndDate()->format('Y-m-d')) < strtotime($today)) {
                                    // Son retard est le % de travail qu'il reste à faire, exprimé par un nombre négatif
                                    // -100 indique qu'aucun avancement n'a été fait sur la tâche (reste 100% de travail)
                                    $overdueArray[$taskObject->getName()] = -(100 - $taskObject->getProgress());
                                }
                            }
                        }

                    }
                }
            }
        }

        return $overdueArray;
    }

    /**
     * Affiche la page du tableau de bord d'un projet,
     * il centralise et affiche des informations générales utiles au pilotage d'un projet.
     * On y retrouvera des indicateurs sur l'avancement du projet et les tâches en retard.
     * @param string $boardId l'identifiant du projet concerné
     */
    public function printProjectDashboardAction(string $boardId)
    {
        $em = $this->getDoctrine()->getManager();
        $board = $this->findOr404($em, 'PiloteTaskerBundle', 'Board', $boardId);

        // L'utilisateur a-t-il accès au projet ?
        if (!$this->AccessGranted($board)) {
            throw $this->createAccessDeniedException('You are not allowed to access to this page.');
        }

        self::$doneLabel = \Pilote\TaskerBundle\Entity\Task::getLabelFromListByText('Fait');

        // Calcul des indicateurs
        $progressArray = $this->computeGeneralAndDetailedProgress($boardId);
        $endDateArray = $this->computeGeneralAndDetailedEndDates($boardId);
        $deadlinesArray = $this->computeNextDeadlines($boardId);
        $overdueArray = $this->computeOverdueTasks($boardId);

        // Présentation à l'utilisateur de la vue du tableau de bord
        return $this->render('PiloteTaskerBundle:Main:projectDashboard.html.twig', array(
            'board' => $board,
            'progressArray' => $progressArray,
            'endDateArray' => $endDateArray,
            'deadlinesArray' => $deadlinesArray,
            'overdueArray' => $overdueArray,
        ));
    }

}