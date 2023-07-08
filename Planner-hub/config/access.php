<?php

return [
    // Company name used for footer
    'company_name' => 'Sail',
    // Application default role when login by social media
    'default_role' => 'user',

    // Configurations for the user
    'users' => [

        // The name of the super administrator role
        'admin_role' => 'super_admin',

        // The default role all new registered users get added to
        'default_role' => 'user',

        // Login username to be used by the controller.
        'username' => 'email',

        // super admin emails
        'super_admin_email' => 'admin@admin.com'
    ],

    // Configuration for roles
    'roles' => [
        // Whether a role must contain a permission or can be used standalone as a label
        'role_must_contain_permission' => true,
    ],

   
];
