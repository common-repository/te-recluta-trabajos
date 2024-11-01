<?php

class TeReclutaValidations
{

  private string $plugin_name;
  private string $version;
  private string $company_code;
  protected string $te_recluta_url = 'https://panel.terecluta.com/api/companies/company/company_code';

  public function __construct(string $plugin_name, string $version)
  {
    $this->plugin_name = $plugin_name;
    $this->version     = $version;

    $te_recluta_options = get_option( 'te_recluta_option_name' );

    $this->company_code = (isset($te_recluta_options['te_recluta_company_code'])) ? $te_recluta_options['te_recluta_company_code'] : "";

    $this->te_recluta_url = str_replace("company_code", $this->company_code, $this->te_recluta_url);
  }

  private function checkCompanyCode(): bool
  {
    if(empty($this->company_code)){ 
      return false;
    }

    $response = wp_remote_get(esc_url($this->te_recluta_url));
    $json = (array) json_decode(wp_remote_retrieve_body($response), true);

    if ($json && array_key_exists("status", $json)) {
      return $json['status'];
    }

    return false;
  }

  public function hasErrors(): bool
  {
    return ($this->checkCompanyCode()) ? false : true;
  }

  public function renderErrors(): void
  {
    $errors = [];

    if (!$this->checkCompanyCode()) {
      $errors = [...$errors, __('company_code no valido', 'te-recluta')];
    }

    if (count($errors) > 0) {
      echo "<ul class='te-recluta-alert te-recluta-alert--error'>";
      foreach ($errors as $error) {
        echo "<li>" . esc_html($error) . "</li>";
      }
      echo "</ul>";
    }
  }
}
