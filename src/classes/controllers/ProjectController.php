<?php

namespace Controllers;

use Models\{Project, Item};
use Ramsey\Uuid\Uuid;

class ProjectController extends Controller
{
    public function create(): void 
    {
        $target_action = '/dashboard?action=store&object=project';
        
        $inputs = [
            [
                'type' => 'text',
                'name' => 'name',
                'placeholder' => 'Project Name',
                'required' => null,
            ],
            [
                'type' => 'text',
                'name' => 'description',
                'placeholder' => 'Description',
            ],
            [
                'type' => 'submit',
                'value' => 'Create Project',
            ],
        ];
        
        echoForm($target_action, $inputs, 'form-create-project', 'POST');
    }

    public function store() 
    {
        global $target_uri;

        $project = Project::create([
            'id' => (string) Uuid::uuid4(),
            'user_id' => Auth()->id(),
            'name' => $_POST['name'],
            'description' => $_POST['description'] ?? null,
            'repo_url' => $_POST['repository'] ?? null,
        ]);

        $target_uri = '/dashboard?action=show&object=project&id=' . $project->id;
    }

    public function edit($id) 
    {
        global $http_code, $error_message;
        
        $target_action = '/dashboard?action=update&object=project&id='.$id;
        $project = Project::where('id', $id)->firstOrFail();

        if (!isset($project)) {
            $http_code = 404;
            $error_message = 'Project could not be found';
        } elseif (!Auth()->can($project, 'edit')) {
            $http_code = 403;
            $error_message = 'You have no permission to access this Project';
        } else {
            $inputs = [
                [
                    'type' => 'text',
                    'name' => 'name',
                    'placeholder' => 'Project Name',
                    'required' => null,
                    'value' => $project->name,
                ],
                [
                    'type' => 'text',
                    'name' => 'description',
                    'placeholder' => 'Description',
                    'value' => $project->description,
                ],
                [
                    'type' => 'submit',
                    'value' => 'Update Project',
                ],
            ];
            
            echoForm($target_action, $inputs, 'form-edit-project', 'POST');
        }
    }

    public function update($id)
    {
        global $http_code, $error_message, $target_uri;
        
        $project = Project::where('id', $id)->firstOrFail();

        if (!isset($project)) {
            $http_code = 404;
            $error_message = 'Project could not be found';
        } elseif (!Auth()->can($project, 'update')) {
            $http_code = 403;
            $error_message = 'You have no permission to access this Project';
        } else {
            $project->update([
                'name' => $_POST['name'] ?? $project->name,
                'description' => $_POST['description'] ?? $project->description,
            ]);

            $target_uri = '/dashboard?action=show&object=project&id=' . $project->id;
        }
    }

    public function show(string $id): void
    {
        global $http_code, $error_message, $view_name, $project, $items;
        
        $project = Project::where('id', $_GET['id'])->where('user_id', $_SESSION['user_id'])->firstOrFail();
        // $items = $project->items();
        $items = Item::where('project_id', $project->id)->get(); // WTF 

        if (!isset($project)) {
            $http_code = 404;
            $error_message = 'Project could not be found';
        } elseif (!Auth()->can($project, 'show')) {
            // can() gets overwritten
            $error_message = 'You have no permission to access this Project';
            $http_code = 403;
        } else {
            // var_dump($project);
            // die('test');
            // die($project);
            $view_name = 'board';
        }
    }

    public function delete($id)
    {
        global $http_code, $error_message, $target_uri;
        
        $project = Project::where('id', $id)->firstOrFail();

        if (!isset($project)) {
            $http_code = 404;
            $error_message = 'Project could not be found';
        } elseif (!Auth()->can($project, 'delete')) {
            // overwrites the can()
            $error_message = 'You have no permission to access this Project';
        } else {
            $project->delete();
            $target_uri = '/dashboard';
        }
    }
}

// index, create, store, show, edit, update

// ToDo: "validation"