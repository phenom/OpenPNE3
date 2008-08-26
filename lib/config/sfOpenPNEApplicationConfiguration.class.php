<?php

/**
 * sfOpenPNEApplicationConfiguration represents a configuration for OpenPNE application.
 *
 * @package    OpenPNE
 * @subpackage config
 * @author     Kousuke Ebihara <ebihara@tejimaya.net>
 */
abstract class sfOpenPNEApplicationConfiguration extends sfApplicationConfiguration
{
  /**
   * Gets directories where controller classes are stored for a given module.
   *
   * @param string $moduleName The module name
   *
   * @return array An array of directories
   */
  public function getControllerDirs($moduleName)
  {
    $dirs = array();

    if ($pluginDirs = glob(sfConfig::get('sf_plugins_dir').'/*/apps/'.sfConfig::get('sf_app').'/modules/'.$moduleName.'/actions'))
    {
      $dirs = array_merge($dirs, array_combine($pluginDirs, array_fill(0, count($pluginDirs), false))); // plugin applications
    }

    $dirs = array_merge($dirs, parent::getControllerDirs($moduleName));

    return $dirs;
  }

  /**
   * Gets directories where template files are stored for a given module.
   *
   * @param string $moduleName The module name
   *
   * @return array An array of directories
   */
  public function getTemplateDirs($moduleName)
  {
    $dirs = array();

    if ($pluginDirs = glob(sfConfig::get('sf_plugins_dir').'/*/apps/'.sfConfig::get('sf_app').'/modules/'.$moduleName.'/templates'))
    {
      $dirs = array_merge($dirs, $pluginDirs); // plugin applications
    }

    $dirs = array_merge($dirs, parent::getTemplateDirs($moduleName));

    return $dirs;
  }

  /**
   * Gets the i18n directories to use for a given module.
   *
   * @param string $moduleName The module name
   *
   * @return array An array of i18n directories
   */
  public function getI18NDirs($moduleName)
  {
    $dirs = array();

    if ($pluginDirs = glob(sfConfig::get('sf_plugins_dir').'/*/apps/'.sfConfig::get('sf_app').'/modules/'.$moduleName.'/i18n'))
    {
      $dirs = array_merge($dirs, $opPluginDirs); // plugin applications
    }

    $dirs = array_merge($dirs, parent::getI18NDirs($moduleName));

    return $dirs;
  }

  /**
   * Gets the configuration file paths for a given relative configuration path.
   *
   * @param string $configPath The configuration path
   *
   * @return array An array of paths
   */
  public function getConfigPaths($configPath)
  {
    $globalConfigPath = basename(dirname($configPath)).'/'.basename($configPath);
    $files = array();

    if ($pluginDirs = glob(sfConfig::get('sf_plugins_dir').'/*/'.sfConfig::get('sf_app').'/'.$configPath))
    {
      $files = array_merge($files, $pluginDirs); // plugin applications
    }

    $configs = array();
    foreach (array_unique($files) as $file)
    {
      if (is_readable($file))
      {
        $configs[] = $file;
      }
    }

    $configs = array_merge(parent::getConfigPaths($configPath), $configs);
    return $configs;
  }
}