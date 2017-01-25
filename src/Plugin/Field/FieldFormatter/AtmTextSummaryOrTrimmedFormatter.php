<?php

namespace Drupal\AdTechMedia\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\text\Plugin\Field\FieldFormatter\TextSummaryOrTrimmedFormatter;
use Drupal\adtechmedia\Plugin\Field\FieldFormatter\AtmTextFormatterTrait;

/**
 * Altering TextSummaryOrTrimmedFormatter plugin by adding ATM text processing.
 */
class AtmTextSummaryOrTrimmedFormatter extends TextSummaryOrTrimmedFormatter {

  use AtmTextFormatterTrait;

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = parent::viewElements($items, $langcode);
    $entity = $items->getEntity();

    foreach ($elements as &$element) {
      $element['#text'] = self::atmContentProcess($element['#text'], $entity);
    }

    return $elements;
  }

}