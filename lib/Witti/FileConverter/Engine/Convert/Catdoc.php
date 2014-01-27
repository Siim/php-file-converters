<?php
namespace Witti\FileConverter\Engine\Convert;

use Witti\FileConverter\Engine\EngineBase;
use Witti\FileConverter\Util\Shell;
class Catdoc extends EngineBase {
  protected $cmd_options = array(
    array(
      'name' => 'b',
      'description' => 'Do not stop if file is broken.',
      'mode' => Shell::SHELL_ARG_BOOL_SGL,
      'default' => TRUE,
    ),
    array(
      'name' => 'w',
      'description' => 'Disable word wrapping',
      'mode' => Shell::SHELL_ARG_BOOL_SGL,
      'default' => FALSE,
    ),
    array(
      'name' => 'x',
      'description' => 'Output unknown unicode characters as \\xNNNN instead of ?.',
      'mode' => Shell::SHELL_ARG_BOOL_SGL,
      'default' => TRUE,
    ),
  );
  protected $cmd_source_safe = TRUE;

  public function getConvertFileShell($source, &$destination) {
    $shell = array(
      $this->cmd,
      Shell::argOptions($this->cmd_options, $this->configuration, 1),
      Shell::arg('f', Shell::SHELL_ARG_BASIC_SGL, ($this->conversion[1]
        === 'tex') ? 'tex' : 'ascii'),
      $source,
      Shell::arg('>', Shell::SHELL_SAFE),
      $destination,
    );
    return $shell;
  }

  public function getHelpInstallation($os, $os_version) {
    $help = "Maintained at http://www.wagner.pp.ru/~vitus/software/catdoc/\n";
    switch ($os) {
      case 'Ubuntu':
        $help .= "sudo apt-get install catdoc\n";
        return $help;
    }

    return parent::getHelpInstallation();
  }

  public function getVersionInfo() {
    $info = array(
      'catdoc' => $this->shell($this->cmd . " -V")
    );
    return $info;
  }

  public function isAvailable() {
    $this->cmd = $this->shellWhich('catdoc');
    return isset($this->cmd);
  }
}