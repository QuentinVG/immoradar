<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\User;
use App\Models\VisitChecklistQuestion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MainScreensTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_homepage_and_seo_files_render(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Visite un bien sans te laisser embarquer')
            ->assertSee('/guides/checklist-visite-immobiliere')
            ->assertSee('SoftwareApplication');

        $this->get('/guides/checklist-visite-immobiliere')
            ->assertOk()
            ->assertSee('Checklist visite immobilière');

        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Sitemap:');

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('/guides/cout-reel-mensuel-immobilier')
            ->assertSee('<urlset', false);
    }

    public function test_demo_login_redirects_to_projects_when_demo_user_exists(): void
    {
        User::factory()->create([
            'email' => 'demo@immoradar.test',
            'password' => Hash::make('password'),
        ]);

        $this->post(route('login.demo'))
            ->assertRedirect(route('projects.index', absolute: false));

        $this->assertAuthenticated();
    }

    public function test_main_authenticated_screens_render(): void
    {
        VisitChecklistQuestion::create([
            'category' => 'Ressenti',
            'question' => 'Est-ce que je me projette dans ce logement ?',
            'weight' => 1,
            'is_active' => true,
        ]);

        /** @var Property $property */
        $property = Property::factory()->create();
        $user = $property->project->user;

        $this->actingAs($user)->get(route('projects.index'))->assertOk();
        $this->actingAs($user)->get(route('projects.show', $property->project))->assertOk();
        $this->actingAs($user)->get(route('projects.properties.index', $property->project))->assertOk();
        $this->actingAs($user)->get(route('projects.properties.show', [$property->project, $property]))->assertOk();
        $this->actingAs($user)->get(route('projects.properties.visit', [$property->project, $property]))->assertOk();
        $this->actingAs($user)->get(route('projects.compare', $property->project))->assertOk();
    }
}
