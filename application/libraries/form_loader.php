<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

/**
 * Class Form_loader.
 */
class Form_loader {

  /**
   * CodeIgniter base object.
   *
   * @var object
   */
  protected $CI;

  /**
   * Form list run.
   *
   * form_name => form_array
   *
   * @var array
   */
  protected $form = [];

  /**
   * Form filter by module.
   *
   * module_name => form_name => form_array
   *
   * @var array
   */
  protected $forms = [];

  /**
   * Load base form class.
   */
  public function __construct() {
    $this->CI =& get_instance();

    $this->CI->load->library('base_form');
  }

  /**
   * Load form.
   *
   * @param string $form_name
   *   Form name.
   * @param array $arguments
   *   Form arguments.
   * @param bool $module_name
   *   Name module with form.
   * @param null|object $base_class
   *   Base class controller load form. For validation.
   *
   * @return array|mixed
   */
  public function load($form_name, $arguments = [], $module_name = FALSE, $base_class = NULL) {
    if (!$module_name) {
      $module_name = $this->CI->router->fetch_module();
    }

    extract($arguments);

    // Path to form file.
    $file_name = $module_name . '/forms/' . $form_name . '_form' . '.php';
    $config = $this->CI->config;

    foreach ($config->item('modules_locations') as $location) {
      if (file_exists($location . $file_name)) {
        if (!isset($this->forms[$module_name][$form_name])) {
          $form = require $location . $file_name;
          $this->forms[$module_name][$form_name] = $form;
        }

        $form_class_name = ucfirst($form_name) . '_Form';

        if (class_exists($form_class_name)) {
          $form = new $form_class_name($arguments, $base_class, $form_name);

          $this->form[$form_name] = $form;

          $form_fields = $form->getForm();

          return array_merge([[
            'id' => 'form_name',
            'value' => $form_name,
            'type' => 'hidden',
          ]], $form_fields);
        }

        return $this->form[$form_name] = $form;
      }
    }
  }

  /**
   * Get form state for form.
   *
   * @param string $form_name
   *   Form get.
   *
   * @return mixed
   */
  public function formState($form_name) {
    if (isset($this->form[$form_name]) && is_object($this->form[$form_name])) {
      return $this->form[$form_name]->form_state;
    }
  }

}
