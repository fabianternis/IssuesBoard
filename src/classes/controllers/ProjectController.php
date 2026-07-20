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
        global $http_code, $error_message, $view_name, $project, $items, $target_uri;

        // ToDo: Add "support" for wrong auth-user ...

        // if(!isset($_SESSION['user_id'])) {
        if(!Auth()->check()) {
            $http_code = 403;
            // $error_message = 'You seem not to be logged-in';
            $target_uri = '/auth';
        } else {
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

    public function batchUpdate($id)
    {
        global $http_code, $error_message, $other;
        if(!Auth()->check()) {
            $http_code = 403;
            $error_message = 'YOu need to be authenticated for thjsi ...';
            exit;
        } else {
            $project = Project::where('id', $id)->where('user_id', Auth()->id())->firstOrFail();
            if(!$project) {
                $http_code = 404;
                // $error_message = 'No Project';
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Ownership verification failed.']);
                exit;
            } else {
                $json = file_get_contents('php://input');
                $payload = json_decode($json, true);

                if (!$payload /*|| !isset($payload['project_id'])*/ || !isset($payload['items'])) {
                    $http_code = 400;
                    header('Content-Type: application/json');
                    echo json_encode(['error' => 'Malformed payload structure.']);
                    exit;
                }

                // $projectId = $payload['pro']

                foreach ($payload['items'] as $itemData) {
                    if (!isset($itemData['id'])) continue;

                    $item = Item::where('id', $itemData['id'])->where('project_id', $project->id)->firstOrFail();
                    
                    if ($item) {
                        $item->update([
                            'name'         => $itemData['name'] ?? $item->name,
                            'description'  => $itemData['description'] ?? $item->description,
                            'type'         => $itemData['type'] ?? $item->type,
                            'state'        => $itemData['state'] ?? $item->state,
                            'external_url' => $itemData['external_url'] ?? $item->external_url,
                            'order_index'  => $itemData['order_index'] ?? ($item->order_index ?? 0),
                        ]);
                    } else {
                        // may not do anything ...
                    }
                }

                $http_code = 200;
                header('Content-Type: application/json');
                echo json_encode(['status' => 'success', 'message' => 'Board synchronized.']);
                exit;
            }
    
        }



    }
}

// index, create, store, show, edit, update

// ToDo: "validation"

// ToDo: $content_type or $header[$header-name_string]