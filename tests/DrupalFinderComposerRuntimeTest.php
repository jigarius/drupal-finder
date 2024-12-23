<?php

namespace DrupalFinder\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class DrupalFinderComposerRuntimeTest extends TestCase {

  protected const installFixtures = 'Execute "composer install-fixtures" first.';

  /**
   * @runInSeparateProcess
   */
  public function testDefault() {
    $basePath = realpath(__DIR__ . '/fixtures/default');
    $this->assertDirectoryExists($basePath . '/vendor', static::installFixtures);
    $this->assertDirectoryExists($basePath . '/web', static::installFixtures);

    $result = json_decode(require $basePath . '/drupal-finder.php', TRUE);
    $this->assertSame($result['getComposerRoot'], $basePath);
    $this->assertSame($result['getVendorDir'], $basePath . '/vendor');
    $this->assertSame($result['getDrupalRoot'], $basePath . '/web');
  }

  /**
   * @runInSeparateProcess
   */
  public function testDefaultComposerScript() {
    $basePath = realpath(__DIR__ . '/fixtures/default');
    $this->assertDirectoryExists($basePath . '/vendor', static::installFixtures);
    $this->assertDirectoryExists($basePath . '/web', static::installFixtures);

    $process = new Process(['composer', 'run-script', 'dump-drupal-finder'], $basePath);
    $process->run();

    // executes after the command finishes
    if (!$process->isSuccessful()) {
      throw new ProcessFailedException($process);
    }

    $result = json_decode($process->getOutput(), TRUE);
    $this->assertSame($result['getComposerRoot'], $basePath);
    $this->assertSame($result['getVendorDir'], $basePath . '/vendor');
    $this->assertSame($result['getDrupalRoot'], $basePath . '/web');
  }

  /**
   * @runInSeparateProcess
   */
  public function testCustomVendor() {
    $basePath = realpath(__DIR__ . '/fixtures/custom-vendor');
    $this->assertDirectoryExists($basePath . '/foo/bar', static::installFixtures);
    $this->assertDirectoryExists($basePath . '/foo/bar/drupal', static::installFixtures);

    $result = json_decode(require $basePath . '/drupal-finder.php', TRUE);
    $this->assertSame($result['getComposerRoot'], $basePath);
    $this->assertSame($result['getVendorDir'], $basePath . '/foo/bar');
    $this->assertSame($result['getDrupalRoot'], $basePath . '/foo/bar/drupal');
  }

  /**
   * @runInSeparateProcess
   */
  public function testSymlinkPackage() {
    $drupalPath = realpath(__DIR__ . '/fixtures/symlink-package/drupal');
    $this->assertDirectoryExists("$drupalPath/vendor", static::installFixtures);
    $this->assertDirectoryExists("$drupalPath/web", static::installFixtures);

    $packagePath = realpath(__DIR__ . '/fixtures/symlink-package/package');
    $this->assertDirectoryExists("$packagePath/vendor", static::installFixtures);

    $process = new Process(['composer', 'exec', 'symlink-package'], $drupalPath);
    $process->run();

    // executes after the command finishes
    if (!$process->isSuccessful()) {
      throw new ProcessFailedException($process);
    }

    $result = json_decode($process->getOutput(), TRUE);
    var_dump($result);
//    $this->assertSame($result['getComposerRoot'], $drupalPath);
//    $this->assertSame($result['getVendorDir'], $drupalPath . '/vendor');
//    $this->assertSame($result['getDrupalRoot'], $drupalPath . '/web');
  }

}
