<?php

namespace Controllers;

use Models\Project;
use Models\Item;
use Ramsey\Uuid\Uuid;

class ItemController extends Controller
{

    public function store() 
    {        
        $project = Project::where('id', $_GET['pid'])->where('user_id', $_SESSION['user_id'])->firstOrFail();
        
        $item = Item::create([
            'id' => (string) Uuid::uuid4(),
            'project_id' => $project->id,
            'name' => $_POST['name'],
            'type' => $_POST['type'],
            'description' => $_POST['description'] ?? null,
            'external_url' => $_POST['external_url'] ?? null,
            //'state' /* maybe "Advanced" options ...*/
        ]);
    }
    public function update($id)
    {
        global $http_code, $error_message, $target_uri;
        
        $item = Item::where('id', $_GET['id'])->firstOrFail();
        $project = Project::where('id', $item->project_id)->where('user_id', $_SESSION['user_id'])->firstOrFail(); // $item->project() maybe and authorization later via can() ...

        if (!isset($project)) {
            $http_code = 404;
            $error_message = 'Ownership of Object could not be verified';
        // } elseif (!Auth()->can($project, 'update')) {
        //     $http_code = 403;
        //     $error_message = 'You have no permission to access this Project';
        } else {
            $item->update([
                'name' => $_POST['name'] ?? $project->name,
                'description' => $_POST['description'] ?? $project->description,
                'type' => $_POST['type'] ?? $project->type,
                'state' => $_POST['state'] ?? $project->state,
                'external_url' => $_POST['external_url'] ?? $project->external_url,
            ]);

            $target_uri = '/dashboard?action=show&object=project&id=' . $project->id;
        }
    }

    public function delete($id)
    {
        // ToDO
    }
}

// index, create, store, show, edit, update

// ToDo: "validation"