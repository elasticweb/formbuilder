<?php if (!defined("BASEPATH")) exit("No direct script access allowed");

/**
 * Class Base_form.
 */
class Base_form {

  /**
   * CodeIgniter base object.
   *
   * @var object
   */
  protected $CI;

  /**
   * Custom data.
   *
   * @var array
   */
  protected $data = [];

  /**
   * Post data.
   *
   * @var array
   */
  protected $post = [];

  /**
   * Base form class.
   *
   * @var null|object
   */
  protected $base_class;

  /**
   * Form state. Variable send to validate, submit handler.
   *
   * @var array
   */
  public $form_state = [];

  /**
   * Form name.
   *
   * @var string
   */
  protected $form_name = '';

  public function __construct($data = [], $base_class = NULL, $form_name = '') {
    $this->CI =& get_instance();
    $this->setFormName($form_name);

    $this->data = $data;

    $this->post = $this->CI->input->post();
    $this->base_class = $base_class;

    // Execute submit.
    if (!empty($this->post) && $this->checkExecuteForm()) {
      foreach ($this->post as $key => $value) {
        $name = str_replace(['-', '_'], '', $key);

        $global_submit_method_name = 'globalSubmit';
        $submit_method_name = $name . 'Submit';
        $validate_method_name = $name . 'Validate';
        $process_submit = TRUE;

        if (method_exists($this, $validate_method_name)) {
          call_user_func_array([$this, $validate_method_name], [$this->post, &$this->form_state]);

          if ($this->CI->form_validation->hasFieldData()) {
            // Execute validate.
            $process_submit = $this->validateRun();
          }
        }

        if (method_exists($this, $global_submit_method_name)) {
          call_user_func_array([$this, $global_submit_method_name], [$this->post, &$this->form_state]);
        }

        if ($process_submit && method_exists($this, $submit_method_name)) {
          call_user_func_array([$this, $submit_method_name], [$this->post, &$this->form_state]);

          // If in $form_stage isset parameter "redirect". Need redirect.
          if (isset($this->form_state['redirect'])) {
            redirect(site_url($this->form_state['redirect']));
          }
        }
      }
    }
  }

  /**
   * Set current form name.
   *
   * @param string $name
   */
  public function setFormName($name) {
    $this->form_name = $name;
  }

  /**
   * Status execute form.
   *
   * Every form have hidden param with form name. For check,
   *
   * @return bool
   */
  public function checkExecuteForm() {
    if (isset($this->post['form_name'])) {
      if ($this->post['form_name'] !== $this->form_name) {
        return FALSE;
      }
    }

    return TRUE;
  }

  /**
   * Status validate.
   *
   * @return bool
   */
  protected function validateRun() {
    return $this->CI->form_validation->run($this->base_class);
  }

}
