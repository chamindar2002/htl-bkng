<?php
$wizard_config = [
    'id' => 'stepwizard',
    'button_previous' => 'Previous',
    'button_next' => 'Next',
    'button_save' => 'Save',
    'button_skip' => 'Skip',
    'steps' => [
        [
            'title' => 'Step 1',
            'icon' => 'glyphicon glyphicon-cloud-download',
            'content' => '<h3>Step 1</h3>This is step 1',
        ],
        [
            'title' => 'Step 2',
            'icon' => 'glyphicon glyphicon-cloud-upload',
            'content' => '<h3>Step 2</h3>This is step 2',
        ],
        [
            'title' => 'Step 3',
            'icon' => 'glyphicon glyphicon-transfer',
            'content' => '<h3>Step 3</h3>This is step 3',
        ],
    ],
    'complete_content' => "You are done!", // Optional final screen
    'start_step' => 2, // Optional to start with a specific step
];
?>


<?= \drsdre\wizardwidget\AutoloadExample::widget($wizard_config); ?>