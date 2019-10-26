<?php

namespace TestApp\Pages;

use \TestApp\Authorization;
use \TestApp\Models\Task as TaskModel;
use \TestApp\Request;
use \TestApp\Repositories\Tasks as TaskRepository;
use \TestApp\Traits;


/**
 * Represents tasks controller.
 */
class Task
{
    use Traits\Twig;


    /** Contains task count per page. */
    const TASK_COUNT = 3;


    /**
     * Process given request and return its result
     *
     * @param Request $request    HTTP request of application.
     *
     * @throws \Exception    If request wrong and can`t be handled.
     *
     * @return string    Returns HTML output of handled request.
     */
    public function process($request)
    {
        switch ($request->page) {
        case 'index':
        case 'tasks':
            return $this->retrieveTasks($request);
        case 'tasks/add':
        case 'tasks/change':
            return $this->saveTask($request);
        default:
            throw new \Exception('Unhandled request: '.$request->page);
        }
    }

    /**
     * Retrieve tasks list.
     *
     * @param Request $request    HTTP request of application.
     *
     * @return string    Returns HTML output of tasks list page.
     */
    public function retrieveTasks($request)
    {
        $from = intval($request->get['from']);
        $sortBy = $request->get['sort_by'];
        $sortDir = $request->get['sort_dir'];
        $TaskRepo = new TaskRepository();
        $tasks = $TaskRepo->getList($sortBy, $sortDir, $from, self::TASK_COUNT);
        return $this->renderPage('tasks_list', [
            'tasks' => $tasks,
            'isAuthorized' => Authorization::$isAuthorized,
            'from' => $from,
            'pages' => ceil($TaskRepo->getTotalCount()/self::TASK_COUNT),
            'pageCurrent' => ceil($from / self::TASK_COUNT) + 1,
            'perPage' => self::TASK_COUNT,
            'sortBy' => $sortBy,
            'sortDir' => $sortDir,
        ]);
    }

    /**
     * Run save task action and return add task page with errors if any exists.
     *
     * @param Request $request    HTTP request of application.
     *
     * @return string    Returns HTML output of add task page.
     */
    public function saveTask($request)
    {
        $id = $request->get['id'];
        $isChangeMode = !empty($id);
        $errors = [];
        $task = $isChangeMode
            ? (new TaskRepository())->get($id)
            : new TaskModel();
        if (isset($request->post['saveTask'])) {
            if (!$isChangeMode) {
                if (empty($task->username = trim($request->post['username']))) {
                    $errors[] = 'no_username';
                }
                if (empty($task->email = trim($request->post['email']))) {
                    $errors[] = 'no_email';
                }
                if (!empty($task->email)
                    && !filter_var($task->email, FILTER_VALIDATE_EMAIL)
                ) {
                    $errors[] = 'email_invalid';
                }
            }
            if (empty($text = trim($request->post['text']))) {
                $errors[] = 'no_text';
            }
            if ($isChangeMode) {
                if (!empty($request->post['status'])) {
                    $task->status = TaskModel::STATUS_RESOLVED;
                }
                if ($task->text !== $text) {
                    $task->changedBy = Authorization::ADMIN_LOGIN;
                }
            }
            $task->text = $text;

            if (empty($errors)) {
                (new TaskRepository())->save($task);
                $this->addAlert('success',
                    $isChangeMode
                        ? 'Task changed'
                        : 'Task successfully created'
                );
                header('Location: /tasks');
                exit;
            }
        }
        return $this->renderPage('tasks_save', [
            'errors' => $errors,
            'task' => $task,
            'isAuthorized' => Authorization::$isAuthorized,
            'isChangeMode' => $isChangeMode,
        ]);
    }
}
