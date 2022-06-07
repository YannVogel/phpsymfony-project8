<?php

namespace Tests\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends AbstractTestController
{
    public function getTaskData(): array
    {
        return [
            [
                [
                    'title' => 'first test task title ' . uniqid(),
                    'content' => 'first test task content ' . uniqid(),
                ]
            ],
            [
                [
                    'title' => 'second test task title ' . uniqid(),
                    'content' => 'second test task content ' . uniqid(),
                ]
            ],
        ];
    }

    public function testAccessTasksListGranted()
    {
        $this->connectUser();
        $this->client->request(
            Request::METHOD_GET,
            $this->urlGenerator->generate('task_list')
        );

        $this->assertEquals(Response::HTTP_OK ,$this->client->getResponse()->getStatusCode());
    }

    public function testAccessTasksListDenied()
    {
        $this->client->request(
            Request::METHOD_GET,
            $this->urlGenerator->generate('task_list')
        );

        $this->assertEquals(Response::HTTP_FOUND ,$this->client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider getTaskData
     */
    public function testCreateTask(array $taskData)
    {
        $this->connectUser();

        $crawler = $this->client->request(
            Request::METHOD_GET,
            $this->urlGenerator->generate('task_create')
        );

        $form = $crawler->selectButton('Ajouter')->form();

        $form['task[title]'] = $taskData['title'];
        $form['task[content]'] = $taskData['content'];

        $this->client->submit($form);

        $task = $this->taskRepository->findByTitle($taskData['title']);

        $this->assertCount(1, $task);
    }

    /**
     * @dataProvider getTaskData
     */
    public function testEditTask(array $taskData)
    {
        $this->connectUser();

        $task = $this->taskRepository->findOneBy([]);
        $taskId = $task->getId();

        $crawler = $this->client->request(
            Request::METHOD_GET,
            $this->urlGenerator->generate('task_edit', ['id' => $taskId])
        );

        $form = $crawler->selectButton('Modifier')->form();

        $form['task[title]'] = $taskData['title'];
        $form['task[content]'] = $taskData['content'];

        $this->client->submit($form);

        $newTask = $this->taskRepository->findByTitle($taskData['title']);

        $this->assertCount(1, $newTask);

        $newTask = $this->taskRepository->findByContent($taskData['content']);

        $this->assertCount(1, $newTask);
    }

    public function testDeleteUserTaskAllowed()
    {
        $this->connectUser();

        $task = $this->taskRepository->findOneByUser($this->user->getId());
        $taskId = $task->getId();

        $this->client->request(
            Request::METHOD_DELETE,
            $this->urlGenerator->generate('task_delete', ['id' => $taskId])
        );

        $this->assertCount(0, $this->taskRepository->findById($taskId));
    }

    public function testDeleteUserTaskDenied()
    {
        $this->connectUser();

        $task = $this->taskRepository->findReadOnlyTask($this->user->getId());
        $taskId = $task->getId();

        $this->client->request(
            Request::METHOD_DELETE,
            $this->urlGenerator->generate('task_delete', ['id' => $taskId])
        );

        $this->assertCount(1, $this->taskRepository->findById($taskId));
    }

    public function testDeleteAnonTaskAllowed()
    {
        $this->connectAdmin();
        $task = $this->taskRepository->findOneByUser(null);
        $taskId = $task->getId();

        $this->client->request(
            Request::METHOD_DELETE,
            $this->urlGenerator->generate('task_delete', ['id' => $taskId])
        );

        $this->assertCount(0, $this->taskRepository->findById($taskId));
    }

    public function testDeleteAnonTaskDenied()
    {
        $this->connectUser();
        $task = $this->taskRepository->findOneByUser(null);
        $taskId = $task->getId();

        $this->client->request(
            Request::METHOD_DELETE,
            $this->urlGenerator->generate('task_delete', ['id' => $taskId])
        );

        $this->assertCount(1, $this->taskRepository->findById($taskId));
    }
}
