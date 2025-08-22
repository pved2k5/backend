<?php

namespace Drupal\sysop_common;

use Drupal\Core\Render\Markup;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class DefaultService.
 *
 * @package Drupal\module_name
 */
class TwigExtension extends AbstractExtension {

   /**
   * In this function we can declare the extension function
   */
  public function getFunctions() {
    return array(
      new TwigFunction('htmlSanitizer', array($this, 'htmlSanitizer'), array('is_safe' => array('html'))),
    );
  }
    public function getName()
    {
        return 'sysop_common.twig_extension';
    }
    public function htmlSanitizer($string)
    {
      return Markup::create($string);
      //return $render_string;
    }

}