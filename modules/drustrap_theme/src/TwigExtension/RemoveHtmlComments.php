<?php

namespace Drupal\drustrap_theme\TwigExtension;

use Drupal\Core\Cache\CacheableDependencyInterface;
use Drupal\Core\Render\AttachmentsInterface;
use Drupal\Core\Render\BubbleableMetadata;
use Drupal\Core\Render\RenderableInterface;
use Drupal\Core\Render\RendererInterface;

/**
 * Class RemoveHtmlComments.
 */
class RemoveHtmlComments extends \Twig_Extension {

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * Constructs \Drupal\Core\Template\TwigExtension.
   *
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer.
   */
  public function __construct(RendererInterface $renderer) {
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'drustrap_theme.twig.remove_html_comments';
  }

  public function getFilters() {
    return [
      new \Twig_SimpleFilter('remove_html_comments', [$this, 'removeHtmlComments']),
    ];
  }

  /**
   * @param $arg
   *
   * @return mixed
   * @throws \Exception
   */
  public function removeHtmlComments($arg) {
    $return = NULL;

    // Check for a numeric zero int or float.
    if ($arg === 0 || $arg === 0.0) {
      $return = 0;
    }

    // Optimize for scalars as it is likely they come from the escape filter.
    if (is_scalar($arg)) {
      $return = $arg;
    }

    if (is_string($arg)) {
      return preg_replace('/<!--(.|\s)*?-->/', '', $arg);
    }

    if (is_object($arg)) {
      $this->bubbleArgMetadata($arg);
      if ($arg instanceof RenderableInterface) {
        $arg = $arg->toRenderable();
      }
      elseif (method_exists($arg, '__toString')) {
        $return = (string) $arg;
      }
      // You can't throw exceptions in the magic PHP __toString() methods, see
      // http://php.net/manual/language.oop5.magic.php#object.tostring so
      // we also support a toString method.
      elseif (method_exists($arg, 'toString')) {
        $return = $arg->toString();
      }
      else {
        throw new \Exception('Object of type ' . get_class($arg) . ' cannot be printed.');
      }
    }

    if (is_array($arg)) {
      // This is a render array, with special simple cases already handled.
      // Early return if this element was pre-rendered (no need to re-render).
      if (isset($arg['#printed']) && $arg['#printed'] == TRUE && isset($arg['#markup']) && strlen($arg['#markup']) > 0) {
        $return = $arg['#markup'];
      }
      $arg['#printed'] = FALSE;
      $return = $this->renderer->render($arg);
    }

    return preg_replace('/<!--(.|\s)*?-->/', '', $return);
  }

  /**
   * Bubbles Twig template argument's cacheability & attachment metadata.
   *
   * For example: a generated link or generated URL object is passed as a Twig
   * template argument, and its bubbleable metadata must be bubbled.
   *
   * @see \Drupal\Core\GeneratedLink
   * @see \Drupal\Core\GeneratedUrl
   *
   * @param mixed $arg
   *   A Twig template argument that is about to be printed.
   *
   * @see \Drupal\Core\Theme\ThemeManager::render()
   * @see \Drupal\Core\Render\RendererInterface::render()
   */
  protected function bubbleArgMetadata($arg) {
    // If it's a renderable, then it'll be up to the generated render array it
    // returns to contain the necessary cacheability & attachment metadata. If
    // it doesn't implement CacheableDependencyInterface or AttachmentsInterface
    // then there is nothing to do here.
    if ($arg instanceof RenderableInterface || !($arg instanceof CacheableDependencyInterface || $arg instanceof AttachmentsInterface)) {
      return;
    }

    $arg_bubbleable = [];
    BubbleableMetadata::createFromObject($arg)
      ->applyTo($arg_bubbleable);

    $this->renderer->render($arg_bubbleable);
  }

}