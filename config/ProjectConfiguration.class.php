<?php
//require_once 'C:\wamp\bin\php\php5.3.0\PEAR\symfony\autoload\sfCoreAutoload.class.php';
require_once '/usr/share/php/symfony/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    // for compatibility / remove and enable only the plugins you want
    $this->enableAllPluginsExcept(array('sfDoctrinePlugin', 'sfCompat10Plugin'));
  }
}
