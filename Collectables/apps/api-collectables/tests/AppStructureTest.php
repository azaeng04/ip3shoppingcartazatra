<?php
namespace ApiCollectables\Tests;

use PHPUnit\Framework\TestCase;

class AppStructureTest extends TestCase
{
    public function test_public_entrypoints_exist(): void
    {
        $root = realpath(__DIR__ . '/..');
        $files = [
            'index.php',
            'home.php',
            'products.php',
            'showcart.php',
            'order-processed.php',
            'view-orders.php',
            'login.php',
            'logout.php',
            'validate-user.php',
        ];

        foreach ($files as $file) {
            $this->assertFileExists($root . '/' . $file);
        }
    }

    public function test_shared_layout_files_exist(): void
    {
        $root = realpath(__DIR__ . '/..');
        $partials = [
            'header.php',
            'footer.php',
            'navigation.php',
            'main-content.php',
        ];

        foreach ($partials as $file) {
            $this->assertFileExists($root . '/' . $file);
        }
    }

    public function test_assets_are_present(): void
    {
        $root = realpath(__DIR__ . '/..');
        $paths = [
            $root . '/images/images/logo',
            $root . '/styles/style.css',
            $root . '/jscript/script.js',
        ];

        foreach ($paths as $path) {
            $this->assertFileExists($path);
        }
    }
}
