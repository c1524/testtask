<?php

namespace TestApp\Models;


class Task
{
    /** Indicates new status of task. */
    const STATUS_NEW = 0;
    /** Indicates resolved status of task. */
    const STATUS_RESOLVED = 1;


    /**
     * Contains task identifier.
     *
     * @var int
     */
    public $id = null;

    /**
     * Contains name of user assigned to the task.
     *
     * @var string
     */
    public $username;

    /**
     * Contains email of user assigned to the task.
     *
     * @var string
     */
    public $email;

    /**
     * Contains text of the task.
     *
     * @var string
     */
    public $text;

    /**
     * Contains status of the task.
     *
     * @var int
     */
    public $status;

    /**
     * Contains name of user who change status of the task.
     *
     * @var null|string
     */
    public $changedBy;
}
