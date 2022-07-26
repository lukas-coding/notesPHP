<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\NotFoundException;

class Controller extends AbstractController
{

    private const PAGE_SIZE = 10;

    public function createAction(): void
    {
        if ($this->request->hasPost()) {
            $noteData = [
                'title' => $this->request->postParam('title'),
                'description' => $this->request->postParam('description')
            ];
            $this->database->createNote($noteData);
            $this->redirect('/', ['before' => 'created']);
            // header('location: /?before=created');
            exit;
        }
        $this->view->render('create');
    }

    public function showAction(): void
    {
        $this->view->render('show', ['note' => $this->getNote()]);
    }

    public function listAction(): void
    {
        $phrase = $this->request->getParam('phrase');
        $pageNumber = (int) $this->request->getParam('page', 1);
        $pageSize = (int) $this->request->getParam('pagesize', self::PAGE_SIZE);
        $sortBy = $this->request->getParam('sortby', 'title');
        $orderBy = $this->request->getParam('orderby', 'asc');

        if (!in_array($pageSize, [1, 5, 10, 25])) {
            $pageSize = self::PAGE_SIZE;
        }

        if ($phrase) {
            $noteList = $this->database->searchNotes($phrase, $pageNumber, $pageSize, $sortBy, $orderBy);
            $notes = $this->database->getSearchCount($phrase);
        } else {
            $noteList = $this->database->getNotes($pageNumber, $pageSize, $sortBy, $orderBy);
            $notes = $this->database->getCount();
        }

        $this->view->render(
            'list',
            [
                'page' => [
                    'number' => $pageNumber,
                    'size' => $pageSize,
                    'pages' => (int)ceil(($notes / $pageSize))
                ],
                'phrase' => $phrase,
                'sort' => ['by' => $sortBy, 'order' => $orderBy,],
                'notes' => $noteList,
                'before' => $this->request->getParam('before') ?? null,
                'error' => $this->request->getParam('error') ?? null
            ]
        );
    }

    public function editAction(): void
    {
        if ($this->request->isPost()) {
            $noteId = (int) $this->request->postParam('id');
            $noteData = [
                'title' => $this->request->postParam('title'),
                'description' => $this->request->postParam('description')
            ];
            $this->database->editNote($noteId, $noteData);
            $this->redirect('/', ['before' => 'edit']);
        }

        $this->view->render('edit', ['note' => $this->getNote()]);
    }

    public function deleteAction(): void
    {

        if ($this->request->isPost()) {
            $id = (int) $this->request->postParam('id');
            $this->database->deleteNote($id);
            $this->redirect('/', ['before' => 'delete']);
        }

        $this->view->render(
            'delete',
            ['note' => $this->getNote()]
        );
    }

    final private function getNote(): array
    {
        $noteId = (int)$this->request->getParam('id');
        if (!$noteId) {
            $this->redirect('/', ['error' => 'missingNoteId']);
        }

        try {
            $note = $this->database->getNote($noteId);
        } catch (NotFoundException $e) {
            $this->redirect('/', ['error' => 'noteNotFound']);
            exit;
        }
        return $note;
    }
}
