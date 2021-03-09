<?php

namespace CF7_Toolkit\Panels;

class Panel {
    protected $name;
    protected $title = 'Panel';

    public function __construct()
    {
        $this->name = sprintf( 'wpcf7_toolkit_%s', strtolower( $this->title ) );
    }

    public function init(): void
    {
        add_action( 'wpcf7_editor_panels', [ $this, 'register' ] );
        add_action( 'wpcf7_after_save', [ $this, 'save' ] );
    }

    public function get($prop)
    {
        return $this->{$prop};
    }

    public function register($panels): array
    {
        $panels[$this->name] = [
            'title' => $this->title,
            'callback' => [ $this, 'render' ],
        ];

        return $panels;
    }

    public function render($form): void
    {
        echo "<h2>{$this->title}</h2>";
    }

    public function save($form): bool
    {
        if ( !isset( $_POST[$this->name] ) ) {
            return FALSE;
        }

        $option = sprintf( 'cf7_toolkit_%s', $form->id() );
        return update_option( $option, $_POST[$this->name] );
    }
}
