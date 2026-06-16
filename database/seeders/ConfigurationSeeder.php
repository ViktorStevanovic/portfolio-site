<?php

namespace Database\Seeders;

use App\Models\Technology;
use App\Models\TechnologyField;
use Illuminate\Database\Seeder;

class ConfigurationSeeder extends Seeder
{
    public function run(): void
    {
        $fields = [
            [
                'name' => 'Backend',
                'code' => 'backend',
                'order' => 1,
                'technologies' => [
                    ['name' => 'PHP', 'code' => 'php'],
                    ['name' => 'Laravel', 'code' => 'laravel'],
                    ['name' => 'Symfony', 'code' => 'symfony'],
                    ['name' => 'Python', 'code' => 'python'],
                    ['name' => 'Django', 'code' => 'django'],
                    ['name' => 'Flask', 'code' => 'flask'],
                    ['name' => 'Node.js', 'code' => 'nodejs'],
                    ['name' => 'Express.js', 'code' => 'expressjs'],
                    ['name' => 'NestJS', 'code' => 'nestjs'],
                    ['name' => 'Java', 'code' => 'java'],
                    ['name' => 'Spring Boot', 'code' => 'spring-boot'],
                    ['name' => 'Go', 'code' => 'go'],
                    ['name' => 'Rust', 'code' => 'rust'],
                    ['name' => 'Ruby', 'code' => 'ruby'],
                    ['name' => 'Ruby on Rails', 'code' => 'rails'],
                    ['name' => 'C#', 'code' => 'csharp'],
                    ['name' => '.NET', 'code' => 'dotnet'],
                    ['name' => 'FastAPI', 'code' => 'fastapi'],
                ],
            ],
            [
                'name' => 'Frontend',
                'code' => 'frontend',
                'order' => 2,
                'technologies' => [
                    ['name' => 'HTML', 'code' => 'html'],
                    ['name' => 'CSS', 'code' => 'css'],
                    ['name' => 'JavaScript', 'code' => 'javascript'],
                    ['name' => 'TypeScript', 'code' => 'typescript'],
                    ['name' => 'Vue.js', 'code' => 'vuejs'],
                    ['name' => 'React', 'code' => 'react'],
                    ['name' => 'Angular', 'code' => 'angular'],
                    ['name' => 'Svelte', 'code' => 'svelte'],
                    ['name' => 'Next.js', 'code' => 'nextjs'],
                    ['name' => 'Nuxt.js', 'code' => 'nuxtjs'],
                    ['name' => 'Tailwind CSS', 'code' => 'tailwindcss'],
                    ['name' => 'Bootstrap', 'code' => 'bootstrap'],
                    ['name' => 'Alpine.js', 'code' => 'alpinejs'],
                    ['name' => 'Livewire', 'code' => 'livewire'],
                    ['name' => 'Inertia.js', 'code' => 'inertiajs'],
                    ['name' => 'jQuery', 'code' => 'jquery'],
                    ['name' => 'SASS/SCSS', 'code' => 'sass'],
                ],
            ],
            [
                'name' => 'Database',
                'code' => 'database',
                'order' => 3,
                'technologies' => [
                    ['name' => 'MySQL', 'code' => 'mysql'],
                    ['name' => 'PostgreSQL', 'code' => 'postgresql'],
                    ['name' => 'SQLite', 'code' => 'sqlite'],
                    ['name' => 'MongoDB', 'code' => 'mongodb'],
                    ['name' => 'Redis', 'code' => 'redis'],
                    ['name' => 'Elasticsearch', 'code' => 'elasticsearch'],
                    ['name' => 'MariaDB', 'code' => 'mariadb'],
                    ['name' => 'SQL Server', 'code' => 'sqlserver'],
                    ['name' => 'DynamoDB', 'code' => 'dynamodb'],
                    ['name' => 'Firebase', 'code' => 'firebase'],
                ],
            ],
            [
                'name' => 'DevOps',
                'code' => 'devops',
                'order' => 4,
                'technologies' => [
                    ['name' => 'Docker', 'code' => 'docker'],
                    ['name' => 'Kubernetes', 'code' => 'kubernetes'],
                    ['name' => 'AWS', 'code' => 'aws'],
                    ['name' => 'Google Cloud', 'code' => 'gcp'],
                    ['name' => 'Azure', 'code' => 'azure'],
                    ['name' => 'DigitalOcean', 'code' => 'digitalocean'],
                    ['name' => 'Nginx', 'code' => 'nginx'],
                    ['name' => 'Apache', 'code' => 'apache'],
                    ['name' => 'GitHub Actions', 'code' => 'github-actions'],
                    ['name' => 'GitLab CI/CD', 'code' => 'gitlab-ci'],
                    ['name' => 'Jenkins', 'code' => 'jenkins'],
                    ['name' => 'Terraform', 'code' => 'terraform'],
                    ['name' => 'Ansible', 'code' => 'ansible'],
                    ['name' => 'Linux', 'code' => 'linux'],
                    ['name' => 'Laravel Forge', 'code' => 'laravel-forge'],
                ],
            ],
            [
                'name' => 'Mobile',
                'code' => 'mobile',
                'order' => 5,
                'technologies' => [
                    ['name' => 'React Native', 'code' => 'react-native'],
                    ['name' => 'Flutter', 'code' => 'flutter'],
                    ['name' => 'Swift', 'code' => 'swift'],
                    ['name' => 'Kotlin', 'code' => 'kotlin'],
                    ['name' => 'Dart', 'code' => 'dart'],
                    ['name' => 'Ionic', 'code' => 'ionic'],
                    ['name' => 'Capacitor', 'code' => 'capacitor'],
                ],
            ],
            [
                'name' => 'Game Development',
                'code' => 'game-dev',
                'order' => 6,
                'technologies' => [
                    ['name' => 'Unity', 'code' => 'unity'],
                    ['name' => 'Unreal Engine', 'code' => 'unreal-engine'],
                    ['name' => 'Godot', 'code' => 'godot'],
                    ['name' => 'Pygame', 'code' => 'pygame'],
                    ['name' => 'C++', 'code' => 'cpp'],
                    ['name' => 'Lua', 'code' => 'lua'],
                    ['name' => 'Phaser', 'code' => 'phaser'],
                ],
            ],
            [
                'name' => 'Testing',
                'code' => 'testing',
                'order' => 7,
                'technologies' => [
                    ['name' => 'PHPUnit', 'code' => 'phpunit'],
                    ['name' => 'Pest', 'code' => 'pest'],
                    ['name' => 'Jest', 'code' => 'jest'],
                    ['name' => 'Cypress', 'code' => 'cypress'],
                    ['name' => 'Playwright', 'code' => 'playwright'],
                    ['name' => 'Postman', 'code' => 'postman'],
                ],
            ],
            [
                'name' => 'Tools & Other',
                'code' => 'tools',
                'order' => 8,
                'technologies' => [
                    ['name' => 'Git', 'code' => 'git'],
                    ['name' => 'GitHub', 'code' => 'github'],
                    ['name' => 'GitLab', 'code' => 'gitlab'],
                    ['name' => 'Bitbucket', 'code' => 'bitbucket'],
                    ['name' => 'Jira', 'code' => 'jira'],
                    ['name' => 'Composer', 'code' => 'composer'],
                    ['name' => 'npm', 'code' => 'npm'],
                    ['name' => 'Webpack', 'code' => 'webpack'],
                    ['name' => 'Vite', 'code' => 'vite'],
                    ['name' => 'RabbitMQ', 'code' => 'rabbitmq'],
                    ['name' => 'GraphQL', 'code' => 'graphql'],
                    ['name' => 'REST API', 'code' => 'rest-api'],
                    ['name' => 'WebSockets', 'code' => 'websockets'],
                    ['name' => 'Swagger/OpenAPI', 'code' => 'swagger'],
                ],
            ],
        ];

        foreach ($fields as $fieldData) {
            $technologies = $fieldData['technologies'];
            unset($fieldData['technologies']);

            $field = TechnologyField::create($fieldData);

            foreach ($technologies as $index => $tech) {
                $field->technologies()->create([
                    ...$tech,
                    'order' => $index + 1,
                ]);
            }
        }
    }
}