<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Example_Form.
 */
class Example_Form extends Base_Form {

  /**
   * Form structure.
   *
   * @return array
   */
  public function getForm() {
    return [
      [
        'label' => 'Login',
        'id' => 'username',
        'name' => 'username',
      ],
      [
        'label' => 'Password',
        'id' => 'password',
        'type' => 'password',
      ],
      [
        'label' => $this->data['submit_value'],
        'id' => 'enter',
        'type' => 'submit',
      ],
    ];
  }

  /**
   * Validate callback.
   *
   * @param array $post
   *   Post params.
   * @param array $form_state
   *   Form state, send to submit handler.
   */
  protected function enterValidate($post, &$form_state) {
    $this->CI->form_validation->set_rules('username', 'Username', 'required');
    $this->CI->form_validation->set_rules('password', 'Password', 'required');

    // Send to submit callback redirect url.
    $form_state['redirect_url'] = 'user';
  }

  /**
   * Submit callback.
   *
   * @param array $post
   *   Post params.
   * @param array $form_state
   *   Form state, send to submit handler.
   */
  protected function enterSubmit($post, &$form_state) {
    // After success submit, redirect user to /user page.
    $form_state['redirect'] = $form_state['redirect_url'];
  }

}
