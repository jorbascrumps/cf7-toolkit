<?php

namespace CF7_Toolkit\Panels;

class Recaptcha extends Panel
{
    protected $title = 'reCAPTCHA';

    public function __construct()
    {
        parent::__construct();

        add_action( 'wpcf7_contact_form', [ $this, 'enqueue_recaptcha_api' ] );
        add_action( 'wpcf7_form_hidden_fields', [ $this, 'add_hidden_field' ] );
        add_action( 'wpcf7_form_elements', [ $this, 'add_container_div' ] );
    }

    public function render($form): void
    {
        parent::render($form);

        $settings = get_option( "{$this->name}_{$form->id()}", [] );

        echo <<<RENDER
            <fieldset>
                <legend>Enter your reCAPTCHA credentials. <a href="https://developers.google.com/recaptcha/intro" target="_blank">Learn more</a></legend>
                <p class="description">
                    <label for="wpcf7-recaptcha-site-key">Site Key<br>
                        <input
                            type="text"
                            id="wpcf7-recaptcha-site-key"
                            name="{$this->name}[recaptcha_site_key]"
                            class="large-text"
                            size="70"
                            value="{$settings['recaptcha_site_key']}"
                            data-config-field="messages.recaptcha_site_key"
                        />
                    </label>
                </p>
                <p class="description">
                    <label for="wpcf7-recaptcha-secret-key">Secret Key<br>
                        <input
                            type="text"
                            id="wpcf7-recaptcha-secret-key"
                            name="{$this->name}[recaptcha_secret_key]"
                            class="large-text"
                            size="70"
                            value="{$settings['recaptcha_secret_key']}"
                            data-config-field="messages.recaptcha_secret_key"
                        />
                    </label>
                </p>
            </fieldset>
        RENDER;
    }

    public function enqueue_recaptcha_api($form): void
    {
        $settings = get_option( "cf7_toolkit_{$form->id()}", [] );

        if ( empty( $settings['recaptcha_site_key'] ) ) {
            return;
        }

        wp_enqueue_script( 'cf7_toolkit_recaptcha', 'https://www.google.com/recaptcha/api.js', null, null, true );
    }

    public function add_hidden_field($fields): array
    {
        return array_merge( $fields, [
            'g-recaptcha-id' => '',
        ] );
    }

    public function add_container_div($form): string
    {
        return $form . <<<RENDER
            <div class="g-recaptcha-container"></div>
        RENDER;
    }
}
