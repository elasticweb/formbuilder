<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Welcome.
 */
class Welcome extends MY_Controller {

  public function __construct() {
    parent::__construct();
  }

  public function index() {
    $form = $this->form_loader->load('example', [
      'submit_value' => 'Test',
    ], 'example', $this);

    $output = $this->form_builder->open_form([
      'action' => '',
      'method' => 'POST',
      'id' => 'test-form',
    ]);

    $output .= $this->form_builder->build_form_horizontal($form);
    $output .= $this->form_builder->close_form();

    echo $output;
  }

}
