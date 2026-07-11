<?php

namespace Controllers;

use Models\Project;
use Ramsey\Uuid\Uuid;

class ProjectController extends Controller
{
    public function new(): void 
    {
        $target_action = '/dashboard?action=create&object=project';
        
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


    public function create() 
    {
        $project = Project::create([
            'id' => (string) Uuid::uuid4(),
            'user_id' => Auth()->id(),
            'name' => $_POST['name'],
            'description' => $_POST['description'] ?? null,
            'repo_url' => $_POST['repository'] ?? null,
        ]);

        // State transition post-execution
        header('Location: /dashboard?action=index&object=project');
        exit;
    }
}