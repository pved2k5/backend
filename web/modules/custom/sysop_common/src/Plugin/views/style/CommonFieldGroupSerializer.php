<?php


namespace Drupal\sysop_common\Plugin\views\style;

use Drupal\views\Plugin\views\field\FieldHandlerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * The style plugin for serialized output formats.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "common_field_group_serializer",
 *   title = @Translation("Group Serializer"),
 *   help = @Translation("Grouping Fields"),
 *   display_types = {"data"}
 * )
 */
class CommonFieldGroupSerializer extends \Drupal\rest\Plugin\views\style\Serializer
{
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('serializer'),
      $container->getParameter('serializer.formats'),
      $container->getParameter('serializer.format_providers')
    );
  }

  /**
   * Constructs a Plugin object.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, SerializerInterface $serializer, array $serializer_formats, array $serializer_format_providers) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer, $serializer_formats, $serializer_format_providers);
  }

  /**
   * {@inheritdoc}
   */
  public function render() {
    $rows = [];
    $fieldGroup = [];
    if (count($this->view->result) > 0) {
      $field_row_options = $this->view->rowPlugin->options['field_options'];
      foreach ($field_row_options as $key => $item) {
        $empty_value = '';
        $response_key = $key;
        if (isset($item['alias']) && !empty($item['alias'])) {
          $response_key = $item['alias'];
        }
        if ($this->view->field[$key] instanceof FieldHandlerInterface
          && isset($this->view->field[$key]->options['empty'])) {
          $empty_value = $this->view->field[$key]->options['empty'];
        }

        if ($empty_value == trim('group')) {
          $fieldGroup[$response_key] = 1;
        }
      }
      // If the Data Entity row plugin is used, this will be an array of entities
      // which will pass through Serializer to one of the registered Normalizers,
      // which will transform it to arrays/scalars. If the Data field row plugin
      // is used, $rows will not contain objects and will pass directly to the
      // Encoder.
      $parentSHA = '';
      $prevSHA = '';
      $counter = 0;
      foreach ($this->view->result as $row_index => $row) {
        $parentArray = [];
        $newRow = [];
        $this->view->row_index = $row_index;
        $viewRow = $this->view->rowPlugin->render($row);
        if (is_array($fieldGroup) && count($fieldGroup) > 0) {
          $parentArray = array_intersect_key($viewRow, $fieldGroup);
          if (is_array($parentArray) && count($parentArray) > 0) {
            $childArray = array_diff_key($viewRow, $fieldGroup);
            $newRow = $parentArray;
            $parentSHA = sha1(implode('', $parentArray));
            if ($parentSHA != $prevSHA) {
              if (!empty($prevSHA)) {
                $counter++;
              }
              $rows[$counter] = $parentArray;
            }
            $rows[$counter]['data'][] = $childArray;
          }
        }
        $prevSHA = $parentSHA;
      }
    }

    unset($this->view->row_index);

    // Get the content type configured in the display or fallback to the
    // default.
    if (empty($this->view->live_preview)) {
      $content_type = $this->displayHandler->getContentType();
    }
    else {
      $content_type = !empty($this->options['formats']) ? reset($this->options['formats']) : 'json';
    }
    return $this->serializer->serialize($rows, $content_type, ['views_style_plugin' => $this]);
  }
}
