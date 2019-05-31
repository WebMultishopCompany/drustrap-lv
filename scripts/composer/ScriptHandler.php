<?php

/**
 * @file
 * Contains \drustrap_lv\composer\ScriptHandler.
 */

namespace drustrap_lv\composer;

use Composer\Script\Event;
use DrupalFinder\DrupalFinder;
use Symfony\Component\Filesystem\Filesystem;

class ScriptHandler {
	
	public static function getFilesystem() {
		return new Filesystem();
	}
	
	public static function getDrupalFinder() {
		return new DrupalFinder();
	}
	
	public static function getDrupalRoot() {
		$drupalFinder = static::getDrupalFinder();
		$drupalFinder->locateRoot(getcwd());
		return $drupalFinder->getDrupalRoot();
	}

  public static function createRequiredFiles(Event $event) {
    $fs = static::getFilesystem();
    $drupalRoot = static::getDrupalRoot();

    $dirs = [
      'log',
      'web/libraries',
    ];

    foreach ($dirs as $dir) {
      if (!$fs->exists($drupalRoot . '/'. $dir)) {
        $fs->mkdir($drupalRoot . '/'. $dir);
        $fs->touch($drupalRoot . '/'. $dir . '/.gitkeep');
      }
    }
  }

  public static function overwriteConfigFiles(Event $event) {
    $fs = static::getFilesystem();
    $drupalRoot = static::getDrupalRoot();

    $dirs = [
      'log',
      'web/libraries',
    ];

    foreach ($dirs as $dir) {
      if (!$fs->exists($drupalRoot . '/'. $dir)) {
        $fs->mkdir($drupalRoot . '/'. $dir);
        $fs->touch($drupalRoot . '/'. $dir . '/.gitkeep');
      }
    }
  }
}
