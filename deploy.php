<?php

namespace Deployer;

require 'recipe/laravel.php';


// Project name
set('application', 'FTCScouting');

// Project repository
set('repository', 'git@github.com:mrkirby153/ftcscoutingv2.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

set('writable_mode', 'chmod');

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);


// Hosts

inventory('hosts.yml');

// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

task('yarn', function () {
    run('cd {{release_path}} && yarn && yarn run production --progress false');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

after('deploy:vendors', 'yarn');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');
