<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Design;
use App\Models\BomItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin User
        User::updateOrCreate(
            ['email' => 'admin@liveria.test'],
            [
                'name' => 'Liveria Admin',
                'password' => Hash::make('password123'),
            ],
        );

        // 2. Sample 3D Designs (SolidWorks, Blender, Hybrid)
        $designs = [
            [
                'code_id'       => 'BLD-001',
                'title'         => 'Cyberpunk Scout Drone MK-IV (.blend)',
                'workflow_type' => 'blender',
                'material'      => null,
                'mass_grams'    => null,
                'poly_count'    => '1.25M Triangles',
                'render_image'  => null,
                'glb_file'      => null,
                'bom'           => [
                    ['part_name' => 'Main Carbon Fiber Chassis', 'quantity' => 1, 'spec_note' => 'Baked Normal 4K UV Map'],
                    ['part_name' => 'Brushless Quad Rotors', 'quantity' => 4, 'spec_note' => 'Subdivision Surface level 2'],
                    ['part_name' => 'Optics Sensor Gimbal', 'quantity' => 1, 'spec_note' => 'Procedural Glass BSDF'],
                    ['part_name' => 'Telemetry Antenna Array', 'quantity' => 2, 'spec_note' => 'Rigged with Inverse Kinematics'],
                ],
            ],
            [
                'code_id'       => 'CAD-102',
                'title'         => 'Precision Planetary Gearbox 5:1',
                'workflow_type' => 'solidworks',
                'material'      => 'AISI 4140 Alloy Steel',
                'mass_grams'    => 1845.50,
                'poly_count'    => null,
                'render_image'  => null,
                'glb_file'      => null,
                'bom'           => [
                    ['part_name' => 'Sun Gear 24T Mod 1.5', 'quantity' => 1, 'spec_note' => 'Case hardened HRC 58-62'],
                    ['part_name' => 'Planet Gears 18T', 'quantity' => 3, 'spec_note' => 'Needle roller bearing fit'],
                    ['part_name' => 'Ring Gear 60T Internal', 'quantity' => 1, 'spec_note' => 'Toleransi ISO h7'],
                    ['part_name' => 'Carrier Plate Front & Rear', 'quantity' => 2, 'spec_note' => 'Anodized 6061-T6 Aluminum'],
                    ['part_name' => 'M5x16 Socket Head Cap Screws', 'quantity' => 8, 'spec_note' => 'Class 12.9 High Tensile'],
                ],
            ],
            [
                'code_id'       => 'BLD-002',
                'title'         => 'Suzanne Cyber-Mesh Mascot 3D',
                'workflow_type' => 'blender',
                'material'      => null,
                'mass_grams'    => null,
                'poly_count'    => '48.5K Polygons',
                'render_image'  => null,
                'glb_file'      => null,
                'bom'           => [
                    ['part_name' => 'Base Suzanne Quad Mesh', 'quantity' => 1, 'spec_note' => 'Native Blender Primordial Primitive'],
                    ['part_name' => 'Holographic Visor Node', 'quantity' => 1, 'spec_note' => 'Emission Shader with Bloom'],
                    ['part_name' => 'Cybernetic Ear Plates', 'quantity' => 2, 'spec_note' => 'Hard-surface bevel modifier'],
                ],
            ],
            [
                'code_id'       => 'HYB-201',
                'title'         => 'Bionic Robotic Arm Actuator Joint',
                'workflow_type' => 'hybrid',
                'material'      => 'Titanium Ti-6Al-4V Grade 5',
                'mass_grams'    => 620.00,
                'poly_count'    => '420K Polygons',
                'render_image'  => null,
                'glb_file'      => null,
                'bom'           => [
                    ['part_name' => 'Harmonic Drive Strain Wave Spline', 'quantity' => 1, 'spec_note' => 'SolidWorks FEA stress verified'],
                    ['part_name' => 'BLDC Frameless Stator & Rotor', 'quantity' => 1, 'spec_note' => 'Blender magnetic field render preview'],
                    ['part_name' => 'Absolute Optical Encoder Disk', 'quantity' => 1, 'spec_note' => '19-bit BiSS-C communication'],
                    ['part_name' => 'Outer Heat Sink Enclosure', 'quantity' => 1, 'spec_note' => 'Blender Geometry Nodes Voronoi Lattice'],
                ],
            ],
            [
                'code_id'       => 'CAD-103',
                'title'         => 'High-Pressure Hydraulic Cylinder 700 Bar',
                'workflow_type' => 'solidworks',
                'material'      => 'Carbon Steel St52-3 Honed Tube',
                'mass_grams'    => 4350.00,
                'poly_count'    => null,
                'render_image'  => null,
                'glb_file'      => null,
                'bom'           => [
                    ['part_name' => 'Cylinder Barrel 63mm Bore', 'quantity' => 1, 'spec_note' => 'H8 Honed surface Ra 0.2um'],
                    ['part_name' => 'Piston Rod 36mm Hard Chrome', 'quantity' => 1, 'spec_note' => 'Chrome layer 25 micron f7'],
                    ['part_name' => 'Polyurethane Step Seal Kit', 'quantity' => 1, 'spec_note' => 'Operating range -30°C to +110°C'],
                ],
            ],
            [
                'code_id'       => 'BLD-003',
                'title'         => 'Deep-Space Exploration Rover Chassis',
                'workflow_type' => 'blender',
                'material'      => null,
                'mass_grams'    => null,
                'poly_count'    => '2.1M Triangles',
                'render_image'  => null,
                'glb_file'      => null,
                'bom'           => [
                    ['part_name' => 'Rocker-Bogie Suspension Arm', 'quantity' => 2, 'spec_note' => 'Blender Rigged with constraints'],
                    ['part_name' => 'Titanium Compliant Mesh Wheels', 'quantity' => 6, 'spec_note' => 'Array modifier with curve deformation'],
                    ['part_name' => 'RTG Thermal Generator Cover', 'quantity' => 1, 'spec_note' => 'PBR Metal Roughness 4K'],
                ],
            ],
        ];

        foreach ($designs as $item) {
            $bomData = $item['bom'];
            unset($item['bom']);

            $design = Design::updateOrCreate(
                ['code_id' => $item['code_id']],
                $item
            );

            // Re-seed BOM items
            $design->bomItems()->delete();
            foreach ($bomData as $bom) {
                $design->bomItems()->create($bom);
            }
        }
    }
}
