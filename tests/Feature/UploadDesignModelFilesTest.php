<?php

namespace Tests\Feature;

use App\Models\Design;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadDesignModelFilesTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_upload_stl_and_blend_files(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create());

        $response = $this->post(route('designs.store'), [
            'code_id' => 'MODEL-001',
            'title' => 'Test Model',
            'workflow_type' => 'blender',
            'stl_file' => UploadedFile::fake()->createWithContent(
                'part.stl',
                "solid part\n facet normal 0 0 1\nendsolid part\n",
            ),
            'blend_file' => UploadedFile::fake()->createWithContent('scene.blend', 'BLENDER-v400'),
        ]);

        $response->assertRedirect(route('designs.index'));
        $design = Design::query()->where('code_id', 'MODEL-001')->firstOrFail();
        Storage::disk('public')->assertExists($design->stl_file);
        Storage::disk('public')->assertExists($design->blend_file);

        $this->get(route('designs.show', $design))
            ->assertOk()
            ->assertSee('STL Model')
            ->assertSee('Blender Source');

        $this->getJson('/api/designs')
            ->assertOk()
            ->assertJsonPath('0.stl_url', asset('storage/'.$design->stl_file))
            ->assertJsonPath('0.blend_url', asset('storage/'.$design->blend_file));
    }

    public function test_guest_must_log_in_before_uploading_model_files(): void
    {
        Storage::fake('public');

        $response = $this->post(route('designs.store'), [
            'code_id' => 'MODEL-003',
            'title' => 'Guest Upload',
            'workflow_type' => 'blender',
            'stl_file' => UploadedFile::fake()->createWithContent(
                'part.stl',
                "solid part\n facet normal 0 0 1\nendsolid part\n",
            ),
        ]);

        $response->assertRedirectToRoute('login');
        $this->assertDatabaseMissing('designs', ['code_id' => 'MODEL-003']);
        Storage::disk('public')->assertEmpty();
    }

    public function test_rejects_blend_files_without_a_blender_header(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create());

        $response = $this->post(route('designs.store'), [
            'code_id' => 'MODEL-002',
            'title' => 'Invalid Model',
            'workflow_type' => 'blender',
            'blend_file' => UploadedFile::fake()->createWithContent('scene.blend', 'not a Blender file'),
        ]);

        $response->assertSessionHasErrors('blend_file');
        $this->assertDatabaseMissing('designs', ['code_id' => 'MODEL-002']);
        Storage::disk('public')->assertEmpty();
    }
}
