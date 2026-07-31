<?php

namespace Controllers;

use Models\{Project, Item, Media};
use Ramsey\Uuid\Uuid;

class ItemController extends Controller
{

    public function store($id) 
    {        
        global $target_uri, $http_code, $error_message;
        $user = Auth()->user();
        $project = Project::where('id', $id)->firstOrFail();
        
        if (!($user->ownedProjects->contains($project) || $user->projects->contains($project))) {
            $http_code = 404;
            $error_message = 'No project with this ID found that you have access to.';
            exit;
        }

        
        // die('reached function'); // it did not – now it did(just didnt due to taht ONE CHARACTER)

        $item = Item::create([
            'id' => (string) Uuid::uuid4(),
            'project_id' => $project->id,
            'name' => $_POST['name'],
            'type' => $_POST['type'],
            'description' => $_POST['description'] ?? null,
            'external_url' => $_POST['external_url'] ?? null,
            //'state' /* maybe "Advanced" options ...*/
            'order_index' => $_POST['order_index'] ?? 0,
            'commit_id' => empty($project->repo_url) ? null : ($_POST['commit_id'] ?? null)
        ]);

        // if (isset($_POST['image'])) {
        //     // TODO: do this actually (reaserch what to do ...)
        //     $media = new Media()->createFromBase64($_POST['image'], attributes: ['parent_id' => $item->id, 'owner_type' => Item::class]); //ToDo: "morphing"
        // }
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            // Media::createFromUpload($_FILES['image'], attributes: ['parent_id' => $item->id, 'parent_type' => Item::class,]);
            Media::createFromUpload($_FILES['image'], attributes: ['parent_id' => $id, 'parent_type' => Item::class,]);
        }

        $target_uri = '/dashboard?action=show&object=project&id=' . $project->id;
    }

    public function update($id)
    // currently not in use bc. batchUpdate() on ProjectController ...
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
                // fn() not in use ... i still updated ...
                'name' => $_POST['name'] ?? $project->name,
                'description' => $_POST['description'] ?? $project->description,
                'type' => $_POST['type'] ?? $project->type,
                'state' => $_POST['state'] ?? ($project->state == 'new') ? 'wip' : $project->state, // currently not in use but why not do it anyway
                // 'external_url' => $_POST['external_url'] ?? $project->external_url,
                'external_url' => $_POST['external_url'] ?? null,
                'order_index' => $_POST['order_index'] ?? 0,
                'commit_id' => empty($project->repo_url) ? null : ($_POST['commit_id'] ?? null)
            ]);

            $target_uri = '/dashboard?action=show&object=project&id=' . $project->id;
            // $this->uri_from_item_id($id); // may be this in the future ...
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

            $target_uri = '/board?action=show&object=project&id=' . $project->id;
        }
    }

    // public function removeMedia($id)
    public function deleteMedia($id)
    {
        global $target_uri, $error_message, $http_code;

        $media = Media::where('id', $_GET['mid'])->where('parent_id', $id)->firstOrFail();

        if(!empty($media)) {
            $media->deleteFully();
        } else {
            $error_message = '...';
            // ToDo: here too
        }

        $this->uri_from_item_id($id);
    }

    public function uploadMedia($id)
    {
        global $target_uri, $error_message, $http_code;

        if (isset($_FILES['media_upload']) && $_FILES['media_upload']['error'] === UPLOAD_ERR_OK) {
            Media::createFromUpload($_FILES['media_upload'], attributes: ['parent_id' => $id, 'parent_type' => Item::class,]);
        }

        // $target_uri = $this->uri_from_item_id($id);
        $this->uri_from_item_id($id);
    }

    public function uri_from_item_id($id)
    {
        $item = Item::where('id', $id)->firstOrFail();
        $project = Project::where('id', $item->project_id)->where('user_id', $_SESSION['user_id'])->firstOrFail();

        // return '/board?action=show&object=project&id=' . $project->id;
        $target_uri =  '/board?action=show&object=project&id=' . $project->id;
        exit; // unsure: Do i have to run this ??
    }
}


// ToDO: itnternal function: uri_from_item_id