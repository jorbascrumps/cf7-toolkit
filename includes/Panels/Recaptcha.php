<?php

namespace CF7_Toolkit\Panels;

class Recaptcha extends Panel
{
    protected $title = 'reCAPTCHA';

    public function init(): void
    {
        parent::init();

        add_action( 'wpcf7_contact_form', [ $this, 'enqueue_recaptcha_api' ] );
        add_action( 'wpcf7_form_hidden_fields', [ $this, 'add_hidden_field' ] );
        add_action( 'wpcf7_form_elements', [ $this, 'add_container_div' ] );
    }

    public function render($form): void
    {
        parent::render($form);

        $settings = get_option( "{$this->name}_{$form->id()}", [] );

        echo "<fieldset>
            <legend>Enter your reCAPTCHA credentials. <a href=\"https://developers.google.com/recaptcha/intro\" target=\"_blank\">Learn more</a></legend>
            <p class=\"description\">
                <label for=\"wpcf7-recaptcha-site-key\">Site Key<br>
                    <input
                        type=\"text\"
                        id=\"wpcf7-recaptcha-site-key\"
                        name=\"{$this->name}[recaptcha_site_key]\"
                        class=\"large-text\"
                        size=\"70\"
                        value=\"{$settings['recaptcha_site_key']}\"
                        data-config-field=\"messages.recaptcha_site_key\"
                    />
                </label>
            </p>
            <p class=\"description\">
                <label for=\"wpcf7-recaptcha-secret-key\">Secret Key<br>
                    <input
                        type=\"text\"
                        id=\"wpcf7-recaptcha-secret-key\"
                        name=\"{$this->name}[recaptcha_secret_key]\"
                        class=\"large-text\"
                        size=\"70\"
                        value=\"{$settings['recaptcha_secret_key']}\"
                        data-config-field=\"messages.recaptcha_secret_key\"
                    />
                </label>
            </p>
        </fieldset>";
    }

    public function enqueue_recaptcha_api($form): void
    {
        if ( is_admin() ) {
            return;
        }

        $settings = get_option( "cf7_toolkit_{$form->id()}", [] );

        if ( !isset( $settings['recaptcha_site_key'] ) ) {
            return;
        }

        $form_id_attr = sprintf( 'form-%s', $form->id() );
        add_filter('wpcf7_form_id_attr', static function () use ($form_id_attr) {
            return $form_id_attr;
        });

        wp_enqueue_script( 'cf7_toolkit_recaptcha', 'https://www.google.com/recaptcha/api.js', null, null, true );

        $script = "
            window.addEventListener('load', function () {
            console.log('plugin load');
                var form = document.getElementById('{$form_id_attr}');
                var recaptchaContainer = form.querySelector('.g-recaptcha-container');
                var recaptchaId = grecaptcha.render(recaptchaContainer, {
                    sitekey: '{$settings['recaptcha_site_key']}',
                    callback: onRecaptchaComplete.bind(form),
                    size: 'invisible',
                });

                function onRecaptchaComplete (token) {
                    var recaptchaEvent = new Event('recaptcha');
                    this.dispatchEvent(recaptchaEvent);
console.log('plugin recaptcha');
                    this.submit();
                }

form.addEventListener('recaptcha', function (e) {
console.log('recaptcha');
});

                form.addEventListener('submit', function (e) {
                if (e.defaultPrevented) {
                return;
                }
                    e.preventDefault();
console.log('plugin submit');
                    grecaptcha.execute(recaptchaId);
                }, true);
            });
        ";

        wp_add_inline_script( 'cf7_toolkit_recaptcha', $script );
    }

    public function add_hidden_field($fields): array
    {
        return array_merge( $fields, [
            'g-recaptcha-id' => '',
        ] );
    }

    public function add_container_div($form): string
    {
        return $form . "<div class=\"g-recaptcha-container\"></div>";
    }
}
