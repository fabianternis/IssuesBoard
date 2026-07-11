<?php

namespace Controllers;

use Models\Project;
use Ramsey\Uiid\Uuid;

class ProjectController extends Controller
{
    function create() {
        $user = Auth()->user();

        $project = Project::create([
            'id' => (string) Uuid::uuid4(),
            'user_id' => Auth()->id(),
            'name' => $_POST['name'],
            'description' => $_POST['description'] ?? null,
            'repo_url' => $_POST['reposetory'] ?? null,
        ]);
    }
}