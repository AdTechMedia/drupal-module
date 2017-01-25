<?php

namespace Drupal\adtechmedia\Form;

use Drupal\adtechmedia\AtmClient;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * AdTechMedia Service configuration.
 */
class AdTechMediaConfigForm extends ConfigFormBase {

  /**
   * ATM Guzzle client.
   *
   * @var \Drupal\adtechmedia\AtmClient
   */
  private $atmClient;

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactoryInterface $config_factory, AtmClient $atm_client) {
    parent::__construct($config_factory);

    $this->atmClient = $atm_client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('atm.client')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'adtechmedia_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return ['adtechmedia.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('adtechmedia.settings');

    $form['general'] = array(
      '#type' => 'details',
      '#title' => $this->t('General configuration'),
      '#open' => TRUE,
    );

    $form['general']['api_key'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('API Key'),
      '#default_value' => $config->get('api_key'),
      '#required' => TRUE,
      '#title_display' => 'after',
      '#suffix' => '<span class="bar"></span>',
    );

    $form['general']['regenerate'] = array(
      '#type' => 'button',
      '#value' => $this->t('Regenerate'),
      '#attributes' => array(
        'class' => array('btn', 'regenerate'),
      ),
      '#ajax' => array(
        'callback' => array($this, 'regenerateApiKeyCallback'),
        'event' => 'click',
        'wrapper' => 'edit-api-key',
        'method' => 'replaceWith',
      ),
      // '#prefix' => '<i class="mdi mdi-autorenew"></i>',.
    );

    $form['general']['country'] = array(
      '#type' => 'select',
      '#title' => $this->t('Country'),
      '#options' => array(
        'usa' => $this->t('USA'),
        'md' => $this->t('Moldova'),
      ),
      '#default_value' => $config->get('country'),
    );

    $form['general']['revenue_model'] = array(
      '#type' => 'select',
      '#title' => $this->t('Revenue Model'),
      '#options' => array(
        'advertising' => $this->t('Advertising'),
        'micropayments' => $this->t('Micropayments'),
        'advertising_micropayments' => $this->t('Advertising & Micropayments'),
      ),
      '#default_value' => $config->get('revenue_model'),
      '#description' => $this->t('Select revenue model'),
    );

    $form['general']['email'] = array(
      '#type' => 'email',
      '#title' => $this->t('Email Address'),
      '#default_value' => $config->get('email'),
      '#title_display' => 'after',
    );

    $form['general']['activate'] = array(
      '#type' => 'button',
      '#value' => $this->t('Activate'),
      '#attributes' => array(
        'class' => array('btn', 'activate'),
      ),
    );

    $form['content'] = array(
      '#type' => 'details',
      '#title' => $this->t('Content configuration'),
      '#open' => TRUE,
    );

    $form['content']['content_pricing'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Content pricing'),
      '#default_value' => $config->get('content_pricing'),
      '#description' => $this->t('It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters'),
      '#placeholder' => $this->t('number'),
      '#title_display' => 'after',
    );

    $form['content']['content_currency'] = array(
      '#type' => 'select',
      '#title' => $this->t('Content currency'),
      '#options' => array(
        'usd' => $this->t('USD'),
        'mdl' => $this->t('MDL'),
      ),
      '#default_value' => $config->get('content_currency'),
      '#description' => $this->t('Select content currency.'),
    );

    $form['content']['content_paywall'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Content paywall'),
      '#default_value' => $config->get('content_paywall'),
      '#description' => $this->t('Remove paywall after.'),
      '#placeholder' => $this->t('number'),
      '#title_display' => 'after',
    );

    $form['content']['content_paywall_type'] = array(
      '#type' => 'select',
      '#title' => $this->t('Content paywall type'),
      '#options' => array(
        'transactions' => $this->t('Transactions'),
      ),
      '#default_value' => $config->get('content_paywall_type'),
      '#description' => $this->t('Select preview type.'),
    );

    $form['content']['content_preview'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Content preview'),
      '#default_value' => $config->get('content_preview'),
      '#description' => $this->t('Select how content should be displayed.'),
      '#placeholder' => $this->t('number'),
      '#title_display' => 'after',
    );

    $form['content']['content_preview_type'] = array(
      '#type' => 'select',
      '#title' => $this->t('Content preview type'),
      '#options' => array(
        'paragraph' => $this->t('Paragraph'),
      ),
      '#default_value' => $config->get('content_preview_type'),
      '#description' => $this->t('Select preview type.'),
    );

    $form['content']['locking_algorithm'] = array(
      '#type' => 'select',
      '#title' => $this->t('Content unlocking algorithm'),
      '#options' => array(
        'blur' => $this->t('Blur'),
        'scramble' => $this->t('Scramble'),
        'keywords' => $this->t('Keywords'),
        'blur_scramble' => $this->t('Blur & Scramble'),
      ),
      '#default_value' => $config->get('locking_algorithm'),
      '#description' => $this->t('How locked content should look.'),
    );

    $form['content']['dns_access'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('DNS access'),
      '#default_value' => $config->get('dns_access'),
      '#description' => $this->t('Route 53 AWS key.'),
      '#title_display' => 'after',
    );

    $form['content']['social_media'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Social media credentials'),
      '#default_value' => $config->get('social_media'),
      '#description' => $this->t('Select social media.'),
      '#title_display' => 'after',
    );

    $form['template'] = array(
      '#type' => 'details',
      '#title' => $this->t('Templates management'),
      '#open' => TRUE,
    );

    $form['template']['vertical_tabs'] = array(
      '#type' => 'vertical_tabs',
      '#default_tab' => 'edit-tab',
    );

    $templates = array(
      'pledge_view' => array(
        'title' => $this->t('Pledge View'),
      ),
      'confirm_view' => array(
        'title' => $this->t('Confirm View'),
      ),
      'refund_view' => array(
        'title' => $this->t('Refund View'),
      ),
      'pay_view' => array(
        'title' => $this->t('Pay View'),
      ),
      'login_view' => array(
        'title' => $this->t('Login View'),
      ),
    );

    foreach ($templates as $name => $template) {
      $form['template']['tab_' . $name] = array(
        '#type' => 'details',
        '#title' => $template['title'],
        '#group' => 'vertical_tabs',
      );

      $form['template']['tab_' . $name][$name] = array(
        '#type' => 'text_format',
        '#format' => $config->get($name)['format'],
        '#default_value' => $config->get($name)['value'],
        '#rows' => 14,
      );
    }

    $form['#attached']['library'][] = 'adtechmedia/adtechmedia.api';

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('adtechmedia.settings');

    foreach ($form_state->getValues() as $key => $value) {
      $config->set($key, $value);
    }
    $config->save();

    parent::submitForm($form, $form_state);
  }

  /**
   * Ajax callback to regenerate new api key.
   */
  public function regenerateApiKeyCallback($form, $form_state) {
    $request = new AtmClient();
    $atm_response = $request->regenerateApiKey();

    $response = new AjaxResponse();
    $response->addCommand(new InvokeCommand(
      '#edit-api-key',
      'val',
      [isset($atm_response['Key']) ? $atm_response['Key'] : '']
    ));

    return $response;
  }

}