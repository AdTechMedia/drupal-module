<?php

namespace Drupal\AdTechMedia\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\text\Plugin\Field\FieldFormatter\TextSummaryOrTrimmedFormatter;

/**
 *
 */
class AtmTextDefaultFormatter extends TextSummaryOrTrimmedFormatter {

  use AtmTextFormatterTrait;

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = parent::viewElements($items, $langcode);

    foreach ($elements as $key => &$element) {
      $element['#text'] = self::atmContentLock($element['#text']);
    }

    return $elements;
  }

}
