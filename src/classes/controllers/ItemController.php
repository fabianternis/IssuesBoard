<?php

namespace Controllers;

use Models\{Project, Item};
use Ramsey\Uuid\Uuid;

class ItemController extends Controller
{

    public function store($id) 
    {        
        global $target_uri;
        $project = Project::where('id', $id)->where('user_id', $_SESSION['user_id'])->firstOrFail();
        
        // die('reached function'); // it did not – now it did(just didnt due to this ONE CHARACTER)

        $item = Item::create([
            'id' => (string) Uuid::uuid4(),
            'project_id' => $project->id,
            'name' => $_POST['name'],
            'type' => $_POST['type'],
            'description' => $_POST['description'] ?? null,
            'external_url' => $_POST['external_url'] ?? null,
            //'state' /* maybe "Advanced" options ...*/
        ]);

         $target_uri = '/dashboard?action=show&object=project&id=' . $project->id;
    }
    public function update($id)
    {
        global $http_code, $error_message, $target_uri;
        
        $item = Item::where('id', $id)->firstOrFail();
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
        global $http_code, $error_message, $target_uri;

        $item = Item::where('id', $id)->firstOrFail();

        $project = Project::where('id', $item->project_id)->where('user_id', $_SESSION['user_id'])->firstOrFail();

        if (!isset($project)) {
            $http_code = 404;
            // $error_message = 'No matching item could not be found';
            $error_message = 'No matching Object could not be found';
        } elseif (!Auth()->can($project, 'update')) {
            // overwrites the can()
            $error_message = 'You have no permission to perform this Action';
        } else {
            $item->delete();
        }
    }
}