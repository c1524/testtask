<?php

namespace TestApp\Repositories;

use \TestApp\Models\Task as TaskModel;
use \TestApp\Traits;


/**
 * Implements tasks repository.
 */
class Tasks
{
    use Traits\MySQL;


    /**
     * Save given task into repository.
     *
     * @param TaskModel $task
     */
    public function save($task)
    {
        $sql = "INSERT INTO `tasks` ".
               "SET `id`=?, `username`=?,`email`=?,`text`=?,".
                   "`status`=?,`changedBy`=? ".
               "ON DUPLICATE KEY ".
               "UPDATE `id`=VALUES(`id`),".
                   "`username`=VALUES(`username`),".
                   "`email`=VALUES(`email`),".
                   "`text`=VALUES(`text`),".
                   "`status`=VALUES(`status`),".
                   "`changedBy`=VALUES(`changedBy`)";

        $this->initMySQL()
             ->prepare($sql)
             ->execute([
                 $task->id,
                 $task->username,
                 $task->email,
                 $task->text,
                 intval($task->status),
                 $task->changedBy,
            ]);
    }

    /**
     * Get list of task by given parameters.
     *
     * @param string $sortField        Sort tasks by given field.
     * @param string $sortDirection    Sort direction.
     * @param int $from                Shift for tasks list.
     * @param int $count               Count of retrieved tasks.
     *
     * @return TaskModel[]    Returns list of tasks.
     */
    public function getList($sortField, $sortDirection, $from, $count)
    {
        if (!in_array($sortField, ['username','email','status'])) {
            $sortField = null;
        }
        if ($sortDirection !== 'asc') {
            $sortDirection = 'desc';
        }
        $sql = "SELECT `id`,`username`,`email`,`text`,`status`,`changedBy` ".
               "FROM `tasks` ".
               (!is_null($sortField)
                    ? " ORDER BY `".$sortField."` ".$sortDirection." "
                    : "").
               "LIMIT ".(int)$from.",".(int)$count;
        $items = [];
        $st = $this->initMySQL()->prepare($sql);
        $st->execute([]);
        while ($r = $st->fetch()) {
            $task = new TaskModel();
            $task->id = (int)$r['id'];
            $task->username = $r['username'];
            $task->email = $r['email'];
            $task->text = $r['text'];
            $task->status = (int)$r['status'];
            $task->changedBy = $r['changedBy'];
            $items[] = $task;
        }
        return $items;
    }

    /**
     * Get task by given ID.
     *
     * @param int $id    Specified task ID.
     *
     * @return null|TaskModel    Return task if exists.
     */
    public function get($id)
    {
        $sql = "SELECT `id`,`username`,`email`,`text`,`status`,`changedBy` ".
               "FROM `tasks` ".
               "WHERE `id`=?";
        $items = [];
        $st = $this->initMySQL()->prepare($sql);
        $st->execute([$id]);
        if ($r = $st->fetch()) {
            $task = new TaskModel();
            $task->id = (int)$r['id'];
            $task->username = $r['username'];
            $task->email = $r['email'];
            $task->text = $r['text'];
            $task->status = (int)$r['status'];
            $task->changedBy = $r['changedBy'];
            return $task;
        }
        return null;
    }

    /**
     * Get total count of tasks in repository.
     *
     * @return int    Returns total count of tasks in repository.
     */
    public function getTotalCount()
    {
        $sql = "SELECT count(*) as `count` ".
               "FROM `tasks`";
        $st = $this->initMySQL()->prepare($sql);
        $st->execute([]);
        $r = $st->fetch();
        return intval($r['count']);
    }
}
