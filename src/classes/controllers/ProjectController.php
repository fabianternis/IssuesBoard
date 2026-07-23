<?php

namespace Controllers;

use Models\{Project, Item, User};
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
        
        $target_action = '/board?action=update&object=project&id='.$id;
        $project = Project::find($id);

        if (!$project) {
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
                    'required' => true,
                    'value' => $project->name,
                ],
                [
                    'type' => 'text',
                    'name' => 'description',
                    'placeholder' => 'Description',
                    'value' => $project->description,
                ],
                [
                    'type' => 'url',
                    'name' => 'repo_url',
                    'placeholder' => 'Repository URL (e.g., https://github.com/user/repo)', // cant be seen on the actual input in full ...
                    'value' => $project->repo_url,
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
        
        $project = Project::find($id);

        if (!$project) {
            $http_code = 404;
            $error_message = 'Project could not be found';
        } elseif (!Auth()->can($project, 'update')) {
            $http_code = 403;
            $error_message = 'You have no permission to access this Project';
        } else {
            $project->update([
                'name' => $_POST['name'] ?? $project->name,
                'description' => $_POST['description'] ?? $project->description,
                'repo_url' => $_POST['repo_url'] ?? $project->repo_url,
            ]);

            $target_uri = '/dashboard?action=show&object=project&id=' . $project->id;
        }
    }

    public function show(string $id)
    {
        global $http_code, $error_message, $view_name, $project, $items, $target_uri;

        // ToDo: Add "support" for wrong auth-user ...

        // if(!isset($_SESSION['user_id'])) {
        if(!Auth()->check()) {
            $http_code = 403;
            // $error_message = 'You seem not to be logged-in';
            $target_uri = '/auth';
            return;
        }

        $project = Project::with('items')->where('id', $id)->first();

        if (!$project) {
            $http_code = 404;
            $error_message = 'Project could not be found';
            return;
        }

        $userId = Auth()->id();
        $isOwner = $project->user_id === $userId;
        $isMember = $project->users()->where('users.id', $userId)->exists();

        if (!$isOwner && !$isMember) {
            $http_code = 403;
            $error_message = 'You have no permission to access this Project';
            return;
        }

        $items = $project->items; 
        
        $view_name = 'board';
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
            $user = Auth()->user();
            $project = Project::where('id', $id)->firstOrFail();
            
            if (!($user->ownedProjects->contains($project) || $user->projects->contains($project))) {
                $http_code = 404;
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

    public function addUser($id)
    {
        global $error_message, $target_uri, $http_code;

        $project = Project::where('id', $id)->where('user_id', Auth()->id())->firstOrFail();

        $user_ident = trim($_POST['user'] ?? '');

        if (empty($user_ident)) {
            $error_mesaage = 'User identifier cannot be empty.';
            $http_code = 404;
            exit;
        }

        $new_user = User::where('id', $user_ident)->orWhere('username', $user_ident)->orWhere('email', $user_ident)->firstOrFail();


        // if (!isset($new_user)) {
        //     $error_mesaage = 'No user to add found.';
        //     $http_code = 404;
        //     exit;
        // }


        if ($new_user->id === $project->user_id) {
            $error_message = 'The project owner cannot be added as a secondary collaborator.';
            $http_code = 400; // ??? what exact code ??
        }

        $project->users()->syncWithoutDetaching([$new_user->id]);

        $target_uri = '/board?action=show&object=project&id=' . $project->id;
    }
}

// index, create, store, show, edit, update

// ToDo: "validation"

// ToDo: $content_type or $header[$header-name_string]