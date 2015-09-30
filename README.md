# formbuilder

Form builder for CodeIgniter. Used https://github.com/wallter/codeigniter_bootstrap_form_builder and HMVC.
You need to install HMVC for correct work with library.

1. Load Libraries
==============

```php
$this->load->helper('form');
$this->load->library('form_builder');
$this->load->library('form_loader');
```

2. Load form
==============

```php
$form = $this->form_loader->load('example', [
  'submit_value' => 'Test',
], 'example', $this);
```

3. Output form
==============

```php
$output = $this->form_builder->open_form([
  'action' => '',
  'method' => 'POST',
  'id' => 'test-form',
]);

$output .= $this->form_builder->build_form_horizontal($form);
$output .= $this->form_builder->close_form();

echo $output;
```
